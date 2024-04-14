<?php
namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Event\AuthenticationSuccessEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class LoginSuccessSubscriber implements EventSubscriberInterface
{
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public static function getSubscribedEvents()
    {
        return [
            AuthenticationSuccessEvent::class => 'onAuthenticationSuccess',
        ];
    }

    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();

        // Redirect based on user's role
        if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            $event->setResponse(new RedirectResponse($this->urlGenerator->generate('Home')));
        } else {
            $event->setResponse(new RedirectResponse($this->urlGenerator->generate('Home2')));
        }
    }
}
