<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Income extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        checklogin();
        // Load global models, check auth, etc.
    }
    
    public function index(){
        $data=['title'=>'Home'];
        //$this->template->load('pages','home',$data);
    }
    
    public function reward(){
        $data=['title'=>'Reward'];
        $data['user']=getuser();
        $data['ranks']=$this->db->get('ranks')->result_array();
        $this->template->load('income','reward',$data);
    }
    
    public function club(){
        $data=['title'=>'Clubs'];
        $data['user']=getuser();
        $data['ranks']=$this->db->get('clubs')->result_array();
        $this->template->load('income','club',$data);
    }
    
	public function directincome(){
        $data['title']="Direct Income";
        $data['tabulator']=true;
        $this->template->load('income','directincome',$data);      
    }
    
	public function levelincome(){
        $data['title']="Level Income";
        $data['tabulator']=true;
        $this->template->load('income','levelincome',$data);      
    }
    
	public function roiincome(){
        $data['title']="ROI Income";
        $data['tabulator']=true;
        $this->template->load('wallet','roiincome',$data);      
    }
    
	public function rankincome(){
        $data['title']="Rank Income";
        $data['tabulator']=true;
        $this->template->load('income','rankincome',$data);      
    }
    
	public function royaltyincome(){
        $data['title']="Royalty Income";
        $data['tabulator']=true;
        $this->template->load('income','royaltyincome',$data);      
    }
    
	public function ultraclubincome(){
        $data['title']="Ultra Club Income";
        $data['tabulator']=true;
        $this->template->load('wallet','ultraclubincome',$data);      
    }
    
	public function getincome(){
        $type=$this->input->get('type');
        $user=getuser();
        $incomes=$this->income->getincome(['t1.regid'=>$user['id'],'t1.type'=>$type]);
        $settings=$this->setting->getsettings(['name'=>'coin_rate'],'single');
        $rate=$settings['value'];
        if(!empty($incomes)){
            foreach($incomes as $key=>$income){
                if($type=='reward' || $type=='royalty'){
                    $incomes[$key]['amount_usdt']=round($income['amount']*$income['rate'],2);
                }
                else{
                    $incomes[$key]['amount_usdt']=round($income['amount']*$rate,2);
                }
            }
        }
        //$incomes=[];
        echo json_encode($incomes);  
    }
    
}