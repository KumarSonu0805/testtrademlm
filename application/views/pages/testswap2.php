<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Custom Token Swap</title>
  <script src="https://cdn.jsdelivr.net/npm/web3@1.10.0/dist/web3.min.js"></script>
  <style>
    body { font-family: Arial; padding: 20px; }
    input, select, button { margin: 5px 0; padding: 10px; width: 100%; }
  </style>
</head>
<body>
  <h2>Swap USDT → Your Token</h2>
  
  <button onclick="connectWallet()">Connect Wallet</button>
  <p id="wallet-address"></p>

  <label>Amount (USDT):</label>
  <input type="number" id="amount" placeholder="Enter USDT amount" />

  <label>Slippage Tolerance (%):</label>
  <input type="number" id="slippage" value="10" />

  <button onclick="swap()">Swap Now</button>

  <p id="status"></p>

  <script>
    const web3 = new Web3(window.ethereum);
    let userAccount;

    const usdtAddress = "0x55d398326f99059fF775485246999027B3197955"; // BEP20 USDT
    const yourTokenAddress = "0x45e882831C9F815d013fF18FC20501366ff1a9df"; // Replace with your token
    const pancakeRouterAddress = "0x10ED43C718714eb63d5aA57B78B54704E256024E"; // PancakeSwap v2

    const erc20ABI = [
      // Minimal ABI for balanceOf, approve
      { constant: true, inputs: [{name:"_owner",type:"address"}], name: "balanceOf", outputs: [{name:"balance",type:"uint256"}], type: "function" },
      { constant: false, inputs: [{name:"_spender",type:"address"},{name:"_value",type:"uint256"}], name: "approve", outputs: [{name:"success",type:"bool"}], type: "function" },
      { constant: "function", name: "decimals", outputs: [{name: "", type: "uint8"}], inputs: [], type: "function" }
    ];

    const routerABI = [
      {
        "name": "swapExactTokensForTokensSupportingFeeOnTransferTokens",
        "type": "function",
        "inputs": [
          {"name": "amountIn", "type": "uint256"},
          {"name": "amountOutMin", "type": "uint256"},
          {"name": "path", "type": "address[]"},
          {"name": "to", "type": "address"},
          {"name": "deadline", "type": "uint256"}
        ],
        "outputs": [],
        "stateMutability": "nonpayable"
      }
    ];

    async function connectWallet() {
      if (window.ethereum) {
        const accounts = await ethereum.request({ method: 'eth_requestAccounts' });
        userAccount = accounts[0];
        document.getElementById('wallet-address').innerText = "Connected: " + userAccount;
      } else {
        alert("MetaMask not found!");
      }
    }

    async function swap() {
      const amount = parseFloat(document.getElementById('amount').value);
      const slippage = parseFloat(document.getElementById('slippage').value);

      if (!amount || amount <= 0) return alert("Enter a valid amount");

      const usdt = new web3.eth.Contract(erc20ABI, usdtAddress);
      const token = new web3.eth.Contract(erc20ABI, yourTokenAddress);
      const router = new web3.eth.Contract(routerABI, pancakeRouterAddress);

      const usdtDecimals = await usdt.methods.decimals().call();
      const amountIn = web3.utils.toBN(amount * (10 ** usdtDecimals));
      const deadline = Math.floor(Date.now() / 1000) + 60 * 10;

      // Approve Router
      document.getElementById("status").innerText = "Approving Router...";
      await usdt.methods.approve(pancakeRouterAddress, amountIn).send({ from: userAccount });

      // Estimate amountOutMin (use placeholder: here we use 0 for simplicity)
      const amountOutMin = 0; // In production, calculate based on expected rate & slippage

      // Swap
      const path = [usdtAddress, yourTokenAddress];
      document.getElementById("status").innerText = "Swapping tokens...";
      await router.methods.swapExactTokensForTokensSupportingFeeOnTransferTokens(
        amountIn,
        amountOutMin,
        path,
        userAccount,
        deadline
      ).send({ from: userAccount });

      document.getElementById("status").innerText = "Swap complete!";
    }
  </script>
</body>
</html>
