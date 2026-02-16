<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Token Swap - USDT to Custom Token</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/web3/1.10.0/web3.min.js"></script>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
/*
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
*/
        }

        .swap-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #333;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .header p {
            color: #666;
            font-size: 14px;
        }

        .wallet-section {
            margin-bottom: 30px;
            text-align: center;
        }

        .connect-btn {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .connect-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .connected {
            background: linear-gradient(135deg, #4CAF50, #45a049);
        }

        .wallet-info {
            margin-top: 15px;
            padding: 15px;
            background: rgba(102, 126, 234, 0.1);
            border-radius: 12px;
            font-size: 14px;
            color: #333;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }

        .input-container {
            position: relative;
            background: white;
            border-radius: 12px;
            border: 2px solid #e1e5e9;
            transition: all 0.3s ease;
        }

        .input-container:focus-within {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-input {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            background: transparent;
            outline: none;
        }

        .token-symbol {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }

        .balance-info {
            display: flex;
            justify-content: space-between;
            margin-top: 8px;
            font-size: 12px;
            color: #666;
        }

        .max-btn {
            background: none;
            border: 1px solid #667eea;
            color: #667eea;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .max-btn:hover {
            background: #667eea;
            color: white;
        }

        .swap-arrow {
            text-align: center;
            margin: 20px 0;
            position: relative;
        }

        .arrow-btn {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            color: white;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .arrow-btn:hover {
            transform: rotate(180deg);
        }

        .rate-info {
            background: rgba(102, 126, 234, 0.1);
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 25px;
            font-size: 14px;
            color: #333;
        }

        .rate-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .rate-row:last-child {
            margin-bottom: 0;
            font-weight: 600;
        }

        .swap-btn {
            width: 100%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 18px;
            border-radius: 12px;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .swap-btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .swap-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
            margin-right: 10px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .error {
            background: #fee;
            border: 1px solid #fcc;
            color: #c66;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .success {
            background: #efe;
            border: 1px solid #cfc;
            color: #6c6;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .contract-info {
            margin-top: 30px;
            padding: 20px;
            background: rgba(0, 0, 0, 0.05);
            border-radius: 12px;
            font-size: 12px;
            color: #666;
        }

        .contract-info h3 {
            color: #333;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .contract-address {
            word-break: break-all;
            font-family: monospace;
            background: rgba(0, 0, 0, 0.05);
            padding: 8px;
            border-radius: 6px;
            margin: 5px 0;
        }

        @media (max-width: 600px) {
            .swap-container {
                padding: 25px;
                margin: 10px;
            }
            
            .header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="swap-container">
        <div class="header">
            <h1>Token Swap</h1>
            <p>Exchange USDT for Custom Token on BSC</p>
        </div>

        <div class="wallet-section">
            <button id="connectWallet" class="connect-btn">Connect Wallet</button>
            <div id="walletInfo" class="wallet-info" style="display: none;"></div>
        </div>

        <div id="errorMessage" class="error" style="display: none;"></div>
        <div id="successMessage" class="success" style="display: none;"></div>

        <form id="swapForm">
            <div class="form-group">
                <label for="fromAmount">From</label>
                <div class="input-container">
                    <input type="number" id="fromAmount" class="form-input" placeholder="0.0" step="0.000001" min="0">
                    <span class="token-symbol">USDT</span>
                </div>
                <div class="balance-info">
                    <span>Balance: <span id="usdtBalance">0.000000</span> USDT</span>
                    <button type="button" class="max-btn" onclick="setMaxAmount()">MAX</button>
                </div>
            </div>

            <div class="swap-arrow">
                <button type="button" class="arrow-btn">↓</button>
            </div>

            <div class="form-group">
                <label for="toAmount">To (Estimated)</label>
                <div class="input-container">
                    <input type="number" id="toAmount" class="form-input" placeholder="0.0" readonly>
                    <span class="token-symbol">DXC</span>
                </div>
                <div class="balance-info">
                    <span>Balance: <span id="customTokenBalance">0.000000</span> DXC</span>
                </div>
            </div>

            <div class="rate-info">
                <div class="rate-row">
                    <span>Exchange Rate:</span>
                    <span id="exchangeRate">1 USDT = 1.0 DXC</span>
                </div>
                <div class="rate-row">
                    <span>Network Fee:</span>
                    <span>~$0.50</span>
                </div>
                <div class="rate-row">
                    <span>You'll Receive:</span>
                    <span id="finalAmount">0.0 DXC</span>
                </div>
            </div>

            <button type="submit" id="swapBtn" class="swap-btn" disabled>
                Connect Wallet to Swap
            </button>
        </form>

        <div class="contract-info">
            <h3>Contract Information</h3>
            <div>
                <strong>USDT (BEP20):</strong>
                <div class="contract-address" id="usdtContract">0x55d398326f99059fF775485246999027B3197955</div>
            </div>
            <div>
                <strong>Custom Token:</strong>
                <div class="contract-address" id="customTokenContract">Your Contract Address Here</div>
            </div>
            <div style="margin-top: 10px;">
                <strong>Network:</strong> Binance Smart Chain (BSC)
            </div>
        </div>
    </div>

    <script>
        // Contract addresses and configuration
        const CONFIG = {
            USDT_CONTRACT: '0x55d398326f99059fF775485246999027B3197955',
            CUSTOM_TOKEN_CONTRACT: '0x45e882831C9F815d013fF18FC20501366ff1a9df', // Replace with your actual contract address
            BSC_CHAIN_ID: '0x38', // BSC Mainnet
            BSC_RPC_URL: 'https://bsc-dataseed.binance.org/',
            EXCHANGE_RATE: 1.0 // 1 USDT = 1.0 Custom Token (adjust as needed)
        };

        // MintableTeamToken ABI (add swap function)
        const SWAP_ABI = [
            ...ERC20_ABI,
            {
                "inputs": [{"name": "usdtAmount", "type": "uint256"}],
                "name": "swapUSDTForTokens",
                "outputs": [],
                "stateMutability": "nonpayable",
                "type": "function"
            },
            {
                "inputs": [{"name": "account", "type": "address"}],
                "name": "balanceOf",
                "outputs": [{"name": "", "type": "uint256"}],
                "stateMutability": "view",
                "type": "function"
            },
            {
                "inputs": [{"name": "spender", "type": "address"}, {"name": "amount", "type": "uint256"}],
                "name": "approve",
                "outputs": [{"name": "", "type": "bool"}],
                "stateMutability": "nonpayable",
                "type": "function"
            },
            {
                "inputs": [{"name": "owner", "type": "address"}, {"name": "spender", "type": "address"}],
                "name": "allowance",
                "outputs": [{"name": "", "type": "uint256"}],
                "stateMutability": "view",
                "type": "function"
            },
            {
                "inputs": [{"name": "recipient", "type": "address"}, {"name": "amount", "type": "uint256"}],
                "name": "transfer",
                "outputs": [{"name": "", "type": "bool"}],
                "stateMutability": "nonpayable",
                "type": "function"
            },
            {
                "inputs": [],
                "name": "decimals",
                "outputs": [{"name": "", "type": "uint8"}],
                "stateMutability": "view",
                "type": "function"
            }
        ];

        // Global variables
        let web3;
        let userAccount;
        let usdtContract;
        let customTokenContract;

        // DOM elements
        const connectWalletBtn = document.getElementById('connectWallet');
        const walletInfo = document.getElementById('walletInfo');
        const fromAmountInput = document.getElementById('fromAmount');
        const toAmountInput = document.getElementById('toAmount');
        const swapBtn = document.getElementById('swapBtn');
        const errorMessage = document.getElementById('errorMessage');
        const successMessage = document.getElementById('successMessage');
        const usdtBalanceSpan = document.getElementById('usdtBalance');
        const customTokenBalanceSpan = document.getElementById('customTokenBalance');
        const exchangeRateSpan = document.getElementById('exchangeRate');
        const finalAmountSpan = document.getElementById('finalAmount');

        // Initialize
        document.addEventListener('DOMContentLoaded', async () => {
            if (window.ethereum) {
                web3 = new Web3(window.ethereum);
                
                // Check if already connected
                const accounts = await web3.eth.getAccounts();
                if (accounts.length > 0) {
                    userAccount = accounts[0];
                    updateWalletUI();
                    initializeContracts();
                }
            } else {
                showError('Please install MetaMask to use this application');
            }

            // Set up event listeners
            connectWalletBtn.addEventListener('click', connectWallet);
            fromAmountInput.addEventListener('input', calculateSwapAmount);
            document.getElementById('swapForm').addEventListener('submit', handleSwap);

            // Update contract address display
            document.getElementById('customTokenContract').textContent = CONFIG.CUSTOM_TOKEN_CONTRACT;
        });

        // Connect wallet function
        async function connectWallet() {
            try {
                if (!window.ethereum) {
                    throw new Error('MetaMask not found');
                }

                // Request account access
                const accounts = await window.ethereum.request({
                    method: 'eth_requestAccounts'
                });

                userAccount = accounts[0];

                // Switch to BSC network if needed
                await switchToBSC();

                updateWalletUI();
                initializeContracts();
                
                hideError();
                showSuccess('Wallet connected successfully!');

            } catch (error) {
                console.error('Wallet connection error:', error);
                showError('Failed to connect wallet: ' + error.message);
            }
        }

        // Switch to BSC network
        async function switchToBSC() {
            try {
                await window.ethereum.request({
                    method: 'wallet_switchEthereumChain',
                    params: [{ chainId: CONFIG.BSC_CHAIN_ID }],
                });
            } catch (switchError) {
                // If BSC network is not added, add it
                if (switchError.code === 4902) {
                    try {
                        await window.ethereum.request({
                            method: 'wallet_addEthereumChain',
                            params: [{
                                chainId: CONFIG.BSC_CHAIN_ID,
                                chainName: 'Binance Smart Chain',
                                nativeCurrency: {
                                    name: 'BNB',
                                    symbol: 'BNB',
                                    decimals: 18
                                },
                                rpcUrls: [CONFIG.BSC_RPC_URL],
                                blockExplorerUrls: ['https://bscscan.com/']
                            }]
                        });
                    } catch (addError) {
                        throw new Error('Failed to add BSC network');
                    }
                }
            }
        }

        // Update wallet UI
        function updateWalletUI() {
            connectWalletBtn.textContent = 'Connected';
            connectWalletBtn.classList.add('connected');
            connectWalletBtn.disabled = true;
            
            walletInfo.style.display = 'block';
            walletInfo.innerHTML = `
                <strong>Connected:</strong> ${userAccount.substring(0, 6)}...${userAccount.substring(38)}<br>
                <strong>Network:</strong> Binance Smart Chain
            `;
            
            swapBtn.disabled = false;
            swapBtn.textContent = 'Enter Amount to Swap';
        }

        // Initialize contracts
        function initializeContracts() {
            usdtContract = new web3.eth.Contract(ERC20_ABI, CONFIG.USDT_CONTRACT);
            customTokenContract = new web3.eth.Contract(ERC20_ABI, CONFIG.CUSTOM_TOKEN_CONTRACT);
            
            updateBalances();
        }

        // Update balances
        async function updateBalances() {
            try {
                if (usdtContract && customTokenContract && userAccount) {
                    const usdtBalance = await usdtContract.methods.balanceOf(userAccount).call();
                    const customTokenBalance = await customTokenContract.methods.balanceOf(userAccount).call();
                    
                    usdtBalanceSpan.textContent = parseFloat(web3.utils.fromWei(usdtBalance, 'ether')).toFixed(6);
                    customTokenBalanceSpan.textContent = parseFloat(web3.utils.fromWei(customTokenBalance, 'ether')).toFixed(6);
                }
            } catch (error) {
                console.error('Error updating balances:', error);
            }
        }

        // Set max amount
        function setMaxAmount() {
            const maxAmount = parseFloat(usdtBalanceSpan.textContent);
            fromAmountInput.value = maxAmount;
            calculateSwapAmount();
        }

        // Calculate swap amount
        function calculateSwapAmount() {
            const fromAmount = parseFloat(fromAmountInput.value) || 0;
            const toAmount = fromAmount * CONFIG.EXCHANGE_RATE;
            
            toAmountInput.value = toAmount.toFixed(6);
            finalAmountSpan.textContent = toAmount.toFixed(6) + ' DXC';
            exchangeRateSpan.textContent = `1 USDT = ${CONFIG.EXCHANGE_RATE} DXC`;

            // Update swap button
            if (fromAmount > 0 && userAccount) {
                swapBtn.disabled = false;
                swapBtn.textContent = `Swap ${fromAmount.toFixed(6)} USDT`;
            } else {
                swapBtn.disabled = true;
                swapBtn.textContent = userAccount ? 'Enter Amount to Swap' : 'Connect Wallet to Swap';
            }
        }

        // Handle swap
        async function handleSwap(event) {
            event.preventDefault();
            
            const fromAmount = parseFloat(fromAmountInput.value);
            if (!fromAmount || fromAmount <= 0) {
                showError('Please enter a valid amount');
                return;
            }

            try {
                swapBtn.disabled = true;
                swapBtn.innerHTML = '<span class="loading"></span>Swapping...';

                const amountWei = web3.utils.toWei(fromAmount.toString(), 'ether');
                
                // Check USDT balance
                const usdtBalance = await usdtContract.methods.balanceOf(userAccount).call();
                if (web3.utils.toBN(usdtBalance).lt(web3.utils.toBN(amountWei))) {
                    throw new Error('Insufficient USDT balance');
                }

                // Check allowance and approve if needed
                const allowance = await usdtContract.methods.allowance(userAccount, CONFIG.CUSTOM_TOKEN_CONTRACT).call();
                if (web3.utils.toBN(allowance).lt(web3.utils.toBN(amountWei))) {
                    showSuccess('Approving USDT spend...');
                    const approveTx = await usdtContract.methods.approve(
                        CONFIG.CUSTOM_TOKEN_CONTRACT, 
                        web3.utils.toWei('1000000', 'ether') // Approve large amount
                    ).send({ from: userAccount });
                    
                    showSuccess(`Approval successful! Transaction: ${approveTx.transactionHash}`);
                }

                // Perform the actual swap transaction
                // Note: Replace 'swapUSDTForTokens' with your actual swap function name
                showSuccess('Executing swap transaction...');
                
                try {
                    // This assumes you have a swap function in your custom token contract
                    // You'll need to replace this with your actual swap method
                    const swapTx = await customTokenContract.methods.swapUSDTForTokens(amountWei).send({ 
                        from: userAccount,
                        gas: 300000 // Adjust gas limit as needed
                    });
                    
                    hideError();
                    showSuccess(`Swap completed successfully! Transaction: ${swapTx.transactionHash}`);
                    updateBalances();
                    
                } catch (swapError) {
                    // If swap function doesn't exist or fails
                    console.error('Swap execution error:', swapError);
                    if (swapError.message.includes('swapUSDTForTokens')) {
                        showError('Swap function not implemented in contract. Please add the swap functionality to your smart contract.');
                    } else {
                        throw swapError; // Re-throw other errors
                    }
                }
                
                // Reset form
                fromAmountInput.value = '';
                toAmountInput.value = '';
                calculateSwapAmount();

            } catch (error) {
                console.error('Swap error:', error);
                showError('Swap failed: ' + error.message);
            } finally {
                swapBtn.disabled = false;
                swapBtn.textContent = 'Swap Tokens';
            }
        }

        // Utility functions
        function showError(message) {
            errorMessage.textContent = message;
            errorMessage.style.display = 'block';
            successMessage.style.display = 'none';
        }

        function showSuccess(message) {
            successMessage.textContent = message;
            successMessage.style.display = 'block';
            errorMessage.style.display = 'none';
        }

        function hideError() {
            errorMessage.style.display = 'none';
        }

        // Handle account changes
        if (window.ethereum) {
            window.ethereum.on('accountsChanged', (accounts) => {
                if (accounts.length === 0) {
                    // User disconnected wallet
                    userAccount = null;
                    connectWalletBtn.textContent = 'Connect Wallet';
                    connectWalletBtn.classList.remove('connected');
                    connectWalletBtn.disabled = false;
                    walletInfo.style.display = 'none';
                    swapBtn.disabled = true;
                    swapBtn.textContent = 'Connect Wallet to Swap';
                } else {
                    // Account changed
                    userAccount = accounts[0];
                    updateWalletUI();
                    updateBalances();
                }
            });

            window.ethereum.on('chainChanged', (chainId) => {
                if (chainId !== CONFIG.BSC_CHAIN_ID) {
                    showError('Please switch to Binance Smart Chain network');
                } else {
                    hideError();
                }
            });
        }
    </script>
</body>
</html>