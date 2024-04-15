<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;

#[\Doctrine\ORM\Mapping\Entity(repositoryClass: App\Repository\TrafficRepository::class)]
#[\Doctrine\ORM\Mapping\Table(name: "traffic")]
class Traffic
{
    #[\Doctrine\ORM\Mapping\Id]
    #[\Doctrine\ORM\Mapping\GeneratedValue(strategy: "IDENTITY")]
    #[\Doctrine\ORM\Mapping\Column(name: "ID", type: "integer", nullable: false)]
    private $id;

    #[\Doctrine\ORM\Mapping\Column(name: "Heure", type: "datetime", nullable: true)]
    private $heure;

    #[\Doctrine\ORM\Mapping\Column(name: "Localisation", type: "string", length: 255, nullable: true)]
    private $localisation;

    #[\Doctrine\ORM\Mapping\Column(name: "Commentaire", type: "text", length: 65535, nullable: true)]
    private $commentaire;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHeure(): ?\DateTimeInterface
    {
        return $this->heure;
    }

    public function setHeure(?\DateTimeInterface $heure): static
    {
        $this->heure = $heure;

        return $this;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(?string $localisation): static
    {
        $this->localisation = $localisation;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }
}
