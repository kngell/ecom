<?php

declare(strict_types=1);
/**
 * Class RandomStringGenerator.
 */
class RandomStringGenerator
{
    /** @var string */
    protected $alphabet;

    /** @var int */
    protected $alphabetLength;

    /**
     * @param string $alphabet
     */
    public function __construct()
    {
    }

    /**
     * @param string $alphabet
     */
    public function setAlphabet($alphabet)
    {
        if ('' !== $alphabet) {
            $this->alphabet = $alphabet;
        } else {
            $this->alphabet =
                implode(range('a', 'z'))
                . implode(range('A', 'Z'))
                . implode(range(0, 9));
        }
        $this->alphabetLength = strlen($this->alphabet);
    }

    public function getAlphabet()
    {
        return $this->alphabet;
    }

    protected function getRandomInteger($min, $max)
    {
        $range = ($max - $min);

        if ($range < 0) {
            // Not so random...
            return $min;
        }

        $log = log($range, 2);

        // Length in bytes.
        $bytes = (int) ($log / 8) + 1;

        // Length in bits.
        $bits = (int) $log + 1;

        // Set all lower bits to 1.
        $filter = (int) (1 << $bits) - 1;

        do {
            $rnd = hexdec(bin2hex(random_bytes($bytes)));

            // Discard irrelevant bits.
            $rnd = $rnd & $filter;
        } while ($rnd >= $range);

        return $min + $rnd;
    }
}
