<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends CI_Controller {

	function __construct(){
		parent::__construct();
        checklogin();
        logrequest();
	}
    
	public function index(){
        if($this->session->role!='admin'){
            redirect('/');
        }
        redirect('settings/franchise/');
        $data['title']="Banner Images";
        //$data['subtitle']="Sample Subtitle";
        $data['breadcrumb']=array();
        $data['bannerimages']=$this->common->getbannerimages();
        $data['datatable']=true;
		$this->template->load('settings','bannerimages',$data);
	}
	
	public function franchise(){
        if($this->session->role!='admin'){
            redirect('/');
        }
        $data['title']="Franchise Bonus";
        //$data['subtitle']="Sample Subtitle";
        $data['breadcrumb']=array();
        $data['franchisebonuses']=$this->common->getfranchisebonus();
        $data['datatable']=true;
		$this->template->load('settings','franchise',$data);
	}
    
	public function helpdeskmessages(){
        if($this->session->role!='admin'){
            redirect('/');
        }
        $data['title']="Helpdesk Messages";
        //$data['subtitle']="Sample Subtitle";
        $data['breadcrumb']=array();
        $data['messages']=$this->common->gethelpdeskmessages("t1.parent_id is NULL and t1.user_id!='1'",'all','t1.id desc');
        $data['datatable']=true;
		$this->template->load('settings','helpdeskmessages',$data);
	}
    
	public function helpdesk(){
        if($this->session->role=='admin'){
            redirect('helpdeskmessages/');
        }
        $data['title']="Helpdesk";
        $getuser=$this->account->getuser(array("md5(id)"=>$this->session->userdata('user')));
        $data['user']=$getuser['user'];
		$regid=$data['user']['id'];
        //$data['subtitle']="Sample Subtitle";
        $data['breadcrumb']=array();
        $data['messages']=$this->common->gethelpdeskmessages("t1.regid ='$regid' and t1.parent_id is NULL");
        $data['datatable']=true;
		$this->template->load('settings','helpdesk',$data);
	}
    
	public function news(){
        if($this->session->role!='admin'){
            redirect('/');
        }
        $data['title']="Notice";
        //$data['subtitle']="Sample Subtitle";
        $data['breadcrumb']=array();
        $data['news']=$this->common->getnews();
        $data['datatable']=true;
		$this->template->load('settings','news',$data);
	}
	
	public function disclaimer(){
        if($this->session->role!='admin'){
            redirect('/');
        }
        redirect('settings/franchise/');
        $data['title']="Disclaimer";
        //$data['subtitle']="Sample Subtitle";
        $data['breadcrumb']=array();
        //$data['bannerimages']=$this->common->getbannerimages();
        if(!file_exists("./disclaimer.txt")){
            $fh=fopen("./disclaimer.txt",'w');
            fclose($fh);
        }
        $disclaimer=file_get_contents("./disclaimer.txt");
        $data['disclaimers'][]=['id'=>1,'disclaimer'=>$disclaimer];
        $data['datatable']=true;
		$this->template->load('settings','disclaimer',$data);
	}
	
	public function whatsappno(){
        if($this->session->role!='admin'){
            redirect('/');
        }
        $data['title']="Whatsapp Number";
        //$data['subtitle']="Sample Subtitle";
        $data['breadcrumb']=array();
        //$data['bannerimages']=$this->common->getbannerimages();
        if(!file_exists("./whatsappno.txt")){
            $fh=fopen("./whatsappno.txt",'w');
            fclose($fh);
        }
        $whatsappno=file_get_contents("./whatsappno.txt");
        $data['whatsappnos'][]=['id'=>1,'whatsappno'=>$whatsappno];
        $data['datatable']=true;
		$this->template->load('settings','whatsappno',$data);
	}
	
	public function telegram(){
        if($this->session->role!='admin'){
            redirect('/');
        }
        $data['title']="Telegram Link";
        //$data['subtitle']="Sample Subtitle";
        $data['breadcrumb']=array();
        //$data['bannerimages']=$this->common->getbannerimages();
        if(!file_exists("./telegram.txt")){
            $fh=fopen("./telegram.txt",'w');
            fclose($fh);
        }
        $telegram=file_get_contents("./telegram.txt");
        $data['telegrams'][]=['id'=>1,'telegram'=>$telegram];
        $data['datatable']=true;
		$this->template->load('settings','telegram',$data);
	}
	
	public function qrimage(){
        if($this->session->role!='admin'){
            redirect('/');
        }
        $data['title']="QR Code Image";
        //$data['subtitle']="Sample Subtitle";
        $data['breadcrumb']=array();
        //$data['bannerimages']=$this->common->getbannerimages();
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
        $disclaimer=file_get_contents("./disclaimer.txt");
        $data['disclaimers'][]=['id'=>1,'disclaimer'=>$disclaimer];
        $data['datatable']=true;
		$this->template->load('settings','qrimage',$data);
	}
	
	public function youtube(){
        if($this->session->role!='admin'){
            redirect('/');
        }
        $data['title']="Youtube Links";
        //$data['subtitle']="Sample Subtitle";
        $data['breadcrumb']=array();
        $data['youtubelinks']=$this->common->getyoutubelinks();
        $data['datatable']=true;
		$this->template->load('settings','youtubelinks',$data);
	}
	
    public function savebannerimage(){
        if($this->input->post('savebannerimage')!==NULL){
            $data=$this->input->post();
            unset($data['savebannerimage']);
			$upload_path='./assets/images/banners/';
			$allowed_types='gif|jpg|jpeg|png|svg';
			$upload=upload_file('image',$upload_path,$allowed_types,'banner');
            if($upload['status']===true){
                create_image_thumb('.'.$upload['path'],'',FALSE,array("width"=>800,"height"=>300));
                $data['image']=$upload['path'];
                $result=$this->common->savebannerimage($data);
                if($result['status']===true){
                    $this->session->set_flashdata("msg",$result['message']);
                }
                else{
                    $this->session->set_flashdata("err_msg",$result['message']);
                }
            }
			else{$this->session->set_flashdata("err_msg","Image not uploaded!");}
        }
        redirect('settings/');
    }
    
    public function updatebannerimage(){
        if($this->input->post('updatebannerimage')!==NULL){
            $data=$this->input->post();
            $where=array("id"=>$data['id']);
            unset($data['updatebannerimage'],$data['id']);
			$upload_path='./assets/images/banners/';
			$allowed_types='gif|jpg|jpeg|png|svg';
			$upload=upload_file('image',$upload_path,$allowed_types,'banner');
            if($upload['status']===true){
                create_image_thumb('.'.$upload['path'],'',FALSE,array("width"=>800,"height"=>300));
                $data['image']=$upload['path'];
                $result=$this->common->updatebannerimage($data,$where);
                if($result['status']===true){
                    $this->session->set_flashdata("msg",$result['message']);
                }
                else{
                    $this->session->set_flashdata("err_msg",$result['message']);
                }
            }
			else{$this->session->set_flashdata("err_msg","Image not uploaded!");}
        }
        redirect('settings/');
    }
    
    public function deletebannerimage($id=NULL){
        if($id!==NULL){
            $where=array("id"=>$id);
            $result=$this->common->deletebannerimage($where);
            if($result['status']===true){
                $this->session->set_flashdata("msg",$result['message']);
            }
            else{
                $this->session->set_flashdata("err_msg",$result['message']);
            }
        }
        redirect("settings/");
    }
    
    public function savedisclaimer(){
        if($this->input->post('savedisclaimer')!==NULL){
            $data=$this->input->post();
            $data = array_map('strip_tags', $data);
            $data = array_map('htmlspecialchars', $data);
            $fh=fopen("./disclaimer.txt","w");
            fwrite($fh,$data['disclaimer']);
            fclose($fh);
            $result=array('status'=>true,'message'=>"Disclaimer Saved Successfully!");
            if($result['status']===true){
                $this->session->set_flashdata("msg",$result['message']);
            }
            else{
                $this->session->set_flashdata("err_msg",$result['message']);
            }
        }
        redirect('settings/qrimage/');
    }
    
    public function updatedisclaimer(){
        if($this->input->post('updatedisclaimer')!==NULL){
            $data=$this->input->post();
            $data = array_map('strip_tags', $data);
            $data = array_map('htmlspecialchars', $data);
            $fh=fopen("./disclaimer.txt","w");
            fwrite($fh,$data['disclaimer']);
            fclose($fh);
            $result=array('status'=>true,'message'=>"Disclaimer Updated Successfully!");
            if($result['status']===true){
                $this->session->set_flashdata("msg",$result['message']);
            }
            else{
                $this->session->set_flashdata("err_msg",$result['message']);
            }
        }
        redirect('settings/qrimage/');
    }
    
    public function saveaddress(){
        if($this->input->post('saveaddress')!==NULL){
            $data=$this->input->post();
            $data = array_map('strip_tags', $data);
            $data = array_map('htmlspecialchars', $data);
            $fh=fopen("./disclaimer.txt","w");
            fwrite($fh,$data['address']);
            fclose($fh);
            $result=array('status'=>true,'message'=>"Address Saved Successfully!");
            if($result['status']===true){
                $this->session->set_flashdata("msg",$result['message']);
            }
            else{
                $this->session->set_flashdata("err_msg",$result['message']);
            }
        }
        redirect('settings/qrimage/');
    }
    
    public function updateaddress(){
        if($this->input->post('updateaddress')!==NULL){
            $data=$this->input->post();
            $status=false;
            $message="";
            $otp=$this->input->post('otp');
            $temp_address=$this->session->address;
            if($temp_address==$data['address']){
                $verify=$this->account->verifyotp($otp,['id'=>1]);
                if($verify['status']===true){
                    $status=true;
                    unset($data['otp']);
                }
                else{
                    $status=false;
                    $message="Invalid OTP!";
                }
            }
            else{
                $status=false;
                $message="Address MisMatch!";
            }
            if($status){
                $data = array_map('strip_tags', $data);
                $data = array_map('htmlspecialchars', $data);
                $fh=fopen("./disclaimer.txt","w");
                fwrite($fh,$data['address']);
                fclose($fh);
                $result=array('status'=>true,'message'=>"Address Updated Successfully!");
                if($result['status']===true){
                    $this->session->unset_userdata('address');
                    $this->session->set_flashdata("msg",$result['message']);
                }
                else{
                    $this->session->set_flashdata("err_msg",$result['message']);
                }
            }
            else{
                $this->session->set_flashdata("err_msg",$message);
            }
        }
        redirect('settings/qrimage/');
    }
    
    public function savewhatsappno(){
        if($this->input->post('savewhatsappno')!==NULL){
            $data=$this->input->post();
            $data = array_map('strip_tags', $data);
            $data = array_map('htmlspecialchars', $data);
            $fh=fopen("./whatsappno.txt","w");
            fwrite($fh,$data['whatsappno']);
            fclose($fh);
            $result=array('status'=>true,'message'=>"Whatsapp N. Saved Successfully!");
            if($result['status']===true){
                $this->session->set_flashdata("msg",$result['message']);
            }
            else{
                $this->session->set_flashdata("err_msg",$result['message']);
            }
        }
        redirect('settings/whatsappno/');
    }
    
    public function updatewhatsappno(){
        if($this->input->post('updatewhatsappno')!==NULL){
            $data=$this->input->post();
            $data = array_map('strip_tags', $data);
            $data = array_map('htmlspecialchars', $data);
            $fh=fopen("./whatsappno.txt","w");
            fwrite($fh,$data['whatsappno']);
            fclose($fh);
            $result=array('status'=>true,'message'=>"Whatsapp N. Updated Successfully!");
            if($result['status']===true){
                $this->session->set_flashdata("msg",$result['message']);
            }
            else{
                $this->session->set_flashdata("err_msg",$result['message']);
            }
        }
        redirect('settings/whatsappno/');
    }
    
    public function saveyoutubelink(){
        if($this->input->post('saveyoutubelink')!==NULL){
            $data=$this->input->post();
            unset($data['saveyoutubelink']);
            $data['updated_on']=date('Y-m-d H:i:s');
            $result=$this->common->saveyoutubelink($data);
            if($result['status']===true){
                $this->session->set_flashdata("msg",$result['message']);
            }
            else{
                $this->session->set_flashdata("err_msg",$result['message']);
            }
        }
        redirect('settings/youtube/');
    }
    
    public function updateyoutubelink(){
        if($this->input->post('updateyoutubelink')!==NULL){
            $data=$this->input->post();
            $where=array("id"=>$data['id']);
            unset($data['updateyoutubelink'],$data['id']);
            $data['updated_on']=date('Y-m-d H:i:s');
            $result=$this->common->updateyoutubelink($data,$where);
            if($result['status']===true){
                $this->session->set_flashdata("msg",$result['message']);
            }
            else{
                $this->session->set_flashdata("err_msg",$result['message']);
            }
        }
        redirect('settings/youtube/');
    }
    
    public function saveqrimage(){
        if($this->input->post('saveqrimage')!==NULL){
            $status=false;
            $message="";
            $otp=$this->input->post('otp');
            $verify=$this->account->verifyotp($otp,['id'=>1]);
            if($verify['status']===true){
                $status=true;
                unset($data['otp']);
            }
            else{
                $status=false;
                $message="Invalid OTP!";
            }
            if($status){
                $upload_path='./assets/images/qrimage/';
                $allowed_types='gif|jpg|jpeg|png|svg';
                $upload=upload_file('qrimage',$upload_path,$allowed_types,'qr-image');
                if($upload['status']===true){
                    $this->session->set_flashdata("msg","QR Image Updated Successfully!");
                }
                else{$this->session->set_flashdata("err_msg","Image not uploaded!");}
            }
			else{$this->session->set_flashdata("err_msg",$message);}
        }
        redirect('settings/qrimage/');
    }
    
    public function savetelegram(){
        if($this->input->post('savetelegram')!==NULL){
            $data=$this->input->post();
            $fh=fopen("./telegram.txt","w");
            fwrite($fh,$data['telegram']);
            fclose($fh);
            $result=array('status'=>true,'message'=>"Telegram Link Saved Successfully!");
            if($result['status']===true){
                $this->session->set_flashdata("msg",$result['message']);
            }
            else{
                $this->session->set_flashdata("err_msg",$result['message']);
            }
        }
        redirect('settings/telegram/');
    }
    
    public function updatetelegram(){
        if($this->input->post('updatetelegram')!==NULL){
            $data=$this->input->post();
            $fh=fopen("./telegram.txt","w");
            fwrite($fh,$data['telegram']);
            fclose($fh);
            $result=array('status'=>true,'message'=>"Telegram Link Updated Successfully!");
            if($result['status']===true){
                $this->session->set_flashdata("msg",$result['message']);
            }
            else{
                $this->session->set_flashdata("err_msg",$result['message']);
            }
        }
        redirect('settings/telegram/');
    }
    
    public function addfranchisebonus(){
        if($this->input->post('addfranchisebonus')!==NULL){
            $data=$this->input->post();
            unset($data['addfranchisebonus']);
			$result=$this->common->addfranchisebonus($data);
			if($result['status']===true){
				$this->session->set_flashdata("msg",$result['message']);
			}
            else{
                $this->session->set_flashdata("err_msg",$result['message']);
            }
        }
        redirect('settings/franchise/');
    }
    
    public function updatefranchisebonus(){
        if($this->input->post('updatefranchisebonus')!==NULL){
            $data=$this->input->post();
            unset($data['updatefranchisebonus']);
			$result=$this->common->updatefranchisebonus($data);
			if($result['status']===true){
				$this->session->set_flashdata("msg",$result['message']);
			}
            else{
                $this->session->set_flashdata("err_msg",$result['message']);
            }
        }
        redirect('settings/franchise/');
    }
    
    public function getfranchisebonus(){
        $id=$this->input->post('id');
        $franchisebonus=$this->common->getfranchisebonus(array("id"=>$id),"single");
        echo json_encode($franchisebonus);
    }
    
    public function savenews(){
        if($this->input->post('savenews')!==NULL){
            $data=$this->input->post();
            unset($data['savenews']);
            $data['added_on']=$data['updated_on']=date('Y-m-d H:i:s');
            $result=$this->common->savenews($data);
            if($result['status']===true){
                $this->session->set_flashdata("msg",$result['message']);
            }
            else{
                $this->session->set_flashdata("err_msg",$result['message']);
            }
        }
        if($this->input->post('updatenews')!==NULL){
            $data=$this->input->post();
            $where=array("id"=>$data['id']);
            unset($data['updatenews'],$data['id']);
            $data['updated_on']=date('Y-m-d H:i:s');
            $result=$this->common->updatenews($data,$where);
            if($result['status']===true){
                $this->session->set_flashdata("msg",$result['message']);
            }
            else{
                $this->session->set_flashdata("err_msg",$result['message']);
            }
        }
        redirect($_SERVER['HTTP_REFERER']);
    }
    
    public function getnews(){
        $id=$this->input->post('id');
        $news=$this->common->getnews(array("id"=>$id),"single");
        echo json_encode($news);
    }
    
    public function saveticket(){
        if($this->input->post('saveticket')!==NULL){
            $data=$this->input->post();
            $data = array_map('strip_tags', $data);
            $data = array_map('htmlspecialchars', $data);
            unset($data['saveticket'],$data['id']);
            $getuser=$this->account->getuser(array("md5(id)"=>$this->session->userdata('user')));
            $user=$getuser['user'];
            $regid=$user['id'];
            $data['regid']= $data['user_id']=$regid;
			$result=$this->common->saveticket($data);
			if($result['status']===true){
                $email=ADMIN_EMAIL;
                $subject="New Helpdesk Query Received";
                $message='<p>You have received a new Helpdesk Query.</p>';
                $message.='<p>Please login to you account to view and update the request.</p>';
                $message.='<a href="'.base_url('helpdeskmessages/').'">Open Helpdesk Query List</a>';
                sendnotifications($email,$subject,$message);
				$this->session->set_flashdata("msg",$result['message']);
			}
            else{
                $this->session->set_flashdata("err_msg",$result['message']);
            }
        }
        redirect('helpdesk/');
    }
    
    public function updateticket(){
        if($this->input->post('updateticket')!==NULL){
            $data=$this->input->post();
            $data = array_map('strip_tags', $data);
            $data = array_map('htmlspecialchars', $data);
            $id=$data['id'];
            $status=$data['status'];
            unset($data['updateticket'],$data['id']);
            $message=$this->common->gethelpdeskmessages(['t1.id'=>$id],'single');
            $data['regid']=$message['regid'];
            $data['ticket_no']=NULL;
            $data['parent_id']=$id;
            $data['user_id']=1;
			$result=$this->common->saveticket($data);
			if($result['status']===true){
                $this->db->update('helpdesk',['status'=>$status],['id'=>$id]);
				$this->session->set_flashdata("msg",$result['message']);
			}
            else{
                $this->session->set_flashdata("err_msg",$result['message']);
            }
        }
        redirect('helpdeskmessages/');
    }
    
    public function getmessages(){
        $id=$this->input->post('id');
        $messages=$this->common->gethelpdeskmessages("t1.id='$id' or t1.parent_id='$id'");
        $row='';
        if(!empty($messages)){
            foreach($messages as $single){
                $row.='<tr>';
                $row.='<td>'.date('d-m-Y H:i a',strtotime($single['added_on'])).'</td>';
                if($single['user_id']=='1'){
                    $row.='<td>Royal Power Club</td>';
                }
                else{
                    $row.='<td>'.$single['username'].'-'.$single['name'].'</td>';
                }
                $row.='<td>'.$single['message'].'</td>';
                $row.='</tr>';
            }
        }
        echo $row;
    }
    
    public function createotp(){
        $address=$this->input->post('address');
        if(!file_exists("./disclaimer.txt")){
            $fh=fopen("./disclaimer.txt",'w');
            fclose($fh);
        }
        $oldaddress=file_get_contents("./disclaimer.txt");
        if($oldaddress!=$address){
            $email=ADMIN_EMAIL;
            $result=$this->account->createotp(array('id'=>1));
            if($result['status']===true){
                $this->session->set_userdata('address',$address);
                $otp=$result['result']['otp'];
                addressotpmail($email,$otp);
                if(isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST']!='localhost'){
                    unset($result['result']);
                }
            }
            else{
                $this->session->set_flashdata("err_msg",$result['message']);
            }
        }
        else{
            $result=array('status'=>false,'msg'=>'same');
            $this->session->set_flashdata("err_msg","Address Not Changed!");
        }
        echo json_encode($result);
    }
}
