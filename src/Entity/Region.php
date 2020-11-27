<?php

namespace App\Entity;

use App\Repository\RegionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=RegionRepository::class)
 * @UniqueEntity("name")
 */
class Region
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"read:site"})
     *  @Groups({"read:noeud"})
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
     * @ORM\OneToMany(targetEntity=Site::class, mappedBy="region")
     */
    private $sites;

    /**
     * @ORM\OneToMany(targetEntity=Armoire::class, mappedBy="region")
     */
    private $armoires;

    public function __construct()
    {
        $this->sites = new ArrayCollection();
        $this->armoires = new ArrayCollection();
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

    /**
     * @return Collection|Site[]
     */
    public function getSites(): Collection
    {
        return $this->sites;
    }

    public function addSite(Site $site): self
    {
        if (!$this->sites->contains($site)) {
            $this->sites[] = $site;
            $site->setRegion($this);
        }

        return $this;
    }

    public function removeSite(Site $site): self
    {
        if ($this->sites->contains($site)) {
            $this->sites->removeElement($site);
            // set the owning side to null (unless already changed)
            if ($site->getRegion() === $this) {
                $site->setRegion(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Armoire[]
     */
    public function getArmoires(): Collection
    {
        return $this->armoires;
    }

    public function addArmoire(Armoire $armoire): self
    {
        if (!$this->armoires->contains($armoire)) {
            $this->armoires[] = $armoire;
            $armoire->setRegion($this);
        }

        return $this;
    }

    public function removeArmoire(Armoire $armoire): self
    {
        if ($this->armoires->contains($armoire)) {
            $this->armoires->removeElement($armoire);
            // set the owning side to null (unless already changed)
            if ($armoire->getRegion() === $this) {
                $armoire->setRegion(null);
            }
        }

        return $this;
    }
}
