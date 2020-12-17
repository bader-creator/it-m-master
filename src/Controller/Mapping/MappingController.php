<?php

namespace App\Controller\Mapping;

use App\Entity\Site;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MappingController extends AbstractController {


    /**
     * @Route("/map_site", name="map_site_page")
     */
    public function index(){
        $em = $this->getDoctrine()->getManager();

        $query = $em->getRepository(Site::class)->findSites();


        return $this->render('Map/index.html.twig',array(
           'data'=>$query
        ));
    }


    

 

} // fin MappingController
