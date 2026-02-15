<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {
	public function __construct(){
		parent::__construct();
		checklogin();
        logrequest();
	}
	
	public function index(){
		if($this->session->role=='admin'){ redirect('home/'); }
		$data['title']="My Profile";
		$data['breadcrumb']=array("home/"=>"Home");
        $getuser=$this->account->getuser(array("md5(id)"=>$this->session->userdata('user')));
        $data['user']=$getuser['user'];
		$regid=$data['user']['id'];
		$memberdetails=$this->member->getalldetails($regid);
		$data=array_merge($data,$memberdetails);
		
		$options=array(""=>"Select Bank","xyz"=>"Others");
		$banks=$this->common->getbanks();
		if(is_array($banks)){
			foreach($banks as $bank){
				$options[$bank['name']]=$bank['name'];
			}
		}
		$data['banks']=$options;
        
		$data['profile']=true;
		$this->template->load('profile','profile',$data);
	}
	
	public function accdetails(){
		if($this->session->role=='admin'){ redirect('home/'); }
		$data['title']="Account Details";
		$data['breadcrumb']=array("home/"=>"Home");
        $getuser=$this->account->getuser(array("md5(id)"=>$this->session->userdata('user')));
        $data['user']=$getuser['user'];
		$regid=$data['user']['id'];
		$memberdetails=$this->member->getalldetails($regid);
		$data=array_merge($data,$memberdetails);
		
		$options=array(""=>"Select Bank","xyz"=>"Others");
		$banks=$this->common->getbanks();
		if(is_array($banks)){
			foreach($banks as $bank){
				$options[$bank['name']]=$bank['name'];
			}
		}
		$data['banks']=$options;
		
		$this->template->load('profile','profile',$data);
	}
	
	public function adminaccdetails(){
		if($this->session->role!='admin'){ redirect('home/'); }
		$data['title']="Account Details";
		$data['breadcrumb']=array("home/"=>"Home");
        $getuser=$this->account->getuser(array("md5(id)"=>$this->session->userdata('user')));
        $data['user']=$getuser['user'];
		$regid=$data['user']['id'];
        
		$accdetails=$this->member->getaccdetails($regid);
        if(!empty($accdetails)){
            $data['acc_details']=$accdetails;
        }
		
		$options=array(""=>"Select Bank","xyz"=>"Others");
		$banks=$this->common->getbanks();
		if(is_array($banks)){
			foreach($banks as $bank){
				$options[$bank['name']]=$bank['name'];
			}
		}
		$data['banks']=$options;
		
		$this->template->load('profile','profile',$data);
	}
	
	public function invoice(){
		if($this->session->role=='admin'){ redirect('home/'); }
		$data['title']="Invoice";
		$data['breadcrumb']=array("home/"=>"Home");
        $getuser=$this->account->getuser(array("md5(id)"=>$this->session->userdata('user')));
        $data['user']=$getuser['user'];
		$regid=$data['user']['id'];
		$memberdetails=$this->member->getalldetails($regid);
		$data=array_merge($data,$memberdetails);
		
		if($data['member']['status']==0){ redirect('home/'); }
		$options=array(""=>"Select Bank","xyz"=>"Others");
		$banks=$this->common->getbanks();
		if(is_array($banks)){
			foreach($banks as $bank){
				$options[$bank['name']]=$bank['name'];
			}
		}
		$data['banks']=$options;
		$data['package']=$this->package->getpackage(array("id"=>$data['member']['package_id']),'Single');
        $data['activated']=$this->db->get_where("epin_used",array("used_by"=>$regid))->unbuffered_row('array');
        $data['epin']=$this->db->get_where("epins",array("id"=>$data['activated']['epin_id']))->unbuffered_row('array');
        //echo PRE;print_r($data);die;
		//$this->template->load('profile','invoice',$data);
        $this->load->view('profile/invoice',$data);
	}
	
	public function changepassword(){
		$data['title']="Change Password";
		$data['breadcrumb']=array("home/"=>"Home","profile/"=>"My Profile");
        $getuser=$this->account->getuser(array("md5(id)"=>$this->session->userdata('user')));
        $data['user']=$getuser['user'];
		$regid=$data['user']['id'];
		$details=$this->member->getmemberdetails($regid);
		//$data['user']['photo']=$details['photo'];
		$this->template->load('profile','changepassword',$data);
	}
	
	public function kyc(){
		if($this->session->role=='admin'){ redirect('home/'); }
		$data['title']="Upload KYC";
		$data['breadcrumb']=array("home/"=>"Home","profile/"=>"My Profile");
        $getuser=$this->account->getuser(array("md5(id)"=>$this->session->userdata('user')));
        $data['user']=$getuser['user'];
		$regid=$data['user']['id'];
		$memberdetails=$this->member->getalldetails($regid);
		$data=array_merge($data,$memberdetails);
		$this->template->load('profile','kyc',$data);
	}
	
	public function kycdocuments(){
		if($this->session->role=='admin'){ redirect('home/'); }
		$data['title']="View Documents";
		$data['breadcrumb']=array("home/"=>"Home","profile/"=>"My Profile");
        $getuser=$this->account->getuser(array("md5(id)"=>$this->session->userdata('user')));
        $data['user']=$getuser['user'];
		$regid=$data['user']['id'];
		$data['acc_details']=$this->member->getaccdetails($regid);
		$this->template->load('profile','documents',$data);
	}
	
	public function idcard(){
		if($this->session->role=='admin'){ redirect('home/'); }
		$data['title']="ID Card";
		$data['breadcrumb']=array("home/"=>"Home");
        $getuser=$this->account->getuser(array("md5(id)"=>$this->session->userdata('user')));
        $data['user']=$getuser['user'];
		$regid=$data['user']['id'];
		$memberdetails=$this->member->getalldetails($regid);
        $data=array_merge($data,$memberdetails);
        if($data['member']['status']==1){
            $doj=$data['member']['activation_date'];
            $package=$this->package->getpackage(array("id"=>$data['member']['package_id']),"single");
            $doe=date('Y-m-d',strtotime($doj." +".$package['validity']." months"));
            $imgdata=array("username"=>$data['user']['username'],"name"=>$data['user']['name'],"mobile"=>$data['user']['mobile'],
                           "photo"=>$data['user']['photo'],"gender"=>$data['member']['gender'],"dob"=>$data['member']['dob'],
                           "email"=>$data['member']['email'],"doj"=>$doj,"doe"=>$doe);
            $imgdata["regenerate"]=true;
        }
        if($data['member']['status']==1 && $data['member']['dob']!='0000-00-00' & $data['member']['aadhar']!='' & $data['member']['pan']!='' & $data['member']['photo']!='' & $data['member']['email']!=''){
		  createcard($imgdata);
        }
		$this->template->load('profile','idcard',$data);
	}
	
	public function updatepassword(){
		if($this->input->post('updatepassword')!==NULL){
			$data=$this->input->post();
            $data = array_map('strip_tags', $data);
            $data = array_map('htmlspecialchars', $data);
			$result=$this->member->updatepassword($data);
			if($result===true){
				$this->session->set_flashdata('msg',"Password Changed!");
			}
			else{
				$this->session->set_flashdata('err_msg',"Password Not Changed!");
			}
			redirect('profile/changepassword/');
		}
	}
	
	public function updatephoto(){
		if($this->input->post('updatephoto')!==NULL){
            $name=$this->input->post('name');
			$regid=$this->input->post('regid');
			$upload_path="./assets/uploads/members/";
			$allowed_types="jpg|jpeg|png";
			$file_name=$this->input->post('name');
            $upload=upload_file('photo',$upload_path,$allowed_types,$name.'-photo');
            if($upload['status']===true){
                create_image_thumb('.'.$upload['path'],'',TRUE,array("width"=>150,"height"=>150));
                $data['photo']=$upload['path'];
            }
			if($data['photo']!=''){
				$result=$this->member->updatephoto($data,$regid);
				if($result===true){
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
	

	public function updatepersonaldetails(){
		if($this->input->post('updatepersonaldetails')!==NULL){
			$data=$this->input->post();
            $data = array_map('strip_tags', $data);
            $data = array_map('htmlspecialchars', $data);
			$where['regid']=$data['regid'];
			unset($data['regid']);
			unset($data['updatepersonaldetails']);
			$result=$this->member->updatepersonaldetails($data,$where);
			if($result===true){
                $member=$this->member->getmemberdetails($where['regid']);
                $getuser=$this->account->getuser(array("id"=>$where['regid']));
                $user=$getuser['user'];
                $this->member->updatecontactinfo($data,$where);
				$this->session->set_flashdata("msg","Personal Details Updated successfully!");
			}
			else{
				$this->session->set_flashdata("err_msg",$result['message']);
			}
		}
        if($this->session->role=='admin'){
            redirect('members/memberlist/');
        }
        else{
            redirect('profile/');
        }
	}
	
	public function updatecontactinfo(){
		if($this->input->post('updatecontactinfo')!==NULL){
			$data=$this->input->post();
            $data = array_map('strip_tags', $data);
            $data = array_map('htmlspecialchars', $data);
			$where['regid']=$data['regid'];
			unset($data['regid']);
			unset($data['updatecontactinfo']);
            //$data=array_map("strtoupper",$data);
            $data['email']=strtolower($data['email']);
			$result=$this->member->updatecontactinfo($data,$where);
			if($result===true){
                $member=$this->member->getmemberdetails($where['regid']);
                $getuser=$this->account->getuser(array("id"=>$where['regid']));
                $user=$getuser['user'];
				$this->session->set_flashdata("msg","Contact Details Updated successfully!");
			}
			else{
				$this->session->set_flashdata("err_msg",$result['message']);
			}
		}
        if($this->session->role=='admin'){
            redirect('members/memberlist/');
        }
        else{
            redirect('profile/');
        }
	}
	
	public function updatenomineedetails(){
		if($this->input->post('updatenomineedetails')!==NULL){
			$data=$this->input->post();
            $data = array_map('strip_tags', $data);
            $data = array_map('htmlspecialchars', $data);
			$where['regid']=$data['regid'];
			unset($data['regid']);
			unset($data['updatenomineedetails']);
            //$data=array_map("strtoupper",$data);
			$result=$this->member->updatenomineedetails($data,$where);
			if($result===true){
				$this->session->set_flashdata("msg","Nominee Details Updated successfully!");
			}
			else{
				$this->session->set_flashdata("err_msg",$result['message']);
			}
		}
        if($this->session->role=='admin'){
            redirect('members/memberlist/');
        }
        else{
            redirect('profile/');
        }
	}
	
	public function updateaccdetails(){
		if($this->input->post('updateaccdetails')!==NULL){
			$data=$this->input->post();
            $data = array_map('strip_tags', $data);
            $data = array_map('htmlspecialchars', $data);
			$where['regid']=$data['regid'];
			unset($data['regid']);
			unset($data['updateaccdetails']);
            $bank=$data['bank'];
            unset($data['bank']);
            //$data=array_map("strtoupper",$data);
            $data['bank']=$bank;
			$name=$this->input->post('name');
			$upload_path="./assets/uploads/documents/";
			$allowed_types="jpg|jpeg|png";
			$file_name=$name;
            if(isset($_FILES['cheque'])){
                $cheque=upload_file('cheque',$upload_path,$allowed_types,$file_name.'_cheque',10000);
                $data['cheque']=$cheque['path'];
            }
            //die;
			$result=$this->member->updateaccdetails($data,$where);
			if($result===true){
                $member=$this->member->getmemberdetails($where['regid']);
                $getuser=$this->account->getuser(array("id"=>$where['regid']));
                $user=$getuser['user'];
				$this->session->set_flashdata("msg","Account Details Updated successfully!");
			}
			else{
				$this->session->set_flashdata("err_msg",$result['message']);
			}
		}
        redirect($_SERVER['HTTP_REFERER']);
	}
	
	public function uploaddocs(){
		if($this->input->post('uploaddocuments')!==NULL){
			$where['regid']=$this->input->post('regid');
			$name=$this->input->post('name');
			$upload_path="./assets/uploads/documents/";
			$allowed_types="jpg|jpeg|png";
			$file_name=$name;
			$pan=upload_file('pan',$upload_path,$allowed_types,$file_name.'_pan',10000);
			$aadhar1=upload_file('aadhar1',$upload_path,$allowed_types,$file_name.'_aadhar1',10000);
			$aadhar2=upload_file('aadhar2',$upload_path,$allowed_types,$file_name.'_aadhar2',10000);
			$cheque=upload_file('cheque',$upload_path,$allowed_types,$file_name.'_cheque',10000);
            $data['pan']=$pan['path'];
            $data['aadhar1']=$aadhar1['path'];
            $data['aadhar2']=$aadhar2['path'];
            $data['cheque']=$cheque['path'];
			foreach($data as $key=>$value){
				if(empty($value)){ unset($data[$key]); }
			}
			if(!empty($data)){
				$result=$this->member->updateaccdetails($data,$where);
				if($result===true){
					$this->session->set_flashdata("msg","Document successfully!");
				}
				else{
					$this->session->set_flashdata("err_msg",$result['message']);
				}
			}
		}
		redirect('profile/kyc/');
	}
	
    public function getdistricts(){
        $parent_id=$this->input->post('parent_id');
        $districts=$this->common->getdistricts($parent_id);
        $options=array(""=>"Select District");
        if(is_array($districts)){
            foreach($districts as $district){
                $options[$district['id']]=$district['name'];
            }
        }
        echo form_dropdown('area_id',$options,'',array('class'=>'form-control','id'=>'area_id','required'=>'true'));
    }
    
    public function checkpan(){
        $getuser=$this->account->getuser(array("md5(id)"=>$this->session->userdata('user')));
        $data['user']=$getuser['user'];
		$regid=$data['user']['id'];
        $pan=$this->input->post('pan');
        if($pan!=''){
            $check=$this->db->get_where("members",array("regid!="=>$regid,"pan"=>$pan))->num_rows();
            if($check!=0){
                echo 0;
            }
            else{
                echo 1;
            }
        }
        else{
            echo 1;
        }
    }
}
