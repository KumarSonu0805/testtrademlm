
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
                                            <div class="col-md-4">
                                                <div id="status"></div>
                                            </div>
                                        </div><br>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive" id="result">
                                                    <table class="table data-table" id="bootstrap-data-table-export">
                                                        <thead>
                                                            <tr>
                                                                <th>Sl No.</th>
                                                                <?php
                                                                    if($this->session->role=='admin'){
                                                                ?>
                                                                <th>Member ID</th>
                                                                <th>Member Name</th>
                                                                <th>A/C Details</th>
                                                                <th>UPI</th>
                                                                <?php } ?>
                                                                <th>Request Date</th>
                                                                <th>Amount</th>
                                                                <?php
                                                                    if($this->session->role=='admin'){
                                                                        if(!isset($register)){
                                                                ?>
                                                                <th class="">Action</th>
                                                                <?php 
                                                                        } 
                                                                        else{
                                                                ?>
                                                                <th>Approve Date</th>
                                                                <?php 
                                                                        }
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
                                                                        }
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $i; ?></td>
                                                                <?php
                                                                    if($this->session->role=='admin'){
                                                                ?>
                                                                <td><?php echo $member['username']; ?></td>
                                                                <td><?php echo $member['name']; ?></td>
                                                                <td><?php echo 'A/C Name: '.$member['account_name'].',<br>A/C No.: '.$member['account_no'].',<br>IFSC: '.$member['ifsc']; ?></td>
                                                                <td><?php echo $member['upi']; ?></td>
                                                                <?php } ?>
                                                                <td><?php echo date('d-m-Y',strtotime($member['date'])); ?></td>
                                                                <td><?php echo $this->amount->toDecimal($member['payable']); ?></td>
                                                                <?php
                                                                    if($this->session->role=='admin'){
                                                                        if(!isset($register)){
                                                                ?>
                                                                <td class="">
                                                                    <form action="<?php echo base_url('wallet/approvepayout'); ?>" method="post" onSubmit="return validate('accept');" class="float-left" style="margin-right:5px;">
                                                                        <button type="submit" value="<?php echo $member['id'] ?>" name="request_id" class="btn btn-sm btn-success">Approve</button>
                                                                    </form>
                                                                    <form action="<?php echo base_url('wallet/rejectpayout'); ?>" method="post" onSubmit="return validate('reject');" class="float-left">
                                                                        <textarea name="reason" class="form-control d-none" required></textarea>
                                                                        <button type="button" value="<?php echo $member['id'] ?>" name="request_id" class="btn btn-sm btn-danger" onClick="$(this).closest('form').prev().addClass('d-none');$(this).prev().removeClass('d-none');$(this).next().removeClass('d-none'); $(this).attr('type','submit')">Reject</button>
                                                                        <button type="button" class="btn btn-sm btn-danger d-none" onClick="$(this).closest('form').prev().removeClass('d-none');$(this).prev().prev().addClass('d-none');$(this).addClass('d-none'); $(this).prev().attr('type','button');">Cancel</button>
                                                                    </form>
                                                                </td>
                                                                <?php 
                                                                        } 
                                                                        else{
                                                                ?>
                                                                <td><?php if($member['approve_date']!='')echo date('d-m-Y',strtotime($member['approve_date'])); ?></td>
                                                                <?php 
                                                                        }
                                                                    }else{ 
                                                                ?>
                                                                <td><?php if($member['approve_date']!='')echo date('d-m-Y',strtotime($member['approve_date'])); ?></td>
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
                    </div>
                </section>
                <script>
                    var table;
                    $(document).ready(function(e) {
                        createDatatable();
                        $('#position').change(function(){
                            var position=$(this).val();
                            $('#member_id').val('');
                            $.ajax({
                                type:"POST",
                                url:"<?php echo base_url('members/getmembertable'); ?>",
                                data:{position:position},
                                success: function(data){
                                    $('#result').html(data);
                                    createDatatable();
                                }
                            });
                        });
                        $('body').on('click','.approve-all',function(){
                            $.ajax({
                                type:"POST",
                                url:"<?php echo base_url("wallet/approveallpayout"); ?>",
                                beforeSend: function(data){
                                    $('#details,#result').hide();
                                    $('#loader').removeClass('hidden');
                                },
                                success: function(data){
                                    $('#loader').addClass('hidden');
                                    $('#success').removeClass('hidden');
                                    window.location="<?php echo base_url('wallet/exportpaymentreport/?from='); ?>"+data+"&to="+data+"&type=pay";
                                }
                            });
                        });
                    });

                    function createDatatable(){
                        $('#status').html('');
                        table=$('#bootstrap-data-table-export').DataTable({
                                dom: 'Bflrtip',
                                buttons: [
                                    {
                                        extend: 'excel',
                                        className: 'btn btn-info',
                                          exportOptions: {
                                            columns: ':not(:last-child)',
                                          }
                                    },
                                    {
                                        extend: 'pdf',
                                        className: 'btn btn-info',
                                          exportOptions: {
                                            columns: ':not(:last-child)',
                                          }
                                    }
                                ]
                            });
                        /*table=$('#bootstrap-data-table-export').DataTable({
                                    dom: 'Bflrtip',
                                    buttons: [
                                        {
                                            extend: 'excel',
                                            className: 'btn btn-info',
                                            exportOptions: {
                                                columns: [ 0, 1, 2, 3,4,5,6,7,8 ]
                                            }
                                        },
                                        {
                                            extend: 'pdf',
                                            className: 'btn btn-info',
                                            exportOptions: {
                                                columns: [ 0, 1, 2, 3,4,5,6,7,8 ]
                                            }
                                        }
                                    ]
                                });*/
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
                        $('#result').find('table').parent().addClass('table-responsive');
                    }
                    function validate(type){
                        if(type=='accept'){
                            msg="Confirm Payout of this Member?";
                        }
                        else{
                            msg="Confirm Reject Payment of this Member?";
                        }
                        if(!confirm(msg)){
                            return false;
                        }
                    }
                </script>
