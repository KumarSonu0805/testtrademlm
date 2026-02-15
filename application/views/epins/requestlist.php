
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card light-bg">
                                    <div class="card-header">
                                        <h3 class="card-title"><?= $title ?></h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive" id="result">
                                                    <table class="table data-table" id="bootstrap-data-table-export">
                                                        <thead>
                                                            <tr>
                                                                <th>Sl No.</th>
                                                                <th>Member ID</th>
                                                                <th>Member Name</th>
                                                                <th>Request Date</th>
                                                                <th>Amount Paid</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                $members=$members;
                                                                if(is_array($members)){$i=0;
                                                                    foreach($members as $member){
                                                                        $i++;
                                                                        $status="<span class='text-danger'>Not activated</span>";
                                                                        if($member['status']==1){
                                                                            $status="<span class='text-success'>Activated</span>";
                                                                        }
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $i; ?></td>
                                                                <td><?php echo $member['username']; ?></td>
                                                                <td><?php echo $member['name']; ?></td>
                                                                <td><?php echo date('d-m-Y',strtotime($member['date'])); ?></td>
                                                                <td><?php echo $member['amount']; ?></td>
                                                                <td>
                                                                    <a href="<?php echo base_url('epins/requestdetails/'.$member['id']); ?>" class="btn btn-sm btn-info"><i class="fa fa-eye"></i> View Details</a>
                                                                </td>
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
                            </div>
                        </div>
                    </div>
                </section>
    <script>
	
		var table;
		$(document).ready(function(e) {
			createDatatable();
			$('#position').change(function(){
				var position=$(this).val();
				$('#member_id').val('');
				$.ajax({
					type:"POST",
					url:"<?php echo base_url('members/getmembertable'); ?>",
					data:{position:position},
					success: function(data){
						$('#result').html(data);
						createDatatable();
					}
				});
			});
        });
		
		function createDatatable(){
			$('#status').html('');
            table=$('#bootstrap-data-table-export').DataTable();
			table.columns('.select-filter').every(function(){
				var that = this;
				var pos=$('#status');
				// Create the select list and search operation
				var select = $('<select class="form-control" />').appendTo(pos).on('change',function(){
								that.search("^" + $(this).val() + "$", true, false, true).draw();
							});
					select.append('<option value=".+">All</option>');
				// Get the search data for the first column and add to the select list
				this.cache( 'search' ).sort().unique().each(function(d){
						select.append($('<option value="'+d+'">'+d+'</option>') );
				});
			});
			$('#member_id').on('keyup',function(){
				table.columns(1).search( this.value ).draw();
			});
		}
		function validate(){
			if(!confirm("Confirm Activate this Member?")){
				return false;
			}
		}
	</script>
    
    	
