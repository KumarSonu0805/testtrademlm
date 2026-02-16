<?php
$tokenrate=getTokenRate();
$settings=$this->setting->getsettings(['name'=>'coin_rate'],'single');
$rate=$settings['value'];
?>
            <div class="col-12">
                <div class="card">
                    <div class="card-header"><?= $title; ?></div>
                    <div class="card-body">
                        <div class="row">
                            
                            <div class="col-md-5">
                                <div class="card card-outline">
                                    <div class="card-body box-profile">
                                        <div class="row">
                                            <div class="col-12 text-center">
                                                <div id="wallet-address" class="badge btn btn-success"><?= $member['wallet_address']; ?></div>
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
                                            echo create_form_input('text','amount','Withdrawal Amount',true,'',array("id"=>"amount","Placeholder"=>"Withdrawal Amount","autocomplete"=>"off","min"=>0));
                                        ?><p class="text-danger"></p>
                                    </div>
                                    <?php
                                        echo create_form_input("hidden","regid","",false,$user['id']); 
                                    ?>
                                    <small class="text-danger">*Note: 10% will be deducted from the withdrawal amount!</small><br>
                                    <button type="submit" class="btn btn-sm btn-success" name="requestwithdrawal" value="Request">Request Withdrawal</button>
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

                const poolAbi = [{
                  "inputs": [],
                  "name": "slot0",
                  "outputs": [
                    { "internalType": "uint160", "name": "sqrtPriceX96", "type": "uint160" },
                    { "internalType": "int24", "name": "tick", "type": "int24" },
                    { "internalType": "uint16", "name": "observationIndex", "type": "uint16" },
                    { "internalType": "uint16", "name": "observationCardinality", "type": "uint16" },
                    { "internalType": "uint16", "name": "observationCardinalityNext", "type": "uint16" },
                    { "internalType": "uint8", "name": "feeProtocol", "type": "uint8" },
                    { "internalType": "bool", "name": "unlocked", "type": "bool" }
                  ],
                  "stateMutability": "view",
                  "type": "function"
                }];

                const poolAddress = "0xd793764dc7968715661c9682fff67edb6de1fdac";

                const contract = new web3.eth.Contract(poolAbi, poolAddress);

                async function getV3Price() {
                    const slot0 = await contract.methods.slot0().call();
                    const sqrtPriceX96 = BigInt(slot0.sqrtPriceX96);
                    const price = Number(sqrtPriceX96 ** 2n) / (2 ** 192);
                    var rate=price.toFixed(8);
                    var avl=$('#avl_balance').val();
                    avl*=rate;
                    $('#avl_usdt').val(avl);
                    $('#rate').val(rate);
                    console.log("Approx price:", price.toFixed(8));
                    console.log("Approx price:", avl);
                }
                    $(document).ready(function(e) {
                        $('#amount').keyup(function(){
                            var avl=Number($('#avl_balance').val());
                            var amount=Number($(this).val());
                            var amount_usdt=0;
                            var rate=Number($('#rate').val());
                            if(isNaN(amount)){ amount=0; }
                            amount_usdt=amount*rate;
                            if(amount>avl){
                                alert("Withdrawal Amount should be less than Available Balance!");
                                $(this).val('');
                                $('#amount_usdt').val('');
                            }
                            else{
                                $('#amount_usdt').val(amount_usdt);
                                if(isNaN($('#avl_usdt').val()) || $('#avl_usdt').val()==0){
                                    var avl_usdt=avl*rate;
                                    $('#avl_usdt').val(avl_usdt);
                                }
                            }
                        });
                        getV3Price();
                    });
                    function validate(){
                        var avl=Number($('#avl_balance').val());
                        var amount=Number($('#amount').val());
                        if(isNaN(amount)){ 
                            amount=0; 
                            alert("Enter Valid Withdrawal Amount!");
                            return false;
                        }
                        if(amount<1){
                            alert("Minimum Withdrawal Amount is 1 USDT!");
                            return false;
                        }
                        if(amount>avl){
                            alert("Withdrawal Amount should be less than Available Balance!");
                            return false;
                            //$('#amount').val('');
                        }
                    }
                </script>