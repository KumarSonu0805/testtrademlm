
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
                                                <?= form_open_multipart('wallet/savetoepinwallet/', 'id="myform"'); ?>
                                                    <div class="form-group">
                                                        <?php
                                                            echo create_form_input("date","date","Date",true,date('Y-m-d')); 
                                                        ?>
                                                    </div>
                                                    <div class="form-group">
                                                        <?php
                                                            echo create_form_input("text","","Member ID",true,'',['id'=>'member_id']); 
                                                            echo create_form_input("hidden","regid","Regid",false,'',['id'=>'regid']); 
                                                        ?>
                                                        <div id="memdiv"></div>
                                                    </div>
                                                    <div class="form-group">
                                                        <?php
                                                            echo create_form_input("text","","Member Name",false,'',["readonly"=>"true",'id'=>'member_name']); 
                                                        ?>
                                                    </div>
                                                    <div class="form-group">
                                                        <select name="" id="type" class="form-control">
                                                            <option value="add">Add to E-Pin Wallet</option>
                                                            <option value="reverse">Reverse from  E-Pin Wallet</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <?php
                                                            echo create_form_input("number","amount","Amount",true,'',['min'=>1,'id'=>'amount']); 
                                                        ?>
                                                    </div>
                                                    <button type="submit" class="btn btn-sm btn-success" name="savetoepinwallet" value="Save" id="savebtn">Save to E-Pin Wallet</button>
                                                <?= form_close(); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <div id="dot-loader" class="hidden"><img src="<?php echo file_url('assets/images/loading.gif'); ?>" alt="" height="15"></div>

                <script>
                    $(document).ready(function(e) {
                        $('body').on('keyup','#member_id',function(){
                            var username=$(this).val();
                            $('#regid,#member_name').val('');
                            $('#memdiv').removeClass('text-danger').removeClass('text-success').html('');
                            $('#savebtn').attr("disabled",true);
                            $.ajax({
                                type:"POST",
                                url:"<?php echo base_url("members/getmember/"); ?>",
                                data:{username:username},
                                beforeSend: function(data){
                                    $('#memdiv').html($('#dot-loader').html());
                                },
                                success: function(data){
                                    data=JSON.parse(data);
                                    if(data['regid']=='' || data['regid']==0){
                                        $('#memdiv').html(data['name']).addClass('text-danger');
                                    }else{
                                        $('#regid').val(data['regid']);
                                        $('#member_name').val(data['name']);
                                        $('#memdiv').html('').addClass('text-success');
                                        $('#savebtn').removeAttr("disabled");
                                    }

                                }
                            });
                        });
                        $('body').on('change','#type',function(){
                            $('#amount').val('');
                            if($(this).val()=='add'){
                                $('#amount').removeAttr('max');
                                $('#amount').attr('min',1);   
                            }
                            else{
                                $('#amount').removeAttr('min');
                                $('#amount').attr('max',-1);   
                            }
                        });
                    });
                    function validate(){
                        if(!confirm("Add package for this member?")){
                            return false;
                        }
                    }
                </script>