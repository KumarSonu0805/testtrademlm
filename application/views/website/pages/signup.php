

    <div class="container-fluid bg-breadcrumb">
        <div class="container text-center py-5" style="max-width: 900px;">
            <h4 class="text-white display-4 mb-4 wow fadeInDown" data-wow-delay="0.1s">Register</h4>
            <ol class="breadcrumb d-flex justify-content-center mb-0 wow fadeInDown" data-wow-delay="0.3s">
                <li><a href="<?= base_url(); ?>" style="color:white">Home / </a></li>
                <li class="breadcrumb-item active text-light">&nbsp;Register</li>
            </ol>
        </div>
    </div>


    <div class="container-fluid contact bg-light py-5">
        <div class="container py-5">
            <div class="text-center mx-auto pb-5 wow fadeInUp" data-wow-delay="0.2s" style="max-width: 800px;">
                <h4 class="text-primary">Register</h4>
                <h1 class="display-4 mb-4">Register your account</h1>
            </div>
            <div class="row g-5">
                <div class="col-xl-12 wow fadeInRight" data-wow-delay="0.4s">
                    <div>
                        <?php echo form_open_multipart('members/addmember', 'id="myform" onsubmit="return validate()" class="account__form needs-validation" novalidate'); ?>
                            <div class="row g-3">
                                <div class="col-lg-12 col-xl-12">
                                    <div class="form-floating">
                                        <?php
                                            $sponsor=$this->input->get('sponsor');
                                            $sponsor=!empty($sponsor)?$sponsor:'';
                                            $attributes=array("id"=>"ref","Placeholder"=>"Sponsor Id","autocomplete"=>"off",
                                                              'class'=>'border-0');
                                            echo create_form_input("text","","",true,$sponsor,$attributes); 
                                            echo '<label for="ref">Sponsor ID :</label>'; 
                                            echo create_form_input("hidden","refid","",false,'',array("id"=>"refid")); 
                                        ?>
                                        <div style="padding:0 10px; font-size:16px; font-weight:600" id="refdiv"></div>
                                        
                                    </div>
                                </div>
                                <div class="col-lg-12 col-xl-12">
                                    <div class="form-floating">
                                        <?php
                                            $attributes=array("id"=>"refname","Placeholder"=>"Sponsor Name",
                                                              "autocomplete"=>"off","readonly"=>true,'class'=>'border-0');
                                            echo create_form_input("text","","",true,'',$attributes); 
                                        ?>
                                        <label for="refname">Sponsor name :</label>
                                    </div>
                                </div>
                                <?php
                                if($epin_status===false){
                                    $epinclass="hidden d-none";
                                    $required=false;
                                }
                                elseif($epin_status===1){
                                    $epinclass="";
                                    $required=true;
                                }
                                else{
                                    $epinclass="";
                                    $required=false;
                                }
                                ?>
                                <div class="col-12 col-md-6 <?= $epinclass; ?>">
                                    <div>
                                        <?php
                                            $attributes=array("id"=>"epin","Placeholder"=>"E-Pin","autocomplete"=>"off");
                                            echo create_form_input("text","epin","E-Pin",$required,'',$attributes);  
                                        ?>
                                        <span id="epinstatus"></span>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-xl-12">
                                    <div class="form-floating">
                                        <?php
                                            $attributes=array("id"=>"username","Placeholder"=>"Member ID",
                                                              "autocomplete"=>"off",'readonly'=>'true','class'=>'border-0');
                                            echo create_form_input("text","username","",true,$username,$attributes);  
                                        ?>
                                        <label for="username">Member ID :</label>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-xl-12">
                                    <div class="form-floating">
                                        <?php
                                            $attributes=array("id"=>"name","Placeholder"=>"Full Name","autocomplete"=>"off",
                                                              'class'=>'border-0');
                                            echo create_form_input("text","name","",true,'',$attributes);  
                                        ?>
                                        <label for="name">Full Name :</label>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-xl-12">
                                    <div class="form-floating">
                                        <?php
                                            $attributes=array("id"=>"mobile","Placeholder"=>"Mobile","autocomplete"=>"off",
                                                                  "pattern"=>"[0-9]{10}","title"=>"Enter Valid Mobile No.",
                                                                  "maxlength"=>"10",'class'=>'border-0');
                                            echo create_form_input("text","mobile","",true,'',$attributes);  
                                        ?>
                                        <label for="mobile">Mobile :</label>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-xl-12">
                                    <div class="form-floating">
                                        <?php
                                            $attributes=array("id"=>"email","Placeholder"=>"Email","autocomplete"=>"off",
                                                              'class'=>'border-0');
                                            echo create_form_input("email","email","",false,'',$attributes);  
                                        ?>
                                        <label for="email">Email :</label>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-xl-12">
                                    <div class="form-floating">
                                        <?php
                                            $attributes=array("id"=>"address","Placeholder"=>"Address","autocomplete"=>"off",
                                                              "rows"=>"3",'class'=>'border-0');
                                            echo create_form_input("textarea","address","",false,'',$attributes);  
                                        ?>
                                        <label for="address">Address :</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <button class="btn btn-primary w-100 py-3" name="addmember">Register</button>
                                </div>
                            </div>
                        <?= form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){ 
            $('#ref').keyup(function(){
                getrefid();
            }); 
            $('#ref').blur(function(){
                getrefid();
            });

            $('body').on('keyup','#epin',function(){
                var epin=$(this).val();
                var regid=$('#refid').val();
                if(regid=='' || regid==0){ 
                    alert("Enter Sponsor ID First!"); 
                    $(this).val('');$('#ref').focus(); 
                    return false;
                }
                $('#epinstatus').removeClass('text-danger').removeClass('text-success');
                $('#savebtn').attr("disabled",true);

                var elen=epin.length;
                epin=epin.trim();
                var enewlen=epin.length;
                if(elen!=enewlen){
                    $(this).val(epin);
                }

                if(epin==''){
                    $('#epinstatus').text('');
                    $('#savebtn').removeAttr("disabled");
                    return false;
                }
                $.ajax({
                    type:"POST",
                    url:"<?php echo base_url("epins/checkepin"); ?>",
                    data:{epin:epin,regid:regid},
                    success: function(data){
                        if(data=='1'){
                            $('#epinstatus').addClass('text-success').text('D-Code Available');
                            $('#savebtn').removeAttr("disabled");
                        }
                        else{
                            $('#epinstatus').addClass('text-danger').text('D-Code Not Available');
                        }
                    }
                });
            });

            if($('#ref').val()!=''){
                $('#ref').trigger('keyup');
            }
            $('body').on('change','#bank',function(){
                var bank=$(this).val();
                if(bank=='xyz'){
                    $('#bank-name').removeClass('hidden').attr('name','bank');
                }
                else{
                    $('#bank-name').addClass('hidden').removeAttr('name');
                }
            });
        });
        function getrefid(){

            var username=$('#ref').val();
            $('#refid,#refname').val('');
            $('#refdiv').removeClass('text-danger').removeClass('text-success').html('');
            $('#savebtn').attr("disabled",true);
            if(username!=''){
                $.ajax({
                    type:"POST",
                    url:"<?php echo base_url("members/getrefid/"); ?>",
                    data:{username:username,status:'activated'},
                    beforeSend: function(data){
                        $('#refdiv').html($('#dot-loader').html());
                    },
                    success: function(data){
                        data=JSON.parse(data);
                        if(data['regid']=='' || data['regid']==0){
                            $('#refdiv').html(data['name']).addClass('text-danger');
                        }else{
                            $('#refid').val(data['regid']);
                            $('#refname').val(data['name']);
                            $('#refdiv').html('').addClass('text-success');
                            $('#savebtn').removeAttr("disabled");
                        }

                    }
                });
            }
        }

        function setChosenSelect(ele){
            ele.chosen({
                disable_search_threshold: 10,
                no_results_text: "Oops, nothing found!",
                width: "100%"
            });
        }

        function validate(){
            if($('#refid').val()=='' || $('#refid').val()==0){
                alert("Enter correct Sponsor ID");
                return false;   
            }
            if($('#epinstatus').text()=='D-Code Not Available'){
                alert("Enter correct D-Code");
                return false;   
            }
            $('#savebtn').hide();
        }
    </script>
