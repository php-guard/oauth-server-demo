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
use OAuth2\Storages\RefreshTokenStorageInterface;

class RefreshTokenStorage implements RefreshTokenStorageInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    function get(string $token): ?RefreshTokenInterface
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
     * @return RefreshTokenInterface
     * @throws \Exception
     */
    function generate(array $scopes, string $clientIdentifier, ?string $resourceOwnerIdentifier = null): RefreshTokenInterface
    {
        $refreshToken = new RefreshToken();
        $refreshToken
            ->setToken(bin2hex(random_bytes(15)))
            ->setScopes($scopes)
            ->setClientIdentifier($clientIdentifier)
            ->setExpiresAt((new \DateTime('now', new \DateTimeZone('UTC')))->modify('+7 days'))
            ->setResourceOwnerIdentifier($resourceOwnerIdentifier);

        $this->em->persist($refreshToken);
        $this->em->flush();
        return $refreshToken;
    }

    function getLifetime(): ?int
    {
        return 604800;
    }

    function hasExpired(RefreshTokenInterface $refreshToken): bool
    {
        $now = new \DateTime('now', new \DateTimeZone('UTC'));
//        $token = $this->em->getRepository(RefreshToken::class)->findOneBy(['token' => $refreshToken->getToken()]);
        return $refreshToken ? $refreshToken->getExpiresAt() < $now : true;
    }
}