<?php
/**
 * Created by PhpStorm.
 * User: sgb638
 * Date: 08/11/19
 * Time: 15:13
 */

namespace Blogger\BlogBundle\EventListener;

use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class RedirectAfterRegisterationSubscriber implements EventSubscriberInterface
{
    use TargetPathTrait;

    /**
     * @var RouterInterface
     */
    private $router;

    public function  __construct(RouterInterface $router)
    {

        $this->router = $router;
    }

    public function onRegistrationSuccess(FormEvent $event)
    {

        $url = $this->getTargetPath($event->getRequest()->getSession(),'main');

        if (!$url){
            $url = $this->router->generate('blogger_index');
        }

        $url = $this->router->generate('blogger_index');
        $response = new RedirectResponse($url);
        $event->setResponse($response);
    }


    public static function getSubscribedEvents()
    {
        return [
            FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess'
        ];
    }

}