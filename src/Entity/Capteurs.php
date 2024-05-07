<?php

namespace App\Entity;

use App\Repository\CapteursRepository;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=CapteursRepository::class)
 * @UniqueEntity("nom", message="Ce nom de capteur est déjà utilisé.")
 */

#[ORM\Entity(repositoryClass: CapteursRepository::class)]
class Capteurs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $nom;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $type;

    #[ORM\Column(type: 'float')]
    private ?float $latitude;

    #[ORM\Column(type: 'float')]
    private ?float $longitude;

    #[ORM\Column(type: 'string')]
    private ?string $dateInstallation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getDateInstallation(): ?string
    {
        return $this->dateInstallation;
    }

    public function setDateInstallation(?string $dateInstallation): self
    {
        $this->dateInstallation = $dateInstallation;

        return $this;
    }

    
}
