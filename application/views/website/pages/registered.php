

    <div class="container-fluid bg-breadcrumb">
        <div class="container text-center py-5" style="max-width: 900px;">
            <h4 class="text-white display-4 mb-4 wow fadeInDown" data-wow-delay="0.1s">Registered</h4>
            <ol class="breadcrumb d-flex justify-content-center mb-0 wow fadeInDown" data-wow-delay="0.3s">
                <li><a href="<?= base_url(); ?>" style="color:white">Home / </a></li>
                <li class="breadcrumb-item active text-light">&nbsp;Registered</li>
            </ol>
        </div>
    </div>


    <div class="container-fluid bg-light about py-5">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <h3 class="header smaller lighter green">Successfully Registered!</h3>
                    <h4>Welcome <?php echo $this->session->flashdata('mname'); ?>,</h4><br>
                    <ul style="list-style:none;">
                        <li><h4>Username : <?php echo $this->session->flashdata('uname');?></h4><br></li>
                        <li><h4>Password : <?php echo $this->session->flashdata('pass');?></h4></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
