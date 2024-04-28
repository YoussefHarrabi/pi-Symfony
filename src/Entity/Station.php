<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: App\Repository\StationRepository::class)]
#[ORM\Table(name: "station")]
class Station
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "station_id", type: "integer", nullable: false)]
    private $stationId;

    #[ORM\Column(name: "station_name", type: "string", length: 255, nullable: false)]
    private $stationName;

    #[ORM\Column(name: "station_order", type: "integer", nullable: false)]
    private $stationOrder;

    public function getStationId(): ?int
    {
        return $this->stationId;
    }

    public function getStationName(): ?string
    {
        return $this->stationName;
    }

    public function setStationName(string $stationName): static
    {
        $this->stationName = $stationName;

        return $this;
    }

    public function getStationOrder(): ?int
    {
        return $this->stationOrder;
    }

    public function setStationOrder(int $stationOrder): static
    {
        $this->stationOrder = $stationOrder;

        return $this;
    }
}
