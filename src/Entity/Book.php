<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $externalId = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(nullable: true)]
    private ?int $firstPublishYear = null;

    /** @var Collection<Author> */
    #[ORM\ManyToMany(targetEntity: Author::class, inversedBy: 'books', cascade: ['persist'])]
    private Collection $authors;

    /** @var Collection<int, Isbn> */
    #[ORM\OneToMany(targetEntity: Isbn::class, mappedBy: 'book', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $isbns;

    /**
     * Book constructor
     */
    public function __construct()
    {
        $this->authors = new ArrayCollection();
        $this->isbns = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstPublishYear(): ?int
    {
        return $this->firstPublishYear;
    }

    public function setFirstPublishYear(?int $firstPublishYear): static
    {
        $this->firstPublishYear = $firstPublishYear;

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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection<int, Author>
     */
    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    public function replaceAuthors(array $authors): void
    {
        $this->authors->clear();
        foreach ($authors as $author) {
            $this->authors->add($author);
        }
    }

    public function addAuthor(Author $author): static
    {
        if (!$this->authors->contains($author)) {
            $this->authors->add($author);
            $author->addBook($this);
        }

        return $this;
    }

    public function removeAuthor(Author $author): static
    {
        if ($this->authors->removeElement($author)) {
            $author->removeBook($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Isbn>
     */
    public function getIsbns(): Collection
    {
        return $this->isbns;
    }

    public function addIsbn(Isbn $isbn): static
    {
        if (!$this->isbns->contains($isbn)) {
            $this->isbns->add($isbn);
            $isbn->setBook($this);
        }

        return $this;
    }

    public function removeIsbn(Isbn $isbn): static
    {
        if ($this->isbns->removeElement($isbn)) {
            if ($isbn->getBook() === $this) {
                $isbn->setBook(null);
            }
        }

        return $this;
    }

    /**
     * @inheritDoc
     * @return array<string, mixed>
     */
    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'externalId' => $this->externalId,
            'title' => $this->title,
            'firstPublishYear' => $this->firstPublishYear,
            'authors' => $this->getAuthors()->map(function (Author $author) {
                return [
                    'id' => $author->getId(),
                    'name' => $author->getName(),
                ];
            }),
            'isbns' => $this->getIsbns()->map(function (Isbn $isbn) {
                return $isbn->getIsbn();
            })
        ];
    }
}
