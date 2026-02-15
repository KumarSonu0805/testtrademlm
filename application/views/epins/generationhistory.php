
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
                                            <div class="col-md-4" id="status"></div>
                                        </div><br>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table class="table data-table" id="bootstrap-data-table-export">
                                                        <thead>
                                                            <tr>
                                                                <th>Sl No.</th>
                                                                <th>E-Pin</th>
                                                                <th>Generated For</th>
                                                                <th>Package</th>
                                                                <th>Approved Date</th>
                                                                <th class="select-filter">Status</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                $transfers=$transfers;
                                                                if(is_array($transfers)){$i=0;
                                                                    foreach($transfers as $transfer){
                                                                        $i++;
                                                                        if($transfer['status']==0){
                                                                            $status="<span class='text-danger'>Not Used</span>";
                                                                        }else{
                                                                            $status="<span class='text-success'>Used</span>";
                                                                        }
                                                            ?>
                                                            <tr>
                                                                <td><?= $i; ?></td>
                                                                <td><?= $transfer['epin']; ?></td>
                                                                <td><?= $transfer['to_username'].'('.$transfer['to_name'].')'; ?></td>
                                                                <td><?= $transfer['package']; ?></td>
                                                                <td><?= date('d-m-Y',strtotime($transfer['added_on'])); ?></td>
                                                                <td><?= $status; ?></td>
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
</script>