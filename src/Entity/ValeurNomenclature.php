<?php

namespace App\Entity;

use App\Repository\ValeurNomenclatureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=ValeurNomenclatureRepository::class)
 * @UniqueEntity("name")
 */
class ValeurNomenclature
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
     * @ORM\ManyToOne(targetEntity=TypeNomenclature::class, inversedBy="valeurNomenclatures")
     * @ORM\JoinColumn(nullable=false,referencedColumnName="id")
     * @Assert\NotBlank()
     */
    private $typeNomenclature;

      /**
     * @ORM\OneToMany(targetEntity="App\Entity\Question", mappedBy="typeArmoire")
     */
    private $armoire;

     /**
     * @ORM\OneToMany(targetEntity="App\Entity\Stock", mappedBy="category")
     */
    private $stock;

    public function __construct()
    {
        $this->armoire = new ArrayCollection();
        $this->stock = new ArrayCollection();
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


    public function getTypeNomenclature(): ?TypeNomenclature
    {
        return $this->typeNomenclature;
    }

    public function setTypeNomenclature(?TypeNomenclature $typeNomenclature): self
    {
        $this->typeNomenclature = $typeNomenclature;

        return $this;
    }

    /**
     * @return Collection|question[]
     */
    public function getArmoire(): Collection
    {
        return $this->armoire;
    }

    public function addArmoire(question $armoire): self
    {
        if (!$this->armoire->contains($armoire)) {
            $this->armoire[] = $armoire;
            $armoire->setTypeArmoire($this);
        }

        return $this;
    }

    public function removeArmoire(question $armoire): self
    {
        if ($this->armoire->contains($armoire)) {
            $this->armoire->removeElement($armoire);
            // set the owning side to null (unless already changed)
            if ($armoire->getTypeArmoire() === $this) {
                $armoire->setTypeArmoire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Stock[]
     */
    public function getStock(): Collection
    {
        return $this->stock;
    }

    public function addStock(Stock $stock): self
    {
        if (!$this->stock->contains($stock)) {
            $this->stock[] = $stock;
            $stock->setCategory($this);
        }

        return $this;
    }

    public function removeStock(Stock $stock): self
    {
        if ($this->stock->contains($stock)) {
            $this->stock->removeElement($stock);
            // set the owning side to null (unless already changed)
            if ($stock->getCategory() === $this) {
                $stock->setCategory(null);
            }
        }

        return $this;
    }
}
