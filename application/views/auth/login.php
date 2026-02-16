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
	@media (max-width: 350px){
        .card {
            width: 95%;
        }
    }
    @media (min-width: 351px) and (max-width: 425px){
        .card {
            width: 80%;
        }
    }
    @media (min-width: 426px) and (max-width: 768px) {
        .card {
            width: 70%;
        }
	}
</style>
            <div class="login-box">
                <div class="container">
                    <!-- /.login-logo -->
                    <div class="card card-outline">
                        <div class="card-header text-center">
                        <a href="<?= base_url(); ?>" class="h1"><img src="<?= file_url(LOGO) ?>" alt=" Logo" class="img-fluid"></a>
                        </div>
                        <div class="card-body">
                            <p class="login-box-msg">Sign in to start your session</p>
                            <?= form_open('login/validatelogin'); ?>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" name="username" placeholder="Username" required>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="input-group mb-3">
                                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-lock"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-danger text-center mb-2"><?= $this->session->flashdata('logerr'); ?></div>
                                <div class="row">
                                    <div class="col-8 d-none">
                                        <div class="icheck-primary">
                                            <input type="checkbox" id="remember">
                                            <label for="remember">
                                            Remember Me
                                            </label>
                                        </div>
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-4">
                                        <button type="submit" name="login" class="btn btn-oflep btn-block">Sign In</button>
                                    </div>
                                    <!-- /.col -->
                                </div>
                            <?= form_close(); ?>

                            <p class="mb-1 d-none">
                                <a href="<?= base_url('forgot-password/'); ?>">I forgot my password</a>
                            </p>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                 </div>
            </div>
            <!-- /.login-box -->
