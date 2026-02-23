<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Website extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        // Load global models, check auth, etc.
    }
    
    public function index(){
        $data['title']='Home';
        $this->load->view('website',$data);
    }
    
    
}