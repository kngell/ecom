<?php

declare(strict_types=1);

namespace App\Tests;

use App\Session\Session;
use PHPUnit\Framework\TestCase;

class SessionTest extends TestCase
{
    protected function setup() : void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
    }

    /** @test */
    public function can_check_if_a_session_is_started()
    {
        //SETUP
        // Create a session
        $session = new Session();
        //Assert Session is not started
        self::assertFalse($session->isStarted());
    }

    /** @test */
    public function a_session_can_be_started()
    {
        //SETUP
        $session = new Session();
        //DO SOMETHING
        $sessionStatus = $session->start();
        //MAKE ASSERTIONS
        self::assertTrue($session->isStarted());
        self::assertTrue($sessionStatus);
    }

    /** @test */
    public function item_caan_be_added_to_the_session()
    {
        //SETUP
        $productId1 = 1;
        $productId2 = 2;
        $session = new Session();
        $session->start();
        //DO SOMETHING
        $session->set('cart.items', [
            $productId1 => [
                'quantity' => 1,
                'price' => 1099,
            ],
            $productId2 => [
                'quantity' => 2,
                'price' => 599,
            ],
        ]);
        //MAKE ASSERTIONS
        $this->arraysHasKeys($_SESSION['cart.items'], [$productId1, $productId2]);
    }

    public function arraysHasKeys(array $arrays, array $keys)
    {
        $diff = array_diff($keys, array_keys($arrays));
        self::assertTrue(!$diff, 'The array does not contains the following key(s) ' . implode(', ', $diff));
    }

    /** @test */
    public function can_check_if_an_tem_exits_in_session()
    {
        //SETUP
        $session = new Session();
        $session->start();
        //DO SOMETHING
        $session->set('auth.id', 1);
        //MAKE ASSERTIONS
        self::assertTrue($session->has('auth.id'));
        self::assertFalse($session->has('false'));
    }

    /** @test */
    public function can_retieve_an_item_from_the_session()
    {
        //SETUP
        $session = new Session();
        $session->start();
        $session->set('auth.id', 678);
        //DO SOMETHING
        $authID = $session->get('auth.id');
        $falseItem = $session->get('false.item');
        $default = $session->get('false.item', 'retrieved default');
        //MAKE ASSERTIONS
        self::assertEquals(678, $authID);
        self::assertNull($falseItem);
        self::assertEquals('retrieved default', $default);
    }

    /** @test */
    public function item_can_be_removed_by_key()
    {
        //SETUP
        $session = new Session();
        $session->start();
        $session->set('auth.id', 678);
        //DO SOMETHING
        $session->remove('auth.id');
        //MAKE ASSERTIONS
        self::assertNull($session->get('auth.id'));
    }

    /** @test */
    public function session_can_be_cleared()
    {
        //SETUP
        $session = new Session();
        $session->start();
        $session->set('key1', 'foo');
        $session->set('key2', 'bar');
        //DO SOMETHING
        $session->clear();
        //MAKE ASSERTIONS
        self::assertEmpty($_SESSION);
    }
}
