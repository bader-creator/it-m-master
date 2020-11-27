<?php


namespace App\Service;

use App\Entity\User;

class Mailer
{

    protected $mailer;
    protected $templating;

    private $email_info = 'info@intt.sfmtechnologies.com';

    public function __construct(\Swift_Mailer $mailer,\Twig_Environment $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    public function sendEmailConnexion($user,$password)
    {
        $message =  (new \Swift_Message())
            ->setSubject("Nouveau compte")
            ->setFrom([$this->email_info =>"INTT Infos"])
            ->setTo([$user->getEmail() => $user->getFullName()])
            ->setCharset('utf-8')
            ->setReturnPath($this->email_info)
            ->setBody($this->templating->render('Emails/connexion.html.twig',
                    array(
                        'user'=>$user,
                        'password' => $password
                    )
            ), 'text/plain');
            // $message->addPart('', 'text/html');

        $this->mailer->send($message);
    }

    public function sendEmailResetPwd($user,$password)
    {
        $message =  (new \Swift_Message())
            ->setSubject("Réinitialiser un mot de passe perdu ou oublié")
            ->setFrom([$this->email_info => "INTT Infos"])
            ->setTo([$user->getEmail() => $user->getFullName()])
            ->setCharset('utf-8')
            ->setReturnPath($this->email_info)
            ->setBody($this->templating->render('Emails/renouvellement_pwd.html.twig',
                array(
                    'user'=>$user,
                    'password' => $password
                )
            ), 'text/plain');
            // $message->addPart('', 'text/html');
        $this->mailer->send($message);
    }

    public function sendResettingEmailMessage(User $user)
    {
        $message =  (new \Swift_Message())
            ->setSubject("Réinitialiser un mot de passe perdu ou oublié")
            ->setFrom([$this->email_info => "INTT Infos"])
            ->setTo([$user->getEmail() => $user->getFullName()])
            ->setCharset('utf-8')
            ->setReturnPath($this->email_info)
            ->setBody($this->templating->render('Emails/resete_pwd.html.twig',
                array('user'=>$user,
                    'token' => $user->getConfirmationToken()
                )
            ), 'text/plain');
            // $message->addPart('', 'text/html');
        $this->mailer->send($message);

        return $message;
    }


} // end class mailer