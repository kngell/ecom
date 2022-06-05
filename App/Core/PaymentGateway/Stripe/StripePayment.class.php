<?php

declare(strict_types=1);

use Stripe\Customer;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\StripeClient;

class StripePayment extends PaymentGateway implements PaymentGatewayInterface
{
    protected ContainerInterface $container;
    protected MoneyManager $money;
    protected PaymentIntent $paymentIntent;
    protected Customer $customer;
    protected array $user_cart;
    protected array $params;
    protected stdClass $pmt_method;
    private string $_pay_mode = 'stripe';

    public function __construct(?MoneyManager $money = null)
    {
        $this->money = $money;
    }

    public function setParams(array $params, array $user_cart, Object $pmt_method, array $credentials) : self
    {
        $this->params = $params;
        $this->pmt_method = $pmt_method;
        $this->user_cart = $user_cart;
        $this->container->singleton(StripeClient::class, fn () => new StripeClient($credentials[$this->_pay_mode]['Secret']));

        return $this;
    }

    public function createCustomer() : self
    {
        $this->customer = $this->container->make(StripeClient::class)->customers->create([
            'description' => 'stripe Customer',
            'email' => $this->params['email'],
            'name' => $this->params['firstName'] . ' ' . $this->params['lastName'],
            'phone' => $this->params['phone'],
            'payment_method' => $this->pmt_method->paymentMethod->id,
        ]);

        return $this;
    }

    public function createPaymentIntent() : ?self
    {
        try {
            if (isset($this->pmt_method->paymentMethod->id)) {
                $this->paymentIntent = $this->container->make(StripeClient::class)->paymentIntents->create([
                    'amount' => $this->money->getIntAmount($this->user_cart[2][1]),
                    'currency' => 'eur',
                    'payment_method_types' => ['card'],
                    'customer' => $this->customer->id,
                    'payment_method' => $this->pmt_method->paymentMethod->id,
                    'confirmation_method' => 'manual',
                    // 'confirm' => true,
                ]);
            }
        } catch (ApiErrorException  $th) {
            throw new ApiErrorException('Error Occured :' . $th);
        }

        return $this;
    }

    public function confirmPaymentIntent(): object
    {
        try {
            if (isset($this->paymentIntent->id)) {
                $intent = $this->container->make(StripeClient::class)->paymentIntents->retrieve($this->paymentIntent->id);
                $intent->confirm();
            }
        } catch (ApiErrorException $th) {
            throw new ApiErrorException('Error Occured :' . $th);
        }

        return $intent;
    }

    public function generateResponse(?PaymentIntent $intent = null) : array
    {
        if ($intent->status == 'requires_action' &&
        $intent->next_action->type == 'use_stripe_sdk') {
            // Tell the client to handle the action
            return [
                'error-action' => [
                    'requires_action' => true,
                    'payment_intent_client_secret' => $intent->client_secret,
                ],
            ];
        }
        if ($intent->status == 'succeeded') {
            // The payment didn’t need any additional actions and completed!
            // Handle post-payment fulfillment
            return [
                'success' => true,
            ];
        } else {
            // Invalid status
            http_response_code(500);

            return ['error' => 'Invalid PaymentIntent status'];
        }
    }

    //Getters
    public function getPaymentIntent() : PaymentIntent
    {
        return $this->paymentIntent;
    }

    public function getUserCart() : array
    {
        return $this->user_cart;
    }

    public function getCredentials() : array
    {
        return $this->credentials;
    }

    public function getCustomer() : Customer
    {
        return $this->customer;
    }
}