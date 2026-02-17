                <?php
                    if($page_type=='auth'){
                        !empty($content)?$this->load->view($content):'';
                    }
                    else{
                ?>
                <?php
                    if(!empty($content_script)){
                        foreach($content_script as $key=>$script){
                            if($key=="link"){
                                if(is_array($script)){
                                    foreach($script as $single_script){
                                        echo "<script src='$single_script'></script>\n\t";
                                    }
                                }
                                else{
                                    echo "<script src='$script'></script>\n\t";
                                }
                            }
                            elseif($key=="file"){
                                if(is_array($script)){
                                    foreach($script as $single_script){
                                        echo "<script src='".file_url("$single_script")."'></script>\n\t";
                                    }
                                }
                                else{
                                    echo "<script src='".file_url("$script")."'></script>\n\t";
                                }
                            }
                        }
                    }
                ?>
              <div class="dashboard container-fluid d-flex">
                <div class="">
                 <nav class="sidebar border-end d-none d-md-block fixed" id="sidebar">

                    <div class="sidebar-wrapper desktopmobilebar">
                       <?php $this->load->view('includes2/sidebar'); ?>
                    </div>
                 </nav>
                 <div class="offcanvas offcanvas-start mobilesidebar" tabindex="-1" id="mobileSidebar" aria-labelledby="mobileSidebarLabel">
                    <div class="offcanvas-header">
                       <div class="text-center sidemenulogo d-flex align-items-center">
                          <img src="<?= file_url(LOGO); ?>" alt="Logo">
                          <h5 class="mt-2"><?= PROJECT_NAME ?></h5>
                       </div>
                       <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
                    </div>
                    <div class="offcanvas-body p-0">
                       <nav class="">
                          <ul class="nav flex-column" id="mobileAccordion">
                             <div class="nav-linktitle">Member Dashboard</div>
                                <?php
                                    if($this->session->sess_type=='admin_access'){
                                ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= base_url('login/backtoadmin/'); ?>">
                                <i class="fa-solid fa-arrow-left"></i> Back To Admin Panel
                                </a>
                            </li>
                            <?php
                                    }
                            ?>
                             <li class="nav-item"><a class="nav-link" href="<?= base_url('home/'); ?>"><i class="fa-solid fa-house"></i> Dashboard</a></li>
                            <?php
                                if($this->session->role=='member'){
                            ?>
                             <li class="nav-item d-none">
                                <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#mobileProfileMenu">
                                <span><i class="fa-solid fa-user"></i> Profile</span>
                                <i class="fas fa-chevron-down"></i>
                                </a>
                                <div class="collapse" id="mobileProfileMenu" data-bs-parent="#mobileAccordion">
                                   <ul class="nav flex-column ms-3">
                                        <li class="nav-item"><a class="nav-link" href="<?= base_url('profile/'); ?>">Profile</a></li>
                                        <li class="nav-item"><a class="nav-link" href="<?= base_url('changepassword/') ?>">Change Password</a></li>
                                   </ul>
                                </div>
                             </li>
                            <?php
                                }
                                else{
                                    ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?= base_url('changepassword/') ?>">
                                    <i class="fa-regular fa-square-plus"></i> Change Password
                                    </a>
                                </li>
                             <li class="nav-item">
                                <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#mobileMemberMenu">
                                <span><i class="fa-solid fa-users"></i> Members</span>
                                <i class="fas fa-chevron-down"></i>
                                </a>
                                <div class="collapse" id="mobileMemberMenu" data-bs-parent="#mobileAccordion">
                                   <ul class="nav flex-column ms-3">
                                    <li class="nav-item"><a class="nav-link" href="<?= base_url('members/memberlist/'); ?>">Downline Members</a></li>
                                        <?php
                                            if($this->session->role=='member'){
                                        ?>
                                    <li class="nav-item"><a class="nav-link" href="<?= base_url('members/directmembers/'); ?>">Direct Members</a></li>
                                        <?php
                                            }
                                            else{
                                        ?>
                                    <li class="nav-item"><a class="nav-link" href="<?= base_url('members/entertomember/'); ?>">Enter To Member</a></li>
                                        <?php
                                            }
                                        ?>
                                   </ul>
                                </div>
                             </li>
                                <?php
                                }
                            ?>
                            <?php
                                if($this->session->role=='member'){
                            ?>
                             <li class="nav-item">
                                <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#mobileDepositeMenu">
                                <span><i class="fa-solid fa-money-bill-transfer"></i> Deposit</span>
                                <i class="fas fa-chevron-down"></i>
                                </a>
                                <div class="collapse" id="mobileDepositeMenu" data-bs-parent="#mobileAccordion">
                                   <ul class="nav flex-column ms-3">
                                      <li class="nav-item"><a class="nav-link" href="<?= base_url('deposit/'); ?>">Add Deposit</a></li>
                                      <li class="nav-item"><a class="nav-link" href="<?= base_url('deposit/depositlist/'); ?>">Deposit List</a></li>
                                   </ul>
                                </div>
                             </li>
                             <li class="nav-item">
                                <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#mobileWalletMenu">
                                <span><i class="fa-solid fa-money-bill-transfer"></i> Wallet</span>
                                <i class="fas fa-chevron-down"></i>
                                </a>
                                <div class="collapse" id="mobileWalletMenu" data-bs-parent="#mobileAccordion">
                                   <ul class="nav flex-column ms-3">
                                      <li class="nav-item"><a class="nav-link" href="<?= base_url('wallet/withdrawal/'); ?>">Withdrawal</a></li>
                                      <li class="nav-item"><a class="nav-link" href="<?= base_url('wallet/withdrawalhistory/'); ?>">Withdrawal History</a></li>
                                   </ul>
                                </div>
                             </li>
                             <?php
                                }
                                else{
                            ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= base_url('settings/') ?>">
                                <i class="fas fa-cogs"></i> Settings
                                </a>
                            </li>
                             <li class="nav-item">
                                <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#mobileWalletMenu">
                                <span><i class="fa-solid fa-money-bill-transfer"></i> wallet</span>
                                <i class="fas fa-chevron-down"></i>
                                </a>
                                <div class="collapse" id="mobileWalletMenu" data-bs-parent="#mobileAccordion">
                                   <ul class="nav flex-column ms-3">
                                        <li class="nav-item"><a class="nav-link" href="<?= base_url('wallet/withdrawalrequests/'); ?>">Withdrawal Requests</a></li>
                                        <li class="nav-item"><a class="nav-link" href="<?= base_url('wallet/history/'); ?>">Withdrawal History</a></li>
                                   </ul>
                                </div>
                             </li>
                             <?php
                                }
                            ?>
                             <li class="nav-item mt-3">
                                <a class="nav-link" href="<?= base_url('logout/'); ?>"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
                             </li>
                          </ul>
                       </nav>
                    </div>
                  </div>
                 </div>
                 <div class="main-content pb-5">
                       <?php $this->load->view('includes2/header'); ?>
                       <div class="main-deshboard-section mb-5">
                       <?php !empty($content)?$this->load->view($content):''; ?>
                       </div>
                       <?php $this->load->view('includes2/footer'); ?>
                    
                  </div>
              </div>
                
                <?php /*?><div class="main-panel">
                    <div class="content">
                        <div class="page-inner">
                            <div class="page-header">
                                <h4 class="page-title"><?= $title; ?></h4>
                                <?php
                                if(!empty($breadcrumbs)){
                                ?>
                                <ul class="breadcrumbs">
                                    <?php
                                    if(!isset($breadcrumb['active']) && $this->uri->segment(1)!=''){ $breadcrumb['active']=$title; }
                                    foreach($breadcrumb as $link=>$crumb){
                                        if($link=='active'){
                                            echo '<li class="breadcrumb-item active" aria-current="page">'.$crumb.'</li>';
                                        }
                                        else{
                                            echo '<li class="breadcrumb-item"><a href="'.base_url($link).'">'.$crumb.'</a></li>';
                                        }
                                    }	
                                    ?>
                                    <li class="nav-home">
                                        <a href="#">
                                            <i class="flaticon-home"></i>
                                        </a>
                                    </li>
                                    <li class="separator">
                                        <i class="flaticon-right-arrow"></i>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#">Base</a>
                                    </li>
                                    <li class="separator">
                                        <i class="flaticon-right-arrow"></i>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#">Avatars</a>
                                    </li>
                                </ul>
                                <?php
                                }
                                ?>
                            </div>
                            <?php !empty($content)?$this->load->view($content):''; ?>
                        </div>
                    </div>
                    <?php $this->load->view($footer); ?>
                </div><?php */?>

                <?php
                    }
                ?>