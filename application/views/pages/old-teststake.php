<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>BEP20 Staking DApp</title>
</head>
<body>
  <h1>Connect Wallet</h1>
  <button id="connectBtn">Connect Wallet</button>
  <p id="walletAddress">Not connected</p>

    <input type="text" id="tokenAddress" placeholder="BEP20 Token Address" size="50" />
    <button id="loadToken">Load Token</button>
    <p id="tokenInfo">Token info will appear here...</p>

    <input type="number" id="stakeAmount" placeholder="Amount to stake" />
    <button id="approveToken">Approve Token</button>

    <button id="stakeToken">Stake</button>
    <button id="unstakeToken">Unstake</button>
    <p id="stakedAmount">Staked: 0</p>

    
  <script src="https://cdn.jsdelivr.net/gh/ethereum/web3.js/dist/web3.min.js"></script>
  <script src="<?= file_url('includes/custom/erc20Abi.js'); ?>"></script>
  <script src="<?= file_url('includes/custom/main.js'); ?>"></script>
</body>
</html>
