
      <header class="top-header">
  <div class="container">
    <ul class="top-links">
      <li>
        <a href="#">
          <i class="fas fa-headset"></i> Support
        </a>
      </li>
      <li>
        <a href="mailto:jioempiremoney@gmail.com">
          <i class="fas fa-envelope"></i> jioempiremoney@gmail.com
        </a>
      </li>
    </ul>
  </div>
</header>

 <nav class="navbar navbar-expand-lg sticky-top jioempire-navbar">
   <div class="container">
      <a class="navbar-brand" href="<?= base_url(); ?>"><img src="<?= file_url('assets/images/jioempiremoney.webp'); ?>" alt="logo" class="jioempirelogo"></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNav">
      <span class="navbar-toggler-icon"></span>
      </button>
      <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNav">
         <div class="offcanvas-header">
            <h5 class="offcanvas-title"><img src="<?= file_url('assets/images/jioempiremoney.webp'); ?>" alt="logo" class="jioempirelogo"></h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
         </div>
         <div class="offcanvas-body">
            <ul class="navbar-nav ms-auto">
               <li class="nav-item">
                  <a class="nav-link <?= activate_menu('website'); ?>" href="<?= base_url(); ?>">Home</a>
               </li>
               <li class="nav-item">
                  <a class="nav-link <?= activate_menu('website/aboutus'); ?>" href="<?= base_url('about-us/'); ?>">About Us</a>
               </li>
                  <li class="nav-item">
                  <a class="nav-link <?= activate_menu('website/service'); ?>" href="<?= base_url('service/'); ?>">Service/Plan</a>
               </li>
                  <li class="nav-item">
                  <a class="nav-link <?= activate_menu('website/gallery'); ?>" href="<?= base_url('gallery/'); ?>">Gallery</a>
               </li>
                  <li class="nav-item">
                  <a class="nav-link <?= activate_menu('website/contactus'); ?>" href="<?= base_url('contact-us/'); ?>">Contact Us</a>
               </li>
               <li class="nav-item registrationnav">
                  <a class="nav-link" href="<?= base_url('register-now/'); ?>">
                     <div class="join-us-btn">
                         Registration Now
                     </div>
                  </a>
               </li>
               <li class="nav-item registrationnav">
                  <a class="nav-link" href="<?= $this->app_link; ?>">
                     <div class="join-us-btn">
                         Download App
                     </div>
                  </a>
               </li>
            </ul>
         </div>
      </div>
   </div>
</nav>  