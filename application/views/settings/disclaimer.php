

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
                                            <div class="col-md-2"></div>
                                            <div class="col-md-8 hidden" id="formdiv">
                                                <?= form_open_multipart('settings/savedisclaimer/'); ?>
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label">Disclaimer</label>
                                                        <div class="col-sm-10">
                                                            <textarea name="disclaimer" id="disclaimer" rows="3" class="form-control"></textarea>
                                                        </div>
                                                    </div><br>
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label"></label>
                                                        <div class="col-sm-10">
                                                            <input type="hidden" name="id" id="id">
                                                            <input type="submit" class="btn btn-success waves-effect waves-light" name="savedisclaimer" value="Save Disclaimer">
                                                            <button type="button" class="btn btn-danger waves-effect waves-light cancel-btn hidden" onClick="window.location.reload();">Cancel</button>
                                                        </div>
                                                    </div>
                                                <?= form_close(); ?>
                                            </div>
                                        </div><br>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table class="table table-condensed" id="table">
                                                        <thead>
                                                            <tr>
                                                                <th>Sl.No.</th>
                                                                <th width="70%">Disclaimer</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php 
                                                                if(!empty($disclaimers) && is_array($disclaimers)){$i=0;
                                                                    foreach($disclaimers as $single){ $i++;
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $i; ?></td>
                                                                <td><?= $single['disclaimer']; ?></td>
                                                                <td>
                                                                    <button type="button" class="btn btn-xs btn-info edit-btn" value="<?php echo $single['id']; ?>" data-disclaimer="<?= $single['disclaimer']; ?>"><i class='fa fa-edit' ></i></button>
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
                            $('#disclaimer').val($(this).data('disclaimer'));
                            $('#formdiv,.cancel-btn').removeClass('hidden');
                            $('html, body').animate({scrollTop: 0}, 800);
                            $('input[type="submit"]').attr({name:'updatedisclaimer',value:'Update Disclaimer'});
                            $('form').attr('action','<?= base_url('settings/updatedisclaimer'); ?>');
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
                        if(!confirm("Confirm Delete Disclaimer?")){
                           return false;
                        }
                    }
                </script>
	