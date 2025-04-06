<?php

namespace App\Entity;

use App\Repository\AuthorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AuthorRepository::class)]
class Author
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateOfBirth = null;

    #[ORM\Column(length: 255)]
    private ?string $externalId = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $workCount = null;

    #[ORM\Column]
    private ?float $ratingsAverage = null;

    #[ORM\Column]
    private ?int $ratingsCount = null;

    #[ORM\Column]
    private ?int $ratingsCount1 = null;

    #[ORM\Column]
    private ?int $ratingsCount2 = null;

    #[ORM\Column]
    private ?int $ratingsCount3 = null;

    #[ORM\Column]
    private ?int $ratingsCount4 = null;

    #[ORM\Column]
    private ?int $ratingsCount5 = null;

    #[ORM\Column]
    private ?int $wantToReadCount = null;

    #[ORM\Column]
    private ?int $alreadyReadCount = null;

    #[ORM\Column]
    private ?int $currentlyReadingCount = null;

    /**
     * @var Collection<int, Book>
     */
    #[ORM\ManyToMany(targetEntity: Book::class, inversedBy: 'authors')]
    private Collection $books;

    /**
     * @var Collection<int, AlternativeName>
     */
    #[ORM\OneToMany(targetEntity: AlternativeName::class, mappedBy: 'author', orphanRemoval: true)]
    private Collection $alternativeNames;

    public function __construct()
    {
        $this->books = new ArrayCollection();
        $this->alternativeNames = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateOfBirth(): ?\DateTimeInterface
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(?\DateTimeInterface $dateOfBirth): static
    {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    public function getExternalId(): ?string
    {
        return $this->externalId;
    }

    public function setExternalId(string $externalId): static
    {
        $this->externalId = $externalId;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getWorkCount(): ?int
    {
        return $this->workCount;
    }

    public function setWorkCount(int $workCount): static
    {
        $this->workCount = $workCount;

        return $this;
    }

    public function getRatingsAverage(): ?float
    {
        return $this->ratingsAverage;
    }

    public function setRatingsAverage(float $ratingsAverage): static
    {
        $this->ratingsAverage = $ratingsAverage;

        return $this;
    }

    public function getRatingsCount(): ?int
    {
        return $this->ratingsCount;
    }

    public function setRatingsCount(int $ratingsCount): static
    {
        $this->ratingsCount = $ratingsCount;

        return $this;
    }

    public function getRatingsCount1(): ?int
    {
        return $this->ratingsCount1;
    }

    public function setRatingsCount1(int $ratingsCount1): static
    {
        $this->ratingsCount1 = $ratingsCount1;

        return $this;
    }

    public function getRatingsCount2(): ?int
    {
        return $this->ratingsCount2;
    }

    public function setRatingsCount2(int $ratingsCount2): static
    {
        $this->ratingsCount2 = $ratingsCount2;

        return $this;
    }

    public function getRatingsCount3(): ?int
    {
        return $this->ratingsCount3;
    }

    public function setRatingsCount3(int $ratingsCount3): static
    {
        $this->ratingsCount3 = $ratingsCount3;

        return $this;
    }

    public function getRatingsCount4(): ?int
    {
        return $this->ratingsCount4;
    }

    public function setRatingsCount4(int $ratingsCount4): static
    {
        $this->ratingsCount4 = $ratingsCount4;

        return $this;
    }

    public function getRatingsCount5(): ?int
    {
        return $this->ratingsCount5;
    }

    public function setRatingsCount5(int $ratingsCount5): static
    {
        $this->ratingsCount5 = $ratingsCount5;

        return $this;
    }

    public function getWantToReadCount(): ?int
    {
        return $this->wantToReadCount;
    }

    public function setWantToReadCount(int $wantToReadCount): static
    {
        $this->wantToReadCount = $wantToReadCount;

        return $this;
    }

    public function getAlreadyReadCount(): ?int
    {
        return $this->alreadyReadCount;
    }

    public function setAlreadyReadCount(int $alreadyReadCount): static
    {
        $this->alreadyReadCount = $alreadyReadCount;

        return $this;
    }

    public function getCurrentlyReadingCount(): ?int
    {
        return $this->currentlyReadingCount;
    }

    public function setCurrentlyReadingCount(int $currentlyReadingCount): static
    {
        $this->currentlyReadingCount = $currentlyReadingCount;

        return $this;
    }

    /**
     * @return Collection<int, Book>
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function addBook(Book $book): static
    {
        if (!$this->books->contains($book)) {
            $this->books->add($book);
        }

        return $this;
    }

    public function removeBook(Book $book): static
    {
        $this->books->removeElement($book);

        return $this;
    }

    /**
     * @return Collection<int, AlternativeName>
     */
    public function getAlternativeNames(): Collection
    {
        return $this->alternativeNames;
    }

    public function addAlternativeName(AlternativeName $alternativeName): static
    {
        if (!$this->alternativeNames->contains($alternativeName)) {
            $this->alternativeNames->add($alternativeName);
            $alternativeName->setAuthor($this);
        }

        return $this;
    }

    public function removeAlternativeName(AlternativeName $alternativeName): static
    {
        if ($this->alternativeNames->removeElement($alternativeName)) {
            // set the owning side to null (unless already changed)
            if ($alternativeName->getAuthor() === $this) {
                $alternativeName->setAuthor(null);
            }
        }

        return $this;
    }
}
