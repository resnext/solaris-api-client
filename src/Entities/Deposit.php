<?php

namespace Solaris\Entities;

class Deposit
{


    protected $customerId;

    protected $amount;

    protected $timestamp;

    public function __construct($customerId, $amount, $timestamp)
    {
        $this->customerId = intval($customerId);
        $this->amount = floatval($amount);
        $this->timestamp = intval($timestamp);
    }

    /**
     * @return int
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return int
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }
}