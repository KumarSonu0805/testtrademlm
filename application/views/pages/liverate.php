<!DOCTYPE html>
<html>
<head>
  <title>DXC Token Live Price</title>
  <script src="https://cdn.jsdelivr.net/npm/web3@1.7.3/dist/web3.min.js"></script>
</head>
<body>
  <h2>Live DXC → USDT Price:</h2>
  <div id="price">Loading...</div>

  <script>
    const DXC = "0x45e882831C9F815d013fF18FC20501366ff1a9df";
    const WBNB = "0xbb4CdB9CBd36B01bD1cBaEBF2De08d9173bc095c";
    const USDT = "0x55d398326f99059fF775485246999027B3197955";
    const routerAddress = "0x10ED43C718714eb63d5aA57B78B54704E256024E"; // PancakeSwap V2 Router

    const routerABI = [
      {
        "name": "getAmountsOut",
        "type": "function",
        "inputs": [
          { "type": "uint256", "name": "amountIn" },
          { "type": "address[]", "name": "path" }
        ],
        "outputs": [
          { "type": "uint256[]", "name": "amounts" }
        ],
        "stateMutability": "view"
      }
    ];

    async function getTokenPrice() {
      if (!window.ethereum) {
        document.getElementById("price").innerText = "Please install MetaMask.";
        return;
      }

      const web3 = new Web3(window.ethereum);
      await ethereum.request({ method: 'eth_requestAccounts' });

      const router = new web3.eth.Contract(routerABI, routerAddress);

      const amountIn = web3.utils.toWei("1", "ether"); // 1 DXC (assuming 18 decimals)
      const path = [DXC, WBNB, USDT]; // via WBNB

      try {
        const amounts = await router.methods.getAmountsOut(amountIn, path).call();
        const usdtOut = web3.utils.fromWei(amounts[amounts.length - 1], "ether");
        document.getElementById("price").innerText = `1 DXC ≈ ${parseFloat(usdtOut).toFixed(6)} USDT`;
      } catch (err) {
        console.error(err);
        document.getElementById("price").innerText = "Failed to fetch price. Check if token is listed or paired.";
      }
    }

    // Run once and refresh every 5 seconds
    getTokenPrice();
    setInterval(getTokenPrice, 5000);
  </script>
</body>
</html>
