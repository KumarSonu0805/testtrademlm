
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
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <img src="" alt="img" class="img-fluid" id="preview">
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
    <script>
	
		$(document).ready(function(e) {
            alertify.defaults.transition = "slide";
            alertify.defaults.theme.ok = "btn btn-primary";
            alertify.defaults.theme.cancel = "btn btn-danger";
            alertify.defaults.theme.input = "form-control";
            $('body').on('click','.approve',function(){
                var id=$(this).val();
                alertify.confirm("Approve Fund Request", "Are you sure you want to Approve this Fund Request?", 
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
                    function(){ alertify.error("Fund Request Approval Cancelled!"); }
                ).set('labels', {ok:'Approve Fund Request'});
            });
            $('body').on('click','.reject',function(){
                var id=$(this).val();
                
                alertify.prompt("Reject Fund Request", "Enter Reason to Reject Fund Request :", "", 
                    function(evt, value){ 
                        if (value.trim() === "") {  
                            alertify.alert("Error","Reason is required!");
                            return false;  // Prevent closing the prompt
                        } 
                        $.ajax({
                            type:'post',
                            url:"<?= base_url('wallet/rejectfundrequest') ?>",
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
                    function(){ alertify.error("Reject Fund Request Cancelled"); }
                ).set('labels', {ok:'Reject Fund Request'})
                .set('closable', false);
            });

            
            var url="<?= base_url('wallet/transferfundhistory/?type=data'); ?>";
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
                    { title: "Rec. MID", field: "to_username" },
                    { title: "Rec. Name", field: "to_name" },
                    { title: "Amount", field: "amount" },
                    { title: "Status", field: "status" }
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
		
		function validate(){
			if(!confirm("Confirm Activate this Member?")){
				return false;
			}
		}
	</script>
    
    	
