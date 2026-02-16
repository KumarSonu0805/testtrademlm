<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Template {
    
    var $ci;
	private $styles=array("link"=>array(),"file"=>array());
	private $top_script=array("link"=>array(),"file"=>array());
	private $content_script=array("link"=>array(),"file"=>array());
	private $bottom_script=array("link"=>array(),"file"=>array());
	private $header_bg=array('dark','blue','purple','light-blue','green','orange','red','dark2','blue2','purple2','light-blue2','green2','orange2','red2','white');
	private $sidebar_bg=array('dark','dark2','white');
	private $content_bg=array('dark','bg1','bg2','bg3');
    
	var $isAdmin=FALSE;
      
    function __construct() {
       $this->ci =& get_instance();
    }
	
    function load($folder, $view, $data=array("title"=>"Page"),$type="page") {
        $root='';
        if($this->isAdmin){
            $root='admin/';
        }
		$contentpath=$root.$folder.'/'.$view;
		$footerpath=$root.'/includes/footer';
        $data['content']=$contentpath;
        $data['footer']=$footerpath;
        $data['page_type']=$type;
		$data['root_path']=$root;
        
        
		if(!empty($data['styles'])){ 
			$styles=$data['styles'];
			foreach($styles as $key=>$style){
				if(is_array($style)){
					foreach($style as $single_style){
						if(array_search($single_style,$this->styles[$key])===false)
							$this->styles[$key][]=$single_style;
					}
				}
				else{
					if(array_search($style,$this->styles[$key])===false)
						$this->styles[$key][]=$style;
				}
			}
		}
		
		if(!empty($data['top_script'])){ 
			$top_script=$data['top_script'];
			foreach($top_script as $key=>$script){
				if(is_array($script)){
					foreach($script as $single_script){
						if(array_search($single_script,$this->top_script[$key])===false)
							$this->top_script[$key][]=$single_script;
					}
				}
				else{
					if(array_search($script,$this->top_script[$key])===false)
						$this->top_script[$key][]=$script;
				}
			}
		}
		
		if(!empty($data['bottom_script'])){ 
			$bottom_script=$data['bottom_script'];
			foreach($bottom_script as $key=>$script){
				if(is_array($script)){
					foreach($script as $single_script){
						if(array_search($single_script,$this->bottom_script[$key])===false)
							$this->bottom_script[$key][]=$single_script;
					}
				}
				else{
					if(array_search($script,$this->bottom_script[$key])===false)
						$this->bottom_script[$key][]=$script;
				}
			}
		}
        
		if(!empty($data['content_script'])){ 
			$content_script=$data['content_script'];
			foreach($content_script as $key=>$script){
				if(is_array($script)){
					foreach($script as $single_script){
						if(array_search($single_script,$this->content_script[$key])===false)
							$this->content_script[$key][]=$single_script;
					}
				}
				else{
					if(array_search($script,$this->content_script[$key])===false)
						$this->content_script[$key][]=$script;
				}
			}
		}
        
		if(isset($data['datatable']) && $data['datatable']===true){
			$this->loaddatatable();
		}
		
		if(isset($data['tabulator']) && $data['tabulator']===true){
			$this->loadtabulator();
		}
		
		if(isset($data['switchery']) && $data['switchery']===true){
			$this->loadswitchery();
		}
		
		if(isset($data['alertify']) && $data['alertify']===true){
			$this->loadalertify();
		}
		
        $data['styles']=$this->styles;
		$data['top_script']=$this->top_script;
		$data['content_script']=$this->content_script;
		$data['bottom_script']=$this->bottom_script;
        if($type=='page'){
            $this->ci->load->view($root.'includes/top-section',$data);       
            $this->ci->load->view($root.'includes/header');
            $this->ci->load->view($root.'includes/sidebar');
            $this->ci->load->view($root.'includes/wrapper');
            $this->ci->load->view($root.'includes/bottom-section');
        }
        elseif($type=='auth'){
            $this->ci->load->view($root.'includes/top-section',$data);       
            $this->ci->load->view($root.'includes/wrapper');
            $this->ci->load->view($root.'includes/bottom-section');
        }
		
    }
    
	function loadtoastr(){
		$this->styles['file'][]="includes/plugins/toastr/toastr.min.css";
		$this->bottom_script['file'][]="includes/plugins/toastr/toastr.min.js";
	}
	
	function loaddatatable(){
		$this->styles['link'][]="https://cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.css";
		$this->content_script['link'][]="https://cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.js";
	}
	
	function loadswitchery(){
		$this->styles['link'][]="https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.css";
		$this->content_script['link'][]="https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.js";
	}
	
	function loadtabulator(){
		$this->styles['link'][]="https://unpkg.com/tabulator-tables@6.3.1/dist/css/tabulator_bulma.min.css";
		//$this->styles['file'][]="includes/custom/tabulator-custom.css";
		$this->styles['link'][]="https://cdn.jsdelivr.net/gh/atalprateek/tabulator-ext/tabulator-ext.css";
		$this->content_script['link'][]="https://unpkg.com/tabulator-tables@6.3.1/dist/js/tabulator.min.js";
		//$this->content_script['file'][]="includes/custom/tabulator-custom.js";
		$this->content_script['link'][]="https://cdn.jsdelivr.net/gh/atalprateek/tabulator-ext/tabulator-ext.js";
	}
	
	function loadalertify(){
		$this->styles['link'][]="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/css/alertify.min.css";
		$this->styles['link'][]="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/css/themes/bootstrap.min.css";
		$this->content_script['link'][]="//cdn.jsdelivr.net/npm/alertifyjs@1.14.0/build/alertify.min.js";
	}
	
}