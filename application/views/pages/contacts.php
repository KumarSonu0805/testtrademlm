
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
                                            <div class="col-md-4 mt-1 mb-1" id="status"></div>
                                        </div><br>
                                        <div class="row">
                                            <div class="col-md-12">                            
                                                <div class="table-responsive" id="result">
                                                    <table class="table data-table" id="bootstrap-data-table-export">
                                                        <thead>
                                                            <tr>
                                                                <th>Sl No.</th>
                                                                <th>Name</th>
                                                                <th>Email</th>
                                                                <th>Mobile</th>
                                                                <th>Location</th>
                                                                <th width="35%">Message</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                $contacts=$contacts;
                                                                if(is_array($contacts)){$i=0;
                                                                    foreach($contacts as $single){
                                                                        $i++;
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $i; ?></td>
                                                                <td><?php echo $single['name']; ?></td>
                                                                <td><?php echo $single['email']; ?></td>
                                                                <td><?php echo $single['mobile']; ?></td>
                                                                <td><?php echo $single['location']; ?></td>
                                                                <td><?php echo $single['message']; ?></td>
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
			$('body').on('click','.view',function(){
				$('#img-popup').attr('src','');
				var src=$(this).val();
				$('#img-popup').attr('src',src);
				$('#mediumModalLabel').text($(this).text());
			});
        });
		
		function createDatatable(){
			$('#status').html('');
            table=$('#bootstrap-data-table-export').DataTable({
                    "pageLength": 25 // Set the default page data count to 25

                });
			table.columns('.select-filter').every(function(){
				var that = this;
				var pos=$('#status');
				// Create the select list and search operation
				var select = $('<select class="form-control" />').appendTo(pos).on('change',function(){
								that.search($(this).val()).draw();
							});
					select.append('<option value="">All</option>');
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
			var status=$(document.activeElement).val();
			if(status==3){ msg="Reject"; }
			else if(status==1){ msg="Approve"; }
			if(!confirm(msg+" KYC of this Member?")){
				return false;
			}
		}
	</script>
    
    	
