<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* 
 *  ======================================= 
 *  Author     : Pratik Deshar 
 *  License    : Free 
 *  Email      : promenum@gmail.com 

 *  =======================================
 */  

require_once(APPPATH."third_party/vendor/autoload.php");

class Stripe extends CI_Controller {

    public $message;
    public $status;
    public $http_status;
    public $apiKey = 'sk_test_SN97sToWcElRI2kDkUb2Ann2';

    function __construct($config = array()) {
        \Stripe\Stripe::setApiKey($this->apiKey);
    }

    public function charge($myCard = array() ,$amount=0,$description="",$currency='usd'){   

          // $myCard = array(
          //       'number' => '4242424242424242', 
          //       'exp_month' => 8, 
          //       'exp_year' => 2018
          //   );
        if(!$this->_validate_card($myCard)){
            return $this->send_response();
        }
        try {   
 
            $charge = \Stripe\Charge::create(array(
                'card' => $myCard, 
                'amount' => $amount, 
                'currency' => $currency)
            );
         
            if ($charge->paid == true) {
                $response = array();
                $response['status'] = 'success' ;
                $response['id'] = $charge->id;
                $response['amount'] = $charge->amount;
                $response['balance_transaction'] = $charge->balance_transaction;
                $response['created'] = $charge->created;
                $response['customer'] = $charge->customer;
                return $response;
            
            }
        
            return;

        }
 
        catch(Stripe_CardError $e) {
 
        }
        catch (Stripe_InvalidRequestError $e) {
 
        } 
        catch (Stripe_AuthenticationError $e) {
        } 
        catch (Stripe_ApiConnectionError $e) {
        } 
        catch (Stripe_Error $e) {
        } 
        catch (Exception $e) {
        }
        if(isset($e)){
            $this->status = 'error';
            $this->message = $e;
            return $this->send_response();
        }
    }

    function charge_by_customer_id($customer_id="",$amount=0,$currency="usd"){

         try {   
 
    
            $charge = \Stripe\Charge::create(array(
              "amount"   => $amount, 
              "currency" => $currency,
              "customer" => $customer_id 
            ));
         
            if ($charge->paid == true) {
                $response = new stdClass();
                $response->status = 'success';
                

                // $res = array();
                // $res['status'] = 'success' ;
                // $res['id'] = $charge->id;
                // $res['amount'] = $charge->amount;
                // $res['balance_transaction'] = $charge->balance_transaction;
                // $res['created'] = $charge->created;
                // $res['customer'] = $charge->customer;

                // $response->data = $res;
                // debug($charge);
                $response->id = $charge->id;
                $response->amount = $charge->amount;
                $response->balance_transaction = $charge->balance_transaction;
                $response->created = $charge->created;
                $response->customer = $charge->customer;
                return $response;
            
            }

        }
        catch(Stripe\Error\InvalidRequest $e) {

            $body = $e->getJsonBody();
            $err  = $body['error'];

            $this->status = 'error';
            $this->message = $err['message'];
            $this->http_status = $e->getHttpStatus();
            return $this->send_response();


        }

        // catch (Stripe_InvalidRequestError $e) {
 
        // } 
        // catch (Stripe_AuthenticationError $e) {
        // } 
        // catch (Stripe_ApiConnectionError $e) {
        // } 
        // catch (Stripe_Error $e) {
        // } 
        // catch (Exception $e) {
        // }
        // if(isset($e)){
        //     $this->status = 'error';
        //     $this->message = $e;
        //     return $this->send_response();
        // }
    }

    public function create_token($myCard = array() ){

        if(!$this->_validate_card($myCard)){
            return $this->send_response();
        }
     
        try {  

            $token = \Stripe\Token::create(array("card" =>$myCard));
            if ($token->id) {
                $res = new stdClass();
                $res->id = $token->id;
                $res->status = 'success';
                $res->fingerprint = $token->card->fingerprint;
                return $res;
            }

        }
 
        catch(\Stripe\Error\Card $e) {

            $body = $e->getJsonBody();
            $err  = $body['error'];

            $this->status = 'error';
            $this->message = $err['message'];
            $this->http_status = $e->getHttpStatus();
            return $this->send_response();


        }
        // catch (Stripe_ApiConnectionError $e) {
        // } 
        // catch (Stripe_Error $e) {
        // } 
        // catch (Exception $e) {
        // }
        
    }



    public function create_customer($myCard=array(),$user_id=""){
        $token = $this->create_token($myCard);

        if(!isset($token->id) ){
            return $this->send_response();
        }
        if(empty($user_id)){
            $this->status = 'error';
            $this->message = 'Missing User Information';
            return $this->send_response();
        }
     
        try {  
            $customer = \Stripe\Customer::create(array(
              "description" => $user_id,
              "source" => $token->id
            ));
            if($customer->object == 'customer'){
                $response = new stdClass();
                $response->status = 'success';
                $response->id = $customer->id;
                $response->description = $customer->description;
                $response->fingerprint = $customer->sources->data[0]->fingerprint;
                return $response;


            }
            return;

        }
 
        catch(Stripe_CardError $e) {
        }
        catch (Stripe_ApiConnectionError $e) {
        } 
        catch (Stripe_Error $e) {
        } 
        catch (Exception $e) {
        }
        if(isset($e)){
            $this->status = 'error';
            $this->message = $e;
            return $this->send_response();
        }
    }

    public function get_customer_info($customer_stripe_id="") {

        if(empty($customer_stripe_id)){
            $this->status = 'error';
            $this->message = 'Missing Customer Information';
            return $this->send_response();
        }

        
           
        try {   
 
           $customer = \Stripe\Customer::retrieve($customer_stripe_id);

        
            return;

        }
 
        catch(Stripe_CardError $e) {
 
        }
        catch (Stripe_InvalidRequestError $e) {
 
        } 
        catch (Stripe_AuthenticationError $e) {
        } 
        catch (Stripe_ApiConnectionError $e) {
        } 
        catch (Stripe_Error $e) {
        } 
        catch (Exception $e) {
        }
        if(isset($e)){
            $this->status = 'error';
            $this->message = $e;
            return $this->send_response();
        }
    }

    public function _validate_card($myCard = array()){
        $status = 'success';

        if(empty($myCard)){
            $this->status = 'error';
            $this->message = 'Card is missing';
            return false;

        }
        $search_array = $myCard;
        if (!array_key_exists('number', $search_array)) {
            $this->status = 'error';
            $this->message = 'Card Number is missing';
            return false;
        }
    
        if (!array_key_exists('exp_month', $search_array)) {
            $this->status = 'error';
            $this->message = 'Card expiry month is missing';
            return false;
        }
        
        if (!array_key_exists('exp_year', $search_array)) {
            $this->status = 'error';
            $this->message = 'Card expiry month is missing';
            return false;
        }

        $this->status = 'success';
        return true;
    }

    function send_response(){
        $response = new stdClass();
        $response->http_status = $this->http_status;
        $response->status = $this->status;
        $response->message = $this->message;
        return $response;
        return array('http_status' => $this->http_status,'status' => $this->status,'message' => $this->message);
    }
   
}




