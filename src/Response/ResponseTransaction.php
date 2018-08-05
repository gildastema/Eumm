<?php
/**
 * User: Gildas Tema
 * Date: 8/5/18
 * Time: 4:43 PM
 */

namespace Eumm\Response;


use Eumm\Models\Transaction;

class ResponseTransaction extends Response
{
    /**
     * @var Transaction
     */
    private $transaction;
    private $message;

    public function __construct($status, $message, $transaction = null)
    {
        parent::__construct($status, $message);
        $this->message = $message;
    }

    /**
     * @return Transaction
     */
    public function getTransaction()
    {
        return $this->transaction;
    }
}