
      <section class="register-section">
         <div class="container">
            <div class="jioheadline">
               <h4 class="subheading">Smart Investing </h4>
               <h2>Create Your Account</h2>
               <p>Register now and start investing with JioEmpireMoney</p>
            </div>
            <form class="registration-form">
               <div class="row">
                  <div class="col-lg-6">
                     <div class="form-group">
                        <label for="fullname">Full Name</label>
                        <input type="text" id="fullname" placeholder="Enter your full name" required>
                     </div>
                  </div>
                  <div class="col-lg-6">
                     <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" placeholder="Enter your email" required>
                     </div>
                  </div>
                  <div class="col-lg-6">
                     <div class="form-group">
                        <label for="phone">Mobile Number</label>
                        <input type="tel" id="phone" placeholder="Enter your mobile number" required>
                     </div>
                  </div>
                  <div class="col-lg-6">
                     <div class="form-group">
                        <label for="memberid">Member Id</label>
                        <input type="text" id="memberid" placeholder="Member Id">
                     </div>
                  </div>
                  <div class="col-lg-6">
                     <div class="form-group">
                        <label for="spouse">Spouse Id</label>
                        <input type="password" id="spouse" placeholder="764876" disabled>
                     </div>
                  </div>
                  <div class="col-lg-6">
                     <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" placeholder="Create a password" required>
                     </div>
                  </div>
               </div>
               <div class="registerBtn">
                  <button type="submit" class="btn-submit">Register Now</button>
               </div>
               <p class="already">Already have an account? <a href="<?= $this->app_link; ?>">Download App</a> to Login</p>
            </form>
         </div>
      </section>