<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use SFM\AcceptanceBundle\Entity\TracabilityResponse;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="fos_user")
 * @UniqueEntity("phone")
 * @UniqueEntity("email")
 * @UniqueEntity("username")
 */
class User extends BaseUser
{
    CONST GENRE = [
        0 => "Masculin",
        1 => "Féminin"
    ];


    CONST STATUT = [
        0 => "Bloqué",
        1 => "Actif",
    ];


    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups({"read:noeud"})
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Groups({"read:noeud"})
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Groups({"read:noeud"})
     */
    private $lastName;

    /**
  
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/^[0-9]*$/",
     * message="Cette valeur n'est pas un numéro de téléphone valide."
     * )
     * @Assert\Length(
     *      min = 8,
     *      max = 8,
     *      minMessage = "Le nombre minimal des chiffres est {{ limit }}",
     *      maxMessage = "Le nombre maximal des chiffres est {{ limit }}"
     * )
     */
    private $phone;

    /**
     * @ORM\Column(type="string",nullable=true, length=255)
     */
    private $path;

    /**
     * @ORM\Column(type="boolean",nullable=true)
     */
    private $isAccountEmailSend = 0;


    /**
     * @ORM\ManyToOne(targetEntity=Groupe::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false,referencedColumnName="id")
     * @Assert\NotBlank()
     */
    private $groupe;


     /**
     * @ORM\ManyToOne(targetEntity=Fonction::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false,referencedColumnName="id")
     * @Assert\NotBlank()
     */
    private $fonction;

    /**
     * @ORM\Column(type="integer")
     */
    private $sexe;

    /**
     * @ORM\OneToMany(targetEntity=NoeudAcceptance::class, mappedBy="userCreator")
     * @ApiSubresource(maxDepth=1)
     */
    private $noeudAcceptances;

    /**
     * @ORM\OneToMany(targetEntity=Tracability::class, mappedBy="user")
     * @ApiSubresource(maxDepth=1)
     */
    private $tracabilities;

    /**
     * @ORM\OneToMany(targetEntity=Mission::class, mappedBy="userCreator")
     * @ApiSubresource(maxDepth=1)
     */
    private $missions;

    /**
     * @ORM\OneToMany(targetEntity=Affectation::class, mappedBy="userCreator")
     * @ApiSubresource(maxDepth=1)
     */
    private $affectations;

    /**
     * @ORM\OneToMany(targetEntity=TracabilityReponse::class, mappedBy="userCreator")
     * @ApiSubresource(maxDepth=1)
     */
    private $tracabilityReponses;

    /**
     * @ORM\OneToMany(targetEntity=CommentaireSite::class, mappedBy="userCreator")
     */
    private $commentaireSites;

    /**
     * @ORM\OneToMany(targetEntity=ImageSite::class, mappedBy="userCreator")
     */
    private $imageSites;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $deviseToken;


  

   
  

    public function __construct()
    {
        parent::__construct();
        $this->noeudAcceptances = new ArrayCollection();
        $this->tracabilities = new ArrayCollection();
        $this->missions = new ArrayCollection();
        $this->affectations = new ArrayCollection();
        $this->tracabilityReponses = new ArrayCollection();
        $this->commentaireSites = new ArrayCollection();
        $this->imageSites = new ArrayCollection();
        $this->commentaireSites = new ArrayCollection();
    }

   

    

    public function getFullName(): ?string
    {
        return $this->firstName.' '.$this->lastName;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getIsAccountEmailSend(): ?bool
    {
        return $this->isAccountEmailSend;
    }

    public function setIsAccountEmailSend(bool $isAccountEmailSend): self
    {
        $this->isAccountEmailSend = $isAccountEmailSend;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGroupe(): ?Groupe
    {
        return $this->groupe;
    }

    public function setGroupe(?Groupe $groupe): self
    {
        $this->groupe = $groupe;

        return $this;
    }

    public function getFonction(): ?Fonction
    {
        return $this->fonction;
    }

    public function setFonction(?Fonction $fonction): self
    {
        $this->fonction = $fonction;

        return $this;
    }

    public function getSexe(): ?int
    {
        return $this->sexe;
    }

    public function setSexe(int $sexe): self
    {
        $this->sexe = $sexe;

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
            $noeudAcceptance->setUserCreator($this);
        }

        return $this;
    }

    public function removeNoeudAcceptance(NoeudAcceptance $noeudAcceptance): self
    {
        if ($this->noeudAcceptances->contains($noeudAcceptance)) {
            $this->noeudAcceptances->removeElement($noeudAcceptance);
            // set the owning side to null (unless already changed)
            if ($noeudAcceptance->getUserCreator() === $this) {
                $noeudAcceptance->setUserCreator(null);
            }
        }

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
            $tracability->setUser($this);
        }

        return $this;
    }

    public function removeTracability(Tracability $tracability): self
    {
        if ($this->tracabilities->contains($tracability)) {
            $this->tracabilities->removeElement($tracability);
            // set the owning side to null (unless already changed)
            if ($tracability->getUser() === $this) {
                $tracability->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Mission[]
     */
    public function getMissions(): Collection
    {
        return $this->missions;
    }

    public function addMission(Mission $mission): self
    {
        if (!$this->missions->contains($mission)) {
            $this->missions[] = $mission;
            $mission->setUserCreator($this);
        }

        return $this;
    }

    public function removeMission(Mission $mission): self
    {
        if ($this->missions->contains($mission)) {
            $this->missions->removeElement($mission);
            // set the owning side to null (unless already changed)
            if ($mission->getUserCreator() === $this) {
                $mission->setUserCreator(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Affectation[]
     */
    public function getAffectations(): Collection
    {
        return $this->affectations;
    }

    public function addAffectation(Affectation $affectation): self
    {
        if (!$this->affectations->contains($affectation)) {
            $this->affectations[] = $affectation;
            $affectation->setLivreur($this);
        }

        return $this;
    }

    public function removeAffectation(Affectation $affectation): self
    {
        if ($this->affectations->contains($affectation)) {
            $this->affectations->removeElement($affectation);
            // set the owning side to null (unless already changed)
            if ($affectation->getLivreur() === $this) {
                $affectation->setLivreur(null);
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
            $tracabilityReponse->setUserCreator($this);
        }

        return $this;
    }

    public function removeTracabilityReponse(TracabilityReponse $tracabilityReponse): self
    {
        if ($this->tracabilityReponses->contains($tracabilityReponse)) {
            $this->tracabilityReponses->removeElement($tracabilityReponse);
            // set the owning side to null (unless already changed)
            if ($tracabilityReponse->getUserCreator() === $this) {
                $tracabilityReponse->setUserCreator(null);
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
            $imageSite->setUserCreator($this);
        }

        return $this;
    }

    public function removeImageSite(ImageSite $imageSite): self
    {
        if ($this->imageSites->contains($imageSite)) {
            $this->imageSites->removeElement($imageSite);
            // set the owning side to null (unless already changed)
            if ($imageSite->getUserCreator() === $this) {
                $imageSite->setUserCreator(null);
            }
        }

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
            $commentaireSite->setUserCreator($this);
        }

        return $this;
    }

    public function removeCommentaireSite(CommentaireSite $commentaireSite): self
    {
        if ($this->commentaireSites->contains($commentaireSite)) {
            $this->commentaireSites->removeElement($commentaireSite);
            // set the owning side to null (unless already changed)
            if ($commentaireSite->getUserCreator() === $this) {
                $commentaireSite->setUserCreator(null);
            }
        }

        return $this;
    }

 

    public function getDeviseToken(): ?string
    {
        return $this->deviseToken;
    }

    public function setDeviseToken(?string $deviseToken): self
    {
        $this->deviseToken = $deviseToken;

        return $this;
    }


    
    

   
 
}