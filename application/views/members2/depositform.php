<?php
$min=MIN_DEPOSIT;
$member['wallet_address']=empty($member['wallet_address'])?'':$member['wallet_address'];
$address['value']='';
$c=0;
while(empty($address['value']) && $c<10){
    $index=rand(0,4);
    $index=empty($index)?'':$index;
    $address=$this->setting->getsettings(['name'=>'admin_address'.$index],'single');
    $qrcode=$this->setting->getsettings(['name'=>'qrcode'.$index],'single'); 
    $c++;
}
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
    .copy-btn{
        float: right;
        margin-top: -8px;
        color: #fff;
    }
</style>
                <div class="card">
                    <div class="card-header"><?= $title; ?></div>
                    <div class="card-body">
                        <div class="row">
                            
                            <div class="col-md-5">
                                <div class="card  card-outline">
                                    <div class="card-body box-profile">
                                <?php 
									if(isset($member['wallet_address']) && $member['wallet_address']!=''){
                                        echo form_open_multipart('deposit/savedeposit/', 'id="myform" onSubmit="return validate()"'); 
                                ?>
                                    <div class="form-group mb-2 d-none">
                                        <button type="button" class="btn btn-sm btn-primary" onClick="connectWallet()">Connect To Wallet</button>
                                    </div>
                                    <div id="walletAddress" class="address d-none"></div>
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
                                            echo create_form_input("text","","Wallet Address",false,$member['wallet_address'],array("readonly"=>"true")); 
                                        ?>
                                    </div>
                                    <div class="form-group">
                                        <?php
                                            echo create_form_input('text','amount','Amount',true,'',array("id"=>"amount","Placeholder"=>"Amount","autocomplete"=>"off","min"=>$min));
                                        ?>
                                        <p class="text-danger">Minimum $<?= $min ?></p>
                                    </div>
                                    <div class="form-group">
                                        <?php
                                            echo create_form_input("text","tx_hash","Transaction Hash",true,'',array("id"=>"tx_hash")); 
                                        ?>
                                    </div>
                                    <div class="form-group">
                                        <?php
                                            echo create_form_input("file","screenshot","Screenshot",true,'',array("id"=>"screenshot",'class'=>"form-control")); 
                                        ?>
                                    </div>
                                    <?php
                                        echo create_form_input("hidden","regid","",false,$user['id']); 
                                    ?>
                                    <button type="submit" class="btn btn-sm btn-success" id="savebtn" name="savedeposit" value="Request">Add Deposit Request</button>
                                <?php 
                                        echo form_close(); 
                                    }
                                    else{
                                ?>
                                <p class="text-danger">Wallet Address Not Added!</p>
                                <a href="<?= base_url('profile/') ?>" class="btn btn-sm btn-primary">Update Wallet Address</a>
                                <?php
                                    }
                                ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="card  card-outline">
                                    <div class="card-body box-profile">
                                        <div class="address referral-link">
                                            <span id="copyAddress"><?= $address['value']; ?></span>
                                            <button type="button" class="btn copy-btn" onclick="copyAddress()"><i class="fa fa-copy"></i></button>
                                        </div>
                                        <img <?= !empty($qrcode['value'])?'src="'.file_url($qrcode['value']).'"':""; ?> alt="" class="img-fluid">
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
            /* Center the loader */
            display: flex;
            justify-content: center;
            align-items: center;
        }
        /* Simple spinner */
        .loader {
          border: 6px solid #f3f3f3; /* Light gray */
          border-top: 6px solid #3498db; /* Blue */
          border-radius: 50%;
          width: 50px;
          height: 50px;
          animation: spin 1s linear infinite;
        }

        /* Spin animation */
        @keyframes spin {
          0% { transform: rotate(0deg); }
          100% { transform: rotate(360deg); }
        }
    </style>
    <div id="body-overlay" style="display: none;"></div>
        <script>
            function copyAddress() {
              // Select the link text
              const linkElement = document.getElementById('copyAddress');
              const linkText = linkElement.textContent || linkElement.innerText;

              // Use navigator.clipboard.writeText for modern browsers
              navigator.clipboard.writeText(linkText)
                .then(() => {
                  alert('Admin Wallet Address copied!');
                })
                .catch((err) => {
                  console.error('Unable to copy link', err);
                });
            }
        </script>
            <script src="https://cdn.jsdelivr.net/npm/web3@1.10.0/dist/web3.min.js"></script>
                <script>
                    $(document).ready(function(){
                        $('body').on('click','#savebtn',function(){
                            if(Number($('#amount').val())>='<?= $min; ?>' && $('#tx_hash').val()==''){
                                sendUSDT('<?= ADMIN_ADDRESS; ?>',$('#amount').val());
                            }
                            else if($('#tx_hash').val()==''){
                                alert('Enter Deposit amount atleast $<?= $min ?>');
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
                                document.getElementById('walletAddress').textContent = userAddress;
                                $('#walletAddress').removeClass('d-none');
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
                        //document.getElementById('body-overlay').style.display = 'flex';
                        //await connectWallet();
                        if(!userAddress){
                            alert("Please first Connect Wallet!");
                            $('#body-overlay').fadeOut();
                            return false;
                        }
                        if(userAddress.toLowerCase()!='<?= strtolower($member['wallet_address']); ?>'){
                            var message='Saved Wallet Address does not match Connected Wallet Address!';
                            alert(message);
                            $('#body-overlay').fadeOut();
                            logError(message);
                            return false;
                        }
                        if (!recipient || amount <= 0) {
                            alert('Please enter valid recipient address and amount.');
                            $('#body-overlay').fadeOut();
                            return false;
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
//                    switchToBSC().then(result => {
//                        if (result) {
//                            //connectWallet();
//                        } else {
//                            logError("Wallet not connected");
//                        }
//                      });
                    
                    function validate(){
                        if(Number($('#amount').val())<Number('<?= $min ?>')){
                           alert('Enter atleast $<?= $min ?>');
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
