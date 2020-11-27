<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Reponse;
use App\Entity\User;
use App\Entity\Question;
use App\Entity\CommentaireSite;
use App\Entity\Choix;
use App\Entity\ImageSite;
use App\Entity\Site;
use App\Entity\NoeudAcceptance;
use Hshn\Base64EncodedFile\HttpFoundation\File\Base64EncodedFile;
use Hshn\Base64EncodedFile\HttpFoundation\File\UploadedBase64EncodedFile;
use Psr\Log\LoggerInterface;
use JMS\Serializer\SerializationContext;



class ResponseController extends AbstractController
{
    /**
     * @Route("/api/response", name="api_response")
     */
    public function index()
    {
        return $this->render('api/response/index.html.twig', [
            'controller_name' => 'ResponseController',
        ]);
    }


    /**
     * @Route("/api/sendResponse/{iduser}", methods={"POST"}, name="Save_Response")
     * @param Request $request
     * @return JsonResponse
     */
    public function SaveResponse(Request $request,$iduser){

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();
        $Responses = $request->getContent();
        $Responses=json_decode($Responses,true);
        $this->SaveSite($Responses["site"]);
        $user = $em->getRepository(User::class)->find($iduser);
        

        foreach ($Responses["data"] as $element) {

            $reponse=new Reponse();

            $date = new \DateTime($element['date']);
    
            $reponse->setDateReponse($date);
    
            $reponse->setType($element['type']);
    
            if($request->get('type')==0){
                if($element['reponse']){
                    $choix = $em->getRepository(Choix::class)->find($element['reponse']);
                    $reponse->setChoixReponse($choix);
                }
            }else{
                if($element['reponse']){
                $reponse->setReponse($element['reponse']);
                }
            }
    
            $question = $em->getRepository(Question::class)->find($element['id']);
            

            $reponse->setQuestion($question);
    
            $em->persist($reponse);
    
           
            if(!empty($element['comments'])){
                $coments=[];
                $coments=$element['comments'];
                foreach ($coments as $com) {
                    $commentaires=new CommentaireSite();

                    $commentaires->setComment($com['commentaire']);
                
                    $commentaires->setReponse($reponse);
            
                    $dateComment = new \DateTime($com['date']);
        
                    $commentaires->setDateCommentaire($dateComment);
            
                    
        
                    $commentaires->setUserCreator($user);
        
                    $em->persist($commentaires);
                }
        
            }
            if(!empty($element['images'])){
                $pictures=[];
                $pictures=$element['images'];
                foreach ($pictures as $pic) {

                    $images=new ImageSite();
    
                    /******************************************************************** */
                    $typefile = "jpeg";
                    $base64Content = $pic['imageData'];
                    if(!empty($base64Content)) {
                        $base64Content = explode("charset=utf-8;base64,", $base64Content);
                        if(isset($base64Content[1])) {
                            $base64Content = $base64Content[1];
                            $file = new UploadedBase64EncodedFile(new Base64EncodedFile($base64Content));


                            if(!empty($typefile)) {
                                $filename = uniqid() .".". $typefile;
                                $path = __DIR__ . '/../../../public/uploads/avatars';
                                $file_path = $path . '/' . $filename;


                                if (!file_exists($path) && !is_dir($path)) {
                                    @mkdir($path, 0775, true);
                                }

                                if ($file->move($path, $filename)) {
                                    $images->setPath($filename);
                                }
                            }
                        }
                   }

                /******************************************************************** */

                    $dateImages = new \DateTime($pic['date']);
            
                    $images->setDateInsertion($dateImages);

                    $images->setReponse($reponse);
         
                    $images->setUserCreator($user);
                    
                    $em->persist($images);
                }
            }
            $em->flush();
        }
        $response = new JsonResponse($Responses["site"], 200);
        return $response;
    }


    public function SaveSite($site){

          /** @var EntityManager $em */
          $em = $this->getDoctrine()->getManager();

           $noeud = $em->getRepository(NoeudAcceptance::class)->find($site['idTicket']);
           $noeud->setLongitude($site['longitude']);
           $noeud->setLatitude($site['latitude']);
           $dateSites = new \DateTime($site['date']);
           $noeud->setDateCreation($dateSites);
          
            /******************************************************************** */
              $typefile = "jpeg";
              if(isset($site['image']['image']['changingThisBreaksApplicationSecurity'])){
                $base64Content = $site['image']['image']['changingThisBreaksApplicationSecurity'];
              }else{
                $base64Content = $site['image']['image'];
              }
              
              if(!empty($base64Content)) {
                  $base64Content = explode("charset=utf-8;base64,", $base64Content);
                  if(isset($base64Content[1])) {
                      $base64Content = $base64Content[1];
                      $file = new UploadedBase64EncodedFile(new Base64EncodedFile($base64Content));


                      if(!empty($typefile)) {
                          $filename = uniqid() .".". $typefile;
                          $path = __DIR__ . '/../../../public/uploads/avatars';
                          $file_path = $path . '/' . $filename;


                          if (!file_exists($path) && !is_dir($path)) {
                              @mkdir($path, 0775, true);
                          }

                          if ($file->move($path, $filename)) {
                                $noeud->setPath($filename);
                          }
                      }
                  }
             }

            $em->persist($noeud);

            $em->flush();

       
          /******************************************************************** */
    }

    /**
     * @Route("/api/ListSites/{idFichier}/{iduser}", methods={"GET"}, name="List_Site")
     * @param Request $request
     * @return JsonResponse
     */
    public function ListSites($idFichier,$iduser){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $Liste=$em->createQueryBuilder()

            ->select(['site',
            'partial noeud.{id,typeAcceptance}',
            'partial fiche.{id,label}'])
            ->from(Site::class,'site')
            ->leftJoin('site.noeudAcceptances', 'noeud')
            ->leftJoin('noeud.userDestinator', 'user')
            ->leftJoin('noeud.fiche', 'fiche')
            ->where('noeud.userDestinator = :iduser')
            ->andWhere('noeud.fiche = :idFichier')
            ->setParameters(array('iduser'=>$iduser,'idFichier'=>$idFichier))
            ->getQuery()->getResult(AbstractQuery::HYDRATE_ARRAY);

        $data=[];
        $data['site']=$Liste;
        $response = new JsonResponse( $data, 200);
        return $response;
    }

     /**
     * @Route("/api/InfoSite/{idSite}", methods={"GET"}, name="Info_Site")
     * @param Request $request
     * @return JsonResponse
     */
    public function InfoSite($idSite){
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $Infos=$em->createQueryBuilder()

            ->select(['noeud','site','userDest','userCreat','fiche'])
            ->from(NoeudAcceptance::class,'noeud')
            ->leftJoin('noeud.site', 'site')
            ->leftJoin('noeud.userDestinator', 'userDest')
            ->leftJoin('noeud.userCreator', 'userCreat')
            ->leftJoin('noeud.fiche', 'fiche')
            ->where('site.id = :idSite')
            ->setParameter('idSite', $idSite)
            ->getQuery()->getResult(AbstractQuery::HYDRATE_ARRAY);

        $data=[];
        $data['info']=$Infos;
        $response = new JsonResponse( $data, 200);
        return $response;
    }
}