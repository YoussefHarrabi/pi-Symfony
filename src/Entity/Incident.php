<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass:"App\Repository\IncidentRepository"::class)]
class Incident
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "IncidentId", type: "integer")]
    private ?int $IncidentId;

    #[Assert\NotBlank(message: "Incident type must not be empty")]
    #[ORM\Column(name: "Type", type: "string", length: 255)]
    private ?string $type;

    #[Assert\NotBlank(message: "Incident place must not be empty")]
    #[ORM\Column(name: "Place", type: "string", length: 255)]
    private ?string $place;

    #[Assert\NotNull(message: "Incident hour must not be null")]
    #[Assert\Regex(
        pattern: "/^([0-1]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/",
        message: "Hour must be in the format HH:mm:ss or HH:mm"
    )]
    #[Assert\LessThanOrEqual("now", message: "Hour cannot be in the future")]
    #[ORM\Column(name: "hour", type: "string", length: 8)]
    private ?string $hour;

    #[Assert\NotBlank(message: "Description must not be empty")]
    #[ORM\Column(name: "Description", type: "string", length: 350)]
    private ?string $description;

    #[ORM\Column(name: "userID", type: "integer")]
    private ?int $userid;

    #[ORM\Column(name: "Date", type: "date", options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?\DateTimeInterface $date;

    public function getIncidentid(): ?int
    {
        return $this->IncidentId;
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

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(?string $place): static
    {
        $this->place = $place;

        return $this;
    }

    public function getHour(): ?\DateTimeInterface
    {
        if ($this->hour === null) {
            return null;
        }
    
        // Assuming $this->hour is in the format 'HH:mm:ss'
        return \DateTime::createFromFormat('H:i:s', $this->hour);
    }

    public function setHour(?\DateTimeInterface $hour): static
{
    if ($hour instanceof \DateTimeInterface) {
        $this->hour = $hour->format('H:i:s'); // Convert DateTime object to string
    } elseif (is_string($hour)) {
        // Validate the string format if needed before setting it
        // Assuming the string format is valid
        $this->hour = $hour;
    } else {
        // Handle other cases as needed, such as setting it to null
        $this->hour = null;
    }

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

    public function getUserid(): ?int
    {
        return $this->userid;
    }

    public function setUserid(?int $userid): static
    {
        $this->userid = $userid;

        return $this;
    }

   public function getDate(): ?string
{
    if (is_string($this->date)) {
        return $this->date; // Return the string representation if it's already a string
    }

    return null; // Return null if $this->date is not a string
}

   public function setDate(?\DateTimeInterface $date): static
   {
       $this->date = $date;

       return $this;
   }

    

}
