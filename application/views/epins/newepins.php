
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
                                                                <th>E-Pin</th>
                                                                <th>Approved Date</th>
                                                                <th>Package</th>
                                                                <th>Action</th>
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
                                                                <td><?= date('d-m-Y',strtotime($epin['added_on'])); ?></td>
                                                                <td><?= $epin['package']; ?></td>
                                                                <td>
                                                                    <?php
                                                                        //if($epin['package_id']==1){
                                                                    ?>
                                                                    <button type="button" class="btn btn-success btn-xs activate-btn" data-toggle="modal" data-target="#modal-default" data-package="<?= $epin['package_id'] ?>"
                                                                            value="<?= $epin['epin'] ?>" onClick="$('#username').val('').trigger('keyup');" >Activate</button>
                                                                    <?php
                                                                        /*}
                                                                        else{
                                                                    ?>
                                                                    <button type="button" class="btn btn-success btn-xs upgrade-btn" data-toggle="modal" data-target="#modal-default" data-package="<?= $epin['package_id'] ?>"
                                                                            value="<?= $epin['epin'] ?>" onClick="$('#username').val('').trigger('keyup');" >Join <?= $epin['package'] ?></button>
                                                                    <?php
                                                                        }*/
                                                                    ?>
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
<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
        	<?= form_open('members/activatemember', 'id="myform" onSubmit="return validate()"'); ?>
                <div class="modal-header">
                    <h4 class="modal-title">Activate Member</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                	<div class="row">
                    	<div class="col-md-12">
                        	<div class="form-group">
								<?= create_form_input('text','epin','E-Pin',true,'',array("id"=>"epin","readonly"=>"true"));
                                ?>
                            </div>
                        	<div class="form-group">
								<?php
                                    echo create_form_input("text","","Member ID",true,'',array("id"=>"username")); 
                                    echo create_form_input("hidden","regid","",false,'0',array("id"=>"regid")); 
                                ?>
                                <div id="name" class="lead"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
					<?= create_form_input('hidden','package_id','',false,'',array("id"=>"package_id"));
                    ?>
                	<button type="submit" class="btn btn-success" name="activatemember" value="Activate" id="savebtn">Activate Member</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            <?= form_close(); ?>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
    <script>
	
		var table;
		$(document).ready(function(e) {
			createDatatable();
			$('#username').keyup(function(){
				$('#name').html('');
				$('#regid').val('');
				$('#savebtn').attr('disabled',true);
			});
			$('#username').blur(function(){
				var username=$(this).val();
                var package_id=$('#package_id').val();
                var status='self,downline,not activated,not renewed';
				
				$('#name').html('');
				$('#regid').val('');
				$('#savebtn').attr('disabled',true);
				if(username!=''){
					$.ajax({
						type:"POST",
						url:"<?php echo base_url("members/getmember/"); ?>",
						data:{username:username,package_id:package_id,status:status},
						success: function(data){
							//console.log(data);
							data=JSON.parse(data);
							if(data['regid']!=0){
                                var name=data['name'];
                                /*if(Array.isArray(name)){
                                    name=name[0];
                                    $('.modal-title,#savebtn').text('Renew Member Account');
                                }
                                else{
                                    $('.modal-title,#savebtn').text('Activate Member');
                                }*/
								$('#name').html("<span class='text-success'>"+name+"</span>");
								$('#regid').val(data['regid']);
								$('#savebtn').prop("disabled",false);
							}
							else{$('#name').html("<span class='text-danger'>"+data['name']+"</span>");}
						}
					});
				}
			});
			$('body').on('click','.activate-btn',function(){
				$('#epin').val($(this).val());
				$('#package_id').val($(this).data('package'));
                $('.modal-title').text('Activate Member');
                $('#savebtn').attr('name','activatemember');
                $('#savebtn').text('Activate Member');
			});
			$('body').on('click','.upgrade-btn',function(){
				$('#epin').val($(this).val());
				$('#package_id').val($(this).data('package'));
                $('.modal-title').text('Join '+$(this).closest('td').prev().text());
                $('#savebtn').attr('name','joinclub');
                $('#savebtn').text('Join '+$(this).closest('td').prev().text());
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
			if(!confirm("Confirm "+$('#savebtn').text()+"?")){
				return false;
			}
		}
	</script>
    
    	
