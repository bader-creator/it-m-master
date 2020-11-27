<?php

namespace App\Entity;

use App\Repository\ValidationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ValidationRepository::class)
 */
class Validation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank()
     */
    private $dateValidation;
      /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $validate;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $typeUser;

    /**
     * @ORM\ManyToOne(targetEntity=Affectation::class, inversedBy="validations")
     * @Assert\NotBlank()
     */
    private $affectation;

    /**
     * @ORM\ManyToOne(targetEntity=Materiel::class, inversedBy="validations")
     * @Assert\NotBlank()
     */
    private $materiel;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $quantiteAjoute;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $quantiteSupprimer;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $quantiteCasse;

    /**
     * @ORM\OneToMany(targetEntity=Photo::class, mappedBy="validation")
     */
    private $photos;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="validation")
     */
    private $commentaires;

    public function __construct()
    {
        $this->photos = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateValidation(): ?\DateTimeInterface
    {
        return $this->dateValidation;
    }

    public function setDateValidation(\DateTimeInterface $dateValidation): self
    {
        $this->dateValidation = $dateValidation;

        return $this;
    }

    public function getTypeUser(): ?int
    {
        return $this->typeUser;
    }

    public function setTypeUser(int $typeUser): self
    {
        $this->typeUser = $typeUser;

        return $this;
    }
    public function getValidate(): ?int
    {
        return $this->validate;
    }

    public function setValidate(int $validate): self
    {
        $this->validate = $validate;

        return $this;
    }

    public function getAffectation(): ?Affectation
    {
        return $this->affectation;
    }

    public function setAffectation(?Affectation $affectation): self
    {
        $this->affectation = $affectation;

        return $this;
    }

    public function getMateriel(): ?Materiel
    {
        return $this->materiel;
    }

    public function setMateriel(?Materiel $materiel): self
    {
        $this->materiel = $materiel;

        return $this;
    }

    public function getQuantiteAjoute(): ?int
    {
        return $this->quantiteAjoute;
    }

    public function setQuantiteAjoute(int $quantiteAjoute): self
    {
        $this->quantiteAjoute = $quantiteAjoute;

        return $this;
    }

    public function getQuantiteSupprimer(): ?int
    {
        return $this->quantiteSupprimer;
    }

    public function setQuantiteSupprimer(int $quantiteSupprimer): self
    {
        $this->quantiteSupprimer = $quantiteSupprimer;

        return $this;
    }

    public function getQuantiteCasse(): ?int
    {
        return $this->quantiteCasse;
    }

    public function setQuantiteCasse(int $quantiteCasse): self
    {
        $this->quantiteCasse = $quantiteCasse;

        return $this;
    }

    /**
     * @return Collection|Photo[]
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(Photo $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos[] = $photo;
            $photo->setValidation($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): self
    {
        if ($this->photos->contains($photo)) {
            $this->photos->removeElement($photo);
            // set the owning side to null (unless already changed)
            if ($photo->getValidation() === $this) {
                $photo->setValidation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Commentaire[]
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setValidation($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->contains($commentaire)) {
            $this->commentaires->removeElement($commentaire);
            // set the owning side to null (unless already changed)
            if ($commentaire->getValidation() === $this) {
                $commentaire->setValidation(null);
            }
        }

        return $this;
    }
}
