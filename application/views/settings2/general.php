<style>
    #table{
        --bs-table-bg: #ffffff00;
    }
    #table th,
    #table td{
        color: #FFFFFF;
    }
</style>

                    <div class="col-12"> 
                        <div class="user-profile-form">
                
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="table-responsive">
                                            <table class="table table-condensed" id="table">
                                                <thead>
                                                    <tr>
                                                        <th>Sl.No.</th>
                                                        <th>Name</th>
                                                        <th>Value</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if(!empty($settings)){ $i=0;
                                                        foreach($settings as $single){
                                                            //if(empty($single['value'])){ continue; }
                                                            $i++;
                                                            $value=$single['value'];
                                                            if($single['type']=='Time'){
                                                                $value=date('h:i A',strtotime($value));;
                                                            }
                                                            elseif($single['name']=='bnpl_limit'){
                                                                $value='₹'.$value;
                                                            }
                                                            elseif(strpos($single['name'],'qrcode')===0 && $value!=''){
                                                                $value='<img src="'.file_url($value).'" alt="" width="150">';
                                                            }
                                                            else{
                                                                $value.=" ".$single['type'];
                                                            }
                                                    ?>
                                                    <tr>
                                                        <td><?= $i; ?></td>
                                                        <td><?= $single['title']; ?></td>
                                                        <td><?= $value; ?></td>
                                                        <td>
                                                            <button type="button" class="btn p-1 btn-sm btn-info edit-btn" value="<?= md5('setting-'.$single['id']) ?>"><i class="fa fa-edit"></i></button>
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
                                    <div class="col-md-6">
                                        <?= form_open_multipart('settings/updatesetting/'); ?>
                                            <div class="form-group row my-2">
                                                <label class="col-sm-2 col-form-label">Name</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="title" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group row my-2">
                                                <label class="col-sm-2 col-form-label" id="value-id">Value</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" name="value" id="value" >
                                                </div>
                                            </div>
                                            <div class="form-group row my-2">
                                                <label class="col-sm-2 col-form-label"></label>
                                                <div class="col-sm-10">
                                                    <input type="hidden" name="name" id="name">
                                                    <input type="hidden" name="id" id="id">
                                                    <button type="submit" class="btn btn-success waves-effect waves-light" name="updatesetting">Update Settings</button>
                                                    <button type="reset" class="ms-2 btn btn-danger waves-effect waves-light cancel-btn" >Cancel</button>
                                                </div>
                                            </div>
                                        <?= form_close(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            <script>
                $(document).ready(function(){
                    $('table').on('click','.edit-btn',function(){
                        var id = $(this).val();
                        $('#value').removeAttr('maxlength');
                        var input='<input type="text" class="form-control" name="value" id="value" >';
                        $('#value').replaceWith(input);
                        $.ajax({
                            type:"post",
                            url:"<?= base_url('settings/getsetting'); ?>",
                            data:{id:id},
                            success:function(data){
                                if(data!='null' && data!='[]'){
                                    data=JSON.parse(data);
                                    $('#id').val(data['id']);
                                    $('#title').val(data['title']);
                                    $('#name').val(data['name']);
                                    $('#value').val(data['value']);
                                    if(data['name'].includes('qrcode')===true){
                                        $('#value').attr('type','file');
                                    }
                                    if(data['type']!=''){
                                        $('#value-id').text('Value ('+data['type']+')');
                                    }
                                    $('#value').focus();
                                }
                            }
                        });
                    });
                });
            </script>