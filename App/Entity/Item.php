<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Product Class
 * =======================================================================.
 * @ORM\Entity
 * @ORM\Table(name="items")
 */
class Item
{
    /**
     * Item ID.
     * @ORM\id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;
    /**
     * Item Price.
     * @ORM\Column(type="integer")
     */
    private $price;
    /**
     * Order ManytoOne.
     *
     * @ORM\ManyToOne(targetEntity="Order")
     * @ORM\joinColumn(nullable=false,name="order_id")
     */
    private $order;

    /**
     * Product ManytoOne.
     *
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\joinColumn(nullable=false,name="product_id")
     */
    private $product;

    /**
     * Created At.
     *@ORM\Column(type="datetime",name="created_at")
     * @var \DateTimeInterface
     */
    private $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    /**
     * Get item ID.
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * Get item Price.
     */
    public function getPrice() : int
    {
        return $this->price;
    }

    /**
     * Set item Price.
     *
     * @return  self
     */
    public function setPrice($price) : self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get order ManytoOne.
     */
    public function getOrder() : mixed
    {
        return $this->order;
    }

    /**
     * Set order ManytoOne.
     *
     * @return  self
     */
    public function setOrder(Order $order) : self
    {
        $this->order = $order;
        $order->addItem($this);

        return $this;
    }

    /**
     * Get product ManytoOne.
     */
    public function getProduct() : mixed
    {
        return $this->product;
    }

    /**
     * Set product ManytoOne.
     *
     * @return  self
     */
    public function setProduct($product) : self
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get *@ORM\Column(type="datetime",name="created_at")
     *
     * @return  \DateTimeInterface
     */
    public function getCreatedAt() : \DateTimeInterface
    {
        return $this->createdAt;
    }
}