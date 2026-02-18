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
    
    public function dailyincome(){
        $data=['title'=>'Daily Income'];
        $data['tabulator']=true;
        $this->template->load('income','dailyincome',$data);
    }
    
	public function sponsorincome(){
        $data['title']="Sponsor Income";
        $data['tabulator']=true;
        $this->template->load('income','sponsorincome',$data);      
    }
    
	public function levelincome(){
        $data['title']="Level Income";
        $data['tabulator']=true;
        $this->template->load('income','levelincome',$data);      
    }
    
	public function salaryincome(){
        $data['title']="Salary Income";
        $data['tabulator']=true;
        $this->template->load('income','salaryincome',$data);      
    }
    
	public function rewardincome(){
        $data['title']="Reward Income";
        $data['tabulator']=true;
        $this->template->load('income','rewardincome',$data);      
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