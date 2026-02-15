<?php
class Epin_model extends CI_Model{
	
	function __construct(){
		parent::__construct(); 
		$this->db->db_debug = false;
	}
	
	public function generateepin($data){
		$quantity=$data['quantity'];
		$type=!empty($data['type'])?$data['type']:'transfer';
		for($i=0;$i<$quantity;$i++){
			$epindata=array();
			$epindata['epin']=generateepin();
			$epindata['regid']=$data['regid'];
			$epindata['package_id']=$data['package_id'];
			$epindata['added_on']=date("Y-m-d H:i:s");
			if($this->db->insert("epins",$epindata)){
				$transferdata=array();
				$transferdata['type']=$type;
				$transferdata['epin_id']=$this->db->insert_id();
				$transferdata['reg_from']=0;
				$transferdata['reg_to']=$data['regid'];
				$transferdata['added_on']=date("Y-m-d H:i:s");
				$this->db->insert("epin_transfer",$transferdata);
			}
		}
		return true;
	}
	
	public function getepin($where=array(),$type='all'){
		$this->db->select("t1.*, t2.package,t2.amount");
		$this->db->from('epins t1');
		$this->db->join('packages t2','t1.package_id=t2.id','Left');
		$this->db->where($where);
		$query=$this->db->get();
		if($type=='all'){ $array=$query->result_array(); }
		else{ $array=$query->unbuffered_row('array'); }
		return $array;
	}
	
	public function getepinbystatus($status,$regid){
		$where['t1.status']=$status;
		$where['t1.regid']=$regid;
		$epins=$this->getepin($where);
		if($status==1){
			if(is_array($epins)){
				foreach($epins as $key=>$epin){
					$usedby=$this->db->get_where("epin_used",array("epin_id"=>$epin['id']))->unbuffered_row('array');
					$user=$this->db->get_where("users",array("id"=>$usedby['used_by']))->unbuffered_row('array');
					$epins[$key]['used_on']=$usedby['added_on'];
					$epins[$key]['username']=$user['username'];
					$epins[$key]['name']=$user['name'];
				}
			}
		}
		return $epins;
	}
	
	public function getepinrequests($where=array(),$type='all'){
		$this->db->select("t1.*, t2.username,t2.name,t3.package,t3.amount as package_amount");
		$this->db->from('epin_requests t1');
		$this->db->join('users t2','t1.regid=t2.id','Left');
		$this->db->join('packages t3','t1.package_id=t3.id','Left');
		$this->db->where($where);
		$query=$this->db->get();
		if($type=='all'){ $array=$query->result_array(); }
		else{ $array=$query->unbuffered_row('array'); }
		return $array;
	}
	
	public function getmemberrequests($where){
		$this->db->select("t1.*, t2.package");
		$this->db->from('epin_requests t1');
		$this->db->join('packages t2','t1.package_id=t2.id','Left');
		$this->db->where($where);
		$query=$this->db->get();
		$array=$query->result_array(); 
		return $array;
	}
	
	//SELECT `id`, `reg_from`, `reg_to`, `epin_id`, `added_on` FROM `epin_transfer` WHERE 1
	public function transferepin($data){
		$quantity=$data['quantity'];
        $getuser=$this->account->getuser(array("md5(id)"=>$this->session->userdata('user')));
        $reg_from=$getuser['user']['id'];
		$where=array("package_id"=>$data['package_id'],"regid"=>$reg_from,"status"=>"0");
		$this->db->limit($quantity);
		$epins=$this->db->get_where("epins",$where)->result_array();
		$epin_ids=$transferdata=array();
		foreach($epins as $epin){
			$transferdata[]=array('epin_id'=>$epin['id'],'reg_from'=>$reg_from,'reg_to'=>$data['reg_to'],'added_on'=>date("Y-m-d H:i:s"));
			
			$epin_ids[]=$epin['id'];
			
		}
		$this->db->insert_batch("epin_transfer",$transferdata);
		$this->db->where_in("id",$epin_ids);
		$update=$this->db->update("epins",array("regid"=>$data['reg_to']));
	}
	
	public function gethistory($regid='',$type="transfer"){
		if($regid!='' && $type=='transfer'){
			$this->db->where('t1.reg_from', $regid);
		}
		elseif($regid!='' && $type=='generate'){
			$this->db->where('t1.reg_to', $regid);
		}
		if($type=="transfer"){ $this->db->where("t1.reg_from!=",0); }
		else{ $this->db->where("t1.reg_from",0); }
		$this->db->select("t1.*, t2.epin, t3.package,t3.amount,t2.status");
		$this->db->from('epin_transfer t1');
		$this->db->join('epins t2','t1.epin_id=t2.id','Left');
		$this->db->join('packages t3','t2.package_id=t3.id','Left');
		$query=$this->db->get();
		//echo $this->db->last_query();
		$array=$query->result_array(); 
		if(is_array($array)){
			foreach($array as $key=>$value){
				if($value['reg_from']!=0){
					$from=$this->db->get_where("users",array("id"=>$value['reg_from']))->unbuffered_row('array');
                    if(empty($from)){
                        $from['username']='';
                        $from['name']='';
                    }
					$array[$key]['from']=$from['username']." ($from[name])";
				}
				else{
					$array[$key]['from']="E-Pin Generated!";
				}
				$to=$this->db->get_where("users",array("id"=>$value['reg_to']))->unbuffered_row('array');
                if(empty($to)){
                    $to['username']='';
                    $to['name']='';
                }
				$array[$key]['to_username']=$to['username'];
				$array[$key]['to_name']=$to['name'];
			}
		}
		return $array;
	}
	
	public function requestepin($data){
		$result=array();
		if($this->db->insert("epin_requests",$data)){
			$result['status']= true;
			$result['request_id']=$this->db->insert_id();
		}
		else{
			$result['status']= true;
			$result['err']= $this->db->error();
		}
		return $result;
	}
	
	public function updaterequest($id,$status=1,$reason=''){
		$date=date('Y-m-d');
        $data=array("status"=>$status,"reason"=>$reason,"approve_date"=>$date,'updated_on'=>date('Y-m-d H:i:s'));
		if($this->db->update("epin_requests",$data,array("id"=>$id))){
            if($status==1){
                $epindata=$this->db->get_where('epins',"regid in (SELECT regid from ".TP."epin_requests where id='$id') and status='0'")->unbuffered_row('array');
                $epin=$epindata['epin'];
                $regid=$epindata['regid'];
				$package=$this->package->getpackages("id in (SELECT package_id from ".TP."epins where epin='$epin')","single");
				$activatedata['package_id']=$package['id'];
				$activatedata['epin']=$epin;
				$activatedata['regid']=$regid;
				$this->member->activatemember($activatedata);
            }
			return true;
		}
		else{
			return $this->db->error();
		}
	}
	
	public function generatedepins(){
		$this->db->select("t2.username,t2.name,count(t1.id) as epins");
		$this->db->from('epins t1');
		$this->db->join('users t2','t1.regid=t2.id','Left');
		$this->db->order_by("t1.regid");
		$this->db->group_by("t1.regid");
		$query=$this->db->get();
		$array=$query->result_array(); 
		return $array;
	}
	
	public function usedepins(){
		$this->db->select("t2.username,t2.name,count(t1.id) as epins");
		$this->db->from('epins t1');
		$this->db->join('users t2','t1.regid=t2.id','Left');
		$this->db->where("t1.status='1'");
		$this->db->order_by("t1.regid");
		$this->db->group_by("t1.regid");
		$query=$this->db->get();
		$array=$query->result_array(); 
		return $array;
	}
	
	public function updatepinstatus($epin,$regid,$status='activate'){
		$added_on=date('Y-m-d H:i:s');
		$update=$this->db->update("epins",array("status"=>"1"),array("epin"=>$epin));
        if($status=='activate'){
		  $update=$this->db->update("members",array("epin"=>$epin),array("regid"=>$regid));
        }
		$epin_id=$this->db->get_where("epins",array("epin"=>$epin))->unbuffered_row()->id;
		$usedpindata=array("epin_id"=>$epin_id,"used_by"=>$regid,"added_on"=>$added_on);
		$this->db->insert("epin_used",$usedpindata);
		return $added_on;
	}
	
}