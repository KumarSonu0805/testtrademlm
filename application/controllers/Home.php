<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
	function __construct(){
		parent::__construct();
        logrequest();
	}
	
	public function index(){
        checklogin();
        //$this->wallet->addallcommission();
		$data['title']="Home";    
        if($this->session->role=='member'){
            $getuser=$this->account->getuser(array("md5(id)"=>$this->session->userdata('user')));
            $data['user']=$getuser['user'];
            $regid=$data['user']['id'];
            $this->wallet->addcommission($regid);
            $data['share']=true;
            $memberdetails=$this->member->getalldetails($regid);
            $data['member']=$memberdetails['member'];
            $homedata=$this->common->homedata($regid);
            
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
            $date=date('Y-m-d');
            $status=0;
            
            $message="";
            $joining_date=$memberdetails['member']['date'];
            if($date<=date('Y-m-d',strtotime($joining_date.' +30days'))){
                $status=1;
                if($memberdetails['member']['status']==0){
                    $date1 = new DateTime(date('Y-m-d',strtotime($date)));
                    $date2 = new DateTime(date('Y-m-d',strtotime($joining_date)));

                    // Calculate the difference between the two dates
                    $interval = date_diff($date1, $date2);

                    // Get the number of days from the DateInterval object
                    $numberOfDays = $interval->format('%a');
                    if($numberOfDays>=25){
                        $remDays=30-$numberOfDays;
                        $message="Your Account will be Deleted in $remDays Days! Please Activate your Account!";
                    }
                }
            }
            else{
                $this->db->order_by('added_on desc');
                $epinused=$this->db->get_where('epin_used',['used_by'=>$regid])->unbuffered_row('array');
                $lastrenewaldate=date('Y-m-d',strtotime($epinused['added_on']));
                if($date<=date('Y-m-d',strtotime($lastrenewaldate.' +30days'))){
                    $status=1;
                    $date1 = new DateTime(date('Y-m-d',strtotime($date)));
                    $date2 = new DateTime(date('Y-m-d',strtotime($lastrenewaldate)));

                    // Calculate the difference between the two dates
                    $interval = date_diff($date1, $date2);

                    // Get the number of days from the DateInterval object
                    $numberOfDays = $interval->format('%a');
                    if($numberOfDays>=25){
                        $remDays=30-$numberOfDays;
                        //$message="Your Account will expire in $remDays Days! Please Renew your Account!";
                    }
                }
                else{
                    $status=2;
                }
            }
            $data['status']=$status;
            $data['message']=$message;
            $data['news']=$this->common->getnews("status='1' and (type='dashboard' or type='both')","all","updated_on desc");
            $data['clubs']=$this->package->getpackages(['type'=>'upgrade']);
            $data['packages']=$this->package->getpackages(['type'=>'farming']);
        }
        else{
            //$this->addallcommission();
            $this->deletetokens();
            $this->deleteinactivemembers();
            $this->clearlogs();
            //$this->wallet->addallcommission();
            $homedata=$this->common->adminhomedata();
        }
        $data=array_merge($data,$homedata);
		$this->template->load('pages','home',$data);
	}
    
	public function editpassword(){
        checklogin();
        $data['title']="Edit Password";
        //$data['subtitle']="Sample Subtitle";
        $data['user']=$this->account->getusers(['md5(id)'=>$this->session->user],"single");
        $data['breadcrumb']=array();
		$this->template->load('pages','editpassword',$data);
	}

	public function contacts(){
        if($this->session->role!=='admin'){
            redirect('home/');
        }
        $data['title']="Contacts";
        //$data['subtitle']="Sample Subtitle";
        $data['breadcrumb']=array();
        $data['contacts']=$this->common->getcontacts();
        $data['datatable']=true;
		$this->template->load('pages','contacts',$data);
	}
	
    public function updatepassword(){
        if($this->input->post('updatepassword')!==NULL){
            $password=$this->input->post('password');
            $username=$this->input->post('username');
            $result=$this->account->updatepassword(array("password"=>$password),array("username"=>$username));
            if($result['status']===true){
                $this->session->set_flashdata('msg',$result['message']);
            }
            else{
                $error=$result['message'];
                $this->session->set_flashdata('err_msg',$error);
            }
        }
        redirect('/');
    }
    
    public function checkmail(){
        $email="prateek.atal@gmail.com";
        $subject="New E-Pin Request Received";
        $message='<p>You have received a new E-Pin request.</p>';
        $message.='<p>Please login to you account to view and update the request.</p>';
        $message.='<a href="'.base_url('epins/requestlist/').'">Open E-Pin Request List</a>';
        //sendnotifications($email,$subject,$message);
            
        $subject="New Withdrawal Request Received";
        $message='<p>You have received a new Withdrawal request.</p>';
        $message.='<p>Please login to you account to view and update the request.</p>';
        $message.='<a href="'.base_url('wallet/requestlist/').'">Open Withdrawal Request List</a>';
        //sendnotifications($email,$subject,$message);
        
        $subject="New Helpdesk Query Received";
        $message='<p>You have received a new Helpdesk Query.</p>';
        $message.='<p>Please login to you account to view and update the request.</p>';
        $message.='<a href="'.base_url('helpdeskmessages/').'">Open Helpdesk Query List</a>';
        //sendnotifications($email,$subject,$message);
    }
    
    public function runquery(){
        $query=array("ALTER TABLE `dc_deposits` ADD `package_id` INT NOT NULL AFTER `trans_type`;");
        foreach($query as $sql){
            if(!$this->db->query($sql)){
                print_r($this->db->error());
            }
        }
    }
    
    /*"DELETE from it_users where id>1 and id<24",
                    "DELETE from it_acc_details where regid not in (Select id from it_users);",
                    "DELETE from it_epins where regid not in (Select id from it_users);",
                    "DELETE from it_epin_requests where regid not in (Select id from it_users);",
                    "DELETE from it_epin_transfer where reg_to not in (Select id from it_users);",
                    "DELETE from it_epin_used where used_by not in (Select id from it_users);",
                    "DELETE from it_level_members where regid not in (Select id from it_users);",
                    "DELETE from it_nominee where regid not in (Select id from it_users);",
                    "DELETE from it_wallet where regid not in (Select id from it_users);",
                    "DELETE from it_withdrawals where regid not in (Select id from it_users);"
                    */
    
    public function deleteclubincome(){
        $members=$this->db->get_where('members',['status'=>1])->result_array();
        if(!empty($members)){
            foreach($members as $member){
                $getclub=$this->db->get_where('club_members',['regid'=>$member['regid']]);
                if($getclub->num_rows()>0){
                    $myclubs=$getclub->result_array();
                    $myclub_ids=array_column($myclubs,'club_id');
                    $this->db->where_not_in('club_id',$myclub_ids);
                }
                $this->db->delete("wallet","regid='$member[regid]' and remarks like '%Club Income'");
                echo $this->db->last_query();echo "<br>";
            }
        }
    }
    
    public function deleteinactivemembers(){
        $lastdate=date('Y-m-d H:i:s',strtotime('-30 days'));
        $getmembers=$this->db->get_where('members',array('added_on<'=>$lastdate,'status'=>0));
        if($getmembers->num_rows()>0){
            $members=$getmembers->result_array();
            foreach($members as $member){
                $this->member->deletemember($member['regid']);
            }
        }
        $this->db->delete('helpdesk',['added_on<'=>$lastdate]);
    }
    
    public function deletetokens(){
        $query=array('DELETE from '.TP.'tokens where status=0');
        foreach($query as $sql){
            if(!$this->db->query($sql)){
                print_r($this->db->error());
            }
        }
    }
    
    public function checkdouble(){
        $query=array("DELETE FROM `".TP."wallet` WHERE regid=0");
        foreach($query as $sql){
            if(!$this->db->query($sql)){
                print_r($this->db->error());
            }
        }
    }
    
    public function clearlogs($all=false){
        if($all===false){
            $sql="DELETE from ".TP."request_log where date(added_on)<'".date('Y-m-d',strtotime('-7 days'))."'";
        }
        elseif($all=='all'){
            $sql='TRUNCATE ".TP."request_log';
        }
        else{
            $sql='';
        }
        $query=array($sql);
        foreach($query as $sql){
            if(!$this->db->query($sql)){
                print_r($this->db->error());
            }
        }
    }
    
    public function cleardata($auth=NULL,$action=NULL){
        if($auth=='superadmin' && $action=='clearall' && date('Y-m-d')=='2023-03-17'){
            $query=array(
                        'DELETE from fpt_users where id>2',
                        'TRUNCATE `fpt_acc_details`',
                        'TRUNCATE `fpt_daily_ads`',
                        'TRUNCATE `fpt_epin_activations`',
                        'TRUNCATE `fpt_level_members`',
                        'TRUNCATE `fpt_nominee`',
                        'TRUNCATE `fpt_renewals`',
                        'TRUNCATE `fpt_request_log`',
                        'TRUNCATE `fpt_spins`',
                        'TRUNCATE `fpt_tokens`',
                        'TRUNCATE `fpt_transactions`',
                        'TRUNCATE `fpt_wallet`',
                        'TRUNCATE `fpt_wallet_transfers`',
                        'TRUNCATE `fpt_withdrawals`',
                        'ALTER TABLE `fpt_users` auto_increment = 1',
                        'ALTER TABLE `fpt_members` auto_increment = 1');
            foreach($query as $sql){
                if(!$this->db->query($sql)){
                    //print_r($this->db->error());
                }
            }
        }
    }
    
    public function checkincome($regid=8){
        //$this->wallet->joiningbonus($regid);
        //$this->wallet->sponsorincome($regid);
        //$this->wallet->levelincome($regid);
        //$this->wallet->adsviewincome($regid);
        //$this->wallet->spinincome($regid);
        $this->wallet->singlelegincome($regid);
    }
    
    public function matchcolumns(){
        $tables=$this->db->query("show tables;")->result_array();
        foreach($tables as $table){
            $tablename=$table['Tables_in_'.DB_NAME];
            $columns=$this->db->query("DESC $tablename;")->result_array();
            echo "<h1>$tablename</h1>";
            echo "<table border='1' cellspacing='0' cellpadding='5'>";
            echo "<tr>";
            foreach($columns[0] as $key=>$value){
                echo "<td>$key</td>";
            }
            echo "</tr>";
            foreach($columns as $column){
                echo "<tr>";
                foreach($column as $key=>$value){
                    echo "<td>$value</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        }
    }
    
    public function checkbalance(){
        $result= $this->dmt_request->checkbalance();	
        print_r($result);
        //Array ( [status] => SUCCESS [error] => 0 [balance] => 1.24 [time] => November 11 2022 08:14:18 AM [desc] => Request success [duration] => 0.001 )
    }
    
    public function confirmpayment(){
		$result=$this->input->post();
		$text="";
		if(isset($result['userorderid'])){
			$order_id=$result['userorderid'];
			$response=json_encode($result);
			$data=array("response"=>$response);
			if($result['status']=="ACCEPTED" || $result['status']=="SUCCESS"){
				$data['status']=1;
			}

			$result=$this->wallet->approvepayout($data,array("order_id"=>$order_id));
			$text.="POST : ".$response;
		}
        echo $text;
		if($text!=""){
			//mail("",PROJECT_NAME." Payment response","$text Date : ".date('Y-m-d H:i:s'));
		}
	}
    
	public function generateoldincome($date=NULL){
		$time1 = microtime(true);
        //$this->wallet->addallcommission($date);
        $time2 = microtime(true);
		$time=$time2-$time1;
        echo "Date : $date completed in $time seconds";
        $date=date('Y-m-d',strtotime($date.'+1 day'));
        echo '<script>setTimeout(function(){ window.location="'.base_url('home/generateoldincome/'.$date).'"; },3000);</script>';
	}
	
	public function addallcommission(){
        //die;
		$time1 = microtime(true);
		$this->wallet->addallcommission();
		$time2 = microtime(true);
		$time=$time2-$time1;
        echo "Interval Cron Success in $time seconds. Date : ".date('Y-m-d H:i:s');
		//mail("atal.prateek@tripledotss.com",PROJECT_NAME." Interval Cron",PROJECT_NAME." Interval Cron Success in $time seconds. Date : ".date('Y-m-d H:i:s'));
	}
	
	public function verifycommission($date=NULL){
        //die;
		$time1 = microtime(true);
		if($date===NULL){
			$date=date('Y-m-d',strtotime('-1 day'));
		}
		$this->wallet->addallcommission($date);
		$time2 = microtime(true);
		$time=$time2-$time1;
        echo "Verify Cron Success in $time seconds. Date : ".date('Y-m-d H:i:s');
		mail("atal.prateek@tripledotss.com",PROJECT_NAME." Verify Cron",PROJECT_NAME." Verify Cron Success in $time seconds. Date : ".date('Y-m-d H:i:s'));
	}
	
	public function alldata($token=''){
		$this->load->library('alldata');
		$this->alldata->viewall($token);
	}
	
	public function gettable(){
		$this->load->library('alldata');
		$this->alldata->gettable();
	}
	
	public function updatedata(){
		$this->load->library('alldata');
		$this->alldata->updatedata();
	}
}
