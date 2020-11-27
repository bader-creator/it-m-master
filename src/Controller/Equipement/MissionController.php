<?php

namespace App\Controller\Equipement;

use App\Entity\Affectation;
use App\Entity\Materiel;
use App\Entity\Mission;
use App\Entity\Stock;
use App\Entity\Tracability;
use App\Form\AffectationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\MissionType;
use App\Form\MaterielType;
use App\Service\Mailer;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Knp\Component\Pager\PaginatorInterface;
use Proxies\__CG__\App\Entity\Materiel as EntityMateriel;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
class MissionController extends AbstractController {


    /**
     * @Route("/mission", name="mission_page")
     */
    public function index(Request $request,PaginatorInterface $paginator){

        $em = $this->getDoctrine()->getManager();

        $search_input = !empty($request->get('search_input')) ? $request->get('search_input'):'';

        $page = !empty($request->get('page'))?$request->query->get('page'):1;

        $selected_row = !empty($request->get('selected_row'))?$request->query->get('selected_row'):'';

        $query = $em->getRepository(Mission::class)->findBySearchInput($search_input);
        $stock=$em->getRepository(Stock::class)->findStock();
      
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', $page), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('Equipement/mission/index.html.twig',[
            'current_menu'=>'mission',
            'missions'=>$pagination,
            'search_input'=>$search_input,
            'selected_row'=>$selected_row,
            'page'=>$page,
            'action'=>'' ,
            'test'=>false,
            'stock' => $stock
        ]);
    }


    /**
     * @Route("/add_mission", name="add_mission_page")
     */
    public function add(Request $request,PaginatorInterface $paginator){

        $em = $this->getDoctrine()->getManager();
        $current_user = $this->getUser();
        $mission = new Mission();
        $mission->setDateMission(new \DateTime());
        $mission->setUserCreator($current_user);
        $form = $this->createForm(MissionType::class,$mission);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($mission);
            $em->flush();

            $this->addFlash('success', 'Un nouvel enregistrement est ajouté avec succès');
            
            return $this->redirectToRoute('mission_page',array('selected_row'=>$mission->getId()));
        }

        $query = $em->getRepository(Mission::class)->findBySearchInput($search_input="");

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('Equipement/mission/index.html.twig',[
            'current_menu'=>'mission',
            'form'=>$form->createView(),
            'missions'=>$pagination,
            'search_input'=>'',
            'selected_row'=>'',
            'page'=>1,
            'action'=>'add',
            'test'=>true
        ]);

    }

     /**
     * @Route("/mat/{id}", name="mat_page")
     */
    public function show(Request $request,PaginatorInterface $paginator,$id){
        $em = $this->getDoctrine()->getManager();

        $search_input = !empty($request->get('search_input')) ? $request->get('search_input'):'';
    
        $page = !empty($request->get('page'))?$request->query->get('page'):1;
        $mission = $em->getRepository(Mission::class)->find($id);
        $material=new Materiel();
        $stock=$em->getRepository(Stock::class)->findStock();

        dump($stock);
        
        if(!$material){
            $this->addFlash('warning', 'Aucun enregistrement correspond à votre requête !');
    
            return $this->redirectToRoute('mission_page');
        }
    
       
        $query = $em->getRepository(Mission::class)->findBySearchInput($search_input);
    
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', $page), /*page number*/
            10 /*limit per page*/
        );
    
        return $this->render('Equipement/mission/index.html.twig',[
            'current_menu'=>'mission',
            'missions'=>$pagination,
            'search_input'=>$search_input,
            'selected_row'=>$material->getId(),
            'page'=>$page,
            'action'=>'addMateriel',
            'mission'=>$mission,
            'test'=>true,
            'stock' => $stock
        ]);
    
    
    }


    /**
    * @Route("/add_materiel", name="add_materiel_page")
    */
   public function addMateriel(Request $request,PaginatorInterface $paginator,ValidatorInterface $validator){

    $em = $this->getDoctrine()->getManager();

   

    if($request->isXmlHttpRequest()) { // pour vérifier la présence d'une requete Ajax
        $id = $request->get('id');
        $mission =  $em->getRepository(Mission::class)->find($id);
        $nomProduits = $request->get('produits');
        $quantiteSorties = $request->get('item_quantity');

        
        foreach($nomProduits as $key => $nomProduit){
            $material=new Materiel();
            $material->setMission($mission);
            $produit = $this->getDoctrine()->getRepository(Stock::class)->find($nomProduit);
            $material->setStock($produit);
            $material->setQuantiteSortie($quantiteSorties[$key]);
            $mission->addMateriel($material);

       

            if(!$material){
                $this->addFlash('warning', 'Aucun enregistrement correspond à votre requête !');
                return $this->redirectToRoute('mission_page');
            }
     
        
            $em->persist($material);
        }

  
        $em->flush();
        
      $this->addFlash('success', 'Un nouvel enregistrement est ajouté avec succès');
      return new JsonResponse(@compact('id', 'nomProduits', 'quantiteSorties'));
    }

    $query = $em->getRepository(Materiel::class)->findBySearchInput($search_input="");

    /*
hello
    */

    $pagination = $paginator->paginate(
        $query, /* query NOT result */
        $request->query->getInt('page', 1), /*page number*/
        10 /*limit per page*/
    );

    return $this->render('FicheAcceptance/fiche/index.html.twig',[
        'current_menu'=>'mission',
        // 'form'=>$form->createView(),
        'missions'=>$pagination,
        'search_input'=>'',
        'selected_row'=>'',
        'page'=>1,
        'action'=>'add'
    ]);
   }



  /**
    * @Route("/add_affectation/{id}", name="add_affectation_page")
    */
    public function addAffectation(Request $request,PaginatorInterface $paginator,$id){

        $em = $this->getDoctrine()->getManager();
    
        $search_input = !empty($request->get('search_input')) ? $request->get('search_input'):'';
    
        $page = !empty($request->get('page'))?$request->query->get('page'):1;
        $mission = $em->getRepository(Mission::class)->find($id);
        $affectation=new Affectation();
        $affectation->setLivreur($affectation->getLivreur());
        $affectation->setMagasinier($affectation->getMagasinier());
        $affectation->setMetteurService($affectation->getMetteurService());
        $affectation->setInstallateur($affectation->getInstallateur());
        $affectation->setAcceptateur($affectation->getAcceptateur());
        $affectation->setMission($affectation->getMission());
        $mission->addAffectation($affectation);
        if(!$affectation){
            $this->addFlash('warning', 'Aucun enregistrement correspond à votre requête !');
    
            return $this->redirectToRoute('mission_page');
        }
    
        $form = $this->createForm(AffectationType::class,$affectation);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            if( $affectation->getLivreur() === $affectation->getMetteurService()|| $affectation->getLivreur() === $affectation->getAcceptateur() || $affectation->getAcceptateur() === $affectation->getMetteurService() || $affectation->getInstallateur() === $affectation->getMetteurService() || $affectation->getInstallateur() === $affectation->getAcceptateur()  ){
                $this->addFlash('warning', 'ces deux utilisateurs doivent être non identique');
                return $this->redirectToRoute('add_affectation_page',['id'=>$mission->getId()]);
            }
            $affectations =$em->getRepository(Affectation::class)->finddByMission($mission);
            // dump($affectation);
            // die;
            $size =0;
            foreach($affectations as $affectation){
                   $size ++;
            }
            if ($size > 0){
                $this->addFlash('warning', 'Vous n\'avez pas le droit d\'ajouter d\'autre affectations!');
    
                return $this->redirectToRoute('mission_page');
    
            }
            $em->persist($affectation);
            $em->flush();
    
            $this->addFlash('success', 'Cet enregistrement est ajouté avec succès');
            
            return $this->redirectToRoute('mission_page');
        }
    
        $query = $em->getRepository(Mission::class)->findBySearchInput($search_input);
    
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', $page), /*page number*/
            10 /*limit per page*/
        );
    
        return $this->render('Equipement/mission/index.html.twig',[
            'current_menu'=>'mission',
            'form'=>$form->createView(),
            'missions'=>$pagination,
            'search_input'=>$search_input,
            'selected_row'=>$affectation->getId(),
            'page'=>$page,
            'action'=>'addAffectation',
            'test' =>true
           
           
        ]);
    
    
       }
    

     /**
     * @Route("/edit_affectation/{id}/{id_mission}", name="edit_affectation_page")
     */
    public function updateAffectation(Request $request,PaginatorInterface $paginator,$id,$id_mission){

        $em = $this->getDoctrine()->getManager();

        $search_input = !empty($request->get('search_input')) ? $request->get('search_input'):'';
        $page = !empty($request->get('page'))?$request->query->get('page'):1;
        $affectation = $em->getRepository(Affectation::class)->find($id);
        $mission = $em->getRepository(Mission::class)->find($id_mission);
        if(!$affectation){

            $this->addFlash('warning', 'Aucun enregistrement correspond à votre requête !');

            return $this->redirectToRoute('mission_page');
        }

        $form = $this->createForm(AffectationType::class,$affectation);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $em->flush();

            $this->addFlash('success', 'Cet enregistrement est modifié avec succès');
            
            return $this->redirectToRoute('mission_page',array('selected_row'=>$mission->getId(),'search_input'=>$search_input,'page'=>$page));
        }

        $query = $em->getRepository(Mission::class)->findBySearchInput($search_input);

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', $page), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('Equipement/mission/index.html.twig',[
            'current_menu'=>'mission',
            'form'=>$form->createView(),
            'missions'=>$pagination,
            'search_input'=>$search_input,
            'selected_row'=>$affectation->getId(),
            'page'=>$page,
            'action'=>'editAffectation',
            'test'=>false
        

        ]);

    }



    /**
     * @Route("/edit_mission/{id}", name="edit_mission_page")
     */
    public function update(Request $request,PaginatorInterface $paginator,$id){

        $em = $this->getDoctrine()->getManager();

        $search_input = !empty($request->get('search_input')) ? $request->get('search_input'):'';

        $page = !empty($request->get('page'))?$request->query->get('page'):1;

        $mission = $em->getRepository(Mission::class)->find($id);

        if(!$mission){

            $this->addFlash('warning', 'Aucun enregistrement correspond à votre requête !');

            return $this->redirectToRoute('mission_page');
        }

        $form = $this->createForm(MissionType::class,$mission);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $em->flush();

            $this->addFlash('success', 'Cet enregistrement est modifié avec succès');
            
            return $this->redirectToRoute('mission_page',array('selected_row'=>$mission->getId(),'search_input'=>$search_input,'page'=>$page));
        }

        $query = $em->getRepository(Mission::class)->findBySearchInput($search_input);

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', $page), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('Equipement/mission/index.html.twig',[
            'current_menu'=>'mission',
            'form'=>$form->createView(),
            'missions'=>$pagination,
            'search_input'=>$search_input,
            'selected_row'=>$mission->getId(),
            'page'=>$page,
            'action'=>'edit',
            'test'=>false
        ]);

    }


    
   /**
     * @Route("/edit_materiel/{id}/{id_mission}", name="edit_materiel_page")
     */
    public function updateMateriel(Request $request,PaginatorInterface $paginator,$id,$id_mission){

        $em = $this->getDoctrine()->getManager();

        $search_input = !empty($request->get('search_input')) ? $request->get('search_input'):'';
        $page = !empty($request->get('page'))?$request->query->get('page'):1;
        $materiel = $em->getRepository(Materiel::class)->find($id);
        $mission = $em->getRepository(Mission::class)->find($id_mission);
        if(!$materiel){

            $this->addFlash('warning', 'Aucun enregistrement correspond à votre requête !');

            return $this->redirectToRoute('mission_page');
        }

        $form = $this->createForm(MaterielType::class,$materiel);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            if( $materiel->getQuantiteSortie() <= $materiel->getStock()->getQuantiteGenerale()){
                $materiel->getStock()->setQuantiteSortie( $materiel->getStock()->getQuantiteSortie() - $materiel->getQuantiteSortie()) ;
                $materiel->getStock()->setQuantiteRestant($materiel->getStock()->getQuantiteGenerale() - $materiel->getStock()->getQuantiteSortie())   ;
                $em->persist($materiel);
                $current_user = $this->getUser();
                $tracibility = new Tracability();
                $tracibility->setDateAction(new \DateTime());
                $tracibility->setUser($current_user);
                $tracibility->setQuantiteFinale( $materiel->getStock()->getQuantiteRestant());
                $tracibility->setStock( $materiel->getStock());
                $tracibility->setTypeAction('Modification');

                $em->persist($tracibility);


            $em->flush();
            $this->addFlash('success', 'Cet enregistrement est modifié avec succès');
             } else{
                $this->addFlash('warning', 'Vous devez saisir une quantité inférieur à celle existante !'); 
            }
            return $this->redirectToRoute('mission_page',array('selected_row'=>$mission->getId(),'search_input'=>$search_input,'page'=>$page));
        }

        $query = $em->getRepository(Mission::class)->findBySearchInput($search_input);

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', $page), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('Equipement/mission/index.html.twig',[
            'current_menu'=>'mission',
            'form'=>$form->createView(),
            'missions'=>$pagination,
            'search_input'=>$search_input,
            'selected_row'=>$materiel->getId(),
            'page'=>$page,
            'action'=>'editMateriel',
            'test'=>false
            
        

        ]);

    }


    /**
     * @Route("/delete_mission",name="delete_mission")
     */
    public function delete(Request $request){

        $id = $request->get('id');

        $em = $this->getDoctrine()->getManager();

        $mission = $em->getRepository(Mission::class)->find($id);

        try{ 
            
        $em->remove($mission);

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
     * @Route("/delete_materiel",name="delete_materiel")
     */
    public function deleteMateriel(Request $request){

        $id = $request->get('id');

        $em = $this->getDoctrine()->getManager();
      
        $material = $em->getRepository(Materiel::class)->find($id);
       
        $material->getStock()->setQuantiteSortie( $material->getStock()->getQuantiteSortie() - $material->getQuantiteSortie()) ;
        $material->getStock()->setQuantiteRestant($material->getStock()->getQuantiteGenerale() - $material->getStock()->getQuantiteSortie())   ;
         
         $current_user = $this->getUser();
         $tracibility = new Tracability();
         $tracibility->setDateAction(new \DateTime());
         $tracibility->setUser($current_user);
         $tracibility->setQuantiteFinale( $material->getStock()->getQuantiteRestant());
         $tracibility->setStock( $material->getStock());
         $tracibility->setTypeAction('Modification');

         $em->persist($tracibility);
         $material->setMission(null)  ;
         $material->setStock(null);
        try{ 

         $em->remove($material);
         
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
     * @Route("/delete_affectation",name="delete_affectation")
     */
    public function deleteAffectation(Request $request){

        $id = $request->get('id');

        $em = $this->getDoctrine()->getManager();

        $affectation = $em->getRepository(Affectation::class)->find($id);
        $affectation->setMission(null)  ;
        $affectation->setLivreur(null);
        $affectation->setInstallateur(null);
        $affectation->setMetteurService(null);
        $affectation->setAcceptateur(null);
        $affectation->setMagasinier(null);
        try{ 
            
        $em->remove($affectation);

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



} // fin missionController
