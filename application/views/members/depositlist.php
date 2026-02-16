
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header"><?= $title; ?></div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div id="status"></div>
                                                    </div>
                                                </div><br>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="table-responsive">
                                                            <table class="table table-condensed" id="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Sl. No.</th>
                                                                        <th>Date</th>
                                                                        <th>Amount</th>
                                                                        <th>Status</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                        if(!empty($deposits)){ $i=0;
                                                                            foreach($deposits as $single){
                                                                                $status='<small class="btn btn-block btn-sm btn-success">Active</small>';
                                                                                if($single['status']==2){
                                                                                    $status='<small class="btn btn-block btn-sm btn-primary">Complete</small>';
                                                                                }
                                                                    ?>
                                                                    <tr>
                                                                        <td><?= ++$i; ?></td>
                                                                        <td><?= date('d-m-Y',strtotime($single['date'])); ?></td>
                                                                        <td><?= $single['amount']; ?></td>
                                                                        <td><?= $status; ?></td>
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
                                        $(document).ready(function(){
                                            //$('#table').dataTable();
                                            createDatatable();
                                        });
                                        function createDatatable(){
                                            $('#status').html('');
                                            table=$('#table').DataTable();
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
                                        }
                                    </script>