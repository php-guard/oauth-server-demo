<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 28/04/2018
 * Time: 15:41
 */

namespace App\Entity;


trait ClientMetadata
{
    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $redirectUris;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tokenEndpointAuthMethod;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $grantTypes;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $responseTypes;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $clientName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $clientUri;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $logoUri;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $scope;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $contacts;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tosUri;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $policyUri;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $jwksUri;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $jwks;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $softwareId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $softwareVersion;


    public function getRedirectUris(): ?array
    {
        return $this->redirectUris;
    }

    public function setRedirectUris(?array $redirectUris): self
    {
        $this->redirectUris = $redirectUris;

        return $this;
    }

    public function getTokenEndpointAuthMethod(): ?string
    {
        return $this->tokenEndpointAuthMethod;
    }

    public function setTokenEndpointAuthMethod(?string $tokenEndpointAuthMethod): self
    {
        $this->tokenEndpointAuthMethod = $tokenEndpointAuthMethod;

        return $this;
    }

    public function getGrantTypes(): ?array
    {
        return $this->grantTypes;
    }

    public function setGrantTypes(?array $grantTypes): self
    {
        $this->grantTypes = $grantTypes;

        return $this;
    }

    public function getResponseTypes(): ?array
    {
        return $this->responseTypes;
    }

    public function setResponseTypes(?array $responseTypes): self
    {
        $this->responseTypes = $responseTypes;

        return $this;
    }

    public function getClientName(): ?string
    {
        return $this->clientName;
    }

    public function setClientName(?string $clientName): self
    {
        $this->clientName = $clientName;

        return $this;
    }

    public function getClientUri(): ?string
    {
        return $this->clientUri;
    }

    public function setClientUri(?string $clientUri): self
    {
        $this->clientUri = $clientUri;

        return $this;
    }

    public function getLogoUri(): ?string
    {
        return $this->logoUri;
    }

    public function setLogoUri(?string $logoUri): self
    {
        $this->logoUri = $logoUri;

        return $this;
    }

    public function getScope(): ?string
    {
        return is_array($this->scope) ? implode(' ', $this->scope) : null;
    }

    public function getScopeList(): ?array
    {
        return $this->scope;
    }

    public function setScopeList(?array $scope): self
    {
        $this->scope = $scope;

        return $this;
    }

    public function getContacts(): ?array
    {
        return $this->contacts;
    }

    public function setContacts(?array $contacts): self
    {
        $this->contacts = $contacts;

        return $this;
    }

    public function getTosUri(): ?string
    {
        return $this->tosUri;
    }

    public function setTosUri(?string $tosUri): self
    {
        $this->tosUri = $tosUri;

        return $this;
    }

    public function getPolicyUri(): ?string
    {
        return $this->policyUri;
    }

    public function setPolicyUri(?string $policyUri): self
    {
        $this->policyUri = $policyUri;

        return $this;
    }

    public function getJwksUri(): ?string
    {
        return $this->jwksUri;
    }

    public function setJwksUri(?string $jwksUri): self
    {
        $this->jwksUri = $jwksUri;

        return $this;
    }

    public function getJwks(): ?array
    {
        return $this->jwks;
    }

    public function setJwks(?array $jwks): self
    {
        $this->jwks = $jwks;

        return $this;
    }

    public function getSoftwareId(): ?string
    {
        return $this->softwareId;
    }

    public function setSoftwareId(?string $softwareId): self
    {
        $this->softwareId = $softwareId;

        return $this;
    }

    public function getSoftwareVersion(): ?string
    {
        return $this->softwareVersion;
    }

    public function setSoftwareVersion(?string $softwareVersion): self
    {
        $this->softwareVersion = $softwareVersion;

        return $this;
    }
}