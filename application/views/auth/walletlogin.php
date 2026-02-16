<style>

    .pop-up {
        background-color: #e4efd9;
        padding: 45px 25px;
        border-radius: 10px;
        position: absolute;
        z-index: 2;
        color: #6A6A6A;
    }
    .pop-up i{
        color: #ddb82f;
    }
    body::before{
        z-index: -1;
    }
    body.pop::before{
        content: "";
        background-color: #00000044;
        position: absolute;
        height: 100%;
        z-index: 1;
        width: 100%;
    }
</style>
            <div class="login-box">
                <div class="container">
                    <!-- /.login-logo -->
                    <div class="card card-outline">
                        <div class="card-header text-center">
                        <a href="<?= base_url(); ?>" class="h1"><img src="<?= file_url(LOGO) ?>" alt=" Logo" class="img-fluid"></a>
                        </div>
                        <div class="card-body">
                            <p class="login-box-msg">Sign in to start your session</p>
                            <?= form_open('login/validatewallet'); ?>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" id="wallet_address" name="wallet_address" placeholder="Wallet Address" <?= (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST']=='localhost')?'':'readonly' ?> required>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-danger text-center mb-2"><?= $this->session->flashdata('logerr'); ?></div>
                                <div class="row">
                                    <!-- /.col -->
                                    <div class="col-md-4">
                                        <button type="submit" name="login" class="btn btn-oflep btn-block">Sign In</button>
                                    </div>
                                    <!-- /.col -->
                                </div>
                            <?= form_close(); ?>

                            <p class="mb-1">
                                <a href="<?= base_url('register/'); ?>">New Member? Register Now</a>
                            </p>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                 </div>
            </div>
            <!-- /.login-box -->

            <?php
                if($this->session->flashdata('msg')=='Registered Successfully!'){
            ?>
            <div class="pop-up text-center">
                <p class="text-center"><i class="fa fa-check-circle fa-4x"></i></p>
                <h4>Registered Successfully!</h4>
                <h3>Your ID is <?= $user['username']; ?></h3>
                <button type="button" class="btn btn-success mt-2" onClick="$('.pop-up').hide();$('body').removeClass('pop');">Login Now</button>
            </div>
            <script>
                $(document).ready(function(){
                    //$('body').addClass('pop');
                });
            </script>
            <?php
                }
            ?>
            <script src="https://cdn.jsdelivr.net/gh/ethereum/web3.js/dist/web3.min.js"></script>
            <script>
                        // Import Web3.js
                if (typeof window.ethereum !== 'undefined') {
                    const web3 = new Web3(window.ethereum);
                } else {
                    alert('A Web3 wallet (like MetaMask or Trust Wallet or Token Pocket) is required. Please install or open it.');
                }

                const BEP20_CHAIN_ID = '0x38'; // Mainnet Chain ID for Binance Smart Chain
                const BEP20_RPC_URL = 'https://bsc-dataseed.binance.org/'; // RPC URL for Binance Smart Chain Mainnet
                const BEP20_CHAIN_NAME = 'BNB Smart Chain Mainnet';
                const BEP20_SYMBOL = 'BNB';
                const BEP20_DECIMALS = 18;
                const BEP20_BLOCK_EXPLORER_URL = 'https://bscscan.com';

                async function connectToWallet() {
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
                        localStorage.setItem('wallet', walletAddress);
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

                // Add a click event to connect button (if any)
                //document.getElementById('connect-wallet').addEventListener('click', connectToWallet);
                window.onload=function(){
                    connectToWallet();
                }
            </script>