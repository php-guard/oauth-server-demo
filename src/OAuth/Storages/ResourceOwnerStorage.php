<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 21/04/2018
 * Time: 18:49
 */

namespace App\OAuth\Storages;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use OAuth2\Roles\ResourceOwnerInterface;
use OAuth2\Storages\ResourceOwnerStorageInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ResourceOwnerStorage implements ResourceOwnerStorageInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    function validateCredentials(string $username, string $password): ?string
    {
        $resourceOwner = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $username]);
        return $this->userPasswordEncoder->isPasswordValid($resourceOwner, $password) ? $resourceOwner->getUsername() : null;
    }

    /**
     * @param string $identifier
     * @return bool
     */
    function exists(string $identifier): bool
    {
        return (bool)$this->entityManager->getRepository(User::class)->findOneBy(['username' => $identifier]);
    }

    public function get(string $identifier): ?ResourceOwnerInterface
    {
        return $this->entityManager->getRepository(User::class)->findOneBy(['username' => $identifier]);
    }
}