<?php

namespace App\Entity;

use App\Repository\TracabilityRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=TracabilityRepository::class)
 */
class Tracability
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    

    /**
     
     * @ORM\Column(type="integer")
     * 
     * @Assert\NotBlank()
     */
    private $quantiteFinale;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank()
     */
    private $dateAction;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $typeAction;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="tracabilities")
     * @Assert\NotBlank()
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Stock::class, inversedBy="tracabilities")
     * @Assert\NotBlank()
     */
    private $stock;

    public function getId(): ?int
    {
        return $this->id;
    }

    
    public function getQuantiteFinale(): ?int
    {
        return $this->quantiteFinale;
    }

    public function setQuantiteFinale(?int $quantiteFinale): self
    {
        $this->quantiteFinale = $quantiteFinale;

        return $this;
    }

    public function getDateAction(): ?\DateTimeInterface
    {
        return $this->dateAction;
    }

    public function setDateAction(?\DateTimeInterface $dateAction): self
    {
        $this->dateAction = $dateAction;

        return $this;
    }

    public function getTypeAction(): ?string
    {
        return $this->typeAction;
    }

    public function setTypeAction(?string $typeAction): self
    {
        $this->typeAction = $typeAction;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getStock(): ?Stock
    {
        return $this->stock;
    }

    public function setStock(?Stock $stock): self
    {
        $this->stock = $stock;

        return $this;
    }
}
