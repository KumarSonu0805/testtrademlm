<!DOCTYPE html>
<html>
<head>
  <title>DXC Staking</title>
  <script src="https://cdn.jsdelivr.net/npm/web3@1.10.0/dist/web3.min.js"></script>
</head>
<body>
  <h2>Total Staked in Contract:</h2>
  <p id="totalStaked">Loading...</p>
  <button id="connectWalletBtn">Connect Wallet</button>

  <script>
    const contractAddress = "0x343a0D72F59f99Ee615ea0FC3930A223a4E5Aa61";
    const tokenAbi = [
	{
		"inputs": [
			{
				"internalType": "uint256",
				"name": "_amount",
				"type": "uint256"
			}
		],
		"name": "stake",
		"outputs": [],
		"stateMutability": "nonpayable",
		"type": "function"
	},
	{
		"inputs": [
			{
				"internalType": "address",
				"name": "_token",
				"type": "address"
			}
		],
		"stateMutability": "nonpayable",
		"type": "constructor"
	},
	{
		"inputs": [],
		"name": "unstake",
		"outputs": [],
		"stateMutability": "nonpayable",
		"type": "function"
	},
	{
		"inputs": [],
		"name": "getAllStakers",
		"outputs": [
			{
				"internalType": "address[]",
				"name": "",
				"type": "address[]"
			}
		],
		"stateMutability": "view",
		"type": "function"
	},
	{
		"inputs": [],
		"name": "getContractTokenBalance",
		"outputs": [
			{
				"internalType": "uint256",
				"name": "",
				"type": "uint256"
			}
		],
		"stateMutability": "view",
		"type": "function"
	},
	{
		"inputs": [
			{
				"internalType": "address",
				"name": "user",
				"type": "address"
			}
		],
		"name": "getStaked",
		"outputs": [
			{
				"internalType": "uint256",
				"name": "",
				"type": "uint256"
			}
		],
		"stateMutability": "view",
		"type": "function"
	},
	{
		"inputs": [
			{
				"internalType": "address",
				"name": "",
				"type": "address"
			}
		],
		"name": "stakes",
		"outputs": [
			{
				"internalType": "uint256",
				"name": "amount",
				"type": "uint256"
			},
			{
				"internalType": "uint256",
				"name": "timestamp",
				"type": "uint256"
			}
		],
		"stateMutability": "view",
		"type": "function"
	},
	{
		"inputs": [],
		"name": "token",
		"outputs": [
			{
				"internalType": "contract IBEP20",
				"name": "",
				"type": "address"
			}
		],
		"stateMutability": "view",
		"type": "function"
	}
];

    let web3, stakingContract, userAccount;

    window.addEventListener('load', async () => {
      if (window.ethereum) {
        web3 = new Web3(window.ethereum);

        document.getElementById('connectWalletBtn').addEventListener('click', async () => {
          try {
            const accounts = await ethereum.request({ method: 'eth_requestAccounts' });
            userAccount = accounts[0];
            stakingContract = new web3.eth.Contract(tokenAbi, contractAddress);
            await updateTotalStaked();
          } catch (err) {
            console.error("Wallet connection failed", err);
          }
        });
      } else {
        alert("Install MetaMask");
      }
    });

    async function updateTotalStaked() {
      try {
        const balance = await stakingContract.methods.getContractTokenBalance().call();
        document.getElementById('totalStaked').innerText = 
          web3.utils.fromWei(balance, 'ether') + ' tokens';
      } catch (err) {
        document.getElementById('totalStaked').innerText = "Error fetching balance";
        console.error("Error fetching total staked:", err);
      }
    }

    </script>
</body>
</html>
