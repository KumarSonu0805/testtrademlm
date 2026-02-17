<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        // Load global models, check auth, etc.
    }
    
    public function index(){
        $data['title']="Profile";
        $data['user']=getuser();
        $data['member']=$this->member->getmemberdetails($data['user']['id']);
        $this->template->load('profile','profile',$data);
    }
    
    public function updateprofile(){
		if($this->input->post('updateprofile')!==NULL){
            $data=$this->input->post();
            unset($data['updateprofile']);
            $user=getuser();
            $result=$this->account->updateuser($data,['id'=>$user['id']]);
            if($result['status']===true){
                $this->session->set_userdata("name",($data['name']));
                $this->session->set_flashdata("msg",$result['message']);
            }
            else{
                $this->session->set_flashdata("err_msg",$result['message']);
            }
		}
		redirect('profile/');
    }
    
    public function updatewallet(){
		if($this->input->post('updatewallet')!==NULL){
            $data=$this->input->post();
            unset($data['updatewallet']);
            $user=getuser();
            $result=$this->db->update('members',$data,['regid'=>$user['id']]);
            if($result){
                $this->session->set_flashdata("msg","Wallet Address Updated Successfully");
            }
            else{
                $error=$this->db->error();
                $this->session->set_flashdata("err_msg",$error['message']);
            }
		}
		redirect('profile/');
    }
    
    public function updatephoto(){
		if($this->input->post('updatephoto')!==NULL){
            $name=$this->input->post('name');
			$regid=$this->input->post('regid');
			$upload_path="./assets/uploads/members/";
			$allowed_types="jpg|jpeg|png";
			$file_name=$this->input->post('name');
            $upload=upload_file('photo',$upload_path,$allowed_types,$name.'-photo',50000);
            if($upload['status']===true){
                //create_image_thumb('.'.$upload['path'],'',TRUE,array("width"=>150,"height"=>150));
                $data['photo']=$upload['path'];
            }
			if($data['photo']!=''){
				$result=$this->account->updatephoto($data,['id'=>$regid]);
				if($result['status']===true){
                    $this->session->set_userdata("photo",file_url($upload['path']));
					$this->session->set_flashdata("msg","Photo Updated successfully!");
				}
				else{
					$this->session->set_flashdata("err_msg",$result['message']);
				}
			}
		}
		redirect('profile/');
    }
    
    
}