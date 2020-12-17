<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiSubresource;
/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=QuestionRepository::class)
 * @UniqueEntity("label")
 */
class Question
{

    CONST Type = [
        0 => "Select",
        1 => "Input"
    ];

    CONST CRITCITY = [
        0 => "Mineure",
        1 => "Majeure",
        2 => "Bloquante",
    ];


    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *  @Groups({"read:fiche"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     *  @Groups({"read:fiche"})
     */
    private $label;

    /**
     * @ApiSubresource(maxDepth=1)
     * @ORM\ManyToMany(targetEntity=Choix::class, inversedBy="questions")
     * @ORM\JoinColumn(nullable=false,referencedColumnName="id")
     * @Groups({"read:fiche"})
     */
    private $choix;

    /**
     * @ApiSubresource(maxDepth=1)
     * @ORM\ManyToMany(targetEntity=SousItem::class, mappedBy="questions")
     */
    private $sousItems;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     *  @Groups({"read:fiche"})
     */
    private $type;

    /**
     * @ORM\Column(type="integer")
     *  @Groups({"read:fiche"})
     */
    private $criticity;

    /**
     * @ORM\OneToMany(targetEntity=Reponse::class, mappedBy="question")
     */
    private $reponses;

    public function __construct()
    {
        $this->choix = new ArrayCollection();
        $this->sousItems = new ArrayCollection();
        $this->reponses = new ArrayCollection();
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
     * @return Collection|Choix[]
     */
    public function getChoix(): Collection
    {
        return $this->choix;
    }

    public function addChoix(Choix $choix): self
    {
        if (!$this->choix->contains($choix)) {
            $this->choix[] = $choix;
        }

        return $this;
    }

    public function removeChoix(Choix $choix): self
    {
        if ($this->choix->contains($choix)) {
            $this->choix->removeElement($choix);
        }

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
            $sousItem->addQuestion($this);
        }

        return $this;
    }

    public function removeSousItem(SousItem $sousItem): self
    {
        if ($this->sousItems->contains($sousItem)) {
            $this->sousItems->removeElement($sousItem);
            $sousItem->removeQuestion($this);
        }

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(?int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getCriticity(): ?int
    {
        return $this->criticity;
    }

    public function setCriticity(?int $criticity): self
    {
        $this->criticity = $criticity;

        return $this;
    }

    /**
     * @return Collection|Reponse[]
     */
    public function getReponses(): Collection
    {
        return $this->reponses;
    }

    public function addReponse(Reponse $reponse): self
    {
        if (!$this->reponses->contains($reponse)) {
            $this->reponses[] = $reponse;
            $reponse->setQuestion($this);
        }

        return $this;
    }

    public function removeReponse(Reponse $reponse): self
    {
        if ($this->reponses->contains($reponse)) {
            $this->reponses->removeElement($reponse);
            // set the owning side to null (unless already changed)
            if ($reponse->getQuestion() === $this) {
                $reponse->setQuestion(null);
            }
        }

        return $this;
    }
    public function  getCriticityValue(): string
    {
      return self::CRITCITY[$this->criticity];
    }
  
}
