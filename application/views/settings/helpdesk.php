

                <section class="content">
                    <div class="container-fluid">
                        <div class="row" id="formdiv">
                            <div class="col-md-12">
                                <div class="card light-bg">
                                    <div class="card-header">
                                        <h3 class="card-title">Help Desk</h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-2"></div>
                                            <div class="col-md-8">
                                                <?= form_open_multipart('settings/saveticket/'); ?>
                                                    <div class="form-group">
                                                        <?= create_form_input('textarea','message','Enter Your Query',true,'',['rows'=>'3','id'=>'message']); ?>
                                                    </div><br>
                                                    <div class="form-group ">
                                                        <input type="submit" class="btn btn-success waves-effect waves-light" name="saveticket" value="Update Ticket">
                                                        <button type="button" class="btn btn-danger waves-effect waves-light cancel-btn hidden" onClick="window.location.reload();">Cancel</button>
                                                    </div>
                                                <?= form_close(); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card light-bg">
                                    <div class="card-header">
                                        <h3 class="card-title">Previous Tickets</h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive">
                                                    <table class="table table-condensed" id="table">
                                                        <thead>
                                                            <tr>
                                                                <th>Sl.No.</th>
                                                                <th>Date</th>
                                                                <th>Ticket No</th>
                                                                <th>Member Id</th>
                                                                <th>Member Name</th>
                                                                <th>Message</th>
                                                                <th>Status</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php 
                                                                if(!empty($messages) && is_array($messages)){$i=0;
                                                                    foreach($messages as $single){ $i++;
                                                                        $status="<span class='text-danger'>New</span>";
                                                                        if($single['status']==2){
                                                                            $status="<span class='text-danger'>Open</span>";
                                                                        }
                                                                        elseif($single['status']==1){
                                                                            $status="<span class='text-success'>Closed</span>";
                                                                        }
                                                            ?>
                                                            <tr>
                                                                <td><?= $i; ?></td>
                                                                <td><?= date('d-m-Y H:i a',strtotime($single['added_on'])); ?></td>
                                                                <td><?= $single['ticket_no']; ?></td>
                                                                <td><?= $single['username']; ?></td>
                                                                <td><?= $single['name']; ?></td>
                                                                <td><?= $single['message']; ?></td>
                                                                <td><?= $status; ?></td>
                                                                <td>
                                                                    <button type="button" class="btn btn-sm btn-info btn-view" data-toggle="modal" data-target="#modal-default" value="<?= $single['id'] ?>">
                                                                    <i class="fa fa-eye"></i></button>
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
<div class="modal fade" id="modal-default">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Ticket Details</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table" id="messages">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>User</th>
                                    <th>Message</th>
                                </tr>
                            </thead>
                            <tbody>
                            
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
                <script>
                    $(document).ready(function(e) {
                        $('table').on('click','.btn-update',function(){
                            $('#id').val($(this).val());
                            $('#formdiv,.cancel-btn').removeClass('hidden');
                            $('#message').focus();
                        });
                        $('table').on('click','.btn-view',function(){
                            var id= $(this).val();
                            $('#message tbody').html('');
                            $.ajax({
                                type:"post",
                                url:'<?= base_url('settings/getmessages'); ?>',
                                data:{id:id},
                                success:function(data){
                                    $('#messages tbody').html(data);
                                }
                            });
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
	