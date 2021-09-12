<?php

namespace App\Entity;

use App\Repository\GroupsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups as serializer;

/**
 * @ORM\Entity(repositoryClass=GroupsRepository::class)
 *
 * @ApiResource(
 *     itemOperations={
 *          "get", "delete", "put"
 *     },
 *     collectionOperations={
 *          "get", "post"
 *     },
 *     normalizationContext = {
 *          "groups" = {
 *              "read"
 *        }
 *     },
 *     attributes = {
 *          "pagination_client_items_per_page" = true
 *     },
 *     denormalizationContext= {
 *          "groups" = {
 *              "write"
 *        }
 *     },
 * )
 *
 */
class Groups
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @serializer({"read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @serializer({"write", "read"})
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @serializer({"write", "read"})
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=UserGroup::class, mappedBy="groups")
     */
    private $userGroups;

    /**
     * Groups constructor.
     */
    public function __construct()
    {
        $this->userGroups = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     *
     * @return $this
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|UserGroup[]
     */
    public function getUserGroups(): Collection
    {
        return $this->userGroups;
    }

    /**
     * @param UserGroup $userGroup
     *
     * @return $this
     */
    public function addUserGroup(UserGroup $userGroup): self
    {
        if (!$this->userGroups->contains($userGroup)) {
            $this->userGroups[] = $userGroup;
            $userGroup->setGroups($this);
        }

        return $this;
    }

    /**
     * @param UserGroup $userGroup
     *
     * @return $this
     */
    public function removeUserGroup(UserGroup $userGroup): self
    {
        if ($this->userGroups->removeElement($userGroup)) {
            // set the owning side to null (unless already changed)
            if ($userGroup->getGroups() === $this) {
                $userGroup->setGroups(null);
            }
        }

        return $this;
    }
}
