
            <div class="col-12">
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
            </div>

    <script>
	
		$(document).ready(function(e) {

            var url="<?= base_url('wallet/memberwallet/?type=data'); ?>";
            var columns=[
                    { 
                        title: "Sl.No.", 
                        field: "serial", 
                        hozAlign: "center",
                        width: 70, 
                        type: "auto"
                    },
                    { title: "Member ID", field: "username" },
                    { title: "Member Name", field: "name" },
                    { title: "Sponsor ID", field: "ref" },
                    { title: "Sponsor Name", field: "refname" },
                    { title: "Total Income", field: "income" },
                    { title: "Total Withdrawal", field: "withdrawal" },
                    { title: "Wallet Balance", field: "balance" }
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
		
		function validate(){
			if(!confirm("Confirm Activate this Member?")){
				return false;
			}
		}
	</script>
    
    	
