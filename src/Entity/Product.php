<?php

namespace App\Entity;

use App\Entity\Trait\SlugTrait;
use App\Entity\Trait\CreatedAtTrait;
use App\Entity\InfoSuppliers;
use App\Entity\Rubric;
use App\Entity\Image;
use App\Assert\GreaterThan;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use DateTimeImmutable;
// use Proxies\__CG__\App\Entity\InfoSuppliers as EntityInfoSuppliers;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
//#[ORM\UniqueConstraint(name: 'slug', columns: ['slug'])]
//#[ORM\UniqueConstraint(name: 'reference', columns: ['reference'])]
class Product
{
   // use CreatedAtTrait;
   //use SlugTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['product:read'])]
    private ?string $label = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['product:read'])]
    private ?string $description = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Groups(['product:read'])]
    private ?string $price = null;

    #[ORM\Column(length: 50)]
    private ?string $reference = null;

    #[ORM\Column(type: 'string', length: '100')]
    #[Assert\NotBlank(message: 'Le slug ne peut pas être vide.')]
    // //#[Assert\Unique]
    private ?string $slug = null;

    #[ORM\Column(type: 'datetime_immutable', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?DateTimeImmutable $updatedAt = null;

    #[Assert\GreaterThan(
        value: 0,
        message: "Le produit est indisponible."
    )]

    #[ORM\Column(type: 'boolean')]
    private bool $isAvailable = true;

    #[ORM\Column(type: 'integer')]
    private int $stock;

    #[ORM\ManyToOne(targetEntity: InfoSuppliers::class, inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?InfoSuppliers $infoSuppliers = null;

    #[ORM\ManyToOne(targetEntity: Rubric::class, inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Rubric $rubric = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $weight = null;

    #[ORM\ManyToOne(targetEntity: Tva::class, inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tva $tva = null;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Image::class, cascade: ['persist', 'remove'])]
    private Collection $images;

    #[ORM\OneToMany(mappedBy: "product", targetEntity: DeliveryDetails::class)]
    private Collection $deliveryDetails;

    #[ORM\OneToMany(targetEntity: OrderDetails::class, mappedBy: 'product')]
    private Collection $orderDetails;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->deliveryDetails = new ArrayCollection();
        $this->orderDetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function label(): ?string
    {
        return $this->label;
    }

    public function setlabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function setStock(int $stock): self
    {
    $this->stock = $stock;
    $this->isAvailable = $stock > 0;

    return $this;
    }

    // public function getViewRubrics(): ?string
    // {
    //     return $this->viewRubrics;
    // }

    // public function viewRubric(string $viewRubric): static
    // {
    //     $this->viewRubrics = $viewRubric;

    //     return $this;
    // }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }


public function getCreatedAt(): ?DateTimeImmutable
{
    return $this->createdAt;
}

public function setCreatedAt(DateTimeImmutable $createdAt): self
{
    $this->createdAt = $createdAt;

    return $this;
}

public function getUpdatedAt(): ?DateTimeImmutable
{
    return $this->updatedAt;
}

public function setUpdatedAt(DateTimeImmutable $updatedAt): self
{
    $this->updatedAt = $updatedAt;

    return $this;
}

/**
 * @return Collection<int, InfoSuppliers>
 */
public function getInfoSuppliers(): ?InfoSuppliers
{
    return $this->infoSuppliers;
}

public function setInfoSuppliers(?InfoSuppliers $infoSuppliers): static
{
    $this->infoSuppliers = $infoSuppliers;

    return $this;
}

public function getImages(): Collection
{
    return $this->images;
}

public function getRubrics(): Rubric
{
    return $this->rubric;
}

public function setRubric( Rubric $rubrics): self
{
    $this->rubric = $rubrics;

    return $this;
}

public function getWeight(): ?string
{
    return $this->weight;
}

public function setWeight(string $weight): self
{
    $this->weight = $weight;

    return $this;
}

public function getTva(): ?Tva
{
    return $this->tva;
}

public function setTva(?Tva $tva): static
{
    $this->tva = $tva;

    return $this;
}

/**
 * @return Collection<int, DeliveryDetails>
 */
public function getDeliveryDetails(): Collection
{
    return $this->deliveryDetails;
}

public function addDeliveryDetail(DeliveryDetails $deliveryDetail): self
{
    if (!$this->deliveryDetails->contains($deliveryDetail)) {
        $this->deliveryDetails->add($deliveryDetail);
        $deliveryDetail->setProduct($this);
    }

    return $this;
}

public function removeDeliveryDetail(DeliveryDetails $deliveryDetail): self
{
    if ($this->deliveryDetails->removeElement($deliveryDetail)) {
        if ($deliveryDetail->getProduct() === $this) {
            $deliveryDetail->setProduct(null);
        }
    }

    return $this;
}

public function getOrderDetails(): Collection
{
    return $this->orderDetails;
}

// public function setOrderDetails(?OrderDetails $orderDetails): static
// {
//     $this->orderDetails = $orderDetails;

//     return $this;
// }

}
