
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
                                        <div class="row" id="status">
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive" id="result">
                                                    <table class="table data-table" id="bootstrap-data-table-export">
                                                        <thead>
                                                            <tr>
                                                                <th>Sl No.</th>
                                                                <th>Member ID</th>
                                                                <th>Member Name</th>
                                                                <th>Sponsor ID</th>
                                                                <th class="select-filter">Rank</th>
                                                                <th class="select-filter">Level</th>
                                                                <th>Club Upgrade Date</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                $members=$members;
                                                                if(is_array($members)){$i=0;
                                                                    foreach($members as $member){
                                                                        $i++;
                                                                        $status="<span class='text-danger'>Not activated</span>";
                                                            ?>
                                                            <tr>
                                                                <td><?= $i; ?></td>
                                                                <td><?= $member['username']; ?></td>
                                                                <td><?= $member['name']; ?></td>
                                                                <td><?= $member['sponsor']; ?></td>
                                                                <td><?= $member['club']; ?></td>
                                                                <td><?= (($member['level']!='Owner')?'Level ':'').$member['level']; ?></td>
                                                                <td><?= date('d-m-Y',strtotime($member['added_on'])); ?></td>
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
                            var div=$('<div class="col-md-4 my-1">').appendTo(pos)
                            // Create the select list and search operation
                            var select = $('<select class="form-control" />').appendTo(div).on('change',function(){
                                            that.search("^" + $(this).val() + "$", true, false, true).draw();
                                        });
                                select.append('<option value=".+">All</option>');
                            // Get the search data for the first column and add to the select list
                            var options=[];
                            this.cache( 'search' ).sort().unique().each(function(d){
                                select.append($('<option value="'+d+'">'+d+'</option>') );
                            });
                            if(options.length>0){
                                options.sort((a, b) => a - b);
                                for(var i in options){
                                    var opt=options[i]!='Owner'?'Level '+options[i]:'Owner';
                                    select.append($('<option value="'+opt+'">'+opt+'</option>') );
                                }
                            }
                        });
                        $('#member_id').on('keyup',function(){
                            table.columns(1).search( this.value ).draw();
                        });
                    }
                    function validate(text){
                        if(!confirm("Confirm "+text+" this Member?")){
                            return false;
                        }
                    }
                    function validateDelete(){
                        if(!confirm("Confirm Delete this Member?")){
                            return false;
                        }
                    }
                </script>
    
