<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private ?string $name;

    /**
     * @ORM\ManyToMany(targetEntity=Article::class, mappedBy="categories")
     */
    private ArrayCollection $articles;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getArticles(): ArrayCollection
    {
        return $this->articles;
    }

    public function setArticles(ArrayCollection $articles): Category
    {
        $this->articles = $articles;
        return $this;
    }

    public function addArticle(Article $article)
    {
        $this->articles[] = $article;
    }

    public function removeArticle(Article $article)
    {
        $this->articles->removeElement($article);
    }
}
