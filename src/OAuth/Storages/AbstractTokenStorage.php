<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 27/05/2018
 * Time: 18:27
 */

namespace App\OAuth\Storages;


use OAuth2\Credentials\TokenInterface;
use OAuth2\Storages\TokenStorageInterface;

abstract class AbstractTokenStorage implements TokenStorageInterface
{
    function hasExpired(TokenInterface $accessToken): bool
    {
        $now = new \DateTime('now', new \DateTimeZone('UTC'));
        return $accessToken ? $accessToken->getExpiresAt() < $now : true;
    }
}