<style>
.value-card-section {
   margin-bottom: 80px;
   text-align: center;
}
.value-card-section .value-card {
   background: linear-gradient(135deg, #1b1b1f, #2e2f36);
   padding:20px;
   border-radius: 15px;
   box-shadow: 0 10px 25px rgba(0,0,0,0.4);
   transition: transform 0.3s, box-shadow 0.3s;
   color: #fff;
   font-family: 'Poppins', sans-serif;
}
.value-card-section .value-card h5 {
   font-size: 1.3rem;
   margin-bottom: 15px;
   color: #f8b400;
}
.value-card-section .value-card p {
   font-size: 1.5rem;
   color: #fff;
   margin-bottom:0;
}
.value-card-section .value-card:hover {
   transform: translateY(-10px);
   box-shadow: 0 15px 30px rgba(51, 38, 1, 0.5);
}
@media (max-width: 768px) {
   .value-card-section .value-card {
      padding: 25px 15px;
   }
   .value-card-section .value-card p {
      font-size: 1.2rem;
   }
}
</style>

<?php
$incomes=getincome();
$legs=getlegbusiness();
$leg1=!empty($legs[0]['business'])?$legs[0]['business']:0;
$leg2=!empty($legs[1]['business'])?$legs[1]['business']:0;
?>
                    <div class="row">
                        <div class="col-12">
                            <!-- value card -->
                            <div class="value-card-section">
                                <div class="row justify-content-center">
                                    <div class="col-sm-6 col-md-4 mb-4">
                                        <div class="value-card">
                                            <h5>Leg Business 1</h5>
                                            <p><?= $this->amount->toDecimal($leg1); ?></p>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-4 mb-4">
                                        <div class="value-card">
                                            <h5>Another Leg Business</h5>
                                            <p><?= $this->amount->toDecimal($leg2); ?></p>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-4 mb-4">
                                        <div class="value-card">
                                            <h5>Direct Business Amount</h5>
                                            <p><?= $this->amount->toDecimal(directbusiness()); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- value card -->
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">Reward List</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-condensed" id="table">
                                            <thead>
                                                <tr>
                                                    <th>Sl. No.</th>
                                                    <th>Rank</th>
                                                    <th>Required</th>
                                                    <th>Reward</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $mranks=getranks();
                                                    $rank_ids=!empty($mranks)?array_column($mranks,'rank_id'):array();
                                                    if(!empty($ranks)){ $i=0;
                                                        foreach($ranks as $single){
                                                            $status='<small class="btn btn-block btn-sm btn-danger">Not Achieved</small>';
                                                            
                                                            if(in_array($single['id'],$rank_ids)){
                                                                $status='<small class="btn btn-block btn-sm btn-success">Achieved</small>';
                                                            }
                                                ?>
                                                <tr>
                                                    <td><?= ++$i; ?></td>
                                                    <td><?= $single['rank']; ?></td>
                                                    <td>$<?= $single['leg_1']; ?></td>
                                                    <td>$<?= $single['reward']; ?></td>
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
                            