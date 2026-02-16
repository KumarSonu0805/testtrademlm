<!-- Sidebar -->
		<div class="sidebar sidebar-style-2" data-background-color="<?= SIDEBAR_BG ?>">
			
			<div class="sidebar-wrapper scrollbar scrollbar-inner">
				<div class="sidebar-content">
					<div class="user">
						<div class="avatar-sm float-left mr-2">
							<img src="<?= file_url('includes/img/profile.jpg'); ?>" alt="..." class="avatar-img rounded-circle">
						</div>
						<div class="info">
							<a data-toggle="collapse" href="#collapseExample" aria-expanded="true">
								<span>
									<?= $this->session->name; ?>
									<span class="user-level"><?= $this->session->role=='member'?$this->session->username.(getrank()):$this->session->role ?></span>
									<span class="caret"></span>
								</span>
							</a>
							<div class="clearfix"></div>
                            <?php if($this->session->role=='admin'){ ?>
							<div class="collapse in" id="collapseExample">
								<ul class="nav">
									<li>
										<a href="<?= base_url('home/changepassword/'); ?>">
											<span class="link-collapse">Change Password</span>
										</a>
									</li>
								</ul>
							</div>
                            <?php } ?>
						</div>
					</div>
					<ul class="nav nav-primary">
                        <?php if($this->session->sess_type=='admin_access'){ ?>
						<li class="nav-item">
							<a href="<?= base_url('login/backtoadmin'); ?>" >
								<i class="fas fa-arrow-left"></i>
								<p>Back to Admin Panel</p>
							</a>
						</li>
                        <?php } ?>
						<li class="nav-item <?= activate_menu('home'); ?>">
							<a href="<?= base_url('home/'); ?>" >
								<i class="fas fa-home"></i>
								<p>Home</p>
							</a>
						</li>
						<li class="nav-section">
							<span class="sidebar-mini-icon">
								<i class="fa fa-ellipsis-h"></i>
							</span>
							<h4 class="text-section">Management</h4>
						</li>
                        <?php
                            if($this->session->role=='admin'){
                        ?>
                        <?php
                        $not=array();
                        ?>
						<li class="nav-item submenu <?= activate_dropdown('members','li',$not); ?>">
							<a data-toggle="collapse" href="#members">
								<i class="fas fa-users"></i>
								<p>Members</p>
								<span class="caret"></span>
							</a>
							<div class="collapse <?= activate_dropdown('members','div',$not); ?>" id="members">
								<ul class="nav nav-collapse">
									<li class="<?= activate_menu('members/memberlist'); ?>">
										<a href="<?= base_url('members/memberlist/'); ?>">
											<span class="sub-item">Downline Members</span>
										</a>
									</li>
									<li class="<?= activate_menu('members/entertomember'); ?>">
										<a href="<?= base_url('members/entertomember/'); ?>">
											<span class="sub-item">Enter to Member</span>
										</a>
									</li>
									<?php /*?><li class="<?= activate_menu('members/modify'); ?>">
										<a href="<?= base_url('members/modify/'); ?>">
											<span class="sub-item">Member Modify</span>
										</a>
									</li>
									<li class="<?= activate_menu('members/changepassword'); ?>">
										<a href="<?= base_url('members/changepassword/'); ?>">
											<span class="sub-item">Change Password</span>
										</a>
									</li>
									<li class="<?= activate_menu('members/coinrate'); ?>">
										<a href="<?= base_url('members/coinrate/'); ?>">
											<span class="sub-item">Coin Rate</span>
										</a>
									</li><?php */?>
								</ul>
							</div>
						</li>
                        <?php
                        $not=array('index');
                        ?>
						<li class="nav-item submenu <?= activate_dropdown('home','li',$not); ?>">
							<a data-toggle="collapse" href="#settings">
								<i class="fas fa-user-lock"></i>
								<p>Admin</p>
								<span class="caret"></span>
							</a>
							<div class="collapse <?= activate_dropdown('home','div',$not); ?>" id="settings">
								<ul class="nav nav-collapse">
									<?php /*?><li class="">
										<a href="<?= base_url(); ?>">
											<span class="sub-item">Create Ultra Bouns Payout</span>
										</a>
									</li><?php */?>
									<li class="<?= activate_menu('home/changepassword'); ?>">
										<a href="<?= base_url('changepassword/'); ?>">
											<span class="sub-item">Admin Change Password</span>
										</a>
									</li>
									<?php /*?><li>
										<a href="<?= base_url(); ?>">
											<span class="sub-item">Setting</span>
										</a>
									</li><?php */?>
								</ul>
							</div>
						</li>
                        <?php
                        $not=array();
                        ?>
						<li class="nav-item submenu <?= activate_dropdown('reports','li',$not); ?> d-none">
							<a data-toggle="collapse" href="#reports">
								<i class="fas fa-list-alt"></i>
								<p>Report</p>
								<span class="caret"></span>
							</a>
							<div class="collapse <?= activate_dropdown('reports','div',$not); ?>" id="reports">
								<ul class="nav nav-collapse">
									<li class="<?= activate_menu('reports'); ?>">
										<a href="<?= base_url('reports/'); ?>">
											<span class="sub-item">Member Joining Report</span>
										</a>
									</li>
									<li class="<?= activate_menu('reports/memberwallet'); ?>">
										<a href="<?= base_url('reports/memberwallet/'); ?>">
											<span class="sub-item">Member Wallet</span>
										</a>
									</li>
									<?php /*?><li class="<?= activate_menu('reports/topupreport'); ?>">
										<a href="<?= base_url('reports/topupreport/'); ?>">
											<span class="sub-item">Top Up Report</span>
										</a>
									</li>
									<li class="<?= activate_menu('reports/ledger'); ?>">
										<a href="<?= base_url('reports/ledger/'); ?>">
											<span class="sub-item">Ledger Report</span>
										</a>
									</li><?php */?>
								</ul>
							</div>
						</li>
                        <?php
                        $not=array();
                        ?>
						<?php /*?><li class="nav-item submenu <?= activate_dropdown('income','li',$not); ?>">
							<a data-toggle="collapse" href="#income">
								<i class="fas fa-money-bill-alt"></i>
								<p>Payout</p>
								<span class="caret"></span>
							</a>
							<div class="collapse <?= activate_dropdown('income','div',$not); ?>" id="income">
								<ul class="nav nav-collapse">
									<li class="<?= activate_menu('income/roiincome'); ?>">
										<a href="<?= base_url('income/roiincome/'); ?>">
											<span class="sub-item">ROI Income</span>
										</a>
									</li>
									<li class="<?= activate_menu('income/levelincome'); ?>">
										<a href="<?= base_url('income/levelincome/'); ?>">
											<span class="sub-item">Level Income</span>
										</a>
									</li>
									<li class="<?= activate_menu('income/levelincome'); ?>">
										<a href="<?= base_url('income/levelincome/'); ?>">
											<span class="sub-item">Level One Time Income</span>
										</a>
									</li>
									<li class="<?= activate_menu('income/rankincome'); ?>">
										<a href="<?= base_url('income/rankincome/'); ?>">
											<span class="sub-item">Rank Income</span>
										</a>
									</li>
									<li class="<?= activate_menu('income/royaltyincome'); ?>">
										<a href="<?= base_url('income/royaltyincome/'); ?>">
											<span class="sub-item">Royalty Income</span>
										</a>
									</li>
									<li class="<?= activate_menu('income/ultraclubincome'); ?>">
										<a href="<?= base_url('income/ultraclubincome/'); ?>">
											<span class="sub-item">Ultra Club Income</span>
										</a>
									</li>
								</ul>
							</div>
						</li><?php */?>
                        <?php
                        $not=array('transferfund','transferfundhistory','fundrequests','approvedfundrequests');
                        ?>
						<li class="nav-item submenu <?= activate_dropdown('wallet','li',$not); ?> d-none">
							<a data-toggle="collapse" href="#wallet">
								<i class="fas fa-money-check"></i>
								<p>Withdrawal</p>
								<span class="caret"></span>
							</a>
							<div class="collapse <?= activate_dropdown('wallet','div',$not); ?>" id="wallet">
								<ul class="nav nav-collapse">
									<li class="<?= activate_menu('wallet/withdrawalrequests'); ?>">
										<a href="<?= base_url('wallet/withdrawalrequests/'); ?>">
											<span class="sub-item">Withdrawal Requests</span>
										</a>
									</li>
									<li class="<?= activate_menu('wallet/history'); ?>">
										<a href="<?= base_url('wallet/history/'); ?>">
											<span class="sub-item">History</span>
										</a>
									</li>
									<li class="<?= activate_menu('wallet/unstakerequests'); ?>">
										<a href="<?= base_url('wallet/unstakerequests/'); ?>">
											<span class="sub-item">Unstake Requests</span>
										</a>
									</li>
									<li class="<?= activate_menu('wallet/unstakehistory'); ?>">
										<a href="<?= base_url('wallet/unstakehistory/'); ?>">
											<span class="sub-item">Unstake History</span>
										</a>
									</li>
								</ul>
							</div>
						</li>
                        <?php
                        $not=array();
                        ?>
						<?php /*?><li class="nav-item submenu <?= activate_dropdown('support','li',$not); ?>">
							<a data-toggle="collapse" href="#support">
								<i class="fas fa-headset"></i>
								<p>Support</p>
								<span class="caret"></span>
							</a>
							<div class="collapse <?= activate_dropdown('support','div',$not); ?>" id="support">
								<ul class="nav nav-collapse">
									<li>
										<a href="<?= base_url(); ?>">
											<span class="sub-item">History</span>
										</a>
									</li>
								</ul>
							</div>
						</li><?php */?>
                        <?php 
                            }
                            else{
                        ?>
                        <?php
                        $not=array();
                        ?>
						<li class="nav-item submenu <?= activate_dropdown('members','li',$not); ?>">
							<a data-toggle="collapse" href="#members">
								<i class="fas fa-users"></i>
								<p>My Team</p>
								<span class="caret"></span>
							</a>
							<div class="collapse <?= activate_dropdown('members','div',$not); ?>" id="members">
								<ul class="nav nav-collapse">
									<li class="<?= activate_menu('members/directmembers'); ?>">
										<a href="<?= base_url('members/directmembers/'); ?>">
											<span class="sub-item">Direct Members</span>
										</a>
									</li>
									<li class="<?= activate_menu('members/memberlist'); ?>">
										<a href="<?= base_url('members/memberlist/'); ?>">
											<span class="sub-item">Downline Members</span>
										</a>
									</li>
									<li class="<?= activate_menu('members/legbusiness'); ?>">
										<a href="<?= base_url('members/legbusiness/'); ?>">
											<span class="sub-item">Leg Business</span>
										</a>
									</li>
								</ul>
							</div>
						</li>
                        <?php
                        $not=array();
                        ?>
						<li class="nav-item submenu <?= activate_dropdown('deposit','li',$not); ?>">
							<a data-toggle="collapse" href="#deposit">
								<i class="fas fa-money-bill-alt"></i>
								<p>Deposit</p>
								<span class="caret"></span>
							</a>
							<div class="collapse <?= activate_dropdown('deposit','div',$not); ?>" id="deposit">
								<ul class="nav nav-collapse">
									<li class="<?= activate_menu('deposit'); ?>">
										<a href="<?= base_url('deposit/'); ?>">
											<span class="sub-item">Deposit</span>
										</a>
									</li>
									<li class="<?= activate_menu('deposit/depositlist'); ?>">
										<a href="<?= base_url('deposit/depositlist/'); ?>">
											<span class="sub-item">Deposit List</span>
										</a>
									</li>
								</ul>
							</div>
						</li>
                        <?php
                        $not=array();
                        ?>
						<li class="nav-item submenu <?= activate_dropdown('income','li',$not); ?>">
							<a data-toggle="collapse" href="#income">
								<i class="fas fa-money-bill-alt"></i>
								<p>Income</p>
								<span class="caret"></span>
							</a>
							<div class="collapse <?= activate_dropdown('income','div',$not); ?>" id="income">
								<ul class="nav nav-collapse">
									<li class="<?= activate_menu('income/roiincome'); ?>">
										<a href="<?= base_url('income/roiincome/'); ?>">
											<span class="sub-item">ROI Income</span>
										</a>
									</li>
									<li class="<?= activate_menu('income/levelincome'); ?>">
										<a href="<?= base_url('income/levelincome/'); ?>">
											<span class="sub-item">Level Income</span>
										</a>
									</li>
									<li class="<?= activate_menu('income/matchingincome'); ?>">
										<a href="<?= base_url('income/matchingincome/'); ?>">
											<span class="sub-item">Matching Income</span>
										</a>
									</li>
									<li class="<?= activate_menu('income/boosterincome'); ?>">
										<a href="<?= base_url('income/boosterincome/'); ?>">
											<span class="sub-item">Booster Income</span>
										</a>
									</li>
									<li class="<?= activate_menu('income/clubincome'); ?>">
										<a href="<?= base_url('income/clubincome/'); ?>">
											<span class="sub-item">Club Income</span>
										</a>
									</li>
								</ul>
							</div>
						</li>
                        <?php
                        $not=array('addfund','transferfund','fundhistory');
                        ?>
						<li class="nav-item submenu <?= activate_dropdown('wallet','li',$not); ?>">
							<a data-toggle="collapse" href="#wallet">
								<i class="fas fa-money-check"></i>
								<p>Withdrawal</p>
								<span class="caret"></span>
							</a>
							<div class="collapse <?= activate_dropdown('wallet','div',$not); ?>" id="wallet">
								<ul class="nav nav-collapse">
									<li class="<?= activate_menu('wallet/withdrawal'); ?>">
										<a href="<?= base_url('wallet/withdrawal/'); ?>">
											<span class="sub-item">New</span>
										</a>
									</li>
									<li class="<?= activate_menu('wallet/withdrawalhistory'); ?>">
										<a href="<?= base_url('wallet/withdrawalhistory/'); ?>">
											<span class="sub-item">History</span>
										</a>
									</li>
								</ul>
							</div>
						</li>
                        <?php
                        $not=array();
                        ?>
						<li class="nav-item submenu d-none <?= activate_dropdown('support','li',$not); ?>">
							<a data-toggle="collapse" href="#support">
								<i class="fas fa-headset"></i>
								<p>Support</p>
								<span class="caret"></span>
							</a>
							<div class="collapse <?= activate_dropdown('support','div',$not); ?>" id="support">
								<ul class="nav nav-collapse">
									<li class="<?= activate_menu('support'); ?>">
										<a href="<?= base_url('support/'); ?>">
											<span class="sub-item">New</span>
										</a>
									</li>
									<li class="<?= activate_menu('support/history'); ?>">
										<a href="<?= base_url('support/history/'); ?>">
											<span class="sub-item">History</span>
										</a>
									</li>
								</ul>
							</div>
						</li>
                        <?php
                            } 
                        ?>
					</ul>
				</div>
			</div>
		</div>
        