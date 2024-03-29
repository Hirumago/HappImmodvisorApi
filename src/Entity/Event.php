<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\EventAddParticipant;
use App\Controller\EventRemoveParticipant;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=App\Repository\EventRepository::class)
 */
#[ApiResource(
    collectionOperations: [
        'get' => [
            'normalization_context' => ['groups' => ['getAll:Event']],
        ],
        'post' => [
            'normalization_context' => ['groups' => ['post:return:Event']],
            'denormalization_context' => ['groups' => ['post:Event']]
        ],
    ],

    itemOperations: [
        'get' => [
            'normalization_context' => ['groups' => ['getOne:Event']],
        ],
        'put' => [
            'normalization_context' => ['groups' => ['put:return:Event']],
            'denormalization_context' => ['groups' => ['put:Event']]
        ],
        'delete',
        'add_participant' => [
            'method' => 'POST',
            'path' => '/events/{id}/participant/{userId}/add',
            'controller' => EventAddParticipant::class,
            'openapi_context' => [
                'summary' => 'Add a participant to an event.'
            ],
            'normalization_context' => ['groups' => ['addPTE:return:Event']],
            'denormalization_context' => ['groups' => ['addPTE:Event']]
        ],
        'remove_participant' => [
            'method' => 'POST',
            'path' => '/events/{id}/participant/{userId}/remove',
            'controller' => EventRemoveParticipant::class,
            'openapi_context' => [
                'summary' => 'Remove a participant to an event.',
                'requestBody' => [
                    'content' => []
                ]
            ],
            'normalization_context' => ['groups' => ['removePTE:return:Event']],
            'denormalization_context' => ['groups' => ['removePTE:Event']]
        ]
    ],
    attributes: ["security" => "is_granted('ROLE_USER')"]
)]
class Event
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups([
        'getAll:Event',
        'post:return:Event',
        'getOne:Event',
        'put:return:Event',
        'putP:return:Event',
        'addPTE:return:Event',
        'removePTE:return:Event'
    ])]
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups([
        'getAll:Event',
        'post:return:Event',
        'getOne:Event',
        'put:return:Event',
        'post:Event',
        'put:Event',
        'putP:return:Event'
    ])]
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="eventsCreated")
     * @ORM\JoinColumn(nullable=false)
     */
    #[Groups([
        'getAll:Event',
        'post:return:Event',
        'getOne:Event',
        'put:return:Event',
        'post:Event',
        'put:Event',
        'putP:return:Event',
        'addPTE:return:Event',
        'removePTE:return:Event'
    ])]
    private $creator;

    /**
     * @ORM\ManyToMany(targetEntity=EventCategory::class, inversedBy="events")
     */
    #[Groups([
        'getAll:Event',
        'post:return:Event',
        'getOne:Event',
        'put:return:Event',
        'post:Event',
        'put:Event',
        'putP:return:Event',
        'addPTE:return:Event',
        'removePTE:return:Event'
    ])]
    private $categories;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="eventsParticipated")
     */
    #[Groups([
        'getAll:Event',
        'post:Event',
        'post:return:Event',
        'getOne:Event',
        'put:return:Event',
        'put:Event',
        'putP:Event',
        'putP:return:Event',
        'addPTE:return:Event',
        'removePTE:return:Event'
    ])]
    private $participants;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->participants = new ArrayCollection();
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
     * @return Collection|EventCategory[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(EventCategory $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(EventCategory $category): self
    {
        $this->categories->removeElement($category);

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
