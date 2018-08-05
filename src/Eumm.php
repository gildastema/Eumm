<?php
/**
 * User: Gildas Tema
 * Date: 5/4/18
 * Time: 11:58 AM
 */

namespace Eumm;


use Eumm\Models\Account;
use Eumm\Models\Payment;
use Eumm\Models\Transaction;
use Eumm\Models\TransactionDetails;
use Eumm\Response\Response;
use Eumm\Response\ResponseAccount;
use Eumm\Response\ResponseBalance;
use Eumm\Response\ResponsePayment;
use Eumm\Response\ResponseTransaction;
use GuzzleHttp\Client;

class Eumm
{
    const SUFFIX="eumobile_api/v2/";

    private $key;
    private $pwd;
    private $id;
    private $client;

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
          if($data->statut === 100) return new ResponseBalance($data->statut, $data->message, $data->balance);
          else return new ResponseBalance($data->statut, $data->message);
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
     * @param $sender_phone
     * @param $phone
     * @param $amount
     * @param $referenceId
     * @return ResponseTransaction
     * @throws \Exception
     */
    public function sendMoney($sender_phone, $phone, $amount, $referenceId)
    {
        $response = $this->makeRequest('sendMoney', [
            'amount' => $amount,
            'phone' => $phone,
            'reference_id' => $referenceId,
            'sender_phone' => $sender_phone,
            'hash' => md5($this->id.$this->pwd.$amount.$phone.$sender_phone.$this->key)
        ]);
        $this->VerifyIfResponseIsNot($response);
        if($response->getStatusCode() == 200){
            $data = $this->decodeResponse($response);
            if($data->statut == 100){
                $transaction = new Transaction($data->phone, $data->message, $data->amount,$data->fees, $data->transaction,$data->balance,$data->datetime);
                return new ResponseTransaction($data->statut, $data->message, $transaction);
            }else return new ResponseTransaction($data->statut, $data->message);
        }else{
            throw new \Exception("Error",500);
        }

    }

    /**
     * @param $transactionId
     * @return ResponseTransaction
     * @throws \Exception
     */
    public function getTransactionDetails($transactionId)
    {
        $response = $this->makeRequest('getTransactionDetails', [
            'transaction' => $transactionId,
            'hash' => md5($this->id.$this->pwd.$transactionId.$this->key)
        ]);
        $this->VerifyIfResponseIsNot($response);

        if($response->getStatusCode() == 200){
            $data = $this->decodeResponse($response);
            if($data->statut == 100) {
                $transactionDetails = new TransactionDetails($data->trans_id, $data->reference_id, $data->source, $data->destination, $data->amount,$data->fee,
                    $data->tax, $data->date, $data->result_desc, $data->type);
                return new ResponseTransaction($data->statut, null, $transactionDetails);
            }else{
                return new ResponseTransaction($data->statut, $data->message);
            }
        }else{
            throw new \Exception("Error",500);
        }
    }



    /**
     * @param $billno
     * @param $amount
     * @param $date
     * @param $duedate
     * @param $name
     * @param $phone
     * @param $custId
     * @param $label
     * @param string $currency
     * @return ResponsePayment
     * @throws \Exception
     */
    public function sendPaymentRequest($billno, $amount, $date,$duedate,$name,$phone,$custId, $label, $currency = "XAF")
    {
        $response = $this->makeRequest('sendPaymentRequest', [
            'amount' => $amount,
            'currency' => $currency,
            'date'   => $date,
            'duedate' => $duedate,
            'name' => $name,
            'phone' => $phone,
            'custid' => $custId,
            'label'  => $label,
            'hash' => md5($this->id.$this->pwd.$billno.$amount.$currency.$date.$duedate.$name.$phone.$custId.$label.$this->key)
        ]);
        $this->VerifyIfResponseIsNot($response);
        if($response->getStatusCode() == 200){
            $data = $this->decodeResponse($response);
            if($data->statut == 100){
                $payment = new Payment($data->phone, $data->amount, $data->reference,$data->balance);
                return new ResponsePayment($data->statut, $data->message, $payment);
            }else{
                return new ResponsePayment($data->statut, $data->message);
            }
        }else{
            throw new \Exception("Error",500);
        }
    }

    /**
     * @param $billno
     * @param $phone
     * @return Response
     * @throws \Exception
     */
    public function getPaymentStatus($billno, $phone)
    {
        $response = $this->makeRequest('sendPaymentRequest', [
            'billno' => $billno,
            'phone' => $phone,
            'hash' => md5($this->id.$this->pwd.$billno.$phone.$this->key)
        ]);
        $this->VerifyIfResponseIsNot($response);
        if($response->getStatusCode() == 200){
            $data = $this->decodeResponse($response);
            return new Response($data->statut, $data->message);
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