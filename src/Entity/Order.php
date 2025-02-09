<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use App\Entity\OrderDetails;
// use App\Enum\OrderStatus;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $reference = null;

    #[ORM\Column(length: 100)]
    private ?string $status = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $paymentDate = null;

    #[ORM\Column(length: 100)]
    private ?string $paymentMethod = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $total = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $document = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column(length: 50)]
    private ?string $paymentStatus = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * @var Collection<int, Delivery>
     */
    #[ORM\OneToMany(targetEntity: Delivery::class, mappedBy: 'order')]
    private Collection $deliveries;

    #[ORM\OneToMany(targetEntity: OrderDetails::class, mappedBy: 'order', cascade: ['persist', 'remove'])]
    private Collection $orderDetails;


    // #[ORM\Id]
    // #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: "orders")]
    // #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    // private ?Product $product = null;

    public function __construct()
    {
        $this->deliveries = new ArrayCollection();
        $this->orderDetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getPaymentDate(): ?\DateTimeImmutable
    {
        return $this->paymentDate;
    }

    public function setPaymentDate(\DateTimeImmutable $paymentDate): static
    {
        $this->paymentDate = $paymentDate;

        return $this;
    }

    public function getPaymentMethod(): ?string
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(string $paymentMethod): static
    {
        $this->paymentMethod = $paymentMethod;

        return $this;
    }

    public function getTotal(): ?string
    {
        return $this->total;
    }

    public function setTotal(string $total): static
    {
        $this->total = $total;

        return $this;
    }

    public function getDocument(): ?string
    {
        return $this->document;
    }

    public function setDocument(?string $document): static
    {
        $this->document = $document;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getPaymentStatus(): ?string
    {
        return $this->paymentStatus;
    }

    public function setPaymentStatus(string $paymentStatus): static
    {
        $this->paymentStatus = $paymentStatus;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Delivery>
     */
    public function getDeliveries(): Collection
    {
        return $this->deliveries;
    }

    public function addDelivery(Delivery $delivery): static
    {
        if (!$this->deliveries->contains($delivery)) {
            $this->deliveries->add($delivery);
            $delivery->setOrder($this);
        }

        return $this;
    }

    public function removeDelivery(Delivery $delivery): static
    {
        if ($this->deliveries->removeElement($delivery)) {
            // set the owning side to null (unless already changed)
            if ($delivery->getOrder() === $this) {
                $delivery->setOrder(null);
            }
        }

        return $this;
    }

    public function getOrderDetails(): Collection
    {
        return $this->orderDetails;
    }
}


// enum Status: string
// {
//     case PENDING = 'pending';   // Commande en attente
//     case SENT = 'sent';         // Commande envoyée
//     case REJECTED = 'rejected'; // Commande refusée

//     /**
//      * Méthode pour obtenir une description de l'état
//      */
//     public function getDescription(): string
//     {
//         return match ($this) {
//             self::PENDING => 'La commande est en attente.',
//             self::SENT => 'La commande a été envoyée.',
//             self::REJECTED => 'La commande a été refusée.',
//         };
//     }

//     /**
//      * Méthode pour vérifier si une commande est dans un état final
//      */
//     public function isFinal(): bool
//     {
//         return match ($this) {
//             self::SENT, self::REJECTED => true,
//             self::PENDING => false,
//         };
    
//     }
// }

// $status = Status::PENDING;

// echo $status->value; // Output: pending
// echo $status->getDescription(); // Output: La commande est en attente.

// $status = Status::SENT;

// echo $status->getDescription(); // Output: La commande a été envoyée.
// echo $status->isFinal() ? 'État final' : 'État non final'; // Output: État final

// foreach (Status::cases() as $status) {
//    echo $status->name . ' => ' . $status->getDescription() . PHP_EOL;
// }
