<?php
/**
 * User: Gildas Tema
 * Date: 8/5/18
 * Time: 6:20 PM
 */

namespace Eumm\Response;


use Eumm\Models\Payment;

class ResponsePayment extends Response
{
    /**
     * @var Payment
     */
    private $payment;

    public function __construct($status, $message, Payment $payment = null)
    {
        parent::__construct($status, $message);
        $this->payment = $payment;
    }

    /**
     * @return Payment
     */
    public function getPayment()
    {
        return $this->payment;
    }
}