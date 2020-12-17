<?php

namespace App\Entity;

use App\Repository\TracabilityReponseRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use ApiPlatform\Core\Annotation\ApiResource;
/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=TracabilityReponseRepository::class)
 */
class TracabilityReponse
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Reponse::class, inversedBy="tracabilityReponses")
     */
    private $reponse;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $valeur;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreation;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="tracabilityReponses")
     */
    private $userCreator;

   


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReponse(): ?reponse
    {
        return $this->reponse;
    }

    public function setReponse(?reponse $reponse): self
    {
        $this->reponse = $reponse;

        return $this;
    }

    public function getValeur(): ?string
    {
        return $this->valeur;
    }

    public function setValeur(string $valeur): self
    {
        $this->valeur = $valeur;

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

    public function getUserCreator(): ?User
    {
        return $this->userCreator;
    }

    public function setUserCreator(?User $userCreator): self
    {
        $this->userCreator = $userCreator;

        return $this;
    }

  
 



    
}
