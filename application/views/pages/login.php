<!-- <body> in top-section-->

        <div class="login-box">
            <div class="login-logo">
                <a href="<?php echo base_url(); ?>">
                    <img src="<?= file_url('assets/images/logo.webp') ?>" alt="<?php echo PROJECT_NAME; ?>" height="120">
                </a>
            </div>
            <!-- /.login-logo -->
            <div class="card">
                <div class="card-body login-card-body">
                <p class="login-box-msg">Sign in to start your session</p>
                
                    <?php echo form_open('login/validateLogin'); ?>
                        <div class="input-group mb-3">
                            <input type="username" class="form-control" name="username" placeholder="Username">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" class="form-control" name="password" placeholder="Password">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>
                        <div class="text-center text-danger form-group">
                            <?php if($this->session->flashdata("logerr")!==NULL){ echo $this->session->flashdata("logerr");} ?>
                        </div>
                        <div class="row">
                            <div class="col-8">
                                <div class="icheck-primary">
                                    <input type="checkbox" id="remember">
                                    <label for="remember">Remember Me</label>
                                </div>
                            </div>
                            <!-- /.col -->
                            <div class="col-4">
                                <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                            </div>
                            <!-- /.col -->
                        </div>
                    <?php echo form_close(); ?>
                
                
                </div>
                <!-- /.login-card-body -->
            </div>
        </div>
        <!-- /.login-box -->
</body>