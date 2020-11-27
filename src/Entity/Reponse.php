<?php

namespace App\Entity;

use App\Repository\ReponseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReponseRepository::class)
 */
class Reponse
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Choix::class, inversedBy="reponses")
     */
    private $choixReponse;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateReponse;

    /**
     * @ORM\ManyToOne(targetEntity=Question::class, inversedBy="reponses")
     */
    private $question;

    /**
     * @ORM\OneToMany(targetEntity=CommentaireSite::class, mappedBy="reponse")
     */
    private $commentaireSites;

    /**
     * @ORM\OneToMany(targetEntity=ImageSite::class, mappedBy="reponse")
     */
    private $imageSites;

    /**
     * @ORM\OneToMany(targetEntity=TracabilityReponse::class, mappedBy="reponse")
     */
    private $tracabilityReponses;

    /**
     * @ORM\Column(type="integer")
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reponse;



    public function __construct()
    {
        $this->commentaireSites = new ArrayCollection();
        $this->imageSites = new ArrayCollection();
        $this->tracabilityReponses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChoixReponse(): ?Choix
    {
        return $this->choixReponse;
    }

    public function setChoixReponse(?Choix $choixReponse): self
    {
        $this->choixReponse = $choixReponse;

        return $this;
    }

    public function getDateReponse(): ?\DateTimeInterface
    {
        return $this->dateReponse;
    }

    public function setDateReponse(\DateTimeInterface $dateReponse): self
    {
        $this->dateReponse = $dateReponse;

        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;    }

    public function setQuestion(?Question $question): self
    {
        $this->question = $question;

        return $this;
    }



    /**
     * @return Collection|CommentaireSite[]
     */
    public function getCommentaireSites(): Collection
    {
        return $this->commentaireSites;
    }

    public function addCommentaireSite(CommentaireSite $commentaireSite): self
    {
        if (!$this->commentaireSites->contains($commentaireSite)) {
            $this->commentaireSites[] = $commentaireSite;
            $commentaireSite->setReponse($this);
        }

        return $this;
    }

    public function removeCommentaireSite(CommentaireSite $commentaireSite): self
    {
        if ($this->commentaireSites->contains($commentaireSite)) {
            $this->commentaireSites->removeElement($commentaireSite);
            // set the owning side to null (unless already changed)
            if ($commentaireSite->getReponse() === $this) {
                $commentaireSite->setReponse(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ImageSite[]
     */
    public function getImageSites(): Collection
    {
        return $this->imageSites;
    }

    public function addImageSite(ImageSite $imageSite): self
    {
        if (!$this->imageSites->contains($imageSite)) {
            $this->imageSites[] = $imageSite;
            $imageSite->setReponse($this);
        }

        return $this;
    }

    public function removeImageSite(ImageSite $imageSite): self
    {
        if ($this->imageSites->contains($imageSite)) {
            $this->imageSites->removeElement($imageSite);
            // set the owning side to null (unless already changed)
            if ($imageSite->getReponse() === $this) {
                $imageSite->setReponse(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TracabilityReponse[]
     */
    public function getTracabilityReponses(): Collection
    {
        return $this->tracabilityReponses;
    }

    public function addTracabilityReponse(TracabilityReponse $tracabilityReponse): self
    {
        if (!$this->tracabilityReponses->contains($tracabilityReponse)) {
            $this->tracabilityReponses[] = $tracabilityReponse;
            $tracabilityReponse->setReponse($this);
        }

        return $this;
    }

    public function removeTracabilityReponse(TracabilityReponse $tracabilityReponse): self
    {
        if ($this->tracabilityReponses->contains($tracabilityReponse)) {
            $this->tracabilityReponses->removeElement($tracabilityReponse);
            // set the owning side to null (unless already changed)
            if ($tracabilityReponse->getReponse() === $this) {
                $tracabilityReponse->setReponse(null);
            }
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

    public function getReponse(): ?string
    {
        return $this->reponse;
    }

    public function setReponse(?string $reponse): self
    {
        $this->reponse = $reponse;

        return $this;
    }


}
