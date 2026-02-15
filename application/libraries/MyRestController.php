<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//include Rest Controller library
use chriskacerguis\RestServer\RestController;

class MyRestController extends RestController{
	protected $CI;
    private $http_code;
    
	function __construct(){
		parent::__construct();
        $this->CI =& get_instance();
        if(!$this->CI->load->is_loaded('CI_Debugger/debugger')){
            $this->CI->load->library('CI_Debugger/debugger');
            $this->CI->debugger->updateDefaultStatus();
        } 
        
	}

    public function customresponse($type="default",$data = NULL,$message="") {
        if($type=='default'){
            $data=$this->defaultStructure($data,$message);
        }
        elseif($type=='missingparameters'){
            $data=$this->missingParameters($data);
        }
        elseif($type=='login'){
            $data=$this->loginResponse($data);
        }
        elseif($type=='error'){
            $data=$this->errorResponse($data,$message);
        }
        elseif($type=='notfound'){
            $data=$this->notfoundResponse($data);
        }
        elseif($type=='list'){
            $data=$this->listResponse($data);
        }
        elseif($type=='creation'){
            $data=$this->creationResponse($data,$message);
        }
        elseif($type=='details'){
            $data=$this->detailsResponse($data);
        }
        if(!isset($data['data'])){
            $data['data']=NULL;
        }
        if(!isset($data['error'])){
            $data['error']=NULL;
        }
        //print_pre($data);
        parent::response($data, $this->http_code);
    }

    public function getTimestamp(){
        $datetime = new DateTime('now', new DateTimeZone('Asia/Kolkata'));  // Get current time in UTC
        $timestamp = $datetime->format('Y-m-d\TH:i:s\Z');  // Format as ISO 8601
        return $timestamp;
    }

    public function defaultStructure($data=array(),$message=""){
        $status='success';
        $status_code=200;
        $timestamp=$this->getTimestamp();
        $this->http_code=RestController::HTTP_OK;
        $response=array('status'=>$status,'status_code'=>$status_code,'timestamp'=>$timestamp,'message'=>$message,'data'=>$data);
        return $response;
    }

    public function errorResponse($data=array(),$message="",$status_code=400){
        $status='error';
        $timestamp=$this->getTimestamp();
        $this->http_code=RestController::HTTP_BAD_REQUEST;
        $error=array('code'=>$message);
        if(isset($data['error']) && $data['error']=='User not Found'){
            $this->http_code=RestController::HTTP_FORBIDDEN;
            $status_code=403;
            $message="You do not have permission to access this resource.";
            $error['code']="FORBIDDEN";
            $error['details']="The user exists but does not have the required access rights to perform this action.";
        }
        if(isset($data['error']) && $data['error']=='Detail not Found'){
            $this->http_code=RestController::HTTP_NOT_FOUND;
            $status_code=404;
            $error['code']="RESOURCE_NOT_FOUND";
            $error['details']="The requested data with the provided ID does not exist.";
        }
        if($message=='Token Invalid'){
            $this->http_code=RestController::HTTP_METHOD_NOT_ALLOWED ;
            $status_code=405;
            $error['code']="TOKEN_EXPIRED";
            $error['details']="The token provided ID has Expired.";
        }
        
        $response=array('status'=>$status,'status_code'=>$status_code,'timestamp'=>$timestamp,'message'=>$message,'error'=>$error,
                        'data'=>$data);
        return $response;
    }

    public function notfoundResponse($resource,$status_code=404){
        $status='error';
        $timestamp=$this->getTimestamp();
        $this->http_code=RestController::HTTP_NOT_FOUND;
        $error=array('code'=>'');
        $status_code=404;
        $error['code']="RESOURCE_NOT_FOUND";
        $message=$resource.' not found!';
        $error['details']="The requested data does not exist.";
        $response=array('status'=>$status,'status_code'=>$status_code,'timestamp'=>$timestamp,'message'=>$message,'error'=>$error);
        return $response;
    }

    public function creationResponse($data=array(),$message=''){
        $status='success';
        $timestamp=$this->getTimestamp();
        $this->http_code=RestController::HTTP_CREATED;
        $status_code=201;
        $response=array('status'=>$status,'status_code'=>$status_code,'timestamp'=>$timestamp,'message'=>$message,'data'=>$data);
        return $response;
    }

    public function loginResponse($data=array()){
        if($data['status']===true){
            $response= $this->defaultStructure($data['data'],'Login Successful');
        }
        else{
            $status_code=400;
            $message='Login Error';
            $error=array('code'=>$message);
            $status_code=401;
            $timestamp=$this->getTimestamp();
            $this->http_code=RestController::HTTP_UNAUTHORIZED;
            if($data['error']=='Wrong Username or Password!'){
                $message="Invalid username or password";
                $error['code']="INVALID_CREDENTIALS";
            }
            if($data['error']=='User Already Logged in from Different Device'){
                $status_code=403;
                $this->http_code=RestController::HTTP_FORBIDDEN;
                $message="You are already logged in from another device. Please log out from the other device before logging in again.";
                $error['code']="USER_LOGGED_IN_FROM_DIFFERENT_DEVICE";
                $error['details']="Another device is currently using your account. To proceed, log out from that device.";
            }
            $response=array('status'=>'error','status_code'=>$status_code,'timestamp'=>$timestamp,'message'=>$message,'error'=>$error);
        }
        return $response;
    }

    public function missingParameters($parameters){
        $status="error";
        $status_code=400;
        $message="Missing required parameter: '".implode("',",$parameters)."'";
        $details=array();
        foreach($parameters as $parameter){
            $details[$parameter]="The '$parameter' parameter is required and was not provided.";
        }
        $error=array('code'=>'MISSING_PARAMETER','details'=>$details);
        $timestamp=$this->getTimestamp();
        $this->http_code=RestController::HTTP_BAD_REQUEST;
        $response=array('status'=>$status,'status_code'=>$status_code,'timestamp'=>$timestamp,'message'=>$message,'error'=>$error);
        return $response;
    }

    public function listResponse($data){
        $message=empty($data)?'No data available.':'Data retrived successfully';
        $response= $this->defaultStructure($data,$message);
        return $response;
    }

    public function detailsResponse($data){
        if(!empty($data['data'])){
            $message='Data retrived successfully';
            $response= $this->defaultStructure($data['data'],$message);
        }
        else{
            $message='Resource not found';
            $data['error']='Detail not Found';
            $response= $this->errorResponse($data,$message);
        }
        return $response;
    }
}
