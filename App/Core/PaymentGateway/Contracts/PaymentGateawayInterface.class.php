<?php

declare(strict_types=1);

interface PaymentGatewayInterface
{
    /**
     * Create Payment Intent
     * --------------------------------------------------------------------------------------------------.
     *
     * @return object
     */
    public function createPaymentIntent() : ?Object;

    /**
     * Confirm Payment Intent
     * --------------------------------------------------------------------------------------------------.
     * @return object
     */
    public function confirmPaymentIntent() : Object;

    /**
     * Create Custumer
     * --------------------------------------------------------------------------------------------------.
     * @return object
     */
    public function createCustomer() : Object;

    /**
     * Get Payment intent
     * --------------------------------------------------------------------------------------------------.
     * @return object
     */
    public function getPaymentIntent() : Object;

    /**
     * Get Customer
     * --------------------------------------------------------------------------------------------------.
     * @return object
     */
    public function getCustomer() : Object;
}
