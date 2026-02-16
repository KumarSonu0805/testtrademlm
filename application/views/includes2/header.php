<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3 topmenubar">
   <button class="btn btn-outline-secondary d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-controls="mobileSidebar">
      <i class="fas fa-bars"></i>
      </button>
   <h4 class="mb-0"><?= $title; ?></h4>
<div class="dropdown">
   <a href="#" class="d-flex align-items-center text-decoration-none" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
   <img src="<?php if(!empty($this->session->photo)){echo ($this->session->photo);}else{echo file_url('assets/images/avatar.jpg');} ?>" alt="Profile" width="36" height="36" class="rounded-circle">
   <span class="ms-2 fw-semibold text-dark d-none d-md-inline"></span>
   </a>
   <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
      <li>
         <div class="dropdown-profile d-flex align-items-center px-3 py-2">
            <img src="<?php if(!empty($this->session->photo)){echo ($this->session->photo);}else{echo file_url('assets/images/avatar.jpg');} ?>" alt="future-coin">
            <div class="ms-2">
               <h5><?= $this->session->name; ?></h5>
               <p><?= $this->session->username ?></p>
            </div>
         </div>
      </li>
      <li>
         <hr class="dropdown-divider">
      </li>
      <li><a class="dropdown-item text-danger" href="<?= base_url('logout/') ?>"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
   </ul>
</div>
</div>