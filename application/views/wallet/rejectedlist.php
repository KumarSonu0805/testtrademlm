
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
                                                            <th>A/C Details</th>
                                                            <th>UPI</th>
                                                            <th>Amount</th>
                                                            <th>TDS</th>
                                                            <th>Admin Charge</th>
                                                            <th>Payable Amount</th>
                                                            <th>Reject Date</th>
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
                                                                    }elseif($member['status']==2 && $member['approve_date']===NULL){
                                                                        $status="<span class='text-danger'>Request Cancelled</span>";
                                                                    }elseif($member['status']==2){
                                                                        $status="<span class='text-danger'>Request Rejected By Admin</span>";
                                                                    }
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $i; ?></td>
                                                            <td><?php echo $member['username']; ?></td>
                                                            <td><?php echo $member['name']; ?></td>
                                                            <td><?php echo 'A/C Name: '.$member['account_name'].',<br>A/C No.: '.$member['account_no'].',<br>IFSC: '.$member['ifsc']; ?></td>
                                                            <td><?php echo $member['upi']; ?></td>
                                                            <td><?php echo $this->amount->toDecimal($member['amount']); ?>
                                                            <td><?php echo $this->amount->toDecimal($member['tds']); ?></td>
                                                            <td><?php echo $this->amount->toDecimal($member['admin_charge']); ?></td>
                                                            <td><?php echo $this->amount->toDecimal($member['payable']); ?></td>
                                                            <td>
                                                            <?php echo date('d-m-Y',strtotime($member['approve_date'])); ?>
                                                            </td>
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
