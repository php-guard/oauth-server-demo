<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 21/04/2018
 * Time: 18:49
 */

namespace App\OAuth\Storages;


use App\Entity\Credentials\AuthorizationCode;
use Doctrine\ORM\EntityManagerInterface;
use OAuth2\Credentials\AuthorizationCodeInterface;
use OAuth2\Storages\AuthorizationCodeStorageInterface;
use Symfony\Component\VarDumper\VarDumper;

class AuthorizationCodeStorage implements AuthorizationCodeStorageInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    function get(string $code): ?AuthorizationCodeInterface
    {
        return $this->em->getRepository(AuthorizationCode::class)->findOneBy(['code' => $code]);
    }

    function revoke(string $code): void
    {
        $authorizationCode = $this->em->getRepository(AuthorizationCode::class)->findOneBy(['code' => $code]);
        if ($authorizationCode) {
            $this->em->remove($authorizationCode);
            $this->em->flush();
        }
    }

    /**
     * @param array $scopes
     * @param string $clientIdentifier
     * @param string $resourceOwnerIdentifier
     * @param array|null $requestedScopes
     * @param null|string $redirectUri
     * @return AuthorizationCodeInterface
     * @throws \Exception
     */
    function generate(array $scopes, string $clientIdentifier, string $resourceOwnerIdentifier,
                      ?array $requestedScopes, ?string $redirectUri): AuthorizationCodeInterface
    {
       $authorizationCode = new AuthorizationCode();
       $authorizationCode
           ->setCode(bin2hex(random_bytes(10)))
           ->setScopes($scopes)
           ->setClientIdentifier($clientIdentifier)
           ->setResourceOwnerIdentifier($resourceOwnerIdentifier)
           ->setRequestedScopes($requestedScopes)
           ->setExpiresAt((new \DateTime('now', new \DateTimeZone('UTC')))->modify('+1 minute'))
           ->setRedirectUri($redirectUri);

       $this->em->persist($authorizationCode);
       $this->em->flush();
       return $authorizationCode;
    }

    function hasExpired(AuthorizationCodeInterface $authorizationCode): bool
    {

        $now = new \DateTime('now', new \DateTimeZone('UTC'));
//        $authorizationCode = $this->em->getRepository(AuthorizationCode::class)->findOneBy([
//            'code' => $authorizationCode->getCode()
//        ]);
//        VarDumper::dump($now);
//        VarDumper::dump($authorizationCode);
//        die;
        return $authorizationCode ? $authorizationCode->getExpiresAt() < $now : true;
    }

    function getSize(): ?int
    {
        return null;
    }
}