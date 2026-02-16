<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        // Load global models, check auth, etc.
    }
    
    public function index(){
        checklogin();
        $data=['title'=>'Profile'];
        $data['user']=getuser();
        $data['member']=$this->member->getmemberdetails($data['user']['id']);
        $this->template->load('profile','profile',$data);
    }
    
}