
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card light-bg" style="height:95%;">
                                    <div class="card-header">
                                        <h3 class="card-title"><?= $title ?></h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <?php  
                                            if($this->session->role=='admin'){
                                        ?>
                                        
                                        <div class="row">
                                            <div class="col-lg-3 col-6">
                                                <a href="<?= base_url('members/memberlist/'); ?>">            
                                                    <div class="card bg-info">
                                                        <div class="card-body">
                                                            <div class="inner">
                                                                <h3><?= $total_members; ?></h3>
                                                                <p>Total Members</p>
                                                            </div>
                                                            <div class="icon">
                                                                <i class="fas fa-users"></i>
                                                            </div>
                                                            <!-- <a href="#" class="card-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="col-lg-3 col-6">
                                                <a href="<?= base_url('members/activelist/'); ?>">            
                                                    <div class="card bg-success">
                                                        <div class="card-body">
                                                            <div class="inner">
                                                                <h3><?= $active_members; ?></h3>
                                                                <p>Active Members</p>
                                                            </div>
                                                            <div class="icon">
                                                                <i class="fa fa-users"></i>
                                                            </div>
                                                            <!-- <a href="#" class="card-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="col-lg-3 col-6">
                                                <a href="<?= base_url('members/activelist/'); ?>">            
                                                    <div class="card bg-primary">
                                                        <div class="card-body">
                                                            <div class="inner">
                                                                <h3><?= $inactive_members ?></h3>
                                                                <p>In-Active Members</p>
                                                            </div>
                                                            <div class="icon">
                                                                <i class="fas fa-users"></i>
                                                            </div>
                                                            <!-- <a href="#" class="card-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                        <?php
                                            }
                                            else{
                                                $br="";
                                                if($status==0){
                                                    $message="Please Activate Your Account!";
                                                    $br="<br>";
                                                }
                                                elseif($status==2){
                                                    $message="";
                                                    $br="<br>";
                                                }
                                        ?>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h3 class="text-danger text-center"><?= $message; ?></h3>
                                            </div>
                                        </div><?= $br; ?>
                                        <div class="row profile">
                                            <div class="col-md-6 mb-3">
                                                <div class="card light-bg">
                                                    <div class="card-header">
                                                        <h3 class="card-title"><?= $title ?></h3>
                                                    </div>
                                                    <!-- /.card-header -->
                                                    <div class="card-body">
                                                        <table class="table" id="personal-details">
                                                            <tr>
                                                                <td colspan="2">
                                                                    <img src="<?php if($member['photo']!=''){echo file_url($member['photo']);}else{echo file_url('assets/images/avatar.png');} ?>" 
                                                                            style="height:135px; width:120px;" alt="User Image" id="view_photo">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th>Sponsor ID</th>
                                                                <td><?= $member['susername']; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th>Sponsor Name</th>
                                                                <td><?= $member['sname']; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th>Member ID</th>
                                                                <td><?= $user['username']; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th>Name</th>
                                                                <td><?= $member['name']; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th>Joining Date</th>
                                                                <td><?= date('d-m-Y',strtotime($member['date'])); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th>Activation Date</th>
                                                                <td><?= !empty($member['activation_date'])?date('d-m-Y',strtotime($member['activation_date'])):'--'; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th>Status</th>
                                                                <td>
                                                                    <?php
                                                                        if($member['status']==1){
                                                                            echo '<span class="text-success">Active<span>';
                                                                        }
                                                                        else{
                                                                            echo '<span class="text-danger">In-Active<span><br>';
                                                                    ?>
                                                                    <a href="<?= base_url('activateaccount/'); ?>" class="btn btn-sm btn-success">Activate Account</a>
                                                                    <?php
                                                                        }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            <?php /*?><tr>
                                                                <th>Father's Name</th>
                                                                <td><?= $member['father']; ?></td>
                                                            </tr><?php */?>
                                                        </table><hr>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <h3 class="">Referral Link</h3>
                                                                <div class="lead text-success my-2" id="copyLink">
                                                                    <?= base_url('signup/?sponsor='.$user['username']); ?>
                                                                </div>
                                                                <a href="<?= base_url('signup/?sponsor='.$user['username']); ?>" class="btn btn-sm btn-info" target="_blank">Open Link</a>
                                                                <button onclick="copyLink()" class="btn btn-sm btn-info">Copy Link</button>
                                                            </div>
                                                        </div><hr>
                                                        <div class="row d-none">
                                                            <div class="col-lg-6 col-12">
                                                                <a href="<?= base_url('epins/newpins/'); ?>">            
                                                                    <div class="card bg-info">
                                                                        <div class="card-body text-white">
                                                                            <div class="inner">
                                                                                <h3><?= $unused; ?></h3>
                                                                                <p>Un-Used E-Pins</p>
                                                                            </div>
                                                                            <div class="icon">
                                                                                <i class="fas fa-tasks"></i>
                                                                            </div>
                                                                            <!-- <a href="#" class="card-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
                                                                        </div>
                                                                    </div>
                                                                </a>
                                                            </div>
                                                            <div class="col-lg-6 col-12">
                                                                <a href="<?= base_url('epins/usedepins/'); ?>">            
                                                                    <div class="card bg-purple">
                                                                        <div class="card-body text-white">
                                                                            <div class="inner">
                                                                                <h3><?= $used; ?></h3>
                                                                                <p>Used E-Pins</p>
                                                                            </div>
                                                                            <div class="icon">
                                                                                <i class="fas fa-tasks"></i>
                                                                            </div>
                                                                            <!-- <a href="#" class="card-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
                                                                        </div>
                                                                    </div>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <?php
                                                    $royalty=$recycle=0;
                                                    if(!empty($incomes)){
                                                        $remarks=array_column($incomes,'remarks');
                                                        $index=array_search('Level Income',$remarks);
                                                        if($index!==false){
                                                            //$levelincome=$incomes[$index]['income'];
                                                        }
                                                        $index=array_search('Recycle Income',$remarks);
                                                        if($index!==false){
                                                            $recycle=$incomes[$index]['income'];
                                                        }
                                                        $index=array_search('Royalty Income',$remarks);
                                                        if($index!==false){
                                                            $royalty=$incomes[$index]['income'];
                                                        }
                                                    }
                                                ?>
                                                <div class="row">
                                                    <div class="col-lg-6 col-12">
                                                        <a href="#">            
                                                            <div class="card bg-success">
                                                                <div class="card-body">
                                                                    <div class="inner">
                                                                        <h3><span>₹</span> <?= $this->amount->toDecimal($totaldeposit,true,3); ?></h3>
                                                                        <p>Total Deposit</p>
                                                                    </div>
                                                                    <div class="icon">
                                                                        <i class="fas fa-money-bill"></i>
                                                                    </div>
                                                                    <!-- <a href="#" class="card-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <div class="col-lg-6 col-12">
                                                        <a href="#">            
                                                            <div class="card bg-maroon">
                                                                <div class="card-body">
                                                                    <div class="inner">
                                                                        <h3><span>₹</span> <?= $this->amount->toDecimal($roiincome,true,3); ?></h3>
                                                                        <p>ROI Income</p>
                                                                    </div>
                                                                    <div class="icon">
                                                                        <i class="fas fa-money-bill"></i>
                                                                    </div>
                                                                    <!-- <a href="#" class="card-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <div class="col-lg-6 col-12">
                                                        <a href="#">            
                                                            <div class="card bg-primary">
                                                                <div class="card-body">
                                                                    <div class="inner">
                                                                        <h3><span>₹</span> <?= $this->amount->toDecimal($levelincome,true,3); ?></h3>
                                                                        <p>Level Income</p>
                                                                    </div>
                                                                    <div class="icon">
                                                                        <i class="fas fa-money-bill"></i>
                                                                    </div>
                                                                    <!-- <a href="#" class="card-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <div class="col-lg-6 col-12">
                                                        <a href="#">            
                                                            <div class="card bg-success">
                                                                <div class="card-body">
                                                                    <div class="inner">
                                                                        <h3><span>₹</span> <?= $this->amount->toDecimal($totalincome,true,3); ?></h3>
                                                                        <p>Total Earning</p>
                                                                    </div>
                                                                    <div class="icon">
                                                                        <i class="fas fa-money-bill"></i>
                                                                    </div>
                                                                    <!-- <a href="#" class="card-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <div class="col-lg-6 col-12">
                                                        <a href="#">            
                                                            <div class="card bg-purple">
                                                                <div class="card-body">
                                                                    <div class="inner">
                                                                        <h3><span>₹</span> <?= $this->amount->toDecimal($transferred,true,3); ?></h3>
                                                                        <p>Wallet Transfers</p>
                                                                    </div>
                                                                    <div class="icon">
                                                                        <i class="fas fa-money-bill"></i>
                                                                    </div>
                                                                    <!-- <a href="#" class="card-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <div class="col-lg-6 col-12">
                                                        <a href="#">            
                                                            <div class="card bg-primary">
                                                                <div class="card-body">
                                                                    <div class="inner">
                                                                        <h3><span>₹</span> <?= $this->amount->toDecimal($received,true,3); ?></h3>
                                                                        <p>Wallet Received</p>
                                                                    </div>
                                                                    <div class="icon">
                                                                        <i class="fas fa-money-bill"></i>
                                                                    </div>
                                                                    <!-- <a href="#" class="card-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <div class="col-lg-6 col-12">
                                                        <a href="#">            
                                                            <div class="card bg-danger">
                                                                <div class="card-body">
                                                                    <div class="inner">
                                                                        <h3><span>₹</span> <?= $this->amount->toDecimal($withdrawals,true,3); ?></h3>
                                                                        <p>Withdrawal</p>
                                                                    </div>
                                                                    <div class="icon">
                                                                        <i class="fas fa-money-bill"></i>
                                                                    </div>
                                                                    <!-- <a href="#" class="card-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <?php
                                                        $balance=$totalincome-$withdrawals-$epingeneration+$received-$transferred;
                                                    ?>
                                                    <div class="col-lg-6 col-12">
                                                        <a href="#">            
                                                            <div class="card bg-info">
                                                                <div class="card-body">
                                                                    <div class="inner">
                                                                        <h3><span>₹</span> <?= $this->amount->toDecimal($balance,true,3); ?></h3>
                                                                        <p>Balance</p>
                                                                    </div>
                                                                    <div class="icon">
                                                                        <i class="fas fa-money-bill"></i>
                                                                    </div>
                                                                    <!-- <a href="#" class="card-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <script>
                    function copyLink() {
                      // Select the link text
                      const linkElement = document.getElementById('copyLink');
                      const linkText = linkElement.textContent || linkElement.innerText;

                      // Use navigator.clipboard.writeText for modern browsers
                      navigator.clipboard.writeText(linkText)
                        .then(() => {
                          alert('Referral Link copied to clipboard!');
                        })
                        .catch((err) => {
                          console.error('Unable to copy link', err);
                        });
                    }
                </script>
