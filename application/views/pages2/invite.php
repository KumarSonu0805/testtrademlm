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
?>
                    <div class="row">
                        <div class="col-12">
                            <!-- value card -->
                            <div class="value-card-section">
                                <div class="row justify-content-center">
                                    <div class="col-sm-6 col-md-4 mb-4">
                                        <div class="value-card">
                                            <h5>No of Levels</h5>
                                            <p><?= $this->amount->toDecimal(getlevels(),false); ?></p>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-4 mb-4">
                                        <div class="value-card">
                                            <h5>Stake Amount</h5>
                                            <p><?= $this->amount->toDecimal(getdeposits()); ?></p>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-4 mb-4">
                                        <div class="value-card">
                                            <h5>Level Amount</h5>
                                            <p><?= $this->amount->toDecimal($incomes['level']); ?></p>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-4 mb-4">
                                        <div class="value-card">
                                            <h5>Direct Members</h5>
                                            <p><?= $this->amount->toDecimal(countdirect(),false); ?></p>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-4 mb-4">
                                        <div class="value-card">
                                            <h5>Team Stake</h5>
                                            <p><?= $this->amount->toDecimal(teamstake()); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- value card -->
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">Invitation List</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-condensed" id="table">
                                            <thead>
                                                <tr>
                                                    <th>Sl. No.</th>
                                                    <th>Member ID</th>
                                                    <th>Member Name</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    if(!empty($members)){ $i=0;
                                                        foreach($members as $single){
                                                            $status='<small class="btn btn-block btn-sm btn-success">Active</small>';
                                                            if($single['status']==0){
                                                                $status='<small class="btn btn-block btn-sm btn-danger">In-Active</small>';
                                                            }
                                                ?>
                                                <tr>
                                                    <td><?= ++$i; ?></td>
                                                    <td><?= $single['username']; ?></td>
                                                    <td><?= $single['name']; ?></td>
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
                            