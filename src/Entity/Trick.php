<?php

namespace App\Entity;

use App\Repository\TrickRepository;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TrickRepository::class)
 */
class Trick
{

    const TRICK_GROUP = [
        1 => 'grabs',
        2 => 'rotations',
        3 => 'flips',
        4 => 'off-center rotations',
        5 => 'slides',
        6 => 'one foot',
        7 => 'old school'
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $trick_name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $trick_group;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    public function __construct()
    {
        $this->created_at = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTrickName(): ?string
    {
        return $this->trick_name;
    }

    public function setTrickName(string $trick_name): self
    {
        $this->trick_name = $trick_name;

        return $this;
    }

    public function getSlug()
    {
        return (new Slugify())->slugify($this->trick_name);
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getTrickGroup(): ?string
    {
        return isset(self::TRICK_GROUP[$this->trick_group]) ? self::TRICK_GROUP[$this->trick_group] : self::TRICK_GROUP[1];
    }

    public function setTrickGroup(int $trick_group): self
    {
        $this->trick_group = $trick_group;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}
