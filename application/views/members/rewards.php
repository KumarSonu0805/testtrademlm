
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
                                            <?php
                                                $prev_club_id=0;
                                                if(!empty($rewards)){
                                                    foreach($rewards as $key=>$reward){
                                                        if($prev_club_id!=0 && $prev_club_id!=$reward['club_id']){
                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>	
                                            <?php
                                                        }
                                                        if($prev_club_id==0 || $prev_club_id!=$reward['club_id']){
                                            ?>
                                            <div class="col-md-6">             
                                                <h4 class=""><?= $reward['club'] ?></h4>
                                                <div class="table-responsive" id="result">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Level</th>
                                                                <th>Team</th>
                                                                <th>Reward</th>
                                                                <th>Status</th>
                                                                <th>Date</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                            <?php
                                                        }
                                                        $members=$reward['members'];
                                                        if($members!=2){
                                                            $members-=$rewards[$key-1]['members'];
                                                        }
                                                        if($reward['status']=='1'){
                                                            $status='<span class="text-success">Achieved</span>';
                                                            $date=date('d-m-Y',strtotime($reward['date']));
                                                        }
                                                        else{
                                                            $status='<span class="text-danger">Not Achieved</span>';
                                                            $date='--';
                                                        }
                                            ?>
                                                            <tr>
                                                                <td><?= $reward['level_id'] ?></td>
                                                                <td><?= $members; ?></td>
                                                                <td><?= $reward['income'] ?></td>
                                                                <td><?= $status; ?></td>
                                                                <td><?= $date; ?></td>
                                                            </tr>
                                            <?php
                                                        $prev_club_id=$reward['club_id'];
                                                        if(!isset($rewards[$key+1])){
                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>	
                                            <?php
                                                        }
                                                    }
                                                }
                                            ?>

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
    
