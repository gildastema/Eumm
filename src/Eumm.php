<?php
/**
 * User: Gildas Tema
 * Date: 5/4/18
 * Time: 11:58 AM
 */

namespace Eumm;


use Eumm\Models\Account;
use Eumm\Models\Transaction;
use Eumm\Response\ResponseAccount;
use Eumm\Response\ResponseBalance;
use Eumm\Response\ResponseTransaction;
use GuzzleHttp\Client;

class Eumm
{
    const SUFFIX="eumobile_api/v2.1/";

    private $key;
    private $pwd;
    private $id;
    private $client;
    private $statut;
    private $message;
    private $balance;

    /**
     * Eumm constructor.
     * @param $id
     * @param $key
     * @param $pwd
     * @param  $ip
     */
    public function __construct($id, $key, $pwd, $ip)
    {
        $this->id = $id;
        $this->key=$key;
        $this->pwd = $pwd;

        $this->client = new Client([
            'base_uri' =>$ip.'/'.self::SUFFIX,
            'timeout' => 120.0
        ]);


    }


    /**
     * @return ResponseBalance|null
     * @throws \Exception
     */
    public function getAccountBalance()
    {
       $resp= $this->makeRequest("getAccountBalance",
                [
                    'hash' => md5($this->id.$this->pwd.$this->key)
                ]
           );

       $this->VerifyIfResponseIsNot($resp);
        if($resp->getStatusCode() === 200){
          $data = $this->decodeResponse($resp);
          return new ResponseBalance($data->statut, $data->message, $data->balance);

      }else{
          throw new \Exception("Error",500);

      }

    }

    /**
     * Get Account Details
     * @param string $phone (237 XXXXXXXXX) E.169 format number
     * @return ResponseAccount
     * @throws \Exception
     */
    public function getAccountDetails($phone)
    {
        $response = $this->makeRequest("getAccountDetails", [
            'account' => $phone,
            'hash' => md5($this->id.$this->pwd.$phone.$this->key)
        ]);
        $this->VerifyIfResponseIsNot($response);

        if($response->getStatusCode() == 200){
            $data = $this->decodeResponse($response);

            if($data->statut == 100){
                $account = new Account($data->phone, $data->accountName, $data->accountStatus, $data->accountPlan);
                return new ResponseAccount($data->statut, "Transaction Successful", $account);
            }else{
                return new ResponseAccount($data->statut, $data->message);
            }

        }else{
            throw new \Exception("Error",500);
        }


    }

    /**
     * @param $phone
     * @param $amount
     * @return ResponseTransaction
     * @throws \Exception
     */
    public function cashIn($phone, $amount)
    {
        $response = $this->makeRequest('cashIn', [
            'amount' => $amount,
            'phone' => $phone,
            'hash' => md5($this->id.$this->pwd.$amount.$phone.$this->key)
        ]);
        $this->VerifyIfResponseIsNot($response);
        if($response->getStatusCode() == 200){
            $data = $this->decodeResponse($response);

            if($data->statut == 100){
               $transaction = new Transaction($data->phone, $data->message, $data->amount,$data->fees, $data->transaction,$data->balance,$data->datetime);
               return new ResponseTransaction($data->statut, $data->message, $transaction);
            }else{
                return new ResponseTransaction($data->statut,$data->message);
            }
        }else{
            throw new \Exception("Error",500);
        }
    }


    /**
     * @param string $sender_phone
     * @param string $phone
     * @param int $amount
     * @return CashIn
     * @throws \Exception
     */
    public function sendMoney($sender_phone, $phone, $amount)
    {
        $response = $this->getResponse('sendMoney', [
            'amount' => $amount,
            'phone' => $phone,
            'sender_phone' => $sender_phone,
            'hash' => md5($this->id.$this->pwd.$amount.$phone.$sender_phone.$this->key)
        ]);
        $this->VerifyIfResponseIsNot($response);
        if($response->getStatusCode() == 200){
            $data = $this->decodeResponse($response);
            $this->returnError($data);
            if($data->statut == 100){
                return new CashIn($data->phone, $data->message, $data->amount,$data->fees, $data->transaction,$data->balance,$data->datetime);
            }
        }else{
            throw new \Exception("Error",500);
        }

    }


    private function makeRequest($pathUrl, $data)
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
     * @param $data
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