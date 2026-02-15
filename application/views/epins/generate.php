
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
                                                <?php echo form_open('epins/generateepin', 'id="myform" onSubmit="return validate()"'); ?>
                                                    <div class="form-group">
                                                        <?php
                                                            echo create_form_input("text","","Generate E-Pin For",true,'admin',array("id"=>"username")); 
                                                            echo create_form_input("hidden","regid","",true,'1',array("id"=>"regid")); 
                                                            //echo create_form_input('select','regid','Generate E-Pin For',true,'',array("class"=>"select2"),$members);
                                                        ?>
                                                        <div id="name" class="lead"><span class="text-success">Admin</span></div>
                                                    </div>
                                                    <div class="form-group">
                                                        <?= create_form_input('select','package_id','Package',true,'',array("id"=>"package_id"),$packages);
                                                        ?>
                                                    </div>
                                                    <div class="form-group">
                                                        <?= create_form_input('number','quantity','No of E-Pins',true,'',array("id"=>"quantity","Placeholder"=>"No of E-Pins","autocomplete"=>"off"));
                                                        ?>
                                                    </div>
                                                    <div class="form-group">
                                                        <?php
                                                            echo create_form_input("hidden","","",false,'',array("id"=>"price")); 
                                                            echo create_form_input('text','amount','E-Pin Amount',true,'',array("id"=>"amount","Placeholder"=>"E-Pin Amount","readonly"=>"true"));
                                                        ?>
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="submit" name="generateepin" class="btn btn-sm btn-success" id="savebtn" value="Generate E-Pin">
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
<?= create_form_input("hidden","","",false,$package_price,array("id"=>"package_price")); ?>
<script>
	$(document).ready(function(e) {
		$('#username').keyup(function(){
			$('#name').html('');
			$('#regid').val('');
			$('#savebtn').attr('disabled',true);
		});
		$('#username').blur(function(){
			var username=$(this).val();
			if(username!=''){
				$.ajax({
					type:"POST",
					url:"<?php echo base_url("members/getmember/"); ?>",
					data:{username:username,status:'all'},
					success: function(data){
						data=JSON.parse(data);
						if(data['regid']!=0){
							$('#name').html("<span class='text-success'>"+data['name']+"</span>");
							$('#regid').val(data['regid']);
							$('#savebtn').prop("disabled",false);
						}
						else{$('#name').html("<span class='text-danger'>"+data['name']+"</span>");}
					}
				});
			}
		});
        $('#package_id').change(function(){
			var package_id=$(this).val();
			var package_price=$('#package_price').val();
			var price='';
			if(package_id!=''){
				package_price=JSON.parse(package_price);
				price=package_price[package_id];
			}
			$('#price').val(price);
			$('#quantity').trigger('keyup');
		});
		$('#quantity').keyup(function(){
			if($('#package_id').val()==''){
				alert("First select Package!");
				$(this).val('');
				return false;
			}
			var quantity=$(this).val();
			quantity=quantity.replace(/[^\d\.]+/,'');
			$(this).val(quantity);
			var price=Number($('#price').val());
			if(isNaN(price)){ price=0; $('#price').val(price); }
			var total_amount=quantity*price;
			$('#amount').val(total_amount);
		});
    });
	
	function validate(){
		if(!confirm("Confirm Generate E-Pin(s) for this User?")){
			return false;
		}
	}
</script>