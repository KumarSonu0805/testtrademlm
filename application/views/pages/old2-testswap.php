<!DOCTYPE html>
<html>
<head>
  <title>Swapper</title>
  <script src="https://cdn.jsdelivr.net/npm/web3@1.8.2/dist/web3.min.js"></script>
</head>
<body>
  <h2>Swapper</h2>

  <div>
    <button onclick="connectWallet()">🔗 Connect Wallet</button><br><br>
    <input type="text" id="amount" placeholder="Amount of USDT" />
    <br><br>
    <button onclick="swapTokens()">⚡ Approve & Swap</button>
  </div>

  <p id="status"></p>

  <script>
    const web3 = new Web3(window.ethereum);
    let userAddress;

    const ROUTER = "0x10ED43C718714eb63d5aA57B78B54704E256024E"; // PancakeSwap V2
    const USDT = "0x55d398326f99059fF775485246999027B3197955";
    const WBNB = "0xbb4CdB9CBd36B01bD1cBaEBF2De08d9173bc095c";
    const MYTOKEN = "0x45e882831C9F815d013fF18FC20501366ff1a9df";

    const ERC20_ABI = [
      { "name": "approve", "type": "function", "inputs": [{ "name": "spender", "type": "address" }, { "name": "amount", "type": "uint256" }], "outputs": [{ "name": "", "type": "bool" }], "stateMutability": "nonpayable" },
      { "name": "allowance", "type": "function", "inputs": [{ "name": "owner", "type": "address" }, { "name": "spender", "type": "address" }], "outputs": [{ "name": "remaining", "type": "uint256" }], "stateMutability": "view" }
    ];

    const ROUTER_ABI = [{
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
    }];

    const usdtContract = new web3.eth.Contract(ERC20_ABI, USDT);
    const router = new web3.eth.Contract(ROUTER_ABI, ROUTER);

    async function connectWallet() {
      try {
        const accounts = await window.ethereum.request({ method: 'eth_requestAccounts' });
        userAddress = accounts[0];
        document.getElementById("status").innerText = "Connected: " + userAddress;
      } catch (err) {
        document.getElementById("status").innerText = "Wallet connection failed.";
      }
    }

    async function swapTokens() {
      if (!userAddress) {
        return alert("Please connect your wallet first.");
      }

      const inputAmount = document.getElementById("amount").value;
      if (!inputAmount || isNaN(inputAmount)) return alert("Enter a valid amount.");

      const amountIn = web3.utils.toWei(inputAmount, "mwei"); // USDT has 6 decimals
      const deadline = Math.floor(Date.now() / 1000) + 60 * 10;

      const path = [USDT, WBNB, MYTOKEN];

      try {
        document.getElementById("status").innerText = "Checking allowance...";

        const allowance = await usdtContract.methods.allowance(userAddress, ROUTER).call();
        if (BigInt(allowance) < BigInt(amountIn)) {
          document.getElementById("status").innerText = "Approving USDT...";
          await usdtContract.methods.approve(ROUTER, amountIn).send({ from: userAddress });
        }

        document.getElementById("status").innerText = "Swapping...";

        await router.methods.swapExactTokensForTokens(
          amountIn,
          0,
          path,
          userAddress,
          deadline
        ).send({ from: userAddress });

        document.getElementById("status").innerText = "Swap successful!";
      } catch (err) {
        console.error(err);
        document.getElementById("status").innerText = "Swap failed: " + err.message;
      }
    }
  </script>
</body>
</html>
