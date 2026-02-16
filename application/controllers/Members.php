<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Members extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        // Load global models, check auth, etc.
    }
    
    public function index(){
        $data=['title'=>'Home'];
        //$this->template->load('pages','home',$data);
    }
    
    public function modify(){
        if($this->session->role!='admin'){ redirect('/'); }
        $data=['title'=>'Member Modify'];
        $this->template->load('members','modify',$data);
    }
    
    public function changepassword(){
        if($this->session->role!='admin'){ redirect('/'); }
        $data=['title'=>'Change Password'];
        $this->template->load('members','changepassword',$data);
    }
    
    public function coinrate(){
        if($this->session->role!='admin'){ redirect('/'); }
        $data=['title'=>'Coin Rate'];
        $this->template->load('members','coinrate',$data);
    }
    
    public function entertomember(){
        if($this->session->role!='admin'){ redirect('/'); }
        $data=['title'=>'Enter To Member'];
        $this->template->load('members','entertomember',$data);
    }
    
    public function directmembers(){
        checklogin();
        $data=['title'=>'Direct Members'];
        $data['datatable']=true;
        $data['type']='direct';
        $user=getuser();
        $data['members']=$this->member->getdirectmembers($user['id']);
        $this->template->load('members','memberlist',$data); 
    }
    
    public function memberlist(){
        checklogin();
        $data=['title'=>'Downline Members'];
        $data['datatable']=true;
        $data['type']='downline';
        $user=getuser();
        $data['members']=$this->member->getmembers($user['id']);
        $this->template->load('members','memberlist',$data); 
    }
    
	public function getmembers(){
        $members=array();
        $type=$this->input->get('type');
        if($type=='direct'){
            $user=getuser();
            $members=$this->member->getdirectmembers($user['id']);
        }
        elseif($type=='downline'){
            $user=getuser();
            $members=$this->member->getmembers($user['id']);
        }
        echo json_encode($members);
    }
    
    public function legbusiness(){
        checklogin();
        $data=['title'=>'Leg Business'];
        $data['tabulator']=true;
        $user=getuser();
        $legbusiness=$this->income->get_leg_business($user['id']);
        
        // Sort legs by business descending
        usort($legbusiness, function($a, $b) {
            return $b['business'] <=> $a['business'];
        });

        // Get top 2 legs
        $top_legs = array_slice($legbusiness, 0, 2);
        
        $data['top_legs']=$top_legs;
        $data['legbusiness']=$legbusiness;
        $this->template->load('members','legbusiness',$data); 
    }
    
	public function getlegbusiness(){
        $members=array();
        $leg=$this->input->get('leg');
        $members=array();
        if(!empty($leg)){
            $user=getuser();
            $legbusiness=$this->income->get_leg_business($user['id']);

            // Sort legs by business descending
            usort($legbusiness, function($a, $b) {
                return $b['business'] <=> $a['business'];
            });

            // Get top 2 legs
            $top_legs = array_slice($legbusiness, 0, 2);
            
            if($leg=='leg-1' && !empty($top_legs[0])){
                $members=$this->member->getteaminvestments($top_legs[0]['regid'],true);
            }
            elseif($leg=='leg-2' && !empty($top_legs[1])){
                $members=$this->member->getteaminvestments($top_legs[1]['regid'],true);
            }
            if(!empty($members)){
                $settings=$this->setting->getsettings(['name'=>'coin_rate'],'single');
                $rate=$settings['value'];
                foreach($members as $key=>$member){
                    $members[$key]['amount']=$this->amount->toDecimal($member['amount'],false,6);
                    $members[$key]['amount_usdt']=$this->amount->toDecimal($member['amount_usdt'],false,6);
                    $members[$key]['current_amount']=$this->amount->toDecimal($member['current_amount'],false,6);
                    $members[$key]['current_amount_usdt']=$this->amount->toDecimal(($member['current_amount']*$rate),false,6);
                }
            }
        }
        echo json_encode($members);
    }
    
	public function getrefid(){
        $username=$this->input->post('username');
		$status=$this->input->post('status');
		$member=$this->member->getmemberid($username,$status);
        $member['name']=$member['regid']==0?'Sponsor ID not Available!':$member['name'];
        if($member['regid']==1 && $this->session->role!='admin'){
            $member['regid']=0;
            $member['name']='Sponsor ID not Available!';
            
        }
        $member['regid']=$member['regid']!=0?md5('regid-'.$member['regid']):0;
		echo json_encode($member);
	}
	 
	public function getmemberid(){
        $username=$this->input->post('username');
		$status=$this->input->post('status');
		$member=$this->member->getmemberid($username,$status);
        $member['name']=$member['regid']==0?'Member ID not Available!':$member['name'];
        if($member['regid']<=1){
            $member['regid']=0;
            $member['name']='Member ID not Available!';
            
        }
        if($member['regid']!=0){
            $data['member']=$this->member->getmemberdetails($member['regid']);
            $member['wallet_address']=$data['member']['wallet_address'];
            //$member['balance']=getUSDTBalance($member['wallet_address']);
        }
        $member['regid']=$member['regid']!=0?md5('regid-'.$member['regid']):0;
		echo json_encode($member);
	}
	 
    public function memberdashboard(){
        $data=$this->input->post();
        $member=$this->member->getmemberid($data['member_id'],'all');
        if($member['regid']==0){
            $this->session->set_flashdata('err_msg',$member['name']);
            redirect($_SERVER['HTTP_REFERER']);
        }
        else{
            $username=md5('username-'.$data['member_id']);
            redirect('login/userlogin/'.$username);
        }
    }
}