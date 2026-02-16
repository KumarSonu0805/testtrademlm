<?php 
	if(!defined('BASEPATH')) exit('No direct script access allowed');
	if(!function_exists('countdownline')) {
  		function countdownline() {
    		$CI = get_instance();
            $user=getuser();
            $members=$CI->member->getmembers($user['id'],true);
            return $members;
		}  
	}

	if(!function_exists('countdirect')) {
  		function countdirect() {
    		$CI = get_instance();
            $user=getuser();
            $members=$CI->member->getdirectmembers($user['id']);
            return !empty($members)?count($members):0;
		}  
	}

	if(!function_exists('teamstake')) {
  		function teamstake() {
    		$CI = get_instance();
            $user=getuser();
            $where="regid in (SELECT member_id from ".TP."level_members where regid='$user[id]')";
            $CI->db->select_sum('amount');
            $amount=$CI->db->get_where('investments',$where)->unbuffered_row()->amount;
            return !empty($amount)?$amount:0;
		}  
	}

	if(!function_exists('getincome')) {
  		function getincome() {
    		$CI = get_instance();
            $user=getuser();
            $incomes=array('roiincome'=>0,'level'=>0,'matching'=>0,'clubincome'=>0,'ultraclub'=>0);
            $CI->db->select('type,sum(amount) as amount');
            $CI->db->group_by('type');
            $query=$CI->db->get_where('income',['regid'=>$user['id']]);
            $array=$query->result_array();
            $types=!empty($array)?array_column($array,'type'):array();
            
            $index=array_search('roiincome',$types);
            $incomes['roiincome']=$index!==false?$array[$index]['amount']:0;
            
            $index=array_search('direct',$types);
            $incomes['direct']=$index!==false?$array[$index]['amount']:0;
            
            $index=array_search('level',$types);
            $incomes['level']=$index!==false?$array[$index]['amount']:0;
            
            $index=array_search('reward',$types);
            $incomes['reward']=$index!==false?$array[$index]['amount']:0;
            
            $index=array_search('royalty',$types);
            $incomes['royalty']=$index!==false?$array[$index]['amount']:0;
            
            $index=array_search('ultraclub',$types);
            $incomes['ultraclub']=$index!==false?$array[$index]['amount']:0;
            
            $total=array_sum($incomes);
            $incomes['total']=$total;
            
            $CI->db->select('sum(amount) as amount');
            $withdrawal=$CI->db->get_where('withdrawals',['regid'=>$user['id'],'status!='=>2])->unbuffered_row()->amount;
            $incomes['withdrawal']=$withdrawal??0;
            
            $incomes['wallet_balance']=getavlbalance($user);
            
            return $incomes;
		}  
	}
