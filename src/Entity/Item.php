<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Component\Serializer\Annotation\Groups;
/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=ItemRepository::class)
 * @UniqueEntity("label")
 */
class Item
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"read:fiche"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Groups({"read:fiche"})
     */
    private $label;

    /**
     * @ApiSubresource(maxDepth=3)
     * @ORM\ManyToMany(targetEntity=SousItem::class, inversedBy="items")
     * @ORM\JoinColumn(nullable=false,referencedColumnName="id")
     * @Assert\Count(
     *      min = "1",
     *      minMessage = "Vous devez sélectionner au moins 1 élément"
     * )
     * @Groups({"read:fiche"})
     */
    private $sousItems;

     /**
     * @ApiSubresource(maxDepth=3)
     * @ORM\ManyToMany(targetEntity=Fiche::class, mappedBy="items")
     */
    private $fiches;

    public function __construct()
    {
       
        $this->fiches = new ArrayCollection();
        $this->sousItems = new ArrayCollection();
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
     * @return Collection|SousItem[]
     */
    public function getSousItems(): Collection
    {
        return $this->sousItems;
    }

    public function addSousItem(SousItem $sousItem): self
    {
        if (!$this->sousItems->contains($sousItem)) {
            $this->sousItems[] = $sousItem;
        }

        return $this;
    }

    public function removeSousItem(SousItem $sousItem): self
    {
        if ($this->sousItems->contains($sousItem)) {
            $this->sousItems->removeElement($sousItem);
        }

        return $this;
    }

    /**
     * @return Collection|Fiche[]
     */
    public function getFiches(): Collection
    {
        return $this->fiches;
    }

    public function addFiche(Fiche $fiche): self
    {
        if (!$this->fiches->contains($fiche)) {
            $this->fiches[] = $fiche;
            $fiche->addItem($this);
        }

        return $this;
    }

    public function removeFiche(Fiche $fiche): self
    {
        if ($this->fiches->contains($fiche)) {
            $this->fiches->removeElement($fiche);
            $fiche->removeItem($this);
        }

        return $this;
    }

    public function addFich(Fiche $fich): self
    {
        if (!$this->fiches->contains($fich)) {
            $this->fiches[] = $fich;
            $fich->addItem($this);
        }

        return $this;
    }

    public function removeFich(Fiche $fich): self
    {
        if ($this->fiches->contains($fich)) {
            $this->fiches->removeElement($fich);
            $fich->removeItem($this);
        }

        return $this;
    }

    
}
