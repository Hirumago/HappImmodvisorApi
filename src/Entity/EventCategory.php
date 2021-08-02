<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\EventCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=App\Repository\EventCategoryRepository::class)
 */
#[ApiResource(
    collectionOperations: [
        'get' => [
            'normalization_context' => ['groups' => ['getAll:EC']],
        ],
        'post' => [
            'normalization_context' => ['groups' => ['post:return:EC']],
            'denormalization_context' => ['groups' => ['post:EC']]
        ],
    ],
    itemOperations: [
        'get' => [
            'normalization_context' => ['groups' => ['getOne:EC']],
        ],
        'put' => [
            'normalization_context' => ['groups' => ['put:return:EC']],
            'denormalization_context' => ['groups' => ['put:EC']]
        ],
        'delete'
    ]
)]
class EventCategory
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups(['getAll:EC', 'post:return:EC', 'getOne:EC', 'put:return:EC'])]
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(['getAll:EC', 'post:return:EC', 'getOne:EC', 'put:return:EC', 'post:EC', 'put:EC'])]
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=Event::class, mappedBy="categories")
     */
    #[Groups(['getAll:EC', 'getOne:EC', 'put:return:EC'])]
    private $events;

    public function __construct()
    {
        $this->events = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->addCategory($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            $event->removeCategory($this);
        }

        return $this;
    }
}
