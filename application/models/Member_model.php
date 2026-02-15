<?php
class Member_model extends CI_Model{
	var $user_prefix="JEM";
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
		$accountdata=$data['accountdata'];
        if(!empty($data['epindata'])){
            $epindata=$data['epindata'];
        }
        $this->db->trans_start();
		$user=$this->adduser($userdata);
		if(is_array($user) && $user['status']===true){
			$regid=$user['regid'];
			$username=$user['username'];
			$password=$user['password'];
			
			$memberdata['regid']=$regid;
			$accountdata['regid']=$regid;
			
			
			$memberdata['added_on']=date('Y-m-d H:i:s');
			$this->db->insert("members",$memberdata);
			$this->db->insert("acc_details",$accountdata);
			$this->db->insert("nominee",array("regid"=>$regid));
            
            $this->addlevel($regid);
            $this->db->trans_complete();
            
			/*if($memberdata['epin']!=''){
				$package=$this->package->getpackages("id in (SELECT package_id from ".TP."epins where epin='$memberdata[epin]')","single");
				$activatedata['package_id']=$package['id'];
				$activatedata['epin']=$memberdata['epin'];
				$activatedata['regid']=$regid;
				$this->activatemember($activatedata);
			}*/
            
		}
		return $user;
	}
	
	public function adduser($userdata){
        if(empty($userdata['username'])){ $userdata['username']=''; }
        $username=$this->generateusername($userdata['username']);
		$userdata['username']=$username;
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
			$result=array("status"=>true,"regid"=>$regid,"username"=>$username,"password"=>$password);
			return $result;
		}
		else{
			$error= $this->db->error();
			$error['status']=false;
			return $error;
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
	
	public function activatemember($data){
		$regid=$data['regid'];
		$updata['activation_date']=date('Y-m-d');
		$updata['activation_time']=date('H:i:s');
		$updata['epin']=$data['epin'];
		$updata['package_id']=$data['package_id'];
		$updata['status']=1;
		$data['date']=date('Y-m-d');
        //print_pre($data,true);
        $this->db->trans_start();
        if($this->db->get_where("members",array("regid"=>$regid,"status"=>0))->num_rows()==1){
            if($this->db->get_where("epins",array("epin"=>$data['epin'],"status"=>0))->num_rows()==1){
                if($this->db->update("members",$updata,array("regid"=>$regid))){
                    $added_on=$this->epin->updatepinstatus($data['epin'],$regid);
                    $this->addinpool($regid,1);//die;
                    $this->db->trans_complete();
                    return array("status"=>true,"message"=>"Member Activated Successfully!");
                }
                else{
                    $err= $this->db->error();
                    return array("status"=>false,"message"=>$err['message']);
                }
            }
            else{
                return array("status"=>false,"message"=>"Invalid E-Pin!");
            }
        }
        else{
            $date=date('Y-m-d');
            $getmember=$this->db->get_where("members",array("regid"=>$regid));
            $member=$getmember->unbuffered_row('array');
            $activation_date=$member['activation_date'];
            if($date>date('Y-m-d',strtotime($activation_date.' +29days'))){
                $this->db->order_by('added_on desc');
                $epinused=$this->db->get_where('epin_used',['used_by'=>$regid])->unbuffered_row('array');
                $lastrenewaldate=date('Y-m-d',strtotime($epinused['added_on']));
                if($date>date('Y-m-d',strtotime($lastrenewaldate.' +29days'))){
                    if($this->db->get_where("epins",array("epin"=>$data['epin'],"status"=>0))->num_rows()==1){
                        $updata=array();
                        $updata['epin']=$data['epin'];
                        if($this->db->update("members",$updata,array("regid"=>$regid))){
                            $added_on=$this->epin->updatepinstatus($data['epin'],$regid);
                            $this->db->trans_complete();
                            return array("status"=>true,"message"=>"Member Renewed Successfully!");
                        }
                        else{
                            $err= $this->db->error();
                            return array("status"=>false,"message"=>$err['message']);
                        }
                    }
                    else{
                        return array("status"=>false,"message"=>"Invalid E-Pin!");
                    }
                }
            }
            return array("status"=>false,"message"=>"Member Already Active!");
        }
	}
	
	public function joinclub($data){
		$regid=$data['regid'];
        $clubs=array('2'=>1,'3'=>2,'4'=>3,'5'=>4);
        $package_id=$data['package_id'];
        $club_id=$clubs[$package_id];
		$data['added_on']=date('Y-m-d H:i:s');
		$data['status']=1;
        $epin=$this->epin->getepin(array("epin"=>$data['epin']),'single');
        $data['epin_id']=$epin['id'];
        $data['club_id']=$club_id;
        unset($data['package_id'],$data['epin']);
        $this->db->trans_start();
        if($this->db->get_where("club_members",array("regid"=>$regid,"club_id"=>$club_id,"status"=>1))->num_rows()==0){
            if($epin['status']==0 && $epin['package_id']==$package_id){
                //print_pre($data,true);
                if($this->db->insert("club_members",$data)){
                    $added_on=$this->epin->updatepinstatus($epin['epin'],$regid,'club');
                    $this->db->trans_complete();
                    return array("status"=>true,"message"=>$epin['package']." Joined Successfully!");
                }
                else{
                    $err= $this->db->error();
                    return array("status"=>false,"message"=>$err['message']);
                }
            }
            else{
                return array("status"=>false,"message"=>"Invalid E-Pin!");
            }
        }
        else{
            return array("status"=>false,"message"=>"Member Already In $epin[package]!");
        }
	}
	
	public function jointree($data){
		$regid=$data['regid'];
        $package_id=$data['package_id'];
        $club_id=$this->db->get_where('packages',['id'=>$package_id])->unbuffered_row()->club_id;
        if($club_id>0){
            $club=$this->db->get_where('clubs',['id'=>$club_id])->unbuffered_row('array');
            $data['added_on']=date('Y-m-d H:i:s');
            $data['status']=1;
            $epin=$this->epin->getepin(array("epin"=>$data['epin']),'single');
            $data['epin_id']=$epin['id'];
            unset($data['package_id'],$data['epin']);
            //print_pre($data,true);
            $this->db->trans_start();
            if($this->db->get_where("member_tree",array("regid"=>$regid,"club_id"=>$club_id))->num_rows()==0){
                if($epin['status']==0 && $epin['package_id']==$package_id){
                    //print_pre($data,true);
                    $result=$this->addinpool($regid,$club_id);//die;
                    if($result['status']===true){
                        $this->epin->updatepinstatus($epin['epin'],$regid,'club');
                        $this->db->trans_complete();
                        return array("status"=>true,"message"=>$club['name']." Joined Successfully!");
                    }
                    else{
                        $err= $this->db->error();
                        return array("status"=>false,"message"=>$result['message']);
                    }
                }
                else{
                    return array("status"=>false,"message"=>"Invalid E-Pin!");
                }
            }
            else{
                return array("status"=>false,"message"=>"Member Already In $club[name]!");
            }
        }
	}
	
	public function addlevel($regid){
		$leveldata=array();
		$this->db->select("regid,GetAncestry(regid) as ancestors");
		$ancestors=$this->db->get_where("members",array("regid"=>$regid))->unbuffered_row()->ancestors;
		$ancestors=explode(',',$ancestors);
        //print_pre($ancestors,true);
		if(is_array($ancestors)){
			foreach($ancestors as $key=>$ancestor){
				$level_id=$key+1;
				if($level_id>28 || empty($ancestor)){ continue; }
                //$this->checklevelmembers($ancestor,$level_id);
				$single['regid']=$ancestor;
				$single['level_id']=$level_id;
				$single['member_id']=$regid;
				$single['added_on']=date('Y-m-d H:i:s');
				$leveldata[]=$single;
			}
		}
        //print_pre($leveldata,true);
		if(!empty($leveldata)){
			$this->db->insert_batch("level_members",$leveldata);
		}
	}
		
    public function checklevelmembers($regid,$level_id,$next=array()){
        $where=array("regid"=>$regid,"level_id"=>$level_id);
        $query=$this->db->get_where("level_members",$where);
        if($query->num_rows()<1){
            echo $regid.":".$query->num_rows()."<br>";
        }
        else{
            $index=array_search($regid,$next);
            if($index!==false){
                unset($next[$index]);
            }
            if(empty($next)){
                $level_id++;
                
            }
        }
	}
	
	public function addinpool($regid,$club_id){
		$data['regid']=$regid;
		$data['added_on']=date('Y-m-d H:i:s');
		$this->db->order_by("id desc");
		$query=$this->db->get_where("member_tree",['club_id'=>$club_id]);
		$count=$query->num_rows();
		if($count>0){
			$last=$query->unbuffered_row('array');
			$lastposition=$last['position'];
            $id_index=$last['id_index'];
			if($lastposition=='F'){
				$position='L';
				$parent_id=$last['regid'];
			}
			elseif($lastposition=='L'){
                $position="R"; 
				$parent_id=$last['parent_id'];
			}
			else{
				$position='L';
				$parent_index=ceil($count/2);
				$parent_id=$this->db->get_where("member_tree",array("id_index"=>$parent_index,'club_id'=>$club_id))->unbuffered_row()->regid;
			}
		}
		else{
			$id_index=0;
			$parent_id=0;
			$position='F';
		}
        $id_index++;
		$data['parent_id']=$parent_id;
		$data['club_id']=$club_id;
		$data['position']=$position;
		$data['id_index']=$id_index;
        //print_pre($data);
		if($this->db->insert("member_tree",$data)){
            return array("status"=>true,"message"=>"Added in Pool");
        }
        else{
            $err= $this->db->error();
            return array("status"=>false,"message"=>$result['message']);
        }
	}
	
	public function countpoolmembers($regid,$club_id){
		$table=TP.'member_tree';
		$sql="select count(regid) as members from 
				(select * from $table where club_id='$club_id' order by id_index) member_tree, 
				(select @pv := '$regid') initialisation 
				where find_in_set(parent_id, @pv) > 0 and @pv := concat(@pv, ',', regid) ";
		return $this->db->query($sql)->row()->members;
	}
	
	public function getalldetails($regid){
		$member=$this->getmemberdetails($regid);
		$member['password']=$this->db->get_where("users",array("id"=>$regid))->unbuffered_row()->vp;
		$getsponsor=$this->account->getuser(array("id"=>$member['refid']));
		$member['susername']=$getsponsor['user']['username'];
		$member['sname']=$getsponsor['user']['name'];
		$acc_details=$this->getaccdetails($regid);
		$nominee_details=$this->getnomineedetails($regid);
		$result=array("member"=>$member,"acc_details"=>$acc_details,"nominee_details"=>$nominee_details);
		return $result;
	}
	
	public function getmemberdetails($regid){
		return $this->db->get_where("members",array("regid"=>$regid))->unbuffered_row('array');
	}
	
	public function getaccdetails($regid){
		return $this->db->get_where("acc_details",array("regid"=>$regid))->unbuffered_row('array');
	}
	
	public function getnomineedetails($regid){
		return $this->db->get_where("nominee",array("regid"=>$regid))->unbuffered_row('array');
	}
	
	public function updatepayment($data){
        $data['added_on']=date('Y-m-d H:i:s');
		if($this->db->insert("transactions",$data)){
			return array('status'=>true,'message'=>'');
		}
		else{
			$err=$this->db->error();
            return array('status'=>false,'message'=>$err['message']);
		}
	}
	
	public function updatepersonaldetails($data,$where){
		if($this->db->update("members",$data,$where)){
			return true;
		}
		else{
			return $this->db->error();
		}
	}
	
	public function updatecontactinfo($data,$where){
		if($this->db->update("members",$data,$where)){
			if(!empty($data['name'])){ $userdata['name']=$data['name']; }
			if(!empty($data['mobile'])){ $userdata['mobile']=$data['mobile']; }
			if(!empty($data['email'])){ $userdata['email']=$data['email']; }
			$where2=array("id"=>$where['regid']);
            if(!empty($userdata)){
                $this->db->update("users",$userdata,$where2);
            }
			return true;
		}
		else{
			return $this->db->error();
		}
	}
	
	public function updatenomineedetails($data,$where){
		$checknominee=$this->db->get_where("nominee",$where)->num_rows();
		if($checknominee==0){
			$data['regid']=$where['regid'];
			$result=$this->db->insert("nominee",$data);
		}
		else{
			$result=$this->db->update("nominee",$data,$where);
		}
		if($result){
			return true;
		}
		else{
			return $this->db->error();
		}
	}
	
	public function updateaccdetails($data,$where){
		$checknominee=$this->db->get_where("acc_details",$where)->num_rows();
		if($checknominee==0){
			$data['regid']=$where['regid'];
			$result=$this->db->insert("acc_details",$data);
		}
		else{
			$result=$this->db->update("acc_details",$data,$where);
		}
		if($result){
			$where2=$where;
			$where2['account_no!=']='';
			$where2['ifsc!=']='';
			$where2['aadhar1!=']='';
			$where2['aadhar2!=']='';
			$where2['cheque!=']='';
			$where2['kyc!=']='1';
			$check=$this->db->get_where("acc_details",$where2)->num_rows();
			if($check!=0){
				$this->db->update("acc_details",array("kyc"=>"2"),$where);
			}
			return true;
		}
		else{
			return $this->db->error();
		}
	}
	
	public function updatephoto($data,$regid){
		if($this->db->update("users",$data,array("id"=>$regid))){
            $this->db->update("members",$data,array("regid"=>$regid));
            $this->session->set_userdata("photo",$data['photo']);
			return true;
		}
		else{
			return $this->db->error();
		}
	}
    
	public function updatepassword($data){
		$oldpass=$data['oldpass'];
		$password=$data['password'];
		$user=$data['user'];
		$where="md5(id)='$user'";
		$query = $this->db->get_where("users",$where);
		$result=$query->unbuffered_row('array');
		$checkpass=false;
		if(!empty($result)){
            $salt=$result['salt'];
            $result['password'];
            if(password_verify($oldpass.SITE_SALT.$salt,$result['password'])){
				$checkpass=true;
				$vp=$password;
                
                $password=$password.SITE_SALT.$salt;
                $password=password_hash($password,PASSWORD_DEFAULT);
                $data=array();
                $data['password']=$password;
                $data['vp']=$vp;
                $data['updated_on']=date('Y-m-d H:i:s');
                
				$this->db->where($where);
				$this->db->update("users",$data);
			}
		}
		return $checkpass;
	}
	
	public function updatetxnpassword($data){
		$password=$data['password'];
		$txn_password=$data['txn_password'];
		$user=$data['user'];
		$where="md5(id)='$user'";
		$query = $this->db->get_where("users",$where);
		$result=$query->unbuffered_row('array');
		$checkpass=false;
		if(!empty($result)){
            $salt=$result['salt'];
            $result['password'];
            if(password_verify($password.SITE_SALT.$salt,$result['password'])){
				$checkpass=true;
				$vp=$txn_password;
                
                $txn_password=$txn_password.SITE_SALT.$salt;
                $txn_password=password_hash($txn_password,PASSWORD_DEFAULT);
                $data=array();
                $data['txn_password']=$txn_password;
                $data['updated_on']=date('Y-m-d H:i:s');
                
				$this->db->where($where);
				$this->db->update("users",$data);
			}
		}
		return $checkpass;
	}
	
    public function verifytxnpassword($data){
        $username=$data['username'];
        $where=array("username"=>$username);
        $flag=true;
        $msg="Wrong Transaction Password!";
        $query=$this->db->get_where("users",$where);
        if($query->num_rows()==0){
            $flag=false;
        }
        else{
            $user=$query->unbuffered_row('array');
            $salt=$user['salt'];
            $txn_password=$user['txn_password'];
            if(!password_verify($data['txn_password'].SITE_SALT.$salt,$txn_password)){
                $flag=false;
            }
        }
        if($flag===true){
            return array("status"=>true,"message"=>"Transaction Approved!");
        }
        else{
            return array("status"=>false,"message"=>$msg);
        }
    }
    
	public function getmemberid($username,$status="activated",$package_id=NULL){
		$where="username='$username'";
		$query=$this->db->get_where("users",$where);
		if($query->num_rows()==1){
			$array=$query->row_array();
			$regid=$array['id'];
			$name=$array['name'];
			$statusarr=explode(',',$status);
			$status=array_shift($statusarr);
            $self=false;
			if($status=='self' && $regid!=0){
				if(md5($regid)==$this->session->user && $this->session->role=='member' ){
                    if(in_array('not activated',$statusarr)){
                        $status='not activated';
                        $self=true;
                    }
                    elseif(in_array('not club',$statusarr)){
                        $status='not club';
                        $self=true;
                    }
                    else{
                        $status='';
                    }
                }
				else{
                    $status=array_shift($statusarr);
                }
			}
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
				if($check!=1){
                    if($self===false){
                        $regid=0;$name="Member ID Already Activated!";
                    }
                    else{
                        $name=array($name,'Renew');
                    }
                }
				$status=array_shift($statusarr);
			}
			if($status=='day limit' && $regid!=0){
				$daylimit=date("Y-m-d",strtotime("-10 days"));
				$check=$this->db->get_where("members",array("regid"=>$regid,"date<"=>$daylimit))->num_rows();
				if($check==1){$regid=0;$name="You cannot activate this ID!";}
				$status=array_shift($statusarr);
			}
			if($status=='not club' && $package_id!==NULL && is_numeric($package_id)){
                if($package_id<11){
                    $club_id=$package_id-1;
                    $club='';
                    $clubs=array(1=>'Star',2=>'Diamond',3=>'Ambassador',4=>'Universal');
                    $club=$clubs[$club_id];
                    $check=0;
                    if($club_id>1){
                        $check=$this->db->get_where('club_members',['regid'=>$regid,'club_id'=>($club_id-1)])->num_rows();
                        if($check==0){$regid=0;$name="First Join ".$clubs[$club_id-1]." Club";}
                    }
                    if($check>0){
                        $check=$this->db->get_where('club_members',['regid'=>$regid,'club_id'=>$club_id])->num_rows();
                        if($check==1){$regid=0;$name="Member Already in Royal $club Club!";}
                    }
                }
                else{
                    if($package_id>11){
                        $club_id=$this->db->get_where('packages',['id'=>$package_id])->unbuffered_row()->club_id;
                        $prevclub_id=$club_id-1;
                        $prevclub=$this->db->get_where('clubs',['id'=>$prevclub_id])->unbuffered_row()->name;
                        $check=$this->db->get_where('member_tree',['regid'=>$regid,'club_id'=>$prevclub_id])->num_rows();
                        if($check==0){$regid=0;$name="First Join ".$prevclub." Club";}
                    }
                    if($check>0){
                        $club=$this->db->get_where('clubs',['id'=>$club_id])->unbuffered_row()->name;
                        $check=$this->db->get_where('member_tree',['regid'=>$regid,'club_id'=>$club_id])->num_rows();
                        if($check==1){$regid=0;$name="Member Already in Royal $club Club!";}
                    }
                }
			}
		}
		else{$regid=0;$name="Member ID not Available!";}
		$result=array("regid"=>$regid,"name"=>$name);
		return $result;
	}
	
    public function directmembers($where){
        $this->db->select("t1.*,t2.username");
        $this->db->where($where);
        $this->db->from("members t1");
        $this->db->join("users t2","t1.regid=t2.id");
        $query=$this->db->get();
        $array=$query->result_array();
        return $array;
    }
	
    public function clubmembers($where){
        $this->db->select("t1.*,t2.username,t2.name,t4.package");
        $this->db->where($where);
        $this->db->from("club_members t1");
        $this->db->join("users t2","t1.regid=t2.id");
        $this->db->join("epins t3","t1.epin_id=t3.id");
        $this->db->join("packages t4","t3.package_id=t4.id");
        $query=$this->db->get();
        $array=$query->result_array();
        return $array;
    }
    
    
	public function countlevelwisemembers($regid,$date=NULL){
		$where=array("regid"=>$regid);
		if($date!==NULL){
			$where['date(added_on)<=']=$date;
		}
		$this->db->select("level_id as level,count(*) as levelcount");
		$this->db->from("level_members");
		$this->db->where($where);
		$this->db->group_by("level_id");
		$this->db->order_by("level_id");
		$query=$this->db->get();
        //echo $this->db->last_query();
		$array=$query->result_array();
		return $array;
	}
	
	public function countlevelwiseactivemembers($regid,$date=NULL){
		$where=array("t1.regid"=>$regid);
		if($date!==NULL){
			$where['date(t3.added_on)<=']=$date;
		}
		$this->db->select("t1.level_id as level,count(*) as levelcount");
		$this->db->from("level_members t1");
		$this->db->join("(Select * from ".TP."members where status=1 ) t2","t2.regid=t1.member_id");
		$this->db->join("epin_used t3",'t2.regid=t3.used_by');
		$this->db->where($where);
		$this->db->group_by("t1.level_id");
		$this->db->order_by("t1.level_id");
		$query=$this->db->get();
		$array=$query->result_array();
        //echo $this->db->last_query();
        //print_pre($array);
		return $array;
	}
	
	public function levelwisemembers($regid,$date=NULL){
		$where=array("t1.regid"=>$regid,'t1.level_id<'=>22);
		if($date!==NULL){
			$where['date(t1.added_on)<=']=$date;
		}
        $columns="t1.member_id,t1.level_id as level,t2.username,t2.name,t3.mobile,t3.date,t3.activation_date,t3.status";
        $columns.=",t4.username as sponsor";
		$this->db->select($columns);
		$this->db->from("level_members t1");
		$this->db->join("users t2","t1.member_id=t2.id");
		$this->db->join("members t3","t1.member_id=t3.regid");
		$this->db->join("users t4","t3.refid=t4.id");
		$this->db->where($where);
		$this->db->order_by("t1.level_id");
		$query=$this->db->get();
		$array=$query->result_array();
		return $array;
	}
	
	public function levelwiseactivemembers($regid,$date=NULL){
		$where=array("t1.regid"=>$regid);
		if($date!==NULL){
			$where['date(t4.added_on)<=']=$date;
		}
		$this->db->select("t1.member_id,t1.level_id as level,t2.username,t2.name,t3.package_id");
		$this->db->from("level_members t1");
		$this->db->join("users t2","t1.member_id=t2.id");
		$this->db->join("(Select * from ".TP."members where status=1 ) t3","t3.regid=t1.member_id");
		$this->db->join("epin_used t4",'t3.regid=t4.used_by');
		$this->db->join("epins t5",'t4.epin_id=t5.id and t5.epin=t3.epin');
		$this->db->where($where);
		$this->db->order_by("t1.level_id");
		$query=$this->db->get();
		$array=$query->result_array();
        //echo print_r($array);
		return $array;
	}
	
	public function levelwisememberpackage($regid,$date=NULL){
		$where=array("t1.regid"=>$regid);
		if($date!==NULL){
			$where['date(t1.added_on)<=']=$date;
		}
		$this->db->select("t1.level_id as level,t3.package_id,count(*) as packagecount");
		$this->db->from("level_members t1");
		$this->db->join("users t2","t1.member_id=t2.id");
		$this->db->join("(Select * from ".TP."members where status=1 ) t3","t3.regid=t1.member_id");
		$this->db->where($where);
		$this->db->group_by("t1.level_id,t3.package_id");
		$this->db->order_by("t1.level_id");
		$query=$this->db->get();
		$array=$query->result_array();
        //echo print_r($array);
		return $array;
	}
	
	public function levelwiseclubmembers($regid,$date=NULL){
		$where=array("t1.regid"=>$regid);
		if($date!==NULL){
			$where['date(t4.added_on)<=']=$date;
		}
        $columns="t1.member_id,t1.level_id ,t1.level_id as level ,t2.username,t2.name,t3.club_id,t3.added_on";
        $columns.=",t6.username as sponsor";
		$this->db->select($columns);
		$this->db->from("level_members t1");
		$this->db->join("users t2","t1.member_id=t2.id");
		$this->db->join("(Select * from ".TP."club_members where status=1 ) t3","t3.regid=t1.member_id");
		$this->db->join("epin_used t4",'t3.regid=t4.used_by and t3.epin_id=t4.epin_id');
		$this->db->join("members t5","t1.member_id=t5.regid");
		$this->db->join("users t6","t5.refid=t6.id");
		$this->db->where($where);
		$this->db->order_by("t3.added_on");
		$query=$this->db->get();
		$array=$query->result_array();
        //echo print_r($array);
		return $array;
	}
	
    public function rankwisemembers($regid,$date=NULL){
		$where=array("t1.regid"=>$regid);
        $columns="t1.member_id,t1.level_id ,t1.level_id as level ,t2.username,t2.name,t3.club_id,t3.added_on";
        $columns.=",t6.username as sponsor,t7.name as club";
		$this->db->select($columns);
		$this->db->from("level_members t1");
		$this->db->join("users t2","t1.member_id=t2.id");
		$this->db->join("member_tree t3","t3.regid=t1.member_id");
		$this->db->join("members t5","t1.member_id=t5.regid");
		$this->db->join("users t6","t5.refid=t6.id");
		$this->db->join("clubs t7","t3.club_id=t7.id");
		$this->db->where($where);
		$this->db->order_by("t3.added_on");
		$query=$this->db->get();
		$array=$query->result_array();
        //echo print_r($array);
		return $array;
    }
    
	public function getallmembers($regid,$type="all"){
		$table=TP.$this->downline_table;
		$regids=NULL;
		$array=$result=array();
		$inclimit=$this->db->query("SET SESSION group_concat_max_len = 1000000;");
		$sql="select GROUP_CONCAT(regid SEPARATOR ',') as regids from 
				(select * from $table order by ".$this->downline_order.") member_tree, 
				(select @pv := '$regid') initialisation 
				where find_in_set(".$this->downline_parent.", @pv) > 0 and @pv := concat(@pv, ',', regid)";
		$exe=$this->db->query($sql);
		$regids=$exe->row()->regids;
		if($regids!==NULL){
			$regids=explode(',',$regids);
				$columns="t1.id as regid,t1.username,t1.name,t1.mobile,t1.vp as password,
                            concat_ws(',',t2.district,t2.state) as location,t2.refid,t3.username as ref,t3.name as refname,
                            t2.date,t2.activation_date,ifnull(t4.package,'--') as package,t2.status,t1.status as user_status,t2.photo";
				
				$this->db->group_start();
				$regid_chunks = array_chunk($regids,25);
				foreach($regid_chunks as $regid_chunk){
					$this->db->or_where_in('t1.id', $regid_chunk);
				}
				$this->db->group_end();
				
				$this->db->select($columns);
				$this->db->from('users t1');
				$this->db->join('members t2','t2.regid=t1.id','Left');
				$this->db->join('users t3','t2.refid=t3.id','Left');
				$this->db->join('packages t4','t2.package_id=t4.id','Left');
				if($type=="active"){
					$this->db->where("t2.status","1");
					$query=$this->db->get();
					$array=$query->result_array();
					$result['active']=$array;
					
					$this->db->group_start();
					$regid_chunks = array_chunk($regids,25);
					foreach($regid_chunks as $regid_chunk){
						$this->db->or_where_in('t1.id', $regid_chunk);
					}
					$this->db->group_end();
					
					$this->db->select($columns);
					$this->db->from('users t1');
					$this->db->join('members t2','t2.regid=t1.id','Left');
					$this->db->join('users t3','t2.refid=t3.id','Left');
					$this->db->join('packages t4','t2.package_id=t4.id','Left');
					$this->db->where("t2.status","0");
					$query=$this->db->get();
					$array=$query->result_array();
					$result['inactive']=$array;
				}
				else{
					$query=$this->db->get();
					$array=$query->result_array();
					$result=$array;
				}
		}
		return $result;
	}
	
    public function getnewmembers(){
        $columns="t1.username as member_id,t1.name as member_name,t3.username as sponsor_id,t3.name as sponsor_name";
        $this->db->select($columns);
        $this->db->limit(20);
        $this->db->order_by('t1.id desc');
        $this->db->where('t2.status=1');
        $this->db->from('users t1');
        $this->db->join('members t2','t1.id=t2.regid');
        $this->db->join('users t3','t3.id=t2.refid');
        $array=$this->db->get()->result_array();
        return $array;
	}
	
    public function getteammembers($regid){
        $array=$this->getallmembers($regid);
        $result=array();
        foreach($array as $key=>$value){
            $status='Active';
            if($value['status']==0){
                $status='In-Active';
            }
            if($value['refid']!=$regid){
                unset($array[$key]);
                continue;
            }
            
            $result[]=array('member_id'=>$value['username'],'member_name'=>$value['name'],
                               'sponsor_id'=>$value['ref'],'sponsor_name'=>$value['refname'],'status'=>$status);
        }
        return $result;
	}
	
    public function membersofmembers($regid,$result=array()){
        $directmembers=$this->getdirectmembers($regid);
        $result=$directmembers;
        foreach($directmembers as $key=>$directmember){
            $directs2=$this->getdirectmembers($directmember['regid']);
            $result[$key]['directs']=$directs2;
            foreach($directs2 as $key2=>$direct2){
                $directs3=$this->getdirectmembers($direct2['regid']);
                $result[$key]['directs'][$key2]['directs']=$directs3;
                foreach($directs3 as $key3=>$direct3){
                    $directs4=$this->getdirectmembers($direct3['regid']);
                    $result[$key]['directs'][$key2]['directs'][$key3]['directs']=$directs4;
                    foreach($directs4 as $key4=>$direct4){
                        $directs5=$this->getdirectmembers($direct4['regid']);
                        $result[$key]['directs'][$key2]['directs'][$key3]['directs'][$key4]['directs']=$directs5;
                        foreach($directs5 as $key5=>$direct5){
                            $directs6=$this->getdirectmembers($direct5['regid']);
                            $result[$key]['directs'][$key2]['directs'][$key3]['directs'][$key4]['directs'][$key5]['directs']=$directs6;
                            foreach($directs6 as $key6=>$direct6){
                                $directs7=$this->getdirectmembers($direct6['regid']);
                                $result[$key]['directs'][$key2]['directs'][$key3]['directs'][$key4]['directs'][$key5]['directs'][$key6]['directs']=$directs7;
                                foreach($directs7 as $key7=>$direct7){
                                    $directs8=$this->getdirectmembers($direct7['regid']);
                                    $result[$key]['directs'][$key2]['directs'][$key3]['directs'][$key4]['directs'][$key5]['directs'][$key6]['directs'][$key7]['directs']=$directs8;
                                }
                            }
                        }
                    }
                }
            }
        }
        return $result;
    }
	
    public function getdirectmembers($regid,$package=false,$active=false){
        $member=$this->member->getmemberdetails($regid);
        $package_id=$member['package_id'];
        $this->db->select("t1.regid,t1.name,t2.username,t1.mobile,t1.status,t1.photo");
        $where="t1.refid='$regid'";
        if($package===true){
            $where.=" and t1.package_id>='$package_id'";
        }
        if($active===true){
            $where.=" and t1.status='1'";
        }
        $this->db->where($where);
        $this->db->from("members t1");
        $this->db->join("users t2","t1.regid=t2.id");
        $query=$this->db->get();
        $array=$query->result_array();
        return $array;
    }
    
	public function kyclist($status=2){
		$where['t1.kyc']=$status;
		$this->db->select("t1.*, t2.username,t3.name,t3.aadhar,t3.pan as pan_no");
		$this->db->from('acc_details t1');
		$this->db->join('users t2','t1.regid=t2.id','Left');
		$this->db->join('members t3','t1.regid=t3.regid','Left');
		$this->db->where($where);
		$query=$this->db->get();
		 $array=$query->result_array(); 
		return $array;
	}
	
	public function approvekyc($data,$where){
		if($this->db->update("acc_details",$data,$where)){
			return true;
		}
		else{
			return $this->db->error();
		}
	}
	
	public function upgrademember($data){
		$regid=$data['regid'];
		$updata['epin']=$data['epin'];
		$updata['package_id']=$data['package_id'];
		$data['date']=date('Y-m-d');
        $getmember=$this->db->get_where("members",array("regid"=>$regid,"status"=>1));
        if($getmember->num_rows()==1){
            $package_id=$getmember->unbuffered_row()->package_id;
            if($data['package_id']>$package_id){
                if($this->db->get_where("epins",array("epin"=>$data['epin'],"status"=>0))->num_rows()==1){
                    if($this->db->update("members",$updata,array("regid"=>$regid))){
                        $added_on=$this->epin->updatepinstatus($data['epin'],$regid,$data['use_type']);
                        return array("status"=>true,"message"=>"Member Upgraded Successfully!");
                    }
                    else{
                        $err= $this->db->error();
                        return array("status"=>false,"message"=>$err['message']);
                    }
                }
                else{
                    return array("status"=>false,"message"=>"Invalid E-Pin!");
                }
            }
            else{
                return array("status"=>false,"message"=>"Package Lower than Current Package!");
            }
        }
        else{
            return array("status"=>false,"message"=>"First Activate Member!");
        }
	}
	
	public function renewmember($data){
		$regid=$data['regid'];
		$updata['epin']=$data['epin'];
		$updata['package_id']=$data['package_id'];
		$data['date']=date('Y-m-d');
        $getmember=$this->db->get_where("members",array("regid"=>$regid,"status"=>1));
        if($getmember->num_rows()==1){
            $member=$getmember->unbuffered_row('array');
            $package=$this->db->get_where("packages",array("id"=>$member['package_id']))->unbuffered_row('array');
            $last_date=$member['activation_date'];
            $this->db->order_by("id desc");
            $query=$this->db->get_where("epin_used",array("regid"=>$regid,"use_type"=>$data['use_type']));
            if($query->num_rows()>0){
                $lastrow=$query->unbuffered_row('array');
                $last_date=$lastrow['added_on'];
            }
            $end_date=date('Y-m-d',strtotime($last_date." +".$package['validity']."months"));
            if($end_date<date('Y-m-d')){
                if($data['package_id']>=$member['package_id']){
                    if($this->db->get_where("epins",array("epin"=>$data['epin'],"status"=>0))->num_rows()==1){
                        if($this->db->update("members",$updata,array("regid"=>$regid))){
                            $added_on=$this->epin->updatepinstatus($data['epin'],$regid,$data['use_type']);
                            return array("status"=>true,"message"=>"Member Upgraded Successfully!");
                        }
                        else{
                            $err= $this->db->error();
                            return array("status"=>false,"message"=>$err['message']);
                        }
                    }
                    else{
                        return array("status"=>false,"message"=>"Invalid E-Pin!");
                    }
                }
                else{
                    return array("status"=>false,"message"=>"Package Lower than Current Package!");
                }
            }
            else{
                return array("status"=>false,"message"=>"You cannot renew your ID right now!");
            }
        }
        else{
            return array("status"=>false,"message"=>"First Activate Member!");
        }
	}
	
    public function deletemember($regid){
        $allmembers=$this->member->getallmembers($regid,'active');
        if(empty($allmembers['active'])){
            if($this->db->delete('users',['id'=>$regid])){
                return array('status'=>true,"message"=>"Member Deleted Successfully!");
            }
            else{
                $err= $this->db->error();
                return array('status'=>false,"message"=>$err['message']);
            }
        }
        else{
            return array('status'=>false,'message'=>'Cannot Delete this Member!');
        }
    }
    
    public function getrewards($regid){
        $this->db->order_by('t1.club_id,level_id');
        $this->db->select("t1.*,t2.name as club,0 as status,'' as date");
        $this->db->from('club_income t1');
        $this->db->join('clubs t2','t1.club_id=t2.id');
        $rewards=$this->db->get()->result_array();
        if(!empty($rewards)){
            foreach($rewards as $key=>$single){
                $where=['regid'=>$regid,'club_id'=>$single['club_id'],'ci_id'=>$single['id'],'remarks'=>'Reward Income'];
                $getreward=$this->db->get_where('wallet',$where);
                if($getreward->num_rows()>0){
                    $date=$getreward->unbuffered_row()->date;
                    $rewards[$key]['status']=1;
                    $rewards[$key]['date']=$date;
                }
            }
        }
        return $rewards;
    }
    
}
