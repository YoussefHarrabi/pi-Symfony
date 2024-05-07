<?php

namespace App\Entity;
use App\Entity\Capteurs;
use App\Repository\DonneeshistoriquesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DonneeshistoriquesRepository::class)]
class Donneeshistoriques
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'integer')]
    private ?int $idCapteur;

    #[ORM\Column(type: 'string')]
    private ?string $timestamp;

    #[ORM\Column(type: 'integer')]
    private ?int $niveauEmbouteillage;

    #[ORM\Column(type: 'string')]
    private ?string $alerte;

    #[ORM\Column(type: 'string')]
    private ?string $conditionsMeteo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdCapteur(): ?int
    {
        return $this->idCapteur;
    }

    public function setIdCapteur(?int $idCapteur): self
    {
        $this->idCapteur = $idCapteur;

        return $this;
    }

    public function getTimestamp(): ?string
    {
        return $this->timestamp;
    }

    public function setTimestamp(?string $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function getNiveauEmbouteillage(): ?int
    {
        return $this->niveauEmbouteillage;
    }

    public function setNiveauEmbouteillage(?int $niveauEmbouteillage): self
    {
        $this->niveauEmbouteillage = $niveauEmbouteillage;

        return $this;
    }

    public function getAlerte(): ?string
    {
        return $this->alerte;
    }

    public function setAlerte(?string $alerte): self
    {
        $this->alerte = $alerte;

        return $this;
    }

    public function getConditionsMeteo(): ?string
    {
        return $this->conditionsMeteo;
    }

    public function setConditionsMeteo(?string $conditionsMeteo): self
    {
        $this->conditionsMeteo = $conditionsMeteo;

        return $this;
    }

    public function getLatitudeByIdCapteur(int $idCapteur): ?float
    {
        $repository = $this->getDoctrine()->getRepository(Donneeshistoriques::class);
        return $repository->findLatitudeByIdCapteur($idCapteur);
    }
    
    public function getLangitudeByIdCapteur(int $idCapteur): ?float
    {
        $repository = $this->getDoctrine()->getRepository(Donneeshistoriques::class);
        return $repository->findLongitudeByIdCapteur($idCapteur);
    }
}
