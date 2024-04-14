<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: App\Repository\ProposedAlternativeRouteRepository::class)]

class ProposedAlternativeRoute
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "IDit", type: "integer", nullable: false)]
    private $idit;

    #[ORM\Column(name: "Description", type: "text", length: 65535, nullable: true)]
    private $description;

    #[ORM\Column(name: "HeureProposee", type: "datetime", nullable: true)]
    private $heureproposee;

    #[ORM\Column(name: "Accepte", type: "boolean", nullable: true)]
    private $accepte;

    #[ORM\ManyToOne(targetEntity: Traffic::class)]
    #[ORM\JoinColumn(name: "trafficID", referencedColumnName: "ID")]
    private $trafficid;

    #[ORM\ManyToOne(targetEntity: Utilisateurs::class)]
    #[ORM\JoinColumn(name: "UtilisateurID", referencedColumnName: "ID")]
    private $utilisateurid;

    #[ORM\ManyToOne(targetEntity: Incident::class)]
    #[ORM\JoinColumn(name: "IncidentID", referencedColumnName: "IncidentId")]
    private $incidentid;

    public function getIdit(): ?int
    {
        return $this->idit;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getHeureproposee(): ?\DateTimeInterface
    {
        return $this->heureproposee;
    }

    public function setHeureproposee(?\DateTimeInterface $heureproposee): static
    {
        $this->heureproposee = $heureproposee;

        return $this;
    }

    public function isAccepte(): ?bool
    {
        return $this->accepte;
    }

    public function setAccepte(?bool $accepte): static
    {
        $this->accepte = $accepte;

        return $this;
    }

    public function getTrafficid(): ?Traffic
    {
        return $this->trafficid;
    }

    public function setTrafficid(?Traffic $trafficid): static
    {
        $this->trafficid = $trafficid;

        return $this;
    }

    public function getUtilisateurid(): ?Utilisateurs
    {
        return $this->utilisateurid;
    }

    public function setUtilisateurid(?Utilisateurs $utilisateurid): static
    {
        $this->utilisateurid = $utilisateurid;

        return $this;
    }

    public function getIncidentid(): ?Incident
    {
        return $this->incidentid;
    }

    public function setIncidentid(?Incident $incidentid): static
    {
        $this->incidentid = $incidentid;

        return $this;
    }
}
