<?php

namespace App\Entity;

use App\Service\UploaderHelper;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TicketRepository")
 */
class Ticket
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
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="float")
     */
    private $priceCE;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\LigneRequest", mappedBy="ticket", cascade={"persist", "remove"})
     */
    private $ligneRequest;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getPriceCE(): ?float
    {
        return $this->priceCE;
    }

    public function setPriceCE(float $priceCE): self
    {
        $this->priceCE = $priceCE;

        return $this;
    }

    public function getLigneRequest(): ?LigneRequest
    {
        return $this->ligneRequest;
    }

    public function setLigneRequest(?LigneRequest $ligneRequest): self
    {
        $this->ligneRequest = $ligneRequest;

        // set (or unset) the owning side of the relation if necessary
        $newTicket = $ligneRequest === null ? null : $this;
        if ($newTicket !== $ligneRequest->getTicket()) {
            $ligneRequest->setTicket($newTicket);
        }

        return $this;
    }
    public function getImagePath()
    {
        return UploaderHelper::TICKET_IMAGE.'/'.$this->getImage();
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }
}
