<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//include Rest Controller library
require APPPATH . '/libraries/MyRestController.php'; 

class Account extends MyRestController{
	function __construct(){
		parent::__construct();
        logrequest();
	}
    
	public function login_post(){
        $username=$this->post('username');
        $password=$this->post('password');
        $regid=$this->post('regid');
        $device_id=$this->post('device_id');
        $device_name=$this->post('device_name');
        $role='member';
        
        if(!empty($username) && !empty($password) && !empty($device_id) && !empty($device_name)){
            $data=array('username'=>$username,'password'=>$password,'role'=>$role);
            $result=$this->account->login($data);
            if($result['status']===true){
                $user=$result['user'];
                $token=md5($user['id'].'.'.time().'.'.$data['username']);
                $tokendata=array("user_id"=>$user['id'],"token"=>$token,"device_id"=>$device_id,
                                 "device_name"=>$device_name,"regid"=>$regid);
                $verify=$this->account->addtoken($tokendata,1);
                if($verify===true){
                    $response=array("token"=>$token);
                    $member=$this->member->getmemberdetails($user['id']);
                    $response=array("name"=>$user['name'],"mobile"=>$user['mobile'],"email"=>$user['email'],
                                    "token"=>$token);
                    $response['package_id']=$member['package_id'];
                    $result=$this->account->getuser(['id'=>$member['refid']]);
                    $sponsor=$result['user'];
                    $response['sponsor_id']=$sponsor['username'];
                    $response['sponsor_name']=$sponsor['name'];
                    $this->customresponse('login',['status'=>TRUE,'data'=>$response]);
                }
                else{
                    $this->customresponse('login',['status'=>FALSE,'error'=>$verify]);
                }
            }
            else{
                $error=$result['message'];
                $this->customresponse('error',[],$error);
            } 
		}		
		else{
            $parameters=array();
            if(empty($username)){
                $parameters[]='username';
            }
            if(empty($password)){
                $parameters[]='password';
            }
            if(empty($device_id)){
                $parameters[]='device_id';
            }
            if(empty($device_name)){
                $parameters[]='device_name';
            }
			$this->customresponse('missingparameters',$parameters);
		}
	}	

	public function forgotpassword_post(){
        $where['username']=$this->post('username');
        $regid=$this->post('regid');
        $device_id=$this->post('device_id');
        $device_name=$this->post('device_name');
        
        if(!empty($where['username']) && !empty($regid) && !empty($device_id) && !empty($device_name)){

            $result=$this->account->getuser($where);
            if($result['status']===true){
                $user=$result['user'];
                $token=md5($user['id'].'.'.time().'.'.$user['username']);
                $tokendata=array("user_id"=>$user['id'],"token"=>$token,"device_id"=>$device_id,
                                 "device_name"=>$device_name,"regid"=>$regid);
                $verify=$this->account->addtoken($tokendata);

                $result=$this->sendotp($where);
                
                if($result['status']===true){
                    $otp=$result['message'];
                    $verification_msg="$otp is your One Time Password to Reset password . This OTP is valid for 15 minutes.";
                    //sendemail($user['email'],"Forgot Password",$verification_msg);
                    
                    $headers  = "From: ".PROJECT_NAME." <5percenttradersclub@gmail.com>\n";
                    $headers .= "Cc: ".PROJECT_NAME." <mail@testsite.com>\n"; 
                    $headers .= "X-Sender: ".PROJECT_NAME." <5percenttradersclub@gmail.com>\n";
                    $headers .= 'X-Mailer: PHP/' . phpversion();
                    $headers .= "X-Priority: 1\n"; // Urgent message!
                    $headers .= "Return-Path: 5percenttradersclub@gmail.com\n"; // Return path for errors
                    $headers .= "MIME-Version: 1.0\r\n";
                    $headers .= "Content-Type: text/html; charset=iso-8859-1\n";
                    mail($user['email'],"Forgot Password",$verification_msg,$headers);
                    $this->response([
                        'status' => true,
                        'result' => $result['message'],"token"=>$token], REST_Controller::HTTP_OK);
                }
                else{
                    $error=$result['message'];
                    $this->response([
                        'status' => false,
                        'message' => $error], REST_Controller::HTTP_OK);
                }
            }
            else{
                $error=$result['message'];
                $this->response([
                    'status' => false,
                    'message' => $error], REST_Controller::HTTP_OK);
            }
        }
        else{
            $this->response([
				'status' => false,
				'message' => "Please provide all Details!"], REST_Controller::HTTP_OK);
        }
	}	

	public function resetpassword_post(){
		$token=$this->input->post('token');
		$password=$this->input->post('password');
        if(!empty($token) && !empty($password)){
            $user=$this->account->verify_token($token);
            if(!empty($user) && is_array($user) && $user['role']=='member'){
                $data=['password'=>$password];
                $result=$this->account->updatepassword($data,['id'=>$user['id']]);
                //print_r($result);
                if($result['status']===true){
                    $this->response([
                            'status' => true,
                            'message' => "Password Updated Successfully!"], REST_Controller::HTTP_OK);
                
                }
                else{
                    $this->response([
                        'status' => false,
                        'message' => $result['result']], REST_Controller::HTTP_OK);
                }
            }
            else{
                $this->response([
                    'status' => false,
                    'message' => "Unauthorized Access!"], REST_Controller::HTTP_OK);
            }
        }
        else{
            $this->response([
				'status' => false,
				'message' => "Please provide all details!"], REST_Controller::HTTP_OK);
        }
	}	
    
	public function getprofile_post(){
        $token=$this->post('token');
        
        if(!empty($token)){
            $user=$this->account->verify_token($token);
            if(!empty($user) && is_array($user) && ($user['role']=='vendor' || $user['role']=='sales')){
                $data=array();
                $data['username']=$user['username'];
                $data['name']=$user['name'];
                $data['mobile']=$user['mobile'];
                $data['email']=$user['email'];
                $data['photo']=file_url($user['photo']);
                $this->response([
                        'status' => true,
                        'profile'=>$data], REST_Controller::HTTP_OK);
            }	
            else{
                $this->response([
                    'status' => false,
                    'message' => "Unauthorized Access!"], REST_Controller::HTTP_OK);
            }
        }
        else{
            $this->response([
				'status' => false,
				'message' => "Please provide all Details!"], REST_Controller::HTTP_OK);
        }
	}	

	public function updateprofile_post(){
        $token=$this->post('token');
        $name=$this->post('name');
        $mobile=$this->post('mobile');
        $email=$this->post('email');
        
        if(!empty($token) && !empty($name) && !empty($mobile) && !empty($email)){
            $user=$this->account->verify_token($token);
            if(!empty($user) && is_array($user) && $user['role']=='vendor'){
                $data=array();
                $upload_path='./assets/images/profile/';
                $allowed_types='gif|jpg|jpeg|png|svg';
                if(isset($_FILES['photo'])){
                    $upload=upload_file('photo',$upload_path,$allowed_types,generate_slug($user['name']));
                    if($upload['status']===true){
                        create_image_thumb('.'.$upload['path'],'',FALSE,array("width"=>300,"height"=>300));
                        $data['photo']=$upload['path'];
                    }
                }
                $data['mobile']=$mobile;
                $data['name']=$name;
                $data['username']=$data['email']=$email;
                $result=$this->account->updateuser($data,array("id"=>$user['id']));
                if($result['status']===true){
                    $vendordata=array("name"=>$name,"mobile"=>$mobile,"email"=>$email);
                    if(!empty($data['photo'])){
                        $vendordata['photo']=$data['photo'];
                    }
                    $vendor=$this->vendor->getvendors(["user_id"=>$user['id']],"single");
                    $vendordata['id']=$vendor['id'];
                    $this->vendor->updatevendor($vendordata);
                    if(isset($data['photo'])){
                        $data['photo']=file_url($data['photo']);
                    }
                    $this->response([
                        'status' => true,
                        'message' => $result['message'],'profile'=>$data], REST_Controller::HTTP_OK);
                }	
                else{
                    $this->response([
                        'status' => false,
                        'message' => $result['message']], REST_Controller::HTTP_OK);
                }
            }	
            else{
                $this->response([
                    'status' => false,
                    'message' => "Unauthorized Access!"], REST_Controller::HTTP_OK);
            }
        }
        else{
            $this->response([
				'status' => false,
				'message' => "Please provide all Details!"], REST_Controller::HTTP_OK);
        }
	}	

	public function getaddresses_post(){
        $token=$this->post('token');
        if(!empty($token)){
            $user=$this->account->verify_token($token);
            if(!empty($user) && is_array($user) && $user['role']=='vendor'){
                $addresses=$this->vendor->getaddresses(array("user_id"=>$user['id']));
                if(!empty($addresses)){
                    $this->response([
                        'status' => true,
                        'addresses' => $addresses], REST_Controller::HTTP_OK);
                }		
                else{
                    $this->response([
                        'status' => false,
                        'message' => "No Address found!"], REST_Controller::HTTP_OK);
                }
            }		
            else{
                $this->response([
                    'status' => false,
                    'message' => "Unauthorized Accekjhgfss!"], REST_Controller::HTTP_OK);
            }
		}		
		else{
			$this->response([
				'status' => false,
				'message' => "Please provide all Details!"], REST_Controller::HTTP_OK);
		}
	}	
    
	public function saveaddress_post(){
        $token=$this->post('token');
        $type="Delivery";
        $name=$this->post('name');
        $mobile=$this->post('mobile');
        $email=$this->post('email');
        $address=$this->post('address');
        $state_id=$this->post('state_id');
        $district_id=$this->post('district_id');
        $pincode=$this->post('pincode');
        
        if(!empty($token) && !empty($name) && !empty($mobile) && !empty($address) && !empty($state_id) && 
           !empty($district_id) && !empty($pincode)){
            $user=$this->account->verify_token($token);
            if(!empty($user) && is_array($user) && $user['role']=='vendor'){
                $data=array("user_id"=>$user['id'],"name"=>$name,"mobile"=>$mobile,"email"=>$email,"type"=>$type,"address"=>$address,
                            "parent_id"=>$state_id,"area_id"=>$district_id,"pincode"=>$pincode);
                $result=$this->vendor->saveaddress($data);
                if($result['status']===true){
                    $this->response([
                        'status' => true,
                        'message' => $result['message']], REST_Controller::HTTP_OK);
                }	
                else{
                    $this->response([
                        'status' => false,
                        'message' => $result['message']], REST_Controller::HTTP_OK);
                }
                
            }	
            else{
                $this->response([
                    'status' => false,
                    'message' => "Unauthorized Access!"], REST_Controller::HTTP_OK);
            }
        }
        else{
            $this->response([
				'status' => false,
				'message' => "Please provide all Details!"], REST_Controller::HTTP_OK);
        }
	}	

	public function checkaddress_post(){
        $token=$this->post('token');
        $latitude=$this->post('latitude');
        $longitude=$this->post('longitude');
        
        if(!empty($token) && !empty($latitude) && !empty($longitude)){
            $user=$this->account->verify_token($token);
            if(!empty($user) && is_array($user) && $user['role']=='customer'){
                $result=$this->cart->checkproducts($user['id'],$latitude,$longitude);

                if($result['status']===true){
                    $this->response([
                        'status' => true,
                        'message' => "Delivery Available!"], REST_Controller::HTTP_OK);
                }	
                else{
                    $this->response([
                        'status' => false,
                        'message' => $result['message']], REST_Controller::HTTP_OK);
                }
            }	
            else{
                $this->response([
                    'status' => false,
                    'message' => "Unauthorized Access!"], REST_Controller::HTTP_OK);
            }
        }
        else{
            $this->response([
				'status' => false,
				'message' => "Please provide all Details!"], REST_Controller::HTTP_OK);
        }
	}	

	public function verifyotp_post(){
        $token=$this->post('token');
        $otp=$this->post('otp');
        if(!empty($token) && !empty($otp)){
            $verify=$this->account->verify_token($token);
            if($verify!==false){
                $where['username']=$verify['username'];
                $result=$this->account->verifyotp($otp,$where);
                if($result['status']===true){
                    $result=$result['result'];
                    $this->response([
                        'status' => true,
                        'result' => $result], REST_Controller::HTTP_OK);
                }
                else{
                    $error=$result['message'];
                    $this->response([
                        'status' => false,
                        'message' => $error], REST_Controller::HTTP_OK);
                }
            }
            else{
                $this->response([
                    'status' => false,
                    'message' => "Token Invalid"], REST_Controller::HTTP_OK);
            }
        }
        else{
            $this->response([
                'status' => false,
                'message' => "Please provide all Details!"], REST_Controller::HTTP_OK);
        }
	}	

	public function sendotptomobile_post(){
        $where['username']=$this->post('mobile');
        $regid=$this->post('regid');
        $device_id=$this->post('device_id');
        $device_name=$this->post('device_name');
        
        if(!empty($where['username']) && !empty($regid) && !empty($device_id) && !empty($device_name)){

            $result=$this->account->getuser($where);
            if($result['status']===true){
                $user=$result['user'];
                $token=md5($user['id'].'.'.time().'.'.$user['username']);
                $tokendata=array("user_id"=>$user['id'],"token"=>$token,"device_id"=>$device_id,
                                 "device_name"=>$device_name,"regid"=>$regid);
                $verify=$this->account->addtoken($tokendata);

                $result=$this->sendotp($where);
                if($result['status']===true){
                    $this->response([
                        'status' => true,
                        'result' => $result['message'],"token"=>$token], REST_Controller::HTTP_OK);
                }
                else{
                    $error=$result['message'];
                    $this->response([
                        'status' => false,
                        'message' => $error], REST_Controller::HTTP_OK);
                }
            }
            else{
                $error=$result['message'];
                $this->response([
                    'status' => false,
                    'message' => $error], REST_Controller::HTTP_OK);
            }
        }
        else{
            $this->response([
				'status' => false,
				'message' => "Please provide all Details!"], REST_Controller::HTTP_OK);
        }
	}	

	public function myorders_post(){
        $token=$this->post('token');
        if(!empty($token)){
            $user=$this->account->verify_token($token);
            if(!empty($user) && is_array($user) && $user['role']=='customer'){
                $orders=$this->order->getorders(array("user_id"=>$user['id']),"all","id desc");
                if(!empty($orders)){
                    foreach($orders as $key=>$order){
                        $driver=$firebase_user_id="";
                        if($order['status']!=0 && $order['status']!=2 && $order['status']!=4){
                            $where=array("order_id"=>$order['id']);
                            $deliveryboy_id=$this->db->get_where("assigned_orders",$where)->unbuffered_row()->deliveryboy_id;
                            $deliveryboy=$this->deliveryboy->getdeliveryboys(array("t1.id"=>$deliveryboy_id),"Single");
                            $driver=$deliveryboy['name'];
                            $firebase_user_id=$deliveryboy['firebase_user_id'];
                        }
                        $orders[$key]['driver']=$driver;
                        $orders[$key]['firebase_user_id']=$firebase_user_id;
                    }
                    $this->response([
                        'status' => true,
                        'orders' => $orders], REST_Controller::HTTP_OK);
                }		
                else{
                    $this->response([
                        'status' => false,
                        'message' => "No Orders!"], REST_Controller::HTTP_OK);
                }
            }		
            else{
                $this->response([
                    'status' => false,
                    'message' => "Unauthorized Access!"], REST_Controller::HTTP_OK);
            }
		}		
		else{
			$this->response([
				'status' => false,
				'message' => "Unauthorized Access!"], REST_Controller::HTTP_OK);
		}
	}	
    
    public function createwalletreceipt_post(){
        $token=$this->post('token');
        $amount=$this->post('amount');
        if(!empty($token) && !empty($amount)){
            $user=$this->account->verify_token($token);
            if(!empty($user) && is_array($user) && $user['role']=='customer'){
                $api = new Api(RAZOR_KEY_ID, RAZOR_SECRET);
                $receipt = generatereceipt('wallet');
                $api->order->create([
                  'receipt' => $receipt,
                  'amount'  => $amount*100,
                  'currency' => 'INR'
                ]);
                $this->response([
                            'status' => true,
                            'receipt' => $receipt], REST_Controller::HTTP_OK);
            }		
            else{
                $this->response([
                    'status' => false,
                    'message' => "Unauthorized Access!"], REST_Controller::HTTP_OK);
            }
		}		
		else{
			$this->response([
				'status' => false,
				'message' => "Please provide all details!"], REST_Controller::HTTP_OK);
		}
    }
    
    public function addmoney_post(){
        $token=$this->post('token');
        $receipt=$this->post('receipt');
        $amount=$this->post('amount');
        $response=$this->post('response');
        if(!empty($token) && !empty($receipt) && !empty($amount) && !empty($response)){
            $user=$this->account->verify_token($token);
            if(!empty($user) && is_array($user) && $user['role']=='customer'){
                $data['user_id']=$user['id'];
                $data['details']=$response;
                $data['receipt']=$receipt;
                $this->order->updatepaystatus($data);
                $walletdata=array("user_id"=>$user['id'],"amount"=>$amount,"status"=>1,"remarks"=>"Money Added!",
                                  "transaction_id"=>$receipt,"added_on"=>date('Y-m-d H:i:s'));
                $this->account->addmoney($walletdata);
                $this->response([
                    'status' => true,
                    'message' => "Money Added Successfully!"], REST_Controller::HTTP_OK);
            }		
            else{
                $this->response([
                    'status' => false,
                    'message' => "Unauthorized Access!"], REST_Controller::HTTP_OK);
            }
		}		
		else{
			$this->response([
				'status' => false,
				'message' => "Please provide all details!"], REST_Controller::HTTP_OK);
		}
    }
    
	public function getwallet_post(){
        $token=$this->post('token');
        if(!empty($token)){
            $user=$this->account->verify_token($token);
            if(!empty($user) && is_array($user) && $user['role']=='customer'){
                $wallet=walletamount($user['id']);
                $wallet=empty($wallet)?0:$wallet;
                $this->response([
                        'status' => true,
                        'wallet' => $wallet], REST_Controller::HTTP_OK);
            }	
            else{
                $this->response([
                    'status' => false,
                    'message' => "Unauthorized Access!"], REST_Controller::HTTP_OK);
            }
		}		
        else{
            $this->response([
                'status' => false,
                'message' => "Unauthorized Access!"], REST_Controller::HTTP_OK);
        }
	}	
    
    public function sendotp($where){
        $array=$this->account->createotp($where);
        if($array['status']===true){
            $result=$array['result'];
            $mobile=$result['mobile'];
            $name=$result['name'];
            $otp=$result['otp'];
            $type=$result['type'];
            //loginotp($mobile,$otp);
            return array("status"=>true,"message"=>$otp);
        }
        else{
            return $array;
        }
    }
    
    public function resendotp(){
        $mobile=$this->session->mobile;
        $where=array("username"=>$mobile);
        $result=$this->sendotp($where);
    }
}
