<?php

namespace App\Entity;

use App\Repository\EquipeRepository;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Integer;

  #[ORM\Table(name: "equipe")]

  #[ORM\Entity(repositoryClass: EquipeRepository::class)]
 
class Equipe
{
    
    #[ORM\Column(name: "id", type: "integer", nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    private $id;

    
    #[ORM\Column(name: "relationTo", type: "string", length: 255, nullable: true)]
    private ?string $relationTo;

    #[ORM\Column(name: "nbrPersonne", type: "integer", length: 255, nullable: true)]
    private?int $nbrPersonne;
   

    #[ORM\ManyToOne(targetEntity: Work::class)]
    #[ORM\JoinColumn(name: "work_id", referencedColumnName: "workID")]
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
   
    public function getWork(): ?Work
    {
        return $this->work;
    }

    public function setWork(?Work $work): self
    {
        $this->work = $work;

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

    

}
