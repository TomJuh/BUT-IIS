<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\ManyToMany(targetEntity: Systems::class, inversedBy: 'users')]
    private Collection $Systems;

    #[ORM\OneToMany(mappedBy: 'userOwner', targetEntity: Systems::class, orphanRemoval: true)]
    private Collection $CreatedSystems;

    public function __construct()
    {
        $this->Systems = new ArrayCollection();
        $this->CreatedSystems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
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
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
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

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Systems>
     */
    public function getSystems(): Collection
    {
        return $this->Systems;
    }

    public function addSystem(Systems $system): static
    {
        if (!$this->Systems->contains($system)) {
            $this->Systems->add($system);
        }

        return $this;
    }

    public function removeSystem(Systems $system): static
    {
        $this->Systems->removeElement($system);

        return $this;
    }

    /**
     * @return Collection<int, Systems>
     */
    public function getCreatedSystems(): Collection
    {
        return $this->CreatedSystems;
    }

    public function addCreatedSystem(Systems $createdSystem): static
    {
        if (!$this->CreatedSystems->contains($createdSystem)) {
            $this->CreatedSystems->add($createdSystem);
            $createdSystem->setUserOwner($this);
        }

        return $this;
    }

    public function removeCreatedSystem(Systems $createdSystem): static
    {
        if ($this->CreatedSystems->removeElement($createdSystem)) {
            // set the owning side to null (unless already changed)
            if ($createdSystem->getUserOwner() === $this) {
                $createdSystem->setUserOwner(null);
            }
        }

        return $this;
    }
}
