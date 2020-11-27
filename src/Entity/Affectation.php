<?php

namespace App\Entity;

use App\Repository\AffectationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource()
 * use ApiPlatform\Core\Annotation\ApiResource;
 * @ORM\Entity(repositoryClass=AffectationRepository::class)
 */
class Affectation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="affectations",cascade={"remove"})
     */
    private $magasinier;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="affectations",cascade={"remove"})
     */
    private $livreur;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="affectations",cascade={"remove"})
     */
    private $installateur;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="affectations",cascade={"remove"})
     */
    private $metteurService;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="affectations",cascade={"remove"})
     */
    private $acceptateur;

    /**
     * @ORM\ManyToOne(targetEntity=Mission::class, inversedBy="affectations",cascade={"remove"})
     */
    private $mission;

    /**
     * @ORM\OneToMany(targetEntity=Validation::class, mappedBy="affectation")
     */
    private $validations;

    public function __construct()
    {
        $this->validations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLivreur(): ?User
    {
        return $this->livreur;
    }

    public function setLivreur(?User $livreur): self
    {
        $this->livreur = $livreur;

        return $this;
    }

    public function getInstallateur(): ?User
    {
        return $this->installateur;
    }

    public function setInstallateur(?User $installateur): self
    {
        $this->installateur = $installateur;

        return $this;
    }

    public function getMetteurService(): ?User
    {
        return $this->metteurService;
    }

    public function setMetteurService(?User $metteurService): self
    {
        $this->metteurService = $metteurService;

        return $this;
    }

    public function getAcceptateur(): ?User
    {
        return $this->acceptateur;
    }

    public function setAcceptateur(?User $acceptateur): self
    {
        $this->acceptateur = $acceptateur;

        return $this;
    }

    public function getMission(): ?Mission
    {
        return $this->mission;
    }

    public function setMission(?Mission $mission): self
    {
        $this->mission = $mission;

        return $this;
    }

    /**
     * @return Collection|Validation[]
     */
    public function getValidations(): Collection
    {
        return $this->validations;
    }

    public function addValidation(Validation $validation): self
    {
        if (!$this->validations->contains($validation)) {
            $this->validations[] = $validation;
            $validation->setAffectation($this);
        }

        return $this;
    }

    public function removeValidation(Validation $validation): self
    {
        if ($this->validations->contains($validation)) {
            $this->validations->removeElement($validation);
            // set the owning side to null (unless already changed)
            if ($validation->getAffectation() === $this) {
                $validation->setAffectation(null);
            }
        }

        return $this;
    }

    public function getMagasinier(): ?User
    {
        return $this->magasinier;
    }

    public function setMagasinier(?User $magasinier): self
    {
        $this->magasinier = $magasinier;

        return $this;
    }
}
