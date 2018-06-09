<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 21/04/2018
 * Time: 18:45
 */

namespace App\Services;


use App\OAuth\Roles\AuthorizationServerEndUser;
use App\OAuth\Storages\AccessTokenStorage;
use App\OAuth\Storages\AuthorizationCodeStorage;
use App\OAuth\Storages\ClientStorage;
use App\OAuth\Storages\RefreshTokenStorage;
use App\OAuth\Storages\ResourceOwnerStorage;
use OAuth2\OAuthServer;
use OAuth2\Extensions\OpenID\Config;
use OAuth2\Roles\AuthorizationServer\AuthorizationServer;
use OAuth2\Roles\ResourceServer\ResourceServer;
use OAuth2\ScopePolicy\Policies\DefaultScopePolicy;

class OAuth
{
    private $server;

    public function __construct(AuthorizationServerEndUser $endUser,
                                ClientStorage $clientStorage,
                                ResourceOwnerStorage $resourceOwnerStorage,
                                AuthorizationCodeStorage $authorizationCodeStorage,
                                AccessTokenStorage $accessTokenStorage,
                                RefreshTokenStorage $refreshTokenStorage)
    {
        $scopePolicy = new DefaultScopePolicy(['email', 'profile']);
        $config = new Config($scopePolicy, 'alexandre-le-borgne/oauth-server-demo');

        $this->server = new OAuthServer($config, $endUser,
            $clientStorage, $resourceOwnerStorage,
            $authorizationCodeStorage, $accessTokenStorage, $refreshTokenStorage);
    }

    /**
     * @return AuthorizationServer
     */
    public function getAuthorizationServer(): AuthorizationServer
    {
        return $this->server->getAuthorizationServer();
    }

    /**
     * @return ResourceServer
     */
    public function getResourceServer(): ResourceServer
    {
        return $this->server->getResourceServer();
    }


}