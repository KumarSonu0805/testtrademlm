<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//include Rest Controller library
require APPPATH . '/libraries/MyRestController.php'; 

class Members extends MyRestController{
	function __construct(){
		parent::__construct();
        logrequest();
	}

	public function generateusername_post(){
        $username=$this->member->generateusername();
        $this->customresponse('default',['username'=>$username],'Username Generated Successfully');
	}	
    
    public function gethomedata_post(){
        $token=$this->post('token');
        if(!empty($token)){
            $user=$this->account->verify_token($token);
            if(!empty($user) && is_array($user)){
                $data=array();
                
                $this->wallet->addcommission($user['id']);
                $memberdetails=$this->member->getalldetails($user['id']);
                $member=$memberdetails['member'];
                $member['username']=$user['username'];
                $member['sponsor_id']=$member['susername'];
                $member['sponsor_name']=$member['sname'];
                $member['joining_date']=$member['date'];
                $member['activation_date']=$member['activation_date']?:'';
                $selectedKeys = ["username","name", "mobile","email","sponsor_id","sponsor_name","status",
                                 "joining_date","activation_date"]; // Keys to keep
                $member = array_intersect_key($member, array_flip($selectedKeys));
                $data['member']=$member;
                $data['homedata']=$this->common->homedata($user['id']);
                $this->customresponse('default',$data,"Home Data Fetched successfully");
            }
            else{
                $this->customresponse('error',[],"Token Invalid");
            }
        }	
        else{
            $parameters=array();
            if(empty($token)){
                $parameters[]='token';
            }
            if(empty($member_id)){
                $parameters[]='member_id';
            }
            $this->customresponse('missingparameters',$parameters);
        }
    }
    
    public function checkactivation_post(){
        $token=$this->post('token');
        $role='member';
        if(!empty($token)){
            $user=$this->account->verify_token($token);
            if(!empty($user) && is_array($user) && $user['role']==$role){
                $check=$this->db->get_where('epin_requests',['regid'=>$user['id'],'status!='=>2])->num_rows();
                $this->db->order_by('id desc');
                $getrequest=$this->db->get_where('epin_requests',['regid'=>$user['id']]);
                $status=0;
                $message="Member In-Active";
                $reason="";
                if($getrequest->num_rows()>0){
                    $request=$getrequest->unbuffered_row('array');
                    if($request['status']==1){
                        $status=1;
                        $message='Member Active';
                    }
                    elseif($request['status']==0){
                        $status=3;
                        $message='Member Activation Request Pending';
                    }
                    elseif($request['status']==2){
                        $status=2;
                        $message='Member Activation Request Rejected';
                        $reason=$request['reason'];
                    }
                }
                $data=array('status'=>$status,'reason'=>$reason);
                $this->customresponse('default',$data,$message);
            }
            else{
                $this->customresponse('error',[],"Token Invalid");
            }
        }	
        else{
            $parameters=array();
            if(empty($token)){
                $parameters[]='token';
            }
            $this->customresponse('missingparameters',$parameters);
        }
	}
    
	public function checkrenewal_post(){
        $token=$this->post('token');
        if(!empty($token)){
            $user=$this->account->verify_token($token);
            if(!empty($user) && is_array($user) && $user['role']=='member'){
                $this->wallet->addcommission($user['id']);
                $this->wallet->checkrenewal($user['id']);
                $result=$this->wallet->getrenewal($user['id']);
                if($result['status']===true){
                    $this->response([
                        'status' => true,
                        'message' => "Active"], REST_Controller::HTTP_OK);
                }
                else{
                    $result['status']=true;
                    $this->response($result, REST_Controller::HTTP_OK);
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

	public function checksponsor_post(){
		$username=$this->input->post('sponsor_id');
		$status='all';
        if(!empty($username)){
            $member=$this->member->getmemberid($username,$status);
            if($member['regid']!=0){
                $this->customresponse('default',['sponsor'=>$member],'Sponsor Fetched Successfully');
            }
            else{
               $this->customresponse('error',[],"Invalid Sponsor ID");
            }
        }
		else{
            $parameters=array();
            if(empty($username)){
                $parameters[]='sponsor_id';
            }
			$this->customresponse('missingparameters',$parameters);
		}
	}	

    public function getmemberlist_post(){
        $token=$this->post('token');
        if(!empty($token) ){
            $user=$this->account->verify_token($token);
            if(!empty($user) && is_array($user) && $user['role']=='member'){
                $members=$this->member->getallmembers($user['id']);
                $response=array();
                if(!empty($members)){
                    foreach($members as $key=>$member){
                        $member['member_id']=$member['username'];
                        //id name mobile registered datetime call whatsapp
                        $selectedKeys = ["member_id","name", "mobile","status","photo"]; // Keys to keep
                        $single = array_intersect_key($member, array_flip($selectedKeys));
                        $members[$key]=$member;
                        $response[]=$single;
                    }
                }
                $message="Member List Fetched Successfully!";
                $this->customresponse('default',$response,$message);
            }
            else{
                $this->customresponse('error',[],"Token Invalid");
            }
        }	
        else{
            $parameters=array();
            if(empty($token)){
                $parameters[]='token';
            }
            $this->customresponse('missingparameters',$parameters);
        }
    }
    
    public function getdirectmembers_post(){
        $token=$this->post('token');
        if(!empty($token) ){
            $user=$this->account->verify_token($token);
            if(!empty($user) && is_array($user) && $user['role']=='member'){
                $members=$this->member->getdirectmembers($user['id']);
                $response=array();
                if(!empty($members)){
                    foreach($members as $key=>$member){
                        $member['member_id']=$member['username'];
                        //id name mobile registered datetime call whatsapp
                        $selectedKeys = ["member_id","name", "mobile","status","photo"]; // Keys to keep
                        $single = array_intersect_key($member, array_flip($selectedKeys));
                        $members[$key]=$member;
                        $response[]=$single;
                    }
                }
                $message="Direct Members Fetched Successfully!";
                $this->customresponse('default',$response,$message);
            }
            else{
                $this->customresponse('error',[],"Token Invalid");
            }
        }	
        else{
            $parameters=array();
            if(empty($token)){
                $parameters[]='token';
            }
            $this->customresponse('missingparameters',$parameters);
        }
    }
    
	public function getnewmembers_post(){
        $members=$this->member->getnewmembers();
        if(!empty($members)){
            $this->response([
                'status' => true,
                'members' => $members], REST_Controller::HTTP_OK);
        }
        else{
            $this->response([
                'status' => false,
                'message' => "No New Members!"], REST_Controller::HTTP_OK);
        }
	}	

	public function getteammembers_post(){
        $token=$this->post('token');
        if(!empty($token)){
            $user=$this->account->verify_token($token);
            if(!empty($user) && is_array($user) && $user['role']=='member'){
                $members=$this->member->getteammembers($user['id']);
                if(!empty($members)){
                    $this->response([
                        'status' => true,
                        'members' => $members], REST_Controller::HTTP_OK);
                }
                else{
                    $this->response([
                        'status' => false,
                        'message' => "No Team Members!"], REST_Controller::HTTP_OK);
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

	public function register_post(){
		$username=$this->input->post('username');
		$ref=$this->input->post('sponsor_id');
		$name=$this->input->post('name');
		$mobile=$this->input->post('mobile');
		$email=$this->input->post('email');
		$password=$this->input->post('password');
        
		$device_id=$this->input->post('device_id');
		$device_name=$this->input->post('device_name');
		$regid=$this->input->post('regid');
        
        if(!empty($username) && !empty($ref) && !empty($name) && !empty($mobile) && !empty($password) 
           && !empty($device_id) && !empty($device_name)){
            $status='all';
            $sponsor=$this->member->getmemberid($ref,$status);
            if($sponsor['regid']>0){
                $userdata=$memberdata=$accountdata=$epindata=array();
				$userdata['username']=$username;
				$userdata['password']=$password;
				$userdata['mobile']=$mobile;
				$userdata['name']=$name;
				$userdata['email']=$email;
				$userdata['role']="member";
				$userdata['status']="1";
				
				$memberdata['name']=$name;
				$memberdata['mobile']=$mobile;
				$memberdata['email']=$email;
				$memberdata['refid']=$sponsor['regid'];
				$memberdata['date']=date('Y-m-d');
				$memberdata['time']=date('H:i:s');
                $memberdata['status']=0;
                
                
				$data=array("userdata"=>$userdata,"memberdata"=>$memberdata,"accountdata"=>$accountdata);
				$result=$this->member->addmember($data);
				if($result['status']===true){
                    if(strpos($memberdata['name']," ")){
                        $name=substr($memberdata['name'],0,strpos($memberdata['name']," "));
                    }
                    else{
                        $name=$memberdata['name'];
                    }
                    
                    $token=md5($result['regid'].'.'.time().'.'.$result['username']);
                    $tokendata=array("user_id"=>$result['regid'],"token"=>$token,"device_id"=>$device_id,
                                     "device_name"=>$device_name,"regid"=>$regid);
                    $verify=$this->account->addtoken($tokendata);
                    
                    $response=array("username"=>$result['username'],"name"=>$name,"mobile"=>$mobile,"email"=>$email,
                                    "token"=>$token,'password'=>$password);
                    
                    $this->customresponse('default',$response,"Registered Successfully!");
				}
				else{
                    $this->customresponse('error',[],$result['message']);
				}
            }
            else{
                $this->customresponse('error',[], "Invalid Sponsor Id");
            }
        }
		else{
            $parameters=array();
            if(empty($username)){
                $parameters[]='username';
            }
            if(empty($sponsor_id)){
                $parameters[]='sponsor_id';
            }
            if(empty($name)){
                $parameters[]='name';
            }
            if(empty($mobile)){
                $parameters[]='mobile';
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

    public function savepackagepayment_post(){
        $token=$this->post('token');
        $response=$this->post('response');
        if(!empty($token) && !empty($response)){
            $user=$this->account->verify_token($token);
            if(!empty($user) && is_array($user) && $user['role']=='member'){
                $member=$this->member->getmemberdetails($user['id']);
                if($member['status']==0){
                    $data=array("regid"=>$user['id'],"details"=>json_encode($response));
                    $result=$this->member->updatepayment($data);
                    if($result['status']===true){
                        $data=array("activation_date"=>date('Y-m-d'),"activation_time"=>date('H:i:s'),"status"=>1);
                        $this->member->updatepersonaldetails($data,['regid'=>$user['id']]);
                        $this->response([
                            'status' => true,
                            'message' => "Activated Successfully!"], REST_Controller::HTTP_OK);
                    }
                    else{
                        $this->response([
                            'status' => false,
                            'message' => "Please Try Again!"], REST_Controller::HTTP_OK);
                    }
                }
                else{
                    $this->response([
                            'status' => false,
                            'message' => "Member Already Activated!"], REST_Controller::HTTP_OK);
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
    
    public function availableads_post(){
        $token=$this->post('token');
        if(!empty($token)){
            $user=$this->account->verify_token($token);
            if(!empty($user) && is_array($user) && $user['role']=='member'){
                $regid=$user['id'];
                $available=$this->member->availableads($regid);
                $this->response([
                            'status' => true,
                            'available' => $available], REST_Controller::HTTP_OK);
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
    
    public function savead_post(){
        $token=$this->post('token');
        $details=$this->post('details');
        if(!empty($token)){
            $user=$this->account->verify_token($token);
            if(!empty($user) && is_array($user) && $user['role']=='member'){
                $regid=$user['id'];
                $data=array("date"=>date('Y-m-d'),"regid"=>$regid,"details"=>$details,"added_on"=>date('Y-m-d H:i:s'));
                $result=$this->member->savead($data);
                if($result['status']===true){
                    $this->response([
                            'status' => true,
                            'message' => "Ad Viewed!"], REST_Controller::HTTP_OK);
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
				'message' => "Please provide all details!"], REST_Controller::HTTP_OK);
		}
    }
    
    public function availableyoutubeads_post(){
        $token=$this->post('token');
        if(!empty($token)){
            $user=$this->account->verify_token($token);
            if(!empty($user) && is_array($user) && $user['role']=='member'){
                $regid=$user['id'];
                $available=$this->member->availableyoutubeads($regid);
                $this->response([
                            'status' => true,
                            'available' => $available], REST_Controller::HTTP_OK);
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
    
    public function saveyoutubead_post(){
        $token=$this->post('token');
        $youtube_id=$this->post('youtube_id');
        if(!empty($token) && !empty($youtube_id)){
            $user=$this->account->verify_token($token);
            if(!empty($user) && is_array($user) && $user['role']=='member'){
                $regid=$user['id'];
                $data=array("date"=>date('Y-m-d'),"regid"=>$regid,"youtube_id"=>$youtube_id, "added_on"=>date('Y-m-d H:i:s'));
                $result=$this->member->saveyoutubead($data);
                if($result['status']===true){
                    $this->response([
                            'status' => true,
                            'message' => "Ad Viewed!"], REST_Controller::HTTP_OK);
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
				'message' => "Please provide all details!"], REST_Controller::HTTP_OK);
		}
    }
    
	public function getyoutubelinks_post(){
        $token=$this->post('token');
        if(!empty($token)){
            $user=$this->account->verify_token($token);
            if(!empty($user) && is_array($user) && $user['role']=='member'){
                $regid=$user['id'];
                $available=$this->member->availableyoutubeads($regid);
                $where="t1.status=1 and id not in (SELECT youtube_id from ".TP."daily_ads where regid='$regid' and date='".date('Y-m-d')."')";
                $youtubelinks=$this->common->getyoutubelinks($where);
                if(!empty($youtubelinks)){
                    $count=count($youtubelinks);
                    while($count>$available){
                        $count--;
                        unset($youtubelinks[$count]);
                    }
                    $this->response([
                        'status' => true,
                        'youtubelinks' => $youtubelinks], REST_Controller::HTTP_OK);
                }		
                else{
                    $this->response([
                        'status' => false,
                        'message' => "No Banner Image Found!"], REST_Controller::HTTP_OK);
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
    
    public function availablespins_post(){
        $token=$this->post('token');
        if(!empty($token)){
            $user=$this->account->verify_token($token);
            if(!empty($user) && is_array($user) && $user['role']=='member'){
                $regid=$user['id'];
                $available=$this->member->availablespins($regid);
                $this->response([
                            'status' => true,
                            'available' => $available], REST_Controller::HTTP_OK);
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
    
    public function savespin_post(){
        $token=$this->post('token');
        $amount=$this->post('amount');
        if(!empty($token) && !empty($amount)){
            $user=$this->account->verify_token($token);
            if(!empty($user) && is_array($user) && $user['role']=='member'){
                $regid=$user['id'];
                $remarks="Daily Spin";
                $data=array("date"=>date('Y-m-d'),"regid"=>$regid,"amount"=>$amount,"added_on"=>date('Y-m-d H:i:s'));
                $result=$this->member->savespin($data);
                if($result['status']===true){
                    $this->response([
                            'status' => true,
                            'message' => "Spin Saved!"], REST_Controller::HTTP_OK);
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
				'message' => "Please provide all details!"], REST_Controller::HTTP_OK);
		}
    }
    
}
