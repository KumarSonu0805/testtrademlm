<style>
    .login-box{
        height: 100vh;
        display: flex;
        align-items: center;
    }
    .card{
        width: 40%;
        margin: 0 auto;
        /* padding: 20px 40px; */
        box-shadow: 0 0 24px 0 rgba(3, 31, 66, 0.1);
    }
    .pop-up {
        background-color: #6185acee;
        box-shadow: 0 0 20px var(--shadow-color);
        padding: 45px 25px;
        border-radius: 10px;
        position: absolute;
        z-index: 2;
        color: #dcbb1a;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    .pop-up i{
        color: #ddb82f;
    }
    body::before{
        z-index: -1;
    }
    body.pop::before{
        content: "";
        background-color: #00000044;
        position: absolute;
        height: 100%;
        z-index: 1;
        width: 100%;
    }
	@media (max-width: 350px){
        .card,.pop-up { 
            width: 95%;
        }
    }
    @media (min-width: 351px) and (max-width: 425px){
        .card,.pop-up { 
            width: 80%;
        }
    }
    @media (min-width: 426px) and (max-width: 768px) {
        .card,.pop-up { 
            width: 70%;
        }
	}
</style>
            <?php
                if($this->session->flashdata('msg')=='Registered Successfully!'){
            ?>
            <div class="pop-up text-center">
                <p class="text-center"><i class="fa fa-check-circle fa-4x"></i></p>
                <h5>Registered Successfully!</h5>
                <h4>Your ID is <?= $user['username']; ?></h4>
                <h4>Password <?= $user['vp']; ?></h4>
                <a href="<?= base_url('home/') ?>" class="btn btn-warning text-white">Go To Dashboard</a>
            </div>
            <script>
                $(document).ready(function(){
                    $('body').addClass('pop');
                });
            </script>
            <?php
                }
            ?>
      <section class="loginsection">
         <div class="container">
             <?= form_open('login/validatelogin'); ?>
            <div class="login-card">
               <div class="login-logo">
                  <img src="<?= file_url(LOGO) ?>" alt=" Logo" width="200" />
                  <p>Login To your account</p>
               </div>
               <div class="mb-3">
                  <label for="username" class="form-label">Username</label>
                  <input type="text" name="username" placeholder="Username" required/>
               </div>
               <div class="mb-3">
                  <label for="password" class="form-label">Password</label>
                  <input type="password" name="password" placeholder="Password" required/>
               </div>
                <div class="text-danger text-center mb-2"><?= $this->session->flashdata('logerr'); ?></div>
                <div class="mb-3">
                  <div class="futureBtn">
                     <button name="login">Login</button>
                  </div>
               </div>
                <?php
                if($this->uri->segment(1)!='Admin'){
                ?>
               <div class="mb-3 createpassword">
                  <div class="row">
                     <div class="col-lg-6">
                        <a href="<?= base_url('register/'); ?>">Register Now</a>
                     </div>
                     <div class="col-lg-6">
                     </div>
                  </div>
               </div>
                <?php
                }
                ?>
            </div>
             <?= form_close(); ?>
         </div>
      </section>
