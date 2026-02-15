
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
                                            <div class="col-md-12 table-responsive">
                                                <table class="table table-striped data-table" id="bootstrap-data-table-export">
                                                    <thead>
                                                        <tr>
                                                            <th>Sl No.</th>
                                                            <?php
                                                                if($this->session->role=='admin'){
                                                            ?>
                                                            <th>Member ID</th>
                                                            <th>Member Name</th>
                                                            <?php } ?>
                                                            <th>Request Date</th>
                                                            <th>Transaction Type</th>
                                                            <th>Amount</th>
                                                            <th>Transaction Details</th>
                                                            <?php
                                                                if($this->session->role=='admin'){
                                                            ?>
                                                            <th class="">Action</th>
                                                            <?php 
                                                                }else{ 
                                                            ?>
                                                            <th>Approve Date</th>
                                                            <th>Status</th>
                                                            <?php } ?>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                            $members=$members;
                                                            if(is_array($members)){$i=0;
                                                                foreach($members as $member){
                                                                    $i++;
                                                                    $status="<span class='text-danger'>Not Approved</span>";
                                                                    if($member['status']==1){
                                                                        $status="<span class='text-success'>Approved</span>";
                                                                    }elseif($member['status']==2 && $member['approved_on']===NULL){
                                                                        $status="<span class='text-danger'>Request Cancelled</span>";
                                                                    }elseif($member['status']==2){
                                                                        $status="<span class='text-danger'>Request Rejected By Admin</span>";
                                                                    }
                                                                    $imagesrc=file_url($member['image']);
                                                                    $image='<button type="button" class="btn btn-sm btn-info mb-1 view" data-toggle="modal" data-target="#mediumModal" value="'.$imagesrc.'">View Image</button>';
                                                        ?>
                                                        <tr>
                                                            <td><?= $i; ?></td>
                                                            <?php
                                                                if($this->session->role=='admin'){
                                                            ?>
                                                            <td><?php echo $member['username']; ?></td>
                                                            <td><?php echo $member['name']; ?></td>
                                                            <?php } ?>
                                                            <td><?= date('d-m-Y',strtotime($member['date'])); ?></td>
                                                            <td><?php echo $member['trans_type']; ?></td>
                                                            <td><?= $this->amount->toDecimal($member['amount']); ?></td>
                                                            <td><?= ($member['type']=='deposit' && $member['trans_type']!='CASH')?$member['details']."<br>".$image:$member['details']; ?></td>
                                                            <?php
                                                                if($this->session->role=='admin'){
                                                            ?>
                                                            <td class="">
                                                                <form action="<?php echo base_url('wallet/approvedeposit'); ?>" method="post" onSubmit="return validate('accept');" class="float-left" style="margin-right:5px;">
                                                                    <button type="submit" value="<?php echo $member['id'] ?>" name="request_id" class="btn btn-sm btn-success">Approve</button>
                                                                </form>
                                                                <form action="<?php echo base_url('wallet/rejectdeposit'); ?>" method="post" onSubmit="return validate('reject');" class="float-left">
                                                                    <button type="submit" value="<?php echo $member['id'] ?>" name="request_id" class="btn btn-sm btn-danger">Reject</button>
                                                                </form>
                                                            </td>
                                                            <?php 
                                                                }else{ 
                                                            ?>
                                                            <td><?php if($member['approved_on']!='' && $member['status']==1)echo date('d-m-Y',strtotime($member['approved_on'])); ?></td>
                                                            <td><?php echo $status; ?></td>
                                                            <?php } ?>
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
                </section>
<div class="modal fade" id="mediumModal" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left" id="mediumModalLabel"></h5>
                <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
                	<span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img src="" alt="" id="img-popup" class="img-fluid">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
                <script>
                    $(document).ready(function(e) {
                        createDatatable();
                        $('body').on('click','.view',function(){
                            $('#img-popup').attr('src','');
                            var src=$(this).val();
                            $('#img-popup').attr('src',src);
                            $('#mediumModalLabel').text($(this).text());
                        });
                    });

                    function createDatatable(){
                        $('#status').html('');
                        table=$('#bootstrap-data-table-export').DataTable();
                        table.columns('.select-filter').every(function(){
                            var that = this;
                            var pos=$('#status');
                            // Create the select list and search operation
                            var select = $('<select class="form-control" />').appendTo(pos).on('change',function(){
                                            that.search("^" + $(this).val() + "$", true, false, true).draw();
                                        });
                                select.append('<option value=".+">All</option>');
                            // Get the search data for the first column and add to the select list
                            this.cache( 'search' ).sort().unique().each(function(d){
                                    select.append($('<option value="'+d+'">'+d+'</option>') );
                            });
                        });
                        $('#member_id').on('keyup',function(){
                            table.columns(1).search( this.value ).draw();
                        });
                    }
                    
                    function validate(){
                    }
                </script>
