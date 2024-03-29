<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity
 */
class User 
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
     * @ORM\Column(name="fullName", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     * @Assert\Length(max=255)
     */
    private $fullname;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     * @Assert\Email
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="mdp", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     * @Assert\Length(min=8, max=255)
     */
    private $mdp;

    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string", length=255, nullable=false)
     * @Assert\NotBlank
     * @Assert\Length(max=255)
     *  * @Assert\Regex(
     *      pattern="/^\d{8}$/",
     *      message="Le numéro de téléphone doit contenir exactement 8 chiffres."
     * )
     */
    private $tel;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=false)
     * @Assert\Length(max=255)
     */
    private $image;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_blocked", type="boolean", nullable=false)
     */
    private $isBlocked = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_approved", type="boolean", nullable=false, options={"default"="1"})
     */
    private $isApproved = true;
    // Getters and setters for id, fullname, email, tel, image, isBlocked, isApproved

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function setFullname(string $fullname): self
    {
        $this->fullname = $fullname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getMdp(): ?string
    {
        return $this->mdp;
    }

    public function setMdp(string $mdp): self
{
    $this->mdp = $mdp;

    return $this;
}


    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(string $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function isBlocked(): ?bool
    {
        return $this->isBlocked;
    }

    public function setIsBlocked(bool $isBlocked): self
    {
        $this->isBlocked = $isBlocked;

        return $this;
    }

    public function isApproved(): ?bool
    {
        return $this->isApproved;
    }

    public function setIsApproved(bool $isApproved): self
    {
        $this->isApproved = $isApproved;

        return $this;
    }

    // Methods required by UserInterface

    public function getUsername(): ?string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->mdp;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

 
}
