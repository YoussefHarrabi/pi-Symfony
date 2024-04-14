<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EquipeRepository")
 */
class Equipe
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $relationTo;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbrPersonne;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Work")
     * @ORM\JoinColumn(name="work_id", referencedColumnName="workID", nullable=false)
     */
    private $work;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRelationTo(): ?string
    {
        return $this->relationTo;
    }

    public function setRelationTo(string $relationTo): self
    {
        $this->relationTo = $relationTo;

        return $this;
    }

    public function getNbrPersonne(): ?int
    {
        return $this->nbrPersonne;
    }

    public function setNbrPersonne(int $nbrPersonne): self
    {
        $this->nbrPersonne = $nbrPersonne;

        return $this;
    }

    public function getWork(): ?Work
    {
        return $this->work;
    }

    public function setWork(?Work $work): self
    {
        $this->work = $work;

        return $this;
    }
}
