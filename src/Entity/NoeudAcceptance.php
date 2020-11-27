<?php

namespace App\Entity;

use App\Repository\NoeudAcceptanceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
/**
 * @ApiResource(normalizationContext = {"groups" = {"read:noeud"}})
 * @ApiFilter(SearchFilter::class, properties={"userDestinator": "exact"})
 * @ORM\Entity(repositoryClass=NoeudAcceptanceRepository::class)
 */
class NoeudAcceptance
{
    CONST TypeAcceptance = [
        0 => "Site",
        // 1 => "Armoire",
        // 2 => "Fibre Optique",
    ];

    CONST Statut = [
        0 => "Nouveau",
        1 => "Terminé"
    ];
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"read:noeud"})
     */
    private $id;

    /**
     * @ApiSubresource(maxDepth=1)
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="noeudAcceptances")
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"read:noeud"})
     */
    private $userCreator;

    /**
     * @ApiSubresource(maxDepth=1)
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="noeudAcceptances")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank();
     */
    private $userDestinator;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $statut;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $path;

    /**
     * @ORM\Column(type="float",nullable=true).
     * @Groups({"read:noeud"})
     */
    private $longitude;

    /**
     * @ORM\Column(type="float",nullable=true)
     * @Groups({"read:noeud"})
     */
    private $latitude;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()

     */
    private $typeAcceptance;

   /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_creation", type="datetime",nullable=true)
     * @Groups({"read:noeud"})
     */
    private $dateCreation;

    /**
     * @ApiSubresource(maxDepth=1)
     * @ORM\ManyToOne(targetEntity=Site::class, inversedBy="noeudAcceptances")
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"read:noeud"})
     */
    private $site;

   

    /**
     * @ApiSubresource(maxDepth=1)
     * @ORM\ManyToOne(targetEntity=Fiche::class, inversedBy="noeudAcceptances")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank();
     * @Groups({"read:noeud"})
     */
    private $fiche;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserCreator(): ?User
    {
        return $this->userCreator;
    }

    public function setUserCreator(?User $userCreator): self
    {
        $this->userCreator = $userCreator;

        return $this;
    }

    public function getUserDestinator(): ?User
    {
        return $this->userDestinator;
    }

    public function setUserDestinator(?User $userDestinator): self
    {
        $this->userDestinator = $userDestinator;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getTypeAcceptance(): ?string
    {
        return $this->typeAcceptance;
    }

    public function setTypeAcceptance(string $typeAcceptance): self
    {
        $this->typeAcceptance = $typeAcceptance;

        return $this;
    }

    
    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): self
    {
        $this->site = $site;

        return $this;
    }


    public function getFiche(): ?Fiche
    {
        return $this->fiche;
    }

    public function setFiche(?Fiche $fiche): self
    {
        $this->fiche = $fiche;

        return $this;
    }

    public function getStatusValue(): string
    {
      return self::Statut[$this->statut];
    }

    public function getTypeAcceptanceValue(): string
    {
      return self::TypeAcceptance[$this->typeAcceptance];
    }
}
