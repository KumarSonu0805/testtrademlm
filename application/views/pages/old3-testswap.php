<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Swap USDT to My Token</title>
  <script src="https://cdn.jsdelivr.net/npm/web3@1.10.0/dist/web3.min.js"></script>
</head>
<body>
  <h2>Swap USDT (BEP-20) → My Token</h2>

  <input type="text" id="amount" placeholder="Amount in USDT" />
  <br><br>
  <button onclick="connectWallet()">Connect Wallet</button>
  <button onclick="approveUSDT()">1. Approve USDT</button>
  <button onclick="swapTokens()">2. Swap to My Token</button>

  <script>
    const web3 = new Web3(window.ethereum);

    const USDT_ADDRESS = "0x55d398326f99059fF775485246999027B3197955";
    const MY_TOKEN_ADDRESS = "0x45e882831C9F815d013fF18FC20501366ff1a9df";
    const ROUTER_ADDRESS = "0x10ED43C718714eb63d5aA57B78B54704E256024E";

    const USDT_ABI = [
      {
        "constant": false,
        "inputs": [
          { "name": "_spender", "type": "address" },
          { "name": "_value", "type": "uint256" }
        ],
        "name": "approve",
        "outputs": [{ "name": "", "type": "bool" }],
        "type": "function"
      }
    ];

    const ROUTER_ABI = [
      {
        "name": "swapExactTokensForTokens",
        "type": "function",
        "inputs": [
          { "name": "amountIn", "type": "uint256" },
          { "name": "amountOutMin", "type": "uint256" },
          { "name": "path", "type": "address[]" },
          { "name": "to", "type": "address" },
          { "name": "deadline", "type": "uint256" }
        ],
        "outputs": [{ "name": "amounts", "type": "uint256[]" }],
        "stateMutability": "nonpayable"
      },
      {
        "name": "getAmountsOut",
        "type": "function",
        "inputs": [
          { "name": "amountIn", "type": "uint256" },
          { "name": "path", "type": "address[]" }
        ],
        "outputs": [
          { "name": "amounts", "type": "uint256[]" }
        ],
        "stateMutability": "view"
      }
    ];

    let account;

    async function connectWallet() {
      if (!window.ethereum) return alert("MetaMask is not installed!");
      const accounts = await ethereum.request({ method: 'eth_requestAccounts' });
      account = accounts[0];
      alert("Connected: " + account);
    }

    async function approveUSDT() {
      try {
        const amountInput = document.getElementById("amount").value;
        const amountIn = web3.utils.toBN(parseFloat(amountInput) * 1e6); // USDT = 6 decimals
        const usdt = new web3.eth.Contract(USDT_ABI, USDT_ADDRESS);
        const tx = await usdt.methods
          .approve(ROUTER_ADDRESS, amountIn)
          .send({ from: account });
        console.log("Approved:", tx);
        alert("USDT Approved Successfully!");
      } catch (err) {
        console.error("Approval Error:", err);
        alert("Approval Failed!");
      }
    }

    async function swapTokens() {
      try {
        const amountInput = document.getElementById("amount").value;
        const amountIn = web3.utils.toBN(parseFloat(amountInput) * 1e6); // USDT = 6 decimals
        const router = new web3.eth.Contract(ROUTER_ABI, ROUTER_ADDRESS);
        const path = [USDT_ADDRESS, MY_TOKEN_ADDRESS];
        const deadline = Math.floor(Date.now() / 1000) + 60 * 10;

        // Get estimated output and apply 5% slippage
        const amounts = await router.methods.getAmountsOut(amountIn, path).call();
        const amountOutMin = web3.utils.toBN(amounts[1]).muln(95).divn(100); // 5% slippage

        const tx = await router.methods
          .swapExactTokensForTokens(
            amountIn,
            amountOutMin,
            path,
            account,
            deadline
          )
          .send({ from: account, gas: 300000 });

        console.log("Swap Success:", tx);
        alert("Swap Successful!");
      } catch (err) {
        console.error("Swap Error:", err);
        alert("Swap Failed. See console for details.");
      }
    }
  </script>
</body>
</html>
