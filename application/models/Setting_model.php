<?php
class Setting_model extends CI_Model{
	
	function __construct(){
		parent::__construct(); 
		$this->db->db_debug = false;
	}
    
    public function generatesettings(){
        $datetime=date('Y-m-d H:i:s');
        $where=array();
        $data=array();
        $where['name']='coin_rate';
        if($this->db->get_where('settings',$where)->num_rows()==0){
            $data[]=array('name'=>'coin_rate','title'=>'Coin Rate','type'=>'Text',
                      'value'=>'0','status'=>1,'added_on'=>$datetime,'updated_on'=>$datetime);
        }
        if(!empty($data)){
            $this->db->insert_batch('settings',$data);
        }
    }
    
    public function getsettings($where=array(),$type="all"){
        $this->db->where($where);
        $query=$this->db->get("settings");
        if($type=='all'){
            $array=$query->result_array();
        }
        else{
            $array=$query->unbuffered_row('array');
        }
        return $array;
    }
    
    public function updatesetting($data){
        $id=$data['id'];
        unset($data['id']);
        $where=array("id"=>$id);
        $getsetting=$this->db->get_where('settings',$where);
        if($getsetting->num_rows()==1){
            $setting=$getsetting->unbuffered_row('array');
            if(!isset($data['updated_on'])){
                $data['updated_on']=date('Y-m-d H:i:s');
            }
            if($this->db->update("settings",$data,$where)){
                return array("status"=>true,"message"=>$setting['title']." Updated Successfully!");
            }
            else{
                $error=$this->db->error();
                return array("status"=>false,"message"=>$error['message']);
            }
        }
        else{
            return array("status"=>false,"message"=>"Setting Not Available!");
        }
    }
    
}