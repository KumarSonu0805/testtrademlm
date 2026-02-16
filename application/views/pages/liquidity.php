<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Check PancakeSwap V3 Pool</title>
  <script src="https://cdn.jsdelivr.net/npm/web3@1.10.0/dist/web3.min.js"></script>
</head>
<body>
  <h2>Check PancakeSwap V3 Liquidity Pool</h2>
  <div>
    <label for="tokenAddress">Your Token Address:</label>
    <input type="text" id="tokenAddress" value="0x45e882831C9F815d013fF18FC20501366ff1a9df" style="width: 350px;">
  </div>
  <div style="margin-top: 10px;">
    <label for="network">Network:</label>
    <select id="network">
      <option value="bsc">BSC Mainnet</option>
      <option value="bsc-testnet">BSC Testnet</option>
    </select>
  </div>
  <div style="margin-top: 10px;">
    <button onclick="checkPool()">Check Pool</button>
    <button onclick="clearResults()">Clear Results</button>
  </div>
  <div style="margin-top: 10px;">
    <pre id="result" style="background-color: #f5f5f5; padding: 10px; border-radius: 5px;"></pre>
  </div>
  
  <script>
    // Network configurations
    const networks = {
      'bsc': {
        rpcUrl: 'https://bsc-dataseed1.binance.org/',
        factoryAddress: '0x1097053Fd2ea711dad45caCcc45EfF7548fCB362',
        usdtAddress: '0x55d398326f99059fF775485246999027B3197955'
      },
      'bsc-testnet': {
        rpcUrl: 'https://data-seed-prebsc-1-s1.binance.org:8545/',
        factoryAddress: '0x0BFbCF9fa4f9C56B0F40a671Ad40E0805A091865',
        usdtAddress: '0x337610d27c682E347C9cD60BD4b3b107C9d34dDd'
      }
    };

    // PancakeSwap V3 Factory ABI (minimal required for getPool)
    const factoryABI = [{
      "inputs": [
        { "internalType": "address", "name": "tokenA", "type": "address" },
        { "internalType": "address", "name": "tokenB", "type": "address" },
        { "internalType": "uint24", "name": "fee", "type": "uint24" }
      ],
      "name": "getPool",
      "outputs": [{ "internalType": "address", "name": "pool", "type": "address" }],
      "stateMutability": "view",
      "type": "function"
    }];

    // Common fee tiers for PancakeSwap V3
    const feeTiers = [100, 500, 2500, 10000];

    // Clear results
    function clearResults() {
      document.getElementById("result").innerText = "";
    }
    
    // Log message to results
    function logMessage(message) {
      document.getElementById("result").innerText += message + "\n";
    }

    // Check pool existence
    async function checkPool() {
      clearResults();
      
      const tokenAddress = document.getElementById("tokenAddress").value.trim();
      if (!tokenAddress || !tokenAddress.startsWith("0x") || tokenAddress.length !== 42) {
        logMessage("⚠️ Please enter a valid token address");
        return;
      }
      
      // Get selected network
      const networkId = document.getElementById("network").value;
      const network = networks[networkId];
      
      logMessage(`🔍 Checking pools for token: ${tokenAddress}`);
      logMessage(`🔗 Network: ${networkId.toUpperCase()}`);
      logMessage(`💱 Base token: USDT (${network.usdtAddress})`);
      logMessage("-----------------------------------");
      
      try {
        // Using HTTP provider instead of wallet provider for more reliability
        const web3 = new Web3(new Web3.providers.HttpProvider(network.rpcUrl));
        
        // Connect to factory contract
        const factory = new web3.eth.Contract(factoryABI, network.factoryAddress);
        
        let found = false;
        
        // Check all fee tiers
        for (let fee of feeTiers) {
          try {
            logMessage(`Checking fee tier ${fee/10000}%...`);
            
            const poolAddress = await factory.methods.getPool(
              network.usdtAddress, 
              tokenAddress, 
              fee
            ).call();
            
            if (poolAddress && poolAddress !== "0x0000000000000000000000000000000000000000") {
              logMessage(`✅ Pool found: ${poolAddress}`);
              found = true;
            } else {
              logMessage(`❌ No pool for ${fee/10000}% fee tier`);
            }
          } catch (err) {
            logMessage(`⚠️ Error checking ${fee/10000}% fee tier: ${err.message}`);
          }
          
          // Add some spacing between fee tiers
          logMessage("");
        }
        
        if (!found) {
          logMessage("❌ No pools found for any fee tier");
        } else {
          logMessage("✅ Pool check complete");
        }
      } catch (err) {
        logMessage(`⚠️ Connection error: ${err.message}`);
      }
    }
  </script>
</body>
</html>