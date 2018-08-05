<?php
/**
 * User: Gildas Tema
 * Date: 8/5/18
 * Time: 4:29 PM
 */

namespace Eumm\Response;



use Eumm\Models\Account;

class ResponseAccount extends Response
{
    /**
     * @var Account
     */
    private $account;

    public function __construct($status, $message, Account $account = null)
    {
        parent::__construct($status, $message);
        $this->account = $account;
    }

    /**
     * @return Account
     */
    public function getAccount()
    {
        return $this->account;
    }


}