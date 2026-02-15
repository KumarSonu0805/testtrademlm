
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title"><?= $title ?></h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-2"></div>
                                            <div class="col-md-8">
                                                <?= form_open_multipart('home/updatepassword/'); ?>
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label">Username</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" class="form-control" name="username" id="username" readonly value="<?= $user['username'] ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label">New Password</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" class="form-control" name="password" id="password" required value="">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label"></label>
                                                        <div class="col-sm-10">
                                                            <input type="submit" class="btn btn-success waves-effect waves-light" name="updatepassword" value="Update Password">
                                                        </div>
                                                    </div>
                                                <?= form_close(); ?>
                                            </div>
                                        </div>

                                    </div>
                                 </div>
                            </div>
                        </div>

                    </div>
                </section>