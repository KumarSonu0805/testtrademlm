

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
                                                <div class="table-responsive">
                                                    <table class="table table-condensed" id="table">
                                                        <thead>
                                                            <tr>
                                                                <th>Sl.No.</th>
                                                                <th width="70%">QR Image</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php 
                                                                if(!empty($qrimages) && is_array($qrimages)){$i=0;
                                                                    foreach($qrimages as $single){ $i++;
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $i; ?></td>
                                                                <td><img src="<?= file_url($single['qrimage']); ?>" alt="qr image" height="350"></td>
                                                                <td>
                                                                    <button type="button" class="btn btn-xs btn-info edit-btn" value="<?php echo $single['id']; ?>" data-qrimage="<?= file_url($single['qrimage']); ?>"><i class='fa fa-edit' ></i></button>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                                    }
                                                                }
                                                            ?>
                                                            <?php 
                                                                if(!empty($disclaimers) && is_array($disclaimers)){
                                                                    foreach($disclaimers as $single){ $i++;
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $i; ?></td>
                                                                <td><?= $single['disclaimer']; ?></td>
                                                                <td>
                                                                    <button type="button" class="btn btn-xs btn-info edit-btn2" value="<?php echo $single['id']; ?>" data-address="<?= $single['disclaimer']; ?>"><i class='fa fa-edit' ></i></button>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                                    }
                                                                }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-md-6 hidden" id="formdiv">
                                                <?= form_open_multipart('settings/saveqrimage/'); ?>
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label">Image</label>
                                                        <div class="col-sm-10">
                                                            <input type="file" name="qrimage" id="image" onChange="getPhoto(this)" required><br>
                                                            <img id="preview" alt=""  style="max-height:300px;">
                                                        </div>
                                                    </div><br>
                                                    <div class="form-group row hidden qr-to-show">
                                                        <label class="col-sm-2 col-form-label">OTP</label>
                                                        <div class="col-sm-10">
                                                            <?php
                                                                $input=array("name"=>"otp","id"=>"qr-otp",'required'=>'true',
                                                                             "Placeholder"=>"OTP","class"=>"form-control", "autocomplete"=>"off");
                                                                echo form_input($input);
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row qr-to-hide">
                                                        <label class="col-sm-2 col-form-label"></label>
                                                        <div class="col-sm-10">
                                                            <button type="button" class="btn btn-sm btn-success " id="send-qr-otp" >Send OTP</button>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row hidden qr-to-show">
                                                        <label class="col-sm-2 col-form-label"></label>
                                                        <div class="col-sm-10">
                                                            <input type="hidden" name="id" id="id">
                                                            <input type="submit" class="btn btn-success waves-effect waves-light" name="saveqrimage" value="Save QR Image">
                                                            <button type="button" class="btn btn-danger waves-effect waves-light cancel-btn hidden" onClick="window.location.reload();">Cancel</button>
                                                        </div>
                                                    </div>
                                                <?= form_close(); ?>
                                            </div>
                                            <div class="col-md-6 hidden" id="formdiv2">
                                                <?= form_open_multipart('settings/saveaddress/'); ?>
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label">Address</label>
                                                        <div class="col-sm-10">
                                                            <textarea name="address" id="address" rows="3" class="form-control"></textarea>
                                                        </div>
                                                    </div><br>
                                                    <div class="form-group row hidden to-show">
                                                        <label class="col-sm-2 col-form-label">OTP</label>
                                                        <div class="col-sm-10">
                                                            <?php
                                                                $input=array("name"=>"otp","id"=>"otp",'required'=>'true',
                                                                             "Placeholder"=>"OTP","class"=>"form-control", "autocomplete"=>"off");
                                                                echo form_input($input);
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row to-hide">
                                                        <label class="col-sm-2 col-form-label"></label>
                                                        <div class="col-sm-10">
                                                            <button type="button" class="btn btn-sm btn-success " id="send-address-otp" >Send OTP</button>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row hidden to-show">
                                                        <label class="col-sm-2 col-form-label"></label>
                                                        <div class="col-sm-10">
                                                            <input type="hidden" name="id" id="id">
                                                            <input type="submit" class="btn btn-success waves-effect waves-light" name="saveaddress" value="Save Disclaimer">
                                                            <button type="button" class="btn btn-danger waves-effect waves-light cancel-btn2 hidden" onClick="window.location.reload();">Cancel</button>
                                                        </div>
                                                    </div>
                                                <?= form_close(); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
<img src="<?= file_url('assets/images/loading.gif'); ?>" alt="loader" id="loader" style="display: none">
                <script>
                    $(document).ready(function(e) {
                        $('table').on('click','.edit-btn',function(){
                            $('#id').val($(this).val());
                            $('#preview').attr('src',$(this).data('qrimage'));
                            $('#formdiv,.cancel-btn').removeClass('hidden');
                            $('html, body').animate({scrollTop: 0}, 800);
                            $('input[type="submit"]').attr({name:'saveqrimage',value:'Update QR Image'});
                            $('form').attr('action','<?= base_url('settings/saveqrimage'); ?>');
                        });
                        $('.cancel-btn').click(function(){
                            $('#slug,#name,#image').val('');
                            $('#formdiv,.cancel-btn').addClass('hidden');
                        });
                        $('table').on('click','.edit-btn2',function(){
                            $('#id').val($(this).val());
                            $('#address').val($(this).data('address'));
                            $('#formdiv2,.cancel-btn2').removeClass('hidden');
                            $('html, body').animate({scrollTop: 0}, 800);
                            $('input[type="submit"]').attr({name:'updateaddress',value:'Update Address'});
                            $('form').attr('action','<?= base_url('settings/updateaddress'); ?>');
                        });
                        $('.cancel-btn2').click(function(){
                            $('#slug,#name,#image').val('');
                            $('#formdiv2,.cancel-btn2').addClass('hidden');
                        });
                        $('#table').dataTable();
                        
                        $('body').on('click','#send-qr-otp',function(){
                            var fileInput = document.getElementById('image');
                            var files = fileInput.files;

                            if (files.length === 0) {
                                alert("First Select QR Image");
                                return false;   
                            }
                            $.ajax({
                                type:"POST",
                                url:"<?php echo base_url('settings/createotp'); ?>",
                                data:{address:'image'},
                                beforeSend: function(data){
                                    $('.qr-to-hide .col-sm-10').replaceWith($('#loader'));
                                    $('#loader').show();
                                },
                                success: function(data){
                                    $('#loader').hide();
                                    data=JSON.parse(data);
                                    if(data['status']===true){
                                        $('.qr-to-show').removeClass('hidden');
                                        $('.qr-to-hide').addClass('hidden');
                                    }
                                    else{
                                        window.location.reload();
                                    }
                                }
                            });
                        });
                        $('body').on('click','#send-address-otp',function(){
                            var address=$('#address').val();
                            if(address==''){
                                alert("First enter address");
                                return false;   
                            }
                            $.ajax({
                                type:"POST",
                                url:"<?php echo base_url('settings/createotp'); ?>",
                                data:{address:address},
                                beforeSend: function(data){
                                    $('.to-hide .col-sm-10').replaceWith($('#loader'));
                                    $('#loader').show();
                                },
                                success: function(data){
                                    $('#loader').hide();
                                    data=JSON.parse(data);
                                    if(data['status']===true){
                                        $('.to-show').removeClass('hidden');
                                        $('.to-hide').addClass('hidden');
                                        $('#address').attr('readonly',true);
                                    }
                                    else{
                                        window.location.reload();
                                    }
                                }
                            });
                        });
                    });

                    function getPhoto(input){
                        if (input.files && input.files[0]) {
                            var filename=input.files[0].name;
                            var re = /(?:\.([^.]+))?$/;
                            var ext = re.exec(filename)[1]; 
                            if(ext=='jpg' || ext=='jpeg' || ext=='png'){
                                var size=input.files[0].size;
                                if(size<=307200){
                                    var reader = new FileReader();
                                    //alert(input.files[0].size);
                                    reader.onload = function (e) {
                                        document.getElementById("preview").src= e.target.result;
                                    }
                                    reader.readAsDataURL(input.files[0]);
                                }
                                else if(size>=307200){
                                    document.getElementById('image').value= null;
                                    alert("Image size is greater than 300KB");	
                                    $('#preview').replaceWith('<img id="preview" alt="" style="max-height:300px;">');
                                }
                            }
                            else{
                                document.getElementById('image').value= null;
                                alert("Select 'jpeg' or 'jpg' or 'png' image file!!");	
                                $('#preview').replaceWith('<img id="preview" alt="" style="max-height:300px;">');
                            }
                        }
                        else{
                            $('#preview').replaceWith('<img id="preview" alt="" style="max-height:300px;">');
                        }
                    }
                    function validate(){
                        if(!confirm("Confirm Delete whatsappno?")){
                           return false;
                        }
                    }
                </script>
	