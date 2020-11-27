<?php

namespace App\Controller\Equipement;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


use App\Entity\Stock;
use App\Entity\Tracability;
use App\Form\StockType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Knp\Component\Pager\PaginatorInterface;

class StockController extends AbstractController {


    /**
     * @Route("/stock", name="stock_page")
     */
    public function index(Request $request,PaginatorInterface $paginator){

        $em = $this->getDoctrine()->getManager();

        $search_input = !empty($request->get('search_input')) ? $request->get('search_input'):'';

        $page = !empty($request->get('page'))?$request->query->get('page'):1;

        $selected_row = !empty($request->get('selected_row'))?$request->query->get('selected_row'):'';

        $query = $em->getRepository(Stock::class)->findBySearchInput($search_input);

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', $page), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('Equipement/stock/index.html.twig',[
            'current_menu'=>'stock',
            'stocks'=>$pagination,
            'search_input'=>$search_input,
            'selected_row'=>$selected_row,
            'page'=>$page,
            'action'=>'' // add or edit
        ]);
    }

    /**
     * @Route("/tracability/{id}", name="tracability_page")
     */
    public function indexTracability(Request $request,PaginatorInterface $paginator,$id){

        $em = $this->getDoctrine()->getManager();

        $search_input = !empty($request->get('search_input')) ? $request->get('search_input'):'';

        $page = !empty($request->get('page'))?$request->query->get('page'):1;

        $selected_row = !empty($request->get('selected_row'))?$request->query->get('selected_row'):'';

        $query = $em->getRepository(Tracability::class)->findBySearchInput($search_input,$id);

        // $stock = $em->getRepository(Stock::class)->find($id);
        // $trace =  $em->getRepository(Tracability::class)->finddTraceByStock($stock);
        // // dump($trace);
        // die;
        // $size =0;
        // foreach( $trace as $t){
        //       $size++;
        // }
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            
            $request->query->getInt('page', $page), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('Equipement/tracability/index.html.twig',[
            'current_menu'=>'tracability',
            'tracabilities'=>$pagination,
            'search_input'=>$search_input,
            'selected_row'=>$selected_row,
            'page'=>$page,
            'action'=>'' ,// add or edit,
            // 'stock' =>$stock,
            // 'size' =>$size,
            // 'trace' => $trace
        ]);
    }


    /**
     * @Route("/add_stock", name="add_stock_page")
     */
    public function add(Request $request,PaginatorInterface $paginator){
        
        $em = $this->getDoctrine()->getManager();
        $stock = new Stock();
       
        $form = $this->createForm(StockType::class,$stock);
        $stock->setDateCreation(new \DateTime());
        $stock->setQuantiteSortie(0);
        $stock->setQuantiteCasse(0);
        $form->handleRequest($request);
        // var_dump( $form->get('quantiteRestant')->getData());
        if($form->isSubmitted() && $form->isValid()){
            $stock->setQuantiteRestant($stock->getQuantiteGenerale() - $stock->getQuantiteSortie());
            $em->persist($stock);
            //tracability
            $current_user = $this->getUser();
            $tracibility = new Tracability();
            $tracibility->setDateAction(new \DateTime());
            $tracibility->setUser($current_user);
            $tracibility->setQuantiteFinale($stock->getQuantiteRestant());
            $tracibility->setStock($stock);
            $tracibility->setTypeAction('Ajout');
            $em->persist($tracibility);
            $em->flush();

            $this->addFlash('success', 'Un nouvel enregistrement est ajouté avec succès');
            
            return $this->redirectToRoute('stock_page',array('selected_row'=>$stock->getId()));
        }

        $query = $em->getRepository(Stock::class)->findBySearchInput($search_input="");
        
        

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('Equipement/stock/index.html.twig',[
            'current_menu'=>'stock',
            'form'=>$form->createView(),
            'stocks'=>$pagination,
            'search_input'=>'',
            'selected_row'=>'',
            'page'=>1,
            'action'=>'add'
        ]);

    }


    /**
     * @Route("/edit_stock/{id}", name="edit_stock_page")
     */
    public function update(Request $request,PaginatorInterface $paginator,$id){

        $em = $this->getDoctrine()->getManager();

        $search_input = !empty($request->get('search_input')) ? $request->get('search_input'):'';

        $page = !empty($request->get('page'))?$request->query->get('page'):1;

        $stock = $em->getRepository(Stock::class)->find($id);
       
   


        if(!$stock){

            $this->addFlash('warning', 'Aucun enregistrement correspond à votre requête !');

            return $this->redirectToRoute('stock_page');
        }
       
        $form = $this->createForm(StockType::class,$stock);
        
        $form->handleRequest($request);
      
        if($form->isSubmitted() && $form->isValid()){
            $current_user = $this->getUser();
            $stock->setQuantiteRestant($stock->getQuantiteGenerale() -  $stock->getQuantiteSortie() );
            $tracibility = new Tracability();
            $tracibility->setQuantiteFinale( $stock->getQuantiteRestant() -  $stock->getQuantiteSortie() );
            $tracibility->setTypeAction('Modification');
            $tracibility->setDateAction(new \DateTime());
            $tracibility->setStock($stock);
            $tracibility->setUser($current_user);
            $em->persist($tracibility);
            $em->flush();

            $this->addFlash('success', 'Cet enregistrement est modifié avec succès');
            
            return $this->redirectToRoute('stock_page',array('selected_row'=>$stock->getId(),'search_input'=>$search_input,'page'=>$page));
        }

        $query = $em->getRepository(Stock::class)->findBySearchInput($search_input);

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', $page), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('Equipement/stock/index.html.twig',[
            'current_menu'=>'stock',
            'form'=>$form->createView(),
            'stocks'=>$pagination,
            'search_input'=>$search_input,
            'selected_row'=>$stock->getId(),
            'page'=>$page,
            'action'=>'edit'
        ]);

    }

    /**
     * @Route("/delete_stock",name="delete_stock")
     */
    public function delete(Request $request){

        $id = $request->get('id');

        $em = $this->getDoctrine()->getManager();

        $stock = $em->getRepository(Stock::class)->find($id);

        try{ 
            
        $em->remove($stock);

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


} // fin stockController
