<nav class="sidebar border-end" id="sidebar">
   <div class="text-center p-4 sidemenulogo">
      <img src="<?= file_url(LOGO); ?>" alt="Logo">
   </div>
   <ul class="nav flex-column" id="sidebarAccordion">
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
      <li class="nav-item">
         <a class="nav-link" href="<?= base_url('home'); ?>">
         <i class="fa-solid fa-dashboard"></i> Dashboard
         </a>
      </li>
      <li class="nav-item d-none">
         <a class="nav-link">
         <i class="fa-regular fa-square-plus"></i> New Registration
         </a>
      </li>
        <?php
            if($this->session->role=='member'){
        ?>
      <li class="nav-item d-none">
         <a class="nav-link" data-bs-toggle="collapse" href="#profileMenu" role="button" aria-expanded="false" aria-controls="profileMenu">
         <i class="fa-solid fa-user"></i> Profile
         <i class="fas fa-chevron-down ms-auto"></i>
         </a>
         <div class="collapse" id="profileMenu" data-bs-parent="#sidebarAccordion">
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
         <a class="nav-link" data-bs-toggle="collapse" href="#membersMenu" role="button" aria-expanded="false" aria-controls="membersMenu">
         <i class="fa-solid fa-users"></i> Members
         <i class="fas fa-chevron-down ms-auto"></i>
         </a>
         <div class="collapse" id="membersMenu" data-bs-parent="#sidebarAccordion">
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
         <a class="nav-link" data-bs-toggle="collapse" href="#depositeMenu" role="button" aria-expanded="false" aria-controls="depositeMenu">
         <i class="fa-solid fa-money-bill-transfer"></i> Deposit
         <i class="fas fa-chevron-down ms-auto"></i>
         </a>
         <div class="collapse" id="depositeMenu" data-bs-parent="#sidebarAccordion">
            <ul class="nav flex-column ms-3">
               <li class="nav-item"><a class="nav-link" href="<?= base_url('deposit/'); ?>">Add Deposit</a></li>
               <li class="nav-item"><a class="nav-link" href="<?= base_url('deposit/depositlist/'); ?>">Deposit History</a></li>
            </ul>
         </div>
      </li>
      <li class="nav-item">
         <a class="nav-link" href="<?= base_url('home/invite'); ?>">
         <i class="fa-solid fa-th-large"></i> Invite
         </a>
      </li>
      <li class="nav-item">
         <a class="nav-link" href="<?= base_url('income/reward'); ?>">
         <i class="fa-solid fa-gift"></i> Reward
         </a>
      </li>
      <li class="nav-item">
         <a class="nav-link" href="<?= base_url('income/club'); ?>">
         <i class="fa-solid fa-tasks"></i> Club
         </a>
      </li>
      <li class="nav-item">
         <a class="nav-link" data-bs-toggle="collapse" href="#walletMenu" role="button" aria-expanded="false" aria-controls="walletMenu">
         <i class="fa-solid fa-money-bill-transfer"></i> Wallet
         <i class="fas fa-chevron-down ms-auto"></i>
         </a>
         <div class="collapse" id="walletMenu" data-bs-parent="#sidebarAccordion">
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
         <a class="nav-link" data-bs-toggle="collapse" href="#walletMenu" role="button" aria-expanded="false" aria-controls="walletMenu">
         <i class="fa-solid fa-money-bill-transfer"></i> Wallet
         <i class="fas fa-chevron-down ms-auto"></i>
         </a>
         <div class="collapse" id="walletMenu" data-bs-parent="#sidebarAccordion">
            <ul class="nav flex-column ms-3">
               <li class="nav-item"><a class="nav-link" href="<?= base_url('wallet/withdrawalrequests/'); ?>">Withdrawal Requests</a></li>
               <li class="nav-item"><a class="nav-link" href="<?= base_url('wallet/history/'); ?>"> Withdrawal History</a></li>
            </ul>
         </div>
      </li>
      <?php
         }
      ?>
      <li class="nav-item">
         <a class="nav-link" href="<?= base_url('logout/'); ?>">
         <i class="fa-solid fa-right-from-bracket"></i> Logout
         </a>
      </li>
   </ul>
</nav>