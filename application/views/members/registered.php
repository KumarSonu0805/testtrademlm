
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card light-bg">
                                    <div class="card-header">
                                        <h3 class="card-title"><?= $title ?></h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <div class="row">
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
                            </div>
                        </div>
                    </div>
                </section>
