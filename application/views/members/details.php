
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
                                                <h3 class="header smaller lighter">Sponsor Details</h3>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <?php
                                                                $attributes=array("id"=>"name","Placeholder"=>"Sponsor ID","autocomplete"=>"off",'readonly'=>'true');
                                                                echo create_form_input("text","name","Sponsor ID",true,$member['susername'],$attributes);  
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <?php
                                                                $attributes=array("id"=>"name","Placeholder"=>"Sponsor Name","autocomplete"=>"off",'readonly'=>'true');
                                                                echo create_form_input("text","name","Sponsor Name",true,$member['sname'],$attributes);  
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <h3 class="header smaller lighter">Personal Details</h3>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <?php
                                                                $attributes=array("id"=>"name","Placeholder"=>"Member ID","autocomplete"=>"off",'readonly'=>'true');
                                                                echo create_form_input("text","name","Member ID",true,$user['username'],$attributes);  
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <?php
                                                                $attributes=array("id"=>"name","Placeholder"=>"Full Name","autocomplete"=>"off",'readonly'=>'true');
                                                                echo create_form_input("text","name","Name",true,$member['name'],$attributes);  
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <?php
                                                                $attributes=array("id"=>"mobile","Placeholder"=>"Mobile","autocomplete"=>"off","pattern"=>"[0-9]{10}","title"=>"Enter Valid Mobile No.","maxlength"=>"10",'readonly'=>'true');
                                                                echo create_form_input("text","mobile","Mobile",true,$member['mobile'],$attributes);  
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <?php
                                                                $attributes=array("id"=>"email","Placeholder"=>"Email","autocomplete"=>"off",'readonly'=>'true');
                                                                echo create_form_input("email","email","Email",false,$member['email'],$attributes);  
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <?php
                                                                $attributes=array("id"=>"address","Placeholder"=>"Address","autocomplete"=>"off",'readonly'=>'true','rows'=>2);
                                                                echo create_form_input("textarea","address","Address",false,$member['address'],$attributes);  
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php /*?><h3 class="header smaller lighter">Nominee Details</h3>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <?php
                                                                $attributes=array("id"=>"name","Placeholder"=>"Nominee Name","autocomplete"=>"off",'readonly'=>'true');
                                                                echo create_form_input("text","name","Nominee Name.",false,$nominee_details['name'],$attributes);  
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <?php
                                                                $attributes=array("id"=>"name","Placeholder"=>"Nominee Mobile No","autocomplete"=>"off",'readonly'=>'true');
                                                                echo create_form_input("text","mobile","Nominee Mobile No.",false,$nominee_details['mobile'],$attributes);  
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <?php
                                                                $attributes=array("id"=>"account_no","Placeholder"=>"Nominee Relation","autocomplete"=>"off",'readonly'=>'true');
                                                                echo create_form_input("text","relation","Nominee Relation",false,$nominee_details['relation'],$attributes);  
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <h3 class="header smaller lighter">Account Details</h3>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <?php
                                                                $attributes=array("id"=>"account_name","Placeholder"=>"A/C Holder Name","autocomplete"=>"off",'readonly'=>'true');
                                                                echo create_form_input("text","account_name","A/C Holder Name.",false,$acc_details['account_name'],$attributes);  
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="bank">Bank Name</label>
                                                            <?php
                                                                $attributes=array("id"=>"bank","Placeholder"=>"Bank Name","autocomplete"=>"off",'readonly'=>'true');
                                                                echo create_form_input("text","bank","Bank Name.",false,$acc_details['bank'],$attributes);  
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <?php
                                                                $attributes=array("id"=>"account_no","Placeholder"=>"A/C No.","autocomplete"=>"off",'readonly'=>'true');
                                                                echo create_form_input("text","account_no","A/C No.",false,$acc_details['account_no'],$attributes);  
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div><?php */?>
                                                <h3 class="header smaller lighter">Withdrawal</h3>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <?php
                                                                $attributes=array("id"=>"address","Placeholder"=>"Withdrawal Address","autocomplete"=>"off",'readonly'=>'true');
                                                                echo create_form_input("text","address","Withdrawal Address",false,$acc_details['address'],$attributes);  
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <h3 class="header smaller lighter">Wallet Details</h3>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <?php
                                                                $attributes=array("id"=>"total","Placeholder"=>"Total Income","autocomplete"=>"off",'readonly'=>'true');
                                                                echo create_form_input("text","total","Total Income",false,$wallet['total'],$attributes);  
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <?php
                                                                $attributes=array("id"=>"bankwithdrawal","Placeholder"=>"Total Withdrawal","autocomplete"=>"off",'readonly'=>'true');
                                                                echo create_form_input("text","bankwithdrawal","Total Withdrawal",false,$wallet['bankwithdrawal'],$attributes);  
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <?php
                                                                $attributes=array("id"=>"epingeneration","Placeholder"=>"E-Pin Generated","autocomplete"=>"off",'readonly'=>'true');
                                                                echo create_form_input("text","epingeneration","E-Pin Generated",false,$wallet['epingeneration'],$attributes);  
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <?php
                                                                $attributes=array("id"=>"actualwallet","Placeholder"=>"Available Balance","autocomplete"=>"off",'readonly'=>'true');
                                                                echo create_form_input("text","actualwallet","Available Balance",false,$wallet['actualwallet'],$attributes);  
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
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
                </script>
    
