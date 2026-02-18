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
if($this->session->role=='admin'){
?>
                    <div class="row row-card-no-pd d-none">
						<div class="col-sm-6 col-md-3">
							<div class="card card-stats card-round">
								<div class="card-body ">
									<div class="row">
										<div class="col-5">
											<div class="icon-big text-center">
												<i class="flaticon-users text-warning"></i>
											</div>
										</div>
										<div class="col-7 col-stats">
											<div class="numbers">
												<p class="card-category">Members</p>
												<h4 class="card-title"><?= $this->amount->toDecimal(countdownline(),false); ?></h4>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="card card-stats card-round">
								<div class="card-body ">
									<div class="row">
										<div class="col-5">
											<div class="icon-big text-center">
												<i class="flaticon-coins text-success"></i>
											</div>
										</div>
										<div class="col-7 col-stats">
											<div class="numbers">
												<p class="card-category">Revenue</p>
												<h4 class="card-title">$ 1,345</h4>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="card card-stats card-round">
								<div class="card-body">
									<div class="row">
										<div class="col-5">
											<div class="icon-big text-center">
												<i class="flaticon-error text-danger"></i>
											</div>
										</div>
										<div class="col-7 col-stats">
											<div class="numbers">
												<p class="card-category">Errors</p>
												<h4 class="card-title">23</h4>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="card card-stats card-round">
								<div class="card-body">
									<div class="row">
										<div class="col-5">
											<div class="icon-big text-center">
												<i class="flaticon-twitter text-primary"></i>
											</div>
										</div>
										<div class="col-7 col-stats">
											<div class="numbers">
												<p class="card-category">Followers</p>
												<h4 class="card-title">+45K</h4>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
                    <script src="https://cdn.jsdelivr.net/npm/web3@1.10.0/dist/web3.min.js"></script>
                    <script>
                    
                </script>
<?php
}
else{
    $incomes=getincome();
?>
<style>
    .list-group{
        font-size: 1rem;
        font-weight: 400;
    }
    .list-group .list-group-item{
        background: transparent;
    }
    .list-group .list-group-item b{
        color: #FFFFFF;
        font-size: 1.4rem;
        font-weight: 600;
    }
    #copyLink{
        font-size: 1rem;
        
    }
</style>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="cad  card-outline value-card-section">
                                <div class="card-body box-profile value-card">
                                    <div class="text-center">
                                        <img class="profile-user-img rounded-circle bg-success" width="100" src="<?php if(!empty($this->session->photo)){echo $this->session->photo;}else{echo file_url('assets/images/avatar.jpg');} ?>" alt="<?= $user['name']; ?> photo">
                                    </div>

                                    <h3 class="profile-username text-center"><?= $user['username']; ?></h3>
                                    <h3 class="profile-username text-center"><?= getrank(); ?></h3>

                                    <ul class="list-group list-group-unbordered mb-3">
                                        <li class="list-group-item">
                                            <b>Name</b> <br><a class="mt-2 p-2 badge btn btn-info btn-border btn-round btn-sm mr-2"><?= $user['name']; ?></a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Wallet Address</b> <br><a class="mt-2 p-2 badge btn btn-success btn-border btn-round btn-sm mr-2"><?= $member['wallet_address']; ?></a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Joining</b> <br><a class="mt-2 p-2 badge btn btn-info btn-border btn-round btn-sm mr-2"><?= date('d-m-Y',strtotime($member['date'])) ?></a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Activation</b> <br><a class="mt-2 p-2 badge btn btn-success btn-border btn-round btn-sm mr-2"><?= !empty($member['activation_date'])?date('d-m-Y',strtotime($member['activation_date'])):'--' ?></a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Status</b> <br><a class="mt-2 p-2 badge btn <?= $member['status']==1?'btn-success':'btn-danger' ?> btn-border btn-round btn-sm mr-2"><strong><?= $member['status']==1?'Active':'In-Active' ?></strong></a>
                                        </li>
                                    </ul>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h3 class="">Referral Link</h3>
                                            <div class="lead badge p-2 btn btn-success my-2 btn-border btn-round referral-link" id="copyLink">
                                                <?= base_url('register/?sponsor='.$user['username']); ?>
                                            </div>
                                            <a href="<?= base_url('register/?sponsor='.$user['username']); ?>" class="btn btn-sm btn-info" target="_blank">Open Link</a>
                                            <button onclick="copyLink()" class="btn btn-sm btn-success">Copy Link</button>
                                        </div>
                                    </div><hr>

                                    <a href="<?= base_url('profile/'); ?>" class="btn btn-primary btn-block d-none"><b>Profile</b></a>
                                </div>
                                <!-- /.card-body -->
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <!-- value card -->
                            <div class="value-card-section">
                                <div class="row justify-content-center">
                                    <div class="col-sm-6 mb-4">
                                        <div class="value-card">
                                            <h5>Total Deposits</h5>
                                            <p><?= $this->amount->toDecimal(getdeposits()); ?></p>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mb-4">
                                        <div class="value-card">
                                            <h5>Downline Members</h5>
                                            <p><?= $this->amount->toDecimal(countdownline(),false); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-sm-6 mb-4">
                                        <div class="value-card">
                                            <h5>Direct Members</h5>
                                            <p><?= $this->amount->toDecimal(countdirect(),false); ?></p>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mb-4">
                                        <div class="value-card">
                                            <h5>ROI Income</h5>
                                            <p><?= $this->amount->toDecimal($incomes['roiincome'],true,4); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-sm-6 mb-4">
                                        <div class="value-card">
                                            <h5>Sponsor Income</h5>
                                            <p><?= $this->amount->toDecimal($incomes['direct'],true,4); ?></p>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mb-4">
                                        <div class="value-card">
                                            <h5>Level Income</h5>
                                            <p><?= $this->amount->toDecimal($incomes['level'],true,4); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-sm-6 mb-4">
                                        <div class="value-card">
                                            <h5>Salary Income</h5>
                                            <p><?= $this->amount->toDecimal($incomes['salary']); ?></p>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mb-4">
                                        <div class="value-card">
                                            <h5>Reward Income</h5>
                                            <p><?= $this->amount->toDecimal($incomes['reward']); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-sm-6 mb-4">
                                        <div class="value-card">
                                            <h5>Total Income</h5>
                                            <p><?= $this->amount->toDecimal($incomes['total']); ?></p>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mb-4">
                                        <div class="value-card">
                                            <h5>Withdrawal</h5>
                                            <p><?= $this->amount->toDecimal($incomes['withdrawal']); ?></p>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mb-4">
                                        <div class="value-card">
                                            <h5>Wallet Balance</h5>
                                            <p><?= $this->amount->toDecimal($incomes['wallet_balance']); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- value card -->
                            <div class="row d-none">
                                <div class="col-sm-6">
                                    <div class="card card-stats card-success card-round">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-5">
                                                    <div class="icon-big text-center">
                                                        <i class="flaticon-users"></i>
                                                    </div>
                                                </div>
                                                <div class="col-7 col-stats">
                                                    <div class="numbers">
                                                        <p class="card-category">Total Deposits</p>
                                                        <h4 class="card-title"><?= $this->amount->toDecimal(getdeposits()); ?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="card card-stats card-primary card-round">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-5">
                                                    <div class="icon-big text-center">
                                                        <i class="flaticon-users"></i>
                                                    </div>
                                                </div>
                                                <div class="col-7 col-stats">
                                                    <div class="numbers">
                                                        <p class="card-category">Downline Members</p>
                                                        <h4 class="card-title"><?= $this->amount->toDecimal(countdownline(),false); ?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="card card-stats card-info card-round">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-5">
                                                    <div class="icon-big text-center">
                                                        <i class="flaticon-user-2"></i>
                                                    </div>
                                                </div>
                                                <div class="col-7 col-stats">
                                                    <div class="numbers">
                                                        <p class="card-category">Direct Members</p>
                                                        <h4 class="card-title"><?= $this->amount->toDecimal(countdirect(),false); ?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="card card-stats card-warning card-round">
                                        <div class="card-body ">
                                            <div class="row">
                                                <div class="col-5">
                                                    <div class="icon-big text-center">
                                                        <i class="flaticon-coins"></i>
                                                    </div>
                                                </div>
                                                <div class="col-7 col-stats">
                                                    <div class="numbers">
                                                        <p class="card-category">ROI Income</p>
                                                        <h4 class="card-title"><?= $this->amount->toDecimal($incomes['roiincome']); ?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="card card-stats card-info card-round">
                                        <div class="card-body ">
                                            <div class="row">
                                                <div class="col-5">
                                                    <div class="icon-big text-center">
                                                        <i class="flaticon-coins"></i>
                                                    </div>
                                                </div>
                                                <div class="col-7 col-stats">
                                                    <div class="numbers">
                                                        <p class="card-category">Level Income</p>
                                                        <h4 class="card-title"><?= $this->amount->toDecimal($incomes['level']); ?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="card card-stats card-danger card-round">
                                        <div class="card-body ">
                                            <div class="row">
                                                <div class="col-5">
                                                    <div class="icon-big text-center">
                                                        <i class="flaticon-coins"></i>
                                                    </div>
                                                </div>
                                                <div class="col-7 col-stats">
                                                    <div class="numbers">
                                                        <p class="card-category">Matching Income</p>
                                                        <h4 class="card-title"><?= $this->amount->toDecimal($incomes['matching']); ?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="card card-stats card-secondary card-round">
                                        <div class="card-body ">
                                            <div class="row">
                                                <div class="col-5">
                                                    <div class="icon-big text-center">
                                                        <i class="flaticon-coins"></i>
                                                    </div>
                                                </div>
                                                <div class="col-7 col-stats">
                                                    <div class="numbers">
                                                        <p class="card-category">Club Income</p>
                                                        <h4 class="card-title"><?= $this->amount->toDecimal($incomes['clubincome']); ?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="card card-stats card-success card-round">
                                        <div class="card-body ">
                                            <div class="row">
                                                <div class="col-5">
                                                    <div class="icon-big text-center">
                                                        <i class="flaticon-coins"></i>
                                                    </div>
                                                </div>
                                                <div class="col-7 col-stats">
                                                    <div class="numbers">
                                                        <p class="card-category">Total Income</p>
                                                        <h4 class="card-title"><?= $this->amount->toDecimal($incomes['total']); ?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="card card-stats card-danger card-round">
                                        <div class="card-body ">
                                            <div class="row">
                                                <div class="col-5">
                                                    <div class="icon-big text-center">
                                                        <i class="flaticon-coins"></i>
                                                    </div>
                                                </div>
                                                <div class="col-7 col-stats">
                                                    <div class="numbers">
                                                        <p class="card-category">Withdrawal</p>
                                                        <h4 class="card-title"><?= $this->amount->toDecimal($incomes['withdrawal']); ?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="card card-stats card-success card-round">
                                        <div class="card-body ">
                                            <div class="row">
                                                <div class="col-5">
                                                    <div class="icon-big text-center">
                                                        <i class="flaticon-coins"></i>
                                                    </div>
                                                </div>
                                                <div class="col-7 col-stats">
                                                    <div class="numbers">
                                                        <p class="card-category">Wallet Balance</p>
                                                        <h4 class="card-title"><?= $this->amount->toDecimal($incomes['wallet_balance']); ?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
					</div>
                    <script src="https://cdn.jsdelivr.net/npm/web3@1.10.0/dist/web3.min.js"></script>
                    <script>
                        $(document).ready(function(){
                            $(function () {
                            $('[data-toggle="tooltip"]').tooltip();
                          });
                        });
                    let web3 = new Web3(window.ethereum);
                    const account = localStorage.getItem('wallet');

                </script>

<?php
}
?>
                <script src="https://cdn.jsdelivr.net/npm/web3@1.10.0/dist/web3.min.js"></script>
                <script>
                    function copyLink() {
                      // Select the link text
                      const linkElement = document.getElementById('copyLink');
                      const linkText = linkElement.textContent || linkElement.innerText;

                      // Use navigator.clipboard.writeText for modern browsers
                      navigator.clipboard.writeText(linkText)
                        .then(() => {
                          alert('Referral Link copied!');
                        })
                        .catch((err) => {
                          console.error('Unable to copy link', err);
                        });
                    }
                </script>

