<?php

namespace App\Entity;

use App\Service\UploaderHelper;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\LigneRequest", mappedBy="ticket")
     */
    private $ligneRequests;

    public function __construct()
    {

        $this->ligneRequests = new ArrayCollection();
    }
//    public function __toString() { return strval($this->getId()); }

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

    /**
     * @return Collection|LigneRequest[]
     */
    public function getLigneRequests(): Collection
    {
        return $this->ligneRequests;
    }

    public function addLigneRequest(LigneRequest $ligneRequest): self
    {
        if (!$this->ligneRequests->contains($ligneRequest)) {
            $this->ligneRequests[] = $ligneRequest;
            $ligneRequest->setTicket($this);
        }

        return $this;
    }

    public function removeLigneRequest(LigneRequest $ligneRequest): self
    {
        if ($this->ligneRequests->contains($ligneRequest)) {
            $this->ligneRequests->removeElement($ligneRequest);
            // set the owning side to null (unless already changed)
            if ($ligneRequest->getTicket() === $this) {
                $ligneRequest->setTicket(null);
            }
        }

        return $this;
    }
}
