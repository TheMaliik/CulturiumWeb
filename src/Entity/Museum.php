<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraints as CustomAssert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * Museum
 *
 * @ORM\Table(name="museum")
 * @ORM\Entity
 * @Vich\Uploadable
 * @UniqueEntity(fields={"name"}, message="Ce nom de musée est déjà utilisé.")
 */
class Museum
{
    /**
     * @var int
     *
     * @ORM\Column(name="IdM", type="integer", nullable=false, unique=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idm;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     * @CustomAssert\UniqueMuseumName
     */
    private $name;


    /**
     * @Vich\UploadableField(mapping="museum_images", fileNameProperty="image")
     * @var File|null
     */
    private $image;

   

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="La description du musée est obligatoire")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="localisation", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="La localisation du musée est obligatoire")
     */
    private $localisation;


    public function getIdm(): ?int
    {
        return $this->idm;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        if ($image) {
            $this->updatedAt = new \DateTimeImmutable();
        }
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

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(string $localisation): self
    {
        $this->localisation = $localisation;

        return $this;
    }

   
    

    public function __toString(): string
    {
        return $this->name;
    }
}
