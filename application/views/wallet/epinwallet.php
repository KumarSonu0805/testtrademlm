
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
                                                <a href="<?= base_url('wallet/addtoepinwallet/'); ?>" class="btn btn-sm btn-success">Add to E-Pin Wallet</a>
                                            </div>
                                        </div><br>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive" id="result">
                                                    <table class="table data-table" id="bootstrap-data-table-export">
                                                        <thead>
                                                            <tr>
                                                                <th>Sl No.</th>
                                                                <th>Date</th>
                                                                <th>Member ID</th>
                                                                <th>Member Name</th>
                                                                <th>Amount</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                $wallet=$wallet;
                                                                if(is_array($wallet)){$i=0;
                                                                    foreach($wallet as $single){
                                                                        $i++;
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $i; ?></td>
                                                                <td><?php echo date('d-m-Y',strtotime($single['date'])); ?></td>
                                                                <td><?php echo $single['username']; ?></td>
                                                                <td><?php echo $single['name']; ?></td>
                                                                <td><?php echo $this->amount->toDecimal($single['amount']); ?></td>
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
                        table=$('#bootstrap-data-table-export').DataTable();
                        /*table=$('#bootstrap-data-table-export').DataTable({
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
