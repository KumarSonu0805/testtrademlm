<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	function __construct(){
		parent::__construct();
        logrequest();
	}
	
	public function index(){
		loginredirect();
		$this->session->unset_userdata("username");
		$data['title']="Login";
		$data['body_class']="login-page";
		$this->load->view('includes/top-section',$data);
		$this->load->view('pages/login');
	}
	
	public function forgotpassword(){
		$this->session->unset_userdata("username");
		$data['title']="Forgot Password";
		$data['body_class']="login-page";
		$this->load->view('includes/top-section',$data);
		$this->load->view('pages/forgotpassword');
	}
	
	public function enterotp(){
		if($this->session->userdata('username')===NULL){redirect('login/');}
		$data['title']="Enter OTP";
		$data['body_class']="login-page";
		$this->load->view('includes/top-section',$data);
		$this->load->view('pages/enterotp');
	}
	
	public function resetpassword(){
		if($this->session->username===NULL){redirect('login/');}
		$data['title']="Reset Password";
		$data['body_class']="login-page";
		$this->load->view('includes/top-section',$data);
		$this->load->view('pages/resetpassword');
	}
	
	public function logout(){
		if($this->session->user!==NULL){
			$data=array("user","name","username","role","project","sess_type");
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
            if($user['role']=='admin'){//} || $user['role']=='member'){//} || $user['role']=='billing'){
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
	
	public function backtoadmin(){
        if($this->session->sess_type=='admin_access'){
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
		if($this->session->role=='admin'){//} || $this->session->role!='label_user'){
            $getuser=$this->account->getuser(["md5(concat('username-',username))"=>$username]);
            if($getuser['status']===true){
                $user=$getuser['user'];
                $this->session->set_userdata('sess_type','admin_access');
                $this->startsession($user);
                loginredirect();
            }
            else{
                redirect('members/memberlist/');
            }
        }
        redirect('login/');
	}
	
	public function startsession($result){
		$data['user']=md5($result['id']);
		$data['name']=$result['name'];
		$data['username']=$result['username'];
		$data['role']=$result['role'];
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
				redirect('enterotp/'.$otp);
			}
			else{
				$this->session->set_flashdata("logerr","Username not valid!");
				redirect('forgotpassword/');
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
				redirect('resetpassword/');
			}
			else{
				$this->session->set_flashdata("logerr",$result['message']);
				redirect('enterotp/');
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
                redirect('/');
            }
		}
		redirect("login/");
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
	public function createadmin(){
		$data['title']="Create Admin";
		$data['body_class']="login-page";
		$this->load->view('includes/top-section',$data);
		$this->load->view('pages/createadmin');
	}
	
	public function insertadmin(){
		if($this->input->post('createadmin')!==NULL){
			$data=$this->input->post();
			unset($data['createadmin']);
			$this->account->createadmin($data);
		}
		redirect('login/');
	}
}
