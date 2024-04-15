<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommunMeansOfTransportRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: CommunMeansOfTransportRepository::class)]
#[UniqueEntity(fields: ['registrationNumber'], message: 'A same registration number already exists !')]
class CommunMeansOfTransport
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id", type: "integer")]
    private $id;



    #[Assert\NotBlank(message: "Please enter registration number")]
    #[Assert\Regex(
        pattern: "/^\d{3}TU\d{4}$/",
        message: "Registration number should be in the format ***TU****, and * are integers ."
    )]
    #[ORM\Column(name: "Registration_number", type: "string", length: 11, nullable: false)]
    private ?string $registrationNumber = null;



    #[ORM\Column(name: "Type", type: "string", length: 255, nullable: true)]
    private ?string $type = null;

public function getId(): ?int
    {
        return $this->id;
    }

    public function getRegistrationNumber(): ?string
    {
        return $this->registrationNumber;
    }

    public function setRegistrationNumber(string $registrationNumber): static
    {
        $this->registrationNumber = $registrationNumber;

        return $this;
    }
    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }
  
}
