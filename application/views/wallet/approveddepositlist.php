
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
                                                            <th>Member ID</th>
                                                            <th>Member Name</th>
                                                            <th>Request Date</th>
                                                            <th>Transaction Type</th>
                                                            <th>Amount</th>
                                                            <th>Transaction Details</th>
                                                            <th>Approve Date</th>
                                                            <th>Status</th>
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
                                                            <td><?php echo $member['username']; ?></td>
                                                            <td><?php echo $member['name']; ?></td>
                                                            <td><?= date('d-m-Y',strtotime($member['date'])); ?></td>
                                                            <td><?php echo $member['trans_type']; ?></td>
                                                            <td><?= $this->amount->toDecimal($member['amount']); ?></td>
                                                            <td><?= $member['details']."<br>".$image; ?></td>
                                                            <td><?php if($member['approved_on']!='' && $member['status']==1)echo date('d-m-Y',strtotime($member['approved_on'])); ?></td>
                                                            <td><?php echo $status; ?></td>
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
