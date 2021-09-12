<?php declare(strict_types=1);

namespace App\Movie\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

/**
 * @ORM\Entity(repositoryClass="App\Movie\Repository\MovieRepository")
 * @ORM\Table(name="movie", indexes={@Index(columns={"title"})})
 */
final class Movie
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private ?int $id;

    /**
     * @ORM\Column()
     */
    private string $title;

    /**
     * @ORM\Column()
     */
    private string $link;

    /**
     * @ORM\Column(type="text")
     */
    private string $description;

    /**
     * @ORM\Column(type="datetime", name="pub_date")
     */
    private \DateTime $pubDate;

    /**
     * @ORM\Column(nullable=true)
     */
    private string $image;

    /**
     */
    public function __construct(string $title, string $link, string $description, \DateTime $pubDate, string $image)
    {
        $this->title = $title;
        $this->link = $link;
        $this->description = $description;
        $this->pubDate = $pubDate;
        $this->image = $image;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getLink(): string
    {
        return $this->link;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPubDate(): \DateTime
    {
        return $this->pubDate;
    }

    public function getImage(): string
    {
        return $this->image;
    }
}
