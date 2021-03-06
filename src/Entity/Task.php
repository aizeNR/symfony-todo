<?php

namespace App\Entity;

use App\Entity\Dictionary\TaskStatusDictionary;
use App\Entity\Traits\CreateUpdateTimeTrait;
use App\Repository\TaskRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TaskRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Task
{
    use CreateUpdateTimeTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"show_task", "list_task"})
     */
    private $id;

    /**
     * @ORM\Column (type="string", length=255)
     *
     * @Assert\NotBlank
     *
     * @Groups ({"show_task", "list_task", "create_task"})
     *
     * @var null|string
     */
    private $title;

    /**
     * @ORM\Column (type="text", nullable=true)
     *
     * @Groups ({"show_task", "create_task"})
     *
     * @var null|string
     */
    private $description;

    /**
     * @ORM\ManyToOne (targetEntity=User::class, inversedBy="tasks")
     *
     * @ORM\JoinColumn (nullable=false)
     *
     * @Groups ({"show_task"})
     *
     * @var User|null
     */
    private $user;

    /**
     * @ORM\Column (type="integer", options={"default" : 0})
     *
     * @Groups ({"show_task", "list_task", "create_task"})
     *
     * @var int|null
     */
    private $status;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="tasks")
     * @ORM\JoinTable(name="task_tag")
     * @Groups({"show_task", "list_task", "create_task"})
     */
    private $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @Groups({"show_task", "list_task"})
     * @return string
     */
    public function getPrettyStatus(): string
    {
        return TaskStatusDictionary::getPrettyStatus($this->getStatus());
    }

    /**
     * @return Collection
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag ...$tags): self
    {
        foreach ($tags as $tag) {
            if (!$this->tags->contains($tag)) {
                $this->tags[] = $tag;
            }
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }
}
