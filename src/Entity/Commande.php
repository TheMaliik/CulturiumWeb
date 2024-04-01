<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Commande
 *
 * @ORM\Table(name="commande")
 * @ORM\Entity
 */
class Commande
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
     * @var \DateTime
     *
     * @ORM\Column(name="Date", type="date", nullable=false)
     */
    private $date;

    /**
     * @var float
     *
     * @ORM\Column(name="montant_totale", type="float", precision=10, scale=0, nullable=false)
     */
    private $montantTotale;

    /**
     * @var string
     *
     * @ORM\Column(name="addresse_livraison", type="string", length=255, nullable=false)
     */
    private $addresseLivraison;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getMontantTotale(): ?float
    {
        return $this->montantTotale;
    }

    public function setMontantTotale(float $montantTotale): static
    {
        $this->montantTotale = $montantTotale;

        return $this;
    }

    public function getAddresseLivraison(): ?string
    {
        return $this->addresseLivraison;
    }

    public function setAddresseLivraison(string $addresseLivraison): static
    {
        $this->addresseLivraison = $addresseLivraison;

        return $this;
    }


}
