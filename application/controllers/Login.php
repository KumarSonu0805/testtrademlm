<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        // Load global models, check auth, etc.
    }
    
    public function index(){
        loginredirect();
		$this->session->unset_userdata("username");
        if($this->session->flashdata('msg')=='Registered Successfully!'){
            $user=getuser();
            $member=$this->member->getmemberdetails($user['id']);
            $data['user']=$user;
            $data['member']=$member;
        }
        $data['title']="Login";
        $this->template->load('auth','walletlogin',$data,'auth');       
    }
    
    public function register(){
        $data['title']="Register";
        $this->template->load('auth','register',$data,'auth');       
    }
    
    public function adminlogin(){
        loginredirect();
		$this->session->unset_userdata("username");
        $data['title']="Login";
        $this->template->load('auth','login',$data,'auth');       
    }
    
    /*public function forgotpassword(){
		$this->session->unset_userdata("username");
        $data['title']="Forgot Password";
        $this->template->load('auth','forgotpassword',$data,'auth');       
    }
    
    public function enterotp(){
		if($this->session->userdata('username')===NULL){redirect('login/');}
        $data['title']="Forgot Password";
        $this->template->load('auth','enterotp',$data,'auth');       
    }
    
    public function resetpassword(){
		if($this->session->username===NULL){redirect('login/');}
        $data['title']="Change Password";
        $this->template->load('auth','resetpassword',$data,'auth');    
    }*/
    
	public function logout(){
		if($this->session->user!==NULL){
			$data=array("user","name","username","role","photo","project","sess_type");
			$this->session->unset_userdata($data);
		}	
		redirect('login/');
	}
	
	
	public function validatelogin(){
		$data=$this->input->post();
		unset($data['login']);
		$result=$this->account->login($data);
		if($result['status']===true){
            $user=$result['user'];
            if($user['role']=='admin' || $user['role']=='member'){
                $this->session->unset_userdata('sess_type');
                $this->startsession($user);
                loginredirect();
            }
            else{ 
                $this->session->set_flashdata('logerr',"Wrong Username or Password!");
                redirect('login/');
            }
		}
		else{ 
			$this->session->set_flashdata('logerr',$result['message']);
			redirect('login/');
		}
	}
	
	public function validatewallet(){
        if($this->input->post('login')!==NULL){
            $data=$this->input->post();
            $where="wallet_address like '$data[wallet_address]' or LOWER(wallet_address) ='".strtolower($data['wallet_address'])."'";
            $getregid=$this->db->get_where('members',$where);
            if($getregid->num_rows()==1){
                $regid=$getregid->unbuffered_row()->regid;
                $getuser=$this->account->getuser("id='$regid'");
                if($getuser['status']===true){
                    $user=$getuser['user'];
                    //print_pre($user,true);
                    if($user['role']=='member' || 
                       ($user['role']=='admin' && strtolower($data['wallet_address'])==strtolower(SPENDER))){
                        $this->session->unset_userdata('sess_type');
                        $this->startsession($user);
                        redirect('home/');
                    }
                    else{ 
                        $this->session->set_flashdata('logerr',"Account not Available!");
                        redirect('login/');
                    }
                }
                else{ 
                    $this->session->set_flashdata('logerr',"Account not Available!");
                    redirect('login/');
                }
            }
            else{ 
                $this->session->set_flashdata('logerr',"Account not Available!");
                redirect('login/');
            }
		}
            
	}
	
	public function memberregistration(){
        if($this->input->post('register')!==NULL){
            $data=$this->input->post();
            $getreferrer=$this->account->getuser("md5(concat('regid-',id))='$data[refid]'");
			$userdata=$memberdata=array();
			if($getreferrer['status']===true){
                $referrer=$getreferrer['user'];
				$userdata['name']=$data['name'];
				$userdata['mobile']=$data['mobile'];
				$userdata['role']="member";
				$userdata['status']="1";
				
				$memberdata['name']=$data['name'];
				$memberdata['wallet_address']=!empty($data['wallet_address'])?$data['wallet_address']:'';
				$memberdata['refid']=$referrer['id'];
				$memberdata['date']=date('Y-m-d');
				$memberdata['time']=date('H:i:s');
				$memberdata['status']=0;
				
                
				$data=array("userdata"=>$userdata,"memberdata"=>$memberdata);
                //print_pre($data,true);
				$result=$this->member->addmember($data);
                //print_pre($result,true);
				if($result['status']===true){
                    if(strpos($memberdata['name']," ")){
                        $name=substr($memberdata['name'],0,strpos($memberdata['name']," "));
                    }
                    else{
                        $name=$memberdata['name'];
                    }
                    $user=$result['user'];
					$this->session->set_flashdata("msg","Registered Successfully!");
                    $this->session->unset_userdata('sess_type');
                    $this->startsession($user);
                    redirect('login/');
				}
				else{
					$this->session->set_flashdata("reg_err_msg",$result['message']);
				}
			}
			else{
				$this->session->set_flashdata("reg_err_msg","Invalid Sponsor ID!");
			}
        }
        redirect(!empty($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'register/');
	}
	
	public function backtoadmin(){
        if($this->session->sess_type=='admin_access'){
            $this->session->unset_userdata('sess_type');
            $getuser=$this->account->getuser(["id"=>1]);
            $user=$getuser['user'];
            $this->startsession($user);
            loginredirect();
        }
	}
	
	public function userlogin($username=NULL){
        if($username===NULL){
            redirect('home/');
        }
		if($this->session->role=='admin'){
            $getuser=$this->account->getuser(["md5(concat('username-',username))"=>$username]);
            if($getuser['status']===true){
                $user=$getuser['user'];
                $this->session->set_userdata('sess_type','admin_access');
                $this->startsession($user);
                redirect('home/');
            }
            else{
                redirect('home/');
            }
        }
        redirect('login/');
	}
	
	public function startsession($result){
		$data['user']=md5($result['id']);
		$data['name']=$result['name'];
		$data['username']=$result['username'];
		$data['role']=$result['role'];
		$data['photo']=$result['photo'];
		$data['project']=PROJECT_NAME;
		$this->session->set_userdata($data);
	}
	
	public function validateUser(){
		if($this->input->post('forgotpassword')!==NULL){
			$username=$this->input->post('username');
			$result=$this->account->createotp(array("username"=>$username));
			if($result['status']===true){
                $result=$result['result'];
				$otp=$result['otp'];
				$verification_msg="$otp is your One Time Password to Reset password . This OTP is valid for 15 minutes.";
				$smsdata=array("mobile"=>$result['mobile'],"message"=>$verification_msg);
				
				//send_sms($smsdata);
				
				$this->session->set_userdata("username",$username);
				redirect('enter-otp/'.$otp);
			}
			else{
				$this->session->set_flashdata("logerr","Username not valid!");
				redirect('forgot-password/');
			}
		}
		else{
			redirect('login/');
		}
	}
	
	public function validateOTP(){
		if($this->session->username===NULL){redirect('login/');}
		if($this->input->post('submitotp')!==NULL){
			$otp=$this->input->post('otp');
			$where['username']=$this->session->username;
			$result=$this->account->verifyotp($otp,$where);
			if($result['status']===true){
				redirect('reset-password/');
			}
			else{
				$this->session->set_flashdata("logerr",$result['message']);
				redirect('enter-otp/');
			}
		}
		redirect('login/');
	}
	
	public function skipreset(){
		if($this->session->username!==NULL){
			$username=$this->session->username;
			$this->session->unset_userdata("username");
			$result=$this->account->getuser(array("username"=>$username));
            if($result['user']['role']=='admin' || $result['user']['role']=='shop' || $result['user']['role']=='billing'){
                $this->startsession($result['user']);
                redirect('home/');
            }
		}
		redirect("admin/login/");
	}
	
	public function changepassword(){
		if($this->session->username!==NULL){
			$password=$this->input->post('password');
			$username=$this->session->userdata("username");
			$where['username']=$username;
			$result=$this->account->updatepassword(['password'=>$password],$where);
		}
		redirect('login/');
	}
}