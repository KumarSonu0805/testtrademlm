
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

            
            var url="<?= base_url('income/getincome/?type=royaltyincome'); ?>";
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
                    { title: "Member ID", field: "username" },
                    { title: "Name", field: "name" },
                    { title: "Amount", field: "amount" }
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
    
    	
