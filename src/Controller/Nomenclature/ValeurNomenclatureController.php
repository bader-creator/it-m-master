<?php

namespace App\Controller\Nomenclature;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\ValeurNomenclature;
use App\Form\ValeurNomenclatureType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Knp\Component\Pager\PaginatorInterface;

class ValeurNomenclatureController extends AbstractController {


    /**
     * @Route("/valeur_nomenclature", name="valeur_nomenclature_page")
     */
    public function index(Request $request,PaginatorInterface $paginator){

        $em = $this->getDoctrine()->getManager();

        $search_input = !empty($request->get('search_input')) ? $request->get('search_input'):'';

        $page = !empty($request->get('page'))?$request->query->get('page'):1;

        $selected_row = !empty($request->get('selected_row'))?$request->query->get('selected_row'):'';

        $query = $em->getRepository(ValeurNomenclature::class)->findBySearchInput($search_input);

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', $page), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('Nomenclature/valeur/index.html.twig',[
            'current_menu'=>'valeur_nomenclature',
            'valeurs'=>$pagination,
            'search_input'=>$search_input,
            'selected_row'=>$selected_row,
            'page'=>$page,
            'action'=>'' // add or edit
        ]);
    }


    /**
     * @Route("/add_valeur_nomenclature", name="add_valeur_nomenclature_page")
     */
    public function add(Request $request,PaginatorInterface $paginator){

        $em = $this->getDoctrine()->getManager();

        $valeur = new ValeurNomenclature();
        
        $form = $this->createForm(ValeurNomenclatureType::class,$valeur);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $em->persist($valeur);
            $em->flush();

            $this->addFlash('success', 'Un nouvel enregistrement est ajouté avec succès');
            
            return $this->redirectToRoute('valeur_nomenclature_page',array('selected_row'=>$valeur->getId()));
        }

        $query = $em->getRepository(ValeurNomenclature::class)->findBySearchInput($search_input="");

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('Nomenclature/valeur/index.html.twig',[
            'current_menu'=>'valeur_nomenclature',
            'form'=>$form->createView(),
            'valeurs'=>$pagination,
            'search_input'=>'',
            'selected_row'=>'',
            'page'=>1,
            'action'=>'add'
        ]);

    }


    /**
     * @Route("/edit_valeur_nomenclature/{id}", name="edit_valeur_nomenclature_page")
     */
    public function update(Request $request,PaginatorInterface $paginator,$id){

        $em = $this->getDoctrine()->getManager();

        $search_input = !empty($request->get('search_input')) ? $request->get('search_input'):'';

        $page = !empty($request->get('page'))?$request->query->get('page'):1;

        $valeur = $em->getRepository(ValeurNomenclature::class)->find($id);

        if(!$valeur){

            $this->addFlash('warning', 'Aucun enregistrement correspond à votre requête !');

            return $this->redirectToRoute('valeur_nomenclature_page');
        }

        
        $form = $this->createForm(ValeurNomenclatureType::class,$valeur);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $em->flush();

            $this->addFlash('success', 'Cet enregistrement est modifié avec succès');
            
            return $this->redirectToRoute('valeur_nomenclature_page',array('selected_row'=>$valeur->getId(),'search_input'=>$search_input,'page'=>$page));
        }

        $query = $em->getRepository(ValeurNomenclature::class)->findBySearchInput($search_input);

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', $page), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('Nomenclature/valeur/index.html.twig',[
            'current_menu'=>'valeur_nomenclature',
            'form'=>$form->createView(),
            'valeurs'=>$pagination,
            'search_input'=>$search_input,
            'selected_row'=>$valeur->getId(),
            'page'=>$page,
            'action'=>'edit'
        ]);

    }

    /**
     * @Route("/delete_valeur_nomenclature",name="delete_valeur_nomenclature")
     */
    public function delete(Request $request){

        $id = $request->get('id');

        $em = $this->getDoctrine()->getManager();

        $valeur = $em->getRepository(ValeurNomenclature::class)->find($id);

        try{ 
            
        $em->remove($valeur);

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


} // fin ValeurNomenclatureController