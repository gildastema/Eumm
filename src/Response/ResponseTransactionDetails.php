<?php
/**
 * User: Gildas Tema
 * Date: 8/5/18
 * Time: 5:48 PM
 */

namespace Eumm\Response;


use Eumm\Models\TransactionDetails;

class ResponseTransactionDetails extends Response
{
    /**
     * @var TransactionDetails
     */
    private $transactionDetails;

    public function __construct($status, $message, TransactionDetails $transactionDetails)
    {
        parent::__construct($status, $message);
        $this->transactionDetails = $transactionDetails;
    }

    /**
     * @return TransactionDetails
     */
    public function getTransactionDetails()
    {
        return $this->transactionDetails;
    }
}