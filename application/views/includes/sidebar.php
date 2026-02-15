<!-- Main Sidebar Container -->
<aside class="main-sidebar elevation-4 sidebar-no-expand <?= SIDEBAR_COLOR; ?>">
    <!-- Brand Logo -->
    <a href="<?= base_url('home/'); ?>" class="brand-link <?= BRAND_COLOR; ?>  text-center" style="background: transparent;">
        <img src="<?= file_url("assets/images/logo.jpg"); ?>" alt="<?= PROJECT_NAME; ?> Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light lead"><strong><?= PROJECT_NAME; ?></strong></span>
    </a>
    
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
            	<img src="<?= $userphoto; ?>" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info pt-0">
            	<a href="#" class="d-block"><?= $this->session->name; ?></a>
            	<a href="#" class="d-block"><?= $this->session->username; ?></a>
            </div>
        </div>
        
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-compact nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
                <?php if($this->session->role!='admin' && $this->session->sess_type=='admin_access'){ ?>
                <li class="nav-item">
                    <a href="<?= base_url('login/backtoadmin/'); ?>" class="nav-link <?= activate_menu('login/backtoadmin'); ?>">
                        <i class="nav-icon fas fa-arrow-left"></i>
                        <p>Back To Admin Panel</p>
                    </a>
                </li>
                <?php } ?>
                <li class="nav-item">
                    <a href="<?= base_url('home/'); ?>" class="nav-link <?= activate_menu('home'); ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Home</p>
                    </a>
                </li>
                <?php if($this->session->role=='member'){ ?>
                <li class="nav-item has-treeview <?= activate_dropdown('profile'); ?>">
                    <a href="#" class="nav-link <?= activate_dropdown('profile','a'); ?>">
                        <i class="nav-icon fas fa-user"></i>
                        <p>Member Details <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url("profile/"); ?>" class="nav-link <?= activate_menu('profile'); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Profile</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url("profile/changepassword/"); ?>" class="nav-link <?= activate_menu('profile/changepassword'); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Change Password</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url("profile/accdetails/"); ?>" class="nav-link <?= activate_menu('profile/accdetails'); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Account Details</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url("profile/kyc/"); ?>" class="nav-link <?= activate_menu('profile/kyc'); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>KYC</p>
                            </a>
                        </li>
                        <?php /*?><li class="nav-item">
                            <a href="<?= base_url("profile/idcard/"); ?>" class="nav-link <?= activate_menu('profile/idcard'); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>ID Card</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url("profile/welcomeletter/"); ?>" class="nav-link <?= activate_menu('profile/welcomeletter'); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Welcome Letter</p>
                            </a>
                        </li><?php */?>
                    </ul>
                </li>
                <?php }else{ ?> 
                <li class="nav-item">
                    <a href="<?= base_url('profile/changepassword/'); ?>" class="nav-link <?= activate_menu('profile/changepassword'); ?>">
                        <i class="nav-icon fas fa-key"></i>
                        <p>Change Password</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('profile/adminaccdetails/'); ?>" class="nav-link <?= activate_menu('profile/adminaccdetails'); ?>">
                        <i class="nav-icon fas fa-list"></i>
                        <p>Admin Account Details</p>
                    </a>
                </li>
                <?php } ?>
                <li class="nav-item has-treeview <?= activate_dropdown('members','li',array('treeview')); ?>">
                    <a href="#" class="nav-link <?= activate_dropdown('members','a',array('treeview')); ?>">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Members <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url("members/"); ?>" class="nav-link <?= activate_menu('members'); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Member Registration</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url("members/memberlist/"); ?>" class="nav-link <?= activate_menu('members/memberlist'); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Member List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url("members/activelist/"); ?>" class="nav-link <?= activate_menu('members/activelist'); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Active Member List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url("members/inactivelist/"); ?>" class="nav-link <?= activate_menu('members/inactivelist'); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>In-Active Member List</p>
                            </a>
                        </li>
                        <li class="nav-item d-none">
                            <a href="<?= base_url("members/tree/"); ?>" class="nav-link <?= activate_menu('members/tree'); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Member Tree</p>
                            </a>
                        </li>
                        <li class="nav-item d-none">
                            <a href="<?= base_url("members/levelwisemembers/"); ?>" class="nav-link <?= activate_menu('members/levelwisemembers'); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Level Wise Member List</p>
                            </a>
                        </li>
                        <?php if($this->session->role=='admin'){ ?>
                        <li class="nav-item">
                            <a href="<?= base_url("members/kyc/"); ?>" class="nav-link <?= activate_menu('members/kyc'); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>KYC Requests</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url("members/approvedkyc/"); ?>" class="nav-link <?= activate_menu('members/approvedkyc'); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Approved KYC</p>
                            </a>
                        </li>
                        <?php } ?>
                        <?php /*?>
                        <li class="nav-item">
                            <a href="<?= base_url("members/renewals/"); ?>" class="nav-link <?= activate_menu('members/renewals'); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Member Renewals</p>
                            </a>
                        </li><?php */?>
                    </ul>
                </li>
                <?php if($this->session->role=='admin'){ ?>
                <li class="nav-item">
                    <a href="<?= base_url('activationrequests/'); ?>" class="nav-link <?= activate_menu('epins/requestlist'); ?>">
                        <i class="nav-icon fa fa-list"></i>
                        <p>Activation Requests</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('packages/'); ?>" class="nav-link <?= activate_menu('packages'); ?>">
                        <i class="nav-icon fa fa-list"></i>
                        <p>Packages</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('notice/'); ?>" class="nav-link <?= activate_menu('settings/news'); ?>">
                        <i class="nav-icon fa fa-list"></i>
                        <p>Notice</p>
                    </a>
                </li>
                <li class="nav-item has-treeview <?= activate_dropdown('wallet','li',['packages','funds','purchaselist']); ?>">
                    <a href="#" class="nav-link <?= activate_dropdown('wallet','a',['packages','funds','purchaselist']); ?>">
                        <i class="nav-icon fas fa-wallet"></i>
                        <p>Wallet <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url("wallet/depositrequestlist/"); ?>" class="nav-link <?= activate_menu('wallet/depositrequestlist'); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p> Deposit Requests</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url("wallet/approveddepositlist/"); ?>" class="nav-link <?= activate_menu('wallet/approveddepositlist'); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Approved Deposits</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url("wallet/memberwisedeposit/"); ?>" class="nav-link <?= activate_menu('wallet/memberwisedeposit'); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Member Wise Deposits</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url("wallet/requestlist/"); ?>" class="nav-link <?= activate_menu('wallet/requestlist'); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Withdrawal Requests</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url("wallet/approvedlist/"); ?>" class="nav-link <?= activate_menu('wallet/approvedlist'); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Approved Payments</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url("wallet/rejectedlist/"); ?>" class="nav-link <?= activate_menu('wallet/rejectedlist'); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Rejected Payments</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('wallet/funds/'); ?>" class="nav-link <?= activate_menu('wallet/funds'); ?>">
                        <i class="nav-icon fa fa-money-bill"></i>
                        <p>Fund Report</p>
                    </a>
                </li>
                <li class="nav-item has-treeview <?= activate_dropdown('settings','li'); ?> d-none">
                    <a href="#" class="nav-link <?= activate_dropdown('settings','a'); ?>">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>Settings <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url("settings/whatsappno/"); ?>" class="nav-link <?= activate_menu('settings/whatsappno'); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>WhatsApp Number</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url("settings/telegram/"); ?>" class="nav-link <?= activate_menu('settings/telegram'); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Telegram Link</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url("settings/qrimage/"); ?>" class="nav-link <?= activate_menu('settings/qrimage'); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>QR Code Image</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item d-none">
                    <a href="<?= base_url('settings/qrimage/'); ?>" class="nav-link <?= activate_menu('settings/qrimage'); ?>">
                        <i class="nav-icon fa fa-qrcode"></i>
                        <p>QR Code</p>
                    </a>
                </li>
                <?php }else{ ?> 
                <li class="nav-item has-treeview <?= activate_dropdown('wallet','li',['index','withdrawal','history','transfer','transferhistory']); ?>">
                    <a href="#" class="nav-link <?= activate_dropdown('wallet','a',['index','withdrawal','history','transfer','transferhistory']); ?>">
                        <i class="nav-icon fas fa-money-bill"></i>
                        <p>Deposit <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url("wallet/adddeposit/"); ?>" class="nav-link <?= activate_menu('wallet/adddeposit'); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add Deposit</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url("wallet/depositlist/"); ?>" class="nav-link <?= activate_menu('wallet/depositlist'); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Deposit List</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview <?= activate_dropdown('wallet','li',['packages','funds','adddeposit','depositlist']); ?>">
                    <a href="#" class="nav-link <?= activate_dropdown('wallet','a',['packages','funds','adddeposit','depositlist']); ?>">
                        <i class="nav-icon fas fa-wallet"></i>
                        <p>Wallet <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url("wallet/"); ?>" class="nav-link <?= activate_menu('wallet'); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>My Wallet</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url("wallet/withdrawal/"); ?>" class="nav-link <?= activate_menu('wallet/withdrawal'); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Withdrawal</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url("wallet/history/"); ?>" class="nav-link <?= activate_menu('wallet/history'); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Withdrawal History</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url("wallet/transfer/"); ?>" class="nav-link <?= activate_menu('wallet/transfer'); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Wallet Transfer</p>
                            </a>
                        </li>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url("wallet/transferhistory/"); ?>" class="nav-link <?= activate_menu('wallet/transferhistory'); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Wallet Received History</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <?php } ?>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

<div class="content-wrapper">
    <?php /*?><div class="overlay" id="loading">
        <i class="fa fa-3x fa-refresh fa-spin"></i>
    </div><?php */?>
	<?php
    	$this->load->view('includes/breadcrumb');
	?>
    <!-- Main content -->
    
