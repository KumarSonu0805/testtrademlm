<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//include Rest Controller library
require APPPATH . '/libraries/MyRestController.php'; 
 
class Profile extends MyRestController { 
 
	function __construct(){
		parent::__construct();
        logrequest();
        //$this->load->library('imager');
	}
    
    public function getmemberstatus_post(){
        $token=$this->post('token');
        $role='member';
        if(!empty($token)){
            $user=$this->account->verify_token($token);
            if(!empty($user) && is_array($user) && $user['role']==$role){
                $response=array('profile'=>'0','contact'=>'0','nominee'=>'0','bank'=>'0','kyc'=>'0');
                
                $memberdetails=$this->member->getalldetails($user['id']);
                //print_pre($memberdetails,true);
                if($memberdetails['member']['father']!='' && $memberdetails['member']['aadhar']!='' && $memberdetails['member']['pan']!=''){
                    $response['profile']='1';
                }
                if($memberdetails['member']['address']!='' && $memberdetails['member']['state_id']!='' && $memberdetails['member']['district_id']!=''){
                    $response['contact']='1';
                }
                if(!empty($memberdetails['nominee_details']) && $memberdetails['nominee_details']['name']!='' && $memberdetails['nominee_details']['relation']!=''){
                    $response['nominee']='1';
                }
                if($memberdetails['acc_details']['account_no']!='' && $memberdetails['acc_details']['ifsc']!=''){
                    $response['bank']='1';
                }
                $response['kyc']=$memberdetails['acc_details']['kyc'];
                $this->customresponse('default',$response,'Member Details Fetched Successfully!');
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

    public function updatephoto_post(){
        $token=$this->post('token');
        if(!empty($token)){
            $user=$this->account->verify_token($token);
            if(!empty($user) && is_array($user) && $user['role']=='member'){
                $data=array();
                $mobile=$user['mobile'];
                $data['username']=$mobile; 
                $upload_path='./assets/images/profile/';
                $allowed_types='gif|jpg|jpeg|png|svg|webp';
                $upload=upload_file('photo',$upload_path,$allowed_types,generate_slug($user['name']),10000);
                if($upload['status']===true){
                    //$path = $this->imager->processimage('.'.$upload['path'],'cropscale',80,['width'=>300,'height'=>300]);
                    $data['photo']=$path;
                }
                //print_pre($data,true);
                if(!empty($data) && count($data)>1){
                    $data['role']='member';
                    $result=$this->account->updateuser($data,array("id"=>$user['id']));
                    if($result['status']===true){
                        $getuser=$this->account->getuser(['username'=>$mobile]);
                        $user=$getuser['user'];
                        $photo=empty($user['photo'])?file_url('assets/images/avatar.png'):$user['photo'];
                        $response=array("name"=>$user['name'],"mobile"=>$user['mobile'],"email"=>$user['email'],
                                        'photo'=>$photo);
                        $this->customresponse('default',$response,$result['message']);
                    }	
                    else{
                        $this->customresponse('error',[],$result['message']);
                    }
                }	
                else{
                    $message=trim($upload['msg']);
                    $message=strip_tags($message);
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
            $this->customresponse('missingparameters',$parameters);
        }
    }

    public function getmemberdetails_post(){
        $token=$this->post('token');
        if(!empty($token)){
            $user=$this->account->verify_token($token);
            if(!empty($user) && is_array($user) && $user['role']=='member'){
                $details=$this->member->getmemberdetails($user['id']);
                $getsponsor=$this->account->getuser(array("id"=>$details['refid']));
                $sponsor=$getsponsor['user'];
                $response=array('username'=>$user['username'],'name'=>$user['name'],'sponsor_id'=>$sponsor['username'],'sponsor_name'=>$sponsor['name']);
                
                $selectedKeys = ["dob", "father","gender","mstatus","occupation","aadhar","pan","date","activation_date"]; // Keys to keep
                $details = array_intersect_key($details, array_flip($selectedKeys));
                $details = array_map(function($value){
                            if($value=='0000-00-00' || $value===NULL){
                                $value='';
                            }
                            $timestamp = strtotime($value); // Convert string to timestamp
                            return $timestamp && date("Y-m-d", $timestamp) === $value ? date('d-m-Y', $timestamp) : $value;
                        }, $details);
                $response=array_merge($response,$details);
                $photo=empty($user['photo'])?file_url('assets/images/avatar.png'):$user['photo'];
                $response['photo']=$photo;
                $response['rank']="";
                $this->customresponse('default',$response,'Member Details Fetched Successfully!');
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

    public function saveprofile_post(){
        $token=$this->post('token');
        $dob=$this->post('dob');
        $father=$this->post('father');
        $gender=$this->post('gender');
        $mstatus=$this->post('mstatus');
        $occupation=$this->post('occupation');
        $aadhar=$this->post('aadhar');
        $pan=$this->post('pan');
        if(!empty($token) && !empty($dob) && !empty($father) && !empty($gender) && !empty($aadhar) && !empty($pan)){
            $user=$this->account->verify_token($token);
            if(!empty($user) && is_array($user) && $user['role']=='member'){
                $dob=date('Y-m-d',strtotime($dob));
                $aadhar_status=checkaadhar($user,$aadhar);
                $pan_status=checkpan($user,$pan);
                if(!$aadhar_status){
                    $this->customresponse('error',[],"Aadhar No. Already Added in Different Account!");
                }
                elseif(!$pan_status){
                    $this->customresponse('error',[],"PAN Already Added in Different Account!");
                }
                else{
                    $data=array('dob'=>$dob,'father'=>$father,'gender'=>$gender,'occupation'=>$occupation,'mstatus'=>$mstatus,'aadhar'=>$aadhar,
                                'pan'=>$pan);
                    $result=$this->member->updatepersonaldetails($data,['regid'=>$user['id']]);
                    if($result===true){
                        $this->customresponse('default',[],'Personal Details Updated Successfully!');
                    }
                    else{
                        $this->customresponse('error',[],$result);
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
            if(empty($dob)){
                $parameters[]='dob';
            }
            if(empty($father)){
                $parameters[]='father';
            }
            if(empty($gender)){
                $parameters[]='gender';
            }
            if(empty($mstatus)){
                $parameters[]='mstatus';
            }
            if(empty($aadhar)){
                $parameters[]='aadhar';
            }
            if(empty($pan)){
                $parameters[]='pan';
            }
            $this->customresponse('missingparameters',$parameters);
        }
    }

    public function getcontactdetails_post(){
        $token=$this->post('token');
        $role='member';
        if(!empty($token)){
            $user=$this->account->verify_token($token);
            if(!empty($user) && is_array($user) && $user['role']==$role){
                $details=$this->member->getmemberdetails($user['id']);
                $response=array();
                $selectedKeys = ["address", "state","state_id","district","district_id","pincode","mobile","email"]; // Keys to keep
                $details = array_intersect_key($details, array_flip($selectedKeys));
                $response=array_merge($response,$details);
                $this->customresponse('default',$response,'Contact Details Fetched Successfully!');
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

    public function updatecontactdetails_post(){
        $token=$this->post('token');
        $address=$this->post('address');
        $state_id=$this->post('state_id');
        $district_id=$this->post('district_id');
        $pincode=$this->post('pincode');
        $email=$this->post('email');
        $role='member';
        if(!empty($token) && !empty($address) && !empty($district_id) && !empty($state_id) && !empty($email)){
            $user=$this->account->verify_token($token);
            if(!empty($user) && is_array($user) && $user['role']==$role){
                $district=$this->common->getdistricts(['id'=>$district_id],'single');
                $state=$this->common->getstates(['id'=>$state_id],'single');
                $data=array('address'=>$address,'district'=>$district['name'],'state'=>$state['name'],'district_id'=>$district_id,'state_id'=>$state_id,'pincode'=>$pincode,
                            'email'=>$email);
                $result=$this->member->updatecontactinfo($data,['regid'=>$user['id']]);
                if($result===true){
                    $this->customresponse('default',[],'Contact Details Updated Successfully!');
                }
                else{
                    $this->customresponse('error',[],$result);
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
            if(empty($address)){
                $parameters[]='address';
            }
            if(empty($district_id)){
                $parameters[]='district_id';
            }
            if(empty($state_id)){
                $parameters[]='state_id';
            }
            if(empty($mobile)){
                $parameters[]='mobile';
            }
            if(empty($email)){
                $parameters[]='email';
            }
            $this->customresponse('missingparameters',$parameters);
        }
    }

    public function getnomineedetails_post(){
        $token=$this->post('token');
        $role='member';
        if(!empty($token)){
            $user=$this->account->verify_token($token);
            if(!empty($user) && is_array($user) && $user['role']==$role){
                $response=array();
                $details=$this->member->getnomineedetails($user['id']);
                $selectedKeys = ["name","mobile","relation"]; // Keys to keep
                $response = array_intersect_key($details, array_flip($selectedKeys));
                $this->customresponse('default',$response,'Nominee Details Fetched Successfully!');
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

    public function updatenomineedetails_post(){
        $token=$this->post('token');
        $name=$this->post('name');
        $relation=$this->post('relation');
        $mobile=$this->post('mobile');
        $role='member';
        if(!empty($token) && !empty($name) && !empty($mobile) && !empty($relation)){
            $user=$this->account->verify_token($token);
            if(!empty($user) && is_array($user) && $user['role']==$role){
                $data=array('name'=>$name,'mobile'=>$mobile,'relation'=>$relation);
                $result=$this->member->updatenomineedetails($data,['regid'=>$user['id']]);
                if($result===true){
                    $this->customresponse('default',[],'Nominee Details Updated Successfully!');
                }
                else{
                    $this->customresponse('error',[],$result);
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
            if(empty($name)){
                $parameters[]='name';
            }
            if(empty($mobile)){
                $parameters[]='mobile';
            }
            if(empty($relation)){
                $parameters[]='relation';
            }
            $this->customresponse('missingparameters',$parameters);
        }
    }

    public function getbankdetails_post(){
        $token=$this->post('token');
        $role='member';
        if(!empty($token)){
            $user=$this->account->verify_token($token);
            if(!empty($user) && is_array($user) && $user['role']==$role){
                $response=array();
                $bank_id='';
                $details=$this->member->getaccdetails($user['id']);
                $selectedKeys = ["bank","account_no","account_name","branch","ifsc","kyc","upi","remarks"]; // Keys to keep
                $response = array_intersect_key($details, array_flip($selectedKeys));
                if(!empty($response['bank'])){
                    $bank=$this->common->getbanks(['name'=>$details['bank']],'single');
                    $bank_id=$bank['id'];
                }
                $response['remarks']=($response['remarks']===NULL || $response['kyc']==1)?"":$response['remarks'];
                $response['bank_id']=$bank_id;
                $this->customresponse('default',$response,'Bank Details Fetched Successfully!');
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

    public function updatebankdetails_post(){
        $token=$this->post('token');
        $bank_id=$this->post('bank_id');
        $account_no=$this->post('account_no');
        $account_name=$this->post('account_name');
        $branch=$this->post('branch');
        $ifsc=$this->post('ifsc');
        $upi=$this->post('upi');
        $role='member';
        if(!empty($token) && !empty($bank_id) && !empty($account_no) && !empty($account_name) && !empty($branch) && !empty($ifsc)){
            $user=$this->account->verify_token($token);
            if(!empty($user) && is_array($user) && $user['role']==$role){
                $bank=$this->common->getbanks(['id'=>$bank_id],'single');
                if(!empty($bank)){
                    $accdetails=$this->member->getaccdetails($user['id']);
                    if(empty($accdetails) || $accdetails['kyc']==0 || $accdetails['kyc']==3){
                        $data=array('bank'=>$bank['name'],'account_no'=>$account_no,'account_name'=>$account_name,
                                    'branch'=>$branch,'ifsc'=>$ifsc,'upi'=>$upi);
                        $result=$this->member->updateaccdetails($data,['regid'=>$user['id']]);
                        if($result===true){
                            $this->customresponse('default',[],'Account Details Updated Successfully!');
                        }
                        else{
                            $this->customresponse('error',[],$result);
                        }
                    }
                    elseif($accdetails['kyc']==2){
                        $this->customresponse('error',[],"KYC Documents already Submitted! Contact Admin to update Details!");
                    }
                    else{
                        $this->customresponse('error',[],"KYC verified! Contact Admin to update Details!");
                    }
                }
                else{
                    $this->customresponse('error',[],"Bank not Avaiable!");
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
            if(empty($bank_id)){
                $parameters[]='bank_id';
            }
            if(empty($account_no)){
                $parameters[]='account_no';
            }
            if(empty($account_name)){
                $parameters[]='account_name';
            }
            $this->customresponse('missingparameters',$parameters);
        }
    }

    public function uploadkyc_post(){
        $token=$this->post('token');
        $status=$this->post('status')??0;
        $role='member';
        if(!empty($token)){
            $user=$this->account->verify_token($token);
            if(!empty($user) && is_array($user) && $user['role']==$role){
                if($role=='member'){
                    $details=$this->member->getmemberdetails($user['id']);
                    $aadhar_status=checkaadhar($user,$details['aadhar']);
                    $pan_status=checkpan($user,$details['pan']);
                }
                else{
                    $aadhar_status=$pan_status=true;
                }
                if(!$aadhar_status){
                    $this->customresponse('error',[],"Aadhar No. Already Added in Different Account!");
                }
                elseif(!$pan_status){
                    $this->customresponse('error',[],"PAN Already Added in Different Account!");
                }
                else{
                    if($status==1){
                        $accdetails=$this->member->getaccdetails($user['id']);
                    }
                    if(empty($accdetails) || $accdetails['kyc']==0 || $accdetails['kyc']==3){
                        $message="KYC Document Uploaded successfully!";
                        $where=array('regid'=>$user['id']);
                        $upload_path="./assets/uploads/documents/";
                        $allowed_types="jpg|jpeg|png|webp";
                        $file_name=$user['name'];
                        $upload=upload_file('pan',$upload_path,$allowed_types,$file_name.'_pan',10000);
                        if($upload['status']===true){
                            $data['pan']=$upload['path'];
                            $message="PAN image Uploaded successfully!";
                        }
                        $upload=upload_file('aadhar1',$upload_path,$allowed_types,$file_name.'_aadhar1',10000);
                        if($upload['status']===true){
                            $data['aadhar1']=$upload['path'];
                            $message="Aadhar Front image Uploaded successfully!";
                        }
                        $upload=upload_file('aadhar2',$upload_path,$allowed_types,$file_name.'_aadhar2',10000);
                        if($upload['status']===true){
                            $data['aadhar2']=$upload['path'];
                            $message="Aadhar Back image Uploaded successfully!";
                        }
                        $upload=upload_file('cheque',$upload_path,$allowed_types,$file_name.'_cheque',10000);
                        if($upload['status']===true){
                            $data['cheque']=$upload['path'];
                            $message="Cheque/Passbook image Uploaded successfully!";
                        }
                        $upload=upload_file('selfie',$upload_path,$allowed_types,$file_name.'_selfie',10000);
                        if($upload['status']===true){
                            $data['selfie']=$upload['path'];
                            $message="Selfie image Uploaded successfully!";
                        }
                        $data['status']=$status;
                        foreach($data as $key=>$value){
                            if(empty($value)){ unset($data[$key]); }
                        }
                        if(!empty($data)){
                            $data['added_on']=$data['updated_on']=date('Y-m-d H:i:s');
                            $result=$this->member->updateaccdetails($data,$where);
                            if($result===true){
                                $this->customresponse('default',[],$message);
                            }
                            else{
                                $this->customresponse('error',[],$result['message']);
                            }
                        }
                        else{
                            $this->customresponse('error',[],"KYC not uploaded! Please Try Again!");
                        }
                    }
                    elseif($accdetails['kyc']==2){
                        $this->customresponse('error',[],"KYC Documents already Submitted! Waiting for Approval!");
                    }
                    else{
                        $this->customresponse('error',[],"KYC verified! Contact Admin to update Details!");
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
            $this->customresponse('missingparameters',$parameters);
        }
    }

    public function getkycdetails_post(){
        $token=$this->post('token');
        $role='member';
        if(!empty($token)){
            $user=$this->account->verify_token($token);
            if(!empty($user) && is_array($user) && $user['role']==$role){
                $response=array();
                $bank_id='';
                $details=$this->member->getaccdetails($user['id']);
                $selectedKeys = ["aadhar1","aadhar2","pan","cheque","kyc","selfie","remarks"]; // Keys to keep
                $response = array_intersect_key($details, array_flip($selectedKeys));
                $remarks=($response['remarks']===NULL || $response['kyc']==1)?"":$response['remarks'];
                $response=array_map(function($item) {
                                return is_numeric($item) || empty($item)?$item:file_url($item);
                            },$response);
                $response['remarks']=$remarks;
                $this->customresponse('default',$response,'KYC Details Fetched Successfully!');
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

    public function changepassword_post(){
        $token=$this->post('token');
        $old_password=$this->post('old_password');
        $password=$this->post('password');
        $role='member';
        if(!empty($token) && !empty($old_password) && !empty($password)){
            $user=$this->account->verify_token($token);
            if(!empty($user) && is_array($user) && $user['role']==$role){
                if(password_verify($old_password.SITE_SALT.$user['salt'],$user['password'])){
                    $result=$this->account->updatepassword(array("password"=>$password),array("id"=>$user['id']));
                    if($result['status']===true){
                        $this->customresponse('default',[],'Password Changed Successfully!');
                    }
                    else{
                        $this->customresponse('error',[],$result['message']);
                    }
                }
                else{
                    $this->customresponse('error',[],"Old Password Does not Match!");
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
            if(empty($old_password)){
                $parameters[]='old_password';
            }
            if(empty($password)){
                $parameters[]='password';
            }
            $this->customresponse('missingparameters',$parameters);
        }
    }
    
	public function updateloginpin_post(){
        $token=$this->post('token');
        $login_pin=$this->post('pin');
        $password=$this->post('password');
        $role='member';
        if(!empty($token) && !empty($login_pin) && !empty($password)){
            $user=$this->account->verify_token($token);
            if(!empty($user) && is_array($user) && $user['role']==$role){
                if(password_verify($password.SITE_SALT.$user['salt'],$user['password'])){
                    $result=$this->member->setloginpin($user,$login_pin,'update');
                    if($result['status']===true){
                        $response=array("token"=>$token,'pin'=>1);
                        $this->customresponse('default',$response,$result['message']);
                    }
                    else{
                        $this->customresponse('error',[],$result['message']);
                    }
                }
                else{
                    $this->customresponse('error',[],"Password Does not Match!");
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
            if(empty($login_pin)){
                $parameters[]='pin';
            }
			$this->customresponse('missingparameters',$parameters);
		}
	}
    
	public function updatetransactionpin_post(){
        $token=$this->post('token');
        $transaction_pin=$this->post('pin');
        $password=$this->post('password');
        $role='member';
        if(!empty($token) && !empty($transaction_pin) && !empty($password)){
            $user=$this->account->verify_token($token);
            if(!empty($user) && is_array($user) && $user['role']==$role){
                if(password_verify($password.SITE_SALT.$user['salt'],$user['password'])){
                    $result=$this->member->settransactionpin($user,$transaction_pin,'update');
                    if($result['status']===true){
                        $response=array("token"=>$token,'pin'=>1);
                        $this->customresponse('default',$response,$result['message']);
                    }
                    else{
                        $this->customresponse('error',[],$result['message']);
                    }
                }
                else{
                    $this->customresponse('error',[],"Password Does not Match!");
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
            if(empty($transaction_pin)){
                $parameters[]='pin';
            }
			$this->customresponse('missingparameters',$parameters);
		}
	}
    

}