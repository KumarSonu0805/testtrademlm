// JavaScript Document
let web3;
let userAccount;

/*-------------- connect wallet------------------------*/
window.addEventListener('load', async () => {
  if (typeof window.ethereum !== 'undefined') {
    web3 = new Web3(window.ethereum);

    document.getElementById('connectBtn').addEventListener('click', async () => {
      try {
        const accounts = await window.ethereum.request({ method: 'eth_requestAccounts' });
        userAccount = accounts[0];
        document.getElementById('walletAddress').innerText = 'Connected: ' + userAccount;
        console.log("Connected account:", userAccount);
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

/*-------------- connect wallet------------------------*/
/*-------------- load token------------------------*/
let token, tokenSymbol, tokenDecimals;
const stakingContractAddress = "0xYourStakingContractAddress"; // Replace this

document.getElementById('loadToken').addEventListener('click', async () => {
  const tokenAddress = document.getElementById('tokenAddress').value.trim();

  if (!web3.utils.isAddress(tokenAddress)) {
    alert("Invalid token address");
    return;
  }

  token = new web3.eth.Contract(ERC20_ABI, tokenAddress);

  try {
    tokenSymbol = await token.methods.symbol().call();
    tokenDecimals = await token.methods.decimals().call();
    const balance = await token.methods.balanceOf(userAccount).call();
    const formattedBalance = balance / (10 ** tokenDecimals);

    document.getElementById('tokenInfo').innerText = `${tokenSymbol} Balance: ${formattedBalance}`;
  } catch (e) {
    console.error("Error loading token:", e);
    alert("Failed to load token details.");
  }
});

document.getElementById('approveToken').addEventListener('click', async () => {
  if (!token) return alert("Load token first");

  const rawAmount = document.getElementById('stakeAmount').value;
  if (!rawAmount || isNaN(rawAmount)) return alert("Enter a valid amount");

  const amount = BigInt(rawAmount * (10 ** tokenDecimals)); // Convert to smallest unit

  try {
    const tx = await token.methods.approve(stakingContractAddress, amount.toString())
      .send({ from: userAccount });
    console.log("Approval TX:", tx);
    alert("Token approved successfully.");
  } catch (err) {
    console.error("Approval error:", err);
    alert("Approval failed.");
  }
});

/*-------------- load token------------------------*/

/*-------------- stake token------------------------*/
let stakingContract;

window.addEventListener('load', () => {
  stakingContract = new web3.eth.Contract(STAKING_ABI, stakingContractAddress);

  // Load staked amount on connect
  if (userAccount) updateStakedAmount();
});

async function updateStakedAmount() {
  if (!stakingContract || !userAccount) return;

  try {
    const result = await stakingContract.methods.getStaked(userAccount).call();
    const formatted = result / (10 ** tokenDecimals);
    document.getElementById('stakedAmount').innerText = `Staked: ${formatted} ${tokenSymbol}`;
  } catch (err) {
    console.error("Failed to fetch staked amount:", err);
  }
}

document.getElementById('stakeToken').addEventListener('click', async () => {
  const rawAmount = document.getElementById('stakeAmount').value;
  if (!rawAmount || isNaN(rawAmount)) return alert("Enter a valid amount");

  const amount = BigInt(rawAmount * (10 ** tokenDecimals));

  try {
    const tx = await stakingContract.methods.stake(amount.toString()).send({ from: userAccount });
    console.log("Stake TX:", tx);
    alert("Staked successfully");
    updateStakedAmount();
  } catch (err) {
    console.error("Staking failed:", err);
    alert("Staking failed");
  }
});

document.getElementById('unstakeToken').addEventListener('click', async () => {
  try {
    const tx = await stakingContract.methods.unstake().send({ from: userAccount });
    console.log("Unstake TX:", tx);
    alert("Unstaked successfully");
    updateStakedAmount();
  } catch (err) {
    console.error("Unstaking failed:", err);
    alert("Unstaking failed");
  }
});

/*-------------- stake token------------------------*/
