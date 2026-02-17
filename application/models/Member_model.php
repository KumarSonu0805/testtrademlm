<?php
class Member_model extends CI_Model{
	var $user_prefix="GT";
	var $random_user=true;
	var $downline_table="members";
	var $downline_order="refid";
	var $downline_parent="refid";
	
	function __construct(){
		parent::__construct(); 
		$this->db->db_debug = false;
	}
	
	public function addmember($data){
		$userdata=$data['userdata'];
		$memberdata=$data['memberdata'];
        if($memberdata['wallet_address']!==NULL){
            $check=$this->db->get_where('members',['wallet_address'=>$memberdata['wallet_address']])->num_rows();
        }
        else{
            $check=0;
        }
        if($check==0){
            $this->db->trans_start();
            $user=$this->adduser($userdata);
            if(is_array($user) && $user['status']===true){
                $regid=$user['regid'];
                $username=$user['username'];
                $password=$user['password'];

                $memberdata['regid']=$regid;
                $accountdata['regid']=$regid;


                $memberdata['added_on']=$memberdata['updated_on']=date('Y-m-d H:i:s');
                $this->db->insert("members",$memberdata);

                $this->addlevel($regid);
                $this->db->trans_complete();
                return $user;
            }
            else{
                return array('status'=>false,'message'=>!empty($user['message'])?$user['message']:'Please Try Again!');
            }
		}
        else{
            return array('status'=>false,'message'=>'Wallet address already present','type'=>'wallet_duplicate');
        }
		
	}
	
	public function adduser($userdata){
        $userdata['username']=!empty($userdata['username'])?$userdata['username']:$this->generateusername();
        $username=$userdata['username'];
        if($this->db->get_where('users',['username'=>$username])->num_rows()==0){
            if(empty($userdata['password'])){
                $password=random_string('numeric', 5);
            }
            else{
                $password=$userdata['password'];
            }
            $userdata['vp']=$password;
            $salt=random_string('alnum', 16);
            $encpassword=$password.SITE_SALT.$salt;
            $encpassword=password_hash($encpassword,PASSWORD_DEFAULT);
            $userdata['salt']=$salt;
            $userdata['password']=$encpassword;
            $userdata['created_on']=date('Y-m-d H:i:s');
            $userdata['updated_on']=date('Y-m-d H:i:s');
            if($this->db->insert("users",$userdata)){
                $regid=$this->db->insert_id();
                $user=$this->db->get_where('users',['id'=>$regid])->unbuffered_row('array');
                $result=array("status"=>true,"regid"=>$regid,"username"=>$username,"password"=>$password,'user'=>$user);
                return $result;
            }
            else{
                $error= $this->db->error();
                $error['status']=false;
                return $error;
            }
        }
        else{
            return array('status'=>false,'message'=>"Username not Available!");
        }
	}
	
	public function generateusername($username=''){
		if($this->random_user===false){
			if($username!=''){
				$username++;
			}
			else{
				$this->db->order_by("id desc");
				$array=$this->db->get_where("users",array("role"=>"member"))->unbuffered_row('array');
				if(!empty($array)){
					$username=$array['username'];
					$username++;
				}
				else{
					$username=$this->user_prefix."00001";
				}
			}
		}
		else{
            if($username==''){
                $userid=random_string('numeric',6);
                $username=$this->user_prefix.$userid;
            }
		}
		$where="username='$username'";
		$query=$this->db->get_where("users",$where);
		if($query->num_rows()!=0){
			return $this->generateusername();
		}
		else{
			return $username;
		}
	}
	
	public function addlevel($regid){
		$leveldata=array();
		$this->db->select("regid,GetAncestry(regid) as ancestors");
		$getancestors=$this->db->get_where("members",array("regid"=>$regid));
        //print_pre($ancestors,true);
		if($getancestors->num_rows()>0){
            $ancestors=$getancestors->unbuffered_row()->ancestors;
            $ancestors=!empty($ancestors)?explode(',',$ancestors):array();
            if(is_array($ancestors)){
                foreach($ancestors as $key=>$ancestor){
                    $level_id=$key+1;
                    //if($level_id>28 || empty($ancestor)){ continue; }
                    //$this->checklevelmembers($ancestor,$level_id);
                    $single['regid']=$ancestor;
                    $single['level_id']=$level_id;
                    $single['member_id']=$regid;
                    $single['added_on']=date('Y-m-d H:i:s');
                    $leveldata[]=$single;
                }
            }
		}
        //print_pre($leveldata,true);
		if(!empty($leveldata)){
			$this->db->insert_batch("level_members",$leveldata);
		}
	}
		
	public function getmemberdetails($regid){
        return $this->db->get_where("members",array("regid"=>$regid))->unbuffered_row('array');
	}
		
	public function getmembers($regid,$count=false){
        
        // Initialize the parent variable in MariaDB
        $this->db->query("SET @pv := ?", [$regid]);

        // Query to fetch hierarchy using variables
        $query = "
            SELECT m.*,u.username,u2.username as sponsor_id,u2.name as sponsor_name,u.mobile,0 as investment, 0 as team_business
            FROM (
                SELECT regid, @pv := CONCAT(@pv, ',', regid) AS pv
                FROM (
                    SELECT *
                    FROM ".TP."members
                    ORDER BY refid, regid
                ) ordered_members,
                (SELECT @pv := ?) init
                WHERE FIND_IN_SET(refid, @pv) > 0
            ) t
            JOIN ".TP."members m ON m.regid = t.regid
            JOIN ".TP."users u ON m.regid = u.id
            JOIN ".TP."users u2 ON m.refid = u2.id;
        ";

        // Execute the query
        if($count===false){
            $result = $this->db->query($query, [$regid])->result_array();
        }
        else{
            $result = $this->db->query($query, [$regid])->num_rows();
        }
        // Output the result as JSON
        //print_pre($result);
        return $result;
	}
		
	public function getdirectmembers($regid){
        $this->db->select("t1.*,t2.username,t2.mobile,t3.username as sponsor_id,t3.name as sponsor_name,0 as investment, 0 as team_business");
        $this->db->where(['t1.refid'=>$regid]);
        $this->db->from('members t1');
        $this->db->join('users t2','t1.regid=t2.id');
        $this->db->join('users t3','t1.refid=t3.id');
        $query=$this->db->get();
        $result=$query->result_array();
        return $result;
	}
		
	public function levelwisemembers($regid,$date=NULL,$status=NULL){
		$where=array("t1.regid"=>$regid);
		if($date!==NULL){
			$where['date(t3.activation_date)<=']=$date;
		}
		if($status!==NULL){
			$where['t3.status']=$status;
		}
        $columns="t1.member_id,t1.level_id as level,t2.username,t2.name,t2.mobile,t3.date,t3.activation_date,t3.status";
		$this->db->select($columns);
		$this->db->from("level_members t1");
		$this->db->join("users t2","t1.member_id=t2.id");
		$this->db->join("members t3","t1.member_id=t3.regid");
		$this->db->where($where);
		$this->db->order_by("t1.level_id");
		$query=$this->db->get();
		$array=$query->result_array();
		return $array;
	}
	
	public function levelwiseinvestment($regid,$date=NULL){
		$where=array("t1.regid"=>$regid);//,'t2.status'=>1);
		if($date!==NULL){
			$where['date(t2.date)<=']=$date;
		}
        $columns="t1.level_id as level,t2.amount,sum(t2.amount*t2.rate) as amount_usdt";
		$this->db->select($columns);
		$this->db->from("level_members t1");
		$this->db->join("investments t2","t1.member_id=t2.regid");
		$this->db->where($where);
		$this->db->group_by("t1.level_id");
		$this->db->order_by("t1.level_id");
		$query=$this->db->get();
		$array=$query->result_array();
		return $array;
	}
	
	public function getmemberid($username,$status="activated"){
		$where="username='$username'";
		$query=$this->db->get_where("users",$where);
		if($query->num_rows()==1){
			$array=$query->row_array();
			$regid=$array['id'];
			$name=$array['name'];
			$statusarr=explode(',',$status);
			$status=array_shift($statusarr);
			if($status=='downline' && $regid!=0 && $this->session->role=='member'){
				$downline=false;
				$refid=$this->db->get_where("members",array("regid"=>$regid))->unbuffered_row()->refid;
				while($refid>1){
					if(md5($refid)==$this->session->user){ $downline=true; break; }
					$refid=$this->db->get_where("members",array("regid"=>$refid))->unbuffered_row()->refid;
				}
				if($downline===false){$regid=0;$name="Enter different Member ID!";}
				$status=array_shift($statusarr);
			}
			elseif($status=='downline' && $this->session->role=='admin'){
				$status=array_shift($statusarr);
			}
			if($status=='not self' && $regid!=0){
				if(md5($regid)==$this->session->user){$regid=0;$name="Enter different Member ID!";}
				$status=array_shift($statusarr);
			}
			if($status=='activated' && $regid!=0){
				$check=$this->db->get_where("members",array("regid"=>$regid,"status"=>"1"))->num_rows();
				if($check!=1){$regid=0;$name="Member ID not Activated!";}
				$status=array_shift($statusarr);
			}
			if($status=='not activated' && $regid!=0){
				$check=$this->db->get_where("members",array("regid"=>$regid,"status"=>"0"))->num_rows();
				if($check!=1){$regid=0;$name="Member ID Already Activated!";}
				$status=array_shift($statusarr);
			}
			if($status=='day limit' && $regid!=0){
				$daylimit=date("Y-m-d",strtotime("-10 days"));
				$check=$this->db->get_where("members",array("regid"=>$regid,"date<"=>$daylimit))->num_rows();
				if($check==1){$regid=0;$name="You cannot activate this ID!";}
				$status=array_shift($statusarr);
			}
		}
		else{$regid=0;$name="Member ID not Available!";}
		$result=array("regid"=>$regid,"name"=>$name);
		return $result;
	}
	
    public function activatemember($user){
		$updata['activation_date']=date('Y-m-d');
		$updata['activation_time']=date('H:i:s');
		$updata['status']=1;
		$updata['updated_on']=date('Y-m-d H:i:s');
        if(isset($user['balance']) && $user['balance']>=MIN_BAL){
            $updata['package']=$user['balance'];
        }
        else{
            return array('status'=>false,'message'=>"Try Again");
        }
        $result=$this->db->update('members',$updata,['regid'=>$user['id']]);
        if($result){
            $data=array('regid'=>$user['id'],'date'=>date('Y-m-d'),'amount'=>$user['balance'],'added_on'=>date('Y-m-d H:i:s'),
                        'updated_on'=>date('Y-m-d H:i:s'));
            $this->db->insert('investments',$data);
            return array('status'=>true,'message'=>"Account activated!");
        }
        else{
            $error=$this->db->error();
            return array('status'=>false,'message'=>$error['message']);
        }
	}
	
    public function updatememberpackage($user){
        $regid=$user['id'];
        $member=$this->member->getmemberdetails($regid);
        $balance=getUSDTBalance($member['wallet_address']);
        if($this->input->get('test')=='test'){
        echo $balance.'<br>';
        }
        $balance=roundTo2DigitLowerLimit($balance);
        $investment=$member['package'];
        $this->db->order_by('id desc');
        $getinvestments=$this->db->get_where('investments',['regid'=>$regid,'status'=>1]);
        $investmentcount=$getinvestments->num_rows();
        if($investmentcount>0){
            $investment=$getinvestments->unbuffered_row()->amount;
            $investment=roundTo2DigitLowerLimit($investment);
        }
        if(WORK_ENV=='deveslopment'){
            $balance=$investment;
        }
        if($regid==3 || $regid==6){
            return true;
        }
        if($balance!=$investment){
            if($investmentcount>0){
                $this->db->update('investments',['status'=>0],['regid'=>$regid,'status'=>1]);
            }
            $data=array('regid'=>$regid,'date'=>date('Y-m-d'),'amount'=>$balance,'added_on'=>date('Y-m-d H:i:s'),
                        'updated_on'=>date('Y-m-d H:i:s'));
            $this->db->insert('investments',$data);
        }
	    $this->db->order_by('id asc');
        $getinvestments=$this->db->get_where('investments',['regid'=>$regid,'status'=>1]);
        if($getinvestments->num_rows()){
        	$investments=$getinvestments->result_array();
            for($i=0;$i<count($investments)-1;$i++){
            	$this->db->update('investments',['status'=>0],['id'=>$investments[$i]['id']]);
            }
	   }
	}
	
    public function getinvestments($user,$status=false){
        $regid=$user['id'];
        $where=['regid'=>$regid];
        if($status!==false){
            $where['status']=$status;
        }
        $this->db->select('*,(rate*amount) as total_amount');
        $this->db->order_by('id desc');
        $investments=$this->db->get_where('investments',$where)->result_array();
        return $investments;
    }
    
    public function savestake($data){
		$regid=$data['regid'];
        $data['added_on']=$data['updated_on']=date('Y-m-d H:i:s');
        if($this->db->insert("investments",$data)){
            $activation_date=date('Y-m-d');
            $activation_time=date('H:i:s');
            $updatedata=array('activation_date'=>$activation_date,'activation_time'=>$activation_time,
                              'package'=>$data['amount'],'status'=>1);
            $this->db->update('members',$updatedata,['regid'=>$regid,'status'=>0]);
            return array('status'=>true,'message'=>'Amount Staked Successfully!');
        }
        else{
            $err=$this->db->error();
            return array("status"=>false,"message"=>$err['message']);
        }
	}
	
    public function saveunstake($data){
        $total=$data['amount'];
        $where=array('regid'=>$data['regid'],'old'=>0,'status'=>1);
        $investments=$this->db->get_where('investments',$where)->result_array();
        if(!empty($investments)){
            $staked=0;
            $last=0;
            foreach($investments as $single){
                $staked+=$single['amount'];
                $last=$single['id'];
            }
            $reward=$total-$staked;
            $data=array('total'=>$total,'status'=>0,'updated_on'=>date('Y-m-d H:i:s'));
            if($this->db->update("investments",$data,$where)){
                if($reward>0){
                    $this->db->update("investments",['reward'=>$reward],['id'=>$last]);
                }
                return array('status'=>true,'message'=>'Amount UnStaked Successfully!');
            }
            else{
                $err=$this->db->error();
                return array("status"=>false,"message"=>$err['message']);
            }
        }
        else{
            return array("status"=>false,"message"=>"Unstaking failed!");
        }
	}
	
    public function getstakinghistory($regid,$status=NULL,$old=NULL){
        $where=['regid'=>$regid];
        if($status!==NULL){
            $where['status']=$status;
        }
        if($old!==NULL){
            $where['old']=$old;
        }
        
        $this->db->where($where);
        $query=$this->db->get('investments');
        $array=$query->result_array();
        return $array;
	}
	
    public function saveunstakerequest($data){
        $data['added_on']=$data['updated_on']=date('Y-m-d H:i:s');
        if($this->db->insert("unstake",$data)){
            return array('status'=>true,'message'=>'UnStaked Request Saved Successfully!');
        }
        else{
            $err=$this->db->error();
            return array("status"=>false,"message"=>$err['message']);
        }
	}
	
    public function requestwithdrawal($data){
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
			return array("status"=>false,"message"=>"Previous Withdrawal Request is Pending!");
		}
	}
	
	public function getmemberrequests($where,$columns=false){
        if($columns){
            $columns ="t1.date,t1.amount, t1.status,";
            $columns.="case when isNULL(t1.order_id) or status!=1 then '' 
                            else t1.order_id end as order_id,";
            $columns.="case when t1.status=0 then 'Request Pending' 
                            when t1.status=1 then concat('Withdrawal Approved on ',DATE_FORMAT(t1.approve_date, '%d-%m-%Y'))
                            else 'Withdrawal Rejected' end as text_status";
            $this->db->select($columns);
        }
        else{
		  $this->db->select("t1.*, t2.username,t2.name,t3.wallet_address");
        }
		$this->db->where($where);
		$this->db->from('withdrawals t1');
		$this->db->join('users t2','t1.regid=t2.id','Left');
		$this->db->join('members t3','t1.regid=t3.regid','Left');
		$query=$this->db->get();
		$array=$query->result_array();
		return $array;
	}
	
	public function getunstakerequests($where,$columns=false){
        if($columns){
            $columns ="t1.date,t1.amount, t1.status,";
            $columns.="case when isNULL(t1.order_id) or status!=1 then '' 
                            else t1.order_id end as order_id,";
            $columns.="case when t1.status=0 then 'Request Pending' 
                            when t1.status=1 then concat('Withdrawal Approved on ',DATE_FORMAT(t1.approve_date, '%d-%m-%Y'))
                            else 'Withdrawal Rejected' end as text_status";
            $this->db->select($columns);
        }
        else{
		  $this->db->select("t1.*, t2.username,t2.name,t3.wallet_address");
        }
		$this->db->where($where);
		$this->db->from('unstake t1');
		$this->db->join('users t2','t1.regid=t2.id','Left');
		$this->db->join('members t3','t1.regid=t3.regid','Left');
		$query=$this->db->get();
		$array=$query->result_array();
		return $array;
	}
	
	public function getwithdrawalrequest($where=array(),$type='all'){
		if(empty($where)){ $where['t1.status']=0; }
		$this->db->select("t1.*, t2.username,t2.name,t3.wallet_address");
		$this->db->from('withdrawals t1');
		$this->db->join('users t2','t1.regid=t2.id','Left');
		$this->db->join('members t3','t1.regid=t3.regid','Left');
		$this->db->where($where);
		$query=$this->db->get();
		if($type=='all'){ $array=$query->result_array(); }
		else{ $array=$query->row_array(); }
		return $array;
	}
	
	public function getunstakerequest($where=array(),$type='all'){
		if(empty($where)){ $where['t1.status']=0; }
		$this->db->select("t1.*, t2.username,t2.name,t3.wallet_address");
		$this->db->from('unstake t1');
		$this->db->join('users t2','t1.regid=t2.id','Left');
		$this->db->join('members t3','t1.regid=t3.regid','Left');
		$this->db->where($where);
		$query=$this->db->get();
		if($type=='all'){ $array=$query->result_array(); }
		else{ $array=$query->row_array(); }
		return $array;
	}
	
    public function updatewithdrawalrequest($data,$where){
        $data['updated_on']=date('Y-m-d H:i:s');
        if($this->db->update('withdrawals',$data,$where)){
            $message="Withdrawal Request Approved Successfully!";
            if($data['status']===2){
                $message="Withdrawal Request Rejected Successfully!";
            }
            return array("status"=>true,"message"=>$message);
        }
        else{
            $error=$this->db->error();
            return array("status"=>false,"message"=>$error['message']);
        }
    }

    public function updateunstakerequest($data,$where){
        $data['updated_on']=date('Y-m-d H:i:s');
        if($this->db->update('unstake',$data,$where)){
            $message="Unstake Request Approved Successfully!";
            if($data['status']===2){
                $message="Unstake Request Rejected Successfully!";
            }
            return array("status"=>true,"message"=>$message);
        }
        else{
            $error=$this->db->error();
            return array("status"=>false,"message"=>$error['message']);
        }
    }

    public function getteaminvestments($regid,$inc=false,$status=false){
        $downline_ids = $this->getmembers($regid);
        $downline_ids=!empty($downline_ids)?array_column($downline_ids,'regid'):array();
        if($inc){
            $downline_ids[] = $regid; // include leg head's business too
        }
        
        if(!empty($downline_ids)){
            $this->db->group_start();
            $regid_chunks = array_chunk($downline_ids,25);
            foreach($regid_chunks as $regid_chunk){
                $this->db->or_where_in('t1.regid', $regid_chunk);
            }
            $this->db->group_end();
        }
        $where=array();
        if($status!==false){
            $where['t1.status']=$status;
        }

        $this->db->select('t1.*,sum(t1.amount) as amount,sum(t1.rate*t1.amount) as amount_usdt,
    SUM(CASE WHEN t1.status = 1 THEN t1.amount ELSE 0 END) AS current_amount,
    SUM(CASE WHEN t1.status = 1 THEN t1.rate * t1.amount ELSE 0 END) AS current_amount_usdt,t2.username ,t2.name');
        $this->db->order_by('t1.regid');
        $this->db->group_by('t1.regid');
        $this->db->from('investments t1');
        $this->db->join('users t2','t1.regid=t2.id');
        $this->db->where($where);
        $investments=$this->db->get()->result_array();
        //print_pre($this->db->last_query(),true);
        return $investments;
    }

    public function getmemberstakinghistory($regid){
        $where=['regid'=>$regid];
        $this->db->where($where);
        $query=$this->db->get('investments');
        $array=$query->result_array();
        return $array;
    }
    
    public function savedeposit($data){
		$regid=$data['regid'];
		$check=$this->db->get_where("investments",array("regid"=>$regid,"tx_hash"=>$data['tx_hash']))->num_rows();
		if($check==0){
			if($this->db->insert("investments",$data)){
				return array('status'=>true,'message'=>'Investment Added Successfully');
			}
			else{
                $err=$this->db->error();
                return array("status"=>false,"message"=>$err['message']);
			}
		}
		else{
			return array("status"=>false,"message"=>"Deposit already Saved!");
		}
	}
	
	public function getdepositrequests($where=array(),$type='all'){
		$this->db->select("t1.*,t2.username,t2.name");
		$this->db->from('investments t1');
		$this->db->join('users t2','t1.regid=t2.id');
		$this->db->where($where);
		$query=$this->db->get();
		if($type=='all'){ $array=$query->result_array(); }
		else{ $array=$query->row_array(); }
		return $array;
	}
    
	public function getdeposits($where=array(),$type='all'){
		$this->db->select("t1.*");
		$this->db->from('investments t1');
		$this->db->where($where);
		$query=$this->db->get();
		if($type=='all'){ $array=$query->result_array(); }
		else{ $array=$query->row_array(); }
		return $array;
	}
    
    public function savewalletdeposit($data){
		$regid=$data['regid'];
		$check=$this->db->get_where("deposits",array("regid"=>$regid,"tx_hash"=>$data['tx_hash']))->num_rows();
		if($check==0){
			if($this->db->insert("deposits",$data)){
                $member=$this->member->getmemberdetails($regid);
                if($this->db->get_where("deposits",array("regid"=>$regid))->num_rows()>=1 && $member['status']==0){
                    $updata=array('package'=>$data['amount'],'activation_date'=>date('Y-m-d'),'activation_time'=>date('H:i:s'),'status'=>1);
                    $this->db->update('members',$updata,['regid'=>$regid]);
                }
				return array('status'=>true,'message'=>'Deposit Saved Successfully');
			}
			else{
                $err=$this->db->error();
                return array("status"=>false,"message"=>$err['message']);
			}
		}
		else{
			return array("status"=>false,"message"=>"Deposit already Saved!");
		}
	}
	
	public function getwalletdeposits($where=array(),$type='all'){
        $this->db->where(['t1.auto'=>0,'t1.status!='=>0]);
		$this->db->select("t1.*");
		$this->db->from('deposits t1');
		$this->db->where($where);
		$query=$this->db->get();
		if($type=='all'){ $array=$query->result_array(); }
		else{ $array=$query->row_array(); }
		return $array;
	}
    
	
    
}
