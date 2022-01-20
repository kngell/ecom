<?php

declare(strict_types=1);

namespace App\Tests;

use App\Entity\Product;

class ProductsTest extends DatabaseDependantTestCase
{
    /** @test */
    public function a_product_can_be_created()
    {
        //SETUP
        $name = 'PHP eCommerce Project #22: ';
        $description = 'Il est nécessaire si vous  ';
        $product = new Product();
        $product->setName($name);
        $product->setDescription($description);
        $product->setPrice(94400);
        // DO SOMETHING
        $this->entityManager->persist($product);
        $this->entityManager->flush();
        // MEKA ASSERTIONS
        $this->assertDatabaseHasEntity(Product::class, ['name' => $name, 'description' => $description]);
        $this->assertDatabaseNotHas(Product::class, ['name' => $name, 'description' => 'foobar']);
    }

    /** @test */
    public function can_test_a_porduct_is_in_database()
    {
        //SETUP
        $name = 'PHP eCommerce Project #22: ';
        $description = 'Il est nécessaire si vous  ';
        $product = new Product();
        $product->setName($name);
        $product->setDescription($description);
        $product->setPrice(94400);
        // DO SOMETHING
        $this->entityManager->persist($product);
        $this->entityManager->flush();
        // MAKE ASSERTIONS
        $this->assertDataBaseHas('products', [
            'name' => $name,
            'description' => $description,
        ]);
    }
}
