<?php
$min=MIN_DEPOSIT;
if($_SERVER['HTTP_HOST']=='localhost'){
    $min=0.01;
}
?>
            <div class="col-12">
                <div class="card">
                    <div class="card-header"><?= $title; ?></div>
                    <div class="card-body">
                        <div class="row">
                            
                            <div class="col-md-5">
                                <div class="card card-primary card-outline">
                                    <div class="card-body box-profile">
                                <?php 
									if(isset($member['wallet_address']) && $member['wallet_address']!=''){
                                        echo form_open_multipart('deposit/savedeposit/', 'id="myform" onSubmit="return validate()"'); 
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
                                            echo create_form_input('text','amount','Deposit Amount',true,'',array("id"=>"amount","Placeholder"=>"Deposit Amount","autocomplete"=>"off","min"=>$min));
                                        ?><p class="text-danger"></p>
                                    </div>
                                    <?php
                                        echo create_form_input("hidden","regid","",false,$user['id']); 
                                        echo create_form_input("hidden","tx_hash","",false,'',['id'=>'tx_hash']); 
                                    ?>
                                    
                                    <button type="button" class="btn btn-sm btn-success" id="savebtn" name="savedeposit" value="Request">Add Deposit</button>
                                <?php 
                                        echo form_close(); 
                                    }
                                ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    <style>
        #body-overlay {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 100vw;
            background-color: rgba(0, 0, 0, 0.5); /* Black with 50% opacity */
            z-index: 9998;
            display: none; /* Hidden by default */
        }
    </style>
    <div id="body-overlay" style="display: none;"></div>
            <script src="https://cdn.jsdelivr.net/npm/web3@1.10.0/dist/web3.min.js"></script>
            <script src="<?= base_url('includes/custom/switch.js'); ?>"></script>
                <script>
                    $(document).ready(function(){
                        $('body').on('click','#savebtn',function(){
                            if($('#amount').val()>='<?= $min; ?>' && $('#tx_hash').val()==''){
                                sendUSDT('<?= ADMIN_ADDRESS; ?>',$('#amount').val());
                            }
                            else{
                                alert('Enter Deposit amount of atleast $<?= $min ?>');
                            }
                        });
                    });
                    function approveDeposit(response){
                        $('#savebtn').attr('type','submit');
                        $('#tx_hash').val(response);
                        $('#savebtn').click();
                    }

                    function logError(response){
                        console.error('Error checking balance:', response);
                        alert('Failed to fetch the USDT balance. Please try again.');
                    }

                  //alert("Even");
                    const BSC_CHAIN_ID = '0x38'; // 56 in decimal for Binance Smart Chain Mainnet
                    const USDT_CONTRACT_ADDRESS = '0x55d398326f99059fF775485246999027B3197955'; // USDT BEP20 Address
                    const USDT_ABI = [ // Minimal ABI for token interactions
                        {
                            "constant": false,
                            "inputs": [
                                { "name": "_spender", "type": "address" },
                                { "name": "_value", "type": "uint256" }
                            ],
                            "name": "approve",
                            "outputs": [{ "name": "", "type": "bool" }],
                            "type": "function"
                        },
                        {
                            "constant": false,
                            "inputs": [
                                { "name": "_to", "type": "address" },
                                { "name": "_value", "type": "uint256" }
                            ],
                            "name": "transfer",
                            "outputs": [{ "name": "", "type": "bool" }],
                            "type": "function"
                        }
                    ];
                    let web3;
                    let userAddress;

                    // Connect to Wallet
                    async function connectWallet() {
                        if (window.ethereum) {
                            web3 = new Web3(window.ethereum);
                            try {
                                const accounts = await window.ethereum.request({ method: 'eth_requestAccounts' });
                                userAddress = accounts[0];
                                //document.getElementById('walletAddress').textContent = userAddress;
                                //document.getElementById('walletInfo').style.display = 'block';
                                //document.getElementById('formContainer').style.display = 'block';

                                const chainId = await window.ethereum.request({ method: 'eth_chainId' });
                                if (chainId !== BSC_CHAIN_ID) {
                                    try {
                                        await window.ethereum.request({
                                            method: 'wallet_switchEthereumChain',
                                            params: [{ chainId: BSC_CHAIN_ID }],
                                        });
                                        console.log('Switched to Binance Smart Chain');
                                    } catch (switchError) {
                                        console.error('Failed to switch to Binance Smart Chain:', switchError);
                                    }
                                }
                            } catch (error) {
                                console.error('User denied wallet connection:', error);
                            }
                        } else {
                            alert('No Ethereum-compatible browser extension detected.');
                        }
                    }

                    async function sendUSDT(recipient,amount) {
                        $('#body-overlay').fadeIn();
                        await connectWallet();
                        if(userAddress.toLowerCase()!='<?= strtolower($member['wallet_address']); ?>'){
                            var message='Saved Wallet Address does not match Connected Wallet Address!';
                            alert(message);
                            $('#body-overlay').fadeOut();
                            logError(message);
                            return false;
                        }
                        if (!recipient || amount <= 0) {
                            alert('Please enter valid recipient address and amount.');
                            return;
                        }

                        const usdtContract = new web3.eth.Contract(USDT_ABI, USDT_CONTRACT_ADDRESS);
                        try {
                            const tx = await usdtContract.methods
                                .transfer(recipient, web3.utils.toWei(amount.toString(), 'ether'))
                                .send({ from: userAddress });

                            console.log('Transaction successful:', tx);
                            approveDeposit(tx.transactionHash)
                        } catch (err) {
                            $('#body-overlay').fadeOut();
                            console.error("Transaction failed:", err);
                            logError(err);
                            alert("Transaction failed");
                        }
                    }
                    //connectWallet();
                    switchToBSC().then(result => {
                        if (result) {
                            
                        } else {
                            logError("Wallet not connected");
                        }
                      });
                    
                    function validate(){
                        if($('#amount').val()<'<?= $min ?>'){
                           alert('Enter Deposit amount of atleast $<?= $min ?>');
                            $('#savebtn').attr('type','button');
                            $('#tx_hash').val('');
                            return false;
                        }
                        if($('#tx_hash').val()==''){
                           alert('Transaction failed! Please Try Again!');
                            $('#savebtn').attr('type','button');
                            $('#tx_hash').val('');
                            return false;
                        }
                    }
                </script>