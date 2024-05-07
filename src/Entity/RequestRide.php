<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: App\Repository\RequestRideRepository::class)]



class RequestRide
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    private $id;

    #[ORM\Column(name: "number_seats", type: "integer", nullable: true)]
    #[Assert\PositiveOrZero(message: "number of seats wanted must be a positive number or zero")]
    #[Assert\NotNull(message: "number of seats' field cannot be empty")]

    private $numberSeats;

    #[ORM\Column(name: "departureTime", type: "datetime", nullable: true)]
    #[Assert\GreaterThan("now", message: "Departure time must be valid")]
    #[Assert\NotNull(message: "departure time's field cannot be empty")]

    private $departuretime;

    #[ORM\Column(name: "startLocation", type: "string", nullable: true)]
    #[Assert\NotNull(message: "Start location's field cannot be empty")]
    private $startlocation;
    #[ORM\Column(name: "mail", type: "string", nullable: true)]
    #[Assert\NotNull(message: "E-mail's field cannot be empty")]
    private $mail;

    #[ORM\Column(name: "endLocation", type: "string", nullable: true)]
    #[Assert\NotNull(message: "End location's field cannot be empty")]
    #[Assert\NotEqualTo(propertyPath: "startlocation", message: "End location must be different from start location")]
    private $endlocation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumberSeats(): ?int
    {
        return $this->numberSeats;
    }

    public function setNumberSeats(?int $numberSeats): static
    {
        $this->numberSeats = $numberSeats;

        return $this;
    }

    public function getDeparturetime(): ?\DateTimeInterface
    {
        return $this->departuretime;
    }

    public function setDeparturetime(?\DateTimeInterface $departuretime): static
    {
        $this->departuretime = $departuretime;

        return $this;
    }

    public function getStartlocation(): ?string
    {
        return $this->startlocation;
    }

    public function setStartlocation(?string $startlocation): static
    {
        $this->startlocation = $startlocation;

        return $this;
    }

    public function getEndlocation(): ?string
    {
        return $this->endlocation;
    }

    public function setEndlocation(?string $endlocation): static
    {
        $this->endlocation = $endlocation;

        return $this;
    }
    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(?string $mail): static
    {
        $this->mail = $mail;

        return $this;
    }
}
