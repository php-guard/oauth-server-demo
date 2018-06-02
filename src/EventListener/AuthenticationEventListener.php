<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 01/05/2018
 * Time: 18:09
 */

namespace App\EventListener;


use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class AuthenticationEventListener
{

    const LAST_TIME_ACTIVELY_AUTHENTICATED = 'oauth.last_time_actively_authenticated';

    /**
     * This is called when an interactive authentication attempt succeeds. This
     * is called by authentication listeners inheriting from
     * AbstractAuthenticationListener.
     *
     * @param InteractiveLoginEvent $event
     */
    public function onAuthenticationSuccess( InteractiveLoginEvent $event)
    {
        $event->getRequest()->getSession()->set(self::LAST_TIME_ACTIVELY_AUTHENTICATED,
            new \DateTime('now', new \DateTimeZone('UTC')));
    }
}