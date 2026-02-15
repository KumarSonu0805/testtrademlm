

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
                                            <div class="col-md-6" id="formdiv">
                                                <?= form_open_multipart('settings/savenews/'); ?>
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label">Notice</label>
                                                        <div class="col-sm-10">
                                                            <textarea name="news" id="news" class="form-control" rows="3"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row d-none">
                                                        <label class="col-sm-2 col-form-label">Diplay In</label>
                                                        <div class="col-sm-10">
                                                            <select name="type" id="type" class="form-control">
                                                                <option value="dashboard">User Dashboard</option>
                                                                <option value="website">Website</option>
                                                                <option value="both">Both</option>
                                                            </select>
                                                        </div>
                                                    </div><br>
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label"></label>
                                                        <div class="col-sm-10">
                                                            <input type="hidden" name="id" id="id">
                                                            <input type="submit" class="btn btn-success waves-effect waves-light" name="savenews" value="Save News">
                                                            <button type="button" class="btn btn-danger waves-effect waves-light cancel-btn hidden" >Cancel</button>
                                                        </div>
                                                    </div>
                                                <?= form_close(); ?>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="table-responsive">
                                                    <table class="table table-condensed" id="table">
                                                        <thead>
                                                            <tr>
                                                                <th>Sl.No.</th>
                                                                <th width="60%">Notice</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php 
                                                                if(!empty($news) && is_array($news)){$i=0;
                                                                    foreach($news as $single){ $i++;
                                                                        $type='User Dashboard';
                                                                        if($single['type']=='website'){
                                                                            $type="Website";
                                                                        }
                                                                        elseif($single['type']=='both'){
                                                                            $type.=" & Website";
                                                                        }
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $i; ?></td>
                                                                <td><?= $single['news']; ?></td>
                                                                <td>
                                                                    <button type="button" class="btn btn-xs btn-info edit-btn" value="<?php echo $single['id']; ?>"><i class='fa fa-edit' ></i></button>
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
                            $.ajax({
                                type:"post",
                                url:"<?= base_url('settings/getnews/'); ?>",
                                data:{id:$(this).val()},
                                success:function(data){
                                    data=JSON.parse(data);
                                    $('#news').val(data['news']);
                                    $('#type').val(data['type']);
                                    $('#id').val(data['id']);
                                    $('.cancel-btn').removeClass('hidden');
                                    $('input[name="savenews"]').attr('name','updatenews').val('Update News');
                                }
                            });
                        });
                        $('.cancel-btn').click(function(){
                            $('#min_quantity,#extra,#id').val('');
                            $('.cancel-btn').addClass('hidden');
                            $('input[name="updatenews"]').attr('name','savenews').val('Save News');
                        });
                        $('#table').dataTable();
                    });

                    function validate(){
                        if(!confirm("Confirm Delete News?")){
                           return false;
                        }
                    }
                </script>
	