<?php

namespace Solaris\Tests;

class DepositsTest extends TestCase
{
    public function testDepositsRetrieving()
    {
        $response = $this->apiClient->getDeposits();
        $deposits = $response->getDeposits();
        $this->assertTrue(is_array($deposits), 'Deposits list should be an array.');
    }
}