<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GameRepository::class)
 */
class Game
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $logo;

    /**
     * @ORM\OneToMany(targetEntity=Expansion::class, mappedBy="game", orphanRemoval=true)
     */
    private $expansions;

    public function __construct()
    {
        $this->expansions = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * @return Collection|Expansion[]
     */
    public function getExpansions(): Collection
    {
        return $this->expansions;
    }

    public function addExpansion(Expansion $expansion): self
    {
        if (!$this->expansions->contains($expansion)) {
            $this->expansions[] = $expansion;
            $expansion->setGame($this);
        }

        return $this;
    }

    public function removeExpansion(Expansion $expansion): self
    {
        if ($this->expansions->contains($expansion)) {
            $this->expansions->removeElement($expansion);
            // set the owning side to null (unless already changed)
            if ($expansion->getGame() === $this) {
                $expansion->setGame(null);
            }
        }

        return $this;
    }
}
