<?php

namespace App\Entity;

#[\Doctrine\ORM\Mapping\Entity(repositoryClass: App\Repository\TrainRepository::class)]
#[\Doctrine\ORM\Mapping\Table(name: "train")]
class Train
{
    #[\Doctrine\ORM\Mapping\Id]
    #[\Doctrine\ORM\Mapping\GeneratedValue(strategy: "IDENTITY")]
    #[\Doctrine\ORM\Mapping\Column(name: "id", type: "integer", nullable: false)]
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }
}
