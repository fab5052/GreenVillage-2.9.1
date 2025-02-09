<?php

namespace App\Entity;

use App\Entity\Trait\SlugTrait;
use App\Repository\RubricRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use App\EventListener\SlugListener;


#[ORM\Entity(repositoryClass: RubricRepository::class)]
//#[ORM\UniqueConstraint(name: 'slug', columns: ['slug'])]
//#[ORM\UniqueConstraint(name: 'parent', columns: ['parent_id'])]

class Rubric 
{
   //use SlugTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    //#[Groups(["products:read", "parent:read"])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $label = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'Le slug ne peut pas être vide.')]
    private ?string $slug = null; 

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;


    // #[ORM\Column(length: 255)]
    // private ?string $image = null;

    #[ORM\Column(type: 'datetime_immutable', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $createdAt;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'rubrics', cascade: ['remove'])]    
    private ?self $parent = null;

    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'parent', cascade: ['persist', 'remove'])]
    private Collection $rubrics;

    #[ORM\OneToMany(targetEntity: Product::class, mappedBy: 'rubric', cascade: ['remove'])]
    private Collection $products;
    

    public function __construct()
    {
        $this->rubrics = new ArrayCollection();
        $this->products = new ArrayCollection();
     }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

/**
 * @return Collection<int, Rubric>
 */
public function getRubrics(): Collection
{
    return $this->rubrics;
}

// public function setRubric( $rubric): self
//     {
//         $this->rubrics = $rubric;

//         return $this;
//     }

    // public function getImage(): ?string
    // {
    //     return $this->image;
    // }

    // public function setImage(string $image): static
    // {
    //     $this->image = $image;

    //     return $this;
    // }
    
    //  /**
    //  * @return Collection<int, Rubric>
    //  */
    // public function getParent(): Collection
    // {
    //     return $this->parent;
    // }

    // public function addParent(Rubric $rubric): self
    // {
    //     if (!$this->rubrics->contains($rubric)) {
    //         $this->rubrics->add($rubric);
    //         $rubric->setParent($this);
    //     }

    //     return $this;
    // }

    // public function removeParent(Rubric $rubrics): self
    // {
    //     if ($this->rubric->removeElement($rubrics)) {
    //         if ($rubrics->getParent() === $this) {
    //             $rubrics->setParent(null);
    //         }
    //     }
    //     return $this;
    // }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->setRubric($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            if ($product->getRubric() === $this) {
                $product->setRubric(null);
            }
        }

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

}