
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
            
            var url="<?= base_url('wallet/withdrawalhistory/?type=data'); ?>";
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
                    { title: "Amount", field: "amount" ,
                        formatter: function(cell){
                            let amount = Number(cell.getValue());
                            amount=amount==Math.round(amount)?Math.round(amount):amount.toFixed(8);
                            return '$'+amount
                        }
                    },
                    { title: "Deduction", field: "deduction_amount" ,
                        formatter: function(cell){
                            let amount = Number(cell.getValue());
                            amount=amount==Math.round(amount)?Math.round(amount):amount.toFixed(8);
                            return '$'+amount
                        }
                    },
                    { title: "Payable Amount", field: "payable_amount" ,
                        formatter: function(cell){
                            let amount = Number(cell.getValue());
                            amount=amount==Math.round(amount)?Math.round(amount):amount.toFixed(8);
                            return '$'+amount
                        }
                    },
                    { 
                        title: "Status", 
                        field: "status",
                        formatter: function(cell){
                            let status = cell.getValue();
                            let html='';
                            if(status==0){
                                html='<span class="text-primary">Request Pending</span>';   
                            }
                            else if(status==1){
                                html='<span class="text-success">Approved</span>';   
                            }
                            else if(status==2){
                                html='<span class="text-danger">Rejected</span>';   
                            }
                            return html;
                        }
                    },
                    /*{ 
                       title: "Action", 
                       field: "id", 
                       width: 200, 
                       formatter: function(cell) {
                            let id = cell.getValue(); // Get full row data
                           let button=`<button type="button" class="btn btn-sm btn-success approve" value="${id}">Approve</button> `;
                           button+=`<button type="button" class="btn btn-sm btn-danger reject" value="${id}">Reject</button>`;
                           return button;
                       } 
                    }*/
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
		}
	</script>
    
    	
