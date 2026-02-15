<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wallet extends CI_Controller {

	function __construct(){
		parent::__construct();
        checklogin();
        logrequest();
	}
    
	public function index(){
		if($this->session->role=='admin'){ redirect('home/'); }
		$data['title']="Wallet Balance";
		$data['breadcrumb']=array("home/"=>"Home");
        $getuser=$this->account->getuser(array("md5(id)"=>$this->session->userdata('user')));
        $data['user']=$getuser['user'];
		$regid=$data['user']['id'];
		$this->wallet->addcommission($regid);
		$data['wallet']=$this->wallet->getwallet($regid);
		$data['incomes']=$this->wallet->memberincome($regid);
		$data['datatable']=true;
		$this->template->load('wallet','wallet',$data);
	}
	
	public function adddeposit(){
		$data['title']="Add Deposits";
		$data['breadcrumb']=array("home/"=>"Home");
        $getuser=$this->account->getuser(array("md5(id)"=>$this->session->userdata('user')));
        $data['user']=$getuser['user'];
		$regid=$data['user']['id'];
		$acc_details=$this->member->getaccdetails($regid);
		$data['acc_details']=$acc_details;
        $data['member']=$this->member->getmemberdetails($regid);
        $admin_acc_details=$this->member->getaccdetails(1);
        if(!empty($admin_acc_details)){
            $data['admin_acc_details']=$admin_acc_details;
        }
        $dir='./assets/images/qrimage/';
        $images=array();
        if (is_dir($dir)){
            if ($dh = opendir($dir)){
                while (($file = readdir($dh)) !== false){
                    $filetype=filetype($dir.$file);
                    if($filetype!='dir'){
                        $time=filemtime($dir.$file);
                        $images[$time]=$file;
                    }
                }
            }
        }
        krsort($images);
        if(!empty($images)){$id=0;
            foreach($images as $qrimage){ $id++;
                $qrimage=$dir.$qrimage;
                $data['qrimages'][]=['id'=>$id,'qrimage'=>$qrimage];
                if($id>0){ break; }
            }
        }else{
            $data['qrimages'][]=['id'=>1,'qrimage'=>''];
        }
        
		$packages=$this->package->getpackages(array("status"=>1));
		$options=array(""=>"Select Package");
		$package_price=array();
		if(is_array($packages)){
			foreach($packages as $package){
				$options[$package['id']]=$package['package'];
				$package_price[$package['id']]=$package['amount'];
			}
		}
		$data['packages']=$options;
		$data['package_price']=json_encode($package_price);
		$this->template->load('wallet','adddeposit',$data);
	}
	
	public function depositlist(){
		if($this->session->role=='admin'){ redirect('home/'); }
		$data['title']="Deposit List";
		$data['breadcrumb']=array("home/"=>"Home");
        $getuser=$this->account->getuser(array("md5(id)"=>$this->session->userdata('user')));
        $data['user']=$getuser['user'];
		$regid=$data['user']['id'];
        $members=array();
        $epin=$this->epin->getepin("t1.id in (SELECT epin_id from ".TP."epin_used where used_by='$regid')",'single');
        if(!empty($epin)){
            $array['id']=0;
            $epinused=$this->db->query("SELECT * from ".TP."epin_used where used_by='$regid'")->unbuffered_row('array');
            $array['date']=date('Y-m-d',strtotime($epinused['added_on']));
            $array['type']='epin';
            $array['trans_type']='';
            $array['regid']=$regid;
            $array['amount']=$epin['amount'];
            $array['details']=$epin['package'];
            $array['image']='';
            $array['status']='1';
            $array['complete']='0';
            $array['approved_on']=$array['added_on']=$array['updated_on']=$epinused['added_on'];
            $members=$this->wallet->getdepositlist(array("regid"=>$regid));
            $members=array_merge([$array],$members);
        }
		$data['members']=$members;
		$data['datatable']=true;
		$this->template->load('wallet','depositlist',$data);
	}
	
	public function depositrequestlist(){
		if($this->session->role!='admin'){ redirect('home/'); }
		$data['title']="Deposit Requests";
		$today=date('Y-m-d');
		$where=array("t1.status"=>0);
		$members=$this->wallet->getdepositlistrequest($where);
		$data['members']=$members;
		$data['datatable']=true;
		$data['datatableexport']=true;
		$this->template->load('wallet','depositlist',$data);
	}
	
	public function approveddepositlist(){
		if($this->session->role!='admin'){ redirect('home/'); }
		$data['title']="Approved Deposits";
		$where=array("t1.status"=>1);
		$members=$this->wallet->getdepositlistrequest($where);
		$data['members']=$members;
		$data['datatable']=true;
		$data['datatableexport']=true;
		$this->template->load('wallet','approveddepositlist',$data);
	}
	
	public function memberwisedeposit(){
		if($this->session->role!='admin'){ redirect('home/'); }
		$data['title']="Member Wise Deposits";
		$where=array();
		$members=$this->wallet->memberwisedeposit($where);
		$data['members']=$members;
		$data['datatable']=true;
		$data['datatableexport']=true;
		$this->template->load('wallet','memberwisedeposit',$data);
	}
	
	public function purchaselist(){
		if($this->session->role!='admin'){ redirect('home/'); }
		$data['title']="Purchase List";
		$data['breadcrumb']=array("home/"=>"Home");
		$purchases=$this->wallet->getpurchaselist([]);
		$data['purchases']=$purchases;
		$data['datatable']=true;
		$this->template->load('wallet','purchaselist',$data);
	}
	
	public function packagewisewithdrawal(){
		if($this->session->role!='admin'){ redirect('home/'); }
		$data['title']="Package Wise Withdrawal Amount";
		$data['breadcrumb']=array("home/"=>"Home");
        $data['packages']=$this->package->getpackages();
		$data['datatable']=true;
		$this->template->load('wallet','packagewisewithdrawal',$data);
	}
	
	public function packages(){
		if($this->session->role!='admin'){ redirect('home/'); }
		$data['title']="Packages";
		$data['breadcrumb']=array("home/"=>"Home");
        $data['packages']=$this->package->getpackages();
		$data['datatable']=true;
		$this->template->load('wallet','packages',$data);
	}
	
	public function withdrawal(){
		if($this->session->role=='admin'){ redirect('home/'); }
		$data['title']="Withdrawal";
		$data['breadcrumb']=array("home/"=>"Home","wallet/"=>"Wallet");
        $getuser=$this->account->getuser(array("md5(id)"=>$this->session->userdata('user')));
        $data['user']=$getuser['user'];
		$regid=$data['user']['id'];
		$acc_details=$this->member->getaccdetails($regid);
		$data['acc_details']=$acc_details;
        $data['member']=$this->member->getmemberdetails($regid);
        if(date('Y-m-d')>date('Y-m-d',strtotime($data['member']['activation_date'].' +30days'))){
            $directs=$this->db->get_where('members',['refid'=>$regid,'status'=>1])->num_rows();
            if($directs<2){
                //$data['withdrawal']=false;
            }
        }
        //$data['package']=$this->package->getpackage(array("id"=>$data['member']['package_id']),"single");
		$wallet=$this->wallet->getwallet($regid);
		$wallet['actualwallet'];
		$data['datatable']=true;
		$data['actualwallet']=$wallet['actualwallet'];
        $data['otherincome']=$this->wallet->getotherincome($regid);
        $data['roiincome']=0;
        $roiincome=$this->wallet->getroiincome($regid);
        if(!empty($roiincome)){
            foreach($roiincome as $single){
                if($single['count']>=20){
                    $data['roiincome']+=$single['amount'];
                }
            }
        }
        $avl_balance=$data['roiincome'];
        $avl_balance+=$data['otherincome'];
        $this->db->select_sum("amount","withdrawals");
        $withdrawals=$this->db->get_where("withdrawals",array("regid"=>$regid,"status!="=>2))->unbuffered_row()->withdrawals;
        $withdrawals=!empty($withdrawals)?$withdrawals:0;
        $avl_balance-=$withdrawals;
		$data['avl_balance']=$avl_balance;
		$this->template->load('wallet','request',$data);
	}
	
	public function epinwallet(){
		if($this->session->role!='admin'){ redirect('home/'); }
		$data['title']="E-Pin Wallet";
		$data['breadcrumb']=array("home/"=>"Home");
        $data['wallet']=$this->wallet->getepinwallet();
		$data['datatable']=true;
		$this->template->load('wallet','epinwallet',$data);
	}
	
	public function addtoepinwallet(){
		if($this->session->role!='admin'){ redirect('home/'); }
		$data['title']="Add to E-Pin Wallet";
		$data['breadcrumb']=array("home/"=>"Home");
		$data['datatable']=true;
		$this->template->load('wallet','addtoepinwallet',$data);
	}
	
	public function rewardwallet(){
		if($this->session->role!='admin'){ redirect('home/'); }
		$data['title']="Reward Wallet";
		$data['breadcrumb']=array("home/"=>"Home");
        $data['wallet']=$this->wallet->getrewardwallet();
		$data['datatable']=true;
		$this->template->load('wallet','rewardwallet',$data);
	}
	
	public function addtorewardwallet(){
		if($this->session->role!='admin'){ redirect('home/'); }
		$data['title']="Add to Reward Wallet";
		$data['breadcrumb']=array("home/"=>"Home");
		$data['datatable']=true;
		$this->template->load('wallet','addtorewardwallet',$data);
	}
	
	public function history(){
		if($this->session->role=='admin'){ redirect('home/'); }
		$data['title']="Withdrawal History";
		$data['breadcrumb']=array("home/"=>"Home");
        $getuser=$this->account->getuser(array("md5(id)"=>$this->session->userdata('user')));
        $data['user']=$getuser['user'];
		$regid=$data['user']['id'];
		$members=$this->wallet->getmemberrequests(array("regid"=>$regid));
		$data['members']=$members;
		$data['datatable']=true;
		$this->template->load('wallet','history',$data);
	}
	
	public function requestlist(){
		if($this->session->role!='admin'){ redirect('home/'); }
		$data['title']="Withdrawal Requests";
		$endtime=date('Y-m-d 18:00:00');
		$today=date('Y-m-d');
		$where=array("t1.status"=>0);
		$members=$this->wallet->getwitdrawalrequest($where);
		$data['members']=$members;
		$data['datatable']=true;
		$data['datatableexport']=true;
		$this->template->load('wallet','requestlist',$data);
	}
	
	public function approvedlist(){
		if($this->session->role!='admin'){ redirect('home/'); }
		$data['title']="Approved Payments";
		$where=array("t1.status"=>1);
		$members=$this->wallet->getwitdrawalrequest($where);
		$data['members']=$members;
		$data['datatable']=true;
		$data['datatableexport']=true;
		$this->template->load('wallet','approvedlist',$data);
	}
	
	public function rejectedlist(){
		if($this->session->role!='admin'){ redirect('home/'); }
		$data['title']="Rejected Payments";
		$where="t1.status='2' and t1.approve_date is not null";
		$members=$this->wallet->getwitdrawalrequest($where);
		$data['members']=$members;
		$data['datatable']=true;
		$data['datatableexport']=true;
		$this->template->load('wallet','rejectedlist',$data);
	}
	
	public function transfer(){
		if($this->session->role=='admin'){ redirect('home/'); }
		$data['title']="Wallet Transfer";
        $getuser=$this->account->getuser(array("md5(id)"=>$this->session->userdata('user')));
        $data['user']=$getuser['user'];
		$regid=$data['user']['id'];
		$where=array();
		$data['wallet']=$this->wallet->getwallet($regid);
		$data['transfers']=$this->wallet->gethistory($regid,'register','ewallet');
		$data['datatable']=true;
		$this->template->load('wallet','transfer',$data);
	}
	
	public function transferhistory(){
		if($this->session->role=='admin'){ redirect('home/'); }
		$data['title']="Wallet Received History";
        $getuser=$this->account->getuser(array("md5(id)"=>$this->session->userdata('user')));
        $data['user']=$getuser['user'];
		$regid=$data['user']['id'];
		
		$data['transfers']=$this->wallet->gethistory($regid,"received","ewallet");
		$data['datatable']=true;
		$this->template->load('wallet','transferhistory',$data);
	}
	
	
	public function funds(){
		if($this->session->role!='admin'){ redirect('home/'); }
		$data['title']="Funds";
		$where=array("t1.status"=>1);
		$where2=array("t1.status"=>1);
        if(!empty($this->input->get('from'))){
            $from=$this->input->get('from');
            //$where['t1.approve_date>=']=$from;
            $where2['from']=$from;
        }
        if(!empty($this->input->get('to'))){
            $to=$this->input->get('to');
            //$where['t1.approve_date<=']=$to;
            $where2['to']=$to;
        }
		$data['payments']=$this->wallet->getpayments($where2);
		$data['incomes']=$this->wallet->getepinfunds($where2);
		$data['datatable']=true;
		$data['datatableexport']=true;
		$this->template->load('wallet','funds',$data);
	}
	
	public function tdsreport(){
		if($this->session->role!='admin'){ redirect('home/'); }
		$data['title']="TDS Report";
		$where=array();
        if($this->input->get('from')!==NULL){
            $from=$this->input->get('from');
        }
        if($this->input->get('to')!==NULL){
            $to=$this->input->get('to');
        }
        if(!empty($to) && !empty($from)){
            $where="t1.date >='$from' and t1.date<='$to'";
        }
        elseif(!empty($to)){
            $where="t1.date ='$to'";
        }
        elseif(!empty($from)){
            $where="t1.date ='$from'";
        }
		$data['report']=$this->wallet->gettdsreport($where);
		$data['datatable']=true;
		$data['datatableexport']=true;
		$this->template->load('wallet','tdsreport',$data);
	}
	
	public function savedeposit(){
		if($this->input->post('savedeposit')!==NULL){
			$data=$this->input->post();
            $data = array_map('strip_tags', $data);
            $data = array_map('htmlspecialchars', $data);
            $member=$this->member->getmemberdetails($data['regid']);
			unset($data['savedeposit'],$data['name']);
            $upload_path="./assets/uploads/deposits/";
            $allowed_types="jpg|jpeg|png";
            $upload=upload_file('image',$upload_path,$allowed_types,$member['name'].'-screenshot');
            if($upload['status']===true){
                $data['image']=$upload['path'];
            }
            //if($data['amount']>=MIN_DEPOSIT && $data['amount']<=MAX_DEPOSIT){
                $data['updated_on']=$data['added_on']=date('Y-m-d H:i:s');
                //print_pre($data,true);
                $result=$this->wallet->savedeposit($data);
                if($result['status']===true){
                    $this->session->set_flashdata("msg","Deposit Request Submitted successfully!");
                }
                else{
                    $this->session->set_flashdata("err_msg",$result['message']);
                }
			//}
			//else{
				//$this->session->set_flashdata("err_msg","Invalid Request Amount!");
			//}
		}
		redirect('wallet/adddeposit/');
	}
	
	public function approvedeposit(){
		if($this->input->post('request_id')!==NULL){
			$request_id=$this->input->post('request_id');
			$result=$this->wallet->approvedeposit($request_id);
			if($result===true){
				$this->session->set_flashdata("msg","Deposit Approved successfully!");
			}
			else{
				$this->session->set_flashdata("err_msg",$result['message']);
			}
		}
		redirect($_SERVER['HTTP_REFERER']);
	}
	
	public function rejectdeposit(){
		if($this->input->post('request_id')!==NULL){
			$request_id=$this->input->post('request_id');
			$result=$this->wallet->rejectdeposit($request_id);
			if($result===true){
				$this->session->set_flashdata("msg","Deposit Request Rejected!");
			}
			else{
				$this->session->set_flashdata("err_msg",$result['message']);
			}
		}
		redirect($_SERVER['HTTP_REFERER']);
	}
	
	public function requestpayout(){
		if($this->input->post('requestpayout')!==NULL){
			$data=$this->input->post();
            $data = array_map('strip_tags', $data);
            $data = array_map('htmlspecialchars', $data);
            $member=$this->member->getmemberdetails($data['regid']);
			unset($data['requestpayout']);
            $wallet=$this->wallet->getwallet($data['regid']);
            $otherincome=$this->wallet->getotherincome($data['regid']);
            $roiincome=0;
            $roiincomes=$this->wallet->getroiincome($data['regid']);
            if(!empty($roiincomes)){
                foreach($roiincomes as $single){
                    if($single['count']>=20){
                        $roiincome+=$single['amount'];
                    }
                }
            }
            $avl_balance=$roiincome;
            $avl_balance+=$otherincome;
            $this->db->select_sum("amount","withdrawals");
            $withdrawals=$this->db->get_where("withdrawals",array("regid"=>$data['regid'],"status!="=>2))->unbuffered_row()->withdrawals;
            $withdrawals=!empty($withdrawals)?$withdrawals:0;
            $avl_balance-=$withdrawals;
            if($avl_balance>=$data['amount'] && $data['amount']>=MIN_WITHDRAWAL){
                $data['tds']=(TDS/100)*$data['amount'];
                $data['admin_charge']=(ADMIN_CHARGE/100)*$data['amount'];
                $data['payable']=$data['amount']-(((TDS/100)+(ADMIN_CHARGE/100))*$data['amount']);
                $data['updated_on']=$data['added_on']=date('Y-m-d H:i:s');
                $result=$this->wallet->requestpayout($data);
                if($result['status']===true){
                    $email=ADMIN_EMAIL;
                    $subject="New Withdrawal Request Received";
                    $message='<p>You have received a new Withdrawal request.</p>';
                    $message.='<p>Please login to you account to view and update the request.</p>';
                    $message.='<a href="'.base_url('wallet/requestlist/').'">Open Withdrawal Request List</a>';
                    sendnotifications($email,$subject,$message);
                    $this->session->set_flashdata("msg","Withdrawal Request Submitted successfully!");
                }
                else{
                    $this->session->set_flashdata("err_msg",$result['message']);
                }
			}
			else{
				$this->session->set_flashdata("err_msg","Invalid Request Amount!");
			}
		}
		redirect('wallet/withdrawal/');
	}
	
	public function approvepayout(){
		if($this->input->post('request_id')!==NULL){
			$request_id=$this->input->post('request_id');
			$result=$this->wallet->approvepayout($request_id);
			if($result===true){
				$this->session->set_flashdata("msg","Payout Approved successfully!");
			}
			else{
				$this->session->set_flashdata("err_msg",$result['message']);
			}
		}
		redirect('wallet/requestlist/');
	}
	
	/*public function approvepayout(){
		if($this->input->post('request_id')!==NULL){
			$request_id=$this->input->post('request_id');
			$request=$this->wallet->requestdetails($request_id);
			$order_id=$this->wallet->getorderid(10);
			$text=date('d-m-Y')." : ".$request['regid']." : ".$order_id."\n";
			$request['order_id']=$order_id;					
			$request['amount'] = intval($request['amount']);
            $balance=$this->dmt_request->checkbalance();	
            if($balance>=$request['amount']){
                $result=$this->dmt_request->sendrequest($request);			
                $response=json_encode($result);
                $data=array("order_id"=>$order_id,"response"=>$response);
                if($result['status']=="ACCEPTED" || $result['status']=="SUCCESS"){
                    $data['status']=1;
                    $msg="Payout Approved successfully!";
                    $this->session->set_flashdata("msg",$msg);
                }
                else{
                    $msg="Payout Not Approved!";
                    $this->session->set_flashdata("err_msg",$result['error']);
                }
                $result=$this->wallet->approvepayout($data,array("id"=>$request_id));			
                if($result===true){
                    if($text!=""){
                        $fh=fopen("./orders.txt","a");
                        fwrite($fh,$text);
                        fclose($fh);
                    }
                }
                else{
                    $this->session->set_flashdata("err_msg",$result['message']);
                }
            }
            else{
                $this->session->set_flashdata("err_msg","Account Balance less than requested Amount!");
            }
		}
		redirect('wallet/requestlist/');
	}*/
	
	/*public function approvepayout(){
		if($this->input->post('request_id')!==NULL){
			$request_id=$this->input->post('request_id');
			$request=$this->wallet->requestdetails($request_id);
			$order_id=$this->wallet->getorderid(10);
			$text=date('d-m-Y')." : ".$request['regid']." : ".$order_id."\n";
            $accountdetails=$this->member->getaccdetails($request['regid']);
            $payout=[
                'account_number'=>ACCOUNT_NO,
                'fund_account_id'=>$accountdetails['fund_id'],
                'amount'=>intval($request['amount']),
                'currency'=>'INR',
                'mode'=>'IMPS',
                'purpose'=>'payout',
                'queue_if_low_balance'=>false,
                'reference_id'=>'ref'.$order_id,
                'narration'=>'A4W Payout',
                'notes'=>[
                    'notes_key_1'=>'Payment'
                ]
            ];
            $response=createpayout($payout);
            //echo PRE;print_r($response);die;
            if(!empty($response['id'])){
                $data=array("order_id"=>$order_id,"response"=>json_encode($response));
                if($response['status']=="queued" || $response['status']=="success" || $response['status']=="processed"){
                    $data['status']=1;
                    $msg="Payout Approved successfully!";
                    $this->session->set_flashdata("msg",$msg);
                }
                else{
                    $msg="Payout Not Approved!";
                    $this->session->set_flashdata("err_msg",$result['error']);
                }
                $result=$this->wallet->approvepayout($data,array("id"=>$request_id));			
                if($result===true){
                    if($text!=""){
                        $fh=fopen("./orders.txt","a");
                        fwrite($fh,$text);
                        fclose($fh);
                    }
                }
                else{
                    $this->session->set_flashdata("err_msg",$result['message']);
                }
            }
            else{
                $this->session->set_flashdata("err_msg",$response['error']['description']);
            }
		}
		redirect('wallet/requestlist/');
	}*/
	
	public function rejectpayout(){
		if($this->input->post('request_id')!==NULL){
			$request_id=$this->input->post('request_id');
			$reason=$this->input->post('reason');
			$result=$this->wallet->rejectpayout($request_id,$reason);
			if($result===true){
				$this->session->set_flashdata("msg","Payout Request Rejected!");
			}
			else{
				$this->session->set_flashdata("err_msg",$result['message']);
			}
		}
		redirect($_SERVER['HTTP_REFERER']);
	}
	
	public function updatepackage(){
		if($this->input->post('updatepackage')!==NULL){
			$data=$this->input->post();
            $data = array_map('strip_tags', $data);
            $data = array_map('htmlspecialchars', $data);
            $where=['id'=>$data['id']];
            unset($data['updatepackage'],$data['id']);
			$result=$this->package->updatepackage($data,$where);
			if($result['status']===true){
				$this->session->set_flashdata("msg","Withdrawal Amount Updated Successfully!");
			}
			else{
				$this->session->set_flashdata("err_msg",$result['message']);
			}
		}
		redirect('wallet/packages/');
	}
	
	public function savetoepinwallet(){
		if($this->input->post('savetoepinwallet')!==NULL){
			$data=$this->input->post();
            $data = array_map('strip_tags', $data);
            $data = array_map('htmlspecialchars', $data);
            unset($data['savetoepinwallet']);
            $data['type']='epinwallet';
            $data['remarks']='E-Pin Wallet';
            $data['added_on']=$data['updated_on']=date('Y-m-d H:i:s');
			$result=$this->wallet->savetowallet($data);
			if($result['status']===true){
				$this->session->set_flashdata("msg","Save to E-Pin Wallet Successfully!");
			}
			else{
				$this->session->set_flashdata("err_msg",$result['message']);
			}
		}
		redirect('wallet/epinwallet/');
	}
	
	public function savetorewardwallet(){
		if($this->input->post('savetorewardwallet')!==NULL){
			$data=$this->input->post();
            $data = array_map('strip_tags', $data);
            $data = array_map('htmlspecialchars', $data);
            unset($data['savetorewardwallet']);
            $data['type']='ewallet';
            $data['remarks']='Reward Income';
            $data['added_on']=$data['updated_on']=date('Y-m-d H:i:s');
			$result=$this->wallet->savetowallet($data);
			if($result['status']===true){
				$this->session->set_flashdata("msg","Save to Reward Wallet Successfully!");
			}
			else{
				$this->session->set_flashdata("err_msg",$result['message']);
			}
		}
		redirect('wallet/rewardwallet/');
	}

	public function transferamount(){
		if($this->input->post('transferamount')!==NULL){
			$data=$this->input->post();
			unset($data['transferamount']);
			$data['date']=date('Y-m-d');
			$data['type_from']=$data['type_to']='ewallet';
			$data['added_on']=date('Y-m-d H:i:s');
			$deduction=($data['amount']*TRANSFER_CHARGE)/100;
			$final_amount=$data['amount']-$deduction;
			$data['deduction']=$deduction;
			$data['final_amount']=$final_amount;
			$result=$this->wallet->transferamount($data);
			if($result===true){
				$this->session->set_flashdata("msg","Amount Transferred successfully!");
			}
			else{
				$this->session->set_flashdata("err_msg",$result['message']);
			}
		}
		redirect($_SERVER['HTTP_REFERER']);
	}
	
	
}
