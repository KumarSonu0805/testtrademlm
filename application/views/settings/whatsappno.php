

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
                                                                <th width="70%">Whatsapp No</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php 
                                                                if(!empty($whatsappnos) && is_array($whatsappnos)){$i=0;
                                                                    foreach($whatsappnos as $single){ $i++;
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $i; ?></td>
                                                                <td><?= $single['whatsappno']; ?></td>
                                                                <td>
                                                                    <button type="button" class="btn btn-xs btn-info edit-btn" value="<?php echo $single['id']; ?>" data-whatsappno="<?= $single['whatsappno']; ?>"><i class='fa fa-edit' ></i></button>
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
                                                <?= form_open_multipart('settings/savewhatsappno/'); ?>
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label">Whatsapp No</label>
                                                        <div class="col-sm-10">
                                                            <input name="whatsappno" id="whatsappno" class="form-control" required maxlength="10">
                                                        </div>
                                                    </div><br>
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label"></label>
                                                        <div class="col-sm-10">
                                                            <input type="hidden" name="id" id="id">
                                                            <input type="submit" class="btn btn-success waves-effect waves-light" name="savewhatsappno" value="Save Whatsapp No">
                                                            <button type="button" class="btn btn-danger waves-effect waves-light cancel-btn hidden" onClick="window.location.reload();">Cancel</button>
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
                <script>
                    $(document).ready(function(e) {
                        $('table').on('click','.edit-btn',function(){
                            $('#id').val($(this).val());
                            $('#whatsappno').val($(this).data('whatsappno'));
                            $('#formdiv,.cancel-btn').removeClass('hidden');
                            $('html, body').animate({scrollTop: 0}, 800);
                            $('input[type="submit"]').attr({name:'updatewhatsappno',value:'Update Whatsapp No'});
                            $('form').attr('action','<?= base_url('settings/updatewhatsappno'); ?>');
                        });
                        $('.cancel-btn').click(function(){
                            $('#slug,#name,#image').val('');
                            $('#formdiv,.cancel-btn').addClass('hidden');
                        });
                        $('#table').dataTable();
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
                                    $('#preview').replaceWith('<img id="preview" alt="" width="800" height="300">');
                                }
                            }
                            else{
                                document.getElementById('image').value= null;
                                alert("Select 'jpeg' or 'jpg' or 'png' image file!!");	
                                $('#preview').replaceWith('<img id="preview" alt="" width="800" height="300">');
                            }
                        }
                        else{
                            $('#preview').replaceWith('<img id="preview" alt="" width="800" height="300">');
                        }
                    }
                    function validate(){
                        if(!confirm("Confirm Delete whatsappno?")){
                           return false;
                        }
                    }
                </script>
	