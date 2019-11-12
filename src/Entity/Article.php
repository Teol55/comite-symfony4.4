<?php

namespace App\Entity;

use App\Service\UploaderHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 */
class Article
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\NotNull(message="Il Faut mettre un titre doudou")
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     * @Gedmo\Slug(fields={"title"})
     */
    private $slug;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     * @Assert\NotBlank()
     */
    private $publishedAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imageFilename;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank()
     */
    private $publishedEnd;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ArticleReference", mappedBy="article")
     * @ORM\OrderBy({"position"="ASC"})
     */
    private $articleReferences;

    public function __construct()
    {

        $this->articleReferences = new ArrayCollection();
    }

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }



    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(\DateTimeInterface $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getImageFilename(): ?string
    {
        return $this->imageFilename;
    }

    public function setImageFilename(?string $imageFilename): self
    {
        $this->imageFilename = $imageFilename;

        return $this;
    }
    public function isPublished(): bool
    {
        $dateToday=date("d-m-Y");

            $datePubishedAt=date_format($this->getPublishedAt(),"d-m-Y");
        $datePubishedend=date_format($this->getPublishedEnd(),"d-m-Y");

        if(($dateToday >= $datePubishedAt) && ($datePubishedend >= $dateToday))
        {
           return true;
        }
        else return false;


    }

    public function getPublishedEnd(): ?\DateTimeInterface
    {
        return $this->publishedEnd;
    }

    public function setPublishedEnd(\DateTimeInterface $publishedEnd): self
    {
        $this->publishedEnd = $publishedEnd;

        return $this;
    }
    public function getImagePath()
    {
        return UploaderHelper::ARTICLE_IMAGE.'/'.$this->getImageFilename();
    }
    /**
     * @return Collection|ArticleReference[]
     */
    public function getArticleReferences(): Collection
    {
        return $this->articleReferences;
    }
}
