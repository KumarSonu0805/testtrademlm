<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Staking extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        // Load global models, check auth, etc.
    }
    
    public function index(){
        checklogin();
        if($this->session->role=='admin'){
            redirect('home/');
        }
        $data=['title'=>'Staking'];
        $user=getuser();
        $history=$this->member->getstakinghistory($user['id'],1,0);
        //$history=$this->member->getmemberstakinghistory($user['id']);
        //print_pre($history,true);
        $amounts=!empty($history)?array_column($history,'amount'):array();
        $data['staked']=!empty($amounts)?array_sum($amounts):0;
        
        $history=$this->member->getstakinghistory($user['id'],1,1);
        $amounts=!empty($history)?array_column($history,'amount'):array();
        $data['oldstaked']=!empty($amounts)?array_sum($amounts):0;
        
        $this->db->select_sum('amount');
        $amount=$this->db->get_where('unstake',['regid'=>$user['id'],'status!='=>2])->unbuffered_row()->amount;
        $data['oldstaked']-=$amount;
        $this->template->load('staking','staking',$data);
    }
    
    public function history(){
        checklogin();
        if($this->session->role=='admin'){
            redirect('home/');
        }
        if($this->input->get('type')===NULL){
            $data=['title'=>'Staking'];
            $data['tabulator']=true;
            $this->template->load('staking','history',$data);
        }
        else{
            $user=getuser();
            $history=$this->member->getstakinghistory($user['id']);
            $result=array();
            $pending=array();
            if(!empty($history)){
                foreach($history as $single){
                    if(!empty($pending) && $single['added_on']>$pending['updated_on']){
                        //$result[]=$pending;
                        //$pending=array();
                    }
                    if($single['status']==1){
                        $index=strtotime($single['added_on']);
                        $result[$index][]=array('date'=>date('d-m-Y',strtotime($single['date'])),'amount'=>$single['amount'],
                                              'type'=>'Stake','updated_on'=>$single['added_on'],
                                              'timestamp'=>date('d-m-Y H:i A',strtotime($single['added_on'])));
                    }
                    if($single['status']==0){
                        $index=strtotime($single['added_on']);
                        $result[$index][]=array('date'=>date('d-m-Y',strtotime($single['date'])),'amount'=>$single['amount'],
                                              'type'=>'Stake','updated_on'=>$single['added_on'],
                                              'timestamp'=>date('d-m-Y H:i A',strtotime($single['added_on'])));
                        $index=strtotime($single['updated_on']);
                        $result[$index][]=array('date'=>date('d-m-Y',strtotime($single['updated_on'])),'amount'=>$single['amount'],'type'=>'Unstake',
                                              'updated_on'=>$single['updated_on'],
                                              'timestamp'=>date('d-m-Y H:i A',strtotime($single['updated_on'])));
                    }
                }
            }
            $array=array();
            if(!empty($result)){
                ksort($result);
                //print_pre($result,true);
                foreach($result as $single){
                    foreach($single as $row){
                        $array[]=$row;
                    }
                }
            }
            //$result = array_values($result); 
            echo json_encode($array);  
        }
    }
    
    public function savestake(){
        if($this->input->post('savestake')!==NULL){
            $rate=$this->input->post('rate');
            $amount=$this->input->post('amount');
            $user=getuser();
            $data=array('regid'=>$user['id'],'date'=>date('Y-m-d'),'rate'=>$rate,'amount'=>$amount);
            $result=$this->member->savestake($data);
            if($result['status']===true){
                $this->session->set_flashdata('msg',$result['message']);
            }
            else{
                $error=$result['message'];
                $this->session->set_flashdata('err_msg',$error);
            }
        }
        if($this->input->post('saveunstake')!==NULL){
            $user=getuser();
            $amount=$this->input->post('unstakeamount');
            $result=$this->member->saveunstake(['regid'=>$user['id'],'amount'=>$amount]);
            if($result['status']===true){
                $this->session->set_flashdata('msg',$result['message']);
            }
            else{
                $error=$result['message'];
                $this->session->set_flashdata('err_msg',$error);
            }
        }
        redirect($_SERVER['HTTP_REFERER']);
    }
    
    public function saveunstake(){
        if($this->input->post('saveunstake')!==NULL){
            $amount=$this->input->post('amount');
            $user=getuser();
            $history=$this->member->getstakinghistory($user['id'],1,1);
            if(!empty($history)){
                $staked=$history[0];
                if($staked['amount']==$amount){
                    $data=array('regid'=>$user['id'],'date'=>date('Y-m-d'),'amount'=>$amount,'inv_id'=>$staked['id']);
                    //print_pre($data,true);
                    $result=$this->member->saveunstakerequest($data);
                    if($result['status']===true){
                        $this->session->set_flashdata('msg',$result['message']);
                    }
                    else{
                        $error=$result['message'];
                        $this->session->set_flashdata('err_msg',$error);
                    }
                }
                else{
                    $this->session->set_flashdata('err_msg',"Please Try Again");
                }
            }
            else{
                $this->session->set_flashdata('err_msg',"Please Try Again");
            }
        }
        redirect($_SERVER['HTTP_REFERER']);
    }
    
    public function saveadminstake(){
        if($this->input->post('saveadminstake')!==NULL){
            $data=$this->input->post();
            $getmember=$this->db->get_where('members',['wallet_address'=>$data['wallet_address']]);
            if($getmember->num_rows()>0){
                $member=$getmember->unbuffered_row('array');
                $amount=$data['amount'];
                $rate=$data['rate'];

                $data=array('regid'=>$member['regid'],'date'=>date('Y-m-d'),'rate'=>$rate,'amount'=>$amount);
                $result=$this->member->savestake($data);
                if($result['status']===true){
                    $this->session->set_flashdata('msg',$result['message']);
                }
                else{
                    $error=$result['message'];
                    $this->session->set_flashdata('err_msg',$error);
                }
            }
            else{
                $this->session->set_flashdata('err_msg',"Member not found!");
            }
        }
        redirect($_SERVER['HTTP_REFERER']);
    }
    
    
    
}