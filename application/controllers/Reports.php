<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        // Load global models, check auth, etc.
        if($this->session->role!='admin'){ redirect('/'); }
    }
    
    public function index(){
        if($this->input->get('type')===NULL){
            $data=['title'=>'Joining Report'];
            $data['tabulator']=true;
            $this->template->load('reports','joining',$data); 
        }
        else{
            $settings=$this->setting->getsettings(['name'=>'coin_rate'],'single');
            $tokenrate=$settings['value'];
            $user=getuser();
            $members=$this->member->getmembers($user['id']);
            $this->db->group_by('regid');
            $this->db->select('*,sum(amount) as amount');
            $investments=$this->db->get_where('investments',['status'=>1])->result_array();
            $regids=!empty($investments)?array_column($investments,'regid'):array();
            if(!empty($members)){
                foreach($members as $key=>$member){
                    $index=array_search($member['regid'],$regids);
                    if($index===false){
                        $members[$key]['package']=0;
                    }
                    else{
                        $amount=$investments[$index]['amount'];
                        if($investments[$index]['old']==1){
                            //$amount=$tokenrate>0?$amount/$tokenrate:0;
                        }
                        $members[$key]['amount']=$amount;//round($amount*$tokenrate,6);
                        $members[$key]['package']=$this->amount->toDecimal($amount,false,6);//round($amount*$tokenrate,6);
                        $members[$key]['amount_usdt']=$this->amount->toDecimal(($amount*$tokenrate),false,6);
                    }
                }
            }
            echo json_encode($members);  
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
    
}