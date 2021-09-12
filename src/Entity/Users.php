<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\BadgeInterface;
use Symfony\Component\Serializer\Annotation\Groups as serializer;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ApiResource(
 *     itemOperations={
 *          "get", "put", "delete"
 *     },
 *     collectionOperations={
 *          "get", "post"
 *     },
 *     normalizationContext = {
*            "groups" = {
 *              "read"
 *        }
 *     },
 *     attributes = {
*           "pagination_client_items_per_page" = true
 *     },
 *     denormalizationContext= {
 *          "groups" = {
 *              "write"
 *        }
 *     },
 * )
 *
 * @ORM\Entity(repositoryClass=UsersRepository::class)
 *
 * @UniqueEntity("email")
 *
 * @method string getUserIdentifier()
 */
class Users implements UserInterface
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
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @serializer({"write", "read"})
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @serializer({"write", "read"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @serializer({"write", "read"})
     */
    private $phone;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @serializer({"write", "read"})
     */
    private $age;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @serializer({"write", "read"})
     */
    private $type;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string")
     *
     * @serializer({"write"})

     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=UserGroup::class, mappedBy="user")
     *
     */
    private $userGroups;

    public function __construct()
    {
        $this->userGroups = new ArrayCollection();
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    /**
     * @param string $password
     *
     * @return $this
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
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
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     *
     * @return $this
     */
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     *
     * @return $this
     */
    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string|null $phone
     *
     * @return $this
     */
    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getAge(): ?int
    {
        return $this->age;
    }

    /**
     * @param int|null $age
     *
     * @return $this
     */
    public function setAge(?int $age): self
    {
        $this->age = $age;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @param array $roles
     *
     * @return $this
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Returns the roles granted to the user.
     *
     * @return string[] The user roles
     */
    public function getRoles()
    {
        return $this->roles;
    }


    /**
     * Returns the salt that was originally used to hash the password.
     *
     * This can return null if the password was not hashed using a salt.
     *
     * This method is deprecated since Symfony 5.3, implement it from {@link LegacyPasswordAuthenticatedUserInterface} instead.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string)$this->email;
    }

    /**
     * @return Collection|UserGroup[]
     */
    public function getUserGroups(): Collection
    {
        return $this->userGroups;
    }

    public function addUserGroup(UserGroup $userGroup): self
    {
        if (!$this->userGroups->contains($userGroup)) {
            $this->userGroups[] = $userGroup;
            $userGroup->setUser($this);
        }

        return $this;
    }

    public function removeUserGroup(UserGroup $userGroup): self
    {
        if ($this->userGroups->removeElement($userGroup)) {
            // set the owning side to null (unless already changed)
            if ($userGroup->getUser() === $this) {
                $userGroup->setUser(null);
            }
        }

        return $this;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
}
