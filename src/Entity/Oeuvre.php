<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Oeuvre
 *
 * @ORM\Table(name="oeuvre")
 * @ORM\Entity
 */
class Oeuvre
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
     * @ORM\Column(name="image", type="string", length=255, nullable=false)
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_artiste", type="string", length=255, nullable=false)
     */
    private $nomArtiste;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_creation", type="date", nullable=false)
     */
    private $dateCreation;

    /**
     * @var int
     *
     * @ORM\Column(name="reference", type="integer", nullable=false)
     */
    private $reference;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float", precision=10, scale=0, nullable=false)
     */
    private $prix;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_oeuvre", type="string", length=255, nullable=false)
     */
    private $nomOeuvre;

    /**
     * @var string
     *
     * @ORM\Column(name="type_oeuvre", type="string", length=255, nullable=false)
     */
    private $typeOeuvre;

    /**
     * @var string
     *
     * @ORM\Column(name="LinkHttp", type="string", length=255, nullable=false)
     */
    private $linkhttp;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getNomArtiste(): ?string
    {
        return $this->nomArtiste;
    }

    public function setNomArtiste(string $nomArtiste): static
    {
        $this->nomArtiste = $nomArtiste;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): static
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getReference(): ?int
    {
        return $this->reference;
    }

    public function setReference(int $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getNomOeuvre(): ?string
    {
        return $this->nomOeuvre;
    }

    public function setNomOeuvre(string $nomOeuvre): static
    {
        $this->nomOeuvre = $nomOeuvre;

        return $this;
    }

    public function getTypeOeuvre(): ?string
    {
        return $this->typeOeuvre;
    }

    public function setTypeOeuvre(string $typeOeuvre): static
    {
        $this->typeOeuvre = $typeOeuvre;

        return $this;
    }

    public function getLinkhttp(): ?string
    {
        return $this->linkhttp;
    }

    public function setLinkhttp(string $linkhttp): static
    {
        $this->linkhttp = $linkhttp;

        return $this;
    }


}
