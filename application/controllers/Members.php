<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Members extends CI_Controller {
	var $epin_status=false;
	var $acc_details=false;
	var $reject_kyc=true;
	
	function __construct(){
		parent::__construct();
        logrequest();
	}
	
	public function index(){
		checklogin();
		$data['title']="Member Registration";
		$data['breadcrumb']=array("home/"=>"Home");
        $getuser=$this->account->getuser(array("md5(id)"=>$this->session->userdata('user')));
        $data['user']=$getuser['user'];
		$regid=$data['user']['id'];
		
		$data['parent_id']='';
		$options=array(""=>"Select Package");
		$packages=$this->package->getpackages();
		if(is_array($packages)){
			foreach($packages as $package){
				$options[$package['id']]=$package['package'].' (₹'.$package['amount'].')';
			}
		}
		$data['packages']=$options;
		$data['epin_status']=$this->epin_status;
        $data['username']=$this->member->generateusername();
		$this->template->load("members","register",$data);
	}
    
	public function registered(){
		checklogin();
		if($this->session->flashdata('mname')===NULL){
			redirect('members/');
		}
		$data['title']="Registration Details";
        $data['breadcrumb']=array("home/"=>"Home");
        $this->template->load('members','registered',$data);
	}
	
	public function memberlist(){
		checklogin();
		$data['title']="Member List";
		$data['breadcrumb']=array("home/"=>"Home");
        $getuser=$this->account->getuser(array("md5(id)"=>$this->session->userdata('user')));
        $data['user']=$getuser['user'];
        $regid=$data['user']['id'];
		
        $data['members']=$this->member->getallmembers($regid);
        $data['datatable']=true;
        if($this->session->role=='admin'){
            $data['datatableexport']=true;
        }
		$this->template->load("members","memberlist",$data);
	}
	
	public function activelist(){
		checklogin();
		$data['title']="Active Member List";
		$data['breadcrumb']=array("home/"=>"Home");
        $getuser=$this->account->getuser(array("md5(id)"=>$this->session->userdata('user')));
        $data['user']=$getuser['user'];
        $regid=$data['user']['id'];
		
        $allmembers=$this->member->getallmembers($regid,'active');
        if(!empty($allmembers['active'])){
            $data['members']=$allmembers['active'];
        }
        else{
            $data['members']=array();
        }
        $data['datatable']=true;
        if($this->session->role=='admin'){
            $data['datatableexport']=true;
        }
		$this->template->load("members","memberlist",$data);
	}
	
	public function inactivelist(){
		checklogin();
		$data['title']="In-Active Member List";
		$data['breadcrumb']=array("home/"=>"Home");
        $getuser=$this->account->getuser(array("md5(id)"=>$this->session->userdata('user')));
        $data['user']=$getuser['user'];
        $regid=$data['user']['id'];
		
        $allmembers=$this->member->getallmembers($regid,'active');
        if(!empty($allmembers['inactive'])){
            $data['members']=$allmembers['inactive'];
        }
        else{
            $data['members']=array();
        }
        $data['datatable']=true;
        if($this->session->role=='admin'){
            $data['datatableexport']=true;
        }
		$this->template->load("members","memberlist",$data);
	}
	
	public function levelwisemembers(){
		checklogin();
		$data['title']="Level Wise Member List";
		$data['breadcrumb']=array("home/"=>"Home");
        $getuser=$this->account->getuser(array("md5(id)"=>$this->session->userdata('user')));
        $data['user']=$getuser['user'];
        $regid=$data['user']['id'];
		if($regid==1){
            $regid=2;
            $getuser=$this->account->getuser(array("id"=>$regid));
            $data['user']=$getuser['user'];
        }
        $details=$this->member->getalldetails($regid);
        $member=$details['member'];
        $member['username']=$data['user']['username'];
        $member['sponsor']=$member['susername'];
        $member['level']='Owner';
        $members=$this->member->levelwisemembers($regid);
        $members=array_merge([$member],$members);
        $data['members']=$members;
        $data['datatable']=true;
		$this->template->load("members","levelwisemembers",$data);
	}
	
	public function levelwiseclubmembers(){
		checklogin();
		$data['title']="Club Wise Member List";
		$data['breadcrumb']=array("home/"=>"Home");
        $getuser=$this->account->getuser(array("md5(id)"=>$this->session->userdata('user')));
        $data['user']=$getuser['user'];
        $regid=$data['user']['id'];
		if($regid==1){
            $regid=2;
            $getuser=$this->account->getuser(array("id"=>$regid));
            $data['user']=$getuser['user'];
        }
        $details=$this->member->getalldetails($regid);
        $member=$details['member'];
        $member['username']=$data['user']['username'];
        $member['sponsor']=$member['susername'];
        $member['level']='Owner';
        $members=$this->member->levelwiseclubmembers($regid);
        //$members=array_merge([$member],$members);
        $data['members']=$members;
        $data['datatable']=true;
		$this->template->load("members","levelwiseclubmembers",$data);
	}
	
	public function clubmembers(){
		checklogin();
		$data['title']="Club Member List";
		$data['breadcrumb']=array("home/"=>"Home");
        $getuser=$this->account->getuser(array("md5(id)"=>$this->session->userdata('user')));
        $data['user']=$getuser['user'];
        $regid=$data['user']['id'];
		
        $data['datatable']=true;
        $where=array('t1.status'=>1);
        if($this->session->role=='admin'){
            $data['datatableexport']=true;
        }
        $data['members']=$this->member->clubmembers($where);
		$this->template->load("members","clubmembers",$data);
	}
	
	public function treeview(){
		checklogin();
		$data['title']="Tree View";
        $getuser=$this->account->getuser(array("md5(id)"=>$this->session->userdata('user')));
        $data['user']=$getuser['user'];
        $regid=$data['user']['id'];
        $uri=$this->uri->segment(2);
        
        if($uri=='bronzetree'){
            $data['title']="Bronze Tree";
            $club_id=1;
        }
        elseif($uri=='silvertree'){
            $data['title']="Silver Tree";
            $club_id=2;
        }
        elseif($uri=='goldtree'){
            $data['title']="Gold Tree";
            $club_id=3;
        }
        elseif($uri=='rubytree'){
            $data['title']="Ruby Tree";
            $club_id=4;
        }
        elseif($uri=='pearltree'){
            $data['title']="Pearl Tree";
            $club_id=5;
        }
        elseif($uri=='platinumtree'){
            $data['title']="Platinum Tree";
            $club_id=6;
        }
        elseif($uri=='emeraldtree'){
            $data['title']="Emerald Tree";
            $club_id=7;
        }
        elseif($uri=='topaztree'){
            $data['title']="Topaz Tree";
            $club_id=8;
        }
        elseif($uri=='sapphiretree'){
            $data['title']="Sapphire Tree";
            $club_id=9;
        }
        elseif($uri=='ambassadortree'){
            $data['title']="Ambassador Tree";
            $club_id=10;
        }
        else{$club_id='';}
        if($club_id!=''){
            $getregid=$this->db->get_where('member_tree',['club_id'=>$club_id,'parent_id'=>0]);
            if($getregid->num_rows()>0 && $this->session->role=='admin'){
                $regid=$getregid->unbuffered_row()->regid;
            }
        }
        
        $data['pagesegment']=$uri;
        if($regid>1){
            $details=$this->member->getmemberdetails($regid);
            $data['user']['photo']=$details['photo'];
        }
        else{
            $data['user']['photo']=file_url("assets/images/male.png");
        }
        $data['club_id']=$club_id;
		$regids=generateTree($regid,$club_id);
        $data['top_id']=$regid;
		$data['packages']=$this->package->getpackages(array("status"=>1));
		$data['tree']=createTree($regids);
		$this->template->load('members','treeview',$data);
	}
	
	public function edit($regid){
		checklogin();
		if($this->session->role!='admin'){ redirect('home/'); }
		$data['title']="Edit Member";
		$data['breadcrumb']=array("home/"=>"Home");
        $getuser=$this->account->getuser(array("md5(id)"=>$regid));
        if($getuser['status']==false){
            redirect('members/memberlist/');
        }
        $data['user']=$getuser['user'];
        $regid=$data['user']['id'];
		$memberdetails=$this->member->getalldetails($regid);
		$data=array_merge($data,$memberdetails);
        //echo PRE;print_r($data);die;
		
		//if($data['member']['status']==0){ redirect('home/'); }
		$options=array(""=>"Select Bank","xyz"=>"Others");
        
		$banks=$this->common->getbanks();
		if(is_array($banks)){
			foreach($banks as $bank){
				$options[$bank['name']]=$bank['name'];
			}
		}
		$data['banks']=$options;
        
		$data['package']=$this->package->getpackages(array("id"=>$data['member']['package_id']),'Single');
        $this->template->load('members','editmember',$data);
	}
	
	public function details($regid){
		checklogin();
		if($this->session->role!='admin'){ redirect('home/'); }
		$data['title']="Member Details";
		$data['breadcrumb']=array("home/"=>"Home");
        $getuser=$this->account->getuser(array("md5(id)"=>$regid));
        if($getuser['status']==false){
            redirect('members/memberlist/');
        }
        $data['user']=$getuser['user'];
        $regid=$data['user']['id'];
		$memberdetails=$this->member->getalldetails($regid);
		$data=array_merge($data,$memberdetails);
        //echo PRE;print_r($data);die;
		
		//if($data['member']['status']==0){ redirect('home/'); }
		$options=array(""=>"Select Bank","xyz"=>"Others");
        
		$banks=$this->common->getbanks();
		if(is_array($banks)){
			foreach($banks as $bank){
				$options[$bank['name']]=$bank['name'];
			}
		}
		$data['banks']=$options;
		$this->wallet->addcommission($regid);
		$data['wallet']=$this->wallet->getwallet($regid);
        //print_pre($data,true);
		$data['package']=$this->package->getpackages(array("id"=>$data['member']['package_id']),'Single');
        $this->template->load('members','details',$data);
	}
	
	public function membertree(){
		checklogin();
		$data['title']="Member Tree";
		$data['breadcrumb']=array("home/"=>"Home");
        $getuser=$this->account->getuser(array("md5(id)"=>$this->session->userdata('user')));
        $data['user']=$getuser['user'];
        $regid=$data['user']['id'];
		
        $data['treechart']=true;
		$this->template->load("members","membertree",$data);
	}
    
	public function tree(){
		checklogin();
		$data['title']="Member Tree";
		$data['breadcrumb']=array("home/"=>"Home");
        $getuser=$this->account->getuser(array("md5(id)"=>$this->session->userdata('user')));
        $user=$getuser['user'];
        $regid=$user['id'];
        if($regid>1){
            $member=$this->member->getmemberdetails($regid);
        }
        else{
            $member['refid']=NULL;
        }
        $data['members']=$this->member->getallmembers($regid);
        $data['usernames']=!empty($data['members'])?array_column($data['members'],'username'):array();
        $hierarchy = buildHierarchyWithQueue($data['members'], $regid);
        $hierarchy = array(['regid'=>$regid,'refid'=>$member['refid'],'name'=>$user['name'],'username'=>$user['username'],
                            'children'=>$hierarchy]);
        //print_pre($hierarchy,true);
        
        $htmlHierarchy = generateHierarchyHTML($hierarchy,0,$data['usernames'],$data['members']);
        //print_pre($htmlHierarchy,true);
        $data['htmlHierarchy']=$htmlHierarchy;
        $data['styles']=['link'=>'https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css'];
        $data['bottom_script']=['link'=>'https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js'];
		$this->template->load("members","tree",$data);
	}
    	
    public function gettreedata(){
        $data=$levels=$nodes=array();
        $username=$this->input->post('username');
        $getuser=$this->account->getuser(array("username"=>$username));
        if($getuser['status']===true){
            $user=$getuser['user'];
            
            $members=$this->member->getallmembers($user['id']);
            $nodes[]=['id'=>$username,'title'=>$username,'name'=>$user['name']];
            foreach($members as $member){
                $data[]=[$member['ref'],$member['username']];
                $node=array('id'=>$member['username'],'title'=>$member['username'],'name'=>$member['name']);
                $nodes[]=$node;
            }
        }
        $result=array('data'=>$data,'levels'=>$levels,'nodes'=>$nodes);
        echo json_encode($result);
    }
	
	public function kyc(){
		checklogin();
		$data['title']="Member KYC Requests";
		$data['breadcrumb']=array("home/"=>"Home");
		$members=$this->member->kyclist();
		$data['members']=$members;
		$data['datatable']=true;
		$data['reject_kyc']=$this->reject_kyc;
		$this->template->load('members','kyclist',$data);
	}
    
	public function approvedkyc(){
		checklogin();
		$data['title']="Approved Member KYC";
		$data['breadcrumb']=array("/"=>"Home");
		$members=$this->member->kyclist(1);
		$data['members']=$members;
		$data['datatable']=true;
		$this->template->load('members','kyclist',$data);
	}
	
	public function renewals(){
		checklogin();
		$data['title']="Member Renewals";
		$data['breadcrumb']=array("home/"=>"Home");
		$members=$this->wallet->renewallist();
		$data['members']=$members;
		$data['datatable']=true;
		$this->template->load('members','renewallist',$data);
	}
	
	public function rankwisemembers(){
		checklogin();
		$data['title']="Rank Wise Member List";
		$data['breadcrumb']=array("home/"=>"Home");
        $getuser=$this->account->getuser(array("md5(id)"=>$this->session->userdata('user')));
        $data['user']=$getuser['user'];
        $regid=$data['user']['id'];
		if($regid==1){
            $regid=2;
            $getuser=$this->account->getuser(array("id"=>$regid));
            $data['user']=$getuser['user'];
        }
        $details=$this->member->getalldetails($regid);
        $member=$details['member'];
        $member['username']=$data['user']['username'];
        $member['sponsor']=$member['susername'];
        $member['level']='Owner';
        $members=$this->member->rankwisemembers($regid);
        //$members=array_merge([$member],$members);
        $data['members']=$members;
        $data['datatable']=true;
		$this->template->load("members","rankwisemembers",$data);
	}
	
	public function rewards(){
		checklogin();
        if($this->session->role=='admin'){
            redirect('members/memberlist/');
        }
		$data['title']="Rewards";
		$data['breadcrumb']=array("home/"=>"Home");
        $getuser=$this->account->getuser(array("md5(id)"=>$this->session->userdata('user')));
        $user=$getuser['user'];
        $regid=$user['id'];
        $data['rewards']=$this->member->getrewards($regid);
        //print_pre($data,true);
		$this->template->load('members','rewards',$data);
	}
	
    public function addmember(){
        if($this->input->post('addmember')!==NULL){
			$data=$this->input->post();
            //print_pre($data);
            $data = array_map('strip_tags', $data);
            $data = array_map('htmlspecialchars', $data);
			$userdata=$memberdata=$accountdata=array();
			if($data['refid']>0){
				$userdata['username']=$data['username'];
				$userdata['mobile']=$data['mobile'];
				$userdata['name']=$data['name'];
				$userdata['email']=$data['email'];
				$userdata['role']="member";
				$userdata['status']="1";
				
				if(isset($data['epin'])){
					$memberdata['epin']=$data['epin'];
				}
				$memberdata['name']=$data['name'];
                if(!empty($data['dob'])){
                    $memberdata['dob']=$data['dob'];
                }
				$memberdata['father']=!empty($data['father'])?$data['father']:'';
				$memberdata['gender']=!empty($data['gender'])?$data['gender']:'';
				$memberdata['mstatus']=!empty($data['mstatus'])?$data['mstatus']:'';
				$memberdata['mobile']=!empty($data['mobile'])?$data['mobile']:'';
				$memberdata['email']=!empty($data['email'])?$data['email']:'';
				$memberdata['aadhar']=!empty($data['aadhar'])?$data['aadhar']:'';
				$memberdata['address']=!empty($data['address'])?$data['address']:'';
				$memberdata['district']=!empty($data['district'])?$data['district']:'';
				$memberdata['state']=!empty($data['state'])?$data['state']:'';
				$memberdata['pincode']=!empty($data['pincode'])?$data['pincode']:'';
				$memberdata['refid']=!empty($data['refid'])?$data['refid']:'';
				$memberdata['date']=!empty($data['date'])?$data['date']:date('Y-m-d');
				$memberdata['time']=date('H:i:s');
				$memberdata['status']=0;
				$upload_path="./assets/uploads/members/";
				$allowed_types="jpg|jpeg|png";
				$file_name=$data['name'];
				$upload=upload_file('photo',$upload_path,$allowed_types,$data['name'].'-photo');
                if($upload['status']===true){
                    $memberdata['photo']=$upload['path'];
                    $userdata['photo']=$upload['path'];
                }
				
                
				$data=array("userdata"=>$userdata,"memberdata"=>$memberdata,"accountdata"=>$accountdata);
                //print_pre($data,true);
				$result=$this->member->addmember($data);
				if($result['status']===true){
                    if(strpos($memberdata['name']," ")){
                        $name=substr($memberdata['name'],0,strpos($memberdata['name']," "));
                    }
                    else{
                        $name=$memberdata['name'];
                    }
                    
					$message = "Welcome $name! Thank you for joining ".PROJECT_NAME.". Your Username is $result[username] and Password is $result[password].";
					$smsdata=array("mobile"=>$memberdata['mobile'],"message"=>$message);
					//send_sms($smsdata);
					$flash=array("mname"=>$memberdata['name'],"uname"=>$result['username'],"pass"=>$result['password']);
					$this->session->set_flashdata($flash);
					$this->session->set_flashdata("msg","Member Added successfully!");
                    if($this->session->user!==NULL){
					   redirect('members/registered/');
                    }
                    else{
					   redirect('registered/');
                    }
				}
				else{
					$this->session->set_flashdata("err_msg",$result['message']);
				}
			}
			else{
				$this->session->set_flashdata("err_msg","Invalid Sponsor ID!");
			}
		}
		redirect('members/');
    }
    
	public function getrefid(){
        $username=$this->input->post('username');
		$status=$this->input->post('status');
        if($username=='admin' && $this->session->role=='admin'){
            $member['regid']=1;
            $member['name']='Admin';
        }
        else{
            $member=$this->member->getmemberid($username,$status);
            if($member['regid']==1 && $this->session->role!='admin'){
                $member['regid']=0;
                $member['name']='Member ID not Available!';
            }
        }
		echo json_encode($member);
	}
		
	public function getmember(){
		$username=$this->input->post('username');
		$status=$this->input->post('status');
        $status=empty($status)?'activated':$status;
		$package_id=$this->input->post('package_id');
        $package_id=empty($package_id)?'activated':$package_id;
		$member=$this->member->getmemberid($username,$status,$package_id);
        
		echo json_encode($member);
	}
		
	public function activatemember(){
        if($this->input->post('activatemember')!==NULL){
            $data=$this->input->post();
            $data = array_map('strip_tags', $data);
            $data = array_map('htmlspecialchars', $data);
            unset($data['activatemember']);
            $data['activation_date']=date('Y-m-d');
            $data['activation_time']=date('H:i:s');
            $result=$this->member->activatemember($data);
            if($result['status']===true){
                $this->session->set_flashdata("msg",$result['message']);
            }
            else{
                $this->session->set_flashdata("err_msg",$result['message']);
            }
        }
        if($this->input->post('joinclub')!==NULL){
            $data=$this->input->post();
            $data = array_map('strip_tags', $data);
            $data = array_map('htmlspecialchars', $data);
            unset($data['joinclub']);
            //print_pre($data,true);
            if($data['package_id']<10){
                $result=$this->member->joinclub($data);
                if($result['status']===true){
                    $this->session->set_flashdata("msg",$result['message']);
                }
                else{
                    $this->session->set_flashdata("err_msg",$result['message']);
                }
            }
            else{
                $result=$this->member->jointree($data);
                if($result['status']===true){
                    $this->session->set_flashdata("msg",$result['message']);
                }
                else{
                    $this->session->set_flashdata("err_msg",$result['message']);
                }
            }
        }
        if($this->input->post('changememberstatus')!==NULL){
            $regid=$this->input->post('regid');
            $status=$this->input->post('status');
            $data=array('status'=>$status);
            $result=$this->account->updateuserstatus($data,['id'=>$regid]);
            if($result['status']===true){
                $text="Blocked";
                if($status==1){
                    $text="Un-blocked";
                }
                $this->session->set_flashdata("msg","Member $text Successfully!");
            }
            else{
                $this->session->set_flashdata("err_msg",$result['message']);
            }
        }
        redirect($_SERVER['HTTP_REFERER']);
	}
    
	public function delete($regid=NULL){
        $getuser=$this->account->getuser(array("md5(id)"=>$regid));
        if($getuser['status']===true){
            $result=$this->member->deletemember($getuser['user']['id']);
            if($result['status']===true){
                $this->session->set_flashdata("msg","Member Deleted Successfully!");
            }
            else{
                $this->session->set_flashdata("err_msg",$result['message']);
            }
            
        }
        else{
            $this->session->set_flashdata("err_msg","Member Not Found!");
        }
        redirect('members/inactivelist/');
	}
		
	public function approverenewal(){
		if($this->input->post('renewal')!==NULL){
			$data['status']=$this->input->post('renewal');
			$where['regid']=$this->input->post('regid');
			$result=$this->wallet->approverenewal($data,$where);
			if($result===true){
                if($data['renewal']==1){ $status="Approved"; }
				$this->session->set_flashdata("msg","Member Renewal $status!");
			}
			else{
				$this->session->set_flashdata("err_msg",$result['message']);
			}
		}
		redirect('members/renewals/');
	}
	
	public function approvekyc(){
		if($this->input->post('kyc')!==NULL){
			$data['kyc']=$this->input->post('kyc');
			$data['reason']=$this->input->post('reason');
			$where['regid']=$this->input->post('regid');
			$result=$this->member->approvekyc($data,$where);
			if($result===true){
				if($data['kyc']==3){ $status="Rejected"; }
				elseif($data['kyc']==1){ $status="Approved"; }
				$this->session->set_flashdata("msg","Member KYC $status!");
			}
			else{
				$this->session->set_flashdata("err_msg",$result['message']);
			}
		}
		redirect('members/kyc/');
	}
		
	public function gettree(){
		$regid=$this->input->post('regid');
		$club_id=$this->input->post('club_id');
		if((int)$regid==0){
			$where['username']=str_replace('a','',$regid);
			$array=$this->account->getuser($where);
			$regid=$array['user']['id'];
			/*$user=$this->account->getuser(array("md5(id)"=>$this->session->userdata('user')));
            $members=$this->member->getallmembers($user_id,array(),"array");
			if(array_search($regid,$members)===false){
				$regid='';
			}*/
		}
		if($regid!=''){
			$regids=generateTree($regid,$club_id);
			$tree=createTree($regids);
			echo $tree;
		}
		else{
			echo "invalid";
		}
	}
	
		
}