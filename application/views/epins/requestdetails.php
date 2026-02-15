
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
                                            <div class="col-md-1"></div>
                                            <div class="col-md-10">
                                                <?php echo form_open_multipart('epins/approveepin', 'id="myform" onSubmit="return validate();"'); ?>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <?php
                                                                    echo create_form_input("text","","Member ID",false,$details['username'],array("readonly"=>"true")); 
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <?php
                                                                    echo create_form_input("text","","Name",false,$details['name'],array("readonly"=>"true")); 
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <?php
                                                                    echo create_form_input("date","","Request Date",false,$details['date'],array("readonly"=>"true")); 
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <?php
                                                                    if($details['type']=='request'){ $type="Cash"; }
                                                                    elseif($details['type']=='ewallet'){ $type="E-Wallet"; }
                                                                    else{ $type=" Pin Wallet"; }
                                                                    echo create_form_input("text","","Request Type",false,$type,array("readonly"=>"true")); 
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <?php
                                                                    echo create_form_input("text","","Transaction Type",false,$details['trans_type'],array("readonly"=>"true")); 
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <?php
                                                                    echo create_form_input("text","","Package",false,$details['package'],array("readonly"=>"true")); 
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 d-none">
                                                            <div class="form-group">
                                                                <?php
                                                                    echo create_form_input("text","quantity","No. of E-Pins",false,$details['quantity'],array("readonly"=>"true")); 
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 hidden">
                                                            <div class="form-group">
                                                                <?php
                                                                    echo create_form_input("text","extra","Extra E-Pins",false,$details['extra'],array("readonly"=>"true")); 
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 d-none">
                                                            <div class="form-group">
                                                                <?php
                                                                    echo create_form_input("text","","Total E-Pins",false,$details['quantity']+$details['extra'],array("readonly"=>"true")); 
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <?php
                                                                    echo create_form_input("text","amount","Paid Amount",false,$details['amount'],array("readonly"=>"true")); 
                                                                ?>
                                                            </div>
                                                            <?php
                                                                if($transaction_image===true && $details['trans_type']!='CASH'){ 
                                                            ?>
                                                            <div class="form-group">
                                                                <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#mediumModal">View Transaction Details</button>
                                                            </div>
                                                            <?php } ?>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <?php
                                                                    echo create_form_input("textarea","","Transaction Details",false,$details['details'],array("readonly"=>"true","rows"=>"4")); 
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6 d-none">
                                                            <div class="form-group">
                                                                <?php
                                                                    echo create_form_input("textarea","reason","Reason",false,'',array('id'=>'reason',"rows"=>"4")); 
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <?php
                                                                echo create_form_input("hidden","regid","",false,$details['regid']); 
                                                                echo create_form_input("hidden","package_id","",false,$details['package_id']); 
                                                                echo create_form_input("hidden","request_id","",false,$details['id']); 
                                                                echo create_form_input("hidden","","",false,"",array("id"=>"type")); 
                                                            ?>
                                                            <button type="submit" class="btn btn-sm btn-success action" name="approveepin" value="Approve">Approve Activation Request</button>
                                                            <?php if($reject_request===true){ ?>
                                                            <button type="button" class="btn btn-sm btn-danger action" name="approveepin" value="Reject">Reject Activation Request</button>
                                                            <?php } ?>
                                                        </div>
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
<?php if($transaction_image===true){ ?>
<div class="modal fade" id="mediumModal" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left" id="mediumModalLabel"></h5>
                <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
                	<span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img src="<?= !empty($details['image'])? file_url($details['image']):''; ?>" alt="" id="img-popup" class="img-fluid">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<script>
	$(document).ready(function(e) {
        $('body').on('click','.action',function(){
			var type=$(this).val();
			$('#type').val(type);
            if(type=='Reject'){
                $('#reason').closest('.col-md-6').removeClass('d-none');;
                $(this).prev().hide();
                $(this).attr('type','submit');
                $('#reason').prop('required',true);
            }
		});
    });
	function validate(){
		var type=$('#type').val();
		if(!confirm(type+" Activation Request for this member?")){
			return false;
		}
        if(type=='Reject'){

        }
	}
</script>