<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RequestRepository")
 */
class Request
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\LigneRequest", mappedBy="request")
     */
    private $ligneRequest;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="requests")
     */
    private $user;


    public function __construct()
    {
        $this->ligneRequest = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

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

    /**
     * @return Collection|LigneRequest[]
     */
    public function getLigneRequest(): Collection
    {
        return $this->ligneRequest;
    }

    public function addLigneRequest(LigneRequest $ligneRequest): self
    {
        if (!$this->ligneRequest->contains($ligneRequest)) {
            $this->ligneRequest[] = $ligneRequest;
            $ligneRequest->setRequest($this);
        }

        return $this;
    }

    public function removeLigneRequest(LigneRequest $ligneRequest): self
    {
        if ($this->ligneRequest->contains($ligneRequest)) {
            $this->ligneRequest->removeElement($ligneRequest);
            // set the owning side to null (unless already changed)
            if ($ligneRequest->getRequest() === $this) {
                $ligneRequest->setRequest(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

}
