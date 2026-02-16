<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wallet extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        // Load global models, check auth, etc.
    }
    
    public function index(){
        $data=['title'=>'Home'];
        $this->template->load('pages','error',$data);
    }
    
    public function withdrawal() {
        if($this->session->role=='admin'){
            redirect('/');
        }
        $data['title']="Withdrawal";
        $data['user']=getuser();
        $incomes=$this->income->getallincome($data['user']);
        $data['member']=$this->member->getmemberdetails($data['user']['id']);
        $data['avl_balance']=getavlbalance($data['user']);
        $this->template->load('wallet','withdrawal',$data);
    }

	public function fundrequests(){
        if($this->input->get('type')===NULL){
            $data['title']="Fund Requests";
            $data['tabulator']=true;
            $data['alertify']=true;
            $this->template->load('wallet','fundrequests',$data);          
        }
        else{
            $requests=[];//$this->wallet->getfundrequests(['t1.status'=>0]);
            $requests=array_map(function($item) {
                if(isset($item['id'])){
                    $item['id']=md5('fund-request-id-'.$item['id']);
                }
                return $item;
            },$requests);
            echo json_encode($requests);
        }      
    }
    
	public function approvedfundrequests(){
        if($this->input->get('type')===NULL){
            $data['title']="Approved Fund Requests";
            $data['tabulator']=true;
            $data['alertify']=true;
            $this->template->load('wallet','approvedfundrequests',$data);          
        }
        else{
            $requests=[];//$this->wallet->getfundrequests(['t1.status'=>1]);
            $requests=array_map(function($item) {
                if(isset($item['id'])){
                    $item['id']=md5('fund-request-id-'.$item['id']);
                }
                return $item;
            },$requests);
            echo json_encode($requests);
        }      
    }
    
	public function memberwallet(){
        if($this->input->get('type')===NULL){
            $data['title']="Member Wallet";
            
            $data['tabulator']=true;
            $data['alertify']=true;
            $this->template->load('wallet','memberwallet',$data);          
        }
        else{
            $members=$this->income->getmemberwallet();            
            echo json_encode($members);
        }      
    }
    
	public function transferfund(){
        $data['title']="Send Fund";
        $data['tabulator']=true;
        $data['alertify']=true;
        $this->template->load('wallet','transferfund',$data); 
    }
    
	public function transferfundhistory(){
        if($this->input->get('type')===NULL){
            $data['title']="Send Fund History";
            $data['tabulator']=true;
            $data['alertify']=true;
            $this->template->load('wallet','transferfundhistory',$data);          
        }
        else{
            $requests=[];//$this->wallet->getfundrequests(['t1.status'=>0]);
            $requests=array_map(function($item) {
                if(isset($item['id'])){
                    $item['id']=md5('fund-request-id-'.$item['id']);
                }
                return $item;
            },$requests);
            echo json_encode($requests);
        }      
    }
    
	public function withdrawalrequests(){
        if($this->input->get('type')===NULL){
            $data['title']="Withdrawal Requests";
            $data['tabulator']=true;
            $data['alertify']=true;
            $this->template->load('wallet','withdrawalrequests',$data);          
        }
        else{
            $requests=$this->member->getmemberrequests(['t1.status'=>0]);
            $requests=array_map(function($item) {
                if(isset($item['id'])){
                    $item['id']=md5('withdrawal-request-id-'.$item['id']);
                }
                return $item;
            },$requests);
            echo json_encode($requests);
        }      
    }
    
	public function unstakerequests(){
        if($this->input->get('type')===NULL){
            $data['title']="Unstake Requests";
            $data['tabulator']=true;
            $data['alertify']=true;
            $this->template->load('wallet','unstakerequests',$data);          
        }
        else{
            $requests=$this->member->getunstakerequests(['t1.status'=>0]);
            $requests=array_map(function($item) {
                if(isset($item['id'])){
                    $item['id']=md5('unstake-request-id-'.$item['id']);
                }
                return $item;
            },$requests);
            echo json_encode($requests);
        }      
    }
    
	public function history(){
        if($this->input->get('type')===NULL){
            $data['title']="Withdrawal History";
            $data['tabulator']=true;
            $data['alertify']=true;
            $this->template->load('wallet','history',$data);          
        }
        else{
            $requests=$this->member->getmemberrequests(['t1.status'=>1]);
            $requests=array_map(function($item) {
                if(isset($item['id'])){
                    $item['id']=md5('withdrawal-request-id-'.$item['id']);
                }
                return $item;
            },$requests);
            echo json_encode($requests);
        }      
    }
    
	public function unstakehistory(){
        if($this->input->get('type')===NULL){
            $data['title']="Unstake History";
            $data['tabulator']=true;
            $data['alertify']=true;
            $this->template->load('wallet','unstakehistory',$data);          
        }
        else{
            $requests=$this->member->getunstakerequests(['t1.status'=>1]);
            $requests=array_map(function($item) {
                if(isset($item['id'])){
                    $item['id']=md5('unstake-request-id-'.$item['id']);
                }
                return $item;
            },$requests);
            echo json_encode($requests);
        }      
    }
    
	public function withdrawalhistory(){
        if($this->input->get('type')===NULL){
            $data['title']="Withdrawal History";
            $data['tabulator']=true;
            $data['alertify']=true;
            $this->template->load('wallet','withdrawalhistory',$data);          
        }
        else{
            $user=getuser();
            $requests=$this->member->getwithdrawalrequest(['t1.regid'=>$user['id']]);
            $requests=array_map(function($item) {
                if(isset($item['id'])){
                    $item['id']=md5('withdrawal-request-id-'.$item['id']);
                }
                return $item;
            },$requests);
            echo json_encode($requests);
        }      
    }
    
	public function requestwithdrawal(){
		if($this->input->post('requestwithdrawal')!==NULL){
			$data=$this->input->post();
            $member=$this->member->getmemberdetails($data['regid']);
            $user=getuser();
            $avl_balance=getavlbalance($user);
            if($avl_balance>=$data['amount']){
                $data['deduction']=DEDUCTION;
                $data['deduction_amount']=($data['amount']*DEDUCTION)/100;
                $data['payable_amount']=$data['amount']-$data['deduction_amount'];
                unset($data['requestwithdrawal']);
                $data['updated_on']=$data['added_on']=date('Y-m-d H:i:s');
                //print_pre($data,true);
                $result=$this->member->requestwithdrawal($data);
                if($result['status']===true){
                    $this->session->set_flashdata("msg","Withdrawal Request Submitted successfully!");
                }
                else{
                    $this->session->set_flashdata("err_msg",$result['message']);
                }
			}
			else{
				$this->session->set_flashdata("err_msg","Invalid Request Amount!");
			}
		}
		redirect('wallet/withdrawal/');
	}
	
	public function approvewithdrawal(){
		$id=$this->input->post('id');
		$response=$this->input->post('response');
        $request=$this->member->getwithdrawalrequest(["md5(concat('withdrawal-request-id-',t1.id))"=>$id],'single');
        if(!empty($request) && $request['status']==0){
            $where=array('id'=>$request['id']);
            $user=getuser();
            $data=array('response'=>$response,'approve_date'=>date('Y-m-d H:i:s'),'approved_by'=>$user['id'],'status'=>1);
            $result=$this->member->updatewithdrawalrequest($data,$where);
            if($result['status']===true){
                echo json_encode(['status'=>true,'message'=>$result['message']]);
            }
            else{
                echo json_encode(['status'=>false,'message'=>$result['message']]);
            }
        }
        elseif(!empty($request) && $request['status']==1){
            echo json_encode(['status'=>true,'message'=>'Withdrawal Request Already Approved!']);
        }
        elseif(!empty($request) && $request['status']==2){
            echo json_encode(['status'=>true,'message'=>'Withdrawal Request Already Rejected!']);
        }
        else{
            echo json_encode(['status'=>false,'message'=>'Please Try Again!']);
        }
	}
	
	public function rejectwithdrawal(){
		$id=$this->input->post('id');
		$remarks=$this->input->post('remarks');
        $request=$this->member->getwithdrawalrequest(["md5(concat('withdrawal-request-id-',t1.id))"=>$id],'single');
        if(!empty($request) && $request['status']==0){
            $where=array('id'=>$request['id']);
            $user=getuser();
            $data=array('remarks'=>$remarks,'approve_date'=>date('Y-m-d H:i:s'),'approved_by'=>$user['id'],'status'=>2);
            $result=$this->member->updatewithdrawalrequest($data,$where);
            if($result['status']===true){
                echo json_encode(['status'=>true,'message'=>$result['message']]);
            }
            else{
                echo json_encode(['status'=>false,'message'=>$result['message']]);
            }
        }
        elseif(!empty($request) && $request['status']==1){
            echo json_encode(['status'=>true,'message'=>'Withdrawal Request Already Approved!']);
        }
        elseif(!empty($request) && $request['status']==2){
            echo json_encode(['status'=>true,'message'=>'Withdrawal Request Already Rejected!']);
        }
        else{
            echo json_encode(['status'=>false,'message'=>'Please Try Again!']);
        }
	}
	
	public function approveunstake(){
		$id=$this->input->post('id');
		$response=$this->input->post('response');
        $request=$this->member->getunstakerequest(["md5(concat('unstake-request-id-',t1.id))"=>$id],'single');
        if(!empty($request) && $request['status']==0){
            $where=array('id'=>$request['id']);
            $user=getuser();
            $data=array('response'=>$response,'approve_date'=>date('Y-m-d H:i:s'),'approved_by'=>$user['id'],'status'=>1);
            $result=$this->member->updateunstakerequest($data,$where);
            if($result['status']===true){
                $this->db->update('investments',['status'=>0,'updated_on'=>date('Y-m-d H:i:s')],['id'=>$request['inv_id']]);
                echo json_encode(['status'=>true,'message'=>$result['message']]);
            }
            else{
                echo json_encode(['status'=>false,'message'=>$result['message']]);
            }
        }
        elseif(!empty($request) && $request['status']==1){
            echo json_encode(['status'=>true,'message'=>'Unstake Request Already Approved!']);
        }
        elseif(!empty($request) && $request['status']==2){
            echo json_encode(['status'=>true,'message'=>'Unstake Request Already Rejected!']);
        }
        else{
            echo json_encode(['status'=>false,'message'=>'Please Try Again!']);
        }
	}
	
	public function rejectunstake(){
		$id=$this->input->post('id');
		$remarks=$this->input->post('remarks');
        $request=$this->member->getunstakerequest(["md5(concat('unstake-request-id-',t1.id))"=>$id],'single');
        if(!empty($request) && $request['status']==0){
            $where=array('id'=>$request['id']);
            $user=getuser();
            $data=array('remarks'=>$remarks,'approve_date'=>date('Y-m-d H:i:s'),'approved_by'=>$user['id'],'status'=>2);
            $result=$this->member->updateunstakerequest($data,$where);
            if($result['status']===true){
                echo json_encode(['status'=>true,'message'=>$result['message']]);
            }
            else{
                echo json_encode(['status'=>false,'message'=>$result['message']]);
            }
        }
        elseif(!empty($request) && $request['status']==1){
            echo json_encode(['status'=>true,'message'=>'Unstake Request Already Approved!']);
        }
        elseif(!empty($request) && $request['status']==2){
            echo json_encode(['status'=>true,'message'=>'Unstake Request Already Rejected!']);
        }
        else{
            echo json_encode(['status'=>false,'message'=>'Please Try Again!']);
        }
	}
	
	public function logerror(){
        $id=$this->input->post('id');
        $response=$this->input->post('response');
        if(is_array($response)){
            $response=json_encode($response);
        }
        $data=array('req_id'=>$id,'response'=>$response,'type'=>'withdrawal','added_on'=>date('Y-m-d H:i:s'));
        $this->db->insert('errors',$data);
	}
	
}