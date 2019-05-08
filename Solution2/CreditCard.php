<?php

class CreditCard
{
    private $cardNumber;
    private $checkDigit;
    private $prefixes = [
        '4' => 'Visa',
        '51' => 'Mastercard',
        '52' => 'Mastercard',
        '53' => 'Mastercard',
        '54' => 'Mastercard',
        '55' => 'Mastercard',
        '6011' => 'Discover',
        '65' => 'Discover',
        '34' => 'American Express',
        '37' => 'American Express'
    ];

    public function __construct($cardNumber)
    {
        $this->cardNumber = substr($cardNumber, 0, 15);
        $this->checkDigit = substr($cardNumber, 15, 1);
    }

    public function getCardType() {
        foreach ($this->prefixes as $prefix => $cardType) {
            if (strpos(trim($this->cardNumber), (string) $prefix) === 0) {
                return $cardType;
            }
        }

    }

    public function validateCard() {
        if (!$this->getCardType()) {
            return false;
        }
        $total = 0;
        for ($i = strlen($this->cardNumber) - 1; $i > -1; $i--) {
            $int = (int) $this->cardNumber[$i];
            if ($i % 2 === 0 || $i === 0) {
                $double = $int * 2;
                if ($double > 9) {
                    $double = (string) $double;
                    $total += (int) $double[0] + (int) $double[1];
                } else {
                    $total += (int) $int * 2;
                }
            } else {
                $total += $int;
            }
        }
        $total += $this->checkDigit;
        if (($total % 10) === 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return mixed
     */
    public function getCheckDigit()
    {
        return $this->checkDigit;
    }

    /**
     * @return mixed
     */
    public function getCardNumber()
    {
        return $this->cardNumber;
    }
}