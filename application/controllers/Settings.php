<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends MY_Controller {

	function __construct(){
		parent::__construct();
        checklogin();
        if($this->session->role!='admin'){
            redirect('/');
        }
	}

	public function index(){
        if($this->session->role!='admin'){
            redirect('/');
        }
        $data['title']="Settings";
        //$data['subtitle']="Sample Subtitle";
        $data['breadcrumb']=array();
        $where=array('status'=>1);
        $this->setting->generatesettings($where);
        $data['settings']=$this->setting->getsettings($where);
		$this->template->load('settings','general',$data);
	}
    

    public function updatesetting(){
        if($this->input->post('updatesetting')!==NULL){
            $data=$this->input->post();
            unset($data['updatesetting']);
            if(strpos($data['name'],'qrcode')===0){
                $upload_path='./assets/images/qrimage/';
                $allowed_types='gif|jpg|jpeg|png|svg';
                $upload=upload_file('value',$upload_path,$allowed_types,'qr-image');
                if($upload['status']===true){
                    $data['value']=$upload['path'];
                }
                else{
                    $data['value']='';
                }
            }
			//$result=$this->setting->updatesetting($data);
			if($result['status']===true){
				$this->session->set_flashdata("msg",$result['message']);
			}
            else{
                $this->session->set_flashdata("err_msg",$result['message']);
            }
        }
        if(!empty($data) && !isset($data['id'])){
            unset($_SESSION["msg"],$_SESSION["err_msg"]);
            echo json_encode($result);
            exit;
        }
        redirect($_SERVER['HTTP_REFERER']);
    }
    
    public function getsetting(){
        $id=$this->input->post('id');
        $setting=$data['settings']=$this->setting->getsettings(array("md5(concat('setting-',id))"=>$id),"single");
        echo json_encode($setting);
    }

}
