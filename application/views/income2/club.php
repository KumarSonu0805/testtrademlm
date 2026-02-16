

<?php
$incomes=getincome();
$leg1=!empty($legs[0]['business'])?$legs[0]['business']:0;
$leg2=!empty($legs[1]['business'])?$legs[1]['business']:0;
?>
                    <div class="card">
                        <div class="card-header">Clubs</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-condensed" id="table">
                                            <thead>
                                                <tr>
                                                    <th>Sl. No.</th>
                                                    <th>Club</th>
                                                    <th>Required</th>
                                                    <th>Weaker</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    if(!empty($ranks)){ $i=0;
                                                        foreach($ranks as $single){
                                                            $status='<small class="btn btn-block btn-sm btn-success">Achieved</small>';
                                                            //if($single['status']==0){
                                                                $status='<small class="btn btn-block btn-sm btn-danger">Not Achieved</small>';
                                                            //}
                                                ?>
                                                <tr>
                                                    <td><?= ++$i; ?></td>
                                                    <td><?= $single['club']; ?></td>
                                                    <td>$<?= $single['required'].' Matching/Month'; ?></td>
                                                    <td><?= $single['weaker'].'%'; ?></td>
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
                    <script>
                        $(document).ready(function(){
                            $('#table').dataTable();
                        });
                    </script>
                            