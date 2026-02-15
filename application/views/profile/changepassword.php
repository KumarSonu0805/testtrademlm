
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
                                            <div class="col-md-5">
                                                <?php echo form_open('profile/updatepassword','onSubmit="return checkPassword(\'form\');"'); ?>
                                                <div class="devit-card-custom">
                                                    <div class="form-group">
                                                        <?php 
                                                            $attributes=array("id"=>"oldpass","Placeholder"=>"Old Password");
                                                            echo create_form_input("password","oldpass","Old Password",true,'',$attributes); 
                                                        ?>
                                                    </div>
                                                    <div class="form-group">
                                                        <?php 
                                                            $attributes=array("id"=>"password","Placeholder"=>"New Password");
                                                            echo create_form_input("password","password","New Password",true,'',$attributes); 
                                                        ?>
                                                    </div>
                                                    <div class="form-group">
                                                        <?php 
                                                            $attributes=array("id"=>"retype","Placeholder"=>"Confirm Password");
                                                            echo create_form_input("password","","Confirm Password",true,'',$attributes); 
                                                        ?>
                                                        <em for="retype" class="text-danger invalid hidden">Passwords Do not Match</em>
                                                    </div>
                                                    <?php 
                                                        echo create_form_input("hidden","user","",false,$this->session->user); 
                                                        $data = array('name' => 'updatepassword', 'value'=>'Update Password', 'class'=>'btn btn-primary btn-sm');
                                                        echo form_submit($data); 
                                                    ?>
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
		$('#password,#retype').keyup(checkPassword);
	});
	
	function checkPassword(check){
		var password=$('#password').val();
		var retype=$('#retype').val();
		if(password==''){
			$('#password').val($(this).val()).focus();
			$(this).val('');
			return false;
		}
		if(retype!='' && retype!=password){
			$('.invalid').removeClass('hidden');
			if(check=='form'){
				alert("Passwords Do not Match!");
				//Lobibox.notify('error', { sound:false,position: 'top center', msg: "Passwords do not Match!" });
			}
			return false;
		}else{
			$('.invalid').addClass('hidden');
		}
	}
</script>