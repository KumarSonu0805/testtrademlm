<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Epins extends CI_Controller {
	var $transaction_image=true;
	var $reject_request=true;
	public function __construct(){
		parent::__construct();
        logrequest();
	}
	
	public function index(){
		checklogin();
		$data['title']="Generate E-Pin";
		$data['breadcrumb']=array("home/"=>"Home",""=>"E-Pins");
		
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
		if($this->session->role=='admin'){
			$this->template->load('epins','generate',$data);
		}
		else{
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

            if(!file_exists("./disclaimer.txt")){
                $fh=fopen("./disclaimer.txt",'w');
                fclose($fh);
            }
            $admin_acc_details=$this->member->getaccdetails(1);
            if(!empty($admin_acc_details)){
                $data['admin_acc_details']=$admin_acc_details;
            }
            $disclaimer=file_get_contents("./disclaimer.txt");
            $data['disclaimers'][]=['id'=>1,'disclaimer'=>$disclaimer];
            $getuser=$this->account->getuser(array("md5(id)"=>$this->session->userdata('user')));
            $data['user']=$getuser['user'];
			$regid=$data['user']['id'];
            $wallet=$this->wallet->getwallet($regid);
            $data['avl_balance']=$wallet['actualwallet'];
			$data['requests']=$this->epin->getmemberrequests(array("t1.regid"=>$regid));
			$data['transaction_image']=$this->transaction_image;
			$this->template->load('epins','request',$data);
		}
	}
	
	public function activateaccount(){
		checklogin();
		$data['title']="Activate Account";
		$data['breadcrumb']=array("home/"=>"Home");
		
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
		if($this->session->role=='admin'){
			$this->template->load('epins','generate',$data);
		}
		else{
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

            if(!file_exists("./disclaimer.txt")){
                $fh=fopen("./disclaimer.txt",'w');
                fclose($fh);
            }
            $admin_acc_details=$this->member->getaccdetails(1);
            if(empty($admin_acc_details)){
				$this->db->insert('acc_details',['regid'=>1]);
				$admin_acc_details=$this->member->getaccdetails(1);
            }
			$data['admin_acc_details']=$admin_acc_details;
            $disclaimer=file_get_contents("./disclaimer.txt");
            $data['disclaimers'][]=['id'=>1,'disclaimer'=>$disclaimer];
            $getuser=$this->account->getuser(array("md5(id)"=>$this->session->userdata('user')));
            $data['user']=$getuser['user'];
			$regid=$data['user']['id'];
            $wallet=$this->wallet->getwallet($regid);
            $data['avl_balance']=$wallet['actualwallet'];
			$data['requests']=$this->epin->getmemberrequests(array("t1.regid"=>$regid));
			$data['transaction_image']=$this->transaction_image;
			$this->template->load('epins','activateaccount',$data);
		}
	}
	
	public function newpins(){
		checklogin();
		$data['title']="My New E-Pin";
		$data['breadcrumb']=array("home/"=>"Home",""=>"E-Pins");
		$getuser=$this->account->getuser(array("md5(id)"=>$this->session->userdata('user')));
        $data['user']=$getuser['user'];
		$regid=$data['user']['id'];
		$epins=$this->epin->getepinbystatus('0',$regid);
		$data['epins']=$epins;
		$data['datatable']=true;
		$this->template->load('epins','newepins',$data);
	}
	
	public function usedepins(){
		checklogin();
		$data['title']="Used E-Pin";
		$data['breadcrumb']=array("home/"=>"Home",""=>"E-Pins");
		$getuser=$this->account->getuser(array("md5(id)"=>$this->session->userdata('user')));
        $data['user']=$getuser['user'];
		$regid=$data['user']['id'];
		$epins=$this->epin->getepinbystatus('1',$regid);
		$data['epins']=$epins;
		$data['datatable']=true;
		$this->template->load('epins','usedepins',$data);
	}
	
	public function transfer(){
		checklogin();
		$data['title']="Transfer E-Pin";
		$data['breadcrumb']=array("home/"=>"Home",""=>"E-Pins");
		$getuser=$this->account->getuser(array("md5(id)"=>$this->session->userdata('user')));
        $data['user']=$getuser['user'];
		$regid=$data['user']['id'];
		$packages=$this->package->getpackages(array("status"=>1));
		$options=array(""=>"Select Package");
		if(is_array($packages)){
			foreach($packages as $package){
				$options[$package['id']]=$package['package'];
			}
		}
		$data['packages']=$options;
		$this->template->load('epins','transfer',$data);
	}
	
	public function transferhistory(){
		checklogin();
		$data['title']="Transfer History";
		$data['breadcrumb']=array("home/"=>"Home",""=>"E-Pins");
		$data['datatable']=true;
		$regid='';
		if($this->session->role!='admin'){
            $getuser=$this->account->getuser(array("md5(id)"=>$this->session->userdata('user')));
            $data['user']=$getuser['user'];
			$regid=$data['user']['id'];
		}
		$data['transfers']=$this->epin->gethistory($regid);
		$this->template->load('epins','transferhistory',$data);
	}
	
	public function generationhistory(){
		checklogin();
		$data['title']="Generation E-Pin";
		$data['breadcrumb']=array("home/"=>"Home",""=>"E-Pins");
		$data['datatable']=true;
		$regid='';
		if($this->session->role!='admin'){
            $getuser=$this->account->getuser(array("md5(id)"=>$this->session->userdata('user')));
            $data['user']=$getuser['user'];
			$regid=$data['user']['id'];
			$data['transfers']=$this->epin->gethistory($regid,"generate");
		}
		else{
			$data['transfers']=$this->epin->gethistory('',"generate");
		}
		$this->template->load('epins','generationhistory',$data);
	}
	
	public function requestlist(){
		checklogin();
		if($this->session->role!='admin'){ redirect('/'); }
		$data['title']="Activation Request List";
		$data['breadcrumb']=array("home/"=>"Home",""=>"E-Pins");
		$members=$this->epin->getepinrequests(array("t1.status"=>"0"));
		$data['members']=$members;
		$data['datatable']=true;
		$this->template->load('epins','requestlist',$data);
	}
	
	public function approvedlist(){
		checklogin();
		if($this->session->role!='admin'){ redirect('/'); }
		$data['title']="E-Pin Approved List";
		$data['breadcrumb']=array("home/"=>"Home",""=>"E-Pins");
		$members=$this->epin->getepinrequests(array("t1.status"=>"1"));
		$data['members']=$members;
		$data['datatable']=true;
		$this->template->load('epins','approvedlist',$data);
	}
	
	public function requestdetails($id=NULL){
		checklogin();
		if($this->session->role!='admin'){ redirect('/'); }
		if($id===NULL){ redirect('epins/requestlist/'); }
		$data['title']="Activation Request Details";
		$data['breadcrumb']=array("home/"=>"Home",""=>"E-Pins");
		$data['user']['name']='admin';
		$data['user']['photo']='assets/images/blank.png';
		$where['t1.status']="0";
		$where['t1.id']=$id;
		$details=$this->epin->getepinrequests($where,"single");
		if(empty($details)){ redirect('epins/requestlist/'); }
		$data['details']=$details;
		$data['transaction_image']=$this->transaction_image;
		$data['reject_request']=$this->reject_request;
		$this->template->load('epins','requestdetails',$data);
	}
	
	public function generateepin(){
		checklogin();
		if($this->input->post('generateepin')!==NULL){
			$data=$this->input->post();
            $data = array_map('strip_tags', $data);
            $data = array_map('htmlspecialchars', $data);
			unset($data['generateepin']);
            $data['type']='generate';
			$result=$this->epin->generateepin($data);
			if($result===true){
				$this->session->set_flashdata("msg","E-Pins generated successfully!");
			}
			else{
				$this->session->set_flashdata("err_msg",$result['message']);
			}
		}
		redirect('epins/');
	}

	public function approveepin(){
		if($this->input->post('approveepin')!==NULL){
			$data=$this->input->post();
            $data = array_map('strip_tags', $data);
            $data = array_map('htmlspecialchars', $data);
			if($data['approveepin']=='Approve'){
				$request_id=$data['request_id'];
				unset($data['request_id']);
				unset($data['approveepin']);
                $data['quantity']+=$data['extra'];
				$result=$this->epin->generateepin($data);
				if($result===true){
					$this->epin->updaterequest($request_id);
					$this->session->set_flashdata("msg","Activation Request Approved successfully!");
				}
				else{
					$this->session->set_flashdata("err_msg",$result['message']);
				}
			}
			else{
				$request_id=$data['request_id'];
				$reason=$data['reason'];
				$this->epin->updaterequest($request_id,2,$reason);
				$this->session->set_flashdata("msg","Activation Request Rejected!");
			}
		}
		if($this->session->role=='admin'){
			redirect('epins/requestlist/');
		}else{
			redirect('epins/');
		}
	}

	public function requestactivation(){
		if($this->input->post('requestactivation')!==NULL){
			$data=$this->input->post();
            $data = array_map('strip_tags', $data);
            $data = array_map('htmlspecialchars', $data);
            $package=$this->db->get_where('packages',['id'=>$data['package_id']])->unbuffered_row('array');
            $data['amount']=$data['quantity']*$package['amount'];
			unset($data['requestactivation']);
			$name=$this->input->post('name');
			unset($data['name']);
            if($data['type']!='request'){
                $wallet=$this->wallet->getwallet($data['regid']);
                $avl_balance=$wallet['actualwallet'];
                if($data['amount']>$avl_balance){
                    $this->session->set_flashdata("err_msg","Please Try Again!");
					redirect('epins/');
                    exit;
                }
            }
			if($this->transaction_image && $data['type']=='request'&& $data['trans_type']!='CASH'){
				$upload_path="./assets/uploads/receipt/";
				$allowed_types="jpg|jpeg|png";
				$image=upload_file('image',$upload_path,$allowed_types,$name.'_receipt');
				if($image['status']===false){
					$this->session->set_flashdata("err_msg","Error Uploading Image! Please Try Again!");
					redirect('epins/');
                    exit;
				}
                else{
                    $data['image']=$image['path'];
                }
			}
            //print_pre($data,true);
			$result=$this->epin->requestepin($data);
			
			if($result['status']===true){
				if($data['type']!='request'){
					$_POST=array();
					$_POST['approveepin']='Approve';
					$_POST['regid']=$data['regid'];
					$_POST['package_id']=$data['package_id'];
					$_POST['quantity']=$data['quantity']+$data['extra'];
					$_POST['amount']=$data['amount'];
					$_POST['request_id']=$result['request_id'];
					$this->approveepin();
				}
                else{
                    $email=ADMIN_EMAIL;
                    $subject="New E-Pin Request Received";
                    $message='<p>You have received a new E-Pin request.</p>';
                    $message.='<p>Please login to you account to view and update the request.</p>';
                    $message.='<a href="'.base_url('epins/requestlist/').'">Open E-Pin Request List</a>';
                    //sendnotifications($email,$subject,$message);
                }
				$this->session->set_flashdata("msg","Activation Request Submitted successfully!");
			}
			else{
				$this->session->set_flashdata("err_msg",$result['err']['message']);
			}
		}
		redirect($_SERVER['HTTP_REFERER']);
	}

	public function requestepin(){
		if($this->input->post('requestepin')!==NULL){
			$data=$this->input->post();
            $data = array_map('strip_tags', $data);
            $data = array_map('htmlspecialchars', $data);
            $package=$this->db->get_where('packages',['id'=>$data['package_id']])->unbuffered_row('array');
            $data['amount']=$data['quantity']*$package['amount'];
			unset($data['requestepin']);
			$name=$this->input->post('name');
			unset($data['name']);
            if($data['type']!='request'){
                $wallet=$this->wallet->getwallet($data['regid']);
                $avl_balance=$wallet['actualwallet'];
                if($data['amount']>$avl_balance){
                    $this->session->set_flashdata("err_msg","Please Try Again!");
					redirect('epins/');
                    exit;
                }
            }
			if($this->transaction_image && $data['type']=='request'&& $data['trans_type']!='CASH'){
				$upload_path="./assets/uploads/receipt/";
				$allowed_types="jpg|jpeg|png";
				$image=upload_file('image',$upload_path,$allowed_types,$name.'_receipt');
				if($image['status']===false){
					$this->session->set_flashdata("err_msg","Error Uploading Image! Please Try Again!");
					redirect('epins/');
                    exit;
				}
                else{
                    $data['image']=$image['path'];
                }
			}
			$result=$this->epin->requestepin($data);
			
			if($result['status']===true){
				if($data['type']!='request'){
					$_POST=array();
					$_POST['approveepin']='Approve';
					$_POST['regid']=$data['regid'];
					$_POST['package_id']=$data['package_id'];
					$_POST['quantity']=$data['quantity']+$data['extra'];
					$_POST['amount']=$data['amount'];
					$_POST['request_id']=$result['request_id'];
					$this->approveepin();
				}
                else{
                    $email=ADMIN_EMAIL;
                    $subject="New E-Pin Request Received";
                    $message='<p>You have received a new E-Pin request.</p>';
                    $message.='<p>Please login to you account to view and update the request.</p>';
                    $message.='<a href="'.base_url('epins/requestlist/').'">Open E-Pin Request List</a>';
                    sendnotifications($email,$subject,$message);
                }
				$this->session->set_flashdata("msg","E-Pin Request Submitted successfully!");
			}
			else{
				$this->session->set_flashdata("err_msg",$result['err']['message']);
			}
		}
		redirect('epins/');
	}
	
	public function transferepin(){
		if($this->input->post('transferepin')!==NULL){
			$data=$this->input->post();
            $data = array_map('strip_tags', $data);
            $data = array_map('htmlspecialchars', $data);
			unset($data['transferepin']);
			$result=$this->epin->transferepin($data);
			if($result===true){
				$this->session->set_flashdata("msg","E-Pins Transferred successfully!");
			}
			else{
				$this->session->set_flashdata("err_msg",$result['message']);
			}
		}
		redirect('epins/transfer/');
	}

	public function getepinquantity(){
		$package_id=$this->input->post('package_id');
		$user=$this->session->userdata('user');
		$where['md5(t1.regid)']=$user;
		$where["t1.package_id"]=$package_id;
		$where["t1.status"]=0;
		$array=$this->epin->getepin($where);
		if(empty($array)){
			echo 0;
		}
		else{
			echo count($array);
		}
	}
	
	public function checkepin(){
        if($this->session->userdata('user')!==NULL){
            $data=$this->account->getuser(array("md5(id)"=>$this->session->userdata('user')));
            $regid=$data['user']['id'];
        }
        else{
		  	$regid=$this->input->post('regid');
        }
		$epin=trim($this->input->post('epin'));
		$checkepin=$this->epin->getepin(array("t1.epin"=>$epin,"t1.regid"=>$regid,"t1.package_id"=>1,"t1.status"=>0),"Single");
		if(is_array($checkepin)){
			echo 1;
		}else{
			echo 0;
		}
	}
    
    public function checkfranchisebonus(){
        $quantity=$this->input->post('quantity');
        $this->db->order_by('min_quantity desc');
        $franchisebonus=$this->common->getfranchisebonus(array("min_quantity<="=>$quantity),"single");
        echo json_encode($franchisebonus);
    }
    	
    
}
