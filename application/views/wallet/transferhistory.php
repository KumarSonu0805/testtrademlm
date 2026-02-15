
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
                                                                <th>Date Time</th>
                                                                <?php if($this->session->role=='admin'){ ?>
                                                                <th>Receiver's Id</th>
                                                                <th>Receiver's Name</th>
                                                                <?php } ?>
                                                                <th>Sender's Id</th>
                                                                <th>Sender's Name</th>
                                                                <th>Amount</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                $transfers=$transfers;
                                                                if(is_array($transfers)){$i=0;
                                                                    foreach($transfers as $transfer){
                                                                        $i++;
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $i; ?></td>
                                                                <td><?php echo date('d-m-Y H:i A',strtotime($transfer['added_on'])); ?></td>
                                                                <?php if($this->session->role=='admin'){ ?>
                                                                <td><?php echo $transfer['to_id']; ?></td>
                                                                <td><?php echo $transfer['to_name']; ?></td>
                                                                <?php } ?>
                                                                <td><?php echo $transfer['from_id']; ?></td>
                                                                <td><?php echo $transfer['from_name']; ?></td>
                                                                <td><?php echo $this->amount->toDecimal($transfer['amount']); ?></td>
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
                    function copyAddress() {
                      // Select the link text
                      const linkElement = document.getElementById('copyAddress');
                      const linkText = linkElement.textContent || linkElement.innerText;

                      // Use navigator.clipboard.writeText for modern browsers
                      navigator.clipboard.writeText(linkText)
                        .then(() => {
                          alert('Address copied to clipboard!');
                        })
                        .catch((err) => {
                          console.error('Unable to copy link', err);
                        });
                    }
                </script>
