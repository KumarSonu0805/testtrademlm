// JavaScript Document
//const contractAddress = "0xD544B8034E810ae37196090eBe8cB7b38AbBF7cd";
//const contractAddress = "0x3B16D5010fcd2a20d16b26cE63305F41EcE83AE6"; //CLIENT
//const contractAddress = "0x343a0D72F59f99Ee615ea0FC3930A223a4E5Aa61";
const contractAddress = "0x1550c1Cc1E5cD69c5F9C2bFCF257A2B2bB30E844";
const tokenAddress = "0x45e882831C9F815d013fF18FC20501366ff1a9df";
const abi = [
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

let web3;
let userAccount;
let stakingContract;
let tokenDecimals = 18;
let tokenSymbol = "DXC";

window.addEventListener('load', async () => {
  if (typeof window.ethereum !== 'undefined') {
    web3 = new Web3(window.ethereum);

    // Check if already connected
    const accounts = await window.ethereum.request({ method: 'eth_accounts' });
    if (accounts.length > 0) {
      userAccount = accounts[0];
      await onWalletConnected();
    }

    // Set up connect button
    document.getElementById('connectWalletBtn').addEventListener('click', async () => {
      try {
        const accounts = await window.ethereum.request({ method: 'eth_requestAccounts' });
        userAccount = accounts[0];
        await onWalletConnected();
      } catch (error) {
        console.error("User rejected connection:", error);
      }
    });

    // Optional: Detect account change
    window.ethereum.on('accountsChanged', async (accounts) => {
      userAccount = accounts[0];
      await onWalletConnected();
    });
  } else {
    alert("Web3 wallet not detected. Please install MetaMask or use Trust Wallet browser.");
  }
});

// Separate function to avoid repetition
async function onWalletConnected() {
  document.getElementById('wallet').innerText = 'Connected: ' + userAccount;
  document.getElementById('w').value = userAccount;

  stakingContract = new web3.eth.Contract(abi, contractAddress);

  await loadTokenInfo();
  //await updatePendingReward();
  await getUserStake(); // your custom function to load staked amount

  // Enable action buttons
//  ["stakeBtn", "unstakeBtn", "claimRewardBtn"].forEach(id => {
//    document.getElementById(id).disabled = false;
//  });
  ["stakeBtn", "unstakeBtn"].forEach(id => {
    document.getElementById(id).disabled = false;
  });
    document.querySelectorAll(".stake-btn:disabled").forEach(btn => btn.disabled = false);
  //setInterval(updatePendingReward, 5000);
}
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
      document.getElementById("save-stake").click();
  } catch (err) {
    console.error("Staking error:", err);
    alert("Error during staking.");
  }
});

document.getElementById("unstakeBtn").addEventListener("click", async () => {
  try {
    await stakingContract.methods.unstake().send({ from: userAccount });
    alert("Unstaked successfully.");
      document.getElementById("save-unstake").click();
  } catch (err) {
    console.error("Unstaking error:", err);
    alert("Error during unstaking.");
  }
});

//document.getElementById("claimRewardBtn").addEventListener("click", async () => {
//  try {
//    await stakingContract.methods.claimReward().send({ from: userAccount });
//    alert("Reward claimed.");
//  } catch (err) {
//    console.error("Claim reward error:", err);
//    alert("Error during claiming reward.");
//  }
//});

async function updatePendingReward() {
  if (!stakingContract || !userAccount) return;
  try {
    const reward = await stakingContract.methods.getPendingReward(userAccount).call();
    const readable = web3.utils.fromWei(reward, "ether");
    document.getElementById("pendingReward").innerText = `Pending Reward: ${readable} ${tokenSymbol}`;
    document.getElementById("pendingReward").innerText = `Pending Reward: ${readable} ${tokenSymbol}`;
  } catch (err) {
    console.error("Reward update error:", err);
  }
}

async function getUserStake(address) {
//  const amount = await stakingContract.methods.stakedAmount(address).call();
//  const readable = web3.utils.fromWei(amount, "ether");
//  console.log(`${address} has staked: ${readable} TOKEN`);
//  return readable;
    if (!address) return;
    
    const amountStaked = await stakingContract.methods.getStaked(address).call();
    console.log("Amount staked:", amountStaked);
  const readable = web3.utils.fromWei(amountStaked, "ether");
  console.log(`${address} has staked: ${readable} TOKEN`);
    //document.getElementById("userStake").innerText = `${address} has staked: ${readable} ${tokenSymbol}`;
    document.getElementById("userStake").innerText = `User Stake: ${readable} ${tokenSymbol}`;
}
if(document.getElementById("loadStakersBtn")){
    document.getElementById("loadStakersBtn").addEventListener("click", async () => {
      try {
        const stakers = await stakingContract.methods.getAllStakers().call();
        const listEl = document.getElementById("stakerList");
        listEl.innerHTML = ""; // Clear existing

        if (stakers.length === 0) {
          listEl.innerHTML = "<li>No stakers yet.</li>";
          return;
        }

        stakers.forEach(addr => {
          const li = document.createElement("li");
          li.textContent = addr;
          listEl.appendChild(li);
        });
      } catch (err) {
        console.error("Error loading stakers:", err);
        alert("Failed to load stakers. See console for details.");
      }
    });
}
