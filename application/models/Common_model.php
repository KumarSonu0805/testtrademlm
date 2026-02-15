<?php
class Common_model extends CI_Model{
	
	function __construct(){
		parent::__construct(); 
		$this->db->db_debug = false;
	}
    
    public function getbanks($where=array(),$type="all"){
        $query=$this->db->get_where('banks',$where);
        if($type=='all'){
            $array=$query->result_array();
        }
        else{
            $array=$query->unbuffered_row('array');
        }
        return $array;
    }
    
    public function getclubs(){
        $array=$this->db->get('clubs')->result_array();
        return $array;
    }
    
    public function getstates($where=array(),$type="all"){
        $this->db->where(array("type"=>"State"));
        $query=$this->db->get_where('area',$where);
        if($type=='all'){
            $array=$query->result_array();
        }
        else{
            $array=$query->unbuffered_row('array');
        }
        return $array;
    }
    
    public function getdistricts($where=array(),$type="all"){
        if(!is_array($where)){
            $where=["parent_id"=>$where];
        }
        $this->db->where(array("type"=>"District"));
        $query=$this->db->get_where('area',$where);
        if($type=='all'){
            $array=$query->result_array();
        }
        else{
            $array=$query->unbuffered_row('array');
        }
        return $array;
    }
    
    public function savecontact($data){
        $data['added_on']=date('Y-m-d H:i:s');
        if($this->db->insert("contact",$data)){
            return array("status"=>true,"message"=>"Contact Info Added Successfully!");
        }
        else{
            $error=$this->db->error();
            return array("status"=>false,"message"=>$error['message']);
        }
    }
    
    public function getcontacts($where=array(),$type="all"){
        $this->db->where($where);
        $this->db->from("contact t1");
        $query=$this->db->get();
        if($type=='all'){
            $array=$query->result_array();
        }
        else{
            $array=$query->unbuffered_row('array');
        }
        return $array;
    }
    
    public function updatecontact($data,$where){
        if($this->db->update("contact",$data,$where)){
            return array("status"=>true,"message"=>"Contact Info Updated Successfully!");
        }
        else{
            $error=$this->db->error();
            return array("status"=>false,"message"=>$error['message']);
        }
    }
     
    public function savebannerimage($data){
        if($this->db->insert("banner_images",$data)){
            return array("status"=>true,"message"=>"Banner Image Added Successfully!");
        }
        else{
            $error=$this->db->error();
            return array("status"=>false,"message"=>$error['message']);
        }
    }
    
    public function getbannerimages($where=array(),$type="all"){
        $default=file_url('assets/images/default.jpg');
        $columns="t1.*,  case when t1.image='' then '$default' else concat('".file_url()."',t1.image) end as image";
        $this->db->select($columns);
        $this->db->where($where);
        $this->db->from("banner_images t1");
        $query=$this->db->get();
        if($type=='all'){
            $array=$query->result_array();
        }
        else{
            $array=$query->unbuffered_row('array');
        }
        return $array;
    }
    
    public function updatebannerimage($data,$where){
        if($this->db->update("banner_images",$data,$where)){
            return array("status"=>true,"message"=>"Banner Image Updated Successfully!");
        }
        else{
            $error=$this->db->error();
            return array("status"=>false,"message"=>$error['message']);
        }
    }
      
    public function deletebannerimage($where){
        if($this->db->delete("banner_images",$where)){
            return array("status"=>true,"message"=>"Banner Image Deleted Successfully!");
        }
        else{
            $error=$this->db->error();
            return array("status"=>false,"message"=>$error['message']);
        }
    }
    
    public function saveyoutubelink($data){
        if($this->db->insert("youtube_links",$data)){
            return array("status"=>true,"message"=>"Banner Image Added Successfully!");
        }
        else{
            $error=$this->db->error();
            return array("status"=>false,"message"=>$error['message']);
        }
    }
    
    public function getyoutubelinks($where=array(),$type="all"){
        $columns="t1.*";
        $this->db->select($columns);
        $this->db->where($where);
        $this->db->from("youtube_links t1");
        $query=$this->db->get();
        if($type=='all'){
            $array=$query->result_array();
        }
        else{
            $array=$query->unbuffered_row('array');
        }
        return $array;
    }
    
    public function updateyoutubelink($data,$where){
        if($this->db->update("youtube_links",$data,$where)){
            return array("status"=>true,"message"=>"Banner Image Updated Successfully!");
        }
        else{
            $error=$this->db->error();
            return array("status"=>false,"message"=>$error['message']);
        }
    }
      
    public function homedata($regid){
        $this->db->group_by('remarks');
        $this->db->select("sum(amount) as income,remarks");
        $incomes=$this->db->get_where("wallet",array("regid"=>$regid))->result_array();
        
        $this->db->select_sum("amount","totalincome");
        $totalincome=$this->db->get_where("wallet",array("regid"=>$regid))->unbuffered_row()->totalincome;
        if($totalincome===NULL){ $totalincome=0; }
        else{ $totalincome=round($totalincome,2); }
        
        $this->db->select_sum("amount","withdrawals");
        $withdrawals=$this->db->get_where("withdrawals",array("regid"=>$regid,"status"=>1))->unbuffered_row()->withdrawals;
        if($withdrawals===NULL){ $withdrawals=0; }
        else{ $withdrawals=round($withdrawals,2); }
        
        $where=array("regid"=>$regid,"status!="=>2,'type'=>'ewallet');
        $this->db->select_sum('amount','amount');
        $epingeneration=$this->db->get_where("epin_requests",$where)->unbuffered_row()->amount;
        if($epingeneration==NULL){ $epingeneration=0; }
        
        $this->db->select_sum("amount","directincome");
        $directincome=$this->db->get_where("wallet",array("regid"=>$regid,"remarks"=>"Level 1 Income"))->unbuffered_row()->directincome;
        if($directincome===NULL){ $directincome=0; }
        else{ $directincome=round($directincome,2); }
        
        $this->db->select_sum("amount","roiincome");
        $roiincome=$this->db->get_where("wallet",array("regid"=>$regid,"remarks"=>"ROI Income"))->unbuffered_row()->roiincome;
        if($roiincome===NULL){ $roiincome=0; }
        else{ $roiincome=round($roiincome,2); }
        
        $this->db->select_sum("amount","levelincome");
        $levelincome=$this->db->get_where("wallet","regid='$regid' and remarks like 'Level%' and level_id='1'")->unbuffered_row()->levelincome;
        if($levelincome===NULL){ $levelincome=0; }
        else{ $levelincome=round($levelincome,2); }
        
        $this->db->select_sum("amount","totaldeposit");
        $totaldeposit=$this->db->get_where("deposits","regid='$regid' and status='1'")->unbuffered_row()->totaldeposit;
        if($totaldeposit===NULL){ $totaldeposit=0; }
        else{ $totaldeposit=round($totaldeposit,2); }
        
        $epin=$this->epin->getepin("t1.id in (SELECT epin_id from ".TP."epin_used where used_by='$regid')",'single');
        if(!empty($epin)){
            $totaldeposit+=$epin['amount'];
        }
        $this->db->select_sum("amount");
        $transferred=$this->db->get_where("wallet_transfers","reg_from='$regid'")->unbuffered_row()->amount;
        if($transferred===NULL){ $transferred=0; }
        else{ $transferred=round($transferred,2); }
        
        $this->db->select_sum("final_amount","amount");
        $received=$this->db->get_where("wallet_transfers","reg_to='$regid'")->unbuffered_row()->amount;
        if($received===NULL){ $received=0; }
        else{ $received=round($received,2); }
        
        $used=$this->db->get_where('epins',['regid'=>$regid,'status'=>1])->num_rows();
        $unused=$this->db->get_where('epins',['regid'=>$regid,'status'=>0])->num_rows();
        
        $result['totalincome']="$totalincome";
        $result['withdrawals']="$withdrawals";
        $result['epingeneration']="$epingeneration";
        $result['levelincome']="$levelincome";
        $result['directincome']="$directincome";
        $result['roiincome']="$roiincome";
        $result['incomes']=$incomes;
        $result['totaldeposit']="$totaldeposit";
        $result['used']="0";
        $result['unused']="0";
        $result['transferred']="$transferred";
        $result['received']="$received";
        $balance=$totalincome-$withdrawals-$epingeneration+$received-$transferred;
        $result['balance']="$balance";
        return $result;
    }
    
    public function adminhomedata(){
        $total_members=$this->db->get("members")->num_rows();
        $active_members=$this->db->get_where("members",array("status"=>1))->num_rows();
        $inactive_members=$total_members-$active_members;
        
        $columns="id as package_id,0 as packagecount,package,amount";
        $this->db->select($columns);
        $packages=$this->db->get("packages")->result_array();
        
        $columns="package_id as package_id,count(*) as packagecount";
        $this->db->select($columns);
		$this->db->from("members");
		$this->db->where(array("status"=>1));
		$this->db->group_by("package_id");
		$this->db->order_by("package_id");
		$query=$this->db->get();
		$package_count=$query->result_array();
        
        $package_ids=array_column($package_count,'package_id');
        foreach($packages as $key=>$package){
            $index=array_search($package['package_id'],$package_ids);
            if($index!==false){
                $packages[$key]['packagecount']=$package_count[$index]['packagecount'];
            }
        }
        $result['total_members']=$total_members;
        $result['active_members']=$active_members;
        $result['inactive_members']=$inactive_members;
        $result['packages']=$packages;
        
        return $result;
    }
    
    public function addnotification($data){
        $data['added_on']=date('Y-m-d H:i:s');
        if($this->db->insert("notifications",$data)){
            return array("status"=>true,"message"=>"Notification Added Successfully!");
        }
        else{
            $error=$this->db->error();
            return array("status"=>false,"message"=>$error['message']);
        }
    }
    
    public function getnotification($where=array(),$type="all"){
        $this->db->where($where);
        $query=$this->db->get("notifications");
        if($type=='all'){
            $array=$query->result_array();
        }
        else{
            $array=$query->unbuffered_row('array');
        }
        return $array;
    }
    
    public function addfranchisebonus($data){
        if($this->db->get_where('franchise',array('min_quantity'=>$data['min_quantity']))->num_rows()==0){
            if($this->db->insert("franchise",$data)){
                return array("status"=>true,"message"=>"Franchise Bonus Added Successfully!");
            }
            else{
                $error=$this->db->error();
                return array("status"=>false,"message"=>$error['message']);
            }
        }
        else{
            return array("status"=>false,"message"=>"Franchise Bonus Already Added!");
        }
    }
    
    public function getfranchisebonus($where=array(),$type="all"){
        $this->db->where($where);
        $query=$this->db->get("franchise");
        if($type=='all'){
            $array=$query->result_array();
        }
        else{
            $array=$query->unbuffered_row('array');
        }
        return $array;
    }
    
    public function updatefranchisebonus($data){
        $id=$data['id'];
        unset($data['id']);
        $where=array("id"=>$id);
        if($this->db->get_where('franchise',array('min_quantity'=>$data['min_quantity'],"id!="=>$id))->num_rows()==0){
            if($this->db->update("franchise",$data,$where)){
                return array("status"=>true,"message"=>"Franchise Bonus Updated Successfully!");
            }
            else{
                $error=$this->db->error();
                return array("status"=>false,"message"=>$error['message']);
            }
        }
        else{
            return array("status"=>false,"message"=>"Franchise Bonus Already Added!");
        }
    }
    
    public function savenews($data){
        if($this->db->insert("news",$data)){
            return array("status"=>true,"message"=>"Notice Added Successfully!");
        }
        else{
            $error=$this->db->error();
            return array("status"=>false,"message"=>$error['message']);
        }
    }
    
    public function getnews($where=array(),$type="all",$order_by="id"){
        $columns="t1.*";
        $this->db->select($columns);
        $this->db->where($where);
        $this->db->order_by($order_by);
        $this->db->from("news t1");
        $query=$this->db->get();
        if($type=='all'){
            $array=$query->result_array();
        }
        else{
            $array=$query->unbuffered_row('array');
        }
        return $array;
    }
    
    public function updatenews($data,$where){
        if($this->db->update("news",$data,$where)){
            return array("status"=>true,"message"=>"Notice Updated Successfully!");
        }
        else{
            $error=$this->db->error();
            return array("status"=>false,"message"=>$error['message']);
        }
    }
      
    public function saveticket($data){
        $data['date']=date('Y-m-d');
        $data['ticket_no']=empty($data['parent_id'])?time():$data['ticket_no'];
        $data['added_on']=$data['updated_on']=date('Y-m-d H:i:s');
        if($this->db->insert("helpdesk",$data)){
            return array("status"=>true,"message"=>"Ticket Created Successfully!");
        }
        else{
            $error=$this->db->error();
            return array("status"=>false,"message"=>$error['message']);
        }
    }
    
    public function gethelpdeskmessages($where=array(),$type="all",$order_by="t1.id"){
        $columns="t1.*,t2.username,t2.name";
        $this->db->select($columns);
        $this->db->where($where);
        $this->db->order_by($order_by);
        $this->db->from("helpdesk t1");
        $this->db->join("users t2","t1.regid=t2.id");
        $query=$this->db->get();
        if($type=='all'){
            $array=$query->result_array();
        }
        else{
            $array=$query->unbuffered_row('array');
        }
        return $array;
    }
    
    public function updateticket($data,$where){
        if($this->db->update("news",$data,$where)){
            return array("status"=>true,"message"=>"Ticket Updated Successfully!");
        }
        else{
            $error=$this->db->error();
            return array("status"=>false,"message"=>$error['message']);
        }
    }
      
}
