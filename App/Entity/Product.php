<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * Product Class
 * =======================================================================.
 * @ORM\Entity
 * @ORM\Table(name="products")
 */
class Product
{
    /**
     * Product ID.
     * @ORM\id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;
    /**
     * Product Name.
     * @ORM\Column(type="string")
     */
    private $name;
    /**
     * Product Description.
     * @ORM\Column(type="text")
     */
    private $description;
    /**
     * Product Price.
     * @ORM\Column(type="integer")
     */
    private $price;
    /**
     * Created At.
     *@ORM\Column(type="datetime",name="created_at")
     * @var \DateTimeInterface
     */
    private $createdAt;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
    }

    /**
     * Get product ID.
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * Get product Name.
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * Set product Name.
     *
     * @return  self
     */
    public function setName($name) : self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get product Description.
     */
    public function getDescription() : string
    {
        return $this->description;
    }

    /**
     * Set product Description.
     *
     * @return  self
     */
    public function setDescription($description) : self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get product Price.
     */
    public function getPrice() : int
    {
        return $this->price;
    }

    /**
     * Set product Price.
     *
     * @return  self
     */
    public function setPrice($price) : self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get *@ORM\Column(type="datetime",name="created_at")
     *
     * @return  \DateTimeInterface
     */
    public function getCreatedAt() :\DateTimeInterface
    {
        return $this->createdAt;
    }
}