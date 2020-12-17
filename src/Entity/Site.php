<?php

namespace App\Entity;

use App\Repository\SiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiSubresource;
/**
 * @ApiResource(normalizationContext = {"groups" = {"read:site"}}),
 * @ORM\Entity(repositoryClass=SiteRepository::class)
 * @UniqueEntity("name")
 */
class Site
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"read:site"})
     * @Groups({"read:noeud"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Groups({"read:site"})
     * @Groups({"read:noeud"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/^(\+|-)?(?:180(?:(?:\.0{1,6})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,6})?))$/"),
     * message="Cette valeur n'est pas une longitude valide."
     * @Groups({"read:site"})
     * @Groups({"read:noeud"})
     */
    private $longitude;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/^(\+|-)?(?:90(?:(?:\.0{1,6})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,6})?))$/"),
     * message="Cette valeur n'est pas une latitude valide."
     * @Groups({"read:site"})
     * @Groups({"read:noeud"})
     */
    private $latitude;

    /**
     * @ORM\ManyToOne(targetEntity=Region::class, inversedBy="sites")
     * @ORM\JoinColumn(nullable=false,referencedColumnName="id")
     * @Assert\NotBlank()
     * @Groups({"read:site"})
     * @Groups({"read:noeud"})
     * 
     */
    private $region;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Groups({"read:noeud"})
     */
    private $siteId;

    /**
     * @ORM\OneToMany(targetEntity=NoeudAcceptance::class, mappedBy="site")
     * @ApiSubresource(maxDepth=1)
     */
    private $noeudAcceptances;

    /**
     * @ORM\OneToMany(targetEntity=Mission::class, mappedBy="site")
     * @ApiSubresource(maxDepth=1)
     */
    private $missions;

    

    public function __construct()
    {
        $this->noeudAcceptances = new ArrayCollection();
        $this->missions = new ArrayCollection();
     
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(?string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(?string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getSiteId(): ?string
    {
        return $this->siteId;
    }

    public function setSiteId(string $siteId): self
    {
        $this->siteId = $siteId;

        return $this;
    }

    /**
     * @return Collection|NoeudAcceptance[]
     */
    public function getNoeudAcceptances(): Collection
    {
        return $this->noeudAcceptances;
    }

    public function addNoeudAcceptance(NoeudAcceptance $noeudAcceptance): self
    {
        if (!$this->noeudAcceptances->contains($noeudAcceptance)) {
            $this->noeudAcceptances[] = $noeudAcceptance;
            $noeudAcceptance->setSite($this);
        }

        return $this;
    }

    public function removeNoeudAcceptance(NoeudAcceptance $noeudAcceptance): self
    {
        if ($this->noeudAcceptances->contains($noeudAcceptance)) {
            $this->noeudAcceptances->removeElement($noeudAcceptance);
            // set the owning side to null (unless already changed)
            if ($noeudAcceptance->getSite() === $this) {
                $noeudAcceptance->setSite(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Mission[]
     */
    public function getMissions(): Collection
    {
        return $this->missions;
    }

    public function addMission(Mission $mission): self
    {
        if (!$this->missions->contains($mission)) {
            $this->missions[] = $mission;
            $mission->setSite($this);
        }

        return $this;
    }

    public function removeMission(Mission $mission): self
    {
        if ($this->missions->contains($mission)) {
            $this->missions->removeElement($mission);
            // set the owning side to null (unless already changed)
            if ($mission->getSite() === $this) {
                $mission->setSite(null);
            }
        }

        return $this;
    }

   
   
}
