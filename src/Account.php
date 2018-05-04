<?php
/**
 * User: Gildas Tema
 * Date: 5/4/18
 * Time: 1:57 PM
 */

namespace Eumm;


class Account
{
    private $phone;
    private $name;
    private $status;
    private $plan;

    /**
     * Account constructor.
     * @param $phone
     * @param $name
     * @param $status
     * @param $plan
     */
    public function __construct($phone, $name, $status, $plan)
    {
        $this->phone = $phone;
        $this->name = $name;
        $this->status = $status;
        $this->plan = $plan;
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getPlan()
    {
        return $this->plan;
    }


}