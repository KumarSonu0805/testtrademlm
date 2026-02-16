<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        // Load global models, check auth, etc.
    }
    
    public function index(){
        checklogin();
        $data=['title'=>'Home'];
        if($this->session->role=='admin' && $this->session->user==md5(1)){
        }
        else{
            $data['user']=getuser();
            $this->income->generateincome($data['user']);
            $data['member']=$this->member->getmemberdetails($data['user']['id']);
        }  
        $this->template->load('pages','home',$data);
    }
    
    public function invite(){
        checklogin();
        if($this->session->role=='admin'){
            redirect('/');
        }
        $data=['title'=>'Invite'];
        $user=getuser();
        $data['members']=$this->member->getdirectmembers($user['id']);
        $data['datatable']=true;
        $this->template->load('pages','invite',$data);
    }
    
	public function changepassword(){
        checklogin();
        $getuser=$this->account->getuser(array("md5(id)"=>$this->session->user));
        if($getuser['status']===true){
            $data['user']=$getuser['user'];
        }
        else{
            redirect('home/');
        }
        $data['title']="Edit Password";
        //$data['subtitle']="Sample Subtitle";
        $data['breadcrumb']=array();
        $data['alertify']=true;
		$this->template->load('pages','changepassword',$data);
	}
    
    public function updatepassword(){
        if($this->input->post('updatepassword')!==NULL){
            $old_password=$this->input->post('old_password');
            $password=$this->input->post('password');
            $repassword=$this->input->post('repassword');
            $user=getuser();
            if(password_verify($old_password.SITE_SALT.$user['salt'],$user['password'])){
                $user=$this->session->user;
                if($password==$repassword){
                    $result=$this->account->updatepassword(array("password"=>$password),array("md5(id)"=>$user));
                    if($result['status']===true){
                        $this->session->set_flashdata('msg',$result['message']);
                    }
                    else{
                        $error=$result['message'];
                        $this->session->set_flashdata('err_msg',$error);
                    }
                }
                else{
                    $error=$result['message'];
                    $this->session->set_flashdata('err_msg',"Password Do not Match!");
                }
            }
            else{
                $this->session->set_flashdata('err_msg',"Old Password Does not Match!");
            }
        }
        redirect($_SERVER['HTTP_REFERER']);
    }
    
    public function updatecoinrate(){
        session_write_close();  
        $this->setting->generatesettings();
        $settings=$this->setting->getsettings(['name'=>'coin_rate'],'single');
        $rate=$this->input->post('rate');
        $data=['id'=>$settings['id'],'value'=>$rate];
        $result=$this->setting->updatesetting($data);
        echo $result['message'];
    }
    
    public function updatewallet(){
        $handle = fopen('./wallet.txt', 'r');
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $array=explode('-',$line);
                $username=!empty($array[0])?trim($array[0]):'';
                $amount=!empty($array[1])?trim($array[1]):0;
                if(!empty($username) && !empty($amount)){
                    $getuser=$this->db->get_where('users',['username'=>$username]);
                    if($getuser->num_rows()==1){
                        $user=$getuser->unbuffered_row('array');
                        $regid=$user['id'];
                        $date='2025-07-01';//date('Y-m-d');
                        $rate=0.8;
                        $amount=$amount/$rate;
                        if($this->db->get_where('income',['regid'=>$regid,'type'=>'opening'])->num_rows()==0){
                            $data=array('regid'=>$regid,'date'=>$date,'type'=>'opening','rate'=>$rate,'amount'=>$amount,'status'=>1,
                                        'added_on'=>date('Y-m-d H:i:s'),'updated_on'=>date('Y-m-d H:i:s'));
                            $this->db->insert('income',$data);
                        }
                    }
                }
            }
            fclose($handle);
        } else {
            echo "Unable to open file.";
        }
    }
    
    public function checkcompound(){
        $principal=10;
        $dailyRate=0.009;
        echo 'Interests:<br>';
        for($days=0;$days<=10;$days++){
            echo $days.':';
            if($days>0){
                if($days==5){
                    $principal= $this->income->calculateDailyCompound($principal, $dailyRate, 1);
                    $principal+= $this->income->calculateDailyCompound(10, $dailyRate, 1);
                }
                else{
                    $principal= $this->income->calculateDailyCompound($principal, $dailyRate, 1);
                }
            }
            echo $principal;
            echo '<br>';
        }
        
        echo '<br>--------------------------------<br>';
        $principal = 10;       // Initial deposit
        $days = 10;            // Number of compounding days
        $rate = 0.009;         // 0.9% per day

        for($days=1;$days<=10;$days++){
            $finalAmount = $this->income->calculateDailyCompound($principal, $rate, $days);
            $totalReward = $finalAmount - $principal;
            if($days==4){
                $principal+=10;
            }
            echo "Final Amount after $days days: $" . number_format($finalAmount, 6) . "<br>";
            echo "Total Reward: $" . number_format($totalReward, 6) . "<br>";
        }
        
        echo '<br>--------------------------------<br>';
        $dailyRate = 0.009;
        $totalDays = 10;

        // First deposit: $10 on Day 0 → active for 10 days
        $deposit1Amount = 10;
        $deposit1Days = 10;
        $final1 = $this->income->calculateDailyCompound($deposit1Amount, $dailyRate, $deposit1Days);

        // Second deposit: $10 on Day 5 → active for (10 - 5) = 5 days
        $deposit2Amount = 10;
        $deposit2Days = 5;
        $final2 = $this->income->calculateDailyCompound($deposit2Amount, $dailyRate, $deposit2Days);

        // Total final amount and reward
        $finalTotal = $final1 + $final2;
        $totalPrincipal = $deposit1Amount + $deposit2Amount;
        $totalReward = $finalTotal - $totalPrincipal;

        // Output
        echo "Final Amount after $totalDays days: $" . number_format($finalTotal, 6) . "<br>";
        echo "Total Reward: $" . number_format($totalReward, 6) . "<br>";
        
        echo '<br>--------------------------------<br>';
        $deposits = [
            ['amount' => 10, 'date' => '2025-06-13'],  // 10 days ago
            ['amount' => 10, 'date' => '2025-06-17'],  // 5 days ago
        ];

        $targetDate = '2025-06-23';
        $startDate = '2025-06-13';

        for($i=0;$i<=10;$i++){
            $targetDate=date('Y-m-d',strtotime($startDate.' +'.$i.'days'));
            $total = $this->income->compoundedTotal($deposits, $targetDate);
            //$principal = array_sum(array_column($deposits, 'amount'));
            $principal = 0;
            foreach($deposits as $deposit){
                if($deposit['date']<=$targetDate){
                    $principal+=$deposit['amount'];
                }
            }
            $reward = $total - $principal;

            echo "Final Amount on $targetDate after $i days: $" . number_format($total, 6) . "<br>";
            echo "Total Reward: $" . number_format($reward, 6) . "<br>";
        }

    }
    
    public function error(){
        $data=['title'=>'Error'];
        $this->template->load('pages','error',$data);
    }
    
    public function testswap(){
        $data=['title'=>'Swap'];
        //$this->template->load('pages','testswap',$data);
        $this->load->view('pages/testswap2',$data);
    }
    
    public function teststake(){
        $data=['title'=>'Stake'];
        $this->template->load('pages','teststake',$data);
    }
    
    public function staked(){
        $data=['title'=>'Staked'];
        $this->template->load('pages','staked',$data);
    }
    
    public function liverate(){
        $data=['title'=>'Live Rate'];
        $this->template->load('pages','liverate',$data);
    }
    
    public function liquidity(){
        $data=['title'=>'Liquidity'];
        $this->template->load('pages','liquidity',$data);
    }
    
    public function generateincome($date=NULL){
        $this->income->generateallincome($date);
        echo "Executed at ".date('d-m-Y H:i:s');
    }
    
    public function testincome($id){
        $user=$this->db->get_where('users',['id'=>$id])->unbuffered_row('array');
        print_pre($user);
        $this->income->generateincome($user);
        echo "Executed at ".date('d-m-Y H:i:s');
    }
    
    public function updateinvestment(){
        $tokenrate=getTokenRate();
        $members=$this->db->get_where('members',"old='1' and regid not in (SELECT regid from ".TP."investments)")->result_array();
        $datetime=date('Y-m-d H:i:s');
        if(!empty($members)){
            foreach($members as $member){
                $amount=$member['package']/2;
                if($tokenrate>0){
                    $amount/=$tokenrate;
                }
                else{
                    $amount=0;
                }
                $data=array('regid'=>$member['regid'],'date'=>date('Y-m-d'),'amount'=>$amount,'old'=>1,'status'=>1,
                            'added_on'=>$datetime,'updated_on'=>$datetime);
                $this->db->insert('investments',$data);
            }
        }
    }
    
    public function updateunstake(){
        $this->db->order_by('updated_on');
        $this->db->group_by('regid,updated_on');
        $this->db->select('*,sum(amount) as t_amount,sum(reward) as t_reward,total as t_total,sum(amount+reward) as tt');
        $investments=$this->db->get_where('investments',['status'=>0,'old'=>0,'unstaked'=>1])->result_array();
        //print_pre($investments,true);
        //echo count($investments);
        $tokenrate=getTokenRate();
        //`regid`, `inv_id`, `date`, `amount`, `rate`, `reward`, `total`, `approve_date`, `response`, `remarks`, `approved_by`, `status`, `added_on`, `updated_on`
        $alldata=array();
        if(!empty($investments)){
            //foreach($investments as $single){
            $regid=0;$s=0;$regids=array();$not=array();
            for($i=0;$i<count($investments);$i++){
                $single=$investments[$i];
                if(bccomp($single['t_total'], $single['tt'], 8) !== 0 && 
                   bccomp($single['t_total'], $single['tt'], 4) !== 0){
                    print_pre($single);
                    $s++;
                    $not[]=$single['id'];
                    continue;
                }
                $regid=$single['regid'];
                $amount=$single['t_amount'];
                $reward=$single['t_reward'];
                $next=isset($investments[$i+1])?$investments[$i+1]:array();
                $added_on=$single['added_on'];
                $updated_on=$single['updated_on'];
                $total=$reward+$amount;
                $date=date('Y-m-d',strtotime($single['updated_on']));
                $data=array('regid'=>$regid,'date'=>$date,'amount'=>$amount,'rate'=>0,
                            'reward'=>$reward,'total'=>$total,'approve_date'=>$date,'approved_by'=>$regid,
                            'status'=>1,'added_on'=>$added_on,'updated_on'=>$updated_on);
                //print_pre($data);
                $alldata[]=$data;
                $regids[]=$regid;
                //echo '<br>--------------------------------<br>';
            }
        }
        echo $s;
        if(!empty($alldata)){
            $this->db->trans_start();
            $this->db->insert_batch('unstake',$alldata);
            if(!empty($regids)){
                $this->db->where_in('regid',$regids);
            }
            if(!empty($not)){
                $this->db->where_not_in('id',$not);
            }
            $this->db->update('investments',['unstake'=>0],['status'=>0,'old'=>0,'unstaked'=>1]);
            print_pre($this->db->last_query());
        }
        //print_pre($alldata);
    }
    
    public function runquery(){
        $query=array(
                    "ALTER TABLE `bs_member_ranks` CHANGE `rank` `rank` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;"
        );
        foreach($query as $sql){
            if(!$this->db->query($sql)){
                print_r($this->db->error());
            }
        }
    }
    
    public function importdata(){
        $json=file_get_contents(file_url('assets/data.json'));
        return false;
        $array=json_decode($json,true);
        if(!empty($array)){
            foreach($array as $row){
                if(empty($row['Member ID'])){
                    continue;
                }
                
                $username=$row['Member ID'];
                $name=$row['Name'];
                $susername=$row['Sponsor ID'];
                $sname=$row['Sponsor Name'];
                $date=date('Y-m-d',strtotime($row['Joining Date']));
                $mobile=$row['MobileNo'];
                $email=$row['Email'];
                $amount=$row['Amount'];
                $wallet_address=$row['Wallet Address'];
                $getreferrer=$this->account->getuser("username='$susername'");
                
                $userdata=$memberdata=array();
                if($getreferrer['status']===true){
                    $referrer=$getreferrer['user'];
                    $userdata['username']=$username;
                    $userdata['name']=$name;
                    $userdata['mobile']=$mobile;
                    $userdata['email']=$email;
                    $userdata['role']="member";
                    $userdata['status']="1";

                    $memberdata['name']=$name;
                    $memberdata['wallet_address']=!empty($wallet_address)?$wallet_address:NULL;
                    $memberdata['refid']=$referrer['id'];
                    $memberdata['date']=$date;
                    $memberdata['time']=date('H:i:s');
                    $memberdata['activation_date']=$date;
                    $memberdata['activation_time']=date('H:i:s');
                    $memberdata['old']=1;
                    $status=1;
                    if(empty($amount) && empty($wallet_address)){
                        $amount=0;
                        $status=0;
                    }
                    $memberdata['package']=$amount;
                    $memberdata['status']=$status;


                    $data=array("userdata"=>$userdata,"memberdata"=>$memberdata);
                    print_pre($data);//continue;
                    //print_pre($data,true);
                    $result=$this->member->addmember($data);
                    print_pre($result);
                    echo '------------------------------------------------';
                }
                else{
                    
                    print_pre($susername);
                    print_pre($getreferrer);
                }
            }
        }
    }
    
}