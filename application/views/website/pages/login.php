

    <div class="container-fluid bg-breadcrumb">
        <div class="container text-center py-5" style="max-width: 900px;">
            <h4 class="text-white display-4 mb-4 wow fadeInDown" data-wow-delay="0.1s">Login</h4>
            <ol class="breadcrumb d-flex justify-content-center mb-0 wow fadeInDown" data-wow-delay="0.3s">
                <li><a href="<?= base_url(); ?>" style="color:white">Home / </a></li>
                <li class="breadcrumb-item active text-light">&nbsp;Login</li>
            </ol>
        </div>
    </div>


    <div class="container-fluid contact bg-light py-5">
        <div class="container py-5">
            <div class="text-center mx-auto pb-5 wow fadeInUp" data-wow-delay="0.2s" style="max-width: 800px;">
                <h4 class="text-primary">Login</h4>
                <h1 class="display-4 mb-4">Login your account</h1>
            </div>
            <div class="row g-5"> 
                <div class="col-xl-12 wow fadeInRight" data-wow-delay="0.4s">
                    <div>
                        <?php echo form_open('login/validateLogin'); ?>
                            <div class="row g-3">
                                <div class="col-lg-12 col-xl-12">
                                    <div class="form-floating">
                                        <input type="text" class="form-control border-0" id="username" name="username" placeholder="Enter your username">
                                        <label for="name">Username :</label>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-xl-12">
                                    <div class="form-floating">
                                        <input type="password" class="form-control border-0" id="password" name="password" placeholder="Enter your password">
                                        <label for="password">Password</label>
                                    </div>
                                </div> 
                                <div class="text-center text-danger form-group">
                                    <?php if($this->session->flashdata("logerr")!==NULL){ echo $this->session->flashdata("logerr");} ?>
                                </div>
                                <div class="col-3">
                                    <button type="submit" class="btn btn-primary w-100 py-3">Login</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div> 
            </div>
        </div>
    </div>
