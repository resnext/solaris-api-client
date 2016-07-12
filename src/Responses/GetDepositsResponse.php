<?php

namespace Solaris\Responses;

use Solaris\Entities\Deposit;
use Solaris\Response;

class GetDepositsResponse extends Response
{
    protected $deposits = [];

    public function init()
    {
        $data = $this->payload->getData();

        foreach ($data['deposits'] as $deposit) {
            $this->deposits[] = new Deposit($deposit['customer_id'], $deposit['deposit'], $deposit['firstDepositTimestamp']);
        }
    }

    /**
     * @return \Solaris\Entities\Deposit[]
     */
    public function getDeposits()
    {
        return $this->deposits;
    }

}