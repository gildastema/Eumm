<?php
/**
 * User: Gildas Tema
 * Date: 8/5/18
 * Time: 6:19 PM
 */

namespace Eumm\Models;


class Payment
{
    private $phone;
    private $amount;
    private $reference;
    private $balance;

    /**
     * Payment constructor.
     * @param $phone
     * @param $amount
     * @param $reference
     * @param $balance
     */
    public function __construct($phone, $amount, $reference, $balance)
    {
        $this->phone = $phone;
        $this->amount = $amount;
        $this->reference = $reference;
        $this->balance = $balance;
    }


}