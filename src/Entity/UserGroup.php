<?php

namespace App\Entity;

use App\Repository\UserGroupRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups as serializer;

/**
 *
 * @ApiResource(
 *     itemOperations={"get"},
 *     denormalizationContext= {
 *       "groups" = {
 *          "write"
 *        }
 *     },
 *
 *     collectionOperations={"post"}
 * )
 *
 * @ORM\Entity(repositoryClass=UserGroupRepository::class)
 *
 * @UniqueEntity(
 *     fields={"user", "groups"}
 * )
 *
 * @ORM\HasLifecycleCallbacks()
 */
class UserGroup
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="userGroups")
     *
     * @serializer({"write"})
     *
     * @Assert\NotBlank()
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Groups::class, inversedBy="userGroups")
     *
     * @serializer({"write"})
     *
     * @Assert\NotBlank()
     */
    private $groups;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        if (!$this->getCreatedAt()) {
            $this->createdAt = new \DateTimeImmutable();
        }
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Users|null
     */
    public function getUser(): ?Users
    {
        return $this->user;
    }

    /**
     * @param Users|null $user
     *
     * @return $this
     */
    public function setUser(?Users $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Groups|null
     */
    public function getGroups(): ?Groups
    {
        return $this->groups;
    }

    /**
     * @param Groups|null $groups
     *
     * @return $this
     */
    public function setGroups(?Groups $groups): self
    {
        $this->groups = $groups;

        return $this;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }
}
