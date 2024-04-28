<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: App\Repository\WalletRepository::class)]
class Wallet
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "wallet_id", type: "integer", nullable: false)]
    private ?int $walletId;

    #[ORM\Column(name: "points", type: "integer", nullable: true)]
    private ?int $points = 0;

    #[ORM\ManyToOne(targetEntity: Utilisateurs::class)]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "id")]
    private ?Utilisateurs $user;

    public function getWalletId(): ?int
    {
        return $this->walletId;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(?int $points): static
    {
        $this->points = $points;
        return $this;
    }

    public function getUser(): ?Utilisateurs
    {
        return $this->user;
    }

    public function setUser(?Utilisateurs $user): static
    {
        $this->user = $user;
        return $this;
    }
}
