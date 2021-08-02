<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=App\Repository\UserRepository::class)
 */
#[ApiResource(
    collectionOperations: [
        'get' => [
            'normalization_context' => ['groups' => ['getAll:User']],
        ],
        'post' => [
            'normalization_context' => ['groups' => ['post:return:User']],
            'denormalization_context' => ['groups' => ['post:User']]
        ],
    ],
    itemOperations: [
        'get' => [
            'normalization_context' => ['groups' => ['getOne:User']],
        ],
        'put' => [
            'normalization_context' => ['groups' => ['put:return:User']],
            'denormalization_context' => ['groups' => ['put:User']]
        ],
        'delete'
    ]
)]
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups(['getAll:User', 'post:return:User', 'getOne:User', 'put:return:User'])]
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    #[Groups(['getAll:User', 'post:return:User', 'getOne:User', 'put:return:User', 'post:User', 'put:User'])]
    private $nickname;

    /**
     * @ORM\OneToMany(targetEntity=Event::class, mappedBy="creator")
     */
    #[Groups(['getAll:User', 'getOne:User', 'put:return:User'])]
    private $eventsCreated;

    /**
     * @ORM\ManyToMany(targetEntity=Event::class, mappedBy="participants")
     */
    private $eventsParticipated;

    public function __construct()
    {
        $this->eventsCreated = new ArrayCollection();
        $this->eventsParticipated = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(?string $nickname): self
    {
        $this->nickname = $nickname;

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getEventsCreated(): Collection
    {
        return $this->eventsCreated;
    }

    public function addEventsCreated(Event $eventsCreated): self
    {
        if (!$this->eventsCreated->contains($eventsCreated)) {
            $this->eventsCreated[] = $eventsCreated;
            $eventsCreated->setCreator($this);
        }

        return $this;
    }

    public function removeEventsCreated(Event $eventsCreated): self
    {
        if ($this->eventsCreated->removeElement($eventsCreated)) {
            // set the owning side to null (unless already changed)
            if ($eventsCreated->getCreator() === $this) {
                $eventsCreated->setCreator(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getEventsParticipated(): Collection
    {
        return $this->eventsParticipated;
    }

    public function addEventsParticipated(Event $eventsParticipated): self
    {
        if (!$this->eventsParticipated->contains($eventsParticipated)) {
            $this->eventsParticipated[] = $eventsParticipated;
            $eventsParticipated->addParticipant($this);
        }

        return $this;
    }

    public function removeEventsParticipated(Event $eventsParticipated): self
    {
        if ($this->eventsParticipated->removeElement($eventsParticipated)) {
            $eventsParticipated->removeParticipant($this);
        }

        return $this;
    }
}
