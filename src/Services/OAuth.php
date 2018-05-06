<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 21/04/2018
 * Time: 18:45
 */

namespace App\Services;


use App\OAuth\Roles\ResourceOwner;
use App\OAuth\Storages\AccessTokenStorage;
use App\OAuth\Storages\AuthorizationCodeStorage;
use App\OAuth\Storages\ClientStorage;
use App\OAuth\Storages\RefreshTokenStorage;
use App\OAuth\Storages\ResourceOwnerStorage;
use OAuth2\Extensions\OpenID\Config;
use OAuth2\Extensions\OpenID\Server;
use OAuth2\Extensions\OpenID\Storages\StorageManager;

class OAuth
{
    private $server;

    public function __construct(ResourceOwner $resourceOwner,
                                ClientStorage $clientStorage,
                                ResourceOwnerStorage $resourceOwnerStorage,
                                AuthorizationCodeStorage $authorizationCodeStorage,
                                AccessTokenStorage $accessTokenStorage,
                                RefreshTokenStorage $refreshTokenStorage)
    {
        $config = new Config('alexandre-le-borgne/oauth-server-demo');
        $config->setDefaultScopes(['email', 'profile']);

        $storageManager = new StorageManager(
            $clientStorage,
            $resourceOwnerStorage,
            $authorizationCodeStorage,
            $accessTokenStorage,
            $refreshTokenStorage
        );

        $this->server = new Server($config, $storageManager, $resourceOwner);
    }

    /**
     * @return Server
     */
    public function getServer(): Server
    {
        return $this->server;
    }
}