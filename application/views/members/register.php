
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
                                                <?php echo form_open_multipart('members/addmember', 'id="myform" onsubmit="return validate()"'); ?>
                                                    <h3 class="header smaller lighter">Sponsor Details</h3>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <?php
                                                                    $attributes=array("id"=>"ref","Placeholder"=>"Sponsor Id","autocomplete"=>"off");
                                                                    if($this->session->role=='member'){ $attributes["readonly"]="true"; }
                                                                    echo create_form_input("text","","Sponsor ID",true,$user['username'],$attributes); 
                                                                    echo create_form_input("hidden","refid","",false,$user['id'],array("id"=>"refid")); 
                                                                ?>
                                                                <div style="padding:0 10px; font-size:16px; font-weight:600" id="refdiv"></div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <?php
                                                                    $attributes=array("id"=>"refname","Placeholder"=>"Sponsor Name","autocomplete"=>"off","readonly"=>true);
                                                                    echo create_form_input("text","","Sponsor Name",true,$user['name'],$attributes); 
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <?php
                                                        if($epin_status===false){
                                                            $epinclass="hidden";
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
                                                        <div class="col-md-4 <?= $epinclass; ?>">
                                                            <div class="form-group">
                                                                <?php
                                                                    $attributes=array("id"=>"epin","Placeholder"=>"E-Pin","autocomplete"=>"off");
                                                                    echo create_form_input("text","epin","E-Pin",$required,'',$attributes);  
                                                                ?>
                                                                <span id="epinstatus"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <h3 class="header smaller lighter">Personal Details</h3>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <?php
                                                                    $attributes=array("id"=>"username","Placeholder"=>"Member ID","autocomplete"=>"off",'readonly'=>'true');
                                                                    echo create_form_input("text","username","Member ID",true,$username,$attributes);  
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <?php
                                                                    $attributes=array("id"=>"name","Placeholder"=>"Full Name","autocomplete"=>"off");
                                                                    echo create_form_input("text","name","Name",true,'',$attributes);  
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <?php
                                                                    $attributes=array("id"=>"mobile","Placeholder"=>"Mobile","autocomplete"=>"off","pattern"=>"[0-9]{10}","title"=>"Enter Valid Mobile No.","maxlength"=>"10");
                                                                    echo create_form_input("text","mobile","Mobile",true,'',$attributes);  
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <?php
                                                                    $attributes=array("id"=>"email","Placeholder"=>"Email","autocomplete"=>"off");
                                                                    echo create_form_input("email","email","Email",false,'',$attributes);  
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 d-none">
                                                            <div class="form-group">
                                                                <?php
                                                                    $attributes=array("id"=>"address","Placeholder"=>"Address","autocomplete"=>"off","rows"=>"3");
                                                                    echo create_form_input("textarea","address","Address",false,'',$attributes);  
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row hidden">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <?php
                                                                    echo create_form_input("date","dob","Date Of Birth",false,'',array("id"=>"dob"));  
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                               <?php
                                                                    $attributes=array("id"=>"father","Placeholder"=>"Father's Name","autocomplete"=>"off");
                                                                    echo create_form_input("text","father","Father's Name",false,'',$attributes);  
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 ">
                                                            <div class="form-group">
                                                                <?php
                                                                    $gender=array(""=>"Select Gender","Male"=>"Male","Female"=>"Female");
                                                                    echo create_form_input("select","gender","Gender",false,'',array("id"=>"gender"),$gender); 
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <?php
                                                                    $attributes=array("id"=>"district","Placeholder"=>"District","autocomplete"=>"off");
                                                                    echo create_form_input("text","district","District",false,'',$attributes);  
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <?php
                                                                    $attributes=array("id"=>"state","Placeholder"=>"State","autocomplete"=>"off");
                                                                    echo create_form_input("text","state","State",false,'',$attributes);  
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row hidden">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <?php
                                                                    $mstatus=array(""=>"Select","Married"=>"Married","Unmarried"=>"Unmarried");
                                                                    echo create_form_input("select","mstatus","Marital Status",false,'',array("id"=>"mstatus"),$mstatus); 
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <?php
                                                                    $attributes=array("id"=>"aadhar","Placeholder"=>"Aadhar No.","pattern"=>"[0-9]{12}","title"=>"Enter Valid Aadhar No.","autocomplete"=>"off","maxlength"=>"12");
                                                                    echo create_form_input("text","aadhar","Aadhar No.",false,'',$attributes);  
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row hidden">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <?php
                                                                    $attributes=array("id"=>"pincode","Placeholder"=>"Pincode","pattern"=>"[0-9]{6}","title"=>"Enter Valid Pincode","autocomplete"=>"off","maxlength"=>"6");
                                                                    echo create_form_input("text","pincode","Pincode",false,'',$attributes);  
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <?php
                                                                    echo create_form_input("date","date","Joining Date",true,date('Y-m-d'),array("id"=>"date"));  
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <?php
                                                                    echo create_form_input("file","photo","Upload Image",false,'',array("id"=>"photo","onChange"=>"getPhoto(this)"));  
                                                                ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <img id="view_photo" style="height:135px; width:120px;" >
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <button type="submit" class="btn btn-sm btn-success" id="savebtn" name="addmember" disabled>Submit</button>
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

                <div id="dot-loader" class="hidden"><img src="<?php echo file_url('assets/images/loading.gif'); ?>" alt="" height="15"></div>

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
                                        $('#epinstatus').addClass('text-success').text('E-Pin Available');
                                        $('#savebtn').removeAttr("disabled");
                                    }
                                    else{
                                        $('#epinstatus').addClass('text-danger').text('E-Pin Not Available');
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
                        var status='activated';
                        //if(username=='admin'){ status='activated'; }
                        $.ajax({
                            type:"POST",
                            url:"<?php echo base_url("members/getrefid/"); ?>",
                            data:{username:username,status:status},
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
                        if($('#epinstatus').text()=='E-Pin Not Available'){
                            alert("Enter correct E-Pin");
                            return false;   
                        }
                        $('#savebtn').hide();
                    }
                </script>