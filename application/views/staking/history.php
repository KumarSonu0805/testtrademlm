
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

            
            var url="<?= base_url('staking/history/?type=data'); ?>";
            var columns=[
                    { 
                        title: "Sl.No.", 
                        field: "serial", 
                        type: "auto"
                    },
                    { title: "Date", field: "date" },
                    { title: "Amount", field: "amount" , width:140,
                        formatter: function(cell){
                            let amount = Number(cell.getValue());
                            amount=amount==Math.round(amount)?Math.round(amount):amount.toFixed(8);
                            return amount+' DXC'
                        }
                    },
                    { title: "Type", field: "type",
                     formatter: function(cell){
                            let type = cell.getValue();
                            if(type=='Stake'){
                                type='<span class="text-success">Stake</span>';  
                            }
                            else{
                                type='<span class="text-danger">Unstake</span>';  
                            }
                            return type;
                        }
                    },
                    { title: "Timestamp", field: "timestamp" }
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

        });
		
	</script>
    
    	
