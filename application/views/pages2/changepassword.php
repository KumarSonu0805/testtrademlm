
                <div class="user-profile-form">
                   <div class="headline">
                      <h2><?= $title; ?></h2>
                   </div>
                   <div class="row">
                      <div class="col-md-6">
                        <?= form_open_multipart('home/updatepassword/','onSubmit="return validate()"'); ?>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Username</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="username" id="username" readonly value="<?= $user['username'] ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Old Password</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control" name="old_password" id="old_password" required value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">New Password</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control" name="password" id="password" required value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Re-Type Password</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control" name="repassword" id="repassword" required value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label"></label>
                                <div class="col-sm-10">
                                    <input type="submit" class="btn btn-success bg-success" name="updatepassword" value="Update Password">
                                </div>
                            </div>
                        <?= form_close(); ?>
                    </div>
                </div>   
            </div>