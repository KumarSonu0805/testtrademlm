
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
                                                <?php echo form_open_multipart('profile/updatepersonaldetails', 'id="myform" onsubmit="return validate(\'Update Member Personal Details?\')"'); ?>
                                                    <h3 class="header smaller lighter">Personal Details</h3>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <?php
                                                                    $attributes=array("id"=>"name","Placeholder"=>"Full Name","autocomplete"=>"off");
                                                                    echo create_form_input("text","name","Name",true,$member['name'],$attributes);  
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <?php
                                                                    $attributes=array("id"=>"mobile","Placeholder"=>"Mobile","autocomplete"=>"off","pattern"=>"[0-9]{10}","title"=>"Enter Valid Mobile No.","maxlength"=>"10");
                                                                    echo create_form_input("text","mobile","Mobile",true,$member['mobile'],$attributes);  
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <?php
                                                                    $attributes=array("id"=>"email","Placeholder"=>"Email","autocomplete"=>"off");
                                                                    echo create_form_input("email","email","Email",false,$member['email'],$attributes);  
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <br>
                                                            <?php
                                                                $input=array("type"=>"hidden","name"=>"regid","value"=>$member['regid']);
                                                                echo form_input($input);
                                                            ?>
                                                            <button type="submit" class="btn btn-sm btn-success" name="updatepersonaldetails" value="Update" id="updatepersonaldetails">Update Personal Details</button>
                                                            <a href="<?php echo base_url('members/memberlist/'); ?>" class="btn btn-sm btn-danger">Cancel</a>
                                                        </div>
                                                    </div>
                                                <?= form_close(); ?><hr>
                                                <?php echo form_open_multipart('profile/updateaccdetails', 'id="myform" onsubmit="return validate(\'Update Withdrawal Address?\')"'); ?>
                                                    <h3 class="header smaller lighter">Withdrawal Details</h3>
                                                    <?php /*?><div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <?php
                                                                    $attributes=array("id"=>"account_name","Placeholder"=>"A/C Holder Name","autocomplete"=>"off");
                                                                    echo create_form_input("text","account_name","A/C Holder Name.",false,$acc_details['account_name'],$attributes);  
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="bank">Bank Name</label>
                                                                <?php
                                                                    $bank="";
                                                                    if($acc_details['bank']!=''){
                                                                        if(in_array($acc_details['bank'],$banks)){
                                                                            $bank=$acc_details['bank'];
                                                                            $acc_details['bank']='';
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
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <?php
                                                                    $attributes=array("id"=>"account_no","Placeholder"=>"A/C No.","autocomplete"=>"off");
                                                                    echo create_form_input("text","account_no","A/C No.",false,$acc_details['account_no'],$attributes);  
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <?php
                                                                    $attributes=array("id"=>"branch","Placeholder"=>"Branch","autocomplete"=>"off");
                                                                    echo create_form_input("text","branch","Branch",false,$acc_details['branch'],$attributes);  
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <?php
                                                                    $attributes=array("id"=>"ifsc","Placeholder"=>"IFSC Code","autocomplete"=>"off");
                                                                    echo create_form_input("text","ifsc","IFSC Code",false,$acc_details['ifsc'],$attributes);  
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div><?php */?>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <?php
                                                                    $attributes=array("id"=>"address","Placeholder"=>"Withdrawal Address","autocomplete"=>"off");
                                                                    echo create_form_input("text","address","Withdrawal Address",false,$acc_details['address'],$attributes);  
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <?php
                                                                $input=array("type"=>"hidden","name"=>"regid","value"=>$member['regid']);
                                                                echo form_input($input);
                                                            ?>
                                                            <button type="submit" class="btn btn-sm btn-success" name="updateaccdetails" value="Update" id="updateaccdetails">Update Address</button>
                                                            <a href="<?php echo base_url('members/memberlist/'); ?>" class="btn btn-sm btn-danger">Cancel</a>
                                                        </div>
                                                    </div><br>
                                                <?php echo form_close(); ?><hr>
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
                        $('body').on('change','#bank',function(){
                            var bank=$(this).val();
                            if(bank=='xyz'){
                                $('#bank-name').removeClass('hidden').attr('name','bank');
                            }
                            else{
                                $('#bank-name').addClass('hidden').removeAttr('name');
                            }
                        });
                        $('#bank').trigger('change');
                    });

                    function createDatatable(){
                        $('#status').html('');
                        table=$('#bootstrap-data-table-export').DataTable({
                                dom: 'Bflrtip',
                                buttons: [
                                    {
                                        extend: 'excel',
                                        className: 'btn btn-info'
                                    },
                                    {
                                        extend: 'pdf',
                                        className: 'btn btn-info'
                                    }
                                ]
                            });
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
                    function validate(msg){
                        if(!confirm(msg)){
                            return false;
                        }
                    }
                </script>
    
