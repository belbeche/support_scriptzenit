<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="Il existe déjà un compte avec cet email")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @Assert\Length(
     *      min = 2,
     *      max = 10,
     *      minMessage = "Votre nom d'utilisateur doit comporter au moins {{ limit }} caractères.",
     *      maxMessage = "Votre nom d'utilisateur ne peut pas comporter plus de {{ limit }} caractères."
     * )
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     *
     * @Assert\Email(
     *     message = "l'email '{{ value }}' n'est pas un courriel valide."
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\OneToMany (targetEntity=Article::class, mappedBy="user")
     */
    private Collection $articles;

    /**
     * @ORM\OneToMany (targetEntity=Comment::class, mappedBy="user")
     */
    private $comments;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $avatar;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date;

    /**
     * @ORM\OneToMany(
     *     targetEntity="UserLike",
     *     mappedBy="user",
     *     orphanRemoval=true,
     *     cascade={"persist"}
     * )
     */
    private Collection $likes;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->likes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        /*$roles[] = 'ROLE_USER';*/

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    /**
     * @param ArrayCollection $articles
     * @return User
     */
    public function setArticles(ArrayCollection $articles): User
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

    /**
     * @return mixed
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param mixed $comments
     * @return User
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
        return $this;
    }

    public function addComment(Comment $comment)
    {
        $this->comments[] = $comment;
    }

    public function removeComment(Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * @param Collection $likes
     * @return User
     */
    public function setLikes($likes)
    {
        $this->likes = $likes;
        return $this;
    }

    /**
     * @param UserLike $like
     * @return void
     */
    public function addLike(UserLike $like)
    {
        $this->likes[] = $like;
    }

    /**
     * @param UserLike $like
     * @return void
     */
    public function removeLike(UserLike $like)
    {
        $this->likes->removeElement($like);
    }

    public function isIsVerified(): ?bool
    {
        return $this->isVerified;
    }
}
