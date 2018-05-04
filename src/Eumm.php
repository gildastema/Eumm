<?php
/**
 * User: Gildas Tema
 * Date: 5/4/18
 * Time: 11:58 AM
 */

namespace Eumm;


use GuzzleHttp\Client;

class Eumm
{
    const SUFFIX="eumobile_api/v2/";
    const URL = "http://195.24.207.114:9000/";
    private $key;
    private $pwd;
    private $id;
    private $client;
    private $statut;
    private $message;
    private $balance;
    public function __construct($id, $key, $pwd)
    {
        $this->id = $id;
        $this->key=$key;
        $this->pwd = $pwd;
        $this->client = new Client([
            'base_uri' => self::URL.self::SUFFIX,
            'timeout' => 120.0
        ]);

    }

    public function getAccountBalance()
    {
       $response= $this->client->post('getAccountBalance', ['form_params' =>
                                                                        ['id' => $this->id,
                                                                        'pwd' => $this->pwd,
                                                                        'hash' => md5($this->id.$this->pwd.$this->key)]
                                                                        ]);
      if(is_null($response)){
          throw new \Exception("TimeOut Exception", 400);
      }elseif($response->getStatusCode() === 200){
          $data =\GuzzleHttp\json_decode($response->getBody()->getContents()) ;
          if($data->statut == 101){
              throw new \Exception("Internal server error/unknown result", 101);
          }elseif ($data->statut == 401){
              throw new \Exception("Authentication parameters not set", 401);
          }elseif ($data->statut == 402){
              throw new \Exception("This client is not yet allowed to use this service", 402);
          }elseif ($data->statut == 403){
              throw new \Exception("Required inputs not set / invalid request", 403);
          }elseif ($data->statut == 404){
              throw new \Exception("Service API not set", 404);
          }elseif ($data->statut == 405){
              throw new \Exception("Transaction Failed", 405);
          }elseif ($data->statut == 406){
              throw new \Exception("Unknown service", 406);
          }elseif ($data->statut == 100){
                $this->statut = $data->statut;
                $this->message = $data->message;
                $this->balance = $data->balance;
          }

      }else{
          throw new \Exception("Error",500);
      }

      return $this->balance;
    }


    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return mixed
     */
    public function getStatut()
    {
        return $this->statut;
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
    public function getBalance()
    {
        return $this->balance;
    }



}