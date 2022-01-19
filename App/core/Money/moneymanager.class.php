<?php

declare(strict_types=1);
use Brick\Math\RoundingMode;
use Brick\Money\Context\AutoContext;
use Brick\Money\Context\CustomContext;
use Brick\Money\Money;

class MoneyManager
{
    /** @var static */
    protected static $inst;

    /**
     * Get container instance
     * ====================================================================================================.
     * @return static
     */
    public static function getInstance(): static
    {
        if (is_null(static::$inst)) {
            static::$inst = new static();
        }

        return self::$inst;
    }

    public function getPrice(mixed $price, string $currencyCode = 'EUR')
    {
        // return Money::of($price, $currencyCode, new AutoContext())->getAmount();
        return Money::ofMinor($price, $currencyCode);
    }

    public function setPrice(mixed $price) : string
    {
        if (!empty($price) && isset($price)) {
            return (string) $this->getAmount($price);
        }

        return '0';
    }

    public function setCurrency(Money $price) : string
    {
        return $price->getCurrency()->getCurrencyCode();
    }

    public function getAmount($p = '')
    {
        if (empty($p)) {
            $p = 0;
        }

        return Money::of($p, 'EUR', new AutoContext())->getAmount();
    }

    public function getIntAmount($p) : int
    {
        return Money::of($p, 'EUR', new CustomContext(2), RoundingMode::UP)->getMinorAmount()->toInt();
    }
}
