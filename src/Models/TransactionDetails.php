<?php
/**
 * User: Gildas Tema
 * Date: 8/5/18
 * Time: 5:45 PM
 */

namespace Eumm\Models;


class TransactionDetails
{
    private $transactionId;
    private $referenceId;
    private $source;
    private $destination;
    private $amount;
    private $fee;
    private $tax;
    private $date;
    private $resultDesc;
    private $type;

    /**
     * TransactionDetails constructor.
     * @param $transactionId
     * @param $referenceId
     * @param $source
     * @param $destination
     * @param $amount
     * @param $fee
     * @param $tax
     * @param $date
     * @param $resultDesc
     * @param $type
     */
    public function __construct($transactionId, $referenceId, $source, $destination, $amount, $fee, $tax, $date, $resultDesc, $type)
    {
        $this->transactionId = $transactionId;
        $this->referenceId = $referenceId;
        $this->source = $source;
        $this->destination = $destination;
        $this->amount = $amount;
        $this->fee = $fee;
        $this->tax = $tax;
        $this->date = $date;
        $this->resultDesc = $resultDesc;
        $this->type = $type;
    }


}