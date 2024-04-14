<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: App\Repository\TicketsRepository::class)]
class Tickets
{
    #[ORM\Column(name: "ticket_id", type: "integer", nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private $ticketId;

    #[ORM\Column(name: "moyen_de_transport", type: "string", length: 255, nullable: true)]
    private $moyenDeTransport;

    #[ORM\Column(name: "prix", type: "float", precision: 10, scale: 0, nullable: true)]
    private $prix;

    #[ORM\Column(name: "date_d_achat", type: "date", nullable: true)]
    private $dateDAchat;

    #[ORM\Column(name: "destination", type: "string", length: 255, nullable: true)]
    private $destination;

    #[ORM\Column(name: "confirmation", type: "boolean", nullable: true)]
    private $confirmation;

    #[ORM\Column(name: "from_destination", type: "string", length: 255, nullable: false)]
    private $fromDestination;

    #[ORM\ManyToOne(targetEntity: Utilisateurs::class)]
    #[ORM\JoinColumn(name: "utilisateur_id", referencedColumnName: "id")]
    private $utilisateur;

    #[ORM\ManyToOne(targetEntity: Wallet::class)]
    #[ORM\JoinColumn(name: "wallet_id", referencedColumnName: "walletId")]
    private $wallet;

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

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): static
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

    public function getDestination(): ?string
    {
        return $this->destination;
    }

    public function setDestination(?string $destination): static
    {
        $this->destination = $destination;
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

    public function getFromDestination(): ?string
    {
        return $this->fromDestination;
    }

    public function setFromDestination(string $fromDestination): static
    {
        $this->fromDestination = $fromDestination;
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

    public function getWallet(): ?Wallet
    {
        return $this->wallet;
    }

    public function setWallet(?Wallet $wallet): static
    {
        $this->wallet = $wallet;
        return $this;
    }
}
