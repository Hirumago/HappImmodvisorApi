<?php

namespace App\Entity;

use App\Controller\AddParticipantsToEvent;
use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 */
#[ApiResource(
    collectionOperations: [
        'get',
        'post'
    ],
    itemOperations: [
        'put',
        'delete',
        'participants' => [
            'method' => 'POST',
            'path' => '/events/{id}/participants',
            'controller' => AddParticipantsToEvent::class
        ]
    ],
    denormalizationContext: [
        'groups' => [
            'write:Event'
        ]
    ]
)]
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
    #[Groups(['write:Event'])]
    private ?string $title;

    /**
     * @ORM\Column(type="text")
     */
    #[Groups(['write:Event'])]
    private ?string $description;

    /**
     * @ORM\Column(type="datetime")
     */
    #[Groups(['write:Event'])]
    private ?\DateTimeInterface $startingDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    #[Groups(['write:Event'])]
    private ?\DateTimeInterface $endingDate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    #[Groups(['write:Event'])]
    private ?string $location;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    #[Groups(['write:Event'])]
    private ?string $mapsLink;

    /**
     * @ORM\ManyToMany(targetEntity=CategoryEvent::class, inversedBy="events")
     */
    #[Groups(['write:Event'])]
    private ArrayCollection $categoriesEvent;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="createdEvents")
     * @ORM\JoinColumn(nullable=false)
     */
    #[Groups(['write:Event'])]
    private ?User $creator;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="registeredEvents")
     */
    private $participants;

    public function __construct()
    {
        $this->categoriesEvent = new ArrayCollection();
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
        return $this->startingDate;
    }

    public function setStartingDate(\DateTimeInterface $startingDate): self
    {
        $this->startingDate = $startingDate;

        return $this;
    }

    public function getEndingDate(): ?\DateTimeInterface
    {
        return $this->endingDate;
    }

    public function setEndingDate(\DateTimeInterface $endingDate): self
    {
        $this->endingDate = $endingDate;

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
        return $this->mapsLink;
    }

    public function setMapsLink(?string $mapsLink): self
    {
        $this->mapsLink = $mapsLink;

        return $this;
    }

    /**
     * @return Collection|CategoryEvent[]
     */
    public function getCategoriesEvent(): Collection
    {
        return $this->categoriesEvent;
    }

    public function addCategoriesEvent(CategoryEvent $categoriesEvent): self
    {
        if (!$this->categoriesEvent->contains($categoriesEvent)) {
            $this->categoriesEvent[] = $categoriesEvent;
        }

        return $this;
    }

    public function removeCategoriesEvent(CategoryEvent $categoriesEvent): self
    {
        $this->categoriesEvent->removeElement($categoriesEvent);

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
