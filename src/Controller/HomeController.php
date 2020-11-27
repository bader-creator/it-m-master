<?php 

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{

    /**
     * @Route("/accueil", name="home_page")
     */
    public function index(){

	 	$user_logged = $this->getUser();

        if(empty($user_logged) || empty($user_logged->getId())) {

            return $this->redirectToRoute('app_login');

        }

        return $this->render('home/index.html.twig',[
            'current_menu'=>'accueil',
        ]);
    }



} // end HomeController

