<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: App\Repository\TicketRepository::class)]

class Ticket
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "ticket_id", type: "integer", nullable: false)]
    private $ticketId;

    #[ORM\Column(name: "moyen_de_transport", type: "string", length: 255, nullable: true)]
    private $moyenDeTransport;

    #[ORM\Column(name: "prix", type: "decimal", precision: 10, scale: 2, nullable: true)]
    private $prix;

    #[ORM\Column(name: "date_d_achat", type: "date", nullable: true)]
    private $dateDAchat;

    #[ORM\Column(name: "depart_time", type: "time", nullable: true)]
    private $departTime;

    #[ORM\Column(name: "arrival_time", type: "time", nullable: true)]
    private $arrivalTime;

    #[ORM\Column(name: "destination", type: "string", length: 255, nullable: true)]
    private $destination;

    #[ORM\Column(name: "from_destination", type: "string", length: 255, nullable: true)]
    private $fromDestination;

    #[ORM\Column(name: "confirmation", type: "boolean", nullable: true)]
    private $confirmation;

    #[ORM\Column(name: "code", type: "string", length: 255, nullable: true)]
    private $code;

    #[ORM\ManyToOne(targetEntity: Utilisateurs::class)]
    #[ORM\JoinColumn(name: "utilisateur_id", referencedColumnName: "ID")]
    private $utilisateur;

    public function getTicketId(): ?int
    {
        return $this->ticketId;
    }

    public function getMoyenDeTransport(): ?string
    {
        return $this->moyenDeTransport;
    }

    public function setMoyenDeTransport(?string $moyenDeTransport): static
    {
        $this->moyenDeTransport = $moyenDeTransport;

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(?string $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDateDAchat(): ?\DateTimeInterface
    {
        return $this->dateDAchat;
    }

    public function setDateDAchat(?\DateTimeInterface $dateDAchat): static
    {
        $this->dateDAchat = $dateDAchat;

        return $this;
    }

    public function getDepartTime(): ?\DateTimeInterface
    {
        return $this->departTime;
    }

    public function setDepartTime(?\DateTimeInterface $departTime): static
    {
        $this->departTime = $departTime;

        return $this;
    }

    public function getArrivalTime(): ?\DateTimeInterface
    {
        return $this->arrivalTime;
    }

    public function setArrivalTime(?\DateTimeInterface $arrivalTime): static
    {
        $this->arrivalTime = $arrivalTime;

        return $this;
    }

    public function getDestination(): ?string
    {
        return $this->destination;
    }

    public function setDestination(?string $destination): static
    {
        $this->destination = $destination;

        return $this;
    }

    public function getFromDestination(): ?string
    {
        return $this->fromDestination;
    }

    public function setFromDestination(?string $fromDestination): static
    {
        $this->fromDestination = $fromDestination;

        return $this;
    }

    public function isConfirmation(): ?bool
    {
        return $this->confirmation;
    }

    public function setConfirmation(?bool $confirmation): static
    {
        $this->confirmation = $confirmation;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getUtilisateur(): ?Utilisateurs
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateurs $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }
}
