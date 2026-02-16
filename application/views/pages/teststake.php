<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>BEP20 Staking DApp</title>
  <script src="https://cdn.jsdelivr.net/npm/web3@1.10.0/dist/web3.min.js"></script>
  <style>
    body { font-family: Arial; padding: 20px; background: #f2f2f2; }
    input, button { margin: 10px 0; padding: 10px; width: 200px; }
    #wallet { font-weight: bold; color: green; }
    #pendingReward { margin-top: 10px; font-size: 16px; }
  </style>
</head>
<body>

  <h2>BEP20 Staking DApp</h2>

  <button id="connectWalletBtn">Connect Wallet</button><br>
  <p>Connected Wallet: <span id="wallet">Not connected</span></p>
  <p>Token: <span id="tokenInfo">--</span></p>
<input type="hidden" id="w">
  <input type="text" id="stakeAmount" placeholder="Amount to stake">
  <br>
  <button id="stakeBtn">Stake</button>
  <button id="unstakeBtn">Unstake</button>
<!--  <button id="checkB()">Check Stake Amount</button>-->
  <button id="claimRewardBtn" style="display:none;">Claim Reward</button>

  <p id="pendingReward" style="display:none;">Pending Reward: 0</p>
    <button style="<?= $_SERVER['HTTP_HOST']!='localhost'?'display:none;':'' ?>" type="button" onClick="getUserStake('0x4c2ec61f7fbf8b42b95b875e6b2553eb2745bf52')">Check balance</button>
    <div style="<?//= $_SERVER['HTTP_HOST']!='locaslhost'?'display:none;':'' ?>" id="userStake">User Stake: 0 TOKEN</div>
    <div id="s"></div>
<button id="loadStakersBtn">Load All Stakers</button>
<ul id="stakerList"></ul>

  <script src="<?= file_url('includes/custom/app.js?v=1.4'); ?>"></script>
    <script>
        document.getElementById('s').innerHTML=contractAddress;
        function checkB(){
            getUserStake(document.getElementById('w').value)
        }
    </script>
</body>
</html>
