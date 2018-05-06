<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 21/04/2018
 * Time: 18:49
 */

namespace App\OAuth\Storages;


use App\Entity\Credentials\AccessToken;
use Doctrine\ORM\EntityManagerInterface;
use OAuth2\Credentials\AccessTokenInterface;
use OAuth2\Storages\AccessTokenStorageInterface;

class AccessTokenStorage implements AccessTokenStorageInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    function get(string $token): ?AccessTokenInterface
    {
        return $this->em->getRepository(AccessToken::class)->findOneBy(['token' => $token]);
    }

    function revoke(string $token)
    {
        $token = $this->em->getRepository(AccessToken::class)->findOneBy(['token' => $token]);
        if ($token) {
            $this->em->remove($token);
            $this->em->flush();
        }
    }

    /**
     * @param array $scopes
     * @param string $clientIdentifier
     * @param null|string $resourceOwnerIdentifier
     * @param null|string $authorizationCode
     * @return AccessTokenInterface
     * @throws \Exception
     */
    function generate(array $scopes, string $clientIdentifier, ?string $resourceOwnerIdentifier = null,
                      ?string $authorizationCode = null): AccessTokenInterface
    {
        $accessToken = new AccessToken();
        $accessToken
            ->setToken(bin2hex(random_bytes(15)))
            ->setType('bearer')
            ->setScopes($scopes)
            ->setClientIdentifier($clientIdentifier)
            ->setResourceOwnerIdentifier($resourceOwnerIdentifier)
            ->setExpiresAt((new \DateTime('now', new \DateTimeZone('UTC')))->modify('+30 minutes'))
            ->setAuthorizationCode($authorizationCode);

        $this->em->persist($accessToken);
        $this->em->flush();
        return $accessToken;
    }

    function getLifetime(): ?int
    {
        return 1800;
    }

    /**
     * @param string $code
     * @return AccessTokenInterface[]|null
     */
    function getByAuthorizationCode(string $code): ?array
    {
        return $this->em->getRepository(AccessToken::class)->findBy(['authorizationCode' => $code]);
    }

    function hasExpired(AccessTokenInterface $accessToken): bool
    {
        $now = new \DateTime('now', new \DateTimeZone('UTC'));
//        $token = $this->em->getRepository(AccessToken::class)->findOneBy(['token' => $accessToken->getToken()]);
        return $accessToken ? $accessToken->getExpiresAt() < $now : true;
    }

    function getSize(): ?int
    {
        return null;
    }
}