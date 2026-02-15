
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
                                            <div class="col-12">
                                                <!--latest product info start-->
                                                <section class="panel">
                                                    <h3>
                                                        Current E-Wallet Balance : <span class="text-success">₹ <?php echo $this->amount->toDecimal($wallet['actualwallet']); ?></span>
                                                    </h3>
                                                </section>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div id="status"></div>
                                            </div>
                                        </div><br>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive" id="result">
                                                    <table class="table table-striped data-table" id="bootstrap-data-table-export">
                                                        <thead>
                                                            <tr>
                                                                <th>Sl No</th>
                                                                <th>Date</th>
                                                                <th>Member Id</th>
                                                                <th>Level</th>
                                                                <th>Income</th>
                                                                <th class="select-filter">Remarks</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                $incomes=$incomes;
                                                                $clubs=array();//$this->common->getclubs();
                                                                if(is_array($incomes)){$i=0;
                                                                    $club_ids=array();//array_column($clubs,'id');
                                                                    foreach($incomes as $income){
                                                                        $i++;
                                                                        $remarks=$income['remarks'];
                                                                        if($income['remarks']=='Reward Income'){
                                                                            $index=array_search($income['club_id'],$club_ids);
                                                                            $remarks=$clubs[$index]['name'].' '.$remarks;
                                                                        }
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $i; ?></td>
                                                                <td><?php if($income['date']!=''){echo date('d-m-Y',strtotime($income['date']));} ?></td>
                                                                <td><?php echo $income['member_id']; ?></td>
                                                                <td><?php echo $income['level_id']; ?></td>
                                                                <td><?php echo $this->amount->toDecimal($income['amount']); ?></td>
                                                                <td><?php echo $remarks; ?></td>
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
                </script>
