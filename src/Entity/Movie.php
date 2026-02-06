<?php

namespace App\Entity;

use App\Repository\MovieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: MovieRepository::class)]
class Movie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['movie-read', 'movie-write'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['movie-read', 'movie-write'])]
    private ?string $title_movie = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['movie-read', 'movie-write'])]
    private ?string $synopsis_movie = null;

    #[ORM\Column(length: 255)]
    #[Groups(['movie-read', 'movie-write'])]
    private ?string $image_cover = null;

    #[ORM\Column]
    #[Groups(['movie-read', 'movie-write'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'movies')]
    #[Groups('movie-read')]
    private ?User $user = null;

    /**
     * @var Collection<int, Director>
     */
    #[ORM\ManyToMany(targetEntity: Director::class, inversedBy: 'movies')]
    #[Groups('movie-read')]
    private Collection $director;

    /**
     * @var Collection<int, Category>
     */
    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'movies')]
    #[Groups('movie-read')]
    private Collection $category;

    public function __construct()
    {
        $this->director = new ArrayCollection();
        $this->category = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitleMovie(): ?string
    {
        return $this->title_movie;
    }

    public function setTitleMovie(string $title_movie): static
    {
        $this->title_movie = $title_movie;

        return $this;
    }

    public function getSynopsisMovie(): ?string
    {
        return $this->synopsis_movie;
    }

    public function setSynopsisMovie(string $synopsis_movie): static
    {
        $this->synopsis_movie = $synopsis_movie;

        return $this;
    }

    public function getImageCover(): ?string
    {
        return $this->image_cover;
    }

    public function setImageCover(string $image_cover): static
    {
        $this->image_cover = $image_cover;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Director>
     */
    public function getDirector(): Collection
    {
        return $this->director;
    }

    public function addDirector(Director $director): static
    {
        if (!$this->director->contains($director)) {
            $this->director->add($director);
        }

        return $this;
    }

    public function removeDirector(Director $director): static
    {
        $this->director->removeElement($director);

        return $this;
    }

    public function clearDirector(): static
    {
        $this->director = new ArrayCollection();

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->category->contains($category)) {
            $this->category->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        $this->category->removeElement($category);

        return $this;
    }

    public function clearCategory(): static
    {
        $this->category = new ArrayCollection();

        return $this;
    }
}
