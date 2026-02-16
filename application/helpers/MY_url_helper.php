<?php 
	if(!defined('BASEPATH')) exit('No direct script access allowed');
	if(!function_exists('file_url')) {
  		function file_url($uri=''){
			$url=base_url($uri);
			$url=str_replace("/index.php","",$url);
			return $url;
		}
	}
	
	if(!function_exists('admin_url')) {
  		function admin_url($uri=''){
            $CI = get_instance();
			$url=base_url('admin/'.$uri);
            if($CI->session->role=='school'){
                $url=base_url('school/'.$uri);
            }
			return $url;
		}
	}
	
	if(!function_exists('school_url')) {
  		function school_url($uri=''){
			$url=base_url('school/'.$uri);
			return $url;
		}
	}
	
	if(!function_exists('checkimageexists')) {
  		function checkimageexists($imageurl,$type='square'){
            $imagepath=str_replace(file_url(),'.',$imageurl);
            if(!file_exists($imagepath)){
                $image=file_url('assets/images/android-chrome-192x192.png');
            }
            else{
                $image=$imageurl;
            }
            return $image;
		}
	}
	
