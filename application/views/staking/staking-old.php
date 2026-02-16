<script src="https://cdn.jsdelivr.net/npm/web3@1.10.0/dist/web3.min.js"></script>

  <style>
    input, button { margin: 10px 0; padding: 10px; width: 200px; }
    #wallet { font-weight: bold; color: green; }
    #pendingReward { margin-top: 10px; font-size: 16px; }
  </style>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card  card-outline">
                                <div class="card-body box-profile">
                                    <?= form_open('staking/savestake'); ?>
                                    <button id="connectWalletBtn" class="d-none">Connect Wallet</button><br>
                                    <p class="d-none">Connected Wallet: <span id="wallet-address">Not connected</span></p>
                                    <p><span id="tokenInfo">DXC Balance : --</span></p>
                                    <input type="hidden" id="w">
                                    <input type="text" id="stakeAmount" name="amount" class="form-control" placeholder="Amount to stake">
                                    <br>
                                    <div class="row">
                                        <div class="col-6">
                                            <button id="stakeBtn" class="btn btn-sm btn-success stake-btn" disabled>Stake</button>
                                            <button id="unstakeBtn" class="btn btn-sm btn-success stake-btn" disabled>Unstake</button>
                                        </div>
                                    </div>
                                      <button type="button" onClick="checkB()" class="btn btn-sm btn-success stake-btn" disabled>Check Stake Amount</button>
                                    <button id="claimRewardBtn" style="display:none;">Claim Reward</button>

                                    <p id="pendingReward" style="display:none;">Pending Reward: 0</p>
<!--                                    <button style="<?= $_SERVER['HTTP_HOST']!='localhost'?'display:none;':'' ?>" type="button" onClick="getUserStake('0x4c2ec61f7fbf8b42b95b875e6b2553eb2745bf52')">Check balance</button>-->
                                    <div id="userStake">User Stake: --</div>
                                    <div id="s"></div>
                                    <button type="submit" name="savestake" class="d-none" id="save-stake"></button>
                                    <button type="submit" name="saveunstake" class="d-none" id="save-unstake"></button>
                                    <?=form_close(); ?>
                                </div>
                            </div>
                        </div>
                    </div>


  <script src="<?= file_url('includes/custom/app.js?v=3.5'); ?>"></script>
    <script>
        function checkB(){
            getUserStake(document.getElementById('w').value)
        }
    </script>
