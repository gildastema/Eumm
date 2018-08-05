<?php
/**
 * User: Gildas Tema
 * Date: 5/4/18
 * Time: 3:12 PM
 */

namespace Eumm\Models;


class Transaction
{
    private $phone;
    private $message;
    private $amount;
    private $fee;
    private $transaction;
    private $balance;
    private $date;

    /**
     * CashIn constructor.
     * @param $phone
     * @param $message
     * @param $amount
     * @param $fee
     * @param $transaction
     * @param $balance
     * @param $date
     */
    public function __construct($phone, $message, $amount, $fee, $transaction, $balance, $date)
    {
        $this->phone = $phone;
        $this->message = $message;
        $this->amount = $amount;
        $this->fee = $fee;
        $this->transaction = $transaction;
        $this->balance = $balance;
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return mixed
     */
    public function getFee()
    {
        return $this->fee;
    }

    /**
     * @return mixed
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * @return mixed
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    public function __toString()
    {
        return $this->message;
    }


}