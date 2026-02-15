
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
                                            <div class="col-md-12">             
                                                <div class="table-responsive" id="result">
                                                    <table class="table data-table" id="bootstrap-data-table-export">
                                                        <thead>
                                                            <tr>
                                                                <th>Sl No.</th>
                                                                <th>Member ID</th>
                                                                <th>Member Name</th>
                                                                <th class="">Status</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                $members=$members;
                                                                if(is_array($members)){$i=0;
                                                                    foreach($members as $member){
                                                                        $i++;
                                                                        if($member['status']==0){ 
                                                                            $status="<span class='text-danger'>Renewal Pending</span>"; 
                                                                            $pan=$cheque='';
                                                                        }
                                                                        else{ 
                                                                            $status="<span class='text-success'>Renewal Approved</span>"; 
                                                                        }
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $i; ?></td>
                                                                <td><?php echo $member['username']; ?></td>
                                                                <td><?php echo $member['name']; ?></td>
                                                                <td><?php echo $status; ?></td>
                                                                <td>
                                                                    <?php
                                                                        if($member['status']==0){
                                                                    ?>
                                                                    <form action="<?php echo base_url('members/approverenewal/'); ?>" method="post" onSubmit='return validate();'>
                                                                        <input type="hidden" name="regid" value="<?php echo $member['regid']; ?>">
                                                                        <button type="submit" value="1" name="renewal" class="btn btn-sm btn-success"><i class="fa fa-check"></i> Approve Renewal</button>
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
                    </div>
                </section>
                <script>

                    var table;
                    $(document).ready(function(e) {
                        createDatatable();
                    });

                    function createDatatable(){
                        $('#status').html('');
                        table=$('#bootstrap-data-table-export').DataTable({
                                dom: 'Bflrtip',
                                buttons: [
                                    {
                                        extend: 'excel',
                                        className: 'btn btn-info'
                                    },
                                    {
                                        extend: 'pdf',
                                        className: 'btn btn-info'
                                    }
                                ]
                            });
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
                        if(!confirm("Approve Renewal of this Member?")){
                            return false;
                        }
                    }
                    function validateDelete(){
                        if(!confirm("Confirm Delete this Member?")){
                            return false;
                        }
                    }
                </script>
    
