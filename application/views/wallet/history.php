
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
                                                            <th>Request Date</th>
                                                            <th>Transaction Type</th>
                                                            <th>Amount</th>
                                                            <th>TDS</th>
                                                            <th>Network Fees</th>
                                                            <th>Payable Amount</th>
                                                            <th>Payable Amount (INR)</th>
                                                            <th>Approve Date</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
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
                                                                    }elseif($member['status']==2 && $member['approve_date']===NULL){
                                                                        $status="<span class='text-danger'>Request Cancelled</span>";
                                                                    }elseif($member['status']==2){
                                                                        $status="<span class='text-danger'>Request Rejected By Admin</span>";
                                                                        if(!empty($member['reason'])){
                                                                            $status.="<p class='text-primary mb-0 pb-0'>".$member['reason']."</p>";
                                                                        }
                                                                    }
                                                        ?>
                                                        <tr>
                                                            <td><?= $i; ?></td>
                                                            <td><?= date('d-m-Y',strtotime($member['date'])); ?></td>
                                                            <td><?= $member['trans_type']; ?></td>
                                                            <td><?= $this->amount->toDecimal($member['amount']); ?></td>
                                                            <td><?= $this->amount->toDecimal($member['tds']); ?></td>
                                                            <td><?= $this->amount->toDecimal($member['admin_charge']); ?></td>
                                                            <td><?= $this->amount->toDecimal($member['payable']); ?></td>
                                                            <td><?= $this->amount->toDecimal($member['payable']*CONV_RATE); ?></td>
                                                            <td>
                                                            <?php 
                                                                if($member['approve_date']!='0000-00-00' && $member['approve_date']!==NULL && $member['status']!=2){
                                                                    echo date('d-m-Y',strtotime($member['approve_date'])); 
                                                                }
                                                            ?>
                                                            </td>
                                                            <td><?= $status; ?></td>
                                                            <td>
                                                                <?php
                                                                    if($member['status']==0){
                                                                ?>
                                                                <form action="<?= base_url('wallet/rejectpayout'); ?>" method="post" onSubmit="return validate('reject');" class="float-left">
                                                                    <button type="submit" value="<?= $member['id'] ?>" name="request_id" class="btn btn-sm btn-danger">Cancel</button>
                                                                </form>
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
                    </div>
                </section>
                <script>
                    $(document).ready(function(e) {
                        createDatatable();
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
