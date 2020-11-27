<?php

namespace App\Entity;

use App\Repository\ArmoireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\ValeurNomenclature;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use ApiPlatform\Core\Annotation\ApiResource;
/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=ArmoireRepository::class)
 * @UniqueEntity("name")
 */
class Armoire
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
    private $numero;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $name;

     /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ValeurNomenclature",inversedBy="armoire")
     * @ORM\JoinColumn(name="typeArmoireId", nullable=false, referencedColumnName="id")
     * @Assert\NotBlank()
     */
    private $typeArmoire;
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/^(\+|-)?(?:180(?:(?:\.0{1,6})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,6})?))$/"),
     * message="Cette valeur n'est pas une longitude valide."
     */
    private $longitude;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/^(\+|-)?(?:90(?:(?:\.0{1,6})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,6})?))$/"),
     * message="Cette valeur n'est pas une latitude valide."
     */
    private $latitude;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $adress;

    /**
     * @ORM\ManyToOne(targetEntity=Region::class, inversedBy="armoires")
     * @ORM\JoinColumn(nullable=false,referencedColumnName="id")
     * @Assert\NotBlank()
     */
    private $region;

 

    public function __construct()
    {
        $this->noeudAcceptances = new ArrayCollection();
        
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(?string $numero): self
    {
        $this->numero = $numero;

        return $this;
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(?string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(?string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getTypeArmoire(): ?ValeurNomenclature
    {
        return $this->typeArmoire;
    }

    public function setTypeArmoire(?ValeurNomenclature $typeArmoire): self
    {
        $this->typeArmoire = $typeArmoire;

        return $this;
    }

    /**
     * @return Collection|NoeudAcceptance[]
     */
    public function getNoeudAcceptances(): Collection
    {
        return $this->noeudAcceptances;
    }


   

   

}
