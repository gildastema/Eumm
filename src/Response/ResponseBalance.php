<?php
/**
 * User: Gildas Tema
 * Date: 8/4/18
 * Time: 8:08 PM
 */

namespace Eumm\Response;


class ResponseBalance extends Response
{
    private $balance;

    public function __construct($status, $message, $balance = null)
    {
        parent::__construct($status, $message);

        $this->balance = $balance;
    }

    /**
     * @return mixed
     */
    public function getBalance()
    {
        return $this->balance;
    }


}