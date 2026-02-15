
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
                                                    <table class="table table-condensed">
                                                        <thead>
                                                            <tr>
                                                                <th>Sl.No.</th>
                                                                <th>Package</th>
                                                                <th>Withdrawal Amount</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php 
                                                                if(!empty($packages) && is_array($packages)){$i=0;
                                                                    foreach($packages as $single){ 
                                                                        $i++;
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $i; ?></td>
                                                                <td><?php echo $single['package']; ?></td>
                                                                <td><?php echo $single['min_withdrawal']; ?></td>
                                                                <td>
                                                                    <button type="button" class="btn btn-xs btn-info edit-btn" value="<?= $single['id']; ?>" data-value="<?= $single['min_withdrawal']; ?>" data-package="<?= $single['package']; ?>"><i class="fa fa-edit"></i></button>
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
                                            <div class="col-md-6 hidden" id="form-div">
                                                <?= form_open_multipart('wallet/updatepackage/'); ?>
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label">Package</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" class="form-control" id="package" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label">Withdrawal Amount</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" class="form-control" name="min_withdrawal" id="min_withdrawal" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label"></label>
                                                        <div class="col-sm-10">
                                                            <input type="hidden" name="id" id="id">
                                                            <input type="submit" class="btn btn-success waves-effect waves-light" name="updatepackage" value="Update Withdrawal Amount">
                                                            <button type="button" class="btn btn-danger waves-effect waves-light cancel-btn hidden">Cancel</button>
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
                            var id=$(this).val();
                            var min_withdrawal=$(this).data('value');
                            var package=$(this).data('package');
                            $('#id').val(id);
                            $('#package').val(package);
                            $('#min_withdrawal').val(min_withdrawal);
                            $('.cancel-btn,#form-div').removeClass('hidden');
                        });
                        $('.cancel-btn').click(function(){
                            $('#package,#min_withdrawal,#id').val('');
                            $('.cancel-btn,#form-div').addClass('hidden');
                        });
                    });
            </script>