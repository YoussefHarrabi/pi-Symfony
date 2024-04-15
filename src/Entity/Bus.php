<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use App\Repository\BusRepository;

#[Entity(repositoryClass: BusRepository::class)]
class Bus
{
    #[Id]
    #[GeneratedValue(strategy: "IDENTITY")]
    #[Column(name: "id", type: "integer", nullable: false)]
    private int $id;

    public function getId(): ?int
    {
        return $this->id;
    }
    
}
