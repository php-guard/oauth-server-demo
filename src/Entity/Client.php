<?php

namespace App\Entity;

use Doctrine\Common\Annotations\Annotation\Enum;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use \OAuth2\Extensions\OpenID\Roles\Clients\ClientMetadataInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClientRepository")
 */
class Client implements ClientMetadataInterface
{
    const TYPE_WEB_APPLICATION = 'web';
    const TYPE_USER_AGENT_BASED_APPLICATION = 'user-agent-based';
    const TYPE_NATIVE_APPLICATION = 'native';

    use ClientMetadata;
    use OpenIDClientMetadata;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Enum({Client::TYPE_WEB_APPLICATION, Client::TYPE_USER_AGENT_BASED_APPLICATION, Client::TYPE_NATIVE_APPLICATION})
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $identifier;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Authorization", mappedBy="client", orphanRemoval=true)
     */
    private $authorizations;

    /**
     * Client constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->setIdentifier(bin2hex(random_bytes(10)));
        $this->authorizations = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Client
     */
    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): self
    {
        $this->identifier = $identifier;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

//    function hasCredentials(): bool
//    {
//        return (bool) $this->password;
//    }
//
//    function isHttpBasicAuthenticationSchemeSupported(): bool
//    {
//        return true;
//    }
//
//    function isTLSSupported(): bool
//    {
//        return true;
//    }

    /**
     * @return Collection|Authorization[]
     */
    public function getAuthorizations(): Collection
    {
        return $this->authorizations;
    }

    public function addAuthorization(Authorization $authorization): self
    {
        if (!$this->authorizations->contains($authorization)) {
            $this->authorizations[] = $authorization;
            $authorization->setClient($this);
        }

        return $this;
    }

    public function removeAuthorization(Authorization $authorization): self
    {
        if ($this->authorizations->contains($authorization)) {
            $this->authorizations->removeElement($authorization);
            // set the owning side to null (unless already changed)
            if ($authorization->getClient() === $this) {
                $authorization->setClient(null);
            }
        }

        return $this;
    }
}
