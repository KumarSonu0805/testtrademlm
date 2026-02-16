<!DOCTYPE html>
<html>
<head>
  <title>Swap USDT to My Token</title>
  <script src="https://cdn.jsdelivr.net/gh/ethereum/web3.js@1.7.3/dist/web3.min.js"></script>
</head>
<body>
  <?php /*<button onclick="swapTokens()">Swap 10 USDT for My Token</button>*/ ?>
    <div>
  <div>USDT Balance: <span id="usdtBalance">0</span></div>
  <div>Your Token Balance: <span id="yourTokenBalance">0</span></div>

  <input type="number" id="fromAmount" placeholder="Amount USDT to swap" min="0" step="any" />
  <button id="swapBtn">Swap USDT for Your Token</button>
</div>


  <script>
    const CONFIG = {
      USDT_CONTRACT: '0x55d398326f99059fF775485246999027B3197955',
      YOUR_TOKEN_CONTRACT: '0x45e882831C9F815d013fF18FC20501366ff1a9df',
      ROUTER_V3_ADDRESS: '0xCc7aDc94F3D80127849D2b41b6439b7CF1eB4Ae0', // PancakeSwap V3 Router
      BSC_CHAIN_ID: '0x38',
    };

    const ERC20_ABI = [
      // minimal ERC20 ABI needed
      {
        constant: true,
        inputs: [{ name: 'account', type: 'address' }],
        name: 'balanceOf',
        outputs: [{ name: '', type: 'uint256' }],
        type: 'function',
      },
      {
        constant: true,
        inputs: [],
        name: 'decimals',
        outputs: [{ name: '', type: 'uint8' }],
        type: 'function',
      },
      {
        constant: false,
        inputs: [
          { name: 'spender', type: 'address' },
          { name: 'amount', type: 'uint256' },
        ],
        name: 'approve',
        outputs: [{ name: '', type: 'bool' }],
        type: 'function',
      },
      {
        constant: true,
        inputs: [
          { name: 'owner', type: 'address' },
          { name: 'spender', type: 'address' },
        ],
        name: 'allowance',
        outputs: [{ name: '', type: 'uint256' }],
        type: 'function',
      },
    ];

    const ROUTER_V3_ABI = [
      {
        inputs: [
          {
            components: [
              { internalType: 'address', name: 'tokenIn', type: 'address' },
              { internalType: 'address', name: 'tokenOut', type: 'address' },
              { internalType: 'uint24', name: 'fee', type: 'uint24' },
              { internalType: 'address', name: 'recipient', type: 'address' },
              { internalType: 'uint256', name: 'deadline', type: 'uint256' },
              { internalType: 'uint256', name: 'amountIn', type: 'uint256' },
              { internalType: 'uint256', name: 'amountOutMinimum', type: 'uint256' },
              { internalType: 'uint160', name: 'sqrtPriceLimitX96', type: 'uint160' },
            ],
            internalType: 'struct ISwapRouter.ExactInputSingleParams',
            name: 'params',
            type: 'tuple',
          },
        ],
        name: 'exactInputSingle',
        outputs: [{ internalType: 'uint256', name: 'amountOut', type: 'uint256' }],
        stateMutability: 'payable',
        type: 'function',
      },
    ];

    let web3;
    let userAccount;
    let usdtContract;
    let yourTokenContract;
    let routerV3Contract;

    async function init() {
      if (!window.ethereum) {
        alert('MetaMask not found, please install it.');
        return;
      }

      web3 = new Web3(window.ethereum);

      const accounts = await window.ethereum.request({
        method: 'eth_requestAccounts',
      });
      userAccount = accounts[0];

      // Switch to BSC mainnet
      await window.ethereum.request({
        method: 'wallet_switchEthereumChain',
        params: [{ chainId: CONFIG.BSC_CHAIN_ID }],
      });

      usdtContract = new web3.eth.Contract(ERC20_ABI, CONFIG.USDT_CONTRACT);
      yourTokenContract = new web3.eth.Contract(ERC20_ABI, CONFIG.YOUR_TOKEN_CONTRACT);
      routerV3Contract = new web3.eth.Contract(ROUTER_V3_ABI, CONFIG.ROUTER_V3_ADDRESS);

      console.log('Wallet connected:', userAccount);
      updateBalances();

      document.getElementById('swapBtn').onclick = swapTokens;
    }

    async function updateBalances() {
      const usdtBalance = await usdtContract.methods.balanceOf(userAccount).call();
      const usdtDecimals = await usdtContract.methods.decimals().call();
      const formattedUSDT = Number(usdtBalance) / 10 ** usdtDecimals;

      const yourTokenBalance = await yourTokenContract.methods.balanceOf(userAccount).call();
      const yourTokenDecimals = await yourTokenContract.methods.decimals().call();
      const formattedYourToken = Number(yourTokenBalance) / 10 ** yourTokenDecimals;

      document.getElementById('usdtBalance').textContent = formattedUSDT.toFixed(6);
      document.getElementById('yourTokenBalance').textContent = formattedYourToken.toFixed(6);
    }

    async function swapTokens() {
      const amountInStr = document.getElementById('fromAmount').value;
      if (!amountInStr || isNaN(amountInStr) || Number(amountInStr) <= 0) {
        alert('Please enter a valid amount');
        return;
      }

      try {
        const usdtDecimals = await usdtContract.methods.decimals().call();
        const amountIn = web3.utils.toBN(
          (Number(amountInStr) * 10 ** usdtDecimals).toString()
        );

        // Check USDT balance
        const balance = await usdtContract.methods.balanceOf(userAccount).call();
        if (web3.utils.toBN(balance).lt(amountIn)) {
          alert('Insufficient USDT balance');
          return;
        }

        // Check allowance
        const allowance = await usdtContract.methods.allowance(userAccount, CONFIG.ROUTER_V3_ADDRESS).call();
        if (web3.utils.toBN(allowance).lt(amountIn)) {
          // Approve Router V3 to spend USDT
          const approveTx = await usdtContract.methods
            .approve(CONFIG.ROUTER_V3_ADDRESS, web3.utils.toWei('1000000', 'ether'))
            .send({ from: userAccount });
          console.log('Approval tx:', approveTx.transactionHash);
          alert('Approval successful. Please confirm the swap transaction.');
        }

        // Swap params
        const fee = 3000; // Pool fee tier (0.3%)
        const to = userAccount;
        const deadline = Math.floor(Date.now() / 1000) + 60 * 10; // 10 minutes deadline
        const amountOutMinimum = 0; // WARNING: for testing only, no slippage protection
        const sqrtPriceLimitX96 = 0; // No price limit

        const params = {
          tokenIn: CONFIG.USDT_CONTRACT,
          tokenOut: CONFIG.YOUR_TOKEN_CONTRACT,
          fee,
          recipient: to,
          deadline,
          amountIn: amountIn.toString(),
          amountOutMinimum,
          sqrtPriceLimitX96,
        };

        const tx = await routerV3Contract.methods
          .exactInputSingle(params)
          .send({ from: userAccount, gas: 300000, value: 0 });

        console.log('Swap tx:', tx.transactionHash);
        alert('Swap successful! Tx: ' + tx.transactionHash);

        updateBalances();
      } catch (error) {
        console.error('Swap error:', error);
        alert('Swap failed: ' + (error.message || error));
      }
    }

    window.addEventListener('load', init);


  </script>
</body>
</html>
