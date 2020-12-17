<?php

namespace App\Controller\Administration;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use App\Entity\Groupe;
use App\Entity\Acteur;
use App\Form\UserType;
use App\Roles\UserRolesTrait;
use App\Roles\AdminRolesTrait;
use App\Service\Mailer;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
class UserController extends AbstractController {


    /**
     * @Route("/user", name="user_page")
     */
    public function index(Request $request,PaginatorInterface $paginator){

        $em = $this->getDoctrine()->getManager();

        $search_input = !empty($request->get('search_input')) ? $request->get('search_input'):'';

        $page = !empty($request->get('page'))?$request->query->get('page'):1;

        $selected_row = !empty($request->get('selected_row'))?$request->query->get('selected_row'):'';

        $query = $em->getRepository(User::class)->findBySearchInput($search_input);

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', $page), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('Administration/user/index.html.twig',[
            'current_menu'=>'utilisateur',
            'users'=>$pagination,
            'search_input'=>$search_input,
            'selected_row'=>$selected_row,
            'page'=>$page
        ]);
    }


    /**
     * @Route("/add_user", name="add_user_page")
     */
    public function add(Request $request,PaginatorInterface $paginator,UserPasswordEncoderInterface $encoder,ValidatorInterface $validator){

        $em = $this->getDoctrine()->getManager();

        $user = new User();

        $form = $this->createForm(UserType::class,$user);
        
        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()){

            /*---------------- debut cryptage de mot de passe --------------*/

            $password = 'sfm2020';

            $encoded= $encoder->encodePassword($user,$password);

            $user->setPassword($encoded);

            /*---------------- fin cryptage de mot de passe --------------*/

            $user->setPath('empty.png');

            $em->persist($user);
            
            $em->flush();

            $this->addFlash('success', 'Un nouvel enregistrement est ajouté avec succès');
            
            return $this->redirectToRoute('user_page',array('selected_row'=>$user->getId()));
        }

        return $this->render('Administration/user/action.html.twig',[
            'current_menu'=>'utilisateur',
            'form'=>$form->createView(),
            'search_input'=>'',
            'selected_row'=>'',
            'page'=>1,
            'action'=>'add',
            'user'=>$user
        ]);

    }


    /**
     * @Route("/edit_user/{id}", name="edit_user_page")
     */
    public function update(Request $request,PaginatorInterface $paginator,$id,ValidatorInterface $validator){

        $em = $this->getDoctrine()->getManager();

        $search_input = !empty($request->get('search_input')) ? $request->get('search_input'):'';

        $page = !empty($request->get('page'))?$request->query->get('page'):1;

        $user = $em->getRepository(User::class)->find($id);

        if(!$user){

            $this->addFlash('warning', 'Aucun enregistrement correspond à votre requête !');

            return $this->redirectToRoute('user_page');
        }

        $form = $this->createForm(UserType::class,$user);

        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()){

            $em->flush();

            $this->addFlash('success', 'Cet enregistrement est modifié avec succès');
            
            return $this->redirectToRoute('user_page',array('selected_row'=>$user->getId(),'search_input'=>$search_input,'page'=>$page));
        }

        return $this->render('Administration/user/action.html.twig',[
            'current_menu'=>'utilisateur',
            'form'=>$form->createView(),
            'search_input'=>$search_input,
            'selected_row'=>'',
            'user'=>$user,
            'page'=>$page,
            'action'=>'edit'
        ]);

    }

     /**
     * @Route("/profil_user",name="profil_user")
     */
    public function profil(Request $request){

        $id = $request->get('id');

        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository(User::class)->findUserDetails($id);

        $url = str_replace("/index.php", "", $request->getUriForPath("/uploads/avatars/"));

        return new Response(json_encode(array(
            'user'=>$user,
            'url'=>$url,
            )),
        200,
        array('Content-Type'=>'application/json'));

    }


    /**
     * @Route("/delete_user",name="delete_user")
     */
    public function delete(Request $request){

        $id = $request->get('id');

        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository(User::class)->find($id);

        try{ 
            
        $em->remove($user);

        $em->flush();

        if($user->getPath()!="empty"){

            $path = $this->getParameter('avatars_directory');

            unlink($path.'/'.$user->getPath());

        }

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
     * @Route("/change_groupe_user",name="change_groupe_user")
     */
    public function changeGroupe(Request $request){

        $em = $this->getDoctrine()->getManager();

        $id_groupe = $request->get('id_groupe');

        $groupe = $em->getRepository(Groupe::class)->find($id_groupe);

        $data = $em->getRepository(Acteur::class)->findBySearchByTypeActeur($groupe->getName());

        return new Response(json_encode(array(
           'data'=>$data,
           'is_acteur'=>$groupe->getIsActeur()
            )),
        200,
        array('Content-Type'=>'application/json'));

    }


    /**
     * @Route("/reset_pwd_user/{id}", name="reset_pwd_user")
     */
    public function restPwd(Request $request,$id,UserPasswordEncoderInterface $encoder,Mailer $mailer){

        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository(User::class)->find($id);

        if($user->isEnabled()){

            /*---------------- debut cryptage de mot de passe --------------*/

             $password = substr(sha1(uniqid(mt_rand(), true)),0,10);

              $encoded= $encoder->encodePassword($user,$password);

              $user->setPassword($encoded);

            /*---------------- fin  cryptage de mot de passe --------------*/

            /*----------------- debut mailing ----------------*/

            $mailer->sendEmailResetPwd($user,$password);

            /*----------------- fin mailing ----------------*/

            $em->flush();

            $this->addFlash('success', 'Renouvellement de mot de passe effectuée avec succès');

        }else{

            $this->addFlash('warning', 'Action non autorisée car l\'utilisateur sélectionné est bloqué !');

        }

            $search_input = !empty($request->get('search_input')) ? $request->get('search_input'):'';

            $page = !empty($request->get('page'))?$request->query->get('page'):1;

            return $this->redirectToRoute('user_page',array('selected_row'=>$user->getId(),'search_input'=>$search_input,'page'=>$page));

    }


    /**
     * @Route("/email_connexion_user/{id}", name="email_connexion_user")
     */
    public function EmailConnexion(Request $request,$id,UserPasswordEncoderInterface $encoder,Mailer $mailer){

        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository(User::class)->find($id);

        if(!$user->getIsAccountEmailSend()){

            /*---------------- debut cryptage de mot de passe --------------*/

            // $password = substr(sha1(uniqid(mt_rand(), true)),0,10);
             $password="sfm2020";

              $encoded= $encoder->encodePassword($user,$password);

              $user->setPassword($encoded);

            /*---------------- fin  cryptage de mot de passe --------------*/

            /*----------------- debut mailing ----------------*/

            $mailer->sendEmailConnexion($user,$password);

            /*----------------- fin mailing ----------------*/

            $user->setIsAccountEmailSend(1);

            $em->flush();

            $this->addFlash('success', 'Création de compte effectuée avec succès');

        }else{

            $this->addFlash('warning', 'Action non autorisée car un email de connexion est déja envoyé !');

        }

            $search_input = !empty($request->get('search_input')) ? $request->get('search_input'):'';

            $page = !empty($request->get('page'))?$request->query->get('page'):1;

            return $this->redirectToRoute('user_page',array('selected_row'=>$user->getId(),'search_input'=>$search_input,'page'=>$page));

    }


    /**
     * @Route("/user_fast_authenticate/{id}", name="user_fast_authenticate")
     */
    public function fastAuthentification(Request $request,$id, EventDispatcherInterface $dispatcher){

        $em = $this->getDoctrine()->getManager();
        
        $user = $em->getRepository(User::class)->find($id);

        if(!empty($user)) {

            $providerKey = 'main';
            $providerKey = 'secured_area'; // your firewall name

            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $this->get('security.token_storage')->setToken($token);

            $this->get('session')->set('_security_main', serialize($token));

            $event = new InteractiveLoginEvent($request, $token);

            $dispatcher->dispatch("security.interactive_login", $event);

            $this->addFlash('success', "Authentification réussie !");
            
            return $this->redirectToRoute('home_page');
        }

        $this->addFlash('error', "Echec d'authentification");

        $search_input = !empty($request->get('search_input')) ? $request->get('search_input'):'';

        $page = !empty($request->get('page'))?$request->query->get('page'):1;

        return $this->redirectToRoute('user_page',array('selected_row'=>$user->getId(),'search_input'=>$search_input,'page'=>$page));

    }


    /**
     * @Route("/user_edit_roles/{id}", name="user_edit_roles_page")
     */
    public function editRoles(Request $request,$id){

        $em = $this->getDoctrine()->getManager();

        $search_input = !empty($request->get('search_input')) ? $request->get('search_input'):'';

        $page = !empty($request->get('page'))?$request->query->get('page'):1;

        $user = $em->getRepository(User::class)->find($id);

        # ---------------------------------------------

        $art = new Class()
        {
            use AdminRolesTrait;
        };

        $adminRT = array_filter($art::getRoles(), function ($value, $key) {
            return is_string($key) && strlen($key);
        }, ARRAY_FILTER_USE_BOTH);
        $admin_roles = [];
        $index = 0;
        foreach ($adminRT as $keyRole => $valueRole) {
            $admin_role = [
                'id' => $index++,
                'text' => $keyRole,
                'children' => []
            ];

            foreach ($valueRole as $keyRoleA => $valueRoleA) {
                $admin_role['children'][] = [
                    'id' => $valueRoleA,
                    'text' => $keyRoleA,
                    'state' => ['selected' => in_array($valueRoleA, $user->getRoles())]
                ];

            }

            $admin_roles[] = $admin_role;
        }

        # ---------------------------------------------

        $urt = new Class()
        {
            use UserRolesTrait;
        };

        $userRT = array_filter($urt::getRoles(), function ($value, $key) {
            return is_string($key) && strlen($key);
        }, ARRAY_FILTER_USE_BOTH);
        $user_roles = [];
        $index = 0;
        foreach ($userRT as $keyRole => $valueRole) {
            $user_role = [
                'id' => $index++,
                'text' => $keyRole,
                'children' => []
            ];

            foreach ($valueRole as $keyRoleA => $valueRoleA) {
                $user_role['children'][] = [
                    'id' => $valueRoleA,
                    'text' => $keyRoleA,
                    'state' => ['selected' => in_array($valueRoleA, $user->getRoles())]
                ];

            }

            $user_roles[] = $user_role;
        }

        # ---------------------------------------------

        if ($request->isMethod('post')) {
            $jstree_input_admin = explode(",", $request->get('jstree-input-admin', []));
            $jstree_input_user = explode(",", $request->get('jstree-input-user', []));

            if (!is_array($jstree_input_admin)) {
                $jstree_input_admin = [];
            }
            if (!is_array($jstree_input_user)) {
                $jstree_input_user = [];
            }

            $data_user_roles = array_filter($jstree_input_user, function ($value, $key) {
                return is_string($value) && strlen($value) && stripos($value, "ROLE_") === 0;
            }, ARRAY_FILTER_USE_BOTH);
            $data_admin_roles = array_filter($jstree_input_admin, function ($value, $key) {
                return is_string($value) && strlen($value) && stripos($value, "ROLE_") === 0;
            }, ARRAY_FILTER_USE_BOTH);

            $roles = array_merge_recursive($data_user_roles, $data_admin_roles);

            $roles = array_unique($roles);
            
            $user->setRoles($roles);

            $em->merge($user);

            $em->flush();

            $this->addFlash('success', "Les privilèges du l'utilisateur sélectioné sont mis à jour avec succès !");

            return $this->redirectToRoute('user_page',array('selected_row'=>$user->getId(),'search_input'=>$search_input,'page'=>$page));
            
        }

        return $this->render('Administration/user/roles.html.twig',[
            'current_menu'=>'utilisateur',
            'user'=>$user,
            'adminRT'=>$adminRT,
            'admin_roles'=>$admin_roles,
            'userRT'=>$userRT,
            'user_roles'=>$user_roles,
            'search_input'=>$search_input,
            'selected_row'=>$user->getId(),
            'page'=>$page,
        ]);
    
    
    }

/**
     * @Route("/setTokenFireBase", methods={"POST"}, name="grh-user-set-token-fairebase")
     *
     * @param Request $request
     * @param LoggerInterface $logger
     * @return JsonResponse
     */
    public function setTokenFireBase(Request $request, LoggerInterface $logger, $id = null)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        if (strpos($request->headers->get('Content-Type'), 'application/json') === 0) {
            $content = $request->getContent();

            $parameters = json_decode($content, true);
        } else {
            $parameters['id'] = $request->get('id');
            $parameters['token'] = $request->get('token');
        }

        /** @var User $personnel */
        $personnel = $em->getRepository(User::class)->find($parameters['id']);

        $data = $parameters;

        if (!empty($personnel)) {
            if (!empty($personnel) && !empty($parameters['token']) && $parameters['token'] != 'null') {
                $personnel->setDeviseToken($parameters['token']);

                $data['id'] = $personnel->getId();

                $em->persist($personnel);
                $em->flush();
            }
        }

     //   $logger->info(sprintf('* %s ==> %s', setTokenDevise(), json_encode(@compact('parameters', 'data'))));

        $response = new JsonResponse($data, 200);
        return $response;
    }
} // fin UserController