<?php

namespace App\EventListener;
use App\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

/**
 * Description of JWTAuthenticatedListener
 *
 * @author Bader
 *
 */
class AuthenticationSuccessListener
{

    /**
     * @param AuthenticationSuccessEvent $event
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {

        $user = $event->getUser();
        $event->setData([
            'data' => $event->getData(),
            'id' => $user->getId(),
            'userName' => $user->getUsername(),
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'path' => $user->getPath(),
            'group'=> $user->getGroupe()->getName(),
            'job'=> $user->getFonction()->getName(),
            'phone'=> $user->getPhone(),  
        ]);
  
    }
  
}