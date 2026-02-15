
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card light-bg">
                                    <div class="card-header">
                                        <h3 class="card-title"><?= $title ?></h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <div class="row">

                                            <div class="col-md-5">
                                                <?php 
                                                    if(isset($withdrawal) && $withdrawal===false){
                                                        echo "<div class='text-center'><h2 class='text-danger'>Please Add Atleast 1 Direct Member to Activate Withdrawal</h2></div>";
                                                    }
                                                    elseif(isset($acc_details['kyc']) && $acc_details['kyc']=='1'){
                                                        echo form_open_multipart('wallet/requestpayout/', 'id="myform" onsubmit="return validate();"'); 
                                                ?>
                                                    <div class="form-group">
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
                                                            echo create_form_input("text","","Total E-Wallet Balance",false,$actualwallet,array("readonly"=>"true")); 
                                                        ?>
                                                    </div>
                                                    <div class="form-group">
                                                        <?php
                                                            echo create_form_input("text","","E-Wallet Balance Available for Withdrawal",false,$avl_balance,array("id"=>"avl_balance","readonly"=>"true")); 
                                                        ?>
                                                    </div>
                                                    <div class="form-group">
                                                        <?php
                                                            $type=array(""=>"Select","USDT"=>"USDT","Net Banking"=>"Net Banking","UPI"=>"UPI","CASH"=>"CASH");
                                                            //$type['ewallet']="Wallet Balance";
                                                            echo create_form_input("select","trans_type","Transaction Type",true,'',array("id"=>"trans_type"),$type); 
                                                        ?>
                                                    </div>
                                                    <div class="form-group">
                                                        <?php
                                                            echo create_form_input('number','amount','Withdrawal Amount ($)',true,'',array("id"=>"amount","Placeholder"=>"Withdrawal Amount","autocomplete"=>"off","min"=>MIN_WITHDRAWAL,'step'=>'0.01'));
                                                        ?><p class="text-danger">* <?= TDS ?>% TDS and <?= ADMIN_CHARGE ?>%  Network Fees Will be deducted from Withdrawal Amount</p>
                                                    </div>
                                                    <div class="form-group">
                                                        <?php
                                                            echo create_form_input('text','','Withdrawal Amount (INR)',true,'',array("id"=>"inr_amount","Placeholder"=>"Withdrawal Amount (INR)","autocomplete"=>"off",'readonly'=>'true',"min"=>MIN_WITHDRAWAL));
                                                        ?>
                                                    </div>
                                                    <?php
                                                        echo create_form_input("hidden","regid","",false,$user['id']); 
                                                    ?>
                                                    <?php //if((date('H')>=7 && date('H')<=9) || (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST']=='localhost')){ ?>
                                                    <button type="submit" class="btn btn-sm btn-success" name="requestpayout" value="Request">Request Withdrawal</button>
                                                    <?php //} ?>
                                                <?php 
                                                        echo form_close(); 
                                                    }
                                                    elseif(isset($acc_details['kyc']) && $acc_details['kyc']=='2'){
                                                        echo "<div class='text-center'><h2 class='text-danger'>KYC Approval is Pending!</h2></div>";
                                                    }
                                                    else{
                                                        echo "<div class='text-center'><h2 class='text-danger'>Please Complete Your KYC to Request Withdrawal</h2>";
                                                        echo "<a href='".base_url('profile/kyc/')."' class='btn btn-sm btn-info'>Complete KYC</a></div>";
                                                    }
                                                    if(!isset($acc_details['account_name'])){
                                                         $acc_details['account_name']= $acc_details['bank']= $acc_details['account_no']= $acc_details['branch']= $acc_details['ifsc']="";
                                                    }
                                                ?>
                                            </div>
                                            <div class="col-md-6 profile">
                                                <legend>Bank Information</legend>
                                                <table class="table" id="bank-details">
                                                    <tr>
                                                        <th>A/C Holder Name</th>
                                                        <td><?php echo $acc_details['account_name']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Bank Name</th>
                                                        <td><?php echo $acc_details['bank'];  ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Account Number</th>
                                                        <td><?php echo $acc_details['account_no']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Branch</th>
                                                        <td><?php echo $acc_details['branch']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>IFSC Code</th>
                                                        <td><?php echo $acc_details['ifsc']; ?></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <p class="text-danger mb-1">Note :</p>
                                                <ol class="pl-3 ">
                                                    <li class="text-danger"><?= TDS+ADMIN_CHARGE ?>% Deduction on Withdrawal.</li>
                                                    <li class="text-danger">ROI Withdrawal minimum 20 Days.</li>
                                                    <li class="text-danger">Other Income Minimum Withdrawal Amount is $<?= MIN_WITHDRAWAL; ?>.</li>
                                                    <li class="text-danger">Amount Withdrawal requested after 6 P.M. will be approved next day. </li>
                                                    <li class="text-danger">After change of withdrawal status to 'Approved', Please wait 24 Hours to get amount in your Account.</li>
                                                    <li class="text-danger">After 24 Hours to Approved status, If amount is not credited in your Account then you can claim in next 7 working days.</li>
                                                </ol>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <script>
                    $(document).ready(function(e) {
                        $('#amount').keyup(function(){
                            var avl=Number($('#avl_balance').val());
                            var amount=Number($(this).val());
                            if(isNaN(amount)){ amount=0; }
                            var inr_amount=amount*Number('<?= CONV_RATE ?>');
                            $('#inr_amount').val(inr_amount);
                            if(amount>avl){
                                alert("Withdrawal Amount should be less than Available Balance!");
                                $(this).val('');
                                $('#inr_amount').val('');
                            }
                        });
                    });
                    function validate(){
                        /*var amount=Number($('#amount').val());
                        if(isNaN(amount)){ amount=0; }
                        if(amount%300!=0){
                            alert("Withdrawal Amount should be multiple of 300!");
                            return false;
                        }*/
                    }
                </script>