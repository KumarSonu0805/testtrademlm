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
        $data['title']="New Withdrawal";
        $data['user']=getuser();
        $incomes=$this->income->getallincome($data['user']);
        $data['member']=$this->member->getmemberdetails($data['user']['id']);
        $data['avl_balance']=getavlbalance($data['user']);
        $this->template->load('wallet','withdrawal',$data);
    }

	public function fundrequests(){
        if($this->input->get('type')===NULL){
            $data['title']="Deposit Requests";
            $data['tabulator']=true;
            $data['alertify']=true;
            $this->template->load('wallet','fundrequests',$data);          
        }
        else{
            $requests=$this->member->getdepositrequests(['t1.status'=>0]);
            $requests=array_map(function($item) {
                if(isset($item['id'])){
                    $item['id']=md5('deposit-request-id-'.$item['id']);
                    $item['amount']=round($item['amount'],2);
                    $item['screenshot']=file_url($item['screenshot']);
                }
                return $item;
            },$requests);
            echo json_encode($requests);
        }      
    }
    
	public function approvedfundrequests(){
        if($this->input->get('type')===NULL){
            $data['title']="Approved Deposit Requests";
            $data['tabulator']=true;
            $data['alertify']=true;
            $this->template->load('wallet','approvedfundrequests',$data);          
        }
        else{
            $requests=$this->member->getdepositrequests(['t1.status'=>1]);
            $requests=array_map(function($item) {
                if(isset($item['id'])){
                    $item['id']=md5('deposit-request-id-'.$item['id']);
                    $item['amount']=round($item['amount'],2);
                    $item['screenshot']=file_url($item['screenshot']);
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
    
	public function withdrawalrequests(){
        if($this->input->get('type')===NULL){
            $data['title']="Withdrawal Requests";
            $data['tabulator']=true;
            $data['alertify']=true;
            $this->template->load('wallet','withdrawalrequests',$data);          
        }
        else{
            $requests=$this->member->getmemberrequests("t1.status='0' or t1.status='3'");
            $requests=array_map(function($item) {
                if(isset($item['id'])){
                    $item['id']=md5('withdrawal-request-id-'.$item['id']);
                }
                return $item;
            },$requests);
            echo json_encode($requests);
        }      
    }
    
	public function manualwithdrawalrequests(){
        if($this->input->get('type')===NULL){
            $data['title']="Withdrawal Requests";
            $data['tabulator']=true;
            $data['alertify']=true;
            $this->template->load('wallet','withdrawalreq',$data);          
        }
        else{
            $requests=$this->member->getmemberrequests("t1.status='0' or t1.status='3'");
            $requests=array_map(function($item) {
                if(isset($item['id'])){
                    $item['id']=md5('withdrawal-request-id-'.$item['id']);
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
	
	public function approvefundrequest(){
		$id=$this->input->post('id');
		//$response=$this->input->post('response');
        $request=$this->member->getdepositrequests(["md5(concat('deposit-request-id-',t1.id))"=>$id],'single');
        if(!empty($request) && $request['status']==0){
            $regid=$request['regid'];
            $where=array('id'=>$request['id']);
            $user=getuser();
            $data=array('approve_date'=>date('Y-m-d H:i:s'),'status'=>1,'updated_on'=>date('Y-m-d H:i:s'));
            $result=$this->db->update('investments',$data,$where);
            if($result){
                $member=$this->member->getmemberdetails($regid);
                if($this->db->get_where("investments",array("regid"=>$regid,'status'=>1))->num_rows()==1 && $member['status']==0){
                    $updata=array('package'=>$request['amount'],'activation_date'=>date('Y-m-d'),'activation_time'=>date('H:i:s'),'status'=>1);
                    $this->db->update('members',$updata,['regid'=>$regid]);
                }
                echo json_encode(['status'=>true,'message'=>"Deposit Request Approved Successfully"]);
            }
            else{
                $result=$this->db->error();
                echo json_encode(['status'=>false,'message'=>$result['message']]);
            }
        }
        elseif(!empty($request) && $request['status']==1){
            echo json_encode(['status'=>true,'message'=>'Deposit Request Already Approved!']);
        }
        elseif(!empty($request) && $request['status']==2){
            echo json_encode(['status'=>true,'message'=>'Deposit Request Already Rejected!']);
        }
        else{
            echo json_encode(['status'=>false,'message'=>'Please Try Again!']);
        }
	}
	
	public function rejectfundrequest(){
		$id=$this->input->post('id');
		$response=$this->input->post('response');
        $request=$this->member->getdepositrequests(["md5(concat('deposit-request-id-',t1.id))"=>$id],'single');
        if(!empty($request) && $request['status']==0){
            $where=array('id'=>$request['id']);
            $user=getuser();
            $data=array('approve_date'=>date('Y-m-d H:i:s'),'response'=>$response,'status'=>2,'updated_on'=>date('Y-m-d H:i:s'));
            $result=$this->db->update('investments',$data,$where);
            if($result){
                echo json_encode(['status'=>true,'message'=>"Deposit Request Rejected Successfully"]);
            }
            else{
                $result=$this->db->error();
                echo json_encode(['status'=>false,'message'=>$result['message']]);
            }
        }
        elseif(!empty($request) && $request['status']==1){
            echo json_encode(['status'=>true,'message'=>'Deposit Request Already Approved!']);
        }
        elseif(!empty($request) && $request['status']==2){
            echo json_encode(['status'=>true,'message'=>'Deposit Request Already Rejected!']);
        }
        else{
            echo json_encode(['status'=>false,'message'=>'Please Try Again!']);
        }
	}
	
	public function createwithdrawal(){
		$id=$this->input->post('id');
        $request=$this->member->getwithdrawalrequest(["md5(concat('withdrawal-request-id-',t1.id))"=>$id],'single');
        if(!empty($request) && $request['status']==0){
            $where=array('id'=>$request['id']);
            if(isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST']=='localhost'){
                $response=file_get_contents('./assets/samples/balance_error.json');
                $response=file_get_contents('./assets/samples/create_error.json');
                $response=file_get_contents('./assets/samples/create_success.json');
                $response=json_decode($response,true);
            }
            else{
                $authdata=["email"=>"aearninga@gmail.com",
                            "password"=>"65Lzs6z@g7YCvkr"] ;
                $data = [
		                "ipn_callback_url"=> "https://nowpayments.io",
		                "withdrawals"=> [
		                    [
		                        "address"=> $request['wallet_address'],
		                        "currency"=> 'usdtbsc',
		                        "amount"=> $request['payable_amount'],
		                        "ipn_callback_url"=> "https://nowpayments.io"
		                    ]
		                ]
		            ];
		
		          $response=createPayout($authdata,$data);
            }
            if(isset($response['statusCode']) && $response['statusCode']==400){
                echo json_encode(['status'=>false,'message'=>$response['message']]);
            }
            else{
                $data=array('response'=>json_encode($response),'status'=>3);
                $result=$this->member->updatewithdrawalrequest($data,$where);
                if($result['status']===true){
                    echo json_encode(['status'=>true,'message'=>"Done"]);
                }
                else{
                    echo json_encode(['status'=>false,'message'=>$result['message']]);
                }
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
	
	public function approvewithdrawal(){
		$id=$this->input->post('id');
		$verification_code=$this->input->post('response');
        $request=$this->member->getwithdrawalrequest(["md5(concat('withdrawal-request-id-',t1.id))"=>$id],'single');
        if(!empty($request) && $request['status']==3){
            $where=array('id'=>$request['id']);
            $req_response=json_decode($request['response'],true);
            $withdrawal_id=$req_response['id'];
            if(isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST']=='localhost'){
                $response=file_get_contents('./assets/samples/otp_error.json');
                $response=file_get_contents('./assets/samples/otp_success.json');
                $response=json_decode($response,true);
            }
            else{
                $authdata=["email"=>"aearninga@gmail.com",
                            "password"=>"65Lzs6z@g7YCvkr"] ;
                $data=["withdrawal_id"=> $withdrawal_id, "verification_code"=> $verification_code];
                $response=verifyPayout($authdata,$data);
            }
            if(!empty($response['error'])){
                echo json_encode(['status'=>false,'message'=>$response['error']]);
            }
            else{
                $user=getuser();
                $response=array('req'=>$req_response,'res'=>$response);
                $data=array('response'=>json_encode($response),'approve_date'=>date('Y-m-d H:i:s'),'approved_by'=>$user['id'],'status'=>1);
                $result=$this->member->updatewithdrawalrequest($data,$where);
                if($result['status']===true){
                    echo json_encode(['status'=>true,'message'=>"Withdrawal Request Approved Successfully"]);
                }
                else{
                    echo json_encode(['status'=>false,'message'=>$result['message']]);
                }
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
	
	public function manualapprovewithdrawal(){
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
        if(!empty($request) && ($request['status']==0 || $request['status']==3)){
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
