<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AuthorizationRepository")
 */
class Authorization
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="authorizationsGivenToClients")
     * @ORM\JoinColumn(nullable=false)
     */
    private $resourceOwner;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Client", inversedBy="authorizations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    /**
     * @ORM\Column(type="array", nullable=false)
     */
    private $scopes = [];

    public function getId()
    {
        return $this->id;
    }

    public function getResourceOwner(): ?User
    {
        return $this->resourceOwner;
    }

    public function setResourceOwner(?User $resourceOwner): self
    {
        $this->resourceOwner = $resourceOwner;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getScopes(): array
    {
        return $this->scopes;
    }

    public function setScopes(array $scopes): self
    {
        $this->scopes = $scopes;

        return $this;
    }
}
