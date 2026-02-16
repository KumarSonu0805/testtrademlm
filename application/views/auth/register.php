<?php
$sponsor='';
if(!empty($this->input->get('sponsor'))){
    $sponsor=$this->input->get('sponsor');
}
?>

            <div class="login-box">
                <div class="container">
                    <!-- /.login-logo -->
                    <div class="card card-outline">
                        <div class="card-header text-center">
                        <a href="<?= base_url(); ?>" class="h1"><img src="<?= file_url(LOGO) ?>" alt=" Logo" class="img-fluid"></a>
                        </div>
                        <div class="card-body">
                            <p class="login-box-msg">Create New Account</p>
                            
                            <?= form_open('login/memberregistration','onSubmit="return validate();"'); ?>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="ref" id="ref" placeholder="SPONSOR" value="<?= $sponsor ?>" required>
                                    <input type="hidden" name="refid" id="refid">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user-plus" style="font-size: 12px;"></span>
                                        </div>
                                    </div>
                                </div>
                                <div id="refdiv" class=" mb-3"></div>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" name="" id="refname" placeholder="SPONSOR NAME" readonly>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user-plus" style="font-size: 12px;"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" name="name" id="name" placeholder="Name" required>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="input-group mb-3 d-none">
                                    <input type="text" class="form-control" name="mobile" id="mobile" placeholder="Mobile" pattern="[\d]{10}" maxlength="10" title="Enter valid 10-Digit Mobile No.">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-mobile"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" name="wallet_address" id="wallet_address" readonly placeholder="Wallet Address">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-wallet"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-danger text-center mb-2"><?= $this->session->flashdata('reg_err_msg'); ?></div>
                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" id="savebtn" name="register" class="btn btn-primary btn-block">Connect Wallet &amp; Register</button>
                                    </div>
                                    <!-- /.col -->
                                </div>
                            <?= form_close(); ?>

                            <a href="<?= base_url('login/'); ?>" class="text-center">Already a Member? Login</a>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                 </div>
            </div>
            <!-- /.login-box -->
            <script>
                $(document).ready(function(){ 
                    $('#ref').keyup(function(){
                        getrefid();
                    }); 
                    $('#ref').blur(function(){
                        getrefid();
                    });

                    if($('#ref').val()!=''){
                        $('#ref').trigger('keyup');
                    }
                });
                function getrefid(){

                    var username=$('#ref').val();
                    $('#refid,#refname').val('');
                    $('#refdiv').removeClass('text-danger').removeClass('text-success').html('');
                    $('#savebtn').attr("disabled",true);
                    $.ajax({
                        type:"POST",
                        url:"<?php echo base_url("members/getrefid/"); ?>",
                        data:{username:username,status:'all'},
                        beforeSend: function(data){
                            $('#refdiv').html($('#dot-loader').html());
                        },
                        success: function(data){
                            data=JSON.parse(data);
                            if(data['regid']=='' || data['regid']==0){
                                $('#refdiv').html(data['name']).addClass('text-danger');
                            }else{
                                $('#refid').val(data['regid']);
                                $('#refname').val(data['name']);
                                $('#refdiv').html('').addClass('text-success');
                                $('#savebtn').removeAttr("disabled");
                            }

                        }
                    });
                }

                function setChosenSelect(ele){
                    ele.chosen({
                        disable_search_threshold: 10,
                        no_results_text: "Oops, nothing found!",
                        width: "100%"
                    });
                }

                function validate(){
                    if($('#password').val()!=$('#repassword').val()){
                        alert('Passwords Do Not Match!');
                       return false;
                    }
                    $('#savebtn').hide();
                }
            </script>
    <script src="https://cdn.jsdelivr.net/gh/ethereum/web3.js/dist/web3.min.js"></script>
    <script>
                // Import Web3.js
        if (typeof window.ethereum !== 'undefined') {
            const web3 = new Web3(window.ethereum);
        } else {
            alert('A Web3 wallet (like MetaMask or Trust Wallet) is required. Please install or open it.');
        }

        const BEP20_CHAIN_ID = '0x38'; // Mainnet Chain ID for Binance Smart Chain
        const BEP20_RPC_URL = 'https://bsc-dataseed.binance.org/'; // RPC URL for Binance Smart Chain Mainnet
        const BEP20_CHAIN_NAME = 'BNB Smart Chain Mainnet';
        const BEP20_SYMBOL = 'BNB';
        const BEP20_DECIMALS = 18;
        const BEP20_BLOCK_EXPLORER_URL = 'https://bscscan.com';

        async function connectToWallet() {
            if (typeof window.ethereum !== 'undefined') {
                const web3 = new Web3(window.ethereum);
            } else {
                alert('A Web3 wallet (like MetaMask or Trust Wallet) is required. Please install or open it.');
            }

            try {
                // Request accounts from MetaMask
                const accounts = await window.ethereum.request({ method: 'eth_requestAccounts' });

                // Check the current chain
                const currentChainId = await window.ethereum.request({ method: 'eth_chainId' });

                if (currentChainId !== BEP20_CHAIN_ID) {
                    // Prompt user to switch chain
                    try {
                        await window.ethereum.request({
                            method: 'wallet_switchEthereumChain',
                            params: [{ chainId: BEP20_CHAIN_ID }],
                        });
                    } catch (switchError) {
                        // If the chain has not been added to MetaMask, add it
                        if (switchError.code === 4902) {
                            try {
                                await window.ethereum.request({
                                    method: 'wallet_addEthereumChain',
                                    params: [
                                        {
                                            chainId: BEP20_CHAIN_ID,
                                            chainName: BEP20_CHAIN_NAME,
                                            rpcUrls: [BEP20_RPC_URL],
                                            nativeCurrency: {
                                                name: BEP20_SYMBOL,
                                                symbol: BEP20_SYMBOL,
                                                decimals: BEP20_DECIMALS,
                                            },
                                            blockExplorerUrls: [BEP20_BLOCK_EXPLORER_URL],
                                        },
                                    ],
                                });
                            } catch (addError) {
                                displayError('Unable to add Binance Smart Chain to your wallet. Please try again.');
                                //resetButton();
                                return;
                            }
                        } else {
                            displayError('Switching to Binance Smart Chain failed. Please check your wallet settings.');
                            //resetButton();
                            return;
                        }
                    }
                }

                // Successfully connected to wallet and switched chain
                const walletAddress = accounts[0];
                console.log('Wallet Address:', walletAddress);
                //alert(`Connected Wallet: ${walletAddress}`);
                $('#wallet_address').val(walletAddress);
                $('#savebtn').trigger('click');
            } catch (error) {
                if (error.code === 4001) {
                    displayError('Connection request was rejected. Please try again.');
                } else if (error.code === -32002) {
                    displayError('A connection request is already pending. Please check your wallet.');
                } else {
                    displayError('An error occurred while connecting to your wallet. Please try again.');
                }
            } finally {
                resetButton();
            }
        }
        
        function displayError(message) {
            console.error(message);
            alert(message);
        }

        function validate(){
            if($('#wallet_address').val()==''){
                connectToWallet();
                return false;
            }
        }

    </script>