

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
                                                <?= form_open_multipart('settings/addfranchisebonus/'); ?>
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label">Minimum  E-Pin Quantity</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" class="form-control" name="min_quantity" id="min_quantity" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label">Extra E-Pins</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" class="form-control" name="extra" id="extra" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label"></label>
                                                        <div class="col-sm-10">
                                                            <input type="hidden" name="id" id="id">
                                                            <input type="submit" class="btn btn-success waves-effect waves-light" name="addfranchisebonus" value="Save Franchise Bonus">
                                                            <button type="button" class="btn btn-danger waves-effect waves-light cancel-btn hidden">Cancel</button>
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
                                                                <th>Min E-Pin Quantity</th>
                                                                <th>Extra E-Pins</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php 
                                                                if(!empty($franchisebonuses) && is_array($franchisebonuses)){$i=0;
                                                                    foreach($franchisebonuses as $single){ $i++;
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $i; ?></td>
                                                                <td><?php echo $single['min_quantity']; ?></td>
                                                                <td><?php echo $single['extra']; ?></td>
                                                                <td>
                                                                    <button type="button" class="btn btn-xs btn-info edit-btn" value="<?php echo $single['id']; ?>"><i class="fa fa-edit"></i></button>
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
                                url:"<?= base_url('settings/getfranchisebonus/'); ?>",
                                data:{id:$(this).val()},
                                success:function(data){
                                    data=JSON.parse(data);
                                    $('#min_quantity').val(data['min_quantity']);
                                    $('#extra').val(data['extra']);
                                    $('#id').val(data['id']);
                                    $('.cancel-btn').removeClass('hidden');
                                    $('input[name="addfranchisebonus"]').attr('name','updatefranchisebonus').val('Update Franchise Bonus');
                                    $('form').attr('action','<?= base_url('settings/updatefranchisebonus/'); ?>');
                                }
                            });
                        });
                        $('.cancel-btn').click(function(){
                            $('#min_quantity,#extra,#id').val('');
                            $('.cancel-btn').addClass('hidden');
                            $('input[name="updatefranchisebonus"]').attr('name','addfranchisebonus').val('Save Franchise Bonus');
                            $('form').attr('action','<?= base_url('settings/addfranchisebonus/'); ?>');
                        });
                        $('#table').dataTable();
                    });

                    function validate(){
                        if(!confirm("Confirm Delete whatsappno?")){
                           return false;
                        }
                    }
                </script>
	