<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 21/04/2018
 * Time: 18:50
 */

namespace App\OAuth\Storages;


use App\Entity\Credentials\RefreshToken;
use Doctrine\ORM\EntityManagerInterface;
use OAuth2\Credentials\RefreshTokenInterface;
use OAuth2\Credentials\TokenInterface;
use OAuth2\Storages\RefreshTokenStorageInterface;

class RefreshTokenStorage extends AbstractTokenStorage implements RefreshTokenStorageInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    function get(string $token): ?TokenInterface
    {
        return $this->em->getRepository(RefreshToken::class)->findOneBy(['token' => $token]);
    }

    function revoke(string $token)
    {
        $token = $this->em->getRepository(RefreshToken::class)->findOneBy(['token' => $token]);
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
     * @return RefreshTokenInterface
     */
    function generate(array $scopes, string $clientIdentifier,
                      ?string $resourceOwnerIdentifier = null, ?string $authorizationCode = null): TokenInterface
    {
        $refreshToken = new RefreshToken();
        $refreshToken
            ->setToken(bin2hex(random_bytes(15)))
            ->setScopes($scopes)
            ->setClientIdentifier($clientIdentifier)
            ->setExpiresAt((new \DateTime('now', new \DateTimeZone('UTC')))->modify('+7 days'))
            ->setResourceOwnerIdentifier($resourceOwnerIdentifier)
            ->setAuthorizationCode($authorizationCode);

        $this->em->persist($refreshToken);
        $this->em->flush();
        return $refreshToken;
    }

    function getLifetime(): ?int
    {
        return 604800;
    }

    /**
     * @param string $code
     * @return RefreshTokenInterface[]
     */
    function getByAuthorizationCode(string $code): array
    {
        return $this->em->getRepository(RefreshToken::class)->findBy(['authorizationCode' => $code]);
    }

    function getSize(): ?int
    {
        return null;
    }
}