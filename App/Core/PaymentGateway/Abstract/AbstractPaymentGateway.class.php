<?php

declare(strict_types=1);
abstract class AbstractPaymentGateway extends PaymentGatewayManager
{
    protected PaymentGatewayInterface $pmg;

    public function createPaymentGateawy(array $params, array $user_cart, int $pm_mode = 0) : mixed
    {
        $credentials = YamlFile::get('paymentgateawaykeys');
        switch (true) {
            case $pm_mode === 1:
                $stripe = $this->container->make(StripePaymentGatewayFactory::class);
                $pm_method = json_decode($this->htmlDecode($params['paymentMethod']));
                $this->container->bind(PaymentGatewayInterface::class, fn () => $stripe->create($params, $user_cart, $pm_method, $credentials));

                break;
            case $pm_mode === 5: // paypal

                break;
            default:
                return false;
                break;
        }

        return $this->pmG = $this->container->make(PaymentGatewayInterface::class);
    }

    public function get_paymentGateway()
    {
        return $this->pmg;
    }
}