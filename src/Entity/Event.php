<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 */
#[ApiResource()]
class Event
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $starting_date;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $ending_date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $location;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $maps_link;

    /**
     * @ORM\ManyToMany(targetEntity=CategoryEvent::class, inversedBy="events")
     */
    private $categories_event;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="createdEvents")
     * @ORM\JoinColumn(nullable=false)
     */
    private $creator;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="registeredEvents")
     */
    private $participants;

    public function __construct()
    {
        $this->categories_event = new ArrayCollection();
        $this->participants = new ArrayCollection();
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

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStartingDate(): ?\DateTimeInterface
    {
        return $this->starting_date;
    }

    public function setStartingDate(\DateTimeInterface $starting_date): self
    {
        $this->starting_date = $starting_date;

        return $this;
    }

    public function getEndingDate(): ?\DateTimeInterface
    {
        return $this->ending_date;
    }

    public function setEndingDate(\DateTimeInterface $ending_date): self
    {
        $this->ending_date = $ending_date;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getMapsLink(): ?string
    {
        return $this->maps_link;
    }

    public function setMapsLink(?string $maps_link): self
    {
        $this->maps_link = $maps_link;

        return $this;
    }

    /**
     * @return Collection|CategoryEvent[]
     */
    public function getCategoriesEvent(): Collection
    {
        return $this->categories_event;
    }

    public function addCategoriesEvent(CategoryEvent $categoriesEvent): self
    {
        if (!$this->categories_event->contains($categoriesEvent)) {
            $this->categories_event[] = $categoriesEvent;
        }

        return $this;
    }

    public function removeCategoriesEvent(CategoryEvent $categoriesEvent): self
    {
        $this->categories_event->removeElement($categoriesEvent);

        return $this;
    }

    public function getCreator(): ?User
    {
        return $this->creator;
    }

    public function setCreator(?User $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(User $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
        }

        return $this;
    }

    public function removeParticipant(User $participant): self
    {
        $this->participants->removeElement($participant);

        return $this;
    }
}
