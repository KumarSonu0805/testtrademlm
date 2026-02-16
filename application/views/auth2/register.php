<?php
$sponsor='';
if(!empty($this->input->get('sponsor'))){
    $sponsor=$this->input->get('sponsor');
}
?>
      <section class="loginsection">
         <div class="container">
            <?= form_open('login/memberregistration','onSubmit="return validate();"'); ?>
            <div class="login-card">
               <div class="login-logo">
                  <img src="<?= file_url(LOGO) ?>" alt=" Logo" width="200" />
                  <p>Sign up your account</p>
               </div>
               <div class="mb-3">
                  <label for="memberid" class="form-label">Sponsor Id</label>
                  <input
                     type="text"
                     class="form-control"
                     id="ref"
                     placeholder="Enter Your Sponsor Id"
                         value="<?= $sponsor ?>" required
                     />
                                    <input type="hidden" name="refid" id="refid">
               </div>
                <div id="refdiv" class=" mb-3"></div>
               <div class="mb-3">
                  <label for="name" class="form-label">Sponsor Name</label>
                  <input type="text" class="form-control" name="" id="refname" placeholder="SPONSOR NAME" readonly/>
               </div>
               <div class="mb-3">
                  <label for="name" class="form-label">Name</label>
                  <input type="text" class="form-control" name="name" id="name" placeholder="Name" required/>
               </div>
               <div class="mb-3">
                  <label for="name" class="form-label">Mobile</label>
                  <input type="text" class="form-control" name="mobile" id="mobile" placeholder="Mobile" pattern="[\d]{10}" maxlength="10" title="Enter valid 10-Digit Mobile No.">
               </div>
                <div class="text-danger text-center mb-2"><?= $this->session->flashdata('reg_err_msg'); ?>
                <div class="mb-3">
                  <div class="futureBtn">
                     <button type="submit" id="savebtn" name="register">Create New Account</button>
                  </div>
               </div>
               <div class="mb-3 createpassword">
                  <div class="row">
                     <div class="col-lg-6">
                        <a href="<?= base_url('login/'); ?>">Sign Me In</a>
                     </div>
                     <div class="col-lg-6">
                     </div>
                  </div>
               </div>
            </div>
            <?= form_close(); ?>
         </div>
      </section>

            <script>
                $(document).ready(function(){ 
                    $('#ref').keyup(function(){
                        getrefid();
                    }); 
                    $('#ref').blur(function(){
                        getrefid();
                    });

                    if($('#ref').val()!=''){
                        $('#ref').trigger('keyup');
                    }
                });
                function getrefid(){

                    var username=$('#ref').val();
                    $('#refid,#refname').val('');
                    $('#refdiv').removeClass('text-danger').removeClass('text-success').html('');
                    $('#savebtn').attr("disabled",true);
                    $.ajax({
                        type:"POST",
                        url:"<?php echo base_url("members/getrefid/"); ?>",
                        data:{username:username,status:'all'},
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

            </script>