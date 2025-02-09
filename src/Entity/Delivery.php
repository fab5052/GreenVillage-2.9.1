<?php

namespace App\Entity;

use App\Repository\DeliveryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DeliveryRepository::class)]
class Delivery
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column(length: 255)]
    private ?string $note = null;

    #[ORM\ManyToOne(inversedBy: 'deliveries')]
    private ?Order $order = null;

    #[ORM\OneToMany(mappedBy: "delivery", targetEntity: DeliveryDetails::class)]
    private Collection $deliveryDetails;

    public function __construct()
{
    $this->deliveryDetails = new ArrayCollection();
}

    public function getId(): ?int
    {
        return $this->id;
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

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(string $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(?Order $order): static
    {
        $this->order = $order;

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
        $deliveryDetail->setDelivery($this);
    }

    return $this;
}

public function removeDeliveryDetail(DeliveryDetails $deliveryDetail): self
{
    if ($this->deliveryDetails->removeElement($deliveryDetail)) {
        if ($deliveryDetail->getDelivery() === $this) {
            $deliveryDetail->setDelivery(null);
        }
    }

    return $this;
}
}
