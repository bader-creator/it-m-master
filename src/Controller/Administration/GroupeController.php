<?php

namespace App\Controller\Administration;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Groupe;
use App\Form\GroupeType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Knp\Component\Pager\PaginatorInterface;

class GroupeController extends AbstractController {


    /**
     * @Route("/groupe", name="groupe_page")
     */
    public function index(Request $request,PaginatorInterface $paginator){

        $em = $this->getDoctrine()->getManager();

        $search_input = !empty($request->get('search_input')) ? $request->get('search_input'):'';

        $page = !empty($request->get('page'))?$request->query->get('page'):1;

        $selected_row = !empty($request->get('selected_row'))?$request->query->get('selected_row'):'';

        $query = $em->getRepository(Groupe::class)->findBySearchInput($search_input);

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', $page), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('Administration/groupe/index.html.twig',[
            'current_menu'=>'groupe',
            'groupes'=>$pagination,
            'search_input'=>$search_input,
            'selected_row'=>$selected_row,
            'page'=>$page,
            'action'=>'' // add or edit
        ]);
    }


    /**
     * @Route("/add_groupe", name="add_groupe_page")
     */
    public function add(Request $request,PaginatorInterface $paginator){

        $em = $this->getDoctrine()->getManager();

        $groupe = new Groupe();

        $form = $this->createForm(GroupeType::class,$groupe);
        
        $form->handleRequest($request);
        
        // $statut = $this->checkUnicityAuditeur($em,$groupe=NULL);

        if($form->isSubmitted() && $form->isValid() ){

            $em->persist($groupe);
            $em->flush();

            $this->addFlash('success', 'Un nouvel enregistrement est ajouté avec succès');
            
            return $this->redirectToRoute('groupe_page',array('selected_row'=>$groupe->getId()));
        }


        $query = $em->getRepository(Groupe::class)->findBySearchInput($search_input="");

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('Administration/groupe/index.html.twig',[
            'current_menu'=>'groupe',
            'form'=>$form->createView(),
            'groupes'=>$pagination,
            'search_input'=>'',
            'selected_row'=>'',
            'page'=>1,
            'action'=>'add'
        ]);

    }


    /**
     * @Route("/edit_groupe/{id}", name="edit_groupe_page")
     */
    public function update(Request $request,PaginatorInterface $paginator,$id){

        $em = $this->getDoctrine()->getManager();

        $search_input = !empty($request->get('search_input')) ? $request->get('search_input'):'';

        $page = !empty($request->get('page'))?$request->query->get('page'):1;

        $groupe = $em->getRepository(Groupe::class)->find($id);

        if(!$groupe){

            $this->addFlash('warning', 'Aucun enregistrement correspond à votre requête !');

            return $this->redirectToRoute('groupe_page');
        }
      
       $form = $this->createForm(GroupeType::class,$groupe);

        $form->handleRequest($request);

        // $statut = $this->checkUnicityAuditeur($em,$groupe);

        if($form->isSubmitted() && $form->isValid()){
            
            $em->flush();

            $this->addFlash('success', 'Cet enregistrement est modifié avec succès');
            
            return $this->redirectToRoute('groupe_page',array('selected_row'=>$groupe->getId(),'search_input'=>$search_input,'page'=>$page));
        }


        $query = $em->getRepository(Groupe::class)->findBySearchInput($search_input);


        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', $page), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('Administration/groupe/index.html.twig',[
            'current_menu'=>'groupe',
            'form'=>$form->createView(),
            'groupes'=>$pagination,
            'search_input'=>$search_input,
            'selected_row'=>$groupe->getId(),
            'page'=>$page,
            'action'=>'edit'
        ]);

    }

    /**
     * @Route("/delete_groupe",name="delete_groupe")
     */
    public function delete(Request $request){

        $id = $request->get('id');

        $em = $this->getDoctrine()->getManager();

        $groupe = $em->getRepository(Groupe::class)->find($id);

        try{ 
            
        $em->remove($groupe);

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

    // /*------------------------------------------------------------------*/
    // /*                   check unicity Auditeur                         */
    // /*------------------------------------------------------------------*/
    // private function checkUnicityAuditeur($em,$groupe){

    //     $statut = true;

    //     $checkAuditeur = $em->getRepository(Groupe::class)->checkUnicity($groupe);

    //     dump(sizeof($checkAuditeur));

    //     if(sizeof($checkAuditeur)>0){

    //         $this->addFlash('info','Vous ne pouvez pas avoir plus q\'un groupe avec le statut Auditeur');

    //         $statut = false;
    //     }

    //     return $statut;
    // }


} // fin GroupeController