<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\UserAddAvatar;
use App\Controller\UserNew;
use App\Controller\UserRemoveAvatar;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
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
            'path' => '/users',
            'deserialize' => false,
            'controller' => UserNew::class,
            'openapi_context' => [
                'summary' => 'Add an avatar to an user.',
                'requestBody' => [
                    'content' => [
                        'multipart/form-data' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'nickname' => [
                                        'type' => 'string',
                                    ],
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
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups(['getAll:User', 'post:return:User', 'getOne:User', 'put:return:User', 'addATU:return:User'])]
    private $id;

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
    private $avatar;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="user_avatar", fileNameProperty="avatar")
     */
    #[Groups(['post:User'])]
    private $file;

    /**
     * @var string|null
     */
    #[Groups(['getAll:User', 'post:return:User', 'getOne:User', 'put:return:User', 'addATU:return:User'])]
    private $avatarUrl;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    #[Groups(['getAll:User', 'getOne:User', 'put:return:User', 'addATU:return:User'])]
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    #[Groups(['getAll:User', 'getOne:User', 'put:return:User', 'addATU:return:User'])]
    private $updatedAt;

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

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @return File|null
     */
    public function getFile(): ?File
    {
        return $this->file;
    }

    /**
     * @param File|null $file
     * @return User
     */
    public function setFile(?File $file): User
    {
        $this->file = $file;
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

    /**
     * @return string|null
     */
    public function getAvatarUrl(): ?string
    {
        return $this->avatarUrl;
    }

    /**
     * @param string|null $avatarUrl
     * @return User
     */
    public function setAvatarUrl(?string $avatarUrl): User
    {
        $this->avatarUrl = $avatarUrl;
        return $this;
    }
}
