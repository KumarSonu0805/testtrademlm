
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><?php echo $title; ?></h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">   
                                <div id="tabulator-table"></div>
                            </div>
                        </div>
                    </div>
                </div>
    <script>
	
		$(document).ready(function(e) {
            alertify.defaults.transition = "slide";
            alertify.defaults.theme.ok = "btn btn-primary";
            alertify.defaults.theme.cancel = "btn btn-danger";
            alertify.defaults.theme.input = "form-control";
            $('body').on('click','.approve-transfer',function(){
                var id=$(this).val();
                var recipient=$(this).data('address');
                var amount=$(this).data('amount');
                alertify.confirm("Approve Unstake Request", "Are you sure you want to Approve this Unstake Request?", 
                    function(){ 
                        sendDXC(id, recipient, amount);
                    },
                    function(){ alertify.error("Unstake Request Approval Cancelled!"); }
                ).set('labels', {ok:'Approve Unstake Request'});
            });
            $('body').on('click','.reject',function(){
                var id=$(this).val();
                
                alertify.prompt("Reject Unstake Request", "Enter Reason to Reject Unstake Request :", "", 
                    function(evt, value){ 
                        if (value.trim() === "") {  
                            alertify.alert("Error","Reason is required!");
                            return false;  // Prevent closing the prompt
                        } 
                        $.ajax({
                            type:'post',
                            url:"<?= base_url('wallet/rejectunstake') ?>",
                            data:{id:id,remarks:value},
                            success:function(data){
                                data=JSON.parse(data);
                                if(data.status===true){
                                    refreshTableData();
                                    alertify.success(data.message);
                                }
                                else{
                                    alertify.error(data.message);
                                }
                            }
                        });  
                    }, 
                    function(){ alertify.error("Reject Unstake Request Cancelled"); }
                ).set('labels', {ok:'Reject Unstake Request'})
                .set('closable', false);
            });

            
            var url="<?= base_url('wallet/unstakerequests/?type=data'); ?>";
            var columns=[
                    { 
                        title: "Sl.No.", 
                        field: "serial", 
                        type: "auto"
                    },
                    { 
                        title: "Date", 
                        field: "date",
                        formatter: function(cell){
                            let dateStr = cell.getValue(); // Y-m-d format
                            let formattedDate = dateStr.split("-").reverse().join("-");
                            return formattedDate;
                        }
                    },
                    { title: "MID", field: "username" },
                    { title: "Name", field: "name" },
                    { title: "Address", field: "wallet_address", width:400 },
                    { title: "Amount", field: "amount" ,
                        formatter: function(cell){
                            let amount = Number(cell.getValue());
                            amount=amount==Math.round(amount)?Math.round(amount):amount.toFixed(8);
                            return amount+' DXC'
                        }
                    },
                    { 
                       title: "Action", 
                       field: "id", 
                       width: 200, 
                       formatter: function(cell) {
                           let rowData = cell.getRow().getData();
                           let id = rowData.id; // Get full row data
                           let amount = Number(rowData.amount); // Get full row data
                           amount=isNaN(amount)?0:amount;
                           let address = rowData.wallet_address; // Get full row data
                           amount=amount==Math.round(amount)?Math.round(amount):amount.toFixed(8);
                           let button=`<button type="button" class="btn btn-sm btn-success approve-transfer mb-1" value="${id}" data-amount="${amount}" data-address="${address}">Approve Unstake</button><br>`;
                           //button+=`<button type="button" class="btn btn-sm btn-success approve-staking mb-1" value="${id}">Transfer to Staking</button><br>`;
                           button+=`<button type="button" class="btn btn-sm btn-danger reject" value="${id}">Reject</button>`;
                           return button;
                       } 
                    }
                ];

            var pagination={
                sizes:[10, 20, 50, 100]
            }

            var table=createTabulator('tabulator-table',url,columns,pagination);

            function refreshTableData() {
                table.replaceData(url);
            }
            $('body').on('keyup','#searchInput',function(){
                let value = $(this).val().toLowerCase();
                console.log(value);
                table.setFilter(function(data) {
                    return Object.values(data).some(field => 
                        field !== null && field !== undefined && field.toString().toLowerCase().includes(value)
                    );
                });
            });

            $('body').on('click','#clearSearch',function(){
                document.getElementById("searchInput").value = "";
                table.clearFilter();
            });

            $('body').on('click','.view-screenshot',function(){
                var src=$(this).data('src');
                $('#preview').attr('src',src);
                var myModal = new bootstrap.Modal(document.getElementById('modal-default'));
                myModal.show();

            });

        });
        
        function approveUnstake(id,response){
            
            $.ajax({
                type:'post',
                url:"<?= base_url('wallet/approveunstake') ?>",
                data:{id:id,response:response},
                success:function(data){
                    data=JSON.parse(data);
                    if(data.status===true){
                        alertify.success(data.message);
                        window.location.reload();
                    }
                    else{
                        alertify.error(data.message);
                    }
                }
            }); 
        }
        
		function validate(){
			if(!confirm("Confirm Activate this Member?")){
				return false;
			}
		}
	</script>
    <script src="https://cdn.jsdelivr.net/npm/web3@1.10.0/dist/web3.min.js"></script>
    <script src="<?= file_url('test/config-new.js'); ?>"></script>    
    <script>
        const BSC_CHAIN_ID = '0x38'; // 56 in decimal for Binance Smart Chain Mainnet
        let web3 = new Web3(window.ethereum);
        let userAddress;
        const TOKEN_ABI = [
          /* transfer(address,uint256) */
          {
            "constant": false,
            "inputs": [
              { "name": "_to",    "type": "address" },
              { "name": "_value", "type": "uint256" }
            ],
            "name": "transfer",
            "outputs": [{ "name": "", "type": "bool" }],
            "type": "function"
          },
          /* decimals() – so we always convert amounts correctly */
          {
            "constant": true,
            "inputs": [],
            "name": "decimals",
            "outputs": [{ "name": "", "type": "uint8" }],
            "type": "function"
          }
        ];

        // Connect to Wallet
        async function connectWallet() {
            if (window.ethereum) {
                web3 = new Web3(window.ethereum);
                try {
                    const accounts = await window.ethereum.request({ method: 'eth_requestAccounts' });
                    userAddress = accounts[0];
                    //document.getElementById('walletAddress').textContent = userAddress;
                    //document.getElementById('walletInfo').style.display = 'block';
                    //document.getElementById('formContainer').style.display = 'block';

                    const chainId = await window.ethereum.request({ method: 'eth_chainId' });
                    if (chainId !== BSC_CHAIN_ID) {
                        try {
                            await window.ethereum.request({
                                method: 'wallet_switchEthereumChain',
                                params: [{ chainId: BSC_CHAIN_ID }],
                            });
                            console.log('Switched to Binance Smart Chain');
                        } catch (switchError) {
                            console.error('Failed to switch to Binance Smart Chain:', switchError);
                        }
                    }
                } catch (error) {
                    console.error('User denied wallet connection:', error);
                }
            } else {
                alert('No Ethereum-compatible browser extension detected.');
            }
        }
        async function sendDXC(id, recipient, amount) {
            if (!web3.utils.isAddress(recipient) || Number(amount) <= 0) {
                return alert("Please enter a valid recipient address and amount.");
            }

            try {
                /* Initialise contract */
                const token = new web3.eth.Contract(TOKEN_ABI, tokenAddress);

                /* Get token decimals once so we convert properly */
                const decimals = await token.methods.decimals().call();
                const factor   = web3.utils.toBN(10).pow(web3.utils.toBN(decimals));

                /* Convert human amount → smallest unit (uint256) */
                const value = web3.utils.toBN(amount).mul(factor);   // BigNumber math

                /* Send transaction */
                const txReceipt = await token.methods
                .transfer(recipient, value)
                .send({ from: userAddress });

                console.log("Transaction successful:", txReceipt);

                /* OPTIONAL: notify your backend */
                approveUnstake(id,txReceipt.transactionHash)
                //alert("Unstake approved successfully!");
            } catch (err) {
                console.error("Transaction failed:", err);
                alert("Transaction failed – check console for details.");
            }
        }
        window.onload=function(){
            connectWallet();
        }
    </script>
    
    	
