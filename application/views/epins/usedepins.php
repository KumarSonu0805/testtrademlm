
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
                                                    <table class="table table-bordered data-table" id="bootstrap-data-table-export">
                                                        <thead>
                                                            <tr>
                                                                <th>Sl No.</th>
                                                                <th>E-Pin</th>
                                                                <th>Activation Date</th>
                                                                <th>Member ID</th>
                                                                <th>Name</th>
                                                                <th>Package</th>
                                                                <th>Amount</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                $epins=$epins;
                                                                if(is_array($epins)){$i=0;
                                                                    foreach($epins as $epin){
                                                                        $i++;
                                                            ?>
                                                            <tr>
                                                                <td><?= $i; ?></td>
                                                                <td><?= $epin['epin']; ?></td>
                                                                <td><?= date('d-m-Y',strtotime($epin['used_on'])); ?></td>
                                                                <td><?= $epin['username']; ?></td>
                                                                <td><?= $epin['name']; ?></td>
                                                                <td><?= $epin['package']; ?></td>
                                                                <td><?= $this->amount->toDecimal($epin['amount']); ?></td>
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
		function validate(){
			if(!confirm("Confirm Activate this Member?")){
				return false;
			}
		}
	</script>
    
    	
