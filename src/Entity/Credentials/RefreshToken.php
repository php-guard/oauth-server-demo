<?php

namespace App\Entity\Credentials;

use Doctrine\ORM\Mapping as ORM;
use OAuth2\Credentials\RefreshTokenInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RefreshTokenRepository")
 */
class RefreshToken implements RefreshTokenInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $token;

    /**
     * @var array
     * @ORM\Column(type="array")
     */
    private $scopes;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $clientIdentifier;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $resourceOwnerIdentifier;

    /**
     * @var \DateTimeInterface
     * @ORM\Column(type="datetime")
     */
    private $expiresAtDate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $authorizationCode;

    public function getId()
    {
        return $this->id;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getClientIdentifier(): string
    {
        return $this->clientIdentifier;
    }

    public function setClientIdentifier(string $clientIdentifier): self
    {
        $this->clientIdentifier = $clientIdentifier;

        return $this;
    }

    public function getResourceOwnerIdentifier(): ?string
    {
        return $this->resourceOwnerIdentifier;
    }

    public function setResourceOwnerIdentifier(?string $resourceOwnerIdentifier): self
    {
        $this->resourceOwnerIdentifier = $resourceOwnerIdentifier;

        return $this;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getExpiresAt(): \DateTimeInterface
    {
        return $this->expiresAtDate;
    }

    /**
     * @param \DateTimeInterface $expiresAtDate
     * @return RefreshToken
     */
    public function setExpiresAt(\DateTimeInterface $expiresAtDate): self
    {
        $this->expiresAtDate = $expiresAtDate;

        return $this;
    }

    /**
     * @return array
     */
    public function getScopes(): array
    {
        return $this->scopes;
    }

    /**
     * @param array $scopes
     * @return RefreshToken
     */
    public function setScopes(array $scopes): self
    {
        $this->scopes = $scopes;

        return $this;
    }

    public function getAuthorizationCode(): ?string
    {
        return $this->authorizationCode;
    }

    public function setAuthorizationCode(?string $authorizationCode): self
    {
        $this->authorizationCode = $authorizationCode;

        return $this;
    }
}
