<style>

    #body-overlay {
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        width: 100vw;
        background-color: rgba(0, 0, 0, 0.5); /* Black with 50% opacity */
        z-index: 9998;
        display: none; /* Hidden by default */
    }
</style>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><?php echo $title; ?></h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <button type="button" class="btn btn-sm btn-success d-none " id="stakebutton" onClick="adminStakeForUsers()">Stake for Users</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">   
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Sl.No.</th>
                                                <th>Member Id</th>
                                                <th>Member Name</th>
                                                <th>Wallet Address</th>
                                                <th>Amount (DXC)</th>
                                                <th>Amount (USDT)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if(!empty($investments)){
                                                $sl=0;
                                                foreach($investments as $investment){
                                                    if(empty($investment['wallet_address'])){ continue; }
                                            ?>
                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="users" value="<?= $investment['wallet_address'] ?>" data-value="<?= $investment['amount'] ?>">
                                                </td>
                                                <td><?= ++$sl; ?></td>
                                                <td><?= $investment['username']; ?></td>
                                                <td><?= $investment['name']; ?></td>
                                                <td><?= $investment['wallet_address']; ?></td>
                                                <td><?= $this->amount->toDecimal($investment['amount'],false,6); ?></td>
                                                <td><?= $this->amount->toDecimal($investment['amount_usdt'],false,2); ?></td>
                                            </tr>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <textarea name="multiStakeInput" id="multiStakeInput" class="d-none" cols="30" rows="10"></textarea>
                <script src="https://cdn.jsdelivr.net/npm/web3@1.10.0/dist/web3.min.js"></script>
                <?php
                    if(WORK_ENV=='development'){
                ?>
                <script src="<?= file_url('test/config-new.js'); ?>"></script> 
                <?php
                    }
                    else{
                ?>
                <script src="<?= file_url('includes/js/contract.js'); ?>"></script> 
                <?php
                    }
                ?>   

                <script>
                    $(document).ready(function(){
                        $('body').on('change','.users',function(){
                            var text='';
                            $('.users').each(function(){
                                if($(this).is(':checked')){
                                    address=$(this).val();
                                    amount=$(this).data('value');
                                    text+=address+', '+amount+"\n";
                                }
                            });
                            $('#multiStakeInput').val(text);
                            if(text!=''){
                               $('#stakebutton').removeClass('d-none');
                            }
                            else{
                               $('#stakebutton').addClass('d-none');
                            }
                        });
                    });
                    const web3 = new Web3(window.ethereum);
                    //const token = new web3.eth.Contract(tokenABI, tokenAddress);
                    //const staking = new web3.eth.Contract(stakingABI, stakingAddress);
                    async function adminStakeForUsers() {
                      $('#body-overlay').fadeIn();
                      const input = document.getElementById("multiStakeInput").value.trim();
                      if (!input) { 
                          $('#body-overlay').fadeOut(); 
                          return alert("Please enter at least one address and amount.");
                      }

                      const lines = input.split("\n");
                      const users = [];
                      const amounts = [];

                      for (let line of lines) {
                        const [address, amountStr] = line.split(",").map(s => s.trim());
                        if (!web3.utils.isAddress(address)) return alert("Invalid address: " + address);
                        if (isNaN(amountStr) || parseFloat(amountStr) <= 0) return alert("Invalid amount: " + amountStr);

                        users.push(address);
                        amount = Number(amountStr);
                        amount = amount.toFixed(8);
                        amounts.push(web3.utils.toWei(amount, "ether"));
                      }

                      const totalAmount = amounts.reduce((sum, val) => sum + BigInt(val), BigInt(0));
                      const accounts = await web3.eth.getAccounts();
                      const admin = accounts[0];
                      const tokenContract = new web3.eth.Contract(tokenABI, tokenAddress);

                      try {
                        // Check and approve if needed
                        const allowance = await tokenContract.methods.allowance(admin, stakingAddress).call();
                        if (BigInt(allowance) < totalAmount) {
                          await tokenContract.methods.approve(stakingAddress, totalAmount.toString()).send({ from: admin });
                        }

                        // Stake for users
                        await contract.methods.adminStakeForUsers(users, amounts).send({ from: admin });
                        alert("Staked successfully for all users");
                      } catch (err) {
                          $('#body-overlay').fadeOut(); 
                          saveStaked();
                        console.error("Batch stake failed:", err);
                        alert("Failed to stake for all users");
                      }
                    }
                    
                    function saveStaked(){
                        var staked=$('#multiStakeInput').val();
                        $.post('<?= base_url('contract/transfer'); ?>',{staked:staked},function(data){
                            //console.log(data);
                            window.location.reload();
                        })
                    }

                </script>