<?php
$tokenrate=getTokenRate();
?>
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
    .custom-group{
        position: absolute;
        top: 10px;
        right: 25px;
    }
    .custom-group .badge{
        margin: 0 2px;
        cursor: pointer;
    }
    #pills-tab-without-border .nav-item{
        width: 50%;
        padding: 0 !important;
        margin: 0 !important;
        text-align: center;
    }
    #pills-tab-without-border .nav-item .nav-link{
        border-radius: 0 !important;
    }
    @media screen and (max-width: 424px) {
        .list-group-item{
            padding-left: 0;
            padding-right: 0;
        }
        .custom-group{
            top: -40px;
        }
    }
</style>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 text-center" style="overflow: auto;">
                                            <div id="wallet-address" class="badge btn btn-success btn-block">Loading...</div>
                                        </div>
                                    </div>
									<ul class="nav nav-pills nav-primary nav-pills-no-bd d-none" id="pills-tab-without-border" role="tablist">
										<li class="nav-item">
											<a class="nav-link active" id="stake-tab" data-toggle="pill" href="#stake-tab-content" role="tab" aria-controls="stake-tab-content" aria-selected="true">Stake</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" id="pills-profile-tab-nobd" data-toggle="pill" href="#pills-profile-nobd" role="tab" aria-controls="pills-profile-nobd" aria-selected="false">UnStake</a>
										</li>
									</ul>
                                    <ul class="list-group list-group-unbordered">
                                        <li class="list-group-item">
                                            <b>Available Balance</b> <span class="float-right badge btn btn-info btn-border btn-round mr-2" id="available">0</span><i class="fa fa-info price-tooltip d-none" data-toggle="tooltip" data-placement="top" title="This is a tooltip!"></i>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Staked</b> <span id="staked" class="float-right badge btn btn-success btn-border btn-round mr-2">0</span><i class="fa fa-info price-tooltip d-none" data-toggle="tooltip" data-placement="top" title="This is a tooltip!"></i>
                                        </li>
                                        <li class="list-group-item d-none">
                                            <b>Total</b> <span id="total" class="float-right badge btn btn-success btn-border btn-round mr-2">0</span><i class="fa fa-info price-tooltip d-none" data-toggle="tooltip" data-placement="top" title="This is a tooltip!"></i>
                                        </li>
                                        <li class="list-group-item d-none">
                                            <b>Old Staked</b> <span id="old-staked" class="float-right badge btn btn-success btn-border btn-round mr-2"><?= $oldstaked ?></span><i class="fa fa-info price-tooltip d-none" data-toggle="tooltip" data-placement="top" title="This is a tooltip!"></i>
                                        </li>
                                    </ul>
									<div class="tab-content" id="pills-without-border-tabContent">
										<div class="tab-pane fade show active" id="stake-tab-content" role="tabpanel" aria-labelledby="stake-tab">
                                            <?= form_open('staking/savestake'); ?>
                                                <div class="form-group row staking d-none">
                                                    <label class="col-12 col-form-label">Amount</label>
                                                    <div class="col-12">
                                                        <input id="amount" type="text" placeholder="Amount" class="form-control" name="amount" required >
                                                        <div class="btn-group custom-group">
                                                            <button type="button" class="badge percent-btn btn-25" >25%</button>
                                                            <button type="button" class="badge percent-btn btn-50" >50%</button>
                                                            <button type="button" class="badge percent-btn btn-100" >Max</button>
                                                        </div>
                                                        <input id="unstakeamount" type="hidden" placeholder="Amount" class="form-control" name="unstakeamount" required >
                                                        <input type="hidden" id="staked-amount">
                                                        <input type="hidden" id="old-staked-amount">
                                                    </div>
                                                </div>
                                                <div class="row staking d-none">
                                                    <div class="col-md-4">
                                                        <button type="button" onclick="stake()" class="btn btn-sm btn-success btn-block my-1">Stake</button>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <button type="button" onclick="unstake()" class="btn btn-sm btn-success btn-block my-1" id="unstake-btn">Unstake</button>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <button type="button" onclick="unstake(true)" class="btn btn-sm btn-success btn-block my-1" id="old-unstake-btn">Unstake Old</button>
                                                        <input type="hidden" name="rate" id="rate">
                                                        <button type="submit" name="savestake" class="d-none" id="save-stake"></button>
                                                        <button type="submit" name="saveunstake" class="d-none" id="save-unstake"></button>
                                                    </div>
                                                </div>

                                            <?= form_close(); ?>
										</div>
										<div class="tab-pane fade" id="pills-profile-nobd" role="tabpanel" aria-labelledby="pills-profile-tab-nobd">
											<p>Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name of Lorem Ipsum decided to leave for the far World of Grammar.</p>
											<p>The Big Oxmox advised her not to do so, because there were thousands of bad Commas, wild Question Marks and devious Semikoli, but the Little Blind Text didn’t listen. She packed her seven versalia, put her initial into the belt and made herself on the way.
											</p>
										</div>
									</div>
								</div>
                            </div>
                        </div>
                        <div class="col-md-4 d-none" id="manual">
                            <div class="card  card-outline">
                                <div class="card-body box-profile">
                                    <?= form_open('staking/saveunstake','onSubmit="return validateUnstake()"'); ?>
                                        <div class="row">
                                            <div class="col-12 text-center">
                                                <h3 >Manual Unstaking</h3>
                                            </div>
                                        </div>
                                        <ul class="list-group list-group-unbordered mb-3">
                                            <li class="list-group-item">
                                                <b>Amount</b> <span id="old-staked2" class="float-right badge btn btn-success btn-border btn-round mr-2"><?= $oldstaked ?></span><i class="fa fa-info price-tooltip d-none" data-toggle="tooltip" data-placement="top" title="This is a tooltip!"></i>
                                            </li>
                                        </ul>
                                        <div class="row">
                                            <div class="col-12">
                                                <input type="hidden" id="old-staked-amount2" name="amount">
                                                <button type="submit" name="saveunstake" class="btn btn-sm btn-success btn-block">Save Unstake Request</button>
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
                    <script src="<?= file_url('test/config-new.js'); ?>"></script> 
                    <?php
                        }
                        else{
                    ?>
                    <script src="<?= file_url('includes/js/contract.js'); ?>"></script> 
                    <?php
                        }
                    ?>   
                    <script>
                        $(document).ready(function(){
                            $(function () {
                            $('[data-toggle="tooltip"]').tooltip();
                          });
                            $('#unstake-btn,#old-unstake-btn').hide();
                            $('body').on('click','.percent-btn',function(){
                                $('#amount').val($(this).val());
                            });
                        });
                    let web3 = new Web3(window.ethereum);
                    const account = localStorage.getItem('wallet');
                    document.getElementById('wallet-address').innerText = `Connected: ${account}`;

                    const token = new web3.eth.Contract(tokenABI, tokenAddress);
                    const staking = new web3.eth.Contract(stakingABI, stakingAddress);
                    async function loadBalances() {
                        const balance = await token.methods.balanceOf(account).call();
                        const stakeInfo = await staking.methods.getStaked(account).call(); // This returns a struct
                        
                        $('.percent-btn').val(0);
                        var avlBalance=web3.utils.fromWei(balance);
                        avlBalance=Number(avlBalance);
                        avlBalance=isNaN(avlBalance)?0:avlBalance;
                        
                        var p25= (avlBalance*0.25);
                        p25 = p25.toFixed(8);
                        var p50= (avlBalance*0.5);
                        p50 = p50.toFixed(8);
                        var p100= (avlBalance);
                        //p100 = p100.toFixed(8);
                        
                        $('.btn-25').val(p25);
                        $('.btn-50').val(p50);
                        $('.btn-100').val(p100);
                        
                        const stakedAmount = !stakeInfo.amount?stakeInfo[0]:stakeInfo.amount;
                        var stkdrwrd = Number(web3.utils.fromWei(stakedAmount, "ether"));
                        const reward = await staking.methods.pendingReward(account).call();
                        stkdrwrd += Number(web3.utils.fromWei(reward, "ether"));
                        var staked = web3.utils.fromWei(stakedAmount.toString());
                        var oldStaked = $('#old-staked').text();
                        //oldStaked -= staked;
                        $('#staked-amount').val(staked);
                        $('#unstakeamount').val(stkdrwrd);
                        $('#old-staked-amount,#old-staked-amount2').val(oldStaked);

                        
                        //console.log("Staked:", stakedAmount);
                        document.getElementById('available').innerText = web3.utils.fromWei(balance.toString())+' DXC';
                        document.getElementById('staked').innerText = stkdrwrd+' DXC';
                        if(oldStaked>0){
                            $('#old-staked').closest('li').removeClass('d-none');
                        }
                        document.getElementById('old-staked').innerText = oldStaked+' DXC';
                        $('.staking').removeClass('d-none');
                        //$('#total').text(stkdrwrd+' DXC');
                        
                        if(stkdrwrd>0 ){
                           $('#unstake-btn').show();
                        }
                        if( oldStaked>0){
                           $('#old-unstake-btn').show();
                        }
                        getV3Price();
                    }

                    async function stake() {
                        $('#amount').attr('required',true);
                        try {
                            $('#body-overlay').fadeIn();
                            var amt=document.getElementById('amount').value;
                            if(amt<=0){
                                alert("Enter Staking Amount > 0!");
                                $('#body-overlay').fadeOut();
                                return false;
                            }
                            var avl=$('#available').text();
                            avl=avl.replace(' DXC','');
                            /*console.log((amt));
                            console.log((avl));
                            console.log((Number(amt)>Number(avl)));*/
                            if(!isNaN(Number(amt)) && !isNaN(Number(avl)) && Number(amt)>Number(avl)){
                                alert("Staking Amount greater than Available Balance! Try with a lower amount or Add more DXC Tokens!");
                                $('#body-overlay').fadeOut();
                                return false;
                            }
                            const amount = web3.utils.toWei(amt);
                            console.log(amount);
                            const weiAmount = web3.utils.toWei(amt, "ether");
                            // 🔍 Check existing allowance
                            const allowance = await token.methods.allowance(account, stakingAddress).call();

                            // ✅ If allowance is less than required amount, approve first
                            if (BigInt(allowance) < BigInt(weiAmount)) {
                                console.log("Approving token...");
                                await token.methods.approve(stakingAddress, weiAmount).send({ from: account });
                                console.log("Approval done.");
                            } else {
                                console.log("Already approved.");
                            }
                            await staking.methods.stake(amount).send({ from: account });
                            alert("Staked successfully.");
                            document.getElementById("save-stake").click();
                        } catch (err) {
                            $('#body-overlay').fadeOut();
                            console.error("Staking error:", err);
                            alert("Error during staking.");
                        }
                        loadBalances();
                    }

                    async function unstake(old=false) {
                        if($('#staked-amount').val()>0 && !old){
                            $('#amount').removeAttr('required');
                            try {
                                $('#body-overlay').fadeIn();
                                await staking.methods.unstake().send({ from: account });
                                alert("Unstaked successfully.");
                                document.getElementById("save-unstake").click();
                            } catch (err) {
                                $('#body-overlay').fadeOut();
                                console.error("Unstaking error:", err);
                                alert("Error during unstaking.");
                            }
                            loadBalances();
                        }
                        else if($('#old-staked-amount').val()>0 || old){
                            alert("To Unstake Old Staked amount add Unstake request!");
                            $('#manual').removeClass('d-none');
                            $('#manual button').focus();
                        }
                        else{
                            alert("Token not Staked!");
                        }
                    }

                    loadBalances();
                 
// Example: Replace with your actual token + USDT pair address
//const pairAddress = '0xd793764dc7968715661c9682fff67edb6de1fdac';
//fetch("https://api.dexscreener.com/latest/dex/pairs/bsc/"+pairAddress)
//  .then(r => r.json())
//  .then(d => console.log(`Price in USD: ${d.pair.priceUsd}`));
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
                        $.post('<?= base_url('home/updatecoinrate') ?>',{rate:price},function(){});
                        var rate=price.toFixed(8);
                        $('#rate').val(rate);
                        var avl=$('#available').text();
                        avl=avl.replace(' DXC','');
                        avl*=rate;
                        avl=avl.toFixed(8)+'USDT';
                        $('#available').next().attr('title',avl);
                        $('#available').next().attr('data-original-title',avl);
                        $('#available').next().removeClass('d-none');

                        var stkd=$('#staked').text();
                        stkd=stkd.replace(' DXC','');
                        stkd*=rate;
                        stkd=stkd.toFixed(8)+'USDT';
                        $('#staked').next().attr('title',stkd);
                        $('#staked').next().attr('data-original-title',stkd);
                        $('#staked').next().removeClass('d-none');

                        var oldstkd=$('#old-staked').text();
                        oldstkd=oldstkd.replace(' DXC','');
                        oldstkd*=rate;
                        oldstkd=oldstkd.toFixed(8)+'USDT';
                        $('#old-staked').next().attr('title',oldstkd);
                        $('#old-staked').next().attr('data-original-title',oldstkd);
                        $('#old-staked').next().removeClass('d-none');
                        $('#old-staked2').next().attr('title',oldstkd);
                        $('#old-staked2').next().attr('data-original-title',oldstkd);
                        $('#old-staked2').next().removeClass('d-none');

                        console.log("Approx price:", price.toFixed(8));
                        console.log("Approx price:", avl);
                    }
                        function validateUnstake(){
                            if(!confirm("Confirm Unstaking of Old Staked Token?")){
                               return false;
                            }
                        }

                    </script>
