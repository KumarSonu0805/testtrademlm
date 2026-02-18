<?php
class Income_model extends CI_Model{
    
    private $dailyRate=0.01;
    private $bonus=0.1;
    private $bonuspackage=array(100,500,1000,2000,5000);
    private $sponsor=0.05;
    private $matching=0.002;
    private $capping=500;
    private $coinRate;
    private $active_ranks=array();
    
	function __construct(){
		parent::__construct(); 
		$this->db->db_debug = false;
	}
    
    function getLevelPercentage($level,$direct) {
        if ($level == 1 && $direct>=$level) return 0.20;
        if ($level == 2 && $direct>=$level) return 0.10; 
        if ($level == 3 && $direct>=$level) return 0.06;
        if ($level == 4 && $direct>=$level) return 0.04;
        if ($level == 5 && $direct>=$level) return 0.01;
        if ($level >= 6 && $level <= 15 && $direct>=$level) return 0.05;
        return 0;
    }
    
    function getSalary($direct,$team) {
        if ($direct>=50 && $team>=5000) return 2000;
        if ($direct>=30 && $team>=1000) return 1000;
        if ($direct>=20 && $team>=500) return 500;
        if ($direct>=15 && $team>=200) return 300;
        if ($direct>=10 && $team>=100) return 200;
        if ($direct>=5 && $team>=50) return 100;
        return 0;
    }
    
    function getReward($team) {
        $result=array();
        if ($team>=100000) $result[]= 500000;
        if ($team>=50000) $result[]= 200000;
        if ($team>=10000) $result[]= 30000;
        if ($team>=5000) $result[]= 10000;
        if ($team>=3000) $result[]= 1500;
        if ($team>=1000) $result[]= 800;
        if ($team>=600) $result[]= 500;
        if ($team>=300) $result[]= 400;
        if ($team>=100) $result[]= 200;
        if ($team>=50) $result[]= 100;
        if ($team>=20) $result[]= 50;
        return $result;
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
                    $rate=$this->dailyRate;
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
            
            //Direct Income
            $subquery="SELECT member_id from ".TP."income where regid='$regid' and type='direct' and status='1'";
            $where="t1.refid='$regid' and t1.status='1' and t1.activation_date<='$date' and 
                    t1.regid not in ($subquery) and t2.status='1' and t1.activation_date>='2025-09-08'";
            $this->db->select("t1.*,t2.id as inv_id,t2.amount");
            $this->db->from("members t1");
            $this->db->join("investments t2","t1.regid=t2.regid");
            $this->db->where($where);
            $getdirects=$this->db->get();
            $directs=$getdirects->result_array();
            if($this->input->get('test')=='test'){
                echo $regid;
                print_pre($directs);
            }
            if(!empty($directs)){
                foreach($directs as $direct){
                    $member_id=$direct['regid'];
                    $ref_investment=$direct['amount'];
                    $inv_id=$direct['inv_id'];
                    $where=array('regid'=>$regid,'member_id'=>$member_id,'type'=>'direct','status'=>1);
                    if($this->db->get_where('income',$where)->num_rows()==0){
                        if($ref_investment>0){
                            $rate=$this->sponsor;
                            $amount=$ref_investment*$rate;
                            if($amount>0){
                                $data=array('regid'=>$regid,'date'=>$date,'type'=>'direct','member_id'=>$member_id,
                                            'rate'=>$rate,'amount'=>$amount,'status'=>1,
                                            'added_on'=>date('Y-m-d H:i:s'),'updated_on'=>date('Y-m-d H:i:s'));
                                if($this->input->get('test')=='test'){
                                    print_pre($data);
                                }
                                $this->db->insert('income',$data);
                            }
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
            
            $team=!empty($levelmembers)?count($levelmembers):0;
            
            $month=date('m',strtotime($date));
            $year=date('Y',strtotime($date));
            $amount=$this->getSalary($directs,$team);
            if($amount>0){
                $where=array('regid'=>$regid,'month(date)'=>$month,'year(date)'=>$year,'type'=>'salary','status'=>1);
                if($this->db->get_where('income',$where)->num_rows()==0){
                    $data=array('regid'=>$regid,'date'=>$date,'type'=>'salary','amount'=>$amount,
                                'status'=>1,'added_on'=>date('Y-m-d H:i:s'),'updated_on'=>date('Y-m-d H:i:s'));
                    $this->db->insert('income',$data);
                }
            }
            
            $amounts=$this->getReward($team);
            if(!empty($amounts)){
                foreach($amounts as $amount){
                    $where=array('regid'=>$regid,'amount'=>$amount,'type'=>'reward','status'=>1);
                    if($this->db->get_where('income',$where)->num_rows()==0){
                        $data=array('regid'=>$regid,'date'=>$date,'type'=>'reward','amount'=>$amount,
                                    'status'=>1,'added_on'=>date('Y-m-d H:i:s'),'updated_on'=>date('Y-m-d H:i:s'));
                        $this->db->insert('income',$data);
                    }
                }
            }
            return false;
        }
    }
    
    
    public function generateallincome($date=NULL){
        $this->db->order_by('id desc');
        $users=$this->db->get_where('users',['id>'=>1])->result_array();
        foreach($users as $user){
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
