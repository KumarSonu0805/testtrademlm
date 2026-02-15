<?php 
	if(!defined('BASEPATH')) exit('No direct script access allowed');
	if(isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST']=='localhost'){
		define("API_URL","https://api.razorpay.com/v1/");
	}
	else{
		define("API_URL","https://api.razorpay.com/v1/");
	}

    if(!function_exists('savecontact')){
        function savecontact($data){
            $url=API_URL.'contacts';
            $curl = curl_init();
            
            $data=json_encode($data);
            
            $authorization=base64_encode(KEY_ID.":".KEY_SECRET);
            
            curl_setopt_array($curl, array(
              CURLOPT_URL => $url,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>$data,
              CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Basic '.$authorization
              ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            $result=json_decode($response,true);
            return $result;
        }
    }

    if(!function_exists('updatecontact')){
        function updatecontact($contact_id,$data){
            $url=API_URL.'contacts/'.$contact_id;
            $curl = curl_init();
            
            $data=json_encode($data);
            
            $authorization=base64_encode(KEY_ID.":".KEY_SECRET);
            
            curl_setopt_array($curl, array(
              CURLOPT_URL => $url,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'PATCH',
              CURLOPT_POSTFIELDS =>$data,
              CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Basic '.$authorization
              ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            $result=json_decode($response,true);
            return $result;
        }
    }

    if(!function_exists('getcontacts')){
        function getcontacts(){
            $url=API_URL.'contacts';
            $curl = curl_init();
            
            $authorization=base64_encode(KEY_ID.":".KEY_SECRET);
            
            curl_setopt_array($curl, array(
              CURLOPT_URL => $url,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'GET',
              CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Basic '.$authorization
              ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            $result=json_decode($response,true);
            return $result;
        }
    }

    if(!function_exists('getcontactbyid')){
        function getcontactbyid($contact_id){
            $url=API_URL.'contacts/'.$contact_id;
            $curl = curl_init();
            
            $authorization=base64_encode(KEY_ID.":".KEY_SECRET);
            
            curl_setopt_array($curl, array(
              CURLOPT_URL => $url,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'GET',
              CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Basic '.$authorization
              ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            $result=json_decode($response,true);
            return $result;
        }
    }

    if(!function_exists('createfundaccount')){
        function createfundaccount($data){
            $url=API_URL.'fund_accounts/';
            $curl = curl_init();
            
            $data=json_encode($data);
            
            $authorization=base64_encode(KEY_ID.":".KEY_SECRET);
            
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>$data,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Basic '.$authorization
              ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            $result=json_decode($response,true);
            return $result;
        }
    }

    if(!function_exists('createfundaccountvpa')){
        function createfundaccountvpa($data){
            $url=API_URL.'fund_accounts/';
            $curl = curl_init();
            
            $data=json_encode($data);
            
            $authorization=base64_encode(KEY_ID.":".KEY_SECRET);
            
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.razorpay.com/v1/fund_accounts',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>$data,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Basic '.$authorization
              ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            $result=json_decode($response,true);
            return $result;
        }
    }

    if(!function_exists('getfundaccounts')){
        function getfundaccounts(){
            $url=API_URL.'fund_accounts/';
            $curl = curl_init();
            
            $authorization=base64_encode(KEY_ID.":".KEY_SECRET);
            
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Basic '.$authorization
              ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            $result=json_decode($response,true);
            return $result;
        }
    }

    if(!function_exists('getfundaccountbyid')){
        function getfundaccountbyid($fundaccount_id){
            $url=API_URL.'fund_accounts/'.$fundaccount_id;
            $curl = curl_init();
            
            $authorization=base64_encode(KEY_ID.":".KEY_SECRET);
            
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Basic '.$authorization
              ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            $result=json_decode($response,true);
            return $result;
        }
    }

    if(!function_exists('createpayout')){
        function createpayout($data){
            $url=API_URL.'payouts/';
            $curl = curl_init();
            
            $data=json_encode($data);
            
            $authorization=base64_encode(KEY_ID.":".KEY_SECRET);
            
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>$data,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Basic '.$authorization
              ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            $result=json_decode($response,true);
            return $result;
        }
    }

    if(!function_exists('createpayoutvpa')){
        function createpayoutvpa($data){
            $url=API_URL.'payouts/';
            $curl = curl_init();
            
            $data=json_encode($data);
            
            $authorization=base64_encode(KEY_ID.":".KEY_SECRET);
            
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>$data,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Basic '.$authorization
              ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            $result=json_decode($response,true);
            return $result;
        }
    }

    if(!function_exists('getpayouts')){
        function getpayouts(){
            $url=API_URL.'payouts/?account_number='.ACCOUNT_NO;
            $curl = curl_init();
            
            $authorization=base64_encode(KEY_ID.":".KEY_SECRET);
            
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Basic '.$authorization
              ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            $result=json_decode($response,true);
            return $result;
        }
    }

    if(!function_exists('getpayoutbyid')){
        function getpayoutbyid($payout_id){
            $url=API_URL.'payouts/'.$payout_id;
            $curl = curl_init();
            
            $authorization=base64_encode(KEY_ID.":".KEY_SECRET);
            
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Basic '.$authorization
              ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            $result=json_decode($response,true);
            return $result;
        }
    }

    if(!function_exists('cancelqueued')){
        function cancelqueued($payout_id){
            $url=API_URL.'payouts/'.$payout_id.'/cancel';
            $curl = curl_init();
            
            $authorization=base64_encode(KEY_ID.":".KEY_SECRET);
            
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Basic '.$authorization
              ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            $result=json_decode($response,true);
            return $result;
        }
    }
