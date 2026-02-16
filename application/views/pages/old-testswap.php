<!DOCTYPE html>
<html>
<head>
  <title>Swap USDT → MyToken</title>
  <script src="https://cdn.jsdelivr.net/npm/web3@1.8.2/dist/web3.min.js"></script>
</head>
<body>
  <h2>Swap USDT to MyToken</h2>
  <input type="text" id="amount" placeholder="USDT amount" />
  <button onclick="swapTokens()">Approve & Swap</button>

  <script>
    const ROUTER = "0x10ED43C718714eb63d5aA57B78B54704E256024E"; // PancakeSwap Router
    const USDT = "0x55d398326f99059fF775485246999027B3197955";
    const MYTOKEN = "0x45e882831C9F815d013fF18FC20501366ff1a9df";
    // Add WBNB if needed: const WBNB = "0xbb4CdB9CBd36B01bD1cBaEBF2De08d9173bc095c";

    const ERC20_ABI = [
      {
        name: "approve",
        type: "function",
        inputs: [
          { name: "spender", type: "address" },
          { name: "amount", type: "uint256" }
        ],
        outputs: [{ name: "", type: "bool" }],
        stateMutability: "nonpayable",
        type: "function"
      },
      {
        name: "decimals",
        type: "function",
        inputs: [],
        outputs: [{ name: "", type: "uint8" }],
        stateMutability: "view",
        type: "function"
      },
      {
        name: "allowance",
        type: "function",
        inputs: [
          { name: "owner", type: "address" },
          { name: "spender", type: "address" }
        ],
        outputs: [{ name: "remaining", type: "uint256" }],
        stateMutability: "view",
        type: "function"
      }
    ];

    const ROUTER_ABI = [
      {
        name: "swapExactTokensForTokens",
        type: "function",
        inputs: [
          { name: "amountIn", type: "uint256" },
          { name: "amountOutMin", type: "uint256" },
          { name: "path", type: "address[]" },
          { name: "to", type: "address" },
          { name: "deadline", type: "uint256" }
        ],
        outputs: [{ name: "amounts", type: "uint256[]" }],
        stateMutability: "nonpayable",
        type: "function"
      }
    ];

    async function swapTokens() {
      // Detect provider (MetaMask or Trust Wallet)
      let provider;
      if (window.ethereum) {
        provider = window.ethereum;
      } else if (window.web3) {
        provider = window.web3.currentProvider;
      } else {
        alert("Please install MetaMask or open this page in Trust Wallet's DApp Browser.");
        return;
      }

      const web3 = new Web3(provider);

      try {
        // Request account access
        if (provider.request) {
          await provider.request({ method: "eth_requestAccounts" });
        } else if (provider.enable) {
          await provider.enable();
        }

        // Check if user is connected to BSC Mainnet (chainId = 0x38)
        let chainId = await provider.request({ method: "eth_chainId" });
        if (chainId !== "0x38") {
          try {
            await provider.request({
              method: "wallet_switchEthereumChain",
              params: [{ chainId: "0x38" }]
            });
            chainId = "0x38";
          } catch (switchError) {
            // Switching not supported or rejected
            alert("Please switch your wallet network to BSC Mainnet (Binance Smart Chain) manually.");
            return;
          }
        }

        const accounts = await web3.eth.getAccounts();
        if (!accounts.length) {
          alert("Please connect your wallet.");
          return;
        }
        const user = accounts[0];

        // Read amount input and validate
        const amountStr = document.getElementById("amount").value.trim();
        if (!amountStr || isNaN(amountStr) || Number(amountStr) <= 0) {
          alert("Please enter a valid USDT amount.");
          return;
        }

        // USDT decimals = 6, so use 'mwei' for toWei conversion
        const amountIn = web3.utils.toWei(amountStr, "mwei");

        const usdtContract = new web3.eth.Contract(ERC20_ABI, USDT);
        const router = new web3.eth.Contract(ROUTER_ABI, ROUTER);

        // Approve max uint256 allowance to reduce repeated approvals
        const MAX_UINT = web3.utils.toTwosComplement(-1);

        // Check allowance
        const allowance = await usdtContract.methods.allowance(user, ROUTER).call();
        if (BigInt(allowance) < BigInt(amountIn)) {
          console.log("Approving tokens...");
          const gasPrice = await web3.eth.getGasPrice();
          await usdtContract.methods.approve(ROUTER, MAX_UINT).send({
            from: user,
            gasPrice: gasPrice,
          });
          console.log("Approval successful.");
        } else {
          console.log("Sufficient allowance already exists.");
        }

        // Prepare swap
        // Try direct USDT -> MYTOKEN swap path for less gas (if liquidity pool exists)
        // Otherwise, use [USDT, WBNB, MYTOKEN]
        const path = [USDT, MYTOKEN];
        const deadline = Math.floor(Date.now() / 1000) + 60 * 10; // 10 minutes

        const gasPrice = await web3.eth.getGasPrice();

        console.log("Swapping tokens...");
        await router.methods
          .swapExactTokensForTokens(
            amountIn,
            0, // amountOutMin = 0, can be risky (slippage)
            path,
            user,
            deadline
          )
          .send({
            from: user,
            gasPrice: gasPrice,
          });

        alert("Swap completed successfully!");
      } catch (error) {
        console.error("Swap failed:", error);
        alert("Swap failed: " + (error.message || error));
      }
    }
  </script>
</body>
</html>
