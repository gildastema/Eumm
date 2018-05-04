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

    /**
     * Get Balance for the account
     * @return mixed
     * @throws \Exception
     */
    public function getAccountBalance()
    {
       $response= $this->getResponse("getAccountBalance",
                [
                    'hash' => md5($this->id.$this->pwd.$this->key)
                ]
           );
       $this->VerifyIfResponseIsNot($response);
        if($response->getStatusCode() === 200){
          $data = $this->decodeResponse($response);
          $this->returnError($data);
           if ($data->statut == 100){
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
     * Get Account Details
     * @param string $phone (237 XXXXXXXXX) E.169 format number
     * @return Account
     * @throws \Exception
     */
    public function getAccountDetails($phone)
    {
        $response = $this->getResponse("getAccountDetails", [
            'account' => $phone,
            'hash' => md5($this->id.$this->pwd.$phone.$this->key)
        ]);
        $this->VerifyIfResponseIsNot($response);

        if($response->getStatusCode() == 200){
            $data = $this->decodeResponse($response);
            $this->returnError($data);
            if($data->statut == 100){
                return new Account($data->phone, $data->accountName, $data->accountStatus, $data->accountPlan);
            }

        }else{
            throw new \Exception("Error",500);
        }


    }

    private function getResponse($pathUrl, $data)
    {
        $authData = [
            'id' => $this->id,
            'pwd' => $this->pwd,
        ];
        return $this->client->post($pathUrl, ['form_params' => array_merge($authData, $data)]);
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

    /**
     * @param $statut
     * @throws \Exception
     */
    private function returnError($data)
    {
        if($data->statut != 100){
            throw new \Exception($data->message, $data->statut);
        }

    }

    /**
     * @param $response
     * @throws \Exception
     */
    private function VerifyIfResponseIsNot($response)
    {
        if(is_null($response)){
            throw new \Exception("TimeOut Exception", 400);
        }
    }

    /**
     * @param $response
     * @return mixed
     */
    private function decodeResponse($response)
    {
        return \GuzzleHttp\json_decode($response->getBody()->getContents()) ;
    }



}