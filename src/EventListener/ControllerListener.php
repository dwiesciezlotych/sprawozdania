<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\RedirectController;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\RouterInterface;

class ControllerListener implements EventSubscriberInterface
{
    private $tokenStorage;
    private $em;
    private $resolver;
    private $passwordEncoder;
    private $router;

    public function __construct(
        TokenStorageInterface $tokenStorage, 
          RouterInterface $router
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->router = $router;
    }
    
    public function onKernelController(ControllerEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        if (!$token = $this->tokenStorage->getToken()) {
            return ;
        }

        if (!$token->isAuthenticated()) {
            return ;
        }

        if (!$user = $token->getUser()) {
            return ;
        }
        // sprawdza czy użytkownik jest zalogowany
        if(!is_object($user)){
            return ;
        }
        // sprawdza czy dla użytkownika wymagana jest zmiana hasła
        if($user->getStatus()->getName() != \App\Entity\Statuses::STATUS_CHANGE_PASSWORD_REQUEST){
            return ;
        }
        
        $route = 'app_user_change_password';

        if ($route === $event->getRequest()->get('_route')) {
            return;
        }

        // przekierowanie na stronę zmiany hasła
        $url = $this->router->generate($route,['uid' => $user->getId()]);
        $event->setController(function () use($url) {
                         return new RedirectResponse($url);
                     });
    }

    public static function getSubscribedEvents(): array {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }

}