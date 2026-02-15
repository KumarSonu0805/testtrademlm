<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Packages extends CI_Controller {

	function __construct(){
		parent::__construct();
        checklogin();
	}
	
	public function index(){
        checklogin();
        if($this->session->role!='admin'){
            redirect('/');
        }
        $data=['title'=>'Packages'];
        $data['breadcrumb']=array();
        $data['nocard']=true;
        $data['datatable']=true;
        $data['packages']=$this->package->getpackages();
        $this->template->load('pages','packages',$data);
    }
    
    public function savepackage(){
        if($this->input->post('savepackage')!==NULL){
            $data=$this->input->post();
            unset($data['savepackage']);
            $result=$this->package->savepackage($data);
            if($result['status']===true){
                $this->session->set_flashdata('msg',$result['message']);
            }
            else{
                $this->session->set_flashdata('err_msg',$result['message']);
            }
        }
        if($this->input->post('updatepackage')!==NULL){
            $data=$this->input->post();
            $id=$data['id'];
            unset($data['updatepackage'],$data['id']);
            $check=$this->db->get_where('packages',
                                        ['package'=>$data['package'],'amount'=>$data['amount'],'id!='=>$id])->num_rows();
            if($check==0){
                $result=$this->package->updatepackage($data,['id'=>$id]);
                if($result['status']===true){
                    $this->session->set_flashdata('msg',$result['message']);
                }
                else{
                    $this->session->set_flashdata('err_msg',$result['message']);
                }
            }
            else{
                $this->session->set_flashdata('err_msg',"Package Already Added!");
            }
        }
        redirect($_SERVER['HTTP_REFERER']);
    }
    
    public function getpackage(){
        $id=$this->input->post('id');
        $package=$this->package->getpackages(['id'=>$id],'single');
        echo json_encode($package);
    }
    
}