<?php

namespace App\Entity;

use App\Repository\TracabilityReponseRepository;
use Doctrine\ORM\Mapping as ORM;

/**
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
     * @ORM\ManyToOne(targetEntity=reponse::class, inversedBy="tracabilityReponses")
     */
    private $reponse;

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
}
