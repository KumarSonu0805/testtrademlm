<?php 
	if(!defined('BASEPATH')) exit('No direct script access allowed');
	if(!function_exists('print_pre')) {
  		function print_pre($data,$die=false) {
            echo PRE; print_r($data); echo "</pre>";
            if($die){ die; }
		}  
	}

    if(!function_exists('logrequest')) {
  		function logrequest() {
            if(REQUEST_LOG==TRUE ){
                $CI = get_instance();
                $get=json_encode($CI->input->get());
                $post=json_encode($CI->input->post());
                $server=empty($_SERVER)?NULL:json_encode($_SERVER);
                $session=empty($_SESSION)?NULL:json_encode($_SESSION);
                $cookie=empty($_COOKIE)?NULL:json_encode($_COOKIE);
                $headers=empty($_SERVER['HTTP_HOST'])?NULL:json_encode(getallheaders());
                $ip= empty($_SERVER['HTTP_HOST'])?'':get_visitor_IP();
                $url = empty($_SERVER['HTTP_HOST'])?'':(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                $CI->db->insert("request_log",array("url"=>$url,"ip_address"=>$ip,"req_get"=>$get,"post"=>$post,"server"=>$server,
                                                    "session"=>$session,"cookie"=>$cookie,"headers"=>$headers,
                                                    "added_on"=>date('Y-m-d H:i:s')));
            }
        }
    }

	if(!function_exists('generateepin')) {
		function generateepin($length=6){
    		$CI = get_instance();
			$epin=strtoupper(random_string('alnum', $length));
			$checkepin=$CI->db->get_where("epins",array("epin"=>$epin))->num_rows();
			if($checkepin==0){
				return $epin;
			}
			else{
				return generateepin($length);
			}
		}
	}

	if(!function_exists('checkclub')) {
		function checkclub($regid,$package_id){
            $club_id=$package_id-1;
    		$CI = get_instance();
			$checkclub=$CI->db->get_where("club_members",array("regid"=>$regid,"club_id"=>$club_id))->num_rows();
			if($checkclub==0){
				return false;
			}
			else{
				return true;
			}
		}
	}

	if(!function_exists('checktree')) {
		function checktree($regid,$club_id){
    		$CI = get_instance();
			$checktree=$CI->db->get_where("member_tree",array("regid"=>$regid,"club_id"=>$club_id))->num_rows();
			if($checktree==0){
				return false;
			}
			else{
				return true;
			}
		}
	}

	if(!function_exists('buildHierarchy')) {
        function buildHierarchy($data, $parentId = null) {
            $hierarchy = array();

            foreach ($data as $item) {
                if ($item['refid'] == $parentId) {
                    $children = buildHierarchy($data, $item['regid']);
                    if ($children) {
                        $item['children'] = $children;
                    }
                    $hierarchy[] = $item;
                }
            }

            return $hierarchy;
        }
    }

	if(!function_exists('buildHierarchyWithQueue')) {
        function buildHierarchyWithQueue($data, $rootId) {
            $hierarchy = array();
            $queue = new SplQueue();
            $queue->enqueue($rootId);

            while (!$queue->isEmpty()) {
                $parentId = $queue->dequeue();
                $children = array();

                foreach ($data as $item) {
                    if ($item['refid'] == $parentId) {
                        $item['children'] = buildHierarchyWithQueue($data, $item['regid']); // Recurse for children
                        $children[] = $item;
                    }
                }

                $hierarchy = array_merge($hierarchy, $children);
            }

            return $hierarchy;
        }

    }

	if(!function_exists('generateHierarchyHTML')) {
        function generateHierarchyHTML($hierarchy,$level=0,$usernames=array(),$members=array()) {
            if (empty($hierarchy)) {
                return '';
            }

            $html = '<ul>';
            foreach ($hierarchy as $index => $node) {
                $class='active';
                $index=array_search($node['username'],$usernames);
                if($index!==false){
                    $member=$members[$index];
                    if($member['status']==0 || $member['user_status']==0){
                        $class="in-active";
                    }
                }
                $html .= '<li class="'.$class.'">';
                $html .= ($level>0)?'Level ' . $level . ': ' :'';
                $html .= $node['username']. ' - ' . $node['name'];
                if (!empty($node['children'])) {
                    $html .= generateHierarchyHTML($node['children'], $level + 1,$usernames,$members); // Recurse with increased level
                }
                $html .= '</li>';
            }
            $html .= '</ul>';

            return $html;
        }        
    }

    if (! function_exists('get_visitor_IP')){
        /**
         * Get the real IP address from visitors proxy. e.g. Cloudflare
         *
         * @return string IP
         */
        function get_visitor_IP()
        {
            // Get real visitor IP behind CloudFlare network
            if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
                $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
                $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            }

            // Sometimes the `HTTP_CLIENT_IP` can be used by proxy servers
            $ip = @$_SERVER['HTTP_CLIENT_IP'];
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
               return $ip;
            }

            // Sometimes the `HTTP_X_FORWARDED_FOR` can contain more than IPs 
            $forward_ips = @$_SERVER['HTTP_X_FORWARDED_FOR'];
            if ($forward_ips) {
                $all_ips = explode(',', $forward_ips);

                foreach ($all_ips as $ip) {
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)){
                        return $ip;
                    }
                }
            }

            return $_SERVER['REMOTE_ADDR'];
        }
    }

	if(!function_exists('checkaadhar')) {
        function checkaadhar($user,$aadhar,$role='member') {
          $CI = get_instance();
          if($role=='member'){
            $check=$CI->db->get_where('members',['aadhar'=>$aadhar,'regid!='=>$user['id']]);
          }
          else{
            $check=$CI->db->get_where('franchise',['aadhar'=>$aadhar,'user_id!='=>$user['id']]);
          }
          if($check->num_rows()==0){
            return true;
          }
          else{
            $members=$check->result_array();
            $regids=array_column($members,'regid');
            $CI->db->where_in('regid',$regids);
            $checkkyc=$CI->db->get_where('acc_details',['kyc!='=>0]);
            if($checkkyc->num_rows()==0){
                return true;
            }
            else{
                return false;
            }
          }
        }
    }

	if(!function_exists('checkpan')) {
        function checkpan($user,$pan,$role='member') {
          $CI = get_instance();
          if($role=='member'){
            $check=$CI->db->get_where('members',['pan'=>$pan,'regid!='=>$user['id']]);
          }
          else{
            $check=$CI->db->get_where('franchise',['pan'=>$pan,'user_id!='=>$user['id']]);
          }
          if($check->num_rows()==0){
            return true;
          }
          else{
            $members=$check->result_array();
            $regids=array_column($members,'regid');
            $CI->db->where_in('regid',$regids);
            $checkkyc=$CI->db->get_where('acc_details',['kyc!='=>0]);
            if($checkkyc->num_rows()==0){
                return true;
            }
            else{
                return false;
            }
          }
        }
    }
    
?>