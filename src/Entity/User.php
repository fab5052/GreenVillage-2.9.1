<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Trait\CreatedAtTrait;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'Un compte existe avec cet email')]
class User 
    implements UserInterface, PasswordAuthenticatedUserInterface
    {
    //use CreatedAtTrait;
 //   use EnumType;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    // #[ORM\Column(type: 'string', enumType: UserRole::class)]
    // private ?UserRole $role = null;

    #[ORM\Column(type: 'string', length: 100)]
    private ?string $lastname;
    
    #[ORM\Column(type: 'string', length: 100)]
    private ?string $firstname;
    
    #[ORM\Column(type: 'string', length: 255)]
    private ?string $address;
    
    #[ORM\Column(type: 'string', length: 5)]
    private ?string $zipcode;
    
    #[ORM\Column(type: 'string', length: 150)]
    private $city;

    #[ORM\Column(nullable: true)]
    private ?string $plainPassword = null;

    #[ORM\Column(type: 'boolean')]
    private bool $is_verified = false;
    
    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $resetToken = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $siret = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTime $lastConnect = null;


    /**
    * @var Collection<int, InfoSuppliers>
    */
    #[ORM\OneToMany(targetEntity: InfoSuppliers::class, mappedBy: 'user')]
    private Collection $infoSuppliers;

    /**
    * @var Collection<int, Order>
    */
    #[ORM\OneToMany(targetEntity: Order::class, mappedBy: 'user')]
    private Collection $orders;
     
    public function __construct()
    {
       // $this->address = new ArrayCollection();
        $this->infoSuppliers = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable(); 
        $this->lastConnect = new \DateTime();
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
     *
     * @return list<string>$roles
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
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function eraseCredentials():void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        //$this->plainPassword = null;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }
    
    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;
        return $this;
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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function isVerified(): ?bool
    {
        return $this->is_verified;
    }

    public function setIsVerified(bool $is_verified): self
    {
        $this->is_verified = $is_verified;

        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): self
    {
        $this->resetToken = $resetToken;

        return $this;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(?string $siret): static
    {
        $this->siret = $siret;

        return $this;
    }

    public function getLastConnect(): ?\DateTime
    {
        return $this->lastConnect;
    }

    public function setLastConnect(\DateTime $lastConnect): self
    {
        $this->lastConnect = $lastConnect;

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


/**
* @return Collection<int, infoSuppliers>
*/
public function getInfoSuppliers(): Collection
{
    return $this->infoSuppliers;
}

public function addInfoSupplier(InfoSuppliers $infoSupplier): self
{
    if (!$this->infoSuppliers->contains($infoSupplier)) {
        $this->infoSuppliers[] = $infoSupplier;
        $infoSupplier->setUser($this);
    }

    return $this;
}

public function removeInfoSupplier(InfoSuppliers $infoSupplier): self
{
    if ($this->infoSuppliers->removeElement($infoSupplier)) {
        if ($infoSupplier->getUser() === $this) {
            $infoSupplier->setUser(null);
        }
    }

    return $this;
}

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): static
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setUser($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): static
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getUser() === $this) {
                $order->setUser(null);
            }
        }

        return $this;
    }


}