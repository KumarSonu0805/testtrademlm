
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
<div class="modal fade" id="modal-default" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Transaction Screenshot</h4>
        </div>
        <div class="modal-body">
          <img src="" alt="img" class="img-fluid" id="preview">
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-danger" id="close-modal" data-dismiss="modal">Close</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
    <script>
	   var myModal;
		$(document).ready(function(e) {
            alertify.defaults.transition = "slide";
            alertify.defaults.theme.ok = "btn btn-primary";
            alertify.defaults.theme.cancel = "btn btn-danger";
            alertify.defaults.theme.input = "form-control";
            $('body').on('click','.approve',function(){
                var id=$(this).val();
                alertify.confirm("Approve Deposit Request", "Are you sure you want to Approve this Deposit Request?", 
                    function(){ 
                        $.ajax({
                            type:'post',
                            url:"<?= base_url('wallet/approvefundrequest') ?>",
                            data:{id:id},
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
                    function(){ alertify.error("Deposit Request Approval Cancelled!"); }
                ).set('labels', {ok:'Approve Deposit Request'});
            });
            $('body').on('click','.reject',function(){
                var id=$(this).val();
                
                alertify.prompt("Reject Deposit Request", "Enter Reason to Reject Deposit Request :", "", 
                    function(evt, value){ 
                        if (value.trim() === "") {  
                            alertify.alert("Error","Reason is required!");
                            return false;  // Prevent closing the prompt
                        } 
                        $.ajax({
                            type:'post',
                            url:"<?= base_url('wallet/rejectfundrequest') ?>",
                            data:{id:id,response:value},
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
                    function(){ alertify.error("Reject Deposit Request Cancelled"); }
                ).set('labels', {ok:'Reject Deposit Request'})
                .set('closable', false);
            });

            
            var url="<?= base_url('wallet/fundrequests/?type=data'); ?>";
            var columns=[
                    { 
                        title: "Sl.No.", 
                        field: "serial", 
                        type: "auto"
                    },
                    { 
                        title: "Date", 
                        field: "date",
                        width: 100,
                        formatter: function(cell){
                            let dateStr = cell.getValue(); // Y-m-d format
                            let formattedDate = dateStr.split("-").reverse().join("-");
                            return formattedDate;
                        }
                    },
                    { title: "Member ID", field: "username", width: 150 },
                    { title: "Name", field: "name" },
                    { title: "Amount", field: "amount", width: 120 },
                    { title: "Transaction hash", field: "tx_hash" },
                    { 
                        title: "Screenshot", 
                        field: "screenshot",
                        formatter: function(cell){
                            let image = cell.getValue();
                            let html='<button type="button" class="btn btn-info btn-sm view-screenshot" data-src="'+image+'">View</button>';
                            return html;
                        }
                    },
                    { 
                       title: "Action", 
                       field: "id", 
                       width: 300, 
                       formatter: function(cell) {
                            let id = cell.getValue(); // Get full row data
                           let button=`<button type="button" class="btn btn-sm btn-success approve" value="${id}">Approve</button> `;
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
                myModal = new bootstrap.Modal(document.getElementById('modal-default'));
                myModal.show();

            });
            $('body').on('click','#close-modal',function(){
                myModal.hide();
            });

        });
		
		function validate(){
			if(!confirm("Confirm Activate this Member?")){
				return false;
			}
		}
	</script>
    
    	
