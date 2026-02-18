<?php
$member['wallet_address']=empty($member['wallet_address'])?'':$member['wallet_address'];
?>
<style>
    
    .address{
        margin-top: 15px;
        padding: 10px;
        background-color: #4ca229;
        border: 1px solid #80d13c;
        border-radius: 5px;
        font-size: 1rem;
        text-align: center;
        color: #ffffff;
    }
</style>
            <div class="col-12">
                <div class="user-profile-form">
                   <div class="headline">
                      <h2><?= $title; ?></h2>
                   </div>
                    <div class="card-body">
                        <div class="row">
                            
                            <div class="col-md-5">
                                <div class=" card-outline">
                                    <div class="card-body box-profile">
                                        <div class="row mb-2">
                                            <div class="col-12 text-center">
                                                <div id="wallet-address" class="address"><?= $member['wallet_address']; ?></div>
                                            </div>
                                        </div>
                                <?php 
									if(isset($member['wallet_address']) && $member['wallet_address']!=''){
                                        echo form_open_multipart('wallet/requestwithdrawal/', 'id="myform" onSubmit="return validate()"'); 
                                ?>
                                    <div class="form-group d-none">
                                        <?php
                                            echo create_form_input("date","date","Date",true,date('Y-m-d')); 
                                        ?>
                                    </div>
                                    <div class="form-group">
                                        <?php
                                            echo create_form_input("text","","Member ID",false,$user['username'],array("readonly"=>"true")); 
                                        ?>
                                    </div>
                                    <div class="form-group">
                                        <?php
                                            echo create_form_input("text","","Name",false,$user['name'],array("readonly"=>"true")); 
                                        ?>
                                    </div>
                                    <div class="form-group">
                                        <?php
                                            echo create_form_input("text","","Wallet Balance",false,$avl_balance,array("id"=>"avl_balance","readonly"=>"true")); 
                                        ?>
                                    </div>
                                    <div class="form-group">
                                        <?php
                                            echo create_form_input('text','amount','Withdrawal Amount',true,'',array("id"=>"amount","Placeholder"=>"Withdrawal Amount","autocomplete"=>"off","min"=>MIN_WITHDRAW));
                                        ?><p class="text-danger"></p>
                                    </div>
                                    <?php
                                        echo create_form_input("hidden","regid","",false,$user['id']); 
                                    ?>
                                    <small class="text-danger">*Note: Minimun withdrawal amount is $<?= MIN_WITHDRAW ?>!</small><br>
                                    <small class="text-danger">*Note: <?= DEDUCTION ?>% will be deducted from the withdrawal amount!</small><br>
                                    <?php
                                        if(date('D')==WITHDRAW_DAY){
                                    ?>
                                    <button type="submit" class="btn btn-sm btn-success" name="requestwithdrawal" value="Request">Request Withdrawal</button>
                                    <?php
                                        }
                                        else{
                                    ?>
                                    <h5 class="text-danger">*Note: Withdrawals are Active only one Monday!</h5><br>
                                    <?php
                                        }
                                    ?>
                                <?php 
                                        echo form_close(); 
                                    }
                                ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            
                            <?php /*?><div class="col-md-12">
                                <p class="text-danger mb-1">Note :</p>
                                <ol class="pl-3 ">
                                    <li class="text-danger">Amount Withdrawal requested after 6 P.M. will be approved next day. </li>
                                    <li class="text-danger">After change of withdrawal status to 'Approved', Please wait 24 Hours to get amount in your Account.</li>
                                    <li class="text-danger">After 24 Hours to Approved status, If amount is not credited in your Account then you can claim in next 7 working days.</li>
                                </ol>
                            </div><?php */?>
                        </div>
                    </div>
                </div>
            </div>

                    <script src="https://cdn.jsdelivr.net/npm/web3@1.10.0/dist/web3.min.js"></script>
                <script>
                let web3 = new Web3(window.ethereum);

                    $(document).ready(function(e) {
                        $('#amount').keyup(function(){
                            var avl=Number($('#avl_balance').val());
                            var amount=Number($(this).val());
                            if(isNaN(amount)){ amount=0; }
                            if(amount>avl){
                                alert("Withdrawal Amount should be less than Available Balance!");
                                $(this).val('');
                            }
                        });
                    });
                    function validate(){
                        var avl=Number($('#avl_balance').val());
                        var amount=Number($('#amount').val());
                        if(isNaN(amount)){ 
                            amount=0; 
                            alert("Enter Valid Withdrawal Amount!");
                            return false;
                        }
                        if(amount<Number('<?= MIN_WITHDRAW ?>') ){
                            alert("Minimum Withdrawal Amount is $<?= MIN_WITHDRAW ?>!");
                            return false;
                        }
                        if(amount>avl){
                            alert("Withdrawal Amount should be less than Available Balance!");
                            return false;
                            //$('#amount').val('');
                        }
                    }
                </script>