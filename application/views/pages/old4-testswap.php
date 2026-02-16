<!DOCTYPE html>
<html>
<head>
  <title>Swap USDT to MyToken</title>
  <script src="https://cdn.jsdelivr.net/npm/web3@1.7.5/dist/web3.min.js"></script>
</head>
<body>
  <button onclick="swap()">Swap USDT to MyToken</button>
    <button onclick="getBNBBalance()">Connect & Get BNB Balance</button>
<p id="bnb-balance">Balance: </p>
    <button onclick="getTokenBalance('0x45e882831C9F815d013fF18FC20501366ff1a9df')">Connect & Get Token Balance</button>
<p id="token-balance">Balance: </p>

<script>
  async function getBNBBalance() {
    if (window.ethereum) {
      try {
        // Connect to MetaMask
        const accounts = await window.ethereum.request({ method: 'eth_requestAccounts' });
        const userAddress = accounts[0];

        // Set up Web3 with MetaMask's provider
        const web3 = new Web3(window.ethereum);

        // Get balance in Wei and convert to BNB
        const balanceWei = await web3.eth.getBalance(userAddress);
        const balanceBNB = web3.utils.fromWei(balanceWei, 'ether');

        document.getElementById('bnb-balance').innerText = `Balance: ${balanceBNB} BNB`;
      } catch (error) {
        console.error(error);
        alert("Failed to fetch BNB balance.");
      }
    } else {
      alert("Please install MetaMask to use this feature.");
    }
  }
</script>
    <script>
  async function getTokenBalance(tokenAddress, tokenSymbol, decimals = 18) {
    const web3 = new Web3(window.ethereum);
    const accounts = await ethereum.request({ method: 'eth_requestAccounts' });
    const userAddress = accounts[0];

    const tokenABI = [
      { constant: true, name: "balanceOf", type: "function", inputs: [{ name: "_owner", type: "address" }], outputs: [{ name: "balance", type: "uint256" }] },
      { constant: true, name: "decimals", type: "function", inputs: [], outputs: [{ name: "", type: "uint8" }] }
    ];

    const contract = new web3.eth.Contract(tokenABI, tokenAddress);
    const rawBalance = await contract.methods.balanceOf(userAddress).call();
    const formatted = web3.utils.fromWei(rawBalance, decimals === 18 ? 'ether' : 'wei');

    //alert(`${tokenSymbol} Balance: ${formatted}`);
      document.getElementById('token-balance').innerText = `Balance: ${formatted}`;
  }

  // Example usage for BUSD
  // BUSD token on BSC: 0xe9e7cea3dedca5984780bafc599bd69add087d56
  // getTokenBalance("0xe9e7cea3dedca5984780bafc599bd69add087d56", "BUSD", 18);
</script>


    <iframe
  src="https://pancakeswap.finance/swap?outputCurrency=0x45e882831C9F815d013fF18FC20501366ff1a9df&inputCurrency=0x55d398326f99059fF775485246999027B3197955"
  width="100%"
  height="600"
  frameborder="0"
  allowfullscreen
></iframe>

  <script>
    const usdtAddress = '0x55d398326f99059fF775485246999027B3197955'; // USDT on BSC
    const myTokenAddress = '0x45e882831C9F815d013fF18FC20501366ff1a9df'; // Your token
    const routerAddress = '0x10ED43C718714eb63d5aA57B78B54704E256024E'; // PancakeSwap Router V2

    const routerABI = [{
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

    async function swap() {
      if (typeof window.ethereum === 'undefined') {
        alert('Please install MetaMask!');
        return;
      }

      const web3 = new Web3(window.ethereum);
      await window.ethereum.request({ method: 'eth_requestAccounts' });
      const accounts = await web3.eth.getAccounts();
      const user = accounts[0];

      const router = new web3.eth.Contract(routerABI, routerAddress);
      const amountIn = web3.utils.toWei('10', 'ether'); // 10 USDT (18 decimals on BSC)
      const path = [usdtAddress, myTokenAddress];
      const deadline = Math.floor(Date.now() / 1000) + 60 * 10;

      const usdtABI = [{
        "constant": false,
        "inputs": [
          { "name": "_spender", "type": "address" },
          { "name": "_value", "type": "uint256" }
        ],
        "name": "approve",
        "outputs": [{ "name": "", "type": "bool" }],
        "type": "function"
      }];
      const usdt = new web3.eth.Contract(usdtABI, usdtAddress);

      try {
        await usdt.methods.approve(routerAddress, amountIn).send({ from: user });
      } catch (err) {
        if (err.code === 4001) {
          alert('Transaction rejected by user.');
          return;
        } else {
          console.error(err);
        }
      }

      try {
        await router.methods.swapExactTokensForTokens(
          amountIn,
          0, // Slippage protection recommended
          path,
          user,
          deadline
        ).send({ from: user });

        alert('Swap completed!');
      } catch (err) {
        console.error(err);
        alert('Swap failed');
      }
    }
  </script>
</body>
</html>
