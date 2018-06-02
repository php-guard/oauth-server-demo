<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 21/04/2018
 * Time: 18:45
 */

namespace App\Services;


use App\OAuth\Roles\AuthorizationServerEndUserEndUser;
use App\OAuth\Storages\AccessTokenStorage;
use App\OAuth\Storages\AuthorizationCodeStorage;
use App\OAuth\Storages\ClientStorage;
use App\OAuth\Storages\RefreshTokenStorage;
use App\OAuth\Storages\ResourceOwnerStorage;
use OAuth2\Roles\AuthorizationServer;
use OAuth2\Extensions\OpenID\Config;
use OAuth2\Roles\ResourceServer\ResourceServer;
use OAuth2\Extensions\OpenID\Server as OpenIDServer;
use OAuth2\Extensions\OpenID\Storages\StorageManager;
use OAuth2\ScopePolicy\Policies\DefaultScopePolicy;

class OAuth
{
    private $authorizationServer;
    private $resourceServer;

    public function __construct(AuthorizationServerEndUserEndUser $authorizationServer,
                                ClientStorage $clientStorage,
                                ResourceOwnerStorage $resourceOwnerStorage,
                                AuthorizationCodeStorage $authorizationCodeStorage,
                                AccessTokenStorage $accessTokenStorage,
                                RefreshTokenStorage $refreshTokenStorage)
    {
        $scopePolicy = new DefaultScopePolicy(['email', 'profile']);
        $config = new Config($scopePolicy, 'alexandre-le-borgne/oauth-server-demo');

        $storageManager = new StorageManager(
            $clientStorage,
            $resourceOwnerStorage,
            $authorizationCodeStorage,
            $accessTokenStorage,
            $refreshTokenStorage
        );

        $this->authorizationServer = new AuthorizationServer($config, $storageManager, $authorizationServer);
        $this->resourceServer = new ResourceServer($accessTokenStorage, $clientStorage, $resourceOwnerStorage);
    }

    /**
     * @return AuthorizationServer
     */
    public function getAuthorizationServer(): AuthorizationServer
    {
        return $this->authorizationServer;
    }

    /**
     * @return ResourceServer
     */
    public function getResourceServer(): ResourceServer
    {
        return $this->resourceServer;
    }


}