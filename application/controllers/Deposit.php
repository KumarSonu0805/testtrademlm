<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Deposit extends MY_Controller {

    public function __construct() {
        parent::__construct();
        //logrequest();
        checklogin();
        if($this->session->role!='member'){
            redirect('/');
        }
    }

    public function index() {
        $data['title']="Add Deposit";
        $data['user']=getuser();
        $data['member']=$this->member->getmemberdetails($data['user']['id']);
        $this->template->load('members','depositform',$data);
    }
    
    public function oldindex() {
        if(empty($this->input->get('round'))){
            redirect('/');
        }
        $data['round']=$this->input->get('round');
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
            $data['status']=0;
			$upload_path="./assets/uploads/screenshot/";
			$allowed_types="jpg|jpeg|png";
            $upload=upload_file('screenshot',$upload_path,$allowed_types,$user['username'].'-screenshot',50000);
            if($upload['status']===true){
                //create_image_thumb('.'.$upload['path'],'',TRUE,array("width"=>150,"height"=>150));
                $data['screenshot']=$upload['path'];
                //print_pre($data,true);
                $result=$this->member->savedeposit($data);
                if($result['status']===true){
                    $this->session->set_flashdata("msg",$result['message']);
                }
                else{
                    $this->session->set_flashdata("err_msg",$result['message']);
                }
            }
            else{
                $this->session->set_flashdata("err_msg",$upload['msg']);
            }
		}
		redirect($_SERVER['HTTP_REFERER']);
    }
    
    public function unitdeposit() {
        $user=getuser();
        $data=$this->input->post();
        $member=$this->member->getmemberdetails($user['id']);
        $data['regid']=$user['id'];
        $data['date']=date('Y-m-d');
        $data['amount']=$data['units']/CONV_RATE;
        $data['updated_on']=$data['added_on']=date('Y-m-d H:i:s');
        //print_pre($data,true);
        $result=$this->member->savedeposit($data);
        if($result['status']===true){
            $this->session->set_flashdata("msg",$result['message']);
        }
        else{
            $this->session->set_flashdata("err_msg",$result['message']);
        }
    }
}