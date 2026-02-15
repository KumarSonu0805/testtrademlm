
                <div class="panel">
                    <div class="panel-body">
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
                                                <th>PAN Name</th>
                                                <th>PAN No</th>
                                                <th>TDS Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $report=$report;
                                                if(is_array($report)){$i=0;
                                                    foreach($report as $member){
                                                        $i++;
                                            ?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td><?php echo $member['name']; ?></td>
                                                <td><?php echo $member['pan']; ?></td>
                                                <td><?php echo $this->amount->toDecimal($member['tds_amount']); ?></td>
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
                                                columns: [ 0, 1, 2, 3 ]
                                            }
                                        },
                                        {
                                            extend: 'pdf',
                                            className: 'btn btn-info',
                                            exportOptions: {
                                                columns: [ 0, 1, 2, 3 ]
                                            }
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
