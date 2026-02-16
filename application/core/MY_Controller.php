<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
    
    var $template;
    public function __construct() {
        parent::__construct();
        // Load global models, check auth, etc.
        if(true || $_SERVER['HTTP_HOST']=='localhost' || $this->input->get('test')=='test'){
            $this->load->library('template2');
            $this->template=$this->template2;
        }
        else{
            $this->load->library('template');
        }
    }
}