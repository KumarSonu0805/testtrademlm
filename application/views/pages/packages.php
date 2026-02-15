                                
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header"><?= $title; ?></div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <?= form_open_multipart('packages/savepackage/'); ?>
                                                            <div class="form-group row">
                                                                <label class="col-sm-2 col-form-label">Package</label>
                                                                <div class="col-sm-10">
                                                                    <input type="text" class="form-control" name="package" id="package" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-sm-2 col-form-label">Amount</label>
                                                                <div class="col-sm-10">
                                                                    <input type="text" class="form-control" name="amount" id="amount" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-sm-2 col-form-label">Daily Income</label>
                                                                <div class="col-sm-10">
                                                                    <input type="text" class="form-control" name="daily_bonus" id="daily_bonus" required>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-sm-2 col-form-label"></label>
                                                                <div class="col-sm-10">
                                                                    <input type="hidden" name="type" id="type" value="joining">
                                                                    <input type="hidden" name="id" id="id">
                                                                    <input type="submit" class="btn btn-success waves-effect waves-light" name="savepackage" value="Save Package">
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
                                                                        <th>Package</th>
                                                                        <th>Amount</th>
                                                                        <th>Daily Income</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php 
                                                                        if(!empty($packages) && is_array($packages)){$i=0;
                                                                            foreach($packages as $package){ 
                                                                                $i++;
                                                                                $id=md5('package-'.$package['id']);
                                                                                $count=0;$this->db->get_where('members',['package_id'=>$package['id']])->num_rows();
                                                                    ?>
                                                                    <tr>
                                                                        <td><?php echo $i; ?></td>
                                                                        <td><?php echo $package['package']; ?></td>
                                                                        <td><?php echo $package['amount']; ?></td>
                                                                        <td><?php echo $package['daily_bonus']; ?></td>
                                                                        <td>
                                                                            <?php
                                                                                if($count==0){
                                                                            ?>
                                                                            <button type="button" class="btn btn-xs btn-info edit-btn" value="<?= $package['id']; ?>"><i class="fa fa-edit"></i></button>
                                                                            <?php
                                                                                }
                                                                            ?>
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
<script>
	$(document).ready(function(e) {
        $('table').on('click','.edit-btn',function(){
            var id=$(this).val();
            $.ajax({
                type:"post",
                url:"<?= base_url('packages/getpackage/'); ?>",
                data:{id:$(this).val()},
                success:function(data){
                    data=JSON.parse(data);
                    $('#package').val(data['package']);
                    $('#amount').val(data['amount']);
                    $('#daily_bonus').val(data['daily_bonus']);
                    $('#id').val(data['id']);
                    $('.cancel-btn').removeClass('hidden');
                    $('input[name="savepackage"]').attr('name','updatepackage').val('Update Package');
                }
            });
            
        });
        $('.cancel-btn').click(function(){
            $('#name,#id').val('');
            $('.cancel-btn').addClass('hidden');
            $('input[name="updatepackage"]').attr('name','savepackage').val('Save package');
        });
        $('#table').dataTable();
    });
function getPhoto(input){
    
}
</script>