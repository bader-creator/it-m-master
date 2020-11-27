<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Fiche;
use App\Entity\NoeudAcceptance;
use App\Entity\Reponse;
use App\Form\NoeudAcceptanceType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

class NoeudAcceptanceController extends AbstractController {


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

        $noeudAcceptance->setDateCreation(new \DateTime());
        
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
            'action'=>'edit'
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
    //    dump($noeudAcceptance->getFiche()->getItems());
    //    die;

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
            'search_input'=>$search_input,
            'selected_row'=>$selected_row,
            'page'=>$page,
            'noeudAcceptance'=> $noeudAcceptance,
            
        ]);
    }


} // fin noeudAcceptanceController
