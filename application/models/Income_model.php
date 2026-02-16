<?php
class Income_model extends CI_Model{
    
    private $dailyRate=0.004;
    private $boosterRate=0.005;
    private $matching=0.002;
    private $capping=500;
    private $coinRate;
    private $active_ranks=array();
    
	function __construct(){
		parent::__construct(); 
		$this->db->db_debug = false;
	}
    
    function getLevelPercentage($level,$direct) {
        if ($level == 1 && $direct>=1) return 0.25;
        if ($level == 2) return 0.20; 
        if ($level == 3 && $direct>=2) return 0.12;
        if ($level == 4 && $direct>=3) return 0.08;
        if ($level == 5 && $direct>=4) return 0.05;
        if ($level >= 6 && $level <= 10 && $direct>=5) return 0.02;
        if ($level >= 11 && $level <= 20 && $direct>=9) return 0.01;
        if ($level >= 21 && $level <= 30 && $direct>=10) return 0.01;
        return 0;
    }

    public function get_leg_business($regid){
        $legs = [];

        // Step 1: Get direct referrals
        $referrals = $this->db->where('refid', $regid)->get('members')->result_array();

        foreach ($referrals as $ref) {
            $downline_ids = $this->member->getmembers($ref['regid']);
            $downline_ids=!empty($downline_ids)?array_column($downline_ids,'regid'):array();
            $downline_ids[] = $ref['regid']; // include leg head's business too
            if (!empty($downline_ids)) {
                $this->db->select('sum(amount) as amount');
                
				$this->db->group_start();
				$regid_chunks = array_chunk($downline_ids,25);
				foreach($regid_chunks as $regid_chunk){
					$this->db->or_where_in('regid', $regid_chunk);
				}
				$this->db->group_end();
				$this->db->where(['status'=>1]);
                $investments = $this->db->get('investments')->unbuffered_row('array');
                $legs[] = [
                    'regid' => $ref['regid'],
                    'business' => $investments['amount']
                ];
            }
        }

        return $legs;
    }
    
    public function check_ranks($regid){
        $where="regid in (SELECT member_id from ".TP."level_members where regid='$regid')";
        $this->db->select_sum('amount');
        $teambusiness=$this->db->get_where('investments',$where)->unbuffered_row()->amount;
        $teambusiness=!empty($teambusiness)?$teambusiness:0;
        
        $where=array();
        $ranks=$this->db->get_where('ranks',$where)->result_array();
        if(isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST']=='localhost'){
            //$teambusiness+=100000;
        }
        foreach($ranks as $rank){
            if ($teambusiness >= $rank['leg_1']) {
                if($this->db->get_where('member_ranks',['regid'=>$regid,'rank_id'=>$rank['id']])->num_rows()==0){
                    $data=array('date'=>date('Y-m-d'),'regid'=>$regid,'rank_id'=>$rank['id'],'rank'=>$rank['rank']);
                    $this->db->insert('member_ranks',$data);
                }
                //$teambusiness-=$rank['leg_1'];
                $this->active_ranks[]=$rank['id'];
            }
            else{
                break;
            }
        }

        return false;
    }
    
    public function checkbooster($user,$date=NULL){
        $regid=$user['id'];
        
        $date=$date===NULL?date('Y-m-d'):$date;
        $member=$this->member->getmemberdetails($regid);
        if($member['status']==1 && $member['booster']==0){
            $activation=$member['activation_date'].' '.$member['activation_time'];
            $limit=date('Y-m-d H:i:s',strtotime($activation.' +7 days'));
            $where=['refid'=>$regid,"CONCAT(activation_date, ' ', activation_time)<="=>$limit,'status'=>1];
            $directs=$this->db->get_where('members',$where)->num_rows();
            $deposits=$this->member->getdeposits(['t1.regid'=>$regid,'status'=>1]);
            $deposit_amounts=!empty($deposits)?array_column($deposits,'amount'):array();
            $deposit=array_sum($deposit_amounts);
            
            if($directs>=5 && $deposit>=5000){
                $this->db->update('members',['booster'=>1],['regid'=>$regid]);
            }
        }
    }
    
    public function generateincome($user,$date=NULL){
        $regid=$user['id'];
        
        $date=$date===NULL?date('Y-m-d'):$date;
        $member=$this->member->getmemberdetails($regid);
        //print_pre($member);
        
        if($member['status']==1 && $member['activation_date']!='0000-00-00' && $member['activation_date']<=$date){
            $booster=$member['booster']==1?TRUE:FALSE;
            $investment=$mininginvestment=0;
            $getinvestments=$this->db->get_where('investments',['regid'=>$regid,'date<='=>$date,'status'=>1]);
            $investmentcount=$getinvestments->num_rows();
            $selfinvestment=0;
            if($investmentcount>0){
                $inv=$getinvestments->unbuffered_row('array');
                $selfinvestment=$inv['amount'];
            }
            
            
            $investments=$this->db->get_where('investments',['regid'=>$regid,'date<='=>$date,
                                                                 'status'=>1])->result_array();
            
            
            if(!empty($investments)){
                foreach($investments as $investment){
                    $inv_id=$investment['id'];
                    $rate=$booster===TRUE?$this->boosterRate:$this->dailyRate;
                    $amount=$investment['amount']*$rate;
                    if($amount>0){
                        $where=array('regid'=>$regid,'date'=>$date,'inv_id'=>$inv_id,'type'=>'roiincome','status'=>1);
                        if($this->db->get_where('income',$where)->num_rows()==0){
                            $data=array('regid'=>$regid,'date'=>$date,'inv_id'=>$inv_id,'type'=>'roiincome',
                                        'rate'=>$rate,'amount'=>$amount,'status'=>1,
                                        'added_on'=>date('Y-m-d H:i:s'),'updated_on'=>date('Y-m-d H:i:s'));
                            $this->db->insert('income',$data);
                        }
                    }

                }
            }
            
            $directs=$this->db->get_where('members',['refid'=>$regid,'status'=>1])->num_rows();
            
            $levelmembers=$this->member->levelwisemembers($regid,$date,1);
            if(!empty($levelmembers)){
                foreach($levelmembers as $levelmember){
                    $member_id=$levelmember['member_id'];
                    $level=$levelmember['level'];
                    $this->db->select('sum(amount) as amount');
                    $wh=['type'=>'roiincome','regid'=>$member_id,'date'=>$date,'status'=>1];
                    $getroiincome=$this->db->get_where('income',$wh);
                    $roiincome=$getroiincome->unbuffered_row()->amount;
                    $roiincome=$roiincome===NULL?0:$roiincome;
                    $rate=$this->getLevelPercentage($level,$directs);
                    $amount=$rate*$roiincome;
                    if($amount>0){
                        $where=array('regid'=>$regid,'date'=>$date,'member_id'=>$member_id,'level'=>$level,'type'=>'level',
                                     'status'=>1);
                        if($this->db->get_where('income',$where)->num_rows()==0){
                            $data=array('regid'=>$regid,'date'=>$date,'member_id'=>$member_id,'level'=>$level,
                                        'type'=>'level','rate'=>$rate,'amount'=>$amount,
                                        'status'=>1,'added_on'=>date('Y-m-d H:i:s'),'updated_on'=>date('Y-m-d H:i:s'));
                            $this->db->insert('income',$data);
                        }
                    }
                }
            }
            
            $legs=$this->get_leg_business($regid);
            // Sort legs by business descending
            usort($legs, function($a, $b) {
                return $b['business'] <=> $a['business'];
            });

            if (count($legs) >= 2) {
                $top_legs = array_slice($legs, 0, 2);
                $leg_1=$top_legs[0]['business'];
                $leg_2=$top_legs[1]['business'];
                if($leg_1>0 && $leg_2>0){
                    $this->db->select("(sum(amount/rate)+sum(capping/rate)) as amount");
                    $prev=$this->db->get_where('income',['regid'=>$regid,'date<'=>$date,
                                                         'type'=>'matching'])->unbuffered_row()->amount;
                    $prev=$prev??0;
                    $leg_1-=$prev;
                    $leg_2-=$prev;
                    
                    $matching=$leg_2;
                    if($matching>0){
                        $amount=$matching*$this->matching;

                        $this->db->select_sum("amount");
                        $todays=$this->db->get_where('income',['regid'=>$regid,'date'=>$date,
                                                               'type'=>'matching'])->unbuffered_row()->amount;
                        $todays=$todays??0;

                        $capping=0;
                        if($amount>$this->capping){
                            $capping=$amount-$this->capping;
                            $amount=$this->capping-$todays;
                            $amount+=$todays;
                        }

                        if($amount>0){
                            $data=array('regid'=>$regid,'date'=>$date,'type'=>'matching','rate'=>$this->matching,
                                        'capping'=>$capping,'amount'=>$amount,'status'=>1,'added_on'=>date('Y-m-d H:i:s'),
                                        'updated_on'=>date('Y-m-d H:i:s'));
                            $where=array('regid'=>$regid,'date'=>$date,'type'=>'matching','status'=>1);
                            if($this->db->get_where('income',$where)->num_rows()==0){
                                $this->db->insert('income',$data);
                            }
                            else{
                                unset($data['added_on']);
                                $this->db->update('income',$data,$where);
                            }
                        }
                    }
                }
            }
            
            $this->check_ranks($regid);
            //Reward Income
            $where="t1.regid='$regid' and t1.rank_id not in (SELECT rank_id from ".TP."income where regid='$regid' and type='reward')";
            $this->db->select('t1.rank_id,t1.rank,t2.reward');
            $this->db->from('member_ranks t1');
            $this->db->join('ranks t2','t1.rank_id=t2.id');
            $this->db->where($where);
            $query=$this->db->get();
            $pending=$query->result_array();
            if(!empty($pending)){
                foreach($pending as $single){
                    if(!in_array($single['rank_id'],$this->active_ranks)){
                        continue;
                    }
                    $amount=$single['reward'];
                    if($amount>0){
                        $data=array('regid'=>$regid,'date'=>$date,'type'=>'reward','rank_id'=>$single['rank_id'],
                                    'amount'=>$amount,'status'=>1,'added_on'=>date('Y-m-d H:i:s'),
                                    'updated_on'=>date('Y-m-d H:i:s'));
                        $this->db->insert('income',$data);
                    }
                }
            }
            
        }
    }
    
    
    public function generateallincome($date=NULL){
        $this->db->order_by('id desc');
        $users=$this->db->get_where('users',['id>'=>1])->result_array();
        foreach($users as $user){
            $this->checkbooster($user,$date);
            $this->generateincome($user,$date);
        }
    }
    
    
    
    public function getallincome($user){
        $regid=$user['id'];
        $where=array("t1.regid"=>$regid,"t1.status"=>1);
        $this->db->select('t1.*,t2.username');
        $this->db->from("income t1");
        $this->db->join("users t2",'t1.member_id=t2.id','left');
        $this->db->where($where);
        $this->db->order_by("t1.date");
        $income=$this->db->get()->result_array();
        return $income;
    }
    
    public function getincome($where){
        $this->db->select("t1.*,t2.username,t2.name as member_name,ifnull(t5.rank,t3.rank) as rank");
        $this->db->from("income t1");
        $this->db->join("users t2",'t1.member_id=t2.id','left');
        $this->db->join("ranks t3",'t1.rank_id=t3.id','left');
        $this->db->join("member_ranks t4",'t1.royalty_id=t4.id','left');
        $this->db->join("ranks t5",'t4.rank_id=t5.id','left');
        $this->db->where($where);
        $this->db->where(['t1.amount>'=>0]);
        $income=$this->db->get()->result_array();
        return $income;
    }
    
    public function getmemberwallet(){
        $columns="t2.*,t3.username as ref,t3.name as refname";
        $this->db->select($columns);
        $this->db->from("members t1");
        $this->db->join("users t2","t1.regid=t2.id");
        $this->db->join("users t3","t1.refid=t3.id");
        $query=$this->db->get();
        $array=$query->result_array();
        if(!empty($array)){
            foreach($array as $key=>$value){
                $income=$withdrawal=0;
                $incomes=$this->income->getallincome($value);
                if(!empty($incomes)){
                    $amounts=array_column($incomes,'amount');
                    $income=array_sum($amounts);
                }
                $array[$key]['income']=$income;
                $withdrawals=$this->member->getwithdrawalrequest(['t1.regid'=>$value['id'],'t1.status!='=>2]);
                if(!empty($withdrawals)){
                    $amounts=array_column($withdrawals,'amount');
                    $withdrawal=array_sum($amounts);
                }
                $array[$key]['withdrawal']=$withdrawal;
                $array[$key]['balance']=getavlbalance($value);
            }
        }
        return $array;
    }
}
