<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: App\Repository\StationRepository::class)]
#[ORM\Table(name: "station")]

class Station
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    private $id;



    #[Assert\NotBlank(message: "Please enter name")]
    #[ORM\Column(name: "Name", type: "string", length: 255, nullable: false)]
    private $name;



    #[Assert\NotBlank(message: "Please enter address")]
    #[ORM\Column(name: "Address", type: "string", nullable: true)]
    private $address;

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }



}