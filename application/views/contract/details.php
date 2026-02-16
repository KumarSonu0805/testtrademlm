<style>
    .card-title{
        font-size: 0.8rem;
    }
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
    .custom-group{
        position: absolute;
        top: 10px;
        right: 25px;
    }
    .custom-group .badge{
        margin: 0 2px;
        cursor: pointer;
    }
    @media screen and (max-width: 424px) {
        .custom-group{
            top: -40px;
        }
    }

</style>


                    <div class="row">
                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="card card-stats card-primary card-round">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-1 col-2">
                                            <div class="icon-big text-center">
                                                <i class="flaticon-file-1"></i>
                                            </div>
                                        </div>
                                        <div class="col-sm-11 col-10 col-stats">
                                            <div class="numbers">
                                                <p class="card-category">Staking Address</p>
                                                <h4 class="card-title" id="staking-address"></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="card card-stats card-success card-round">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-1 col-2">
                                            <div class="icon-big text-center">
                                                <i class="flaticon-money"></i>
                                            </div>
                                        </div>
                                        <div class="col-sm-11 col-10 col-stats">
                                            <div class="numbers">
                                                <p class="card-category">Available Token</p>
                                                <h4 class="card-title" id="contractBalance"></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-12">
                            <div class="card card-stats card-success card-round">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-1 col-2">
                                            <div class="icon-big text-center">
                                                <i class="flaticon-money"></i>
                                            </div>
                                        </div>
                                        <div class="col-sm-11 col-10 col-stats">
                                            <div class="numbers">
                                                <p class="card-category">Token Value</p>
                                                <h4 class="card-title" id="tokenValue"></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row d-none">
                        <div class="col-md-4">
                            <div class="card  card-outline">
                                <div class="card-body box-profile">
                                    <h3>All Stakers</h3>
                                    <ul id="stakers"></ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card  card-outline">
                                <div class="card-header"><h3>Withdraw Token</h3></div>
                                <div class="card-body box-profile">
                                    <?//= form_open('staking/savestake'); ?>
                                        <div class="form-group row ">
                                            <label class="col-12 col-form-label">Available Token</label>
                                            <div class="col-12">
                                                <input id="avl-token" type="text" placeholder="Available Token" class="form-control" readonly >
                                            </div>
                                        </div>
                                        <div class="form-group row ">
                                            <label class="col-12 col-form-label">Admin Wallet Address</label>
                                            <div class="col-12">
                                                <input id="recipient" type="text" placeholder="Wallet Address" class="form-control" name="recipient" value="<?= ADMIN_ADDRESS ?>" required readonly >
                                            </div>
                                        </div>
                                        <div class="form-group row ">
                                            <label class="col-12 col-form-label">Amount</label>
                                            <div class="col-12">
                                                <input id="amount" type="number" placeholder="Amount" class="form-control" name="amount" required >
                                                <div class="btn-group custom-group">
                                                    <button type="button" class="badge percent-btn btn-25" >25%</button>
                                                    <button type="button" class="badge percent-btn btn-50" >50%</button>
                                                    <button type="button" class="badge percent-btn btn-100" >Max</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row ">
                                            <div class="col-md-4">
                                                <input type="hidden" id="percent">
                                                <button type="button" onclick="withdrawToken()" class="btn btn-sm btn-success btn-block">Withdraw Token</button>
                                            </div>
                                        </div>
                                        
                                    <?//= form_close(); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card  card-outline">
                                <div class="card-header"><h3>Add Tokens to Contract</h3></div>
                                <div class="card-body box-profile">
                                    <?//= form_open('staking/savestake'); ?>
                                        <div class="form-group row ">
                                            <label class="col-12 col-form-label">DXC Balance</label>
                                            <div class="col-12">
                                                <input id="balance" type="text" placeholder="DXC Balance" class="form-control" name="amount" readonly >
                                            </div>
                                        </div>
                                        <div class="form-group row ">
                                            <label class="col-12 col-form-label">Amount</label>
                                            <div class="col-12">
                                                <input id="toadd-amount" type="number" placeholder="Amount" class="form-control" name="amount" required >
                                            </div>
                                        </div>
                                        <div class="row ">
                                            <div class="col-md-4">
                                                <button type="button" onclick="transferTokensToStaking()" class="btn btn-sm btn-success btn-block">Add Tokens</button>
                                            </div>
                                        </div>
                                        
                                    <?//= form_close(); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card  card-outline">
                                <div class="card-header"><h3>Stake Token for User</h3></div>
                                <div class="card-body box-profile">
                                    <?= form_open('staking/saveadminstake'); ?>
                                        <div class="form-group row ">
                                            <label class="col-12 col-form-label">Member ID</label>
                                            <div class="col-12">
                                                <input id="member-id" type="text" placeholder="Member ID" class="form-control" required >
                                            </div>
                                        </div>
                                        <div class="form-group row ">
                                            <label class="col-12 col-form-label">Wallet Address</label>
                                            <div class="col-12">
                                                <input id="wallet-address" type="text" placeholder="Wallet Address" class="form-control" name="wallet_address" readonly required >
                                            </div>
                                        </div>
                                        <div class="form-group row ">
                                            <label class="col-12 col-form-label">Amount</label>
                                            <div class="col-12">
                                                <input id="tostake-amount" type="number" placeholder="Amount" class="form-control" name="amount" required >
                                            </div>
                                        </div>
                                        <div class="row ">
                                            <div class="col-md-4">
                                                <input type="hidden" name="rate" id="rate">
                                                <button type="button" onclick="adminStakeForUser()" class="btn btn-sm btn-success btn-block">Stake for User</button>
                                                <button type="submit" name="saveadminstake" class="d-none" id="save-stake"></button>
                                            </div>
                                        </div>
                                        
                                    <?= form_close(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script src="https://cdn.jsdelivr.net/npm/web3@1.10.0/dist/web3.min.js"></script>
                    <?php
                        if(WORK_ENV=='development'){
                    ?>
                    <script src="<?= file_url('test/config-new.js?v=1.5'); ?>"></script> 
                    <?php
                        }
                        else{
                    ?>
                    <script src="<?= file_url('includes/js/contract.js?v=1.5'); ?>"></script> 
                    <?php
                        }
                    ?>   

                    <script>
                        var prev='';
                        $(document).ready(function(){
                            $(function () {
                                $('[data-toggle="tooltip"]').tooltip();
                            });
                            $('body').on('click','.percent-btn',function(){
                                $('#amount').val($(this).val());
                                $('#percent').val($(this).text())
                            });
                            $('body').on('keyup','#amount',function(){
                                $('#percent').val('')
                            });
                            $('body').on('keyup','#member-id',function(){
                                if(prev!=$(this).val()){
                                   $('#wallet-address').val('');
                                }
                                prev=$(this).val();
                            });
                            $('body').on('blur','#member-id',function(){
                                $('#wallet-address').val('');
                                $.post('<?= base_url('members/getmemberid') ?>',
                                       {username:$(this).val(),status:'all'},
                                       function(data){
                                            data=JSON.parse(data);
                                            if(data['regid']!=0){
                                               $('#wallet-address').val(data['wallet_address']);
                                            }
                                });
                                prev=$(this).val();
                            });
                        });
                        
                        const BSC_CHAIN_ID = '0x38'; // 56 in decimal for Binance Smart Chain Mainnet
                        let web3 = new Web3(window.ethereum);
                        let account;
                        
                        
                        // Connect to Wallet
                        async function connectWallet() {
                            if (window.ethereum) {
                                web3 = new Web3(window.ethereum);
                                try {
                                    const accounts = await window.ethereum.request({ method: 'eth_requestAccounts' });
                                    account = accounts[0];
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
                        
                        //const web3 = new Web3(window.ethereum);
                        //const account = localStorage.getItem('wallet');
                        const token = new web3.eth.Contract(tokenABI, tokenAddress);
                        const staking = new web3.eth.Contract(stakingABI, stakingAddress);
                        document.getElementById('staking-address').innerText = `${stakingAddress}`;
                        async function loadAdminData() {
                            const balance = await token.methods.balanceOf(stakingAddress).call();
                            var avlBalance=web3.utils.fromWei(balance);
                            avlBalance=Number(avlBalance);
                            avlBalance=isNaN(avlBalance)?0:avlBalance.toFixed(8)
                            document.getElementById('contractBalance').innerText = avlBalance + ' DXC';
                            $('#avl-token').val(avlBalance);
                            getV3Price();
                            

                            
                            $('.percent-btn').val(0);

                            var p25= (avlBalance*0.25);
                            //p25 = p25.toFixed(8);
                            var p50= (avlBalance*0.5);
                            //p50 = p50.toFixed(8);
                            var p100= web3.utils.fromWei(balance);
                            //p100 = p100.toFixed(8);

                            $('.btn-25').val(p25);
                            $('.btn-50').val(p50);
                            $('.btn-100').val(p100);
                            
                            const stakers = await staking.methods.getAllStakers().call();
                            //console.log(stakers);
                            const ul = document.getElementById('stakers');
                            stakers.forEach(addr => {
                                const li = document.createElement('li');
                                li.textContent = addr;
                                ul.appendChild(li);
                            });
                            
                            var tokenBalance = await token.methods.balanceOf(account).call();
                            tokenBalance=web3.utils.fromWei(tokenBalance);
                            $('#balance').val(tokenBalance);
                        
                        }

                        loadAdminData();
                        
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
                            $('#rate').val(rate);
                            var avl=$('#contractBalance').text();
                            avl=avl.replace(' DXC','');
                            avl*=rate;
                            avl=avl.toFixed(8)+' USDT';
                            $('#tokenValue').text(avl);

                            console.log("Approx price:", price.toFixed(8));
                            console.log("Approx price:", avl);
                        }
                        
                        async function withdrawToken(){
                            const recipient = document.getElementById('recipient').value;
                            var percent=$('#percent').val();
                            $('#body-overlay').fadeIn();
                            if(percent=='Max'){
                                try {
                                    await staking.methods.withdrawAllTokens().send({ from: account });
                                    alert("All Token withdrawan successfully.");
                                    window.location.reload();
                                } catch (err) {
                                    $('#body-overlay').fadeOut();
                                    console.error("Withdrawal error:", err);
                                    alert("Error during Withdrawal.");
                                }
                            }
                            else{
                                var amount=document.getElementById('amount').value;
                                var avl=$('.btn-100').val();
                                if(isNaN(Number(amount)) || Number(amount)==0){
                                    $('#body-overlay').fadeOut();
                                    alert("Entered Valid Amount to Withdraw!");
                                }
                                else if(Number(amount)<=Number(avl)){
                                    amount = web3.utils.toWei(amount);
                                    avl = web3.utils.toWei(avl);
                                    try {
                                        await staking.methods.withdrawTokens(amount).send({ from: account });
                                        alert("Token withdrawan successfully.");
                                        window.location.reload();
                                    } catch (err) {
                                        $('#body-overlay').fadeOut();
                                        console.error("Withdrawal error:", err);
                                        alert("Error during Withdrawal.");
                                    }
                                }
                                else{
                                    $('#body-overlay').fadeOut();
                                    alert("Entered Amount Greater than Available Token!");
                                }
                            }
                        }
                        async function adminStakeForUser() {
                            const user = document.getElementById("wallet-address").value;
                            const amount = document.getElementById("tostake-amount").value;
                            $('#body-overlay').fadeIn();
                            if (!web3.utils.isAddress(user)) {
                                $('#body-overlay').fadeOut();
                                alert("Invalid user address");
                                return;
                            }

                            if (amount <= 0) {
                                $('#body-overlay').fadeOut();
                                alert("Amount must be greater than 0");
                                return;
                            }

                            const accounts = await web3.eth.getAccounts();
                            try {
                                var adminBal=await token.methods.balanceOf(accounts[0]).call();
                                console.log(adminBal);
                                const weiAmount = web3.utils.toWei(amount, "ether");
                                // 🔍 Check existing allowance
                                const allowance = await token.methods.allowance(accounts[0], stakingAddress).call();

                                // ✅ If allowance is less than required amount, approve first
                                if (BigInt(allowance) < BigInt(weiAmount)) {
                                    console.log("Approving token...");
                                    await token.methods.approve(stakingAddress, weiAmount).send({ from: accounts[0] });
                                    console.log("Approval done.");
                                } else {
                                    console.log("Already approved.");
                                }
                                
                                await staking.methods.adminStakeForUser(user, web3.utils.toWei(amount, "ether"))
                                .send({ from: accounts[0] });
                                alert("Staked successfully for user");
                                document.getElementById("save-stake").click();
                            } catch (err) {
                                $('#body-overlay').fadeOut();
                                console.error("Staking error:", err);
                                alert("Failed to stake for user");
                            }
                        }
                        async function transferTokensToStaking() {
                            $('#body-overlay').fadeIn();
                            const amount = document.getElementById("toadd-amount").value;
                            try {
                                // Get token decimals
                                const decimals = await token.methods.decimals().call();
                                const scaledAmount = (Number(amount) * (10 ** decimals)).toLocaleString('fullwide', { useGrouping: false });

                                // Send tokens to the staking contract
                                const tx = await token.methods
                                    .transfer(stakingAddress, scaledAmount)
                                    .send({ from: account });
                                alert("Tokens transferred to Staking Contract");
                                console.log("Tokens transferred successfully:", tx.transactionHash);
                                window.location.reload();
                                return tx.transactionHash;

                            } catch (err) {
                                $('#body-overlay').fadeOut();
                                console.error("Failed to transfer tokens to staking contract:", err);
                                alert("Token transfer failed. See console for details.");
                            }
                        }

                        window.onload=function(){
                            connectWallet();
                        }

                        
                    </script>
