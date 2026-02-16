<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Deposit extends MY_Controller {

    public function __construct() {
        parent::__construct();
        //logrequest();
        //checkactivation();
    }

    public function index() {
        $data['title']="Add Deposit";
        $data['user']=getuser();
        $data['member']=$this->member->getmemberdetails($data['user']['id']);
        $data['datatable']=true;
        $this->template->load('members','depositform',$data);
    }
    
    public function depositlist() {
        $data['title']="Deposit List";
        $data['user']=getuser();
        $data['deposits']=$this->member->getdeposits(['t1.regid'=>$data['user']['id']]);
        $data['datatable']=true;
        $this->template->load('members','depositlist',$data);
    }
    
    public function savedeposit() {
		if($this->input->post('savedeposit')!==NULL){
			$data=$this->input->post();
            $member=$this->member->getmemberdetails($data['regid']);
            $user=getuser();
            $data['updated_on']=$data['added_on']=date('Y-m-d H:i:s');
            unset($data['savedeposit']);
            $result=$this->member->savedeposit($data);
            if($result['status']===true){
                $this->session->set_flashdata("msg",$result['message']);
            }
            else{
                $this->session->set_flashdata("err_msg",$result['message']);
            }
		}
		redirect($_SERVER['HTTP_REFERER']);
    }
}