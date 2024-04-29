<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommunMeansOfTransportRepository;

#[ORM\Entity(repositoryClass: CommunMeansOfTransportRepository::class)]
class CommunMeansOfTransport
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id", type: "integer")]
    private $id;
    

    #[ORM\ManyToOne(targetEntity: Train::class)]
    #[ORM\JoinColumn(name: "train_id", referencedColumnName: "id")]
    private ?Train $train = null;

    #[ORM\ManyToOne(targetEntity: Car::class)]
    #[ORM\JoinColumn(name: "car_id", referencedColumnName: "id")]
    private ?Car $car = null;

    #[ORM\ManyToOne(targetEntity: Bus::class)]
    #[ORM\JoinColumn(name: "bus_id", referencedColumnName: "id")]
    private ?Bus $bus = null;

    public function getRegistrationNumber(): ?int
    {
        return $this->registrationNumber;
    }

    public function getTrain(): ?Train
    {
        return $this->train;
    }

    public function setTrain(?Train $train): static
    {
        $this->train = $train;

        return $this;
    }

    public function getCar(): ?Car
    {
        return $this->car;
    }

    public function setCar(?Car $car): static
    {
        $this->car = $car;

        return $this;
    }

    public function getBus(): ?Bus
    {
        return $this->bus;
    }

    public function setBus(?Bus $bus): static
    {
        $this->bus = $bus;

        return $this;
    }
}
