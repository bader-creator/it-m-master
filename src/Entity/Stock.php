<?php

namespace App\Entity;

use App\Repository\StockRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=StockRepository::class)
 * @UniqueEntity("ref")
 * @UniqueEntity("nomProduit")
 */
class Stock
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
    private $ref;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $nomProduit;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $quantiteGenerale;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $quantiteSortie;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $quantiteRestant;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $quantiteCasse;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank()
     */
    private $dateCreation;

 

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $unite;
    
     /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ValeurNomenclature",inversedBy="stock")
     * @ORM\JoinColumn(name="category", nullable=false, referencedColumnName="id")
     * @Assert\NotBlank()
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity=Tracability::class, mappedBy="stock")
     */
    private $tracabilities;

    /**
     * @ORM\OneToMany(targetEntity=Materiel::class, mappedBy="stock")
     */
    private $materiels;

    public function __construct()
    {
        $this->tracabilities = new ArrayCollection();
        $this->materiels = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRef(): ?string
    {
        return $this->ref;
    }

    public function setRef(?string $ref): self
    {
        $this->ref = $ref;

        return $this;
    }

    public function getNomProduit(): ?string
    {
        return $this->nomProduit;
    }

    public function setNomProduit(?string $nomProduit): self
    {
        $this->nomProduit = $nomProduit;

        return $this;
    }

    public function getQuantiteGenerale(): ?int
    {
        return $this->quantiteGenerale;
    }

    public function setQuantiteGenerale(?int $quantiteGenerale): self
    {
        $this->quantiteGenerale = $quantiteGenerale;

        return $this;
    }

    public function getQuantiteSortie(): ?int
    {
        return $this->quantiteSortie;
    }

    public function setQuantiteSortie(?int $quantiteSortie): self
    {
        $this->quantiteSortie = $quantiteSortie;

        return $this;
    }

    public function getQuantiteRestant(): ?int
    {
        return $this->quantiteRestant;
    }

    public function setQuantiteRestant(?int $quantiteRestant): self
    {
        $this->quantiteRestant = $quantiteRestant;

        return $this;
    }

    public function getQuantiteCasse(): ?int
    {
        return $this->quantiteCasse;
    }

    public function setQuantiteCasse(?int $quantiteCasse): self
    {
        $this->quantiteCasse = $quantiteCasse;

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


    public function getUnite(): ?string
    {
        return $this->unite;
    }

    public function setUnite(?string $unite): self
    {
        $this->unite = $unite;

        return $this;
    }

    /**
     * @return Collection|Tracability[]
     */
    public function getTracabilities(): Collection
    {
        return $this->tracabilities;
    }

    public function addTracability(Tracability $tracability): self
    {
        if (!$this->tracabilities->contains($tracability)) {
            $this->tracabilities[] = $tracability;
            $tracability->setStock($this);
        }

        return $this;
    }

    public function removeTracability(Tracability $tracability): self
    {
        if ($this->tracabilities->contains($tracability)) {
            $this->tracabilities->removeElement($tracability);
            // set the owning side to null (unless already changed)
            if ($tracability->getStock() === $this) {
                $tracability->setStock(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Materiel[]
     */
    public function getMateriels(): Collection
    {
        return $this->materiels;
    }

    public function addMateriel(Materiel $materiel): self
    {
        if (!$this->materiels->contains($materiel)) {
            $this->materiels[] = $materiel;
            $materiel->setStock($this);
        }

        return $this;
    }

    public function removeMateriel(Materiel $materiel): self
    {
        if ($this->materiels->contains($materiel)) {
            $this->materiels->removeElement($materiel);
            // set the owning side to null (unless already changed)
            if ($materiel->getStock() === $this) {
                $materiel->setStock(null);
            }
        }

        return $this;
    }

    public function getCategory(): ?ValeurNomenclature
    {
        return $this->category;
    }

    public function setCategory(?ValeurNomenclature $category): self
    {
        $this->category = $category;

        return $this;
    }
}
