// JavaScript Document
const contractAddress = "0xD544B8034E810ae37196090eBe8cB7b38AbBF7cd";
const tokenAddress = "0x45e882831C9F815d013fF18FC20501366ff1a9df";
const abi = [
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
		"name": "claimReward",
		"outputs": [],
		"stateMutability": "nonpayable",
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
		"name": "getPendingReward",
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
		"inputs": [],
		"name": "rewardRatePerSecond",
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
	},
	{
		"inputs": [],
		"name": "unstake",
		"outputs": [],
		"stateMutability": "nonpayable",
		"type": "function"
	}
];

let web3;
let userAccount;
let stakingContract;
let tokenDecimals = 18;
let tokenSymbol = "DXC";
//window.addEventListener("DOMContentLoaded", () => {
//    document.getElementById("connectWalletBtn").addEventListener("click", async () => {
//      if (window.ethereum) {
//        web3 = new Web3(window.ethereum);
//        try {
//          await ethereum.request({ method: "eth_requestAccounts" });
//          const accounts = await web3.eth.getAccounts();
//          userAccount = accounts[0];
//          document.getElementById("wallet").innerText = userAccount;
//
//          stakingContract = new web3.eth.Contract(abi, contractAddress);
//
//          await loadTokenInfo();
//          setInterval(updatePendingReward, 5000);
//
//          // Enable action buttons
//          ["stakeBtn", "unstakeBtn", "claimRewardBtn"].forEach(id => {
//            document.getElementById(id).disabled = false;
//          });
//
//        } catch (err) {
//          console.error("Wallet connection failed:", err);
//          alert("Failed to connect wallet.");
//        }
//      } else {
//        alert("Please use a Web3-enabled browser like MetaMask or Trust Wallet.");
//      }
//    });
//});

window.addEventListener('load', async () => {
  if (typeof window.ethereum !== 'undefined') {
    web3 = new Web3(window.ethereum);

    document.getElementById('connectWalletBtn').addEventListener('click', async () => {
      try {
        const accounts = await window.ethereum.request({ method: 'eth_requestAccounts' });
        userAccount = accounts[0];
        document.getElementById('wallet').innerText = 'Connected: ' + userAccount;
        console.log("Connected account:", userAccount);
          stakingContract = new web3.eth.Contract(abi, contractAddress);

          await loadTokenInfo();
          setInterval(updatePendingReward, 5000);

          // Enable action buttons
          ["stakeBtn", "unstakeBtn", "claimRewardBtn"].forEach(id => {
            document.getElementById(id).disabled = false;
          });
      } catch (error) {
        console.error("User rejected connection:", error);
      }
    });

    // Optional: Detect account change
    window.ethereum.on('accountsChanged', function (accounts) {
      userAccount = accounts[0];
      document.getElementById('walletAddress').innerText = 'Changed: ' + userAccount;
    });
  } else {
    alert("Web3 wallet not detected. Please install MetaMask or use Trust Wallet browser.");
  }
});

async function loadTokenInfo() {
  const token = new web3.eth.Contract([
  // balanceOf
  {
    "constant":true,"inputs":[{"name":"_owner","type":"address"}],
    "name":"balanceOf","outputs":[{"name":"balance","type":"uint256"}],
    "type":"function"
  },
  // decimals
  {
    "constant":true,"inputs":[],"name":"decimals",
    "outputs":[{"name":"","type":"uint8"}],
    "type":"function"
  },
  // symbol
  {
    "constant":true,"inputs":[],"name":"symbol",
    "outputs":[{"name":"","type":"string"}],
    "type":"function"
  },
  // approve
  {
    "constant":false,
    "inputs":[{"name":"_spender","type":"address"},{"name":"_value","type":"uint256"}],
    "name":"approve",
    "outputs":[{"name":"success","type":"bool"}],
    "type":"function"
  },
  // allowance
  {
    "constant":true,
    "inputs":[{"name":"_owner","type":"address"},{"name":"_spender","type":"address"}],
    "name":"allowance",
    "outputs":[{"name":"remaining","type":"uint256"}],
    "type":"function"
  }
], tokenAddress);

  try {
    tokenSymbol = await token.methods.symbol().call();
    tokenDecimals = await token.methods.decimals().call();
    const balance = await token.methods.balanceOf(userAccount).call();
    const formattedBalance = balance / (10 ** tokenDecimals);

    document.getElementById('tokenInfo').innerText = `${tokenSymbol} Balance: ${formattedBalance}`;
  } catch (err) {
    console.error("Failed to load token info:", err);
  }
}

document.getElementById("stakeBtn").addEventListener("click", async () => {
  const amount = document.getElementById("stakeAmount").value;
  const value = web3.utils.toWei(amount, "ether");

  const token = new web3.eth.Contract([
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
  ], tokenAddress);

  try {
    await token.methods.approve(contractAddress, value).send({ from: userAccount });
    await stakingContract.methods.stake(value).send({ from: userAccount });
    alert("Staked successfully.");
  } catch (err) {
    console.error("Staking error:", err);
    alert("Error during staking.");
  }
});

document.getElementById("unstakeBtn").addEventListener("click", async () => {
  try {
    await stakingContract.methods.unstake().send({ from: userAccount });
    alert("Unstaked successfully.");
  } catch (err) {
    console.error("Unstaking error:", err);
    alert("Error during unstaking.");
  }
});

document.getElementById("claimRewardBtn").addEventListener("click", async () => {
  try {
    await stakingContract.methods.claimReward().send({ from: userAccount });
    alert("Reward claimed.");
  } catch (err) {
    console.error("Claim reward error:", err);
    alert("Error during claiming reward.");
  }
});

async function updatePendingReward() {
  if (!stakingContract || !userAccount) return;
  try {
    const reward = await stakingContract.methods.getPendingReward(userAccount).call();
    const readable = web3.utils.fromWei(reward, "ether");
    document.getElementById("pendingReward").innerText = `Pending Reward: ${readable} ${tokenSymbol}`;
  } catch (err) {
    console.error("Reward update error:", err);
  }
}
