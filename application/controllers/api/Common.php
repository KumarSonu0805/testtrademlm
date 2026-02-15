<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//include Rest Controller library
require APPPATH . '/libraries/MyRestController.php'; 
 
class Common extends MyRestController { 
	function __construct(){
		parent::__construct();
        logrequest();
	}

	public function getpackages_post(){
        $packages=$this->package->getpackages(['status'=>1]);
		$this->customresponse('default',$packages,"Packages Fetched successfully");
	}	

	public function getbannerimages_post(){
        $bannerimages=$this->common->getbannerimages(['status'=>1]);
        if(!empty($bannerimages)){
            $bannerimages=array_column($bannerimages,'image');
			$this->response([
				'status' => true,
				'bannerimages' => $bannerimages], REST_Controller::HTTP_OK);
		}		
		else{
			$this->response([
				'status' => false,
				'message' => "No Banner Image Found!"], REST_Controller::HTTP_OK);
		}
	}	

    public function getrelations_post(){
        $relations=array('Father','Mother','Husband','Wife','Brother','Sister');
        $this->customresponse('default',$relations,"Relations Fetched successfully");
    }

	public function getstates_post(){
        $states=$this->common->getstates();
		$states=array_map(function($item){
			$item['added_on']=$item['updated_on']=date('Y-m-d H:i:s');
			return $item;
		},$states);
		$this->customresponse('default',$states,'States Fetched Successfully!');
	}	

	public function getdistricts_post(){
        $state_id=$this->post('state_id');
        if(!empty($state_id)){
            $districts=$this->common->getdistricts($state_id);
			$districts=array_map(function($item){
				$item['state_id']=$item['parent_id'];
				$item['added_on']=$item['updated_on']=date('Y-m-d H:i:s');
				return $item;
			},$districts);
            $this->customresponse('default',$districts,'Districts Fetched Successfully!');
        }	
		else{
            if(empty($state_id)){
                $parameters[]='state_id';
            }
            $this->customresponse('missingparameters',$parameters);
		}
	}	
    
    public function getbanks_post(){
        $bank=$this->common->getbanks();
        $this->customresponse('default',$bank,"Banks Fetched successfully");
    }
    
    public function getnotice_post(){
        $news=$this->common->getnews();
        $this->customresponse('default',$news,"Notice Fetched successfully");
    }
    
    public function getadminaccountdetails_post(){
        $admin_acc_details=$this->member->getaccdetails(1);
		if(empty($admin_acc_details)){
			$this->db->insert('acc_details',['regid'=>1]);
			$admin_acc_details=$this->member->getaccdetails(1);
		}
        $this->customresponse('default',$admin_acc_details,"Admin Account Details Fetched successfully");
    }
    

	public function getareas_post(){
		$this->customresponse('default',[],'Areas Fetched Successfully!');
	}	

	public function getdisclaimer_post(){
        $disclaimer=file_get_contents("./disclaimer.txt");
		if(!empty($disclaimer)){
			$this->response([
				'status' => true,
				'disclaimer' => $disclaimer], REST_Controller::HTTP_OK);
		}		
		else{
			$this->response([
				'status' => false,
				'message' => "No Disclaimer!"], REST_Controller::HTTP_OK);
		}
	}	

	public function getwhatsappno_post(){
        $whatsappno=file_get_contents("./whatsappno.txt");
		if(!empty($whatsappno)){
			$this->response([
				'status' => true,
				'whatsappno' => $whatsappno], REST_Controller::HTTP_OK);
		}		
		else{
			$this->response([
				'status' => false,
				'message' => "No Disclaimer!"], REST_Controller::HTTP_OK);
		}
	}	
    
	public function getqrimage_post(){
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
        $dir=str_replace('./','',$dir);
        krsort($images);
        if(!empty($images)){$id=0;
            foreach($images as $qrimage){ $id++;
                
                $qrimage=file_url($dir.$qrimage);
                break;
            }
        }
		if(!empty($qrimage)){
			$this->response([
				'status' => true,
				'qrimage' => $qrimage], REST_Controller::HTTP_OK);
		}		
		else{
			$this->response([
				'status' => false,
				'message' => "QR Image Not Found!"], REST_Controller::HTTP_OK);
		}
	}	

}
