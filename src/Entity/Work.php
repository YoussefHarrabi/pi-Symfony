<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: "work")]
#[ORM\Entity(repositoryClass: App\Repository\WorkRepository::class)]
class Work
{
    #[ORM\Column(name: "workID", type: "integer", nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private $workid;

    #[ORM\Column(name: "location", type: "string", length: 255, nullable: true)]
    private ?string $location;

    #[ORM\Column(name: "startdate", type: "date", nullable: true)]
    private ?\DateTimeInterface $startdate;

    #[ORM\Column(name: "enddate", type: "date", nullable: true)]
    private ?\DateTimeInterface $enddate;

    #[ORM\Column(name: "description", type: "text", length: 65535, nullable: true)]
    private ?string $description;

    #[ORM\Column(name: "isActive", type: "boolean", nullable: true)]
    private ?bool $isactive;

    public function getWorkid(): ?int
    {
        return $this->workid;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): static
    {
        $this->location = $location;
        return $this;
    }

    public function getStartdate(): ?\DateTimeInterface
    {
        return $this->startdate;
    }

    public function setStartdate(?\DateTimeInterface $startdate): static
    {
        $this->startdate = $startdate;
        return $this;
    }

    public function getEnddate(): ?\DateTimeInterface
    {
        return $this->enddate;
    }

    public function setEnddate(?\DateTimeInterface $enddate): static
    {
        $this->enddate = $enddate;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function isIsactive(): ?bool
    {
        return $this->isactive;
    }

    public function setIsactive(?bool $isactive): static
    {
        $this->isactive = $isactive;
        return $this;
    }
}
