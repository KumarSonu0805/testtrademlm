
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
                                                <?php echo form_open_multipart('wallet/savedeposit', 'id="myform" onSubmit="return validate();"'); ?>
                                                    <div class="form-group">
                                                        <?php
                                                            echo create_form_input("date","date","Date",true,date('Y-m-d')); 
                                                        ?>
                                                    </div>
                                                    <div class="form-group">
                                                        <?php
                                                            echo create_form_input("text","","Member ID",false,$user['username'],array("readonly"=>"true")); 
                                                        ?>
                                                    </div>
                                                    <div class="form-group">
                                                        <?php
                                                            echo create_form_input("text","name","Name",false,$user['name'],array("readonly"=>"true")); 
                                                        ?>
                                                    </div>
                                                    <div class="form-group">
                                                        <?php
                                                            echo create_form_input("select","package_id","Package",true,'',array("id"=>"package_id"),$packages); 
                                                        ?>
                                                    </div>
                                                    <div class="form-group d-none">
                                                        <?php
                                                            echo create_form_input("text","amount","Deposit Amount",true,'',array("id"=>"amount")); 
                                                        ?>
                                                    </div>
                                                    <div class="form-group">
                                                        <?php
                                                            $type=array(""=>"Select","Net Banking"=>"Net Banking","UPI"=>"UPI");//,"CASH"=>"CASH");
                                                            //$type['ewallet']="Wallet Balance";
                                                            echo create_form_input("select","trans_type","Transaction Type",true,'',array("id"=>"trans_type"),$type); 
                                                        ?>
                                                    </div>
                                                    <div class="form-group request">
                                                        <?php
                                                            echo create_form_input("textarea","details","Transaction Details",true,'',array("id"=>"details","rows"=>"2")); 
                                                        ?>
                                                    </div>
                                                    <div class="form-group request">
                                                            <div class="row">
                                                                <div class="col-xs-6">
                                                                    <?php 
                                                                        $attributes=array("id"=>"image","onChange"=>"getPhoto(this,'image')");
                                                                        echo create_form_input("file","image","Upload Receipt:",true,'',$attributes); 
                                                                    ?>
                                                                </div>
                                                            <div class="col-xs-6">
                                                                <img  id="imagepreview" style="height:150px; width:250px;" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php
                                                        echo create_form_input("hidden","","",false,$package_price,array("id"=>"package_price")); 
                                                        echo create_form_input("hidden","regid","",false,$user['id']); 
                                                        $check=$this->db->get_where('epin_requests',['regid'=>$user['id'],'status!='=>2])->num_rows();
                                                        $this->db->order_by('id desc');
                                                        $getrequest=$this->db->get_where('epin_requests',['regid'=>$user['id']]);
                                                        $status=false;
                                                        if($getrequest->num_rows()>0){
                                                            $request=$getrequest->unbuffered_row('array');
                                                            if($request['status']==1){
                                                                $status=true;
                                                            }
                                                        }
                                                        if($status){
                                                    ?>
                                                    <button type="submit" class="btn btn-sm btn-success" name="savedeposit" value="Request">Save Deposit</button>
                                                    <?php
                                                        }
                                                        
                                                    ?>
                                                <?php echo form_close(); ?>
                                            </div>	
                                            <div class="col-md-7">
                                                <legend>Bank Information</legend>
                                                <table class="table" id="bank-details">
                                                    <tr>
                                                        <th>A/C Holder Name</th>
                                                        <td><?php echo $admin_acc_details['account_name']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Bank Name</th>
                                                        <td><?php echo strtoupper($admin_acc_details['bank']);  ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Account Number</th>
                                                        <td><?php echo $admin_acc_details['account_no']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Branch</th>
                                                        <td><?php echo $admin_acc_details['branch']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>IFSC Code</th>
                                                        <td><?php echo $admin_acc_details['ifsc']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>UPI</th>
                                                        <td><?php echo $admin_acc_details['upi']; ?></td>
                                                    </tr>
                                                </table>
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
        $('#package_id').change(function(){
			var package_id=$(this).val();
			var package_price=$('#package_price').val();
			var amount='';
			if(package_id!=''){
				package_price=JSON.parse(package_price);
				amount=package_price[package_id];
			}
			$('#amount').val(amount);
			$('#quantity').trigger('keyup');
		});
		$('#amount').keyup(function(){
			var amount=$(this).val();
			amount=amount.replace(/[^\d\.]+/,'');
			$(this).val(amount);
            var total_amount=amount*Number('<?= CONV_RATE ?>');
			$('#inr_amount').val(total_amount);
		});
		$('#trans_type').change(function(){
			var type=$(this).val();
			if(type=='USDT' || type=='Net Banking' || type=='UPI'){
				$('.request').removeClass('hidden');
				$('#details,#image').attr("required",true);
			}
			else{
				var id="#"+type;
				//$('#avl_balance').val($(id).val());
				$('.request').addClass('hidden');
				$('#details,#image').removeAttr("required");
			}
		});
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
				if(size<=2097152 && size>=20480){
					var reader = new FileReader();
					
					reader.onload = function (e) {
						$(preview).attr('src',e.target.result);
					}
					reader.readAsDataURL(input.files[0]);
				}
				else if(size>=2097152){
					document.getElementById(field).value= null;
					alert("Image size is greater than 2MB");	
				}
				else if(size<=20480){
					document.getElementById(field).value= null; 
					alert("Image size is less than 20KB");	
				}
			}
			else{
				document.getElementById(field).value= null;
				alert("Select 'jpeg' or 'jpg' or 'png' image file!!");	
			}
		}
	}
	function validate(){
		var avl=Number($('#avl_balance').val());
		var amount=Number($('#amount').val());
		if($('#type').val()!='request'){
			if(avl<amount){
				alert("Pin Amount Must be less than Available Balance!");
				return false;
			}
		}
	}
    function copyAddress() {
      // Select the link text
      const linkElement = document.getElementById('copyAddress');
      const linkText = linkElement.textContent || linkElement.innerText;

      // Use navigator.clipboard.writeText for modern browsers
      navigator.clipboard.writeText(linkText)
        .then(() => {
          alert('Address copied to clipboard!');
        })
        .catch((err) => {
          console.error('Unable to copy link', err);
        });
    }
</script>