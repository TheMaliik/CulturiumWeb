<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\LivraisonRepository;

#[ORM\Entity(repositoryClass: LivraisonRepository::class)]
class Livraison
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(name: 'dateDeLivraison', type: 'date')]
    private $dateDeLivraison;
    

    #[ORM\Column(type: 'string', length: 50)]
    #[Assert\NotBlank(message: "Ce champ ne peut pas être vide.")]
    private $statut;

    #[ORM\Column(type: 'string', length: 50)]
    #[Assert\NotBlank(message: "Ce champ ne peut pas être vide.")]
    private $depot;

    #[ORM\ManyToOne(targetEntity: Adresse::class, inversedBy: 'livraisons')]
    #[ORM\JoinColumn(name: 'idAdresse', referencedColumnName: 'id')]
    private $adresse;
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDeLivraison(): ?\DateTimeInterface
    {
        return $this->dateDeLivraison;
    }

    public function setDateDeLivraison(\DateTimeInterface $dateDeLivraison): self
    {
        $this->dateDeLivraison = $dateDeLivraison;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getDepot(): ?string
    {
        return $this->depot;
    }

    public function setDepot(string $depot): self
    {
        $this->depot = $depot;

        return $this;
    }

    public function getAdresse(): ?Adresse
    {
        return $this->adresse;
    }

    public function setAdresse(?Adresse $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

  

}
