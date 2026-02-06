<?php

namespace App\Entity;

use App\Repository\DirectorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: DirectorRepository::class)]
class Director
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('movie-read')]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups('movie-read')]
    private ?string $name_director = null;

    #[ORM\Column(length: 50)]
    #[Groups('movie-read')]
    private ?string $firstname_director = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    #[Groups('movie-read')]
    private ?\DateTimeImmutable $day_of_birth = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups('movie-read')]
    private ?string $country_director = null;

    /**
     * @var Collection<int, Movie>
     */
    #[ORM\ManyToMany(targetEntity: Movie::class, mappedBy: 'director')]
    private Collection $movies;

    public function __construct()
    {
        $this->movies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameDirector(): ?string
    {
        return $this->name_director;
    }

    public function setNameDirector(string $name_director): static
    {
        $this->name_director = $name_director;

        return $this;
    }

    public function getFirstnameDirector(): ?string
    {
        return $this->firstname_director;
    }

    public function setFirstnameDirector(string $firstname_director): static
    {
        $this->firstname_director = $firstname_director;

        return $this;
    }

    public function getDayOfBirth(): ?\DateTimeImmutable
    {
        return $this->day_of_birth;
    }

    public function setDayOfBirth(?\DateTimeImmutable $day_of_birth): static
    {
        $this->day_of_birth = $day_of_birth;

        return $this;
    }

    public function getCountryDirector(): ?string
    {
        return $this->country_director;
    }

    public function setCountryDirector(?string $country_director): static
    {
        $this->country_director = $country_director;

        return $this;
    }

    /**
     * @return Collection<int, Movie>
     */
    public function getMovies(): Collection
    {
        return $this->movies;
    }

    public function addMovie(Movie $movie): static
    {
        if (!$this->movies->contains($movie)) {
            $this->movies->add($movie);
            $movie->addDirector($this);
        }

        return $this;
    }

    public function removeMovie(Movie $movie): static
    {
        if ($this->movies->removeElement($movie)) {
            $movie->removeDirector($this);
        }

        return $this;
    }
}
