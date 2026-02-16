					<div class="row">
						<div class="col-md-6">
							<div class="card">
								<div class="card-header">
									<h4 class="card-title"><?= $title; ?></h4>
									
								</div>
								<div class="card-body">
                                    <?= form_open('members/memberdashboard'); ?>
                                        <div class="form-group form-inline">
                                            <label for="member_id" class="col-md-3 col-form-label">Member ID</label>
                                            <div class="col-md-9 p-0">
                                                <input type="text" class="form-control input-full" id="member_id" name="member_id" placeholder="Enter Member ID">
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-success">Enter to Member Dashboard</button>
                                    <?= form_close(); ?>
								</div>
							</div>
						</div>
					</div>