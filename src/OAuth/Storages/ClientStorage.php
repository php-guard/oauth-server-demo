<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 21/04/2018
 * Time: 18:48
 */

namespace App\OAuth\Storages;


use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use OAuth2\Roles\ClientProfiles\NativeApplicationClient;
use OAuth2\Roles\ClientProfiles\UserAgentBasedApplicationClient;
use OAuth2\Roles\ClientProfiles\WebApplicationClient;
use OAuth2\Roles\ClientTypes\RegisteredClient;
use OAuth2\Storages\ClientStorageInterface;

class ClientStorage implements ClientStorageInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param string $identifier
     * @return null|RegisteredClient
     */
    function get(string $identifier): ?RegisteredClient
    {
        if($client = $this->em->getRepository(Client::class)->findOneBy(['identifier' => $identifier])) {
            switch ($client->getType()) {
                case Client::TYPE_WEB_APPLICATION:
                    $client = new WebApplicationClient($client->getIdentifier(), $client->getPassword(), $client);
                    break;
                case Client::TYPE_USER_AGENT_BASED_APPLICATION:
                    $client = new UserAgentBasedApplicationClient($client->getIdentifier(), $client);
                    break;
                case Client::TYPE_NATIVE_APPLICATION:
                    $client = new NativeApplicationClient($client->getIdentifier(), $client);
                    break;
                default:
                    $client = null;
            }
        }
        return $client;
    }

    function getIdentifierSize(): ?int
    {
        return null;
    }
}