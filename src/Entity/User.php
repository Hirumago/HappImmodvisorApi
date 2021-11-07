<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\UserAddAvatar;
use App\Controller\UserRemoveAvatar;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=App\Repository\UserRepository::class)
 * @Vich\Uploadable()
 */
#[ApiResource(
    collectionOperations: [
        'get' => [
            'normalization_context' => ['groups' => ['getAll:User']],
        ],
        'post' => [
            'method' => 'POST',
            'path' => '/register',
            'deserialize' => false,
            'controller' => \App\Controller\RegisterController::class,
            'openapi_context' => [
                'summary' => 'Create an user.',
                'requestBody' => [
                    'content' => [
                        'multipart/form-data' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'email' => [
                                        'type' => 'string'
                                    ],
                                    'password' => [
                                        'type' => 'string'
                                    ],
                                    'nickname' => [
                                        'type' => 'string'
                                    ],
                                    'file' => [
                                        'type' => 'string'
                                    ],
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'normalization_context' => ['groups' => ['post:return:User']],
            'denormalization_context' => ['groups' => ['post:User']],
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
        'delete',
        'add_avatar' => [
            'method' => 'POST',
            'path' => '/users/{id}/avatar/add',
            'deserialize' => false,
            'controller' => UserAddAvatar::class,
            'openapi_context' => [
                'summary' => 'Add an avatar to an user.',
                'requestBody' => [
                    'content' => [
                        'multipart/form-data' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'file' => [
                                        'type' => 'string',
                                        'format' => 'binary'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'normalization_context' => ['groups' => ['addATU:return:User']],
            'denormalization_context' => ['groups' => ['addATU:User']]
        ],
        'remove_avatar' => [
            'method' => 'POST',
            'path' => '/users/{id}/avatar/remove',
            'deserialize' => false,
            'controller' => UserRemoveAvatar::class,
            'openapi_context' => [
                'summary' => 'Remove an avatar to an user.',
                'requestBody' => [
                    'content' => []
                ]
            ],
            'normalization_context' => ['groups' => ['addATU:return:User']],
            'denormalization_context' => ['groups' => ['addATU:User']]
        ],
    ]
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups(['getAll:User', 'post:return:User', 'getOne:User', 'put:return:User', 'addATU:return:User'])]
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    #[Groups(['getAll:User', 'post:return:User', 'getOne:User', 'put:return:User', 'addATU:return:User'])]
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    #[Groups(['getAll:User', 'post:return:User', 'getOne:User', 'put:return:User', 'addATU:return:User'])]
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    #[Groups(['getAll:User', 'post:return:User', 'getOne:User', 'put:return:User', 'post:User', 'put:User', 'addATU:return:User'])]
    private $nickname;

    /**
     * @ORM\OneToMany(targetEntity=Event::class, mappedBy="creator")
     */
    #[Groups(['getAll:User', 'getOne:User', 'put:return:User', 'addATU:return:User'])]
    private $eventsCreated;

    /**
     * @ORM\ManyToMany(targetEntity=Event::class, mappedBy="participants")
     */
    #[Groups(['getAll:User', 'getOne:User', 'addATU:return:User'])]
    private $eventsParticipated;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    #[Groups(['getAll:User', 'post:return:User', 'getOne:User', 'put:return:User', 'addATU:return:User'])]
    private $avatar;
    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    #[Groups(['getAll:User', 'getOne:User', 'put:return:User', 'addATU:return:User'])]
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    #[Groups(['getAll:User', 'getOne:User', 'put:return:User', 'addATU:return:User'])]
    private $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
