<?php

namespace App\Controller\FicheAcceptance;



use App\Entity\SousItem;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Form\SousItemType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Knp\Component\Pager\PaginatorInterface;

class SousItemController extends AbstractController {


    /**
     * @Route("/sousItem", name="sousItem_page")
     */
    public function index(Request $request,PaginatorInterface $paginator){

        $em = $this->getDoctrine()->getManager();

        $search_input = !empty($request->get('search_input')) ? $request->get('search_input'):'';

        $page = !empty($request->get('page'))?$request->query->get('page'):1;

        $selected_row = !empty($request->get('selected_row'))?$request->query->get('selected_row'):'';

        $query = $em->getRepository(SousItem::class)->findBySearchInput($search_input);

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', $page), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('FicheAcceptance/sousItem/index.html.twig',[
            'current_menu'=>'sousItem',
            'sousItems'=>$pagination,
            'search_input'=>$search_input,
            'selected_row'=>$selected_row,
            'page'=>$page,
            'action'=>'' // add or edit
        ]);
    }


    /**
     * @Route("/add_sousItem", name="add_sousItem_page")
     */
    public function add(Request $request,PaginatorInterface $paginator){

        $em = $this->getDoctrine()->getManager();

        $sousItem = new SousItem();

        $form = $this->createForm(SousItemType::class,$sousItem);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $em->persist($sousItem);
            $em->flush();

            $this->addFlash('success', 'Un nouvel enregistrement est ajouté avec succès');
            
            return $this->redirectToRoute('sousItem_page',array('selected_row'=>$sousItem->getId()));
        }

        $query = $em->getRepository(SousItem::class)->findBySearchInput($search_input="");

        /*
hello
        */

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('FicheAcceptance/sousItem/index.html.twig',[
            'current_menu'=>'sousItem',
            'form'=>$form->createView(),
            'sousItems'=>$pagination,
            'search_input'=>'',
            'selected_row'=>'',
            'page'=>1,
            'action'=>'add'
        ]);

    }


    /**
     * @Route("/edit_sousItem/{id}", name="edit_sousItem_page")
     */
    public function update(Request $request,PaginatorInterface $paginator,$id){

        $em = $this->getDoctrine()->getManager();

        $search_input = !empty($request->get('search_input')) ? $request->get('search_input'):'';

        $page = !empty($request->get('page'))?$request->query->get('page'):1;

        $sousItem = $em->getRepository(SousItem::class)->find($id);

        if(!$sousItem){

            $this->addFlash('warning', 'Aucun enregistrement correspond à votre requête !');

            return $this->redirectToRoute('sousItem_page');
        }

        $form = $this->createForm(SousItemType::class,$sousItem);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $em->flush();

            $this->addFlash('success', 'Cet enregistrement est modifié avec succès');
            
            return $this->redirectToRoute('sousItem_page',array('selected_row'=>$sousItem->getId(),'search_input'=>$search_input,'page'=>$page));
        }

        $query = $em->getRepository(SousItem::class)->findBySearchInput($search_input);

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', $page), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('FicheAcceptance/sousItem/index.html.twig',[
            'current_menu'=>'sousItem',
            'form'=>$form->createView(),
            'sousItems'=>$pagination,
            'search_input'=>$search_input,
            'selected_row'=>$sousItem->getId(),
            'page'=>$page,
            'action'=>'edit'
        ]);

    }

    /**
     * @Route("/delete_sousItem",name="delete_sousItem")
     */
    public function delete(Request $request){

        $id = $request->get('id');

        $em = $this->getDoctrine()->getManager();

        $sousItem = $em->getRepository(SousItem::class)->find($id);

        try{ 
            
        $em->remove($sousItem);

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


} // fin SousItemController
