<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 01/05/2018
 * Time: 14:59
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use OAuth2\Roles\ResourceOwnerInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields="email", message="Email already taken")
 * @UniqueEntity(fields="username", message="Username already taken")
 */
class User implements UserInterface, \Serializable, ResourceOwnerInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    /**
     * The below length depends on the "algorithm" you use for encoding
     * the password, but this works well with bcrypt.
     *
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Authorization", mappedBy="resourceOwner", orphanRemoval=true)
     */
    private $authorizationsGivenToClients;

    public function __construct()
    {
        $this->authorizationsGivenToClients = new ArrayCollection();
    }

    public function getIdentifier(): string
    {
        return $this->getUsername();
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }


    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getSalt()
    {
        // The bcrypt and argon2i algorithms don't require a separate salt.
        // You *may* need a real salt if you choose a different encoder.
        return null;
    }

    public function getRoles()
    {
        return array('ROLE_USER');
    }

    public function eraseCredentials()
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize()
     * @param $serialized
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized, ['allowed_classes' => false]);
    }

    /**
     * @return Collection|Authorization[]
     */
    public function getAuthorizationsGivenToClients(): Collection
    {
        return $this->authorizationsGivenToClients;
    }

    public function addAuthorizationGivenToClient(Authorization $authorizedClient): self
    {
        if (!$this->authorizationsGivenToClients->contains($authorizedClient)) {
            $this->authorizationsGivenToClients[] = $authorizedClient;
            $authorizedClient->setResourceOwner($this);
        }

        return $this;
    }

    public function removeAuthorizationGivenToClient(Authorization $authorization): self
    {
        if ($this->authorizationsGivenToClients->contains($authorization)) {
            $this->authorizationsGivenToClients->removeElement($authorization);
            // set the owning side to null (unless already changed)
            if ($authorization->getResourceOwner() === $this) {
                $authorization->setResourceOwner(null);
            }
        }

        return $this;
    }

}