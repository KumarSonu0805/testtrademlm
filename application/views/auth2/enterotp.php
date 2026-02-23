<style>

    .pop-up {
        background-color: #e4efd9;
        padding: 45px 25px;
        border-radius: 10px;
        position: absolute;
        z-index: 2;
        color: #6A6A6A;
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
</style>
      <section class="loginsection">
         <div class="container">
             <?= form_open('login/validateotp'); ?>
            <div class="login-card">
               <div class="login-logo">
                  <img src="<?= file_url(LOGO) ?>" alt=" Logo" width="200" />
                  <p>Enter OTP</p>
               </div>
               <div class="mb-3">
                  <label for="memberid" class="form-label">OTP</label>
                  <input type="password" class="form-control" id="otp" name="otp" placeholder="OTP" required/>
               </div>
                    <div class="text-danger text-center mb-2"><?= $this->session->flashdata('logerr'); ?></div>
                <div class="mb-3">
                  <div class="futureBtn">
                     <button type="submit" name="submitotp">Submit OTP</button>
                  </div>
               </div>
            </div>
             <?= form_close(); ?>
         </div>
      </section>