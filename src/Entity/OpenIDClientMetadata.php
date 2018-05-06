<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 28/04/2018
 * Time: 15:45
 */

namespace App\Entity;


trait OpenIDClientMetadata
{

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $applicationType;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sectorIdentifierUri;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $subjectType;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $idTokenSignedResponseAlg;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $idTokenEncryptedResponseAlg;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $idTokenEncryptedResponseEnc;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $userinfoEncryptedResponseAlg;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $userinfoEncryptedResponseEnc;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $userinfoSignedResponseAlg;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $requestObjectSigningAlg;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $requestObjectEncryptionAlg;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $requestObjectEncryptionEnc;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tokenEndpointAuthSigningAlg;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $defaultMaxAge;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $requireAuthTime;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $defaultAcrValues;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $initiateLoginUri;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $requestUris;

    public function getApplicationType(): ?string
    {
        return $this->applicationType;
    }

    public function setApplicationType(?string $applicationType): self
    {
        $this->applicationType = $applicationType;

        return $this;
    }

    public function getSectorIdentifierUri(): ?string
    {
        return $this->sectorIdentifierUri;
    }

    public function setSectorIdentifierUri(?string $sectorIdentifierUri): self
    {
        $this->sectorIdentifierUri = $sectorIdentifierUri;

        return $this;
    }

    public function getSubjectType(): ?string
    {
        return $this->subjectType;
    }

    public function setSubjectType(?string $subjectType): self
    {
        $this->subjectType = $subjectType;

        return $this;
    }

    public function getIdTokenSignedResponseAlg(): ?string
    {
        return $this->idTokenSignedResponseAlg;
    }

    public function setIdTokenSignedResponseAlg(?string $idTokenSignedResponseAlg): self
    {
        $this->idTokenSignedResponseAlg = $idTokenSignedResponseAlg;

        return $this;
    }

    public function getIdTokenEncryptedResponseAlg(): ?string
    {
        return $this->idTokenEncryptedResponseAlg;
    }

    public function setIdTokenEncryptedResponseAlg(?string $idTokenEncryptedResponseAlg): self
    {
        $this->idTokenEncryptedResponseAlg = $idTokenEncryptedResponseAlg;

        return $this;
    }

    public function getIdTokenEncryptedResponseEnc(): ?string
    {
        return $this->idTokenEncryptedResponseEnc;
    }

    public function setIdTokenEncryptedResponseEnc(?string $idTokenEncryptedResponseEnc): self
    {
        $this->idTokenEncryptedResponseEnc = $idTokenEncryptedResponseEnc;

        return $this;
    }

    public function getUserinfoEncryptedResponseAlg(): ?string
    {
        return $this->userinfoEncryptedResponseAlg;
    }

    public function setUserinfoEncryptedResponseAlg(?string $userinfoEncryptedResponseAlg): self
    {
        $this->userinfoEncryptedResponseAlg = $userinfoEncryptedResponseAlg;

        return $this;
    }

    public function getUserinfoEncryptedResponseEnc(): ?string
    {
        return $this->userinfoEncryptedResponseEnc;
    }

    public function setUserinfoEncryptedResponseEnc(?string $userinfoEncryptedResponseEnc): self
    {
        $this->userinfoEncryptedResponseEnc = $userinfoEncryptedResponseEnc;

        return $this;
    }

    public function getUserinfoSignedResponseAlg(): ?string
    {
        return $this->userinfoSignedResponseAlg;
    }

    public function setUserinfoSignedResponseAlg(?string $userinfoSignedResponseAlg): self
    {
        $this->userinfoSignedResponseAlg = $userinfoSignedResponseAlg;

        return $this;
    }

    public function getRequestObjectSigningAlg(): ?string
    {
        return $this->requestObjectSigningAlg;
    }

    public function setRequestObjectSigningAlg(?string $requestObjectSigningAlg): self
    {
        $this->requestObjectSigningAlg = $requestObjectSigningAlg;

        return $this;
    }

    public function getRequestObjectEncryptionAlg(): ?string
    {
        return $this->requestObjectEncryptionAlg;
    }

    public function setRequestObjectEncryptionAlg(?string $requestObjectEncryptionAlg): self
    {
        $this->requestObjectEncryptionAlg = $requestObjectEncryptionAlg;

        return $this;
    }

    public function getRequestObjectEncryptionEnc(): ?string
    {
        return $this->requestObjectEncryptionEnc;
    }

    public function setRequestObjectEncryptionEnc(?string $requestObjectEncryptionEnc): self
    {
        $this->requestObjectEncryptionEnc = $requestObjectEncryptionEnc;

        return $this;
    }

    public function getTokenEndpointAuthSigningAlg(): ?string
    {
        return $this->tokenEndpointAuthSigningAlg;
    }

    public function setTokenEndpointAuthSigningAlg(?string $tokenEndpointAuthSigningAlg): self
    {
        $this->tokenEndpointAuthSigningAlg = $tokenEndpointAuthSigningAlg;

        return $this;
    }

    public function getDefaultMaxAge(): ?int
    {
        return $this->defaultMaxAge;
    }

    public function setDefaultMaxAge(?int $defaultMaxAge): self
    {
        $this->defaultMaxAge = $defaultMaxAge;

        return $this;
    }

    public function getRequireAuthTime(): ?bool
    {
        return $this->requireAuthTime;
    }

    public function setRequireAuthTime(?bool $requireAuthTime): self
    {
        $this->requireAuthTime = $requireAuthTime;

        return $this;
    }

    public function getDefaultAcrValues(): ?array
    {
        return $this->defaultAcrValues;
    }

    public function setDefaultAcrValues(?array $defaultAcrValues): self
    {
        $this->defaultAcrValues = $defaultAcrValues;

        return $this;
    }

    public function getInitiateLoginUri(): ?string
    {
        return $this->initiateLoginUri;
    }

    public function setInitiateLoginUri(?string $initiateLoginUri): self
    {
        $this->initiateLoginUri = $initiateLoginUri;

        return $this;
    }

    public function getRequestUris(): ?array
    {
        return $this->requestUris;
    }

    public function setRequestUris(?array $requestUris): self
    {
        $this->requestUris = $requestUris;

        return $this;
    }


}