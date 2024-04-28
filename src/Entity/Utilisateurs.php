<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UtilisateurRepository;
use DateTime;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Table(name: "utilisateurs")]
#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
class Utilisateurs implements UserInterface
{
    #[ORM\Column(name: "ID", type: "integer", nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private int $id;

    #[ORM\Column(name: "Nom", type: "string", length: 255, nullable: false)]
    private string $nom;

    #[ORM\Column(name: "Prenom", type: "string", length: 255, nullable: false)]
    private string $prenom;

    #[ORM\Column(name: "Email", type: "string", length: 255, nullable: false)]
    private string $email;

    #[ORM\Column(name: "mot_de_passe", type: "string", length: 255, nullable: false)]
    private string $motDePasse;

    #[ORM\Column(name: "role", type: "string", length: 255, nullable: false)]
    private string $role;

    #[ORM\Column(name: "verification_code", type: "string", length: 10, nullable: true)]
    private ?string $verificationCode;

    #[ORM\Column(name: "age", type: "string", length: 255, nullable: true)]
    private ?string $age;

    #[ORM\Column(name: "is_valid", type: "boolean", nullable: true)]
    private ?bool $isValid;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getMotDePasse(): ?string
    {
        return $this->motDePasse;
    }

    public function setMotDePasse(string $motDePasse): static
    {
        $this->motDePasse = $motDePasse;
        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;
        return $this;
    }

    public function getVerificationCode(): ?string
    {
        return $this->verificationCode;
    }

    public function setVerificationCode(?string $verificationCode): static
    {
        $this->verificationCode = $verificationCode;
        return $this;
    }

    public function getAge(): ?string
    {
        return $this->age;
    }

    public function setAge(?\DateTimeInterface $age): static
    {
        if ($age instanceof \DateTimeInterface) {
            // Convert DateTime to string representation of age
            $this->age = $age->format('Y-m-d');
        } else {
            // Handle other cases, such as null
            $this->age = $age;
        }
        return $this;
    }

    public function isIsvalid(): ?bool
    {
        return $this->isvalid;
    }

    public function setIsvalid(?bool $isvalid): static
    {
        $this->isvalid = $isvalid;
        return $this;
    }

    // Implement UserInterface methods
    public function getUsername(): string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        // Return the user's roles as an array
        return [$this->role];
    }

    public function getPassword(): string
    {
        return $this->motDePasse;
    }

    public function getSalt()
    {
        // Return null if you're using modern password hashing methods like bcrypt
        return null;
    }

    public function eraseCredentials()
    {
        // This method is only needed if you're storing sensitive data that should be cleared after authentication
        // Since Symfony's default behavior handles this automatically, leave this method empty
    }
}
