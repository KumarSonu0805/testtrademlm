

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
                                                                <th width="70%">Link</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php 
                                                                if(!empty($youtubelinks) && is_array($youtubelinks)){$i=0;
                                                                    foreach($youtubelinks as $image){ $i++;
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $i; ?></td>
                                                                <td><?= $image['link']; ?></td>
                                                                <td>
                                                                    <button type="button" class="btn btn-xs btn-info edit-btn" value="<?php echo $image['id']; ?>" data-link="<?= $image['link'] ?>"><i class='fa fa-edit' ></i></button>
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
                                                <?= form_open_multipart('settings/saveyoutubelink/'); ?>
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label">Youtube Link</label>
                                                        <div class="col-sm-10">
                                                            <input type="url" name="link" id="link" class="form-control" required >
                                                        </div>
                                                    </div><br>
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label"></label>
                                                        <div class="col-sm-10">
                                                            <input type="hidden" name="id" id="id">
                                                            <input type="submit" class="btn btn-success waves-effect waves-light" name="saveyoutubelink" value="Save Youtube Link">
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
                            $('#link').val($(this).data('link'));
                            $('#formdiv,.cancel-btn').removeClass('hidden');
                            $('html, body').animate({scrollTop: 0}, 800);
                            $('input[type="submit"]').attr({name:'updateyoutubelink',value:'Update Youtube Link'});
                            $('form').attr('action','<?= base_url('settings/updateyoutubelink'); ?>');
                        });
                        $('.cancel-btn').click(function(){
                            $('#slug,#name,#image').val('');
                            $('#formdiv,.cancel-btn').addClass('hidden');
                        });
                        $('#table').dataTable();
                    });

                    function validate(){
                        if(!confirm("Confirm Delete Youtube Link?")){
                           return false;
                        }
                    }
                </script>
	