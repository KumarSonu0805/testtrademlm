<?php
$sponsor='';
if(!empty($this->input->get('sponsor'))){
    $sponsor=$this->input->get('sponsor');
}
?>
      <section class="loginsection">
         <div class="container">
            <?= form_open('login/memberregistration','onSubmit="return validate();"'); ?>
            <div class="login-card">
               <div class="login-logo">
                  <img src="<?= file_url(LOGO) ?>" alt=" Logo" width="200" />
                  <p>Sign up your account</p>
               </div>
               <div class="mb-3">
                  <label for="memberid" class="form-label">Sponsor Id</label>
                  <input
                     type="text"
                     class="form-control"
                     id="ref"
                     placeholder="Enter Your Sponsor Id"
                         value="<?= $sponsor ?>" required
                     />
                                    <input type="hidden" name="refid" id="refid">
               </div>
                <div id="refdiv" class=" mb-3"></div>
               <div class="mb-3">
                  <label for="name" class="form-label">Sponsor Name</label>
                  <input type="text" class="form-control" name="" id="refname" placeholder="SPONSOR NAME" readonly/>
               </div>
               <div class="mb-3">
                  <label for="name" class="form-label">Name</label>
                  <input type="text" class="form-control" name="name" id="name" placeholder="Name" required/>
               </div>
               <div class="mb-3">
                  <label for="name" class="form-label">Mobile</label>
                  <input type="text" class="form-control" name="mobile" id="mobile" placeholder="Mobile" pattern="[\d]{10}" maxlength="10" title="Enter valid 10-Digit Mobile No.">
               </div>
               <div class="mb-3">
                  <label for="name" class="form-label">Wallet Address</label>
                  <input type="text" class="form-control" name="wallet_address" id="wallet_address" readonly placeholder="Wallet Address">
               </div>
                <div class="text-danger text-center mb-2"><?= $this->session->flashdata('reg_err_msg'); ?>
                <div class="mb-3">
                  <div class="futureBtn">
                     <button type="submit" id="savebtn" name="register">Create New Account</button>
                  </div>
               </div>
               <div class="mb-3 createpassword">
                  <div class="row">
                     <div class="col-lg-6">
                        <a href="<?= base_url('login/'); ?>">Sign Me In</a>
                     </div>
                     <div class="col-lg-6">
                     </div>
                  </div>
               </div>
            </div>
            <?= form_close(); ?>
         </div>
      </section>

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

            </script>
    <script src="https://cdn.jsdelivr.net/gh/ethereum/web3.js/dist/web3.min.js"></script>
    <script src="<?= file_url('includes/custom/switch.js'); ?>"></script>
    <script>
        switchToBSC();

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
                //resetButton();
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