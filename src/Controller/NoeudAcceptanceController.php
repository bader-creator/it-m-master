<?php

namespace App\Controller;

use App\Entity\Choix;
use App\Entity\CommentaireSite;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Fiche;
use App\Entity\ImageSite;
use App\Entity\NoeudAcceptance;
use App\Entity\Question;
use App\Entity\Reponse;
use App\Entity\TracabilityReponse;
use App\Form\NoeudAcceptanceType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Validator\Constraints\Length;

class NoeudAcceptanceController extends AbstractController {


    use  FirebaseNotifTrait {
        pushNotificationFireBase as private;
    }


    /**
     * @Route("/noeudAcceptance", name="noeudAcceptance_page")
     */
    public function index(Request $request,PaginatorInterface $paginator){

        $em = $this->getDoctrine()->getManager();

        $search_input = !empty($request->get('search_input')) ? $request->get('search_input'):'';

        $page = !empty($request->get('page'))?$request->query->get('page'):1;

        $selected_row = !empty($request->get('selected_row'))?$request->query->get('selected_row'):'';

        $query = $em->getRepository(NoeudAcceptance::class)->findBySearchInput($search_input);
       
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', $page), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('noeudAcceptance/index.html.twig',[
            'current_menu'=>'noeudAcceptance',
            'noeudAcceptances'=>$pagination,
            'search_input'=>$search_input,
            'selected_row'=>$selected_row,
            'page'=>$page,
            'action'=>'' // add or edit
        ]);
    }


    

    /**
     * @Route("/add_noeudAcceptance", name="add_noeudAcceptance_page")
     */
    public function add(Request $request,PaginatorInterface $paginator,ValidatorInterface $validator){
        $current_user = $this->getUser();

        $em = $this->getDoctrine()->getManager();

        $noeudAcceptance = new NoeudAcceptance();

        $form = $this->createForm(NoeudAcceptanceType::class,$noeudAcceptance);

        $date = $noeudAcceptance->setDateCreation(new \DateTime());
        
        $noeudAcceptance->setUserCreator($current_user);
        
        $form->handleRequest($request);

        $is_obligatoire = true;

         /*------ start check site notBlank ---*/

         if($form->get('typeAcceptance')->getData() == 0 ){

            $site = $form->get('site')->getData();

            $siteConstraint = new Assert\NotBlank();

            $errorSite = $validator->validate($site,$siteConstraint);

            if (count($errorSite) > 0) {

                $is_obligatoire=true;

                $errorMessage = new FormError($errorSite[0]->getMessage());

                $form->get('site')->addError($errorMessage);

            }

        }
         /*------ end check notBlank ---*/

         /*------ start check armoire notBlank ---*/

         if($form->get('typeAcceptance')->getData() == 1 ){

            $armoire = $form->get('armoire')->getData();

            $armoireConstraint = new Assert\NotBlank();

            $errorArmoire = $validator->validate($armoire,$armoireConstraint);

            if (count($errorArmoire) > 0) {

                $is_obligatoire=true;

                $errorMessage = new FormError($errorArmoire[0]->getMessage());

                $form->get('armoire')->addError($errorMessage);

            }

        }
         /*------ end check notBlank ---*/

        if($form->isSubmitted() && $form->isValid() &&  $is_obligatoire){

            $noeudAcceptance->setStatut(0);
        
            $em->persist($noeudAcceptance);
            $em->flush();
            
            $this->addFlash('success', 'Un nouvel enregistrement est ajouté avec succès');

            //   // notification to auditeur
              $data['title']='New Noeud Acceptance';
              $data['message']='You Have a new Noeud Acceptance to do whose Site name is : '.$noeudAcceptance->getSite()->getName();
              $data['token']=$noeudAcceptance->getUserDestinator()->getDeviseToken();
  
             $this->addFlash('success', json_encode($this->pushNotificationFireBase($data)));
  
            //   // notification to chef du projet
                $data['title']='New BTS Acceptance';
                $data['message']='There is a new BTS Acceptance whose Site name is : '.$noeudAcceptance->getSite()->getName();
                $data['token']=$noeudAcceptance->getUserCreator()->getDeviseToken();
  
                $this->addFlash('success', json_encode($this->pushNotificationFireBase($data)));

            
            return $this->redirectToRoute('noeudAcceptance_page',array('selected_row'=>$noeudAcceptance->getId()));
 
        }

        $query = $em->getRepository(NoeudAcceptance::class)->findBySearchInput($search_input="");


        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('noeudAcceptance/index.html.twig',[
            'current_menu'=>'noeudAcceptance',
            'form'=>$form->createView(),
            'noeudAcceptances'=>$pagination,
            'search_input'=>'',
            'selected_row'=>'',
            'page'=>1,
            'id_selected_fiche'=>'',
            'action'=>'add'
        ]);

    }


    /**
     * @Route("/edit_noeudAcceptance/{id}", name="edit_noeudAcceptance_page")
     */
    public function update(Request $request,PaginatorInterface $paginator,$id,ValidatorInterface $validator){

        $em = $this->getDoctrine()->getManager();

        $search_input = !empty($request->get('search_input')) ? $request->get('search_input'):'';

        $page = !empty($request->get('page'))?$request->query->get('page'):1;

        $noeudAcceptance = $em->getRepository(NoeudAcceptance::class)->find($id);

        if(!$noeudAcceptance){

            $this->addFlash('warning', 'Aucun enregistrement correspond à votre requête !');

            return $this->redirectToRoute('noeudAcceptance_page');
        }

        $form = $this->createForm(NoeudAcceptanceType::class,$noeudAcceptance);


        $is_obligatoire = true;

        /*------ start check site notBlank ---*/

        if($form->get('typeAcceptance')->getData() == 0 ){

           $site = $form->get('site')->getData();

           $siteConstraint = new Assert\NotBlank();

           $errorSite = $validator->validate($site,$siteConstraint);

           if (count($errorSite) > 0) {

               $is_obligatoire=true;

               $errorMessage = new FormError($errorSite[0]->getMessage());

               $form->get('site')->addError($errorMessage);

           }

       }
        /*------ end check notBlank ---*/

        /*------ start check armoire notBlank ---*/

        if($form->get('typeAcceptance')->getData() == 1 ){

           $armoire = $form->get('armoire')->getData();

           $armoireConstraint = new Assert\NotBlank();

           $errorArmoire = $validator->validate($armoire,$armoireConstraint);

           if (count($errorArmoire) > 0) {

               $is_obligatoire=true;

               $errorMessage = new FormError($errorArmoire[0]->getMessage());

               $form->get('armoire')->addError($errorMessage);

           }

       }
        /*------ end check notBlank ---*/

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() && $is_obligatoire){

            $em->flush();

            $this->addFlash('success', 'Cet enregistrement est modifié avec succès');
            
            return $this->redirectToRoute('noeudAcceptance_page',array('selected_row'=>$noeudAcceptance->getId(),'search_input'=>$search_input,'page'=>$page));
        }

        $query = $em->getRepository(NoeudAcceptance::class)->findBySearchInput($search_input);

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', $page), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('noeudAcceptance/index.html.twig',[
            'current_menu'=>'noeudAcceptance',
            'form'=>$form->createView(),
            'noeudAcceptances'=>$pagination,
            'search_input'=>$search_input,
            'selected_row'=>$noeudAcceptance->getId(),
            'page'=>$page,
            'action'=>'edit',
            'id_selected_fiche'=>$noeudAcceptance->getFiche()->getId(),
        ]);

    }

    /**
     * @Route("/delete_noeudAcceptance",name="delete_noeudAcceptance")
     */
    public function delete(Request $request){

        $id = $request->get('id');

        $em = $this->getDoctrine()->getManager();

        $noeudAcceptance = $em->getRepository(NoeudAcceptance::class)->find($id);

        try{ 
            
        $em->remove($noeudAcceptance);

        $em->flush();

       $this->addFlash('success', 'Cet enregistrement est supprimé avec succés');

        return new Response(json_encode(array()),
            200,
            array('Content-Type'=>'application/json'));

        } catch (ForeignKeyConstraintViolationException $e) {

            $msg = "Cet enregistrement a des services connectés, il ne peut donc pas être supprimé !";

            return new Response(json_encode(array('msg'=>$msg)),
            201,
            array('Content-Type'=>'application/json'));
        }

    }

    /**
     * @Route("/change_type",name="change_type")
     */
    public function changeGroupe(Request $request){

        $em = $this->getDoctrine()->getManager();

        $id_type = $request->get('id_type');

        $data = $em->getRepository(Fiche::class)->findFicheByType($id_type);

        return new Response(json_encode(array(
           'data'=>$data)),
        200,
        array('Content-Type'=>'application/json'));


    }

   /**
     * @Route("/show_noeudAcceptance/{id}", name="show_noeudAcceptance_page")
     */
    public function show(Request $request,PaginatorInterface $paginator,$id){



       
        $em = $this->getDoctrine()->getManager();

        $search_input = !empty($request->get('search_input')) ? $request->get('search_input'):'';

        $page = !empty($request->get('page'))?$request->query->get('page'):1;
       
        $selected_row = !empty($request->get('selected_row'))?$request->query->get('selected_row'):'';

        $query = $em->getRepository(Reponse::class)->findBySearchInput($search_input);

       $noeudAcceptance = $em->getRepository(NoeudAcceptance::class)->find($id);
   

       if(!$noeudAcceptance){  

        $this->addFlash('warning', 'Aucun enregistrement correspond à votre requête !');

        return $this->redirectToRoute('noeudAcceptance_page');
        }
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', $page), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('noeudAcceptance/show.html.twig',[
            'current_menu'=>'reponse',
            'reponses'=>$pagination,
            'noeudAcceptance'=> $noeudAcceptance,
          
          
        ]);
    }

    /*---------------------------------------------------------------------------------------*/
    /*                                                                                       */
    /*                       fonction get list option sof response                          */
    /*                                                                                       */
    /*---------------------------------------------------------------------------------------*/
   
   /**
     * @Route("/list_choix", name="list_choix_page")
     */
    public function getListOptionsAction(Request $request)
    {
        
        if($request->isXmlHttpRequest()) {
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository(Choix::class)->findChoix();
        return new JsonResponse(array( 'data'=>$query));
        
    
        }
    }


    /*---------------------------------------------------------------------------------------*/
    /*                       fonction update  response                                      */
    /*---------------------------------------------------------------------------------------*/

     /**
     * @Route("/edit_reponse", name="edit_reponse_page")
     */
    public function updateReponse(Request $request,PaginatorInterface $paginator){
        if($request->isXmlHttpRequest()) {
            $id_ques = $request->get('id_question');
            $id_choix = $request->get('id_choix');
            $em = $this->getDoctrine()->getManager();
            $question = $em->getRepository(Reponse::class)->findOneBy(['question' =>$id_ques]);
            
            $choix = $em->getRepository(Choix::class)->find($id_choix);

            $question->setChoixReponse($choix);
            /*--- debut tracability ----*/
            $current_user = $this->getUser();
            $tracabilityReponse = new TracabilityReponse();
            $rep = $tracabilityReponse->setReponse(  $question  );
            $date = $tracabilityReponse->setDateCreation(new \DateTime());
            $valeur = $tracabilityReponse->setValeur( $choix->getLabel());
            $user = $tracabilityReponse->setUserCreator($current_user);
       
            $em->persist($tracabilityReponse);
            $msg = "Votre réponse a été modifié avec succés!";
            /*--- end tracability ----*/
            $em->flush();
         
            return new Response(json_encode(array(
                'question' =>$question,
                'id_question'=>$id_ques,
                'id_choix'=>  $id_choix,
                'value'=>$choix->getLabel(),
                'reponse'=>$rep,
                'date' =>$date,
                'valeur' =>$valeur,
                'user' => $user,
                'msg' => $msg

                )
            ),
            200,
            array('Content-Type'=>'application/json'));
        
           
    }
    }


     /*---------------------------------------------------------------------------------------*/
    /*                       fonction update  response                                      */
    /*---------------------------------------------------------------------------------------*/

     /**
     * @Route("/edit_reponse_input_page", name="edit_reponse_input_page")
     */
    public function updateReponseInput(Request $request,PaginatorInterface $paginator){
        if($request->isXmlHttpRequest()) {

            $id_ques = $request->get('id_question');
            $reponse = $request->get('reponse');
            $em = $this->getDoctrine()->getManager();
            $question = $em->getRepository(Reponse::class)->findOneBy(['question' =>$id_ques]);
            $question->setReponse($reponse);

            /*--- debut tracability ----*/
            $current_user = $this->getUser();
            $tracabilityReponse = new TracabilityReponse();
            $rep = $tracabilityReponse->setReponse(  $question  );
            $date = $tracabilityReponse->setDateCreation(new \DateTime());
            $valeur = $tracabilityReponse->setValeur($reponse);
            $user = $tracabilityReponse->setUserCreator($current_user);
            $em->persist($tracabilityReponse);
          
            /*---  end tracability ----*/
            $msg = "Votre réponse a été modifié avec succés!";
            $em->flush();
            return new Response(json_encode(array(
                'value'=> $reponse ,
                'question' =>$question,
                'id_question'=>$id_ques,
                'reponse'=>  $reponse,
                'value'=>$reponse,
                'reponse'=>$rep,
                'date' =>$date,
                'valeur' =>$valeur,
                'user' => $user,
                'msg' =>$msg
                )
            ),
            200,
            array('Content-Type'=>'application/json'));
            
        }

    }


      /**
     * @Route("/list_reponse", name="list_reponse_page")
     */
    public function getListReponsesAction(Request $request)
    {
        
        if($request->isXmlHttpRequest()) {
        $id_ques = $request->get('id_question');
        // dump(  $id_ques);
        // die;
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository(TracabilityReponse::class)->findReponses(  $id_ques );
        // dump($query);
        // die;
        return new JsonResponse(array( 'data'=>$query,'id_question'=>$id_ques));
        
    
        }
    }


      /**
     * @Route("/list_commentaire", name="list_commentaire_page")
     */
    public function getListCommnetairesAction(Request $request)
    {
        
        if($request->isXmlHttpRequest()) {
        $id_ques = $request->get('id_question');
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository(CommentaireSite::class)->findCommentaires($id_ques);
        return new JsonResponse(array( 'data'=>$query,'id_question'=>$id_ques));
        
    
        }
    }
    /**
     * @Route("/delete_commentaire",name="delete_commentaire")
     */
    public function deleteCommentaire(Request $request)
    {

        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $commentaire = $em->getRepository(CommentaireSite::class)->find($id);
        
        try {

            $em->remove($commentaire);
            $em->flush();

            $msg = "Cet enregistrement est supprimé avec succés !";
            return new Response(
                json_encode(array('msg' => $msg)),
                200,
                array('Content-Type' => 'application/json')
            );

        } catch (ForeignKeyConstraintViolationException $e) {

            $msg = "Cet enregistrement a des services connectés, il ne peut donc pas être supprimé !";

            return new Response(
                json_encode(array('msg' => $msg)),
                201,
                array('Content-Type' => 'application/json')
            );
        }
    }


    /**
     * @Route("/add_commentaire", name="add_commentaire_page")
     */
    public function addCommentaire(Request $request)
    {
        if($request->isXmlHttpRequest()) {
        $comment = $request->get('comment');
        $id_qestion = $request->get('id_question');
        $em = $this->getDoctrine()->getManager();
        $reponse = $em->getRepository(Reponse::class)->findOneBy(['question' =>$id_qestion]);
        $current_user = $this->getUser();
        $commentaire_site = new CommentaireSite();
        $commentaire_site->setComment( $comment);
        $date = $commentaire_site->setDateCommentaire(new \DateTime());
        $reponses= $commentaire_site->setReponse( $reponse );
        $user=  $commentaire_site->setUserCreator( $current_user);
        $em->persist($commentaire_site);
        $msg = "Votre commentaire a été ajouté avec succés!";
        $em->flush();
        
        return new Response(json_encode(array(
            'date'=> $date->getDateCommentaire() ,
            'comment'=>$comment,
            'id_question'=> $id_qestion,
            'lastName'=> $user->getUserCreator()->getLastName(),
            'firstName'=>$user->getUserCreator()->getFirstName(),
            'path'=>$user->getUserCreator()->getPath(),
            'reponse'=> $commentaire_site->getId(),
            'msg' => $msg

            
            ) ),
        200,
        array('Content-Type'=>'application/json'));
    }
    }


     /**
     * @Route("/list_photo", name="list_photo_page")
     */
    public function getListPhotosAction(Request $request)
    {
        
        if($request->isXmlHttpRequest()) {
        $id_ques = $request->get('id_question');
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository(ImageSite::class)->findPhotos($id_ques);
        return new JsonResponse(array( 'data'=>$query,'id_question'=>$id_ques));
        
    
        }
    }

     /**
     * @Route("/delete_photo",name="delete_photo")
     */
    public function deletePhoto(Request $request)
    {

        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $photo = $em->getRepository(ImageSite::class)->find($id);
        
        try {

            $em->remove($photo);
            $em->flush();

            $msg = "Cet enregistrement est supprimé avec succés !";
            return new Response(
                json_encode(array('msg' => $msg)),
                200,
                array('Content-Type' => 'application/json')
            );

        } catch (ForeignKeyConstraintViolationException $e) {

            $msg = "Cet enregistrement a des services connectés, il ne peut donc pas être supprimé !";

            return new Response(
                json_encode(array('msg' => $msg)),
                201,
                array('Content-Type' => 'application/json')
            );
        }
    }



     /**
     * @Route("/add_photo/{id}", name="add_photo_page")
     */
    public function addPhoto(Request $request,$id){

        $em = $this->getDoctrine()->getManager();
        $file = $request->files->get('input-file-upload');
        
        $filename = uniqid().".".$file->getClientOriginalExtension();
        $path = $this->getParameter('photo_directory');
        $file->move($path,$filename); // move the file to a path
        $mime_type_image = strtolower(mime_content_type($path.'/'.$filename));
        if($mime_type_image!="image/png" && $mime_type_image!="image/jpg"  && $mime_type_image!="image/jpeg") // format non valide
        {
            unlink($path.'/'.$filename);
            return new Response(json_encode(array()),
                201,
                array('Content-Type'=>'application/json'));
        }
        $reponse = $em->getRepository(Reponse::class)->findOneBy(['question' =>$id]);
        $current_user = $this->getUser();
        $imageSite = new ImageSite();
        $image = $imageSite->setPath($filename);
        $date = $imageSite->setDateInsertion(new \DateTime());
        $reponses= $imageSite->setReponse( $reponse );
        $user=  $imageSite->setUserCreator( $current_user);

        $em->persist($imageSite); 
         $msg = "Votre photo a été ajouté avec succés!";
        $em->flush();
        return new Response(json_encode(array('msg' => $msg)),
            200,
            array('Content-Type'=>'application/json'));

    }
/**
 * @Route("/export_pdf/{id}", name="export_pdf_page")
 */
    public function generatePdf($id)
    {
        $em = $this->getDoctrine()->getManager();
        $noeudAcceptance = $em->getRepository(NoeudAcceptance::class)->find($id);
        $fiche = $noeudAcceptance->getFiche()->getLabel();
        $siteId =$noeudAcceptance->getSite()->getSiteId();
        $siteName =$noeudAcceptance->getSite()->getName();
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        // Retrieve the HTML generated in our twig file
        
        $html = $this->renderView('noeudAcceptance/pdf.html.twig', [
            'title' => "Audit site",
            'fiche' =>$fiche,
            'siteName'=>$siteName,
            'siteId'=>$siteId,
            'noeudAcceptance'=>$noeudAcceptance
        ]);
        
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
          
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

         // Render the HTML as PDF
         $dompdf->render();
         
         // Output the generated PDF to Browser (force download)
         $dompdf->stream("auditSites.pdf", [
            "Attachment" => false,
          
        ]);
    }

    /**
 * @Route("/export_csv/{id}", name="export_csv_page")
 */
    public function generateCsvAction(Request $request, $id){

        $em = $this->getDoctrine()->getManager();
        $noeudAcceptance = $em->getRepository(NoeudAcceptance::class)->find($id);
        $siteName =$noeudAcceptance->getSite()->getName();
        $items =   $noeudAcceptance->getFiche()->getItems();
              

        /*------------------------------------------------------------------*/

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Famille');
        $sheet->setCellValue('B1', 'Criticité');
        $sheet->setCellValue('C1', 'Réponses');
        $sheet->setCellValue('D1', 'Commentaires');


         /*--------------------- debut style excel ---------------------*/

 

         $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(100);
         $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(15);
         $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(15);
         $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(60);

 
         /*------ debut remplissage data -----*/

         $ind=1;

         foreach ($items as $item){
            $item_name = $item->getLabel();
            $item_id = $item->getId();

            $ind++;

            $sheet->setCellValue('A'.$ind,$item_name);
            $sheet->setCellValue('B'.$ind, 'Criticité');
            $sheet->setCellValue('C'.$ind, 'Réponses');
            $sheet->setCellValue('D'.$ind, 'Commentaires');

            foreach($item->getSousItems() as $sousItem){
                $name_sousItem = $sousItem->getLabel();
                $sousItem_id = $sousItem->getId();
             
              
                    
                    $ind++;

                    $sheet->setCellValue('A'.$ind,  $name_sousItem );
                    $sheet->setCellValue('B'.$ind, '');
                    $sheet->setCellValue('C'.$ind, '');
                    $sheet->setCellValue('D'.$ind, '');

                    foreach($sousItem->getQuestion() as $question){
                        $name_question = $question->getLabel();
                        foreach($question->getReponses() as $reponse){
                            $ind++;
                            $sheet->setCellValue('A'.$ind,$name_question);
                            $sheet->setCellValue('B'.$ind, $question->getCriticityValue());
                            if($question->getType()== 0)
                            $sheet->setCellValue('C'.$ind, $reponse->getChoixReponse()->getLabel());
                            else if ($question->getType()== 1)
                            $sheet->setCellValue('C'.$ind, $reponse->getReponse());
                               $list_comment ="";
                               for($i=0;$i<$reponse->getCommentaireSites()->count() ;$i++){  
                               $list_comment .= $reponse->getCommentaireSites()[$i]->getComment().'.  '; 
                               
                                
                               } // end list_commentaire
                               $sheet->setCellValue('D'.$ind,  $list_comment);
                            // 
                } // end $reponse
             }// end $question

            } // end $list_sous_item

        }// end $list_item

        /*------ end remplissage data -----*/


         $cell_st =[

             'font' =>['bold' => true],
             'alignment' =>['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
             'borders' => array(
                 'top' => array(
                     'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                 ),
             ),
             'fill' => array(
                 'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                 'rotation' => 90,
                 'startColor' => array(
                     'argb' => '32ADFF',
                 ),
                 'endColor' => array(
                     'argb' => '00FFFF',
                 ),
             )

            ];
            $spreadsheet->getActiveSheet()->getStyle('A1:D1')->applyFromArray($cell_st);
         /*------------------- end style excel --------------------------------*/
         $path =  $this->getParameter('csv_directory');
         $title = 'Audit-Site-'.$siteName.'.xlsx';
         $writer = new Xlsx($spreadsheet);
         $writer->save($path.$title);
         return $this->file($path.$title, $title, ResponseHeaderBag::DISPOSITION_INLINE);
    }

 

      /**
 * @Route("/export_csv_global", name="export_csv_global_page")
 */
public function generateCsvGlobaleAction(){


    $em = $this->getDoctrine()->getManager();
    $noeudAcceptance = $em->getRepository(NoeudAcceptance::class)->findAll();
 /*-----------------------------------------------------------------*/
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setCellValue('A1', 'Nom site');
    $sheet->setCellValue('B1', 'Nom fiche');
    $sheet->setCellValue('C1', 'Famille');
    $sheet->setCellValue('D1', 'Sous Item');
    $sheet->setCellValue('E1', 'Question');
    $sheet->setCellValue('F1', 'Criticité');
    $sheet->setCellValue('G1', 'Réponses');
    $sheet->setCellValue('H1', 'Commentaires');

     /*--------------------- debut style excel ---------------------*/

     $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(30);
     $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(40);
     $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(40);
     $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(50);
     $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(100);
     $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(20);
     $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(40);
     $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(80);

  
     /*------ debut remplissage data -----*/
     $ind =1 ;
     $count = count($noeudAcceptance);
     for($i=0;$i< $count ;$i++){ 
         $sites = $noeudAcceptance[$i]->getSite();  
         $fiches = $noeudAcceptance[$i]->getFiche(); 
         $items =    $fiches ->getItems();
         foreach ($items as $item){
              $item_name = $item->getLabel();
              foreach($item->getSousItems() as $sousItem){
                $name_sousItem = $sousItem->getLabel();
                foreach($sousItem->getQuestion() as $question){
                    $name_question = $question->getLabel();
                    $criticity_question = $question->getCriticityValue();
                    foreach($question->getReponses() as $reponse){
                        $ind++; 
                        $sheet->setCellValue('A'.$ind,$sites->getName() );
                        $sheet->setCellValue('B'.$ind, $fiches->getLabel());
                        $sheet->setCellValue('C'.$ind,  $item_name);
                        $sheet->setCellValue('D'.$ind, $name_sousItem );
                        $sheet->setCellValue('E'.$ind, $name_question );
                        $sheet->setCellValue('F'.$ind, $criticity_question);
                        if($question->getType()== 0)
                        $sheet->setCellValue('G'.$ind, $reponse->getChoixReponse()->getLabel());
                        else if ($question->getType()== 1)
                        $sheet->setCellValue('G'.$ind, $reponse->getReponse());
                        $list_comment ="";
                        foreach($reponse->getCommentaireSites() as $comment){
                        $list_comment .= $comment->getComment().'.  '; 
                         
                        }
                         $sheet->setCellValue('H'.$ind,  $list_comment);
                    
                       
                       
                 }
               }
             }
        }
 }


    /*------ end remplissage data -----*/


     $cell_st =[

         'font' =>['bold' => true],
         'alignment' =>['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
         'borders' => array(
             'top' => array(
                 'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
             ),
         ),
         'fill' => array(
             'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
             'rotation' => 90,
             'startColor' => array(
                 'argb' => '32ADFF',
             ),
             'endColor' => array(
                 'argb' => '00FFFF',
             ),
         )

        ];
        $spreadsheet->getActiveSheet()->getStyle('A1:H1')->applyFromArray($cell_st);
     /*------------------- end style excel --------------------------------*/
     $path =  $this->getParameter('csv_directory');
     $title = 'Audit-Site-globale.xlsx';
     $writer = new Xlsx($spreadsheet);
     $writer->save($path.$title);
     return $this->file($path.$title, $title, ResponseHeaderBag::DISPOSITION_INLINE);


          
}

 

} // fin noeudAcceptanceController
