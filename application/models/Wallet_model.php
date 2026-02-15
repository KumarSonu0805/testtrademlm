<?php
class Wallet_model extends CI_Model{
	/*
        ROI Income - 0.4% //8% monthly till 200%
        ROI Limit - 2;
        Referral Income - 5%
        Level Income 
        1-20%
        2-8%
        3-4%
        4-2%
        5-1%
        6-1%
        7-1%
        Withdrawal $20
    */
    var $status=false;
    var $level_status=true;
    var $referral=0;
    var $daily_roi=0.4;
    var $roi_limit=2;
    var $level_percent=array(1=>10,2=>1,3=>1,4=>1,5=>1);
    var $mypackages=array();
    
	function __construct(){
		parent::__construct(); 
		$this->db->db_debug = false;
	}
	
	public function checkstatus($regid,$date=NULL){
        if($date===NULL){
            $date=date('Y-m-d');
        }
        $day=date('D',strtotime($date));
        $where=array("regid"=>$regid,"status"=>'1');
        if($date!==NULL){
            $where['activation_date<=']=$date;
        }
        $getmember=$this->db->get_where("members",$where);
        if($getmember->num_rows()==0){ 
            $this->status=false; 
        }
        else{ 
            $this->status=true;
            $this->db->select('t2.*');
            $this->db->from('epin_used t1');
            $this->db->join('epins t2','t1.epin_id=t2.id');
            $this->db->where(array('t1.used_by'=>$regid,'date(t1.added_on)<='=>$date));
            $packages=$this->db->get()->result_array();
            $this->mypackages=$packages;
        }
	}
    
    public function sponsorincome($regid,$date=NULL){
        if($date===NULL){
            $date=date('Y-m-d');
        }
        if($this->status){
            $where="t1.refid='$regid' and t1.activation_date<='$date' and t1.regid not in 
                    (SELECT member_id from ".TP."wallet where regid='$regid' and deposit_id='0' and remarks='Direct Income')";
            $newdirects=$this->member->directmembers($where);
            $packages=$this->db->get('packages')->result_array();
            $package_ids=array_column($packages,'id');
            if(!empty($newdirects)){
                foreach($newdirects as $member){
                    $index=array_search($member['package_id'],$package_ids);
                    if($index!==false){
                        $packageamount=$packages[$index]['amount'];
                        $member_id=$member['regid'];
                        $amount=($packageamount*$this->referral)/100;
                        if($amount>0){
                            $data=array("date"=>$date,"type"=>"ewallet","regid"=>$regid,"member_id"=>$member_id,
                                        "package_id"=>$member['package_id'],"amount"=>$amount,"percent"=>$this->referral,
                                        "remarks"=>"Direct Income","added_on"=>date('Y-m-d H:i:s'),
                                        "updated_on"=>date('Y-m-d H:i:s'));
                            $where=array("type"=>"ewallet","regid"=>$regid,"member_id"=>$member_id,
                                         "remarks"=>"Direct Income");
                            if($this->db->get_where("wallet",$where)->num_rows()==0){
                                $this->db->insert("wallet",$data);
                            }
                        }
                    }
                }
            }
            
            $where="t3.refid='$regid' and date(t1.approved_on)<='$date' and t1.id not in 
                    (SELECT deposit_id from ".TP."wallet where regid='$regid' and remarks='Direct Income')";
            $newdeposits=$this->wallet->getdepositlistrequest($where);
            //print_pre($newdeposits,true);
            
            if(!empty($newdeposits)){
                foreach($newdeposits as $deposit){
                    $depositamount=$deposit['amount'];
                    $member_id=$deposit['regid'];
                    $amount=($depositamount*$this->referral)/100;
                    if($amount>0){
                        $data=array("date"=>$date,"type"=>"ewallet","regid"=>$regid,"member_id"=>$member_id,
                                    "deposit_id"=>$deposit['id'],"amount"=>$amount,"percent"=>$this->referral,
                                    "remarks"=>"Direct Income","added_on"=>date('Y-m-d H:i:s'),
                                    "updated_on"=>date('Y-m-d H:i:s'));
                        //print_pre($data,true);
                        $where=array("type"=>"ewallet","regid"=>$regid,"member_id"=>$member_id,"deposit_id"=>$deposit['id'],
                                     "remarks"=>"Direct Income");
                        if($this->db->get_where("wallet",$where)->num_rows()==0){
                            $this->db->insert("wallet",$data);
                        }
                    }
                }
            }
        }
    }
	
    public function roiincome($regid,$date=NULL){
		if($date===NULL){
			$date=date('Y-m-d');
		}
        $day=date('D',strtotime($date));
        if($this->status){//} && $day!='Sat' && $day!='Sun'){
            $member=$this->db->get_where("members",['regid'=>$regid])->unbuffered_row('array');
            $memberpackage=$this->db->get_where('packages',['id'=>$member['package_id']])->unbuffered_row('array');
            $this->daily_roi=$memberpackage['daily_bonus'];
            $investments=array();
            $where=array('regid'=>$regid,'package_id'=>$memberpackage['id'],'remarks'=>'ROI Income');
            $this->db->select_sum('amount');
            $packageroi=$this->db->get_where('wallet',$where)->unbuffered_row()->amount;
            $packageroi=$packageroi===NULL?0:$packageroi;
            if($packageroi<$memberpackage['amount']*$this->roi_limit){
                $investments[]=array('added_on'=>$member['activation_date'].' '.$member['activation_time'],
                                     'package_id'=>$memberpackage['id'],'deposit_id'=>0,'amount'=>$memberpackage['amount']);
            }
            $deposits=$this->wallet->getdepositlistrequest(['t1.regid'=>$regid,'date(t1.approved_on)<'=>$date,
                                                            't1.status'=>1,'t1.complete'=>0]);
            if(!empty($deposits)){
                foreach($deposits as $deposit){
                    $investments[]=array('added_on'=>$deposit['approved_on'],'package_id'=>$deposit['package_id'],'deposit_id'=>$deposit['id'],
                                         'amount'=>$deposit['amount']);
                }
            }
            //print_pre($investments,true);
            if(!empty($investments)){
                foreach($investments as $single){
                    if($date==date('Y-m-d',strtotime($single['added_on']))){ continue; }
                    $amount=($single['amount']*$this->daily_roi)/100;
                    if(!empty($single['package_id']) && empty($single['deposit_id'])){
                        if($packageroi+$amount>$single['amount']*$this->roi_limit){
                            $amount=$single['amount']*$this->roi_limit-$packageroi;
                        }
                    }
                    else{
                        $where=array('regid'=>$regid,'deposit_id'=>$single['deposit_id'],'remarks'=>'ROI Income');
                        $this->db->select_sum('amount');
                        $depositroi=$this->db->get_where('wallet',$where)->unbuffered_row()->amount;
                        if($depositroi+$amount>$single['amount']*$this->roi_limit){
                            $amount=$single['amount']*$this->roi_limit-$depositroi;
                        }
                        if($depositroi>=$single['amount']*$this->roi_limit){
                            $this->db->update('deposits',['complete'=>1,'updated_on'=>date('Y-m-d H:i:s')]);
                        }
                    }
                    if($amount>0){
                        $data=array("date"=>$date,"type"=>"ewallet","regid"=>$regid,"amount"=>$amount,
                                    "percent"=>$this->daily_roi,"remarks"=>"ROI Income",
                                    "added_on"=>date('Y-m-d H:i:s'),"updated_on"=>date('Y-m-d H:i:s'));
                        $where=array("date"=>$date,"type"=>"ewallet","regid"=>$regid,"remarks"=>"ROI Income");
                        if(!empty($single['package_id'])){
                            $data['package_id']=$single['package_id'];
                            $where['package_id']=$single['package_id'];
                        }
                        if(!empty($single['deposit_id'])){
                            $data['deposit_id']=$single['deposit_id'];
                            $where['deposit_id']=$single['deposit_id'];
                        }
                        //print_pre($data,true);
                        if($this->db->get_where("wallet",$where)->num_rows()==0){
                            $this->db->insert("wallet",$data);
                        }
                    }
                }
            }
            //print_pre($investments,true);
            
        }
    }
	
    public function levelincome($regid,$date=NULL){
		if($date===NULL){
			$date=date('Y-m-d');
		}
        if($this->status){
            $levelmembers=$this->member->levelwiseactivemembers($regid,$date);
            if(!empty($levelmembers)){
                $levels=$this->level_percent;
                $packages=$this->db->get('packages')->result_array();
                $package_ids=array_column($packages,'id');
                foreach($levelmembers as $levelmember){
                    $level_id=$levelmember['level'];
                    if(!isset($levels[$level_id])){ continue; }
                    $member_id=$levelmember['member_id'];
                    $package_id=$levelmember['package_id'];
                    $index=array_search($package_id,$package_ids);
                    if($index!==false){
                        $packageamount=$packages[$index]['amount'];
                        $percent=$levels[$level_id];
                        $amount=($packageamount*$percent)/100;
                        if($amount>0){
                            $data=array("type"=>"ewallet","date"=>$date,"regid"=>$regid,"level_id"=>$level_id,"member_id"=>$member_id,
                                        "percent"=>$percent,"amount"=>$amount,"remarks"=>"Level $level_id Income",
                                        "added_on"=>date('Y-m-d H:i:s'),"updated_on"=>date('Y-m-d H:i:s'));
                            $where=array("type"=>"ewallet","regid"=>$regid,"level_id"=>$level_id,
                                        "member_id"=>$member_id,"remarks"=>"Level $level_id Income");
                            if($this->db->get_where("wallet",$where)->num_rows()==0){
                                $this->db->insert("wallet",$data);
                            }
                            else{
                            }
                        }
                    }
                }
            }
        }
    }
	
    
	public function addcommission($regid,$date=NULL){
        $this->checkstatus($regid,$date);
		//$this->sponsorincome($regid,$date);
		$this->roiincome($regid,$date);
		$this->levelincome($regid,$date);
	}
	
	public function addallcommission($date=NULL){
		if($date===NULL){
			$date=date('Y-m-d');
		}
		$this->db->select('id');
		$where="id>1 and status=1";
        $this->db->order_by('id desc');
		$query=$this->db->get_where("users",$where);
		$array=$query->result_array();
		if(is_array($array)){
			foreach($array as $user){
				$this->addcommission($user['id'],$date);
			}
		}
	}
	
	public function getwallet($regid,$type="ewallet"){
		$result=array();
		$where=array("regid"=>$regid,"type"=>$type);
		$this->db->select_sum('amount');
		$query=$this->db->get_where("wallet",$where);
		$wallet=$query->row()->amount;
		if($wallet==NULL){ $wallet=0; }
		$result['wallet']=$wallet;
		
		$bankwithdrawal=$wallettransfers=$walletreceived=$epingeneration=$cancelled=0;
        
		if($type=="ewallet"){
			$where2=array("regid"=>$regid,"status!="=>2);
			$this->db->select_sum('amount','amount');
			$query2=$this->db->get_where("withdrawals",$where2);
			$bankwithdrawal=$query2->row()->amount;
			if($bankwithdrawal==NULL){ $bankwithdrawal=0; }
			
			$where3=array("reg_from"=>$regid);
			$this->db->select_sum('amount');
			$query3=$this->db->get_where("wallet_transfers",$where3);
			$wallettransfers=$query3->row()->amount;
			if($wallettransfers==NULL){ $wallettransfers=0; }
			
			$where4=array("reg_to"=>$regid);
			$this->db->select_sum('final_amount','amount');
			$query4=$this->db->get_where("wallet_transfers",$where4);
			$walletreceived=$query4->row()->amount;
			if($walletreceived==NULL){ $walletreceived=0; }
			
			$where2=array("regid"=>$regid,"status!="=>2,'type'=>'ewallet');
			$this->db->select_sum('amount','amount');
			$query2=$this->db->get_where("epin_requests",$where2);
			$epingeneration=$query2->row()->amount;
			if($epingeneration==NULL){ $epingeneration=0; }
		}
        
		$result['bankwithdrawal']=$bankwithdrawal;
        
		$result['epingeneration']=$epingeneration;
		
		$result['cancelled']=$cancelled;
		
		$result['wallettransfers']=$wallettransfers;
		
		$result['walletreceived']=$walletreceived;
		
		$result['total']=$wallet;
		
		$result['actualwallet']=$wallet-$bankwithdrawal-$epingeneration-$wallettransfers+$walletreceived+$cancelled;
		$result['wallet']=$result['actualwallet']-(10*$result['actualwallet'])/100;
        //print_pre($result);
		return $result;
	}
	
	public function getroiincome($regid){
        $this->db->select('package_id, deposit_id, COUNT(*) as count, SUM(amount) as amount');
        $this->db->from('dc_wallet');
        $this->db->where('regid', $regid);
        $this->db->where('remarks', 'ROI Income');
        $this->db->group_by('deposit_id');

        $query = $this->db->get();
        $array = $query->result_array();
		return $array;
	}
	
	public function getotherincome($regid){
        $this->db->select('SUM(amount) as amount');
        $this->db->from('dc_wallet');
        $this->db->where(['regid'=>$regid,'remarks!='=>'ROI Income']);
        $this->db->group_by('deposit_id');

        $query = $this->db->get();
        $array = $query->result_array();
        $amount=0;
        if(!empty($array)){
            foreach($array as $row){
                $amount+=$row['amount'];
            }
        }
		return $amount;
	}
	
	public function getepinwallet($where=array(),$type='all'){
        $where['t1.type']='epinwallet'; 
		$this->db->select("t1.*, t2.username,t2.name");
		$this->db->from('wallet t1');
		$this->db->join('users t2','t1.regid=t2.id','Left');
		$this->db->where($where);
		$query=$this->db->get();
		if($type=='all'){ $array=$query->result_array(); }
		else{ $array=$query->row_array(); }
		return $array;
	}
	
	public function getrewardwallet($where=array(),$type='all'){
        $where['t1.type']='ewallet'; 
        $where['t1.remarks']='Reward Income'; 
		$this->db->select("t1.*, t2.username,t2.name");
		$this->db->from('wallet t1');
		$this->db->join('users t2','t1.regid=t2.id','Left');
		$this->db->where($where);
		$query=$this->db->get();
		if($type=='all'){ $array=$query->result_array(); }
		else{ $array=$query->row_array(); }
		return $array;
	}
	
	public function savetowallet($data){
		if($this->db->insert("wallet",$data)){
			return array('status'=>true,'message'=>"Saved");
		}
		else{
			$err=$this->db->error();
			return array('status'=>false,'message'=>$err['message']);
		}
	}
	
	public function memberincome($regid){
        $columns="t1.*,";
        $columns.="CASE WHEN t1.level_id>1 and remarks='Royal Star Club Income' THEN 'Royal Star Club Level Income'";
        $columns.="WHEN t1.level_id>1 and remarks='Royal Diamond Club Income' THEN 'Royal Diamond Club Level Income'";
        $columns.="WHEN t1.level_id>1 and remarks='Royal Ambassador Club Income' THEN 'Royal Ambassador Club Level Income'";
        $columns.="WHEN t1.level_id>1 and remarks='Royal Universal Club Income' THEN 'Royal Universal Club Level Income'";
        $columns.="WHEN t1.percent=60 and remarks='Royalty Income' THEN 'Active Royalty Income'";
        $columns.="WHEN t1.percent=30 and remarks='Royalty Income' THEN 'Lifetime Royalty Income'";
        $columns.="ELSE remarks END as remarks,ifnull(t2.username,'--') as member_id";
        $this->db->select($columns);
		$this->db->order_by("t1.date");
        $this->db->from("wallet t1");
        $this->db->join("users t2",'t1.member_id=t2.id','left');
        $this->db->where(array("t1.regid"=>$regid,'t1.type'=>'ewallet',"t1.amount!="=>"0"));
		$array=$this->db->get()->result_array();
		return $array;
	}
	
	public function getepinwalletamount($regid){
        $where=array("regid"=>$regid,'type'=>'epinwallet',"amount!="=>"0");
		$this->db->select_sum("amount");
		$epinamount=$this->db->get_where("wallet",$where)->unbuffered_row()->amount;
        $epinamount=($epinamount===NULL)?0:$epinamount;
        
        $where=array("refid"=>$regid);
		$this->db->select_sum("amount");
		$activation_amount=$this->db->get_where("epin_activations",$where)->unbuffered_row()->amount;
        $activation_amount=($activation_amount===NULL)?0:$activation_amount;
        
        $epinamount-=$activation_amount;
        
		return $epinamount;
	}
	
	public function transferamount($data){
		if($this->db->insert("wallet_transfers",$data)){
			return true;
		}
		else{
			return $this->db->error();
		}
	}
	
	public function gethistory($regid,$type="register",$wallet="ewallet"){
		if($type=="register"){
			$where=array("reg_from"=>$regid,"type_from"=>$wallet);
		}
		else{
			$where=array("reg_to"=>$regid,"type_to"=>$wallet);
		}
		$array=$this->db->get_where("wallet_transfers",$where)->result_array();
		if(is_array($array)){
			foreach($array as $key=>$value){
				$to=$this->db->get_where("users",array("id"=>$value['reg_to']))->row_array();
				$from=$this->db->get_where("users",array("id"=>$value['reg_from']))->row_array();
				$array[$key]['to_id']=$to["username"];
				$array[$key]['to_name']=$to["name"];
				$array[$key]['from_id']=$from["username"];
				$array[$key]['from_name']=$from["name"];
                $array[$key]['remarks']=$value['reg_to']==$regid?"Amount received from ".$from["username"]:"Amount Transferred to ".$to["username"];
                $array[$key]['transferred_amount']=$value['reg_to']==$regid?$value['final_amount']:$value['amount'];
			}
		}
		return $array;
	}
	
	public function gettransferhistory($regid){
        $wallet="ewallet";
        $where="(reg_from='$regid' and type_from='$wallet') or (reg_to='$regid' and type_to='$wallet')";
		$array=$this->db->get_where("wallet_transfers",$where)->result_array();
		if(is_array($array)){
			foreach($array as $key=>$value){
				$to=$this->db->get_where("users",array("id"=>$value['reg_to']))->row_array();
				$from=$this->db->get_where("users",array("id"=>$value['reg_from']))->row_array();
				$array[$key]['to_id']=$to["username"];
				$array[$key]['to_name']=$to["name"];
				$array[$key]['from_id']=$from["username"];
				$array[$key]['from_name']=$from["name"];
                $array[$key]['remarks']=$value['reg_to']==$regid?"Amount received from ".$from["username"]:"Amount Transferred to ".$to["username"];
                $array[$key]['transferred_amount']=$value['reg_to']==$regid?$value['final_amount']:$value['amount'];
			}
		}
		return $array;
	}
	
	public function savedeposit($data){
		$regid=$data['regid'];
		$check=$this->db->get_where("deposits",array("regid"=>$regid,"status"=>"0"))->num_rows();
		if($check==0){
            $check2=$this->db->get_where("deposits",array("regid"=>$regid,"status"=>"1"))->num_rows();
            $data['type']='deposit';
			if($this->db->insert("deposits",$data)){
				return array('status'=>true,'message'=>'Deposit Request Submitted');
			}
			else{
                $err=$this->db->error();
                return array("status"=>false,"message"=>$err['message']);
			}
		}
		else{
			return array("status"=>false,"message"=>"Previous Deposit Request is Pending!");
		}
	}
	
	public function getdepositlist($where,$columns=false){
        if($columns){
            $columns ="date,type,amount,";
            $columns.="case when status=0 then 'Request Pending' 
                            when status=1 then concat('Deposit Approved on ',DATE_FORMAT(approved_on, '%d-%m-%Y'))
                            else 'Deposit Rejected' end as status";
            $this->db->select($columns);
        }
		$this->db->where($where);
		$query=$this->db->get("deposits");
		$array=$query->result_array();
		return $array;
	}
	
	public function getmemberdepositlist($where,$columns=false){
        if($columns){
            $columns ="date,type,amount,";
            $columns.="case when status=0 then 'Request Pending' 
                            when status=1 then concat('Deposit Approved on ',DATE_FORMAT(approved_on, '%d-%m-%Y'))
                            else 'Deposit Rejected' end as status";
            $this->db->select($columns);
        }
        else{
            $this->db->select('t1.*,t2.package');
        }
		$this->db->where($where);
        $this->db->from("deposits t1");
        $this->db->from("packages t2","t1.package_id=t2.id");
		$query=$this->db->get();
		$array=$query->result_array();
		return $array;
	}
	
	public function getdepositlistrequest($where=array(),$type='all'){
		if(empty($where)){ $where['t1.status']=0; }
		$this->db->select("t1.*, t2.username,t2.name");
		$this->db->from('deposits t1');
		$this->db->join('users t2','t1.regid=t2.id','Left');
		$this->db->join('members t3','t1.regid=t3.regid','Left');
		$this->db->where($where);
		$query=$this->db->get();
		if($type=='all'){ $array=$query->result_array(); }
		else{ $array=$query->row_array(); }
		return $array;
	}
	
	public function approvedeposit($id){
		$date=date('Y-m-d');
		$updated_on=date('Y-m-d H:i:s');
        $data=array("status"=>1,"approved_on"=>$updated_on,"updated_on"=>$updated_on);
		if($this->db->update("deposits",$data,array("id"=>$id))){
			return true;
		}
		else{
			return $this->db->error();
		}
	}
	
	public function rejectdeposit($id){
		$updated_on=date('Y-m-d H:i:s');
		$data=array("status"=>2,"updated_on"=>$updated_on);
		if($this->session->role=='admin'){
			$data['approved_on']=$updated_on;
		}
		if($this->db->update("deposits",$data,array("id"=>$id))){
			return true;
		}
		else{
			return $this->db->error();
		}
	}
    
    public function savewithdrawalrequest($data){
        if($this->db->get_where('withdrawals',['regid'=>$data['regid'],'status'=>0])->num_rows()==0){
            $data['added_on']=$data['updated_on']=date('Y-m-d H:i:s');
            if($this->db->insert('withdrawals',$data)){
                return array("status"=>true,"message"=>"Withdrawal Request Added Successfully!");
            }
            else{
                $error=$this->db->error();
                return array("status"=>false,"message"=>$error['message']);
            }
        }
        else{
            return array("status"=>false,"message"=>"Previous Withdrawal Request is Pending!");
        }
    }
    
	public function requestpayout($data){
		$regid=$data['regid'];
		$check=$this->db->get_where("withdrawals",array("regid"=>$regid,"status"=>"0"))->num_rows();
		if($check==0){
			if($this->db->insert("withdrawals",$data)){
				return array('status'=>true,'message'=>'Withdrawal Request Submitted');
			}
			else{
                $err=$this->db->error();
                return array("status"=>false,"message"=>$err['message']);
			}
		}
		else{
			return array("status"=>false,"message"=>"Previous Payout Request is Pending!");
		}
	}
	
	public function getmemberrequests($where,$columns=false){
        if($columns){
            $columns ="date,amount,tds,admin_charge,payable,";
            $columns.="case when isNULL(order_id) or status!=1 then '' 
                            else order_id end as order_id,";
            $columns.="case when status=0 then 'Request Pending' 
                            when status=1 then concat('Withdrawal Approved on ',DATE_FORMAT(approve_date, '%d-%m-%Y'))
                            else 'Withdrawal Rejected' end as status";
            $this->db->select($columns);
        }
		$this->db->where($where);
		$query=$this->db->get("withdrawals");
		$array=$query->result_array();
		return $array;
	}
	
	public function getwitdrawalrequest($where=array(),$type='all'){
		if(empty($where)){ $where['t1.status']=0; }
		$this->db->select("t1.*, t2.username,t2.name,t3.bank,t3.account_no,t3.account_name,t3.ifsc,t3.upi,t3.address,t3.cheque");
		$this->db->from('withdrawals t1');
		$this->db->join('users t2','t1.regid=t2.id','Left');
		$this->db->join('acc_details t3','t1.regid=t3.regid','Left');
		$this->db->where($where);
		$query=$this->db->get();
		if($type=='all'){ $array=$query->result_array(); }
		else{ $array=$query->row_array(); }
		return $array;
	}
	
	public function approvepayout($id){
		$date=date('Y-m-d');
		$updated_on=date('Y-m-d H:i:s');
        $data=array("status"=>1,"approve_date"=>$date,'order_id'=>time(),"updated_on"=>$updated_on);
		if($this->db->update("withdrawals",$data,array("id"=>$id))){
			return true;
		}
		else{
			return $this->db->error();
		}
	}
	
	/*public function approvepayout($data,$where){
		$updated_on=date('Y-m-d H:i:s');
		$data['updated_on'] = $updated_on;
		if($this->session->role=='admin'){
			$data['approve_date']=date('Y-m-d');
		}
		if($this->db->update("withdrawals",$data,$where)){
			return true;
		}
		else{
			return $this->db->error();
		}
	}*/
	
	public function rejectpayout($id,$reason=''){
		$updated_on=date('Y-m-d H:i:s');
		$data=array("status"=>2,'reason'=>$reason,"updated_on"=>$updated_on);
		if($this->session->role=='admin'){
			$data['approve_date']=date('Y-m-d');
		}
		if($this->db->update("withdrawals",$data,array("id"=>$id))){
			return true;
		}
		else{
			return $this->db->error();
		}
	}
	
	public function paymentreport($where=array(),$type='all'){
		$where['t1.status']=1;
		$columns="t1.approve_date,t2.username, t2.name,t3.bank,t3.account_no,t3.ifsc,amount,tds,admin_charge,t1.payable as paidamount";
		$this->db->select($columns);
		$this->db->from('withdrawals t1');
		$this->db->join('users t2','t1.regid=t2.id','Left');
		$this->db->join('acc_details t3','t1.regid=t3.regid','Left');
		$this->db->order_by("t1.approve_date");
		$this->db->where($where);
		$query=$this->db->get();
		if($type=='all'){ $array=$query->result_array(); }
		else{ $array=$query->row_array(); }
		return $array;
	}
	
	public function approveallpayout($endtime){
		$where=array("t1.status"=>0,"t1.added_on<"=>$endtime);
		$members=$this->Wallet_model->getwitdrawalrequest($where);
		foreach($members as $member){
			$this->approvepayout($member['id']);
		}
	}
	
	public function dailypaymentreport(){
		$this->db->select("approve_date,sum(payable) as total_amount");
		$this->db->group_by("approve_date");
		$query=$this->db->get_where("withdrawals",array("status"=>1));
		$array=$query->result_array();
		return $array;
	}
	
	public function getmemberrewards(){
		$this->db->select("t2.username,t2.name,t1.*,t3.category");
		$this->db->from("member_rewards t1");
		$this->db->join("users t2","t1.regid=t2.id");
		$this->db->join("rewards t3","t1.reward_id=t3.id");
		$this->db->order_by("t1.status,t1.id");
		//$this->db->where(array("t1.status"=>"0"));
		$query=$this->db->get();
		$array=$query->result_array();
		return $array;
	}
	
	public function approvereward($id){
		if($this->db->update("member_rewards",array("status"=>1,"approve_date"=>date('Y-m-d')),array("id"=>$id))){
			return true;
		}
		else{
			return $this->db->error();
		}
	}
	
	public function getmembercommission(){
		$this->db->select("t1.regid,t2.username,t2.name,sum(t1.amount) as total");
		$this->db->from("wallet t1");
		$this->db->join("users t2","t1.regid=t2.id");
		$this->db->group_by("t1.regid");
		$array=$this->db->get()->result_array();
		if(is_array($array)){
			foreach($array as $key=>$member){
				$where2=array("regid"=>$member['regid'],"status!="=>2);
				$this->db->select_sum('amount','amount');
				$query2=$this->db->get_where("withdrawals",$where2);
				$bankwithdrawal=$query2->row()->amount;
				if($bankwithdrawal==NULL){ $bankwithdrawal=0; }
				$array[$key]['available']=$member['total']-$bankwithdrawal;
			}
		}
		return $array;
	}
    
	public function requestdetails($request_id){
		$this->db->select("t1.id,t1.regid,t2.mobile,t3.bank,t3.account_no,t3.ifsc,sum(t1.payable) as amount");
		$this->db->from("withdrawals t1");
		$this->db->join("users t2","t1.regid=t2.id");
		$this->db->join("acc_details t3","t1.regid=t3.regid");
		$this->db->where("t1.id=$request_id");
		$array=$this->db->get()->unbuffered_row('array');
		return $array;
	}
	
	public function getorderid($length=6){
		$order_id=strtoupper(random_string('alnum', $length));
		$checkorder_id=$this->db->get_where("withdrawals",array("order_id"=>$order_id))->num_rows();
		if($checkorder_id==0){
			return $order_id;
		}
		else{
			return $this->getorderid($length);
		}
	}
    
	public function getincome($where=array()){
        $columns="t1.*,t2.package,t2.amount as rate,t2.discount,count(t2.id) as members,sum((t2.amount*(100-t2.discount)/100)) as amount";
		$this->db->select($columns);
		$this->db->from('members t1');
		$this->db->join('packages t2',"t1.package_id=t2.id");
		$this->db->group_by("t1.package_id,t1.activation_date");
		$this->db->where($where);
        $this->db->order_by('t1.activation_date');
		$query=$this->db->get();
        $array=$query->result_array();
		return $array;
	}
	
	public function gettdsreport($where=array()){
        $columns="sum(t1.tds) as tds_amount,t2.name,t2.pan";
		$this->db->select($columns);
		$this->db->from('withdrawals t1');
		$this->db->join('members t2',"t1.regid=t2.regid");
		$this->db->where($where);
		$query=$this->db->get();
        $array=$query->result_array();
        if($array[0]['name']==""){
            $array=array();
        }
		return $array;
	}
	
	public function checkrenewal($regid){
        $where=['regid'=>$regid,'type'=>'ewallet','remarks'=>'Daily Self Income','date>'=>'2023-01-09'];
        /*$this->db->select_sum('amount');
        $income=$this->db->get_where('wallet',$where)->unbuffered_row()->amount;
        if(empty($income)){
            return false;
        }*/
        $incomecount=$this->db->get_where('wallet',$where)->num_rows();
        if($incomecount==0){
            return false;
        }
        //echo PRE;
        $member=$this->member->getmemberdetails($regid);
        $package_id=$member['package_id'];
        $package=$this->package->getpackages(['id'=>$package_id],'single');
        $package_amount=$package['original'];
        $times=2;
        $datetime=date('Y-m-d H:i:s');
        if($incomecount>=50){
            $check=$this->db->get_where('renewals',['regid'=>$regid])->num_rows();
            if($check==0){
                $data=array('regid'=>$regid,'package_id'=>$package_id,'added_on'=>$datetime,'updated_on'=>$datetime);
                $this->db->insert('renewals',$data);
            }
        }
        
	}
	
	public function renewallist($status=0){
		$where['t1.status']=$status;
		$where['t2.status']=1;
		$this->db->select("t1.*, t2.username,t3.name");
		$this->db->from('renewals t1');
		$this->db->join('users t2','t1.regid=t2.id','Left');
		$this->db->join('members t3','t1.regid=t3.regid','Left');
		$this->db->where($where);
		$query=$this->db->get();
        $array=$query->result_array(); 
		return $array;
	}
	
	public function approverenewal($data,$where){
		if($this->db->update("renewals",$data,$where)){
			return true;
		}
		else{
			return $this->db->error();
		}
	}
	
	public function getrenewal($regid){
        $result=array('status'=>true,'message'=>'Renew Your Account','package_id'=>0,'amount'=>0);
        $where=['t1.regid'=>$regid];
        $this->db->select('t1.*,t2.package,t2.amount');
        $this->db->from('renewals t1');
        $this->db->join('packages t2','t1.package_id=t2.id');
        $this->db->where($where);
        $query=$this->db->get();
        if($query->num_rows()>0){
            $renewal=$query->unbuffered_row('array');
            if($renewal['status']==0){
                $result['status']=false;
                $result['package_name']=$renewal['package'];
                $result['package_id']=$renewal['package_id'];
                $package=$this->package->getpackages(['id'=>$renewal['package_id']],'single');
                $result['amount']=$renewal['amount'];
            }
        }
        return $result;
    }
    
	public function getrebateincome($regid){
        $where=['t1.regid'=>$regid,'remarks'=>'Team Rebate Income'];
        $columns="t1.date,t1.level,t2.username as member_id,t1.percent,t1.amount";
        $this->db->select($columns);
        $this->db->from('wallet t1');
        $this->db->join('users t2','t1.member_id=t2.id');
        $this->db->where($where);
        $this->db->order_by('t1.date,t1.level');
        $query=$this->db->get();
        $array=$query->result_array();
        return $array;
    }
    
	public function getreferralincome($regid){
        $where=['t1.regid'=>$regid,'remarks'=>'Sponsor Income'];
        $columns="t1.date,t1.level,t2.package,t1.members member_count,t1.percent,t1.amount";
        $this->db->select($columns);
        $this->db->from('wallet t1');
        $this->db->join('packages t2','t1.package_id=t2.id');
        $this->db->where($where);
        $this->db->order_by('t1.date,t1.level');
        $query=$this->db->get();
        $array=$query->result_array();
        return $array;
    }
    
	public function purchasecourse($data){
        if($this->db->insert("purchases",$data)){
            return array('status'=>true,'message'=>'Deposit Request Submitted');
        }
        else{
            $err=$this->db->error();
            return array("status"=>false,"message"=>$err['message']);
        }
	}
	
	public function getpurchaselist($where,$columns=false){
        if($columns){
            $columns ="*";
            $this->db->select($columns);
        }
		$this->db->where($where);
		$query=$this->db->get("purchases");
		$array=$query->result_array();
		return $array;
	}
	
	public function getepinfunds($where=array()){
        $where1[]="t1.status=1";
        $where2[]="t1.reg_from=0 and t1.type='generate'";
        $where3[]="t1.status=1";
        if(isset($where['from'])){
            $from=$where['from'];
            $where1[]="t1.approve_date>='$from'";
            $where2[]="date(t1.added_on)>='$from'";
            $where3[]="date(t1.approved_on)>='$from'";
        }
        if(isset($where['to'])){
            $to=$where['to'];
            $where1[]="t1.approve_date<='$to'";
            $where2[]="date(t1.added_on)<='$to'";
            $where3[]="date(t1.approved_on)<='$to'";
        }
        $where1=implode(' and ',$where1);
        $where2=implode(' and ',$where2);
        $where3=implode(' and ',$where3);
        $sql1="SELECT
                    `t1`.approve_date,
                    'E-Pin Request Approved' as type,
                    `t1`.quantity,
                    `t2`.`username`,
                    `t2`.`name`,
                    `t5`.`username` as `susername`,
                    `t5`.`name` as `sname`,
                    `t3`.`amount` AS `package_amount`,
                    `t3`.`package`,
                    `t1`.`amount`,
                    `t1`.`updated_on` as `time`
                FROM
                    `".TP."epin_requests` `t1`
                LEFT JOIN `".TP."users` `t2` ON
                    `t1`.`regid` = `t2`.`id`
                LEFT JOIN `".TP."packages` `t3` ON
                    `t1`.`package_id` = `t3`.`id`
                LEFT JOIN `".TP."members` `t4` ON
                    `t1`.`regid` = `t4`.`regid`
                LEFT JOIN `".TP."users` `t5` ON
                    `t4`.`refid` = `t5`.`id`
                WHERE $where1";
        $sql2="SELECT
                date(`t1`.added_on) as approve_date,
                'E-Pin Generation' as type,
                count(`t1`.id) as quantity,
                `t3`.`username`,
                `t3`.`name`,
                `t6`.`username` as `susername`,
                `t6`.`name` as `sname`,
                `t4`.`amount` AS `package_amount`,
                `t4`.`package`,
                count(`t1`.id)*t4.amount as amount,
                `t1`.`added_on` as `time`
            FROM
                `".TP."epin_transfer` `t1`
            LEFT JOIN `".TP."epins` `t2` ON
                `t1`.`epin_id` = `t2`.`id`
            LEFT JOIN `".TP."users` `t3` ON
                `t1`.`reg_to` = `t3`.`id`
            LEFT JOIN `".TP."packages` `t4` ON
                `t2`.`package_id` = `t4`.`id`
            LEFT JOIN `".TP."members` `t5` ON
                `t1`.`reg_to` = `t5`.`regid`
            LEFT JOIN `".TP."users` `t6` ON
                `t5`.`refid` = `t6`.`id`
            WHERE $where2 
            GROUP BY t1.added_on";
        
        $sql3="SELECT
                    DATE(`t1`.approved_on) AS approve_date,
                    'Deposit' as type,
                    0 AS quantity,
                    `t2`.`username`,
                    `t2`.`name`,
                    `t4`.`username` AS `susername`,
                    `t4`.`name` AS `sname`,
                    `t1`.`amount` AS `package_amount`,
                    '' AS `package`,
                    `t1`.`amount`,
                    `t1`.`updated_on` as `time`
                FROM
                    `dc_deposits` `t1`
                LEFT JOIN `dc_users` `t2` ON
                    `t1`.`regid` = `t2`.`id`
                LEFT JOIN `dc_members` `t3` ON
                    `t1`.`regid` = `t3`.`regid`
                LEFT JOIN `dc_users` `t4` ON
                    `t3`.`refid` = `t4`.`id`
                WHERE
                    $where3";
        
        $sql=$sql1.' UNION '.$sql2.' UNION '.$sql3.' order by time';
		$query=$this->db->query($sql);
        //print_pre($this->db->error());
        //echo $this->db->last_query();die;
        $array=$query->result_array();
		return $array;
	}
	
	public function getpayments($where=array()){
        $where1[]="t1.status=1";
        if(isset($where['from'])){
            $from=$where['from'];
            $where1[]="t1.approve_date>='$from'";
        }
        if(isset($where['to'])){
            $to=$where['to'];
            $where1[]="t1.approve_date<='$to'";
        }
        $where1=implode(' and ',$where1);
        $sql2="SELECT
                `t1`.`approve_date`,
                `t2`.`username`,
                `t2`.`name`,
                `t1`.`amount`,
                `t1`.`tds`,
                `t1`.`admin_charge` as `fees`,
                `t1`.`payable`
            FROM
                `".TP."withdrawals` `t1`
            LEFT JOIN `".TP."users` `t2` ON
                `t1`.`regid` = `t2`.`id`
            WHERE
                $where1";
        
        $sql=$sql2.' order by t1.updated_on';
		$query=$this->db->query($sql);
        //print_pre($this->db->error());
        //echo $this->db->last_query();die;
        $array=$query->result_array();
		return $array;
	}
	
	public function memberwisedeposit($where=array()){
        $columns="t2.username,t2.name,(t5.amount+ifnull(t6.amount,0)) as deposit";
        $this->db->select($columns);
        $this->db->where($where);
        $this->db->from("members t1");
        $this->db->join("users t2","t1.regid=t2.id");
        $this->db->join("epin_used t3","t1.regid=t3.used_by");
        $this->db->join("epins t4","t3.epin_id=t4.id");
        $this->db->join("packages t5","t4.package_id=t5.id");
        $join="(SELECT regid,sum(amount) as amount from ".TP."deposits where status='1' group by regid)";
        $this->db->join("$join t6","t6.regid=t1.regid",'left');
        $query=$this->db->get();
        $array=$query->result_array();
        return $array;
	}
	
}
