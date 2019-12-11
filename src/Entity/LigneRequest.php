<?php

namespace App\Entity;

use App\Validator\NumberTicketByMonth;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LigneRequestRepository")
  */
class LigneRequest
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;



    /**
     * @ORM\Column(type="integer")
     */
    private $NbTicket;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Request", inversedBy="ligneRequest", cascade={"persist", "remove"})
     */
    private $request;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ticket", inversedBy="ligneRequests")
     */
    private $ticket;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getNbTicket(): ?int
    {
        return $this->NbTicket;
    }

    public function setNbTicket(int $NbTicket): self
    {
        $this->NbTicket = $NbTicket;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getRequest(): ?Request
    {
        return $this->request;
    }

    public function setRequest(?Request $request): self
    {
        $this->request = $request;

        return $this;
    }

    public function getTicket(): ?Ticket
    {
        return $this->ticket;
    }

    public function setTicket(?Ticket $ticket): self
    {
        $this->ticket = $ticket;

        return $this;
    }
}
