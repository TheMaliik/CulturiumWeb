<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\AdresseRepository;

#[ORM\Entity(repositoryClass: AdresseRepository::class)]
class Adresse
{
    #[ORM\Id]
    #[ORM\GeneratedValue] 
    #[ORM\Column]
    private $id;

    #[ORM\Column]
    #[Assert\NotBlank(message:"Ce champ ne peut pas être vide.")]
    private $idcommande;

    #[ORM\Column]
    #[Assert\NotBlank(message:"Ce champ ne peut pas être vide.")]
    private $adresse;

    #[ORM\Column]
    #[Assert\NotBlank(message:"Ce champ ne peut pas être vide.")]
    private $ville;

    #[ORM\Column]
    #[Assert\NotBlank(message:"Ce champ ne peut pas être vide.")]
    private $codepostal;

    

     /**
     * @ORM\Column(type="string", length=255)
     */
    private $search;

    /**
     * @ORM\OneToMany(targetEntity=Livraison::class, mappedBy="adresse")
     */
    private $livraisons;

    public function __construct()
    {
        $this->livraisons = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getIdcommande(): ?string
    {
        return $this->idcommande;
    }

    public function setIdcommande(string $idcommande): self
    {
        $this->idcommande = $idcommande;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getCodepostal(): ?string
    {
        return $this->codepostal;
    }

    public function setCodepostal(string $codepostal): self
    {
        $this->codepostal = $codepostal;

        return $this;
    }

      // Ajout des méthodes getter et setter pour la propriété search
      public function getSearch(): ?string
      {
          return $this->search;
      }
  
      public function setSearch(string $search): self
      {
          $this->search = $search;
  
          return $this;
      }

    /**
     * @return Collection|Livraison[]
     */
    public function getLivraisons(): Collection
    {
        return $this->livraisons;
    }

    public function addLivraison(Livraison $livraison): self
    {
        if (!$this->livraisons->contains($livraison)) {
            $this->livraisons[] = $livraison;
            $livraison->setAdresse($this);
        }

        return $this;
    }

    public function removeLivraison(Livraison $livraison): self
    {
        if ($this->livraisons->removeElement($livraison)) {
            // set the owning side to null (unless already changed)
            if ($livraison->getAdresse() === $this) {
                $livraison->setAdresse(null);
            }
        }

        return $this;
    }



/**
 * Returns a string representation of the Adresse entity.
 *
 * @return string
 */
public function __toString(): string
{
    return "{$this->adresse}, {$this->ville}, {$this->codepostal}";
}}
