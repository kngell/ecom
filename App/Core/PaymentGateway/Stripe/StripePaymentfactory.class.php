<?php

declare(strict_types=1);

class StripePaymentGatewayFactory
{
    protected Container $container;

    /**
     * Main constructor
     *==================================================================.
     */
    public function __construct()
    {
    }

    public function create(array $params, array $user_cart, Object $userData, array $credentials) : PaymentGatewayInterface
    {
        $stripe = $this->container->make(StripePaymentGateway::class);
        $stripe->setParams($params, $user_cart, $userData, $credentials);
        if (!$stripe instanceof PaymentGatewayInterface) {
            throw new BaseUnexpectedValueException($stripe . ' is not a valid Payment Object!');
        }

        return $stripe;
    }
}
