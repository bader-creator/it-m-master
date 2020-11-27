<?php

namespace App\Entity;

use App\Repository\FicheRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(normalizationContext = {"groups" = {"read:fiche"}})
 * @ORM\Entity(repositoryClass=FicheRepository::class)
 * @UniqueEntity("label")
 */
class Fiche
{

    CONST TypeFiche = [
        0 => "Site",
        // 1 => "Armoire",
        // 2 => "Fibre Optique",
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"read:fiche"})
     * @Groups({"read:noeud"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Groups({"read:fiche"})
     * @Groups({"read:noeud"})
     */

    private $label;

    /**
     * @ApiSubresource(maxDepth=3)
     * @ORM\ManyToMany(targetEntity=Item::class, inversedBy="fiches")
     * @ORM\JoinColumn(nullable=false,referencedColumnName="id")
     * @Assert\Count(
     *      min = "1",
     *      minMessage = "Vous devez sélectionner au moins 1 élément"
     * )
     * @Groups({"read:fiche"})
     */
    private $items;

    
    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     * @Groups({"read:fiche"})
     */
    private $type;

    /**
     * @ApiSubresource(maxDepth=3)
     * @ORM\OneToMany(targetEntity=NoeudAcceptance::class, mappedBy="fiche")
     */
    private $noeudAcceptances;


    public function __construct()
    {
        $this->noeudAcceptances = new ArrayCollection();
        $this->items = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(?string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Collection|Item[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
        }

        return $this;
    }

    public function removeItem(Item $item): self
    {
        if ($this->items->contains($item)) {
            $this->items->removeElement($item);
        }

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

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
            $noeudAcceptance->setFiche($this);
        }

        return $this;
    }

    public function removeNoeudAcceptance(NoeudAcceptance $noeudAcceptance): self
    {
        if ($this->noeudAcceptances->contains($noeudAcceptance)) {
            $this->noeudAcceptances->removeElement($noeudAcceptance);
            // set the owning side to null (unless already changed)
            if ($noeudAcceptance->getFiche() === $this) {
                $noeudAcceptance->setFiche(null);
            }
        }

        return $this;
    }
}
