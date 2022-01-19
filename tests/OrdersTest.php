<?php

declare(strict_types=1);

namespace App\Tests;

use App\Entity\Item;
use App\Entity\Order;
use App\Entity\Product;

class OrdersTest extends DatabaseDependantTestCase
{
    private string $deliveryName = 'Ringo Starr';
    private string $deliveryAddress = '44, Penny Lane, Liverpool';

    protected function setUp() : void
    {
        parent::setUp();
        //SETUP
        $order = new Order();
        $order->setDeliveryName($this->deliveryName);
        $order->setDeliveryAddress($this->deliveryAddress);
        // DO SOMETHING
        $this->entityManager->persist($order);
        $this->entityManager->flush();
    }

    /** @test */
    public function a_order_can_be_created()
    {

        // MEKA ASSERTIONS
        $this->assertDatabaseHasEntity(Order::class, [
            'deliveryName' => $this->deliveryName,
            'deliveryAddress' => $this->deliveryAddress,
            'cancelledAt' => null,
        ]);
    }

    /** @test */
    public function an_order_can_be_updated()
    {
        //SETUP
        /** @var Order */
        $order = $this->entityManager->getRepository(Order::class)->findOneBy([
            'deliveryName' => $this->deliveryName,
            'deliveryAddress' => $this->deliveryAddress,
        ]);
        $newAddress = '9 newcastle Avenue, liverpool';
        //DO SOMETHING
        $order->setDeliveryAddress($newAddress);
        $this->entityManager->persist($order);
        $this->entityManager->flush();
        //MAKE ASSERTION
        $this->assertDatabaseHasEntity(Order::class, ['deliveryName' => $this->deliveryName, 'deliveryAddress' => $newAddress]);
        $this->assertDatabaseNotHas(Order::class, ['deliveryName' => $this->deliveryName, 'deliveryAddress' => $this->deliveryAddress]);
    }

    /** @test */
    public function an_order_can_be_canclled()
    {
        //SETUP
        /** @var Order */
        $order = $this->entityManager->getRepository(Order::class)->findOneBy([
            'deliveryName' => $this->deliveryName,
            'deliveryAddress' => $this->deliveryAddress,
        ]);
        $cancelledAt = new \DateTimeImmutable();
        //DO SOMETHING
        $order->setCancelledAt($cancelledAt);
        $this->entityManager->persist($order);
        $this->entityManager->flush();
        //MAKE ASSERTION
        $this->assertDatabaseHasEntity(Order::class, [
            'deliveryName' => $this->deliveryName,
            'deliveryAddress' => $this->deliveryAddress,
            'cancelledAt' => $cancelledAt,
        ]);
    }

    /** @test */
    public function an_item_can_be_added_to_an_order()
    {
        //SETUP
        $name = 'PHP eCommerce Project #22: ';
        $description = 'Learn how to create products, orders, and items in PHP using Doctrine ORM  and MySQL and test the relationship between the three using PHPUnit';
        $product = new Product();
        $product->setName($name);
        $product->setDescription($description);
        $product->setPrice(94400);
        $this->entityManager->persist($product);
        $this->entityManager->flush();
        /** @var Order */
        $order = $this->entityManager->getRepository(Order::class)->findOneBy([
                'deliveryName' => $this->deliveryName,
                'deliveryAddress' => $this->deliveryAddress,
            ]);
        // DO SOMETHING
        $item = new Item();
        $item->setOrder($order);
        $item->setProduct($product);
        $item->setPrice($product->getPrice());
        $this->entityManager->persist($item);
        $this->entityManager->flush();
        // MAKE ASSERTIONS
        $this->assertDatabaseHas('items', [
            'price' => $product->getPrice(),
            'order_id' => $order->getId(),
            'product_id' => $product->getId(),
        ]);

        // check we can retrieve items for an order
        self::assertCount(1, $order->getItems());
    }

    /** @test */
    public function multiple_items_can_be_added_to_an_order()
    {
        //SETUP
        $multiple = 3;
        $name = 'PHP eCommerce Project #22: ';
        $description = 'Learn how to create products, orders, and items in PHP using Doctrine ORM  and MySQL and test the relationship between the three using PHPUnit';
        $product = new Product();
        $product->setName($name);
        $product->setDescription($description);
        $product->setPrice(94400);
        $this->entityManager->persist($product);
        $this->entityManager->flush();
        /** @var Order */
        $order = $this->entityManager->getRepository(Order::class)->findOneBy([
                    'deliveryName' => $this->deliveryName,
                    'deliveryAddress' => $this->deliveryAddress,
                ]);
        // DO SOMETHING
        for ($i = 0; $i < $multiple; $i++) {
            $item = new Item();
            $item->setOrder($order);
            $item->setProduct($product);
            $item->setPrice($product->getPrice());
            $this->entityManager->persist($item);
        }

        $this->entityManager->flush();
        // MAKE ASSERTIONS
        $this->assertDatabaseHasEntity(Item::class, [
            'price' => $product->getPrice(),
        ]);
        // check we can retrieve items for an order
        self::assertCount($multiple, $order->getItems());
    }
}