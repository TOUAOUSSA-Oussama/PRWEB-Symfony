<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Book
 *
 * @ORM\Table(name="book")
 * @ORM\Entity(repositoryClass="App\Repository\RepositoryBook")
 */
class Book
{
    /**
     * @var int
     *
     * @ORM\Column(name="book_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="book_book_id_seq", allocationSize=1, initialValue=1)
     */
    private $bookId;

    /**
     * @var string
     *
     * @ORM\Column(name="book_title", type="string", length=256, nullable=false)
     */
    private $bookTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="book_authors", type="string", length=256, nullable=false)
     */
    private $bookAuthors;

    public function getBookId(): ?int
    {
        return $this->bookId;
    }

    public function getBookTitle(): ?string
    {
        return $this->bookTitle;
    }

    public function setBookTitle(string $bookTitle): self
    {
        $this->bookTitle = $bookTitle;

        return $this;
    }

    public function getBookAuthors(): ?string
    {
        return $this->bookAuthors;
    }

    public function setBookAuthors(string $bookAuthors): self
    {
        $this->bookAuthors = $bookAuthors;

        return $this;
    }


}
