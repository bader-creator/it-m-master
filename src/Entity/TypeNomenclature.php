<?php

namespace App\Entity;

use App\Repository\TypeNomenclatureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=TypeNomenclatureRepository::class)
 * @UniqueEntity("name")
 * @UniqueEntity("code")
 */
class TypeNomenclature
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $code;

    /**
     * @ORM\OneToMany(targetEntity=ValeurNomenclature::class, mappedBy="typeNomenclature")
     */
    private $valeurNomenclatures;

    public function __construct()
    {
        $this->valeurNomenclatures = new ArrayCollection();
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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Collection|ValeurNomenclature[]
     */
    public function getValeurNomenclatures(): Collection
    {
        return $this->valeurNomenclatures;
    }

    public function addValeurNomenclature(ValeurNomenclature $valeurNomenclature): self
    {
        if (!$this->valeurNomenclatures->contains($valeurNomenclature)) {
            $this->valeurNomenclatures[] = $valeurNomenclature;
            $valeurNomenclature->setTypeNomenclature($this);
        }

        return $this;
    }

    public function removeValeurNomenclature(ValeurNomenclature $valeurNomenclature): self
    {
        if ($this->valeurNomenclatures->contains($valeurNomenclature)) {
            $this->valeurNomenclatures->removeElement($valeurNomenclature);
            // set the owning side to null (unless already changed)
            if ($valeurNomenclature->getTypeNomenclature() === $this) {
                $valeurNomenclature->setTypeNomenclature(null);
            }
        }

        return $this;
    }
}
