// JavaScript Document
const bscMainnetParams = {
  chainId: '0x38',
  chainName: 'BNB Smart Chain Mainnet',
  nativeCurrency: { name: 'BNB', symbol: 'BNB', decimals: 18 },
  rpcUrls: ['https://bsc-dataseed.binance.org/'],
  blockExplorerUrls: ['https://bscscan.com']
};

async function switchToBSC() {
  if (!window.ethereum) { 
      alert('To Directly Transfer Amount a Web3 wallet (like MetaMask or Trust Wallet or Token Pocket or SafePal) is required. Please install or open it.'); 
      return false; 
  }
  try {
    await window.ethereum.request({
      method: 'wallet_switchEthereumChain',
      params: [{ chainId: bscMainnetParams.chainId }]
    });
    return true;
  } catch (switchError) {
    if (switchError.code === 4902) {
      try {
        await window.ethereum.request({
          method: 'wallet_addEthereumChain',
          params: [bscMainnetParams]
        });
        return true;
      } catch (addError) { return false; }
    }
    return false;
  }
}