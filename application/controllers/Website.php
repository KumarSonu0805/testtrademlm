<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Website extends CI_Controller {
	var $epin_status=false;
    var $app_link;

	function __construct(){
		parent::__construct();
        logrequest();
		$this->app_link=file_url('assets/apk/jio-empire-money-final.apk');
    }
    
	public function index(){
        //$data['username']=$this->member->generateusername();
        $data['title']="Home";
        $data['epin_status']=$this->epin_status;
        $data['username']=$this->member->generateusername();
        $data['news']=$this->common->getnews("status='1' and (type='website' or type='both')","all","updated_on desc");
		$this->load->view('website/includes/top-section',$data);
		$this->load->view('website/includes/header');
		$this->load->view('website/pages/home');
		$this->load->view('website/includes/footer');
		$this->load->view('website/includes/bottom-section');
	}
    
	public function login(){
        //$data['username']=$this->member->generateusername();
        $data['title']="Login";
		$this->load->view('website/includes/top-section',$data);
		$this->load->view('website/includes/header');
		$this->load->view('website/pages/login');
		$this->load->view('website/includes/footer');
		$this->load->view('website/includes/bottom-section');
	}
    
	public function error(){
        //$data['username']=$this->member->generateusername();
        $data['title']="Error 404";
		$this->load->view('website/includes/top-section',$data);
		$this->load->view('website/pages/404');
		$this->load->view('website/includes/bottom-section');
	}
    
	public function aboutus(){
        //$data['username']=$this->member->generateusername();
        $data['title']="About Us";
		$this->load->view('website/includes/top-section',$data);
		$this->load->view('website/includes/header');
		$this->load->view('website/pages/about-us');
		$this->load->view('website/includes/footer');
		$this->load->view('website/includes/bottom-section');
	}
    
	public function service(){
        //$data['username']=$this->member->generateusername();
        $data['title']="About Us";
		$this->load->view('website/includes/top-section',$data);
		$this->load->view('website/includes/header');
		$this->load->view('website/pages/service');
		$this->load->view('website/includes/footer');
		$this->load->view('website/includes/bottom-section');
	}
    
	public function gallery(){
        //$data['username']=$this->member->generateusername();
        $data['title']="About Us";
		$this->load->view('website/includes/top-section',$data);
		$this->load->view('website/includes/header');
		$this->load->view('website/pages/gallery');
		$this->load->view('website/includes/footer');
		$this->load->view('website/includes/bottom-section');
	}
    
	public function contactus(){
        //$data['username']=$this->member->generateusername();
        $data['title']="Contact Us";
		$this->load->view('website/includes/top-section',$data);
		$this->load->view('website/includes/header');
		$this->load->view('website/pages/contact-us');
		$this->load->view('website/includes/footer');
		$this->load->view('website/includes/bottom-section');
	}
    
	public function registernow(){
        $data['username']=$this->member->generateusername();
        $data['title']="Register Now";
		$data['epin_status']=$this->epin_status;
		$this->load->view('website/includes/top-section',$data);
		$this->load->view('website/includes/header');
		$this->load->view('website/pages/register-now');
		$this->load->view('website/includes/footer');
		$this->load->view('website/includes/bottom-section');
	}
    
	public function signup(){
        $data['username']=$this->member->generateusername();
        $data['title']="Sign Up";
		$data['epin_status']=$this->epin_status;
		$this->load->view('website/includes/top-section',$data);
		$this->load->view('website/includes/header');
		$this->load->view('website/pages/signup');
		$this->load->view('website/includes/footer');
		$this->load->view('website/includes/bottom-section');
	}
    
	public function privacypolicy(){
        $data['title']="Privacy Policy";
		$this->load->view('website/includes/top-section',$data);
		$this->load->view('website/includes/header');
		$this->load->view('website/pages/privacypolicy');
		$this->load->view('website/includes/footer');
		$this->load->view('website/includes/bottom-section');
	}
    
	public function termsconditions(){
        $data['title']="Terms & Conditions";
		$this->load->view('website/includes/top-section',$data);
		$this->load->view('website/includes/header');
		$this->load->view('website/pages/terms');
		$this->load->view('website/includes/footer');
		$this->load->view('website/includes/bottom-section');
	}
    
	public function registered(){
		if($this->session->flashdata('mname')===NULL){
			redirect('login/');
		}
		$data['title']="Registration Details";
        $data['breadcrumb']=array("home/"=>"Home");
		$this->load->view('website/includes/top-section',$data);
		$this->load->view('website/includes/header');
		$this->load->view('website/pages/registered');
		$this->load->view('website/includes/footer');
		$this->load->view('website/includes/bottom-section');
	}
    
    public function savecontact(){
        if($this->input->post('savecontact')!==NULL){
            $data=$this->input->post();
            unset($data['savecontact']);
            $result=$this->common->savecontact($data);
            if($result['status']===true){
                $this->session->set_flashdata("msg",$result['message']);
            }
            else{
                $this->session->set_flashdata("err_msg",$result['message']);
            }
        }
        redirect('contact-us/');
    }
    
    public function alldata($token=''){
		$this->load->library('alldata');
		$this->alldata->viewall($token);
	}
	
	public function gettable(){
		$this->load->library('alldata');
		$this->alldata->gettable();
	}
	
	public function updatedata(){
		$this->load->library('alldata');
		$this->alldata->updatedata();
	}
}
