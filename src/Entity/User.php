<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
#[ApiResource(
    collectionOperations: [
        'get',
        'post',
    ],
    itemOperations: [
        'put',
        'get'
    ],
    denormalizationContext: [
        'groups' => [
            'write:User'
        ]
    ]
)]
class User
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
    #[Groups(['write:User'])]
    private ?string $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(['write:User'])]
    private ?string $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(['write:User'])]
    private ?string $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(['write:User'])]
    private ?string $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    #[Groups(['write:User'])]
    private ?string $nickname;

    /**
     * @ORM\OneToMany(targetEntity=Event::class, mappedBy="creator")
     */
    private ArrayCollection $createdEvents;

    /**
     * @ORM\ManyToMany(targetEntity=Event::class, mappedBy="participants")
     */
    private ArrayCollection $registeredEvents;

    public function __construct()
    {
        $this->createdEvents = new ArrayCollection();
        $this->registeredEvents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
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
    public function getCreatedEvents(): Collection
    {
        return $this->createdEvents;
    }

    public function addCreatedEvent(Event $createdEvent): self
    {
        if (!$this->createdEvents->contains($createdEvent)) {
            $this->createdEvents[] = $createdEvent;
            $createdEvent->setCreator($this);
        }

        return $this;
    }

    public function removeCreatedEvent(Event $createdEvent): self
    {
        if ($this->createdEvents->removeElement($createdEvent)) {
            // set the owning side to null (unless already changed)
            if ($createdEvent->getCreator() === $this) {
                $createdEvent->setCreator(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getRegisteredEvents(): Collection
    {
        return $this->registeredEvents;
    }

    public function addRegisteredEvent(Event $registeredEvent): self
    {
        if (!$this->registeredEvents->contains($registeredEvent)) {
            $this->registeredEvents[] = $registeredEvent;
            $registeredEvent->addParticipant($this);
        }

        return $this;
    }

    public function removeRegisteredEvent(Event $registeredEvent): self
    {
        if ($this->registeredEvents->removeElement($registeredEvent)) {
            $registeredEvent->removeParticipant($this);
        }

        return $this;
    }
}
