<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Livraison
 *
 * @ORM\Table(name="livraison", indexes={@ORM\Index(name="ffk_key", columns={"idAdresse"})})
 * @ORM\Entity
 */
class Livraison
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="jourDeLivraison", type="string", length=50, nullable=false)
     */
    private $jourdelivraison;

    /**
     * @var string
     *
     * @ORM\Column(name="statut", type="string", length=50, nullable=false)
     */
    private $statut;

    /**
     * @var string
     *
     * @ORM\Column(name="depot", type="string", length=50, nullable=false)
     */
    private $depot;

    /**
     * @var \Adresse
     *
     * @ORM\ManyToOne(targetEntity="Adresse")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idAdresse", referencedColumnName="id")
     * })
     */
    private $idadresse;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJourdelivraison(): ?string
    {
        return $this->jourdelivraison;
    }

    public function setJourdelivraison(string $jourdelivraison): static
    {
        $this->jourdelivraison = $jourdelivraison;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getDepot(): ?string
    {
        return $this->depot;
    }

    public function setDepot(string $depot): static
    {
        $this->depot = $depot;

        return $this;
    }

    public function getIdadresse(): ?Adresse
    {
        return $this->idadresse;
    }

    public function setIdadresse(?Adresse $idadresse): static
    {
        $this->idadresse = $idadresse;

        return $this;
    }


}
