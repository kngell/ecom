<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Product Class
 * =======================================================================.
 * @ORM\Entity
 * @ORM\Table(name="orders")
 */
class Order
{
    /**
     * Order ID.
     * @ORM\id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private string $id;
    /**
     * Items.
     *
     * @ORM\OneToMany(targetEntity="Item", mappedBy="order")
     */
    private $items;
    /**
     * ORder deliveryName.
     * @ORM\Column(type="string",name="delivery_name")
     */
    private string $deliveryName;

    /**
     * ORder deliveryAddress.
     * @ORM\Column(type="text",name="delivery_address")
     */
    private string $deliveryAddress;

    /**
     * Created At.
     *@ORM\Column(type="datetime",name="created_at")
     * @var \DateTimeInterface
     */
    private DateTimeInterface $createdAt;

    /**
     * Cancelled At.
     *@ORM\Column(type="datetime",name="cancelled_at",nullable=true)
     * @var DateTimeInterface
     */
    private ?\DateTimeInterface $cancelledAt = null;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->items = new ArrayCollection();
    }

    /**
     * Get order ID.
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Get product Name.
     */
    public function getDeliveryName() : string
    {
        return $this->deliveryName;
    }

    /**
     * Set product Name.
     *
     * @return  self
     */
    public function setDeliveryName($deliveryName) : self
    {
        $this->deliveryName = $deliveryName;

        return $this;
    }

    /**
     * Get oRder deliveryAddress.
     */
    public function getDeliveryAddress() : string
    {
        return $this->deliveryAddress;
    }

    /**
     * Set oRder deliveryAddress.
     *
     * @return  self
     */
    public function setDeliveryAddress($deliveryAddress) : self
    {
        $this->deliveryAddress = $deliveryAddress;

        return $this;
    }

    /**
     * Get *@ORM\Column(type="datetime",name="created_at")
     *
     * @return  \DateTimeInterface
     */
    public function getCreatedAt() : DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * Get *@ORM\Column(type="datetime",name="cancelled_at",nullable=true)
     *
     * @return  DateTimeInterface
     */
    public function getCancelledAt() : DateTimeInterface
    {
        return $this->cancelledAt;
    }

    /**
     * Set *@ORM\Column(type="datetime",name="cancelled_at",nullable=true)
     *
     * @param  DateTimeInterface  $cancelledAt  *@ORM\Column(type="datetime",name="cancelled_at",nullable=true)
     *
     * @return  self
     */
    public function setCancelledAt(DateTimeImmutable|null $cancelledAt) : self
    {
        $this->cancelledAt = $cancelledAt;

        return $this;
    }

    /**
     * Get items.
     */
    public function getItems() : mixed
    {
        return $this->items;
    }

    /**
     * Set items.
     *
     * @return  self
     */
    public function addItem(Item $item) : self
    {
        $this->items[] = $item;

        return $this;
    }
}