<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//include Rest Controller library
require APPPATH . '/libraries/MyRestController.php'; 
 
class Wallet extends MyRestController { 
 
	function __construct(){
		parent::__construct();
        logrequest();
	}
    
    public function activateaccount_post(){
        $token=$this->post('token');
        $package_id=$this->post('package_id');
        $date=$this->post('date');
        $amount=$this->post('amount');
        $trans_type=$this->post('trans_type');
        $details=$this->post('details');
        $role='member';
        if(!empty($token) && !empty($date) &&!empty($package_id) && !empty($amount) && !empty($details) && !empty($trans_type)){
            $user=$this->account->verify_token($token);
            if(!empty($user) && is_array($user) && $user['role']==$role){
                $check=$this->db->get_where('epin_requests',['regid'=>$user['id'],'status'=>0])->num_rows();
                $check2=$this->db->get_where('epin_requests',['regid'=>$user['id'],'status'=>1])->num_rows();
                if($check==0 && $check2==0){
                    $date=date('Y-m-d',strtotime($date));
                    $data=array('type'=>'request','regid'=>$user['id'],'date'=>$date,'package_id'=>$package_id,'details'=>$details,'trans_type'=>$trans_type,'quantity'=>1);
                    $package=$this->db->get_where('packages',['id'=>$data['package_id']])->unbuffered_row('array');
                    $data['amount']=1*$package['amount'];
                    $upload_path='./assets/images/wallet/';
                    $allowed_types='gif|jpg|jpeg|png|svg|webp';
                    $filename=generate_slug($user['name'].'-request'.date('dmyhis'));
                    $upload=upload_file('image',$upload_path,$allowed_types,$filename,10000);
                    if($upload['status']===true){
                        $data['image']=$upload['path'];
                        //print_pre($data,true);
                        $result=$this->epin->requestepin($data);
                        
                        if($result['status']===true){
                            $email=ADMIN_EMAIL;
                            $subject="New E-Pin Request Received";
                            $message='<p>You have received a new E-Pin request.</p>';
                            $message.='<p>Please login to you account to view and update the request.</p>';
                            $message.='<a href="'.base_url('epins/requestlist/').'">Open E-Pin Request List</a>';
                            //sendnotifications($email,$subject,$message);
                        $this->customresponse('default',[],"Activation Request Submitted successfully!");
                        }
                        else{
                            $this->customresponse('error',[],$result['err']['message']);
                        }
                    }
                    else{
                        $this->customresponse('error',[],$upload['message']);
                    }
                }
                else{
                    $message=$check2>0?"Member Already Active!":"Activation Request Pending!";
                    $this->customresponse('error',[],$message);
                }
            }
            else{
                $this->customresponse('error',[],"Token Invalid");
            }
        }	
        else{
            $parameters=array();
            if(empty($token)){
                $parameters[]='token';
            }
            if(empty($date)){
                $parameters[]='date';
            }
            if(empty($package_id)){
                $parameters[]='package_id';
            }
            if(empty($amount)){
                $parameters[]='amount';
            }
            if(empty($details)){
                $parameters[]='details';
            }
            $this->customresponse('missingparameters',$parameters);
        }
    }

    public function adddeposit_post(){
        $token=$this->post('token');
        $package_id=$this->post('package_id');
        $date=$this->post('date');
        $amount=$this->post('amount');
        $trans_type=$this->post('trans_type');
        $details=$this->post('details');
        $role='member';
        if(!empty($token) && !empty($date) &&!empty($package_id) && !empty($amount) && !empty($details) && !empty($trans_type)){
            $user=$this->account->verify_token($token);
            if(!empty($user) && is_array($user) && $user['role']==$role){
                $member=$this->member->getmemberdetails($user['id']);
                $date=date('Y-m-d',strtotime($date));
                if($member['status']==1){
                    $data=array('type'=>'deposit','regid'=>$user['id'],'date'=>$date,'package_id'=>$package_id,'details'=>$details,'trans_type'=>$trans_type);
                    
                    $package=$this->db->get_where('packages',['id'=>$data['package_id']])->unbuffered_row('array');
                    $data['amount']=$package['amount'];
                    $upload_path="./assets/images/wallet/";
                    $allowed_types='gif|jpg|jpeg|png|svg|webp';
                    $upload=upload_file('image',$upload_path,$allowed_types,$member['name'].'-screenshot');
                    if($upload['status']===true){
                        $data['image']=$upload['path'];
                    }
                    $data['updated_on']=$data['added_on']=date('Y-m-d H:i:s');
                    //print_pre($data,true);
                    $result=$this->wallet->savedeposit($data);
                    if($result['status']===true){
                        $this->customresponse('default',[],"Deposit Request Submitted successfully!");
                    }
                    else{
                        $this->customresponse('error',[],$result['message']);
                    }
                }
                else{
                    $this->customresponse('error',[],"Member not Active! First Activate member to Add Deposit!");
                }
            }
            else{
                $this->customresponse('error',[],"Token Invalid");
            }
        }	
        else{
            $parameters=array();
            if(empty($token)){
                $parameters[]='token';
            }
            if(empty($date)){
                $parameters[]='date';
            }
            if(empty($package_id)){
                $parameters[]='package_id';
            }
            if(empty($amount)){
                $parameters[]='amount';
            }
            if(empty($details)){
                $parameters[]='details';
            }
            $this->customresponse('missingparameters',$parameters);
        }
    }

	public function getdepositlist_post(){
		$token=$this->input->post('token');
        if(!empty($token)){
            $user=$this->account->verify_token($token);
            if(!empty($user) && is_array($user) && $user['role']=='member'){
                $regid=$user['id'];
                $response=array();
                $epin=$this->epin->getepin("t1.id in (SELECT epin_id from ".TP."epin_used where used_by='$regid')",'single');
                if(!empty($epin)){
                    $array['id']="0";
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
                    $response=$this->wallet->getmemberdepositlist(array("t1.regid"=>$regid,'t1.status'=>1));
                    $response=array_merge([$array],$response);
                }
                if(!empty($response)){
                    foreach($response as $key=>$single){
                        //id name mobile registered datetime call whatsapp
                        $single['package']=$single['package']??$single['details'];
                        $selectedKeys = ["id","date","package","amount","status"]; // Keys to keep
                        $single = array_intersect_key($single, array_flip($selectedKeys));
                        $response[$key]=$single;
                    }
                }
                $this->customresponse('default',$response,"Deposit List Fetched Successfully");
            }
            else{
                $this->customresponse('error',[],"Token Invalid");
            }
        }	
        else{
            $parameters=array();
            if(empty($token)){
                $parameters[]='token';
            }
            $this->customresponse('missingparameters',$parameters);
        }
    }

	public function wallettransfer_post(){
		$token=$this->input->post('token');
		$member_id=$this->input->post('member_id');
		$amount=$this->input->post('amount');
        if(!empty($token) && !empty($member_id) && !empty($amount)){
            $user=$this->account->verify_token($token);
            if(!empty($user) && is_array($user) && $user['role']=='member'){
                $regid=$user['id'];
                $member=$this->member->getmemberid($member_id,'all');
                if($member['regid']>1 && $member['regid']!=$user['id']){
                    $wallet=$this->wallet->getwallet($user['id']);
                    if($wallet['actualwallet']>=$amount){
                        $data=array('date'=>date('Y-m-d'),'reg_from'=>$user['id'],'reg_to'=>$member['regid'],'amount'=>$amount);
                        $deduction=($amount*TRANSFER_CHARGE)/100;
                        $final_amount=$amount-$deduction;
                        $data['deduction']=$deduction;
                        $data['final_amount']=$final_amount;
                        $data['type_from']=$data['type_to']='ewallet';
                        $data['added_on']=date('Y-m-d H:i:s');
                        $result=$this->wallet->transferamount($data);
                        if($result===true){
                            $this->customresponse('default',$data,"Wallet Transfer Successful");
                        }
                        else{
                            $this->customresponse('error',[],$result['message']);
                        }
                    }
                    else{
                        $this->customresponse('error',[],"Transfer Amount is More than Available Balance! Please Try Again with A Lower Amount!");
                    }
                }
                else{
                    if($member['regid']==$user['id']){
                        $this->customresponse('error',[],"You have entered you own Member Id. Try Again with different Member ID");
                    }
                    else{
                        $this->customresponse('error',[],"Invalid Member Id");
                    }
                }
            }
            else{
                $this->customresponse('error',[],"Token Invalid");
            }
        }	
        else{
            $parameters=array();
            if(empty($token)){
                $parameters[]='token';
            }
            if(empty($member_id)){
                $parameters[]='member_id';
            }
            if(empty($amount)){
                $parameters[]='amount';
            }
            $this->customresponse('missingparameters',$parameters);
        }
    }

	public function wallettransferhistory_post(){
		$token=$this->input->post('token');
        if(!empty($token)){
            $user=$this->account->verify_token($token);
            if(!empty($user) && is_array($user) && $user['role']=='member'){
                $regid=$user['id'];
                $history=$this->wallet->gettransferhistory($regid);
                $this->customresponse('default',$history,"Wallet Transfer History Fetched Successfully");
            }
            else{
                $this->customresponse('error',[],"Token Invalid");
            }
        }	
        else{
            $parameters=array();
            if(empty($token)){
                $parameters[]='token';
            }
            $this->customresponse('missingparameters',$parameters);
        }
    }

	public function getwallet_post(){
		$token=$this->input->post('token');
        if(!empty($token)){
            $user=$this->account->verify_token($token);
            if(!empty($user) && is_array($user) && $user['role']=='member'){
                $this->wallet->addcommission($user['id']);
                $wallet=$this->wallet->getwallet($user['id']);
                $balance=$this->amount->toDecimal($wallet['actualwallet']);
                $incomes=$this->wallet->memberincome($user['id']);
                $joiningbonus=$referralincome=$dailyselfincome=$singlelegincome=$adsviewincome=$spinincome=0;
                $epinwallet=$rewardincome=$totalincome=0;
                
                $epinwallet=$this->wallet->getepinwalletamount($user['id']);
                
                if(!empty($incomes)){
                    foreach($incomes as $income){
                        if($income['remarks']=="Joining Bonus"){
                            $joiningbonus+=$income['amount'];
                        }
                        elseif($income['remarks']=="Sponsor Income"){
                            $referralincome+=$income['amount'];
                        }
                        elseif($income['remarks']=="Daily Self Income"){
                            $dailyselfincome+=$income['amount'];
                        }
                        elseif($income['remarks']=="Single Leg Income"){
                            $singlelegincome+=$income['amount'];
                        }
                        elseif($income['remarks']=="Spin Income"){
                            $spinincome+=$income['amount'];
                        }
                        elseif($income['remarks']=="Reward Income"){
                            $rewardincome+=$income['amount'];
                        }
                        $totalincome+=$income['amount'];
                    }
                }
                $joiningbonus=$this->amount->toDecimal($joiningbonus);
                $referralincome=$this->amount->toDecimal($referralincome);
                $dailyselfincome=$this->amount->toDecimal($dailyselfincome);
                $singlelegincome=$this->amount->toDecimal($singlelegincome);
                $adsviewincome=$this->amount->toDecimal($adsviewincome);
                $spinincome=$this->amount->toDecimal($spinincome);
                $rewardincome=$this->amount->toDecimal($rewardincome);
                $epinwallet=$this->amount->toDecimal($epinwallet);
                $totalincome=$this->amount->toDecimal($totalincome);
                $result=['balance'=>$balance,'joiningbonus'=>$joiningbonus,'referralincome'=>$referralincome,
                         'dailyselfincome'=>$dailyselfincome,'singlelegincome'=>$singlelegincome,
                         'spinincome'=>$spinincome,'rewardincome'=>$rewardincome,'epinwallet'=>$epinwallet,'totalincome'=>$totalincome];
                $this->response([
                        'status' => true,
                        'wallet'=>$result], REST_Controller::HTTP_OK);
            }
            else{
                $this->response([
                    'status' => false,
                    'message' => "Unauthorized Access!"], REST_Controller::HTTP_OK);
            }
        }
        else{
            $this->response([
				'status' => false,
				'message' => "Please provide all details!"], REST_Controller::HTTP_OK);
        }
	}	
    
    public function getwallethistory_post(){
        $token=$this->post('token');
        $role='member';
        if(!empty($token)){
            $user=$this->account->verify_token($token);
            if(!empty($user) && is_array($user) && $user['role']==$role){
                $regid=$user['id'];
                $history=$this->wallet->memberincome($regid);
                if(!empty($history)){
                    foreach($history as $key=>$single){
                        //id name mobile registered datetime call whatsapp
                        $selectedKeys = ["id","date","level_id","amount","remarks"]; // Keys to keep
                        if($single['remarks']=='Level 1 Income'){
                            $single['remarks']='Sponsor Income';
                        }
                        $single = array_intersect_key($single, array_flip($selectedKeys));
                        $history[$key]=$single;
                    }
                }
                $this->customresponse('default',$history,"Wallet History Fetched successfully");
            }
            else{
                $this->customresponse('error',[],"Token Invalid");
            }
        }	
        else{
            $parameters=array();
            if(empty($token)){
                $parameters[]='token';
            }
            $this->customresponse('missingparameters',$parameters);
        }
    }

    
	public function requestwithdrawal_post(){
		$token=$this->input->post('token');
		$amount=$this->input->post('amount');
		$txn_password=$this->input->post('txn_password');
        if(!empty($token) && !empty($amount)){
            $user=$this->account->verify_token($token);
            if(!empty($user) && is_array($user) && $user['role']=='member'){
                $wallet=$this->wallet->getwallet($user['id']);
                //print_pre($wallet,true);
                $date=date('Y-m-d');
                if($wallet['actualwallet']>=$amount){
                    $tds=round((TDS*$amount/100),2);
                    $admin_charge=round((ADMIN_CHARGE*$amount/100),2);
                    $total_amount=$amount-$tds-$admin_charge;
                    $total_amount=$this->amount->toDecimal($total_amount,true,3);
                    $data=array('regid'=>$user['id'],'date'=>$date,'amount'=>$amount,'tds'=>$tds,'admin_charge'=>$admin_charge,'payable'=>$total_amount);
                    if($wallet=='recharge'){
                        $data['point']=$amount;
                    }
                    $result=$this->wallet->savewithdrawalrequest($data);
                    if($result['status']===true){
                        $data=array('amount'=>$amount,'tds'=>$tds,'network_charge'=>$admin_charge,'net_payable'=>$total_amount);
                        $this->customresponse('default',$data,$result['message']);
                    }
                    else{
                        $this->customresponse('error',[],$result['message']);
                    }
                }
                else{
                    $this->customresponse('error',[],"Requested Amount is More than Available Balance! Please Try Again with A Lower Amount!");
                }
            }
            else{
                $this->customresponse('error',[],"Token Invalid");
            }
        }	
        else{
            $parameters=array();
            if(empty($token)){
                $parameters[]='token';
            }
            if(empty($amount)){
                $parameters[]='amount';
            }
            $this->customresponse('missingparameters',$parameters);
        }
    }

	public function withdrawalhistory_post(){
        $token=$this->post('token');
        if(!empty($token)){
            $user=$this->account->verify_token($token);
            if(!empty($user) && is_array($user) && $user['role']=='member'){
                $withdrawals=$this->wallet->getmemberrequests(['regid'=>$user['id']],true);
                $this->customresponse('default',$withdrawals,"Member Withdrawals Fetched Successfully");
            }
            else{
                $this->customresponse('error',[],"Token Invalid");
            }
        }	
        else{
            $parameters=array();
            if(empty($token)){
                $parameters[]='token';
            }
            $this->customresponse('missingparameters',$parameters);
        }
    }
    
	public function getrebateincome_post(){
		$token=$this->input->post('token');
        if(!empty($token)){
            $user=$this->account->verify_token($token);
            if(!empty($user) && is_array($user) && $user['role']=='member'){
                $rebateincome=$this->wallet->getrebateincome($user['id']);
                if(!empty($rebateincome)){
                $this->response([
                        'status' => true,
                        'rebateincome'=>$rebateincome], REST_Controller::HTTP_OK);
                }
                else{
                    $this->response([
                        'status' => false,
                        'message' => "No Earnings!"], REST_Controller::HTTP_OK);
                }
            }
            else{
                $this->response([
                    'status' => false,
                    'message' => "Unauthorized Access!"], REST_Controller::HTTP_OK);
            }
        }
        else{
            $this->response([
				'status' => false,
				'message' => "Please provide all details!"], REST_Controller::HTTP_OK);
        }
	}	
    
	public function getreferralincome_post(){
		$token=$this->input->post('token');
        if(!empty($token)){
            $user=$this->account->verify_token($token);
            if(!empty($user) && is_array($user) && $user['role']=='member'){
                $referralincome=$this->wallet->getreferralincome($user['id']);
                if(!empty($referralincome)){
                $this->response([
                        'status' => true,
                        'referralincome'=>$referralincome], REST_Controller::HTTP_OK);
                }
                else{
                    $this->response([
                        'status' => false,
                        'message' => "No Earnings!"], REST_Controller::HTTP_OK);
                }
            }
            else{
                $this->response([
                    'status' => false,
                    'message' => "Unauthorized Access!"], REST_Controller::HTTP_OK);
            }
        }
        else{
            $this->response([
				'status' => false,
				'message' => "Please provide all details!"], REST_Controller::HTTP_OK);
        }
	}	
    
}
