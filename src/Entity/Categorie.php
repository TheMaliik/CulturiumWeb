<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert; 

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategorieRepository")
 * @ORM\Table(name="categorie", indexes={@ORM\Index(name="ffff_key", columns={"id_oeuvre"})})
 */
class Categorie
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
     * @ORM\Column(name="type_oeuvre", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Type Oeuvre cannot be blank")
     */
    private $typeOeuvre;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Description cannot be blank")
     */
    private $description;

    /**
     * @var \Oeuvre|null
     *
     * @ORM\ManyToOne(targetEntity="Oeuvre")
     * @ORM\JoinColumn(name="id_oeuvre", referencedColumnName="id")
     */
    private $idOeuvre;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeOeuvre(): ?string
    {
        return $this->typeOeuvre;
    }

    public function setTypeOeuvre(string $typeOeuvre): self
    {
        $this->typeOeuvre = $typeOeuvre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getIdOeuvre(): ?Oeuvre
    {
        return $this->idOeuvre;
    }

    public function setIdOeuvre(?Oeuvre $idOeuvre): self
    {
        $this->idOeuvre = $idOeuvre;

        return $this;
    }
}
