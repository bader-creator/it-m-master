<?php

namespace App\Controller\Nomenclature;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\TypeNomenclature;
use App\Form\TypeNomenclatureType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Knp\Component\Pager\PaginatorInterface;

class TypeNomenclatureController extends AbstractController {


    /**
     * @Route("/type_nomenclature", name="type_nomenclature_page")
     */
    public function index(Request $request,PaginatorInterface $paginator){

        $em = $this->getDoctrine()->getManager();

        $search_input = !empty($request->get('search_input')) ? $request->get('search_input'):'';

        $page = !empty($request->get('page'))?$request->query->get('page'):1;

        $selected_row = !empty($request->get('selected_row'))?$request->query->get('selected_row'):'';

        $query = $em->getRepository(TypeNomenclature::class)->findBySearchInput($search_input);

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', $page), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('Nomenclature/type/index.html.twig',[
            'current_menu'=>'type_nomenclature',
            'types'=>$pagination,
            'search_input'=>$search_input,
            'selected_row'=>$selected_row,
            'page'=>$page,
            'action'=>'' // add or edit
        ]);
    }


    /**
     * @Route("/add_type_nomenclature", name="add_type_nomenclature_page")
     */
    public function add(Request $request,PaginatorInterface $paginator){

        $em = $this->getDoctrine()->getManager();

        $type = new TypeNomenclature();

        $form = $this->createForm(TypeNomenclatureType::class,$type);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $em->persist($type);
            $em->flush();

            $this->addFlash('success', 'Un nouvel enregistrement est ajouté avec succès');
            
            return $this->redirectToRoute('type_nomenclature_page',array('selected_row'=>$type->getId()));
        }


        $query = $em->getRepository(TypeNomenclature::class)->findBySearchInput($search_input="");

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('Nomenclature/type/index.html.twig',[
            'current_menu'=>'type_nomenclature',
            'form'=>$form->createView(),
            'types'=>$pagination,
            'search_input'=>'',
            'selected_row'=>'',
            'page'=>1,
            'action'=>'add'
        ]);

    }


    /**
     * @Route("/edit_type_nomenclature/{id}", name="edit_type_nomenclature_page")
     */
    public function update(Request $request,PaginatorInterface $paginator,$id){

        $em = $this->getDoctrine()->getManager();

        $search_input = !empty($request->get('search_input')) ? $request->get('search_input'):'';

        $page = !empty($request->get('page'))?$request->query->get('page'):1;

        $type = $em->getRepository(TypeNomenclature::class)->find($id);

        if(!$type){

            $this->addFlash('warning', 'Aucun enregistrement correspond à votre requête !');

            return $this->redirectToRoute('type_nomenclature_page');
        }

        $code = $type->getCode();

        $form = $this->createForm(TypeNomenclatureType::class,$type);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $type->setCode($code);

            $em->flush();

            $this->addFlash('success', 'Cet enregistrement est modifié avec succès');
            
            return $this->redirectToRoute('type_nomenclature_page',array('selected_row'=>$type->getId(),'search_input'=>$search_input,'page'=>$page));
        }

        $query = $em->getRepository(TypeNomenclature::class)->findBySearchInput($search_input);

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', $page), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('Nomenclature/type/index.html.twig',[
            'current_menu'=>'type_nomenclature',
            'form'=>$form->createView(),
            'types'=>$pagination,
            'search_input'=>$search_input,
            'selected_row'=>$type->getId(),
            'page'=>$page,
            'action'=>'edit'
        ]);

    }

    /**
     * @Route("/delete_type_nomenclature",name="delete_type_nomenclature")
     */
    public function delete(Request $request){

        $id = $request->get('id');

        $em = $this->getDoctrine()->getManager();

        $type = $em->getRepository(TypeNomenclature::class)->find($id);

        try{ 
            
        $em->remove($type);

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


} // fin TypeNomenclatureController