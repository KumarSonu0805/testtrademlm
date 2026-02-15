
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
                                            <div class="col-md-6">
                                                <?php echo form_open('epins/transferepin', 'id="myform"'); ?>
                                                    <div class="form-group">
                                                        <?php echo create_form_input("text","","Enter Receiver",true,'',array("id"=>"username")); 
                                                            echo create_form_input("hidden","reg_to","Enter Receiver",true,'',array("id"=>"reg_to")); 
                                                        ?>
                                                        <div id="name" class="lead"><span class="text-success"></span></div>
                                                    </div>
                                                    <div class="form-group">
                                                        <?= create_form_input('select','package_id','Package',true,'',array("id"=>"package_id"),$packages);
                                                        ?>
                                                    </div>
                                                    <div class="form-group">
                                                        <?= create_form_input('text','','Available E-Pins',false,'',array("id"=>"available","readonly"=>"true"));
                                                        ?>
                                                    </div>
                                                    <div class="form-group">
                                                        <?= create_form_input('number','quantity','No of E-Pins',true,'',array("id"=>"quantity","autocomplete"=>"off"));
                                                        ?>
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="submit" name="transferepin" class="btn btn-sm btn-success" id="savebtn" value="Transfer E-Pin">
                                                    </div>
                                                <?php echo form_close(); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
<script>
	$(document).ready(function(e) {
		$('#username').keyup(function(){
			$('#name').html('');
			$('#reg_to').val('');
			$('#savebtn').attr('disabled',true);
		});
		$('#username').blur(function(){
			var username=$(this).val();
			if(username!=''){
				$.ajax({
					type:"POST",
					url:"<?php echo base_url("members/getmember/"); ?>",
					data:{username:username,status:'not self'},
					success: function(data){
						data=JSON.parse(data);
						if(data['regid']!=0){
							$('#name').html("<span class='text-success'>"+data['name']+"</span>");
							$('#reg_to').val(data['regid']);
							$('#savebtn').prop("disabled",false);
						}
						else{$('#name').html("<span class='text-danger'>"+data['name']+"</span>");}
					}
				});
			}
		});
        $('#package_id').change(function(){
			var package_id=$(this).val();
			$.ajax({
				type:"POST",
				url:"<?php echo base_url('epins/getepinquantity/'); ?>",
				data:{package_id:package_id},
				success: function(data){
					$('#available').val(data);
				}
			});
		});
		$('#quantity').keyup(function(){
			var quantity=$(this).val();
			var avl=parseInt($('#available').val());
			if(isNaN(avl)){avl=0;}
			if(quantity>avl){
				alert("Quantity Not available!");
				$('#quantity').val('');
			}
		});
    });
</script>