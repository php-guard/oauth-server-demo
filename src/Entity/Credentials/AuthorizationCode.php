<?php

namespace App\Entity\Credentials;

use Doctrine\ORM\Mapping as ORM;
use OAuth2\Credentials\AuthorizationCodeInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AuthorizationCodeRepository")
 */
class AuthorizationCode implements AuthorizationCodeInterface
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
    private $code;

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
     * @ORM\Column(type="string", length=255)
     */
    private $resourceOwnerIdentifier;

    /**
     * @var null|array
     * @ORM\Column(type="array", nullable=true)
     */
    private $requestedScopes;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $redirectUri;

    /**
     * @var \DateTimeInterface
     * @ORM\Column(type="datetime")
     */
    private $expiresAt;

    public function getId()
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

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

    public function getResourceOwnerIdentifier(): string
    {
        return $this->resourceOwnerIdentifier;
    }

    public function setResourceOwnerIdentifier(string $resourceOwnerIdentifier): self
    {
        $this->resourceOwnerIdentifier = $resourceOwnerIdentifier;

        return $this;
    }

    public function getRedirectUri(): ?string
    {
        return $this->redirectUri;
    }

    public function setRedirectUri(?string $redirectUri): self
    {
        $this->redirectUri = $redirectUri;

        return $this;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getExpiresAt(): \DateTimeInterface
    {
        return $this->expiresAt;
    }

    /**
     * @param \DateTimeInterface $expiresAt
     * @return AuthorizationCode
     */
    public function setExpiresAt(\DateTimeInterface $expiresAt): self
    {
        $this->expiresAt = $expiresAt;

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
     * @return AuthorizationCode
     */
    public function setScopes(array $scopes): self
    {
        $this->scopes = $scopes;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getRequestedScopes(): ?array
    {
        return $this->requestedScopes;
    }

    /**
     * @param array|null $requestedScopes
     * @return AuthorizationCode
     */
    public function setRequestedScopes(?array $requestedScopes): self
    {
        $this->requestedScopes = $requestedScopes;

        return $this;
    }
}
