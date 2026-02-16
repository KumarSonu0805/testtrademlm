<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contract extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        // Load global models, check auth, etc.
        checklogin();
        if($this->session->role!='admin'){
            redirect('home/');
        }
    }
    
    public function index(){
        $data=['title'=>'Contract Details'];
        $user=getuser();
        $this->template->load('contract','details',$data);
    }
    
    public function transfercontracts(){
        $data=['title'=>'Transfer Old Staking'];
        $user=getuser();
        $investments=array();
        $this->db->select('t1.*,(t1.rate*t1.amount) as amount_usdt,t2.username,t2.name,t3.wallet_address');
        $this->db->from('investments t1');
        $this->db->join('users t2','t1.regid=t2.id');
        $this->db->join('members t3','t1.regid=t3.regid');
        $this->db->where(['t1.old'=>1,'t1.status'=>1,'t1.amount>'=>0]);
        $query=$this->db->get();
        if($query->num_rows()>0){
            $investments=$query->result_array();
        }
        $data['investments']=$investments;
        $this->template->load('contract','transfercontracts',$data);
    }
    
    public function transfer(){
        $staked=$this->input->post('staked');
        if(!empty($staked)){
            $settings=$this->setting->getsettings(['name'=>'coin_rate'],'single');
            $rate=$settings['value'];
            $staked=explode("\n",$staked);
            //print_pre($staked);
            foreach($staked as $row){
                $row=explode(",",$row);
                $address=trim($row[0]);
                $amount=trim($row[1]);
                
                //$data=$this->input->post();
                $getmember=$this->db->get_where('members',['wallet_address'=>$address]);
                if($getmember->num_rows()>0){
                    $member=$getmember->unbuffered_row('array');

                    $data=array('regid'=>$member['regid'],'date'=>date('Y-m-d'),'rate'=>$rate,'amount'=>$amount);
                    $result=$this->member->savestake($data);
                    if($result['status']===true){
                        //unstake here
                        $data=array('status'=>0,'updated_on'=>date('Y-m-d H:i:s'));
                        $this->db->update('investments',$data,['old'=>1,'status'=>1,'regid'=>$member['regid']]);
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
        }
    }
    
    public function history2(){
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
    
}