<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 class Dmt_request {
    var $ci;
	var $token;
	const DMT_URL = "http://13.127.227.22/freeunlimited/v3/demo";
	//const DMT_URL = "http://13.127.227.22/freeunlimited/v3/";
	const API_KEY = "839493304052892";
	const USER_ID = "";
	
	var  $hashedheaderstring="";
	
    function __construct() {
       $this->ci =& get_instance();
		$headerstring = self::USER_ID."|".self::API_KEY;
		$this->hashedheaderstring = hash("sha512", $headerstring);
		
    }
	
	public function sendrequest($data){
		$url = self::DMT_URL;
		$paramList = array();
		$paramList["apikey"] = self::API_KEY;
		$paramList["mobileno"] = $data['mobile'];
		$paramList["beneficiary_account_no"] = $data['account_no'];
		$paramList["beneficiary_ifsc"] = $data['ifsc'];
		$paramList["amount"] = floatval($data['amount']);
		$paramList["orderid"] = $data['order_id'];
		$paramList["purpose"] = "OTHERS";
		$paramList["remarks"] = "Member Payment";
		$paramList["callbackurl"] = base_url();
		$payload = json_encode($paramList, true);

		
		$header= array('Content-Type:application/json','Authorization:'.$this->hashedheaderstring);
		$ch = curl_init("$url/transfer.php");
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		$file_contents = curl_exec ($ch); // execute
		$err = curl_error($ch);
		curl_close($ch);
		$result=json_decode($file_contents,true);
		return $result;
	}
	
    public function checkbalance(){
        $paramList = array();
        $paramList["apikey"] = self::API_KEY;
        $payload = json_encode($paramList, true);
        //set url
        $url = self::DMT_URL;
        //run url
        
		$header= array('Content-Type:application/json','Authorization:'.$this->hashedheaderstring);
        $ch = curl_init("$url/balance.php");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		$file_contents = curl_exec ($ch); // execute
		$err = curl_error($ch);
		curl_close($ch);
		$result=json_decode($file_contents,true);
		//$balance=isset($result['balance'])?$result['balance']:0;
        $balance=1000;
        return $balance;
    }
	
}
