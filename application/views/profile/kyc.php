
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
                                        <?php
                                            if($acc_details['bank']=='' || $acc_details['branch']=='' || $acc_details['account_no']=='' || $acc_details['account_name']=='' || $acc_details['ifsc']==''){
                                                echo "<div class='text-center'><h2 class='text-danger'>Please Enter Bank Details before Uploading KYC!</h2>";
                                                echo "<a href='".base_url('profile/accdetails/')."' class='btn btn-sm btn-info'>Enter Bank Details</a></div>";
                                            }
                                            else{
                                        ?>
                                        <div class="row">
                                            <div class="col-md-5">
                                                <?php echo form_open_multipart('profile/uploaddocs', 'id="myform"'); ?>
                                                    <div class="form-group">
                                                        <?php 
                                                            $attributes=array("readonly"=>"true","Placeholder"=>"Member ID");
                                                            echo create_form_input("text","","Member ID",false,$user['username'],$attributes); 
                                                        ?>
                                                    </div>
                                                    <div class="form-group">
                                                        <?php 
                                                            $attributes=array("readonly"=>"true","Placeholder"=>"Member Name");
                                                            echo create_form_input("text","name","Member Name",false,$user['name'],$attributes); 
                                                        ?>
                                                    </div>
                                                    <div class="form-group">
                                                        <?php 
                                                            $attributes=array("readonly"=>"true","Placeholder"=>"Aadhar");
                                                            echo create_form_input("text","","Aadhar",false,$member['aadhar'],$attributes); 
                                                        ?>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <?php 
                                                                    $attributes=array("id"=>"aadhar1","onChange"=>"getPhoto(this,'aadhar1')","accept"=>"image/*");
                                                                    echo create_form_input("file","aadhar1","Upload Aadhar Card Front :",true,'',$attributes); 
                                                                    $aadhar1="";
                                                                    if($acc_details['aadhar1']!=''){
                                                                        $aadhar1="src='".file_url($acc_details['aadhar1'])."'";
                                                                    }
                                                                ?>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <img <?php echo $aadhar1; ?> id="aadhar1preview" style="height:150px; width:250px;" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <?php 
                                                                    $attributes=array("id"=>"aadhar2","onChange"=>"getPhoto(this,'aadhar2')","accept"=>"image/*");
                                                                    echo create_form_input("file","aadhar2","Upload Aadhar Card Back:",true,'',$attributes); 
                                                                    $aadhar2="";
                                                                    if($acc_details['aadhar2']!=''){
                                                                        $aadhar2="src='".file_url($acc_details['aadhar2'])."'";
                                                                    }
                                                                ?>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <img <?php echo $aadhar2; ?> id="aadhar2preview" style="height:150px; width:250px;" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <?php 
                                                            $attributes=array("readonly"=>"true","Placeholder"=>"PAN");
                                                            echo create_form_input("text","","PAN",false,$member['pan'],$attributes); 
                                                        ?>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <?php 
                                                                    $attributes=array("id"=>"pan","onChange"=>"getPhoto(this,'pan')","accept"=>"image/*");
                                                                    echo create_form_input("file","pan","Upload PAN Card :",false,'',$attributes); 
                                                                    $pan="";
                                                                    if($acc_details['pan']!=''){
                                                                        $pan="src='".file_url($acc_details['pan'])."'";
                                                                    }
                                                                ?>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <img <?php echo $pan; ?> id="panpreview" style="height:150px; width:250px;" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <?php 
                                                            $attributes=array("readonly"=>"true","Placeholder"=>"Account No");
                                                            echo create_form_input("text","","Account No",false,$acc_details['account_no'],$attributes); 
                                                        ?>
                                                    </div>
                                                    <div class="form-group">
                                                        <?php 
                                                            $attributes=array("readonly"=>"true","Placeholder"=>"IFSC");
                                                            echo create_form_input("text","","IFSC",false,$acc_details['ifsc'],$attributes); 
                                                        ?>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <?php 
                                                                    $attributes=array("id"=>"cheque","onChange"=>"getPhoto(this,'cheque')","accept"=>"image/*");
                                                                    echo create_form_input("file","cheque","Upload Passbook:",false,'',$attributes); 
                                                                    $cheque="";
                                                                    if($acc_details['cheque']!=''){
                                                                        $cheque="src='".file_url($acc_details['cheque'])."'";
                                                                    }
                                                                ?>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <img <?php echo $cheque; ?> id="chequepreview" style="height:150px; width:250px;" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <?php
                                                            $input=array("type"=>"hidden","name"=>"regid","value"=>$user['id']);
                                                            echo form_input($input);
                                                            if(!isset($acc_details['kyc'])){ $acc_details['kyc']=0; }
                                                            if($acc_details['kyc']==0 || $acc_details['kyc']==3){
                                                                if($acc_details['kyc']==3){
                                                                    echo "<h3 class='text-danger'>KYC Rejected! Resubmit KYC!</h3>";
                                                                    echo "<p class='lead'>Reason: ".$acc_details['reason']."</p>";
                                                                }
                                                        ?>
                                                        <button type="submit" class="btn btn-sm btn-success" id="uploaddocuments" name="uploaddocuments" value="upload">Upload</button>
                                                        <?php
                                                            }
                                                            elseif($acc_details['kyc']==2){
                                                                echo "<h3 class='text-danger'>KYC Pending </h3>";
                                                            }
                                                            else{
                                                                echo "<div class='text-success lead'>KYC Approved</div>";
                                                            }
                                                        ?>
                                                    </div>
                                                <?php echo form_close(); ?>
                                            </div>
                                        </div>
                                        <?php
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
<script>
	$('.view').click(function(){
		var src=$(this).val();
		$('#img-popup').attr('src',src);
	});
	
	function getPhoto(input,field){
		var id="#"+field;
		var preview="#"+field+"preview";
		$(preview).replaceWith('<img id="'+field+'preview" style="height:150px; width:250px;" >');
		if (input.files && input.files[0]) {
			var filename=input.files[0].name;
			var re = /(?:\.([^.]+))?$/;
			var ext = re.exec(filename)[1]; 
			ext=ext.toLowerCase();
			if(ext=='jpg' || ext=='jpeg' || ext=='png'){
				var size=input.files[0].size;
				if(size<=10485760 && size>=10240){
					var reader = new FileReader();
					
					reader.onload = function (e) {
						$(preview).attr('src',e.target.result);
					}
					reader.readAsDataURL(input.files[0]);
				}
				else if(size>=10485760){
					alert("Image size is greater than 10MB");	
					document.getElementById(field).value= null;
				}
				else if(size<=10240){
					alert("Image size is less than 10KB");	
					document.getElementById(field).value= null; 
				}
			}
			else{
				alert("Select 'jpeg' or 'jpg' or 'png' image file!!");	
				document.getElementById(field).value= null;
			}
		}
	}
</script>
    
    	
