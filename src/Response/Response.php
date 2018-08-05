<?php
/**
 * User: Gildas Tema
 * Date: 8/4/18
 * Time: 8:06 PM
 */

namespace Eumm\Response;


class Response
{
    private $status;
    private $message;

    /**
     * Response constructor.
     * @param $status
     * @param $message
     */
    public function __construct($status, $message)
    {
        $this->status = $status;
        $this->message = $message;
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
    public function getMessage()
    {
        return $this->message;
    }




}