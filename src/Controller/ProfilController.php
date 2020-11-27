<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\ResetPasswordType;
use Symfony\Component\Form\FormError;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfilController extends AbstractController
{
    /**
     * @Route("/profil", name="profil_page")
     */
    public function index(Request $request,UserPasswordEncoderInterface $encoder)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();

        $form = $this->createForm(ResetPasswordType::class,$user);

        $form->handleRequest($request);

        dump($request->request);

        if($form->isSubmitted() && $form->isValid()){

            $oldPassword = $form->get('oldPassword')->getData();

            // si l'ancien mot de passe est bon
            if($encoder->isPasswordValid($user,$oldPassword)){

                $encoded= $encoder->encodePassword($user,$user->getPlainPassword());

                $user->setPassword($encoded);

                $em->persist($user);

                $em->flush();

                $this->addFlash('success', 'Votre mot de passe à bien été changé !');

                return $this->redirectToRoute('profil_page');

            }else{
                $this->addFlash('error', 'Ancien mot de passe incorrect !');
            }

        }

        return $this->render('profil/index.html.twig', [
            'current_menu'=>'profil',
            'form'=>$form->createView()
        ]);
    }


    /**
     * @Route("/profil_change_photo", name="profil_change_photo")
     */
    public function changePhoto(Request $request){

        $em = $this->getDoctrine()->getManager();

        $file = $request->files->get('input-file-upload');

        $id = $this->getUser()->getId();

        $old_photo = $this->getUser()->getPath();

        $filename = uniqid().".".$file->getClientOriginalExtension();

        $path = $this->getParameter('avatars_directory');

        $file->move($path,$filename); // move the file to a path

        $mime_type_image = strtolower(mime_content_type($path.'/'.$filename));

        if($mime_type_image!="image/png" && $mime_type_image!="image/jpg"  && $mime_type_image!="image/jpeg") // format non valide
        {

            unlink($path.'/'.$filename);

            return new Response(json_encode(array()),
                201,
                array('Content-Type'=>'application/json'));

        }

        if($old_photo!="empty.png")
        {
            $old_path='%kernel.project_dir%/public/avatars';

            unlink($old_path);

        }

        $this->getUser()->setPath($filename);

        $em->flush();

        $this->addFlash('success', 'Photo de profil Modifié avec succès.');

        return new Response(json_encode(array()),
            200,
            array('Content-Type'=>'application/json'));

    }


} // end controller ProfilController 

