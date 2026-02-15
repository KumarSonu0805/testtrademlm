
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
                                            if(isset($profile) && $profile==true){
                                        ?>
                                        <div class="row profile">
                                            <div class="col-md-6">
                                                <legend>Personal Details</legend>
                                                <table class="table" id="personal-details">
                                                    <tr>
                                                        <td colspan="2">
                                                            <img src="<?php if($member['photo']!=''){echo file_url($member['photo']);}else{echo file_url('assets/images/avatar.png');} ?>" 
                                                                    style="height:135px; width:120px;" alt="User Image" id="view_photo"><br>
                                                            <button type="button" class="btn btn-sm btn-warning" 
                                                                    onClick="$(this).hide();$('#photoform').show();">Change Photo <i class="fa fa-camera"></i></button>

                                                            <?php echo form_open_multipart('profile/updatephoto', 'id="photoform"'); ?>
                                                                <input type="hidden" name="name" value="<?= $member['name']; ?>">
                                                                <input type="file" name="photo" id="photo" onChange="getPhoto(this)" required/><br>
                                                                <?php
                                                                    $input=array("type"=>"hidden","name"=>"name","value"=>$user['name']);
                                                                    echo form_input($input);
                                                                    $input=array("type"=>"hidden","name"=>"regid","value"=>$user['id']);
                                                                    echo form_input($input);
                                                                ?>
                                                                <button type="submit" class="btn btn-sm btn-success" name="updatephoto" value="Update">Update</button>
                                                                <button type="button" class="btn btn-sm btn-danger" onClick="window.location.reload()">Cancel</button>
                                                            <?php echo form_close(); ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>UP ID</th>
                                                        <td><?php echo $member['susername']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Sponsor Name</th>
                                                        <td><?php echo $member['sname']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Member ID</th>
                                                        <td><?php echo $user['username']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Name</th>
                                                        <td><?php echo $member['name']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Date of Birth</th>
                                                        <td><?php if($member['dob']!='0000-00-00' && $member['dob']!==NULL)echo date('d-m-Y',strtotime($member['dob'])); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Father's Name</th>
                                                        <td><?php echo $member['father']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Occupation</th>
                                                        <td><?php echo $member['occupation']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Gender</th>
                                                        <td><?php echo $member['gender']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Marital Status</th>
                                                        <td><?php echo $member['mstatus']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Aadhar No.</th>
                                                        <td><?php echo $member['aadhar']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Pan No.</th>
                                                        <td><?php echo $member['pan']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Joining Date</th>
                                                        <td><?php echo date('d-m-Y',strtotime($member['date'])); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Activation Date</th>
                                                        <td><?php if($member['activation_date']!='0000-00-00' && $member['activation_date']!==NULL)echo date('d-m-Y',strtotime($member['activation_date'])); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <button type="button" class="btn btn-primary btn-sm" 
                                                                    onClick="$('#personal-details').hide();$('#pan').trigger('keyup');$('#personalform').show().find('input').first().focus();">Edit Personal Info <i class="fa fa-edit"></i></button>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <?php echo form_open('profile/updatepersonaldetails', 'id="personalform"'); ?>
                                                    <div class="form-group">
                                                        <label for="father" class=" form-control-label">Father's Name</label>
                                                        <?php
                                                            $input=array("name"=>"father","id"=>"father","Placeholder"=>"Father's Name","class"=>"form-control",
                                                                    "autocomplete"=>"off","value"=>$member['father']);
                                                            echo form_input($input);
                                                        ?>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="occupation" class=" form-control-label">Occupation</label>
                                                        <?php
                                                            $input=array("name"=>"occupation","id"=>"occupation","Placeholder"=>"Occupation","class"=>"form-control",
                                                                    "autocomplete"=>"off","value"=>$member['occupation']);
                                                            echo form_input($input);
                                                        ?>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="dob">Date of Birth</label>
                                                        <?php
                                                            $input=array("type"=>"date","name"=>"dob","class"=>"form-control", "autocomplete"=>"off","value"=>$member['dob'],"max"=>date('Y-m-d'),"min"=>date('Y-m-d',strtotime('-100 years')));
                                                            echo form_input($input);
                                                        ?>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="gender" class=" form-control-label">Gender</label>
                                                        <?php
                                                            $gender=array(""=>"Select Gender","Male"=>"Male","Female"=>"Female");
                                                            $attrs=array("id"=>"gender","class"=>"form-control form-control-select", "tabindex"=>"1");
                                                            echo form_dropdown('gender',$gender,$member['gender'],$attrs);
                                                        ?>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="mstatus">Marital Status</label>
                                                        <?php
                                                            $mstatus=array(""=>"Select","MARRIED"=>"Married","UNMARRIED"=>"Unmarried");
                                                            $attrs=array("id"=>"mstatus","class"=>"form-control form-control-select", "tabindex"=>"1");
                                                            echo form_dropdown('mstatus',$mstatus,$member['mstatus'],$attrs);
                                                        ?>
                                                    </div>
                                                    <?php /*?><div class="form-group">
                                                        <label for="blood_group" class=" form-control-label">Blood Group</label>
                                                        <?php
                                                            $input=array("name"=>"blood_group","id"=>"blood_group","Placeholder"=>"Blood Group","class"=>"form-control",
                                                                    "autocomplete"=>"off","value"=>$member['blood_group']);
                                                            echo form_input($input);
                                                        ?>
                                                    </div><?php */?>
                                                    <div class="form-group">
                                                        <label for="aadhar" class=" form-control-label">Aadhar No.</label>
                                                        <?php
                                                            $input=array("name"=>"aadhar","id"=>"aadhar","Placeholder"=>"Aadhar No.",
                                                                         "class"=>"form-control","pattern"=>"[0-9]{12}",
                                                                         "title"=>"Enter Valid Aadhar No.","autocomplete"=>"off",
                                                                         "maxlength"=>"12","value"=>$member['aadhar'],'requried'=>'true');
                                                            if(!empty($member['aadhar']) && isset($acc_details['kyc']) && ($acc_details['kyc']==1 || $acc_details['kyc']==2)){
                                                                $input['readonly']="true";
                                                            }
                                                            echo form_input($input);
                                                        ?>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="pan" class=" form-control-label">PAN No.</label>
                                                        <?php
                                                            $input=array("name"=>"pan","id"=>"pan","Placeholder"=>"PAN No.",
                                                                         "class"=>"form-control","pattern"=>"[A-Z0-9]{10}",
                                                                         "title"=>"Enter Valid Pan No.",
                                                                         "autocomplete"=>"off","maxlength"=>"10",
                                                                         "value"=>$member['pan']);
                                                            if(!empty($member['pan']) && isset($acc_details['kyc']) && ($acc_details['kyc']==1 || $acc_details['kyc']==2)){
                                                                $input['readonly']="true";
                                                            }
                                                            echo form_input($input);
                                                        ?>
                                                        <small id="pan-err"></small>
                                                    </div>
                                                    <div class="form-group">
                                                        <?php
                                                            $input=array("type"=>"hidden","name"=>"regid","value"=>$user['id']);
                                                            echo form_input($input);
                                                        ?>
                                                        <button type="submit" class="btn btn-sm btn-success" name="updatepersonaldetails" value="Update" id="updatepersonaldetails">Update</button>
                                                        <button type="button" class="btn btn-sm btn-danger" onClick="window.location.reload()">Cancel</button>
                                                    </div>
                                                <?php echo form_close(); ?>
                                            </div>
                                            <div class="col-md-6">
                                                <legend>Contact Information</legend>
                                                <table class="table" id="contact-details">
                                                    <tr>
                                                        <th>Address</th>
                                                        <td><?php echo $member['address']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>District</th>
                                                        <td><?php echo $member['district']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>State</th>
                                                        <td><?php echo $member['state']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Pincode</th>
                                                        <td><?php echo $member['pincode']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Mobile</th>
                                                        <td><?php echo $member['mobile']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Email</th>
                                                        <td><?php echo $member['email']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <button type="button" class="btn btn-primary btn-sm" 
                                                                    onClick="$('#contact-details').hide();$('#contactform').show().find('textarea').first().focus();">Edit Contact Info <i class="fa fa-edit"></i></button>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <?php echo form_open('profile/updatecontactinfo', 'id="contactform"'); ?>
                                                    <div class="form-group">
                                                        <label for="address" class=" form-control-label">Address</label>
                                                        <?php
                                                            $input=array("name"=>"address","id"=>"address","Placeholder"=>"Address","class"=>"form-control",
                                                                            "autocomplete"=>"off","rows"=>"3","value"=>$member['address']);
                                                            echo form_textarea($input);
                                                        ?>
                                                    </div>
                                                    <div class="form-group hidden">
                                                        <label for="country">Country</label>
                                                        <?php
                                                            $country="India";
                                                            if($member['country']!=''){
                                                                $country=$member['country'];
                                                            }
                                                            $input=array("name"=>"country","id"=>"country","Placeholder"=>"Country",
                                                                         "class"=>"form-control","autocomplete"=>"off","value"=>$country);
                                                            echo form_input($input);
                                                        ?>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="state">State</label>
                                                        <input type="text" class="form-control" name="state" id="state" value="<?= $member['state']; ?>" >
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="district">District</label>
                                                        <input type="text" class="form-control" name="district" id="district" value="<?= $member['district']; ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="pincode">Pincode</label>
                                                        <?php
                                                            $input=array("name"=>"pincode","id"=>"pincode","Placeholder"=>"Pincode","class"=>"form-control", 
                                                                            "autocomplete"=>"off","value"=>$member['pincode']);
                                                            echo form_input($input);
                                                        ?>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="mobile">Mobile</label>
                                                        <?php
                                                            $input=array("name"=>"mobile","id"=>"mobile",
                                                                         "Placeholder"=>"Mobile","class"=>"form-control", 
                                                                         "readonly"=>"true","autocomplete"=>"off",
                                                                         "value"=>$member['mobile'],"pattern"=>"[0-9]{10}",
                                                                         "title"=>"Enter Valid Mobile No.","maxlength"=>"10");
                                                            echo form_input($input);
                                                        ?>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="email">Email</label>
                                                        <?php
                                                            $input=array("type"=>"email","name"=>"email","id"=>"email","Placeholder"=>"Email","class"=>"form-control", 
                                                                            "autocomplete"=>"off","value"=>$member['email']);
                                                            echo form_input($input);
                                                        ?>
                                                    </div>
                                                    <div class="form-group">
                                                        <?php
                                                            $input=array("type"=>"hidden","name"=>"regid","value"=>$user['id']);
                                                            echo form_input($input);
                                                        ?>
                                                        <button type="submit" class="btn btn-sm btn-success" name="updatecontactinfo" value="Update">Update</button>
                                                        <button type="button" class="btn btn-sm btn-danger" onClick="window.location.reload()">Cancel</button>
                                                    </div>
                                                <?php echo form_close(); ?>

                                                <legend>Nominee Information</legend>
                                                <table class="table" id="nominee-details">
                                                    <tr>
                                                        <th width="35%">Nominee Name</th>
                                                        <td><?php echo $nominee_details['name']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Nominee Mobile No.</th>
                                                        <td><?php echo $nominee_details['mobile']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Relation With Applicant</th>
                                                        <td><?php echo $nominee_details['relation']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">
                                                            <button type="button" class="btn btn-primary btn-sm" 
                                                                    onClick="$('#nominee-details').hide();$('#nomineeform').show().find('input').first().focus();">Edit Nominee <i class="fa fa-edit"></i></button>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <?php echo form_open('profile/updatenomineedetails', 'id="nomineeform"'); ?>
                                                    <div class="form-group">
                                                        <label for="branch">Nominee Name</label>
                                                        <?php
                                                            $input=array("name"=>"name","Placeholder"=>"Nominee Name","class"=>"form-control", "autocomplete"=>"off","value"=>$nominee_details['name'],"required"=>"true");
                                                            echo form_input($input);
                                                        ?>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="ifsc">Nominee Mobile No.</label>
                                                        <?php
                                                           $input=array("name"=>"mobile","class"=>"form-control",
                                                                         "Placeholder"=>"Nominee Mobile No.",
                                                                         "autocomplete"=>"off",
                                                                         "value"=>$nominee_details['mobile'],
                                                                         "required"=>"true","pattern"=>"[0-9]{10}",
                                                                         "title"=>"Enter Valid Mobile No.","maxlength"=>"10");
                                                            echo form_input($input);
                                                        ?>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="branch">Relation With Applicant</label>
                                                        <?php
                                                            $input=array("name"=>"relation","Placeholder"=>"Relation With Applicant","class"=>"form-control", "autocomplete"=>"off",
                                                                        "value"=>$nominee_details['relation'],"required"=>"true");
                                                            echo form_input($input);
                                                        ?>
                                                    </div>
                                                    <div class="form-group">
                                                        <?php
                                                            $input=array("type"=>"hidden","name"=>"regid","value"=>$user['id']);
                                                            echo form_input($input);
                                                        ?>
                                                        <button type="submit" class="btn btn-sm btn-success" name="updatenomineedetails" value="Update">Update</button>
                                                        <button type="button" class="btn btn-sm btn-danger" onClick="window.location.reload()">Cancel</button>
                                                    </div>
                                                <?php echo form_close(); ?>
                                                
                                            </div>
                                        </div>
                                        <?php
                                            }
                                            elseif((empty($member['address']) || empty($nominee_details['name'])) 
                                                   && $this->session->role!='admin'){
                                                echo "<div class='text-center'><h2 class='text-danger'>Please Complete Your Profile Before Adding Account Details!</h2>";
                                                echo "<a href='".base_url('profile/')."' class='btn btn-sm btn-info'>Edit Profile</a></div>";
                                            }
                                            else{
                                                if(empty($acc_details)){
                                                    $acc_details['account_name']=$acc_details['bank']='';
                                                    $acc_details['account_no']=$acc_details['branch']='';
                                                    $acc_details['ifsc']=$acc_details['upi']=$acc_details['address']='';
                                                    $acc_details['kyc']=0;
                                                }
                                        ?>
                                        <div class="row profile">
                                            <div class="col-md-6">
                                                <legend>Bank Information</legend>
                                                <table class="table" id="bank-details">
                                                    <tr>
                                                        <th>A/C Holder Name</th>
                                                        <td><?php echo $acc_details['account_name']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Bank Name</th>
                                                        <td><?php echo strtoupper($acc_details['bank']);  ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Account Number</th>
                                                        <td><?php echo $acc_details['account_no']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Branch</th>
                                                        <td><?php echo $acc_details['branch']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>IFSC Code</th>
                                                        <td><?php echo $acc_details['ifsc']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>UPI</th>
                                                        <td><?php echo $acc_details['upi']; ?></td>
                                                    </tr>
                                                    <?php if($acc_details['kyc']!=1){ ?>
                                                    <tr>
                                                        <td colspan="2">
                                                            <button type="button" class="btn btn-primary btn-sm" 
                                                                    onClick="$('#bank-details').hide();$('#accform').show().find('input').first().focus();">Edit Bank Details <i class="fa fa-edit"></i></button>
                                                        </td>
                                                    </tr>
                                                    <?php } ?>
                                                </table>
                                                <?php echo form_open('profile/updateaccdetails', 'id="accform"'); ?>
                                                    <div class="form-group">
                                                        <label for="account_name">A/C Holder Name</label>
                                                        <?php
                                                            $acc_name=empty($acc_details['account_name'])?$user['name']:$acc_details['account_name'];
                                                            $input=array("name"=>"account_name","id"=>"account_name","Placeholder"=>"Account Holder Name","class"=>"form-control", 
                                                                            "autocomplete"=>"off","value"=>$acc_name);
                                                            echo form_input($input);
                                                        ?>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="bank">Bank Name</label>
                                                        <?php
                                                            $bank="";
                                                            if($acc_details['bank']!=''){
                                                                if(in_array($acc_details['bank'],$banks)){
                                                                    $bank=$acc_details['bank'];
                                                                }
                                                                else{
                                                                    $bank='xyz';
                                                                }
                                                            }
                                                            $attrs=array("id"=>"bank","class"=>"form-control form-control-select", "tabindex"=>"1");
                                                            echo form_dropdown('bank',$banks,$bank,$attrs);
                                                            $input=array("id"=>"bank-name","Placeholder"=>"Bank Name","class"=>"form-control hidden mt-2",
                                                                            "autocomplete"=>"off","pattern"=>"[\w\s]+","title"=>"Enter Valid Bank Name","value"=>$acc_details['bank']);
                                                            echo form_input($input);
                                                        ?>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="account_no">A/C No.</label>
                                                        <?php
                                                            $input=array("name"=>"account_no","id"=>"account_no","Placeholder"=>"Account No","class"=>"form-control",
                                                                            "autocomplete"=>"off","pattern"=>"[0-9]{9,}","title"=>"Enter Valid Account No.","value"=>$acc_details['account_no']);
                                                            echo form_input($input);
                                                        ?>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="branch">Branch</label>
                                                        <?php
                                                            $input=array("name"=>"branch","id"=>"branch","Placeholder"=>"Branch","class"=>"form-control",
                                                                            "autocomplete"=>"off","value"=>$acc_details['branch']);
                                                            echo form_input($input);
                                                        ?>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="ifsc">IFSC Code</label>
                                                        <?php
                                                            $input=array("name"=>"ifsc","id"=>"ifsc","Placeholder"=>"IFSC Code","class"=>"form-control", 
                                                                            "autocomplete"=>"off","value"=>$acc_details['ifsc']);
                                                            echo form_input($input);
                                                        ?>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="upi">UPI</label>
                                                        <?php
                                                            $input=array("name"=>"upi","id"=>"upi","Placeholder"=>"UPI","class"=>"form-control", 
                                                                            "autocomplete"=>"off","value"=>$acc_details['upi']);
                                                            echo form_input($input);
                                                        ?>
                                                    </div>
                                                    <div class="form-group">
                                                        <?php
                                                            $input=array("type"=>"hidden","name"=>"regid","value"=>$user['id']);
                                                            echo form_input($input);
                                                        ?>
                                                        <button type="submit" class="btn btn-sm btn-success" name="updateaccdetails" value="Update">Update</button>
                                                        <button type="button" class="btn btn-sm btn-danger" onClick="window.location.reload()">Cancel</button>
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
		$(document).ready(function(e) { 
            $('form').on('change','#parent_id',function(){
                var parent_id=$(this).val();
                var state=$(this).find('option:selected').text();
                $('#state').val(state);
                $.ajax({
                    type:"post",
                    url:"<?= base_url('profile/getdistricts/'); ?>",
                    data:{parent_id:parent_id},
                    success:function(data){
                        $('#area_id').replaceWith(data);
                        if($('#area_id').val()=='')
                            $('#district').val('');
                        //setarea_id();
                    }
                });
            });
            $('form').on('change','#area_id',function(){
                var district=$(this).find('option:selected').text();
                $('#district').val(district);
            }); 
			$('#parent').keyup(function(){
				var username=$(this).val();
				var poslabel=$('#position-div').find("label");
				$('#parent_id').val('');
				$.ajax({
					type:"POST",
					url:"<?php echo base_url('members/getpositions'); ?>",
					data:{username:username},
					beforeSend: function(data){
						$('#pdiv').html($('#dot-loader').html());
					},
					success: function(data){
						$('#position-div').html(poslabel);
						data=JSON.parse(data);
						if(data['name']!=null){
							$('#pdiv').html(data['name']);
							$('#parent_id').val(data['id']);
						}
						$('#position-div').append(data['position']);
						var ele=$('#position')
						setChosenSelect(ele);
						
					}
				});
			});
			$('#parent').blur(function(){
				if($('#parent_id').val()==''){
					$('#pdiv').html("Enter Valid Placement ID!");
				}
			});
			$('body').on('keyup','#pan',function(){
                $('#updatepersonaldetails').addClass('disabled');
                let pan=$(this).val();
                if(pan.length==10){
                    $.ajax({
                        type:"post",
                        url:"<?= base_url("profile/checkpan"); ?>",
                        data:{pan,pan},
                        success:function(data){
                            if(data==1){
                                $('#updatepersonaldetails').removeClass('disabled');
                                $('#pan-err').text("PAN No available!").addClass('text-success').removeClass('text-danger');
                            }
                            else{
                                $('#pan-err').text("PAN No Not available!").addClass('text-danger').removeClass('text-success');
                            }
                        }
                    });
                }
                else{
                    $('#pan-err').text("");
                    $('#updatepersonaldetails').removeClass('disabled');
                }
			});
			$('body').on('change','#bank',function(){
				var bank=$(this).val();
				if(bank=='xyz'){
					$('#bank-name').removeClass('hidden').attr('name','bank').attr('required',true);;
				}
				else{
					$('#bank-name').addClass('hidden').removeAttr('name').removeAttr('required');
				}
			});
			$('#bank').trigger('change');
        });
		
		function getPhoto(input){
			$('#view_photo').replaceWith(' <img id="view_photo" style="height:135px; width:120px;" >');
			if (input.files && input.files[0]) {
				var filename=input.files[0].name;
				var re = /(?:\.([^.]+))?$/;
				var ext = re.exec(filename)[1]; 
				ext=ext.toLowerCase();
				if(ext=='jpg' || ext=='jpeg' || ext=='png'){
					var size=input.files[0].size;
					if(size<=1024000 && size>=20480){
						var reader = new FileReader();
						
						reader.onload = function (e) {
							$('#view_photo').attr('src',e.target.result);
						}
						reader.readAsDataURL(input.files[0]);
					}
					else if(size>=1024000){
						document.getElementById('photo').value= null;
						alert("Image size is greater than 1MB");	
					}
				}
				else{
					document.getElementById('photo').value= null;
					alert("Select 'jpeg' or 'jpg' or 'png' image file!!");	
				}
			}
		}
		
		function setChosenSelect(ele){
			ele.chosen({
				disable_search_threshold: 10,
				no_results_text: "Oops, nothing found!",
				width: "100%"
			});
		}
	function getPhoto2(input,field){
		var id="#"+field;
		var preview="#"+field+"preview";
		$(preview).replaceWith('<img id="'+field+'preview" style="height:250px; width:250px;" >');
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
    
    	
