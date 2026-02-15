<?php
	if(!defined('BASEPATH')) exit('No direct script access allowed');
	if(!function_exists('generateTree')) {
  		function generateTree($regid,$club_id) {
    		$CI = get_instance();
			$result=array($regid);
			for($i=0;$i<7;$i++){
				$where=array("parent_id"=>$result[$i],'club_id'=>$club_id,"parent_id!="=>"0");
				$CI->db->order_by("position");
				$query=$CI->db->get_where("member_tree",$where);
				if($query->num_rows()==2){
					$array=$query->result_array();
					foreach($array as $value){
						$result[]=$value['regid'];
					}
				}
				elseif($query->num_rows()==1){
					$array=$query->row_array();
					if($array['position']=='L'){
						$result[]=$array['regid'];
						$result[]='R'.$result[$i];
					}
					else{
						$result[]='L'.$result[$i];
						$result[]=$array['regid'];
					}
				}
				else{
					$result[]='L'.$result[$i];
					$result[]='R'.$result[$i];
				}
			}
			return $result;
		}
	}
	
	if(!function_exists('createTree')) {
  		function createTree($regids) {
			$table="<table border='0' width='100%' cellpadding='0' cellspacing='0' class=' tree-table' >";
			for($i=0;$i<15;$i++){
				switch($i){
					case 0: $table.= "<tr><td align='center' width='96%' colspan='16'>".getDiv($regids[0])."|</td></tr>";
							$table.= "<tr height='10'><td colspan='4' width='24%'></td><td colspan='8' class='branch'></td><td colspan='4' width='24%'></td></tr>";
					break;
					case 1: $table.= "<tr><td align='center' width='48%' colspan='8'>".getDiv($regids[1])."|</td>";
					break;
					case 2: $table.= "<td align='center' width='48%' colspan='8'>".getDiv($regids[2])."|</td></tr>";
							$table.= "<tr height='10'><td colspan='2' width='12%'></td><td colspan='4' class='branch' width='24%' ></td><td colspan='4' width='24%' ></td>
									<td colspan='4' class='branch' width='24%' ></td><td colspan='2' width='12%' ></td></tr>";
					break;
					case 3: $table.= "<tr><td align='center' width='24%' colspan='4'>".getDiv($regids[3])."|</td>";
					break;
					case 4: $table.= "<td align='center' width='24%' colspan='4'>".getDiv($regids[4])."|</td>";
					break;
					case 5: $table.= "<td align='center' width='24%' colspan='4'>".getDiv($regids[5])."|</td>";
					break;
					case 6: $table.= "<td align='center' width='24%' colspan='4'>".getDiv($regids[6])."|</td></tr>";
							$table.= "<tr height='10'><td width='6%'></td><td colspan='2' width='12%'  class='branch'></td>
									<td colspan='2' width='12%' ></td><td colspan='2' width='12%'  class='branch'></td><td colspan='2' width='12%' ></td>";
							$table.= "<td colspan='2' class='branch' width='12%' ></td><td colspan='2' width='12%' ></td><td colspan='2' class='branch' width='12%' ></td>
								<td width='6%' ></td></tr>";
					break;
					case 7: $table.= "<tr><td align='center' width='12%' colspan='2'>".getDiv($regids[7])."</td>";
					break;
					case 8: $table.= "<td align='center' width='12%' colspan='2'>".getDiv($regids[8])."</td>";
					break;
					case 9: $table.= "<td align='center' width='12%' colspan='2'>".getDiv($regids[9])."</td>";
					break;
					case 10: $table.= "<td align='center' width='12%' colspan='2'>".getDiv($regids[10])."</td>";
					break;
					case 11: $table.= "<td align='center' width='12%' colspan='2'>".getDiv($regids[11])."</td>";
					break;
					case 12: $table.= "<td align='center' width='12%' colspan='2'>".getDiv($regids[12])."</td>";
					break;
					case 13: $table.= "<td align='center' width='12%' colspan='2'>".getDiv($regids[13])."</td>";
					break;
					case 14: $table.= "<td align='center' width='12%' colspan='2'>".getDiv($regids[14])."</td></tr>";
				}
			}
			$table.= "</table>";
			return $table;
		}
	}
	
	if(!function_exists('getDiv')) {
  		function getDiv($regid) {
    		$CI = get_instance();
			$CI->db->select("t1.*, t2.username, t2.name,'btn-success' as btn_class,'' as imgclass");
			$CI->db->from('members t1');
			$CI->db->join('users t2','t1.regid=t2.id','Left');
			$CI->db->join('packages t3','t1.package_id=t3.id','Left');
			$CI->db->where(array("t1.regid"=>$regid));
			$member=$CI->db->get()->row_array();
            if(is_array($member)){
                $status=$member['status'];
            }
            else{
                $status=0;
            }
			$btnclass="btn-primary";
			$imgclass="";
            if(is_array($member) && $member['photo']!=''){ 
                $photo=file_url($member['photo']); 
            }
            elseif($status==1 || $regid==1){
                $photo=file_url("assets/images/male.jpg");
            }else{
                $photo=file_url("assets/images/no-image.png");
            }
			if($regid==1){
				$member=array("regid"=>"1","name"=>"Admin","username"=>"Admin","photo"=>"","parent_id"=>"0");
				$btnclass="btn-success open-branch"; $imgclass="active";
			}
			elseif(!is_array($member) || empty($member)){
				$parent=substr($regid,1);
				$position=substr($regid,0,1);
				$name="Add Member";
				//if((int)$parent==0){$parent=''; $name="No Member";}
				//else{ $btnclass='btn-info add-btn'; }
                $parent=''; $name="No Member"; $btnclass='btn-default';
				$member=array("regid"=>$position,"name"=>$name,"username"=>$name,"photo"=>"","parent_id"=>"$parent");
			}
			else{
				$member['parent_id']=$CI->db->get_where("member_tree",array("regid"=>$regid))->row()->parent_id;
				if($status==1){ $btnclass=$member['btn_class']; $imgclass=$member['imgclass']; }
				elseif($status==0){ $btnclass="btn-danger"; $imgclass="inactive"; }
				$btnclass.=" open-branch";
			}
			
			$memberdata="<div><img src='$photo' alt='$member[name]' class=' member-img $imgclass img-circle' ><br>";
			$memberdata.="<button type='button' class='btn btn-sm $btnclass tree-btn btn-flat' value='$member[regid]' data-parent='$member[parent_id]'>";
			$memberdata.="$member[username]</button><br></div>";
			return $memberdata;
		}
	}
?>