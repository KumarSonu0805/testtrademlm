<style>
    .card-title{
        font-size: 0.8rem;
    }
</style>


                    <div class="row d-none">
                        <div class="col-md-4">
                            <div class="card  card-outline">
                                <div class="card-body box-profile">
                                    <h3>History</h3>
                                    <ul id="history"></ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <script src="https://cdn.jsdelivr.net/npm/web3@1.10.0/dist/web3.min.js"></script>
                    <?php
                        if(WORK_ENV=='development'){
                    ?>
                    <script src="<?= file_url('test/config-new.js'); ?>"></script> 
                    <?php
                        }
                        else{
                    ?>
                    <script src="<?= file_url('includes/custom/config.js'); ?>"></script> 
                    <?php
                        }
                    ?>   

                    <script>
                        $(document).ready(function(){
                            $(function () {
                                $('[data-toggle="tooltip"]').tooltip();
                            });
                        });
                        const web3 = new Web3(window.ethereum);
                        const account = localStorage.getItem('wallet');
                        const staking = new web3.eth.Contract(stakingABI, stakingAddress);

                        async function loadHistory() {
                          const events = await staking.getPastEvents('allEvents', {
                            filter: { user: account },
                            fromBlock: 0,
                            toBlock: 'latest'
                          });
                          const historyElem = document.getElementById('history');
                          events.forEach(e => {
                            const li = document.createElement('li');
                            li.textContent = `${e.event} - Amount: ${web3.utils.fromWei(e.returnValues._amount || 0)} - Block: ${e.blockNumber}`;
                            historyElem.appendChild(li);
                          });
                        }

                        loadHistory();
                        
                        const poolAbi = [{
                          "inputs": [],
                          "name": "slot0",
                          "outputs": [
                            { "internalType": "uint160", "name": "sqrtPriceX96", "type": "uint160" },
                            { "internalType": "int24", "name": "tick", "type": "int24" },
                            { "internalType": "uint16", "name": "observationIndex", "type": "uint16" },
                            { "internalType": "uint16", "name": "observationCardinality", "type": "uint16" },
                            { "internalType": "uint16", "name": "observationCardinalityNext", "type": "uint16" },
                            { "internalType": "uint8", "name": "feeProtocol", "type": "uint8" },
                            { "internalType": "bool", "name": "unlocked", "type": "bool" }
                          ],
                          "stateMutability": "view",
                          "type": "function"
                        }];

                        const poolAddress = "0xd793764dc7968715661c9682fff67edb6de1fdac";

                        const contract = new web3.eth.Contract(poolAbi, poolAddress);

                        async function getV3Price() {
                            const slot0 = await contract.methods.slot0().call();
                            const sqrtPriceX96 = BigInt(slot0.sqrtPriceX96);
                            const price = Number(sqrtPriceX96 ** 2n) / (2 ** 192);
                            var rate=price.toFixed(8);
                            var avl=$('#contractBalance').text();
                            avl=avl.replace(' DXC','');
                            avl*=rate;
                            avl=avl.toFixed(8)+' USDT';
                            $('#tokenValue').text(avl);

                            console.log("Approx price:", price.toFixed(8));
                            console.log("Approx price:", avl);
                        }
                    </script>