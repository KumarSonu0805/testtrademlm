
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
                                        <?php if($this->session->role=="admin" && $title=='Active Member List'){ ?>
                                        <div class="row">
                                            <div class="col-12">
                                                <form action="">
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <label for="from">From</label>
                                                            <input type="date" class="form-control" name="from" value="<?= $this->input->get('from'); ?>">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label for="to">To</label>
                                                            <input type="date" class="form-control" name="to" value="<?= $this->input->get('to'); ?>">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <br>
                                                            <button type="submit" class="btn btn-sm btn-success">Search</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div><br>
                                        <?php } ?>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive" id="result">
                                                    <table class="table data-table" id="bootstrap-data-table-export">
                                                        <thead>
                                                            <tr>
                                                                <th>Sl No.</th>
                                                                <th>Member ID</th>
                                                                <th>Member Name</th>
                                                                <th>Mobile</th>
                                                                <th>Registration Date</th>
                                                                <th>Activation Date</th>
                                                                <?php if($this->session->role=="admin" && ($title=="Member List" || $title=='In-Active Member List')){ ?>
                                                                <th>Password</th>
                                                                <th>Action</th>
                                                                <?php 
                                                                    }
                                                                    elseif($this->session->role=="admin" && $title=='Active Member List'){
                                                                ?>
                                                                <th>Action</th>
                                                                <?php
                                                                    } 
                                                                ?>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                $members=$members;
                                                                if(is_array($members)){$i=0;
                                                                    foreach($members as $member){
                                                                        if($this->session->role=="admin" && $title=='Active Member List'){
                                                                            if(!empty($this->input->get('from'))){
                                                                                $from=$this->input->get('from');
                                                                            }
                                                                            if(!empty($this->input->get('to'))){
                                                                                $to=$this->input->get('to');
                                                                            }
                                                                            if(!empty($from) && !empty($to)){
                                                                                if($member['activation_date']<$from || $member['activation_date']>$to){
                                                                                   continue;
                                                                                }
                                                                            }
                                                                            if(!empty($from) && empty($to)){
                                                                                if($member['activation_date']!=$from){
                                                                                   continue;
                                                                                }
                                                                            }
                                                                            if(empty($from) && !empty($to)){
                                                                                if($member['activation_date']!=$to){
                                                                                   continue;
                                                                                }
                                                                            }
                                                                        }
                                                                        $i++;
                                                                        $status="<span class='text-danger'>Not activated</span>";
                                                                        if($member['status']==1){
                                                                            $status="<span class='text-success'>Activated</span>";
                                                                        }
                                                            ?>
                                                            <tr>
                                                                <td><?= $i; ?></td>
                                                                <td><?= $member['username']; ?></td>
                                                                <td><?= $member['name']; ?></td>
                                                                <td><?= $member['mobile']; ?></td>
                                                                <td><?= date('d-m-Y',strtotime($member['date'])); ?></td>
                                                                <td><?= ($member['activation_date']===NULL)?'-':date('d-m-Y',strtotime($member['activation_date'])); ?></td>
                                                                <?php if($this->session->role=="admin" && ($title=="Member List" || $title=='In-Active Member List')){ ?>
                                                                <td>
                                                                    <a href="#" onClick="$(this).hide();$(this).parent().find('span').removeClass('hidden');return false;" class="text-primary">View Password</a>
                                                                    <span class="hidden"><?php echo $member['password']; ?></span>
                                                                    <span class="hidden text-danger" onClick="$(this).parent().find('span').addClass('hidden');$(this).parent().find('a').show();"><i class="fa fa-times"></i></span>
                                                                </td>
                                                                <td>
                                                                    <a href="<?= base_url('members/edit/'.md5($member['regid'])); ?>" class="btn btn-xs btn-info mt-1">Edit</a> 
                                                                    <a href="<?= base_url('members/details/'.md5($member['regid'])); ?>" class="btn btn-xs btn-info mt-1">View</a> 
                                                                    <?php 
                                                                        if($this->session->role=="admin" && $title=='Member List'){ 
                                                                    ?>
                                                                    <a href="<?= base_url('login/userlogin/'.md5('username-'.$member['username'])); ?>" class="btn btn-xs btn-success mt-1">User Login</a> 
                                                                    <?php
                                                                        }
                                                                    ?>
                                                                    <?php /*?><a href="<?= base_url('login/userlogin/'.md5('username-'.$member['username'])); ?>" class="btn btn-xs btn-primary mt-1">Login</a><?php */?>
                                                                    <?php 
                                                                        if($this->session->role=="admin" && $title=='In-Active Member List'){ 
                                                                    ?>
                                                                    <?php /*?><a href="<?= base_url('members/delete/'.md5($member['regid'])); ?>" class="btn btn-xs btn-danger mt-1" onClick="return validateDelete();">Delete</a> <?php */?>
                                                                    <?php } ?>
                                                                    <?php /*?><form action="<?= base_url('members/activatemember/') ?>" method="post" onSubmit="return validate()">
                                                                        <input type="hidden" name="regid" value="<?= $member['regid']; ?>">
                                                                        <?php if($member['status']==0){ ?>
                                                                        <button type="submit" class="btn btn-xs btn-success mt-1" name="activatemember" value="Activate">Activate Member</button>
                                                                        <?php }elseif($member['user_status']==0){ ?>
                                                                        <input type="hidden" name="status" value="1">
                                                                        <button type="submit" class="btn btn-xs btn-success mt-1" name="changememberstatus" value="Un-block">Un-block Member</button>
                                                                        <?php }elseif($member['user_status']==1){ ?>
                                                                        <input type="hidden" name="status" value="0">
                                                                        <button type="submit" class="btn btn-xs btn-danger mt-1" name="changememberstatus" value="Un-block">Block Member</button>
                                                                        <?php } ?>
                                                                    </form><?php */?>
                                                                </td>
                                                                <?php 
                                                                    }
                                                                    elseif($this->session->role=="admin" && $title=='Active Member List'){
                                                                        $msg=($member['user_status']==0)?'Un-block':'Block';
                                                                ?>
                                                                <td>
                                                                    <form action="<?= base_url('members/activatemember/') ?>" method="post" onSubmit="return validate('<?= $msg ?>')">
                                                                        <input type="hidden" name="regid" value="<?= $member['regid']; ?>">
                                                                        <?php if($member['user_status']==0){ ?>
                                                                        <input type="hidden" name="status" value="1">
                                                                        <button type="submit" class="btn btn-xs btn-success mt-1" name="changememberstatus" value="Un-block">Un-block Member</button>
                                                                        <?php }elseif($member['user_status']==1){ ?>
                                                                        <input type="hidden" name="status" value="0">
                                                                        <button type="submit" class="btn btn-xs btn-danger mt-1" name="changememberstatus" value="Un-block">Block Member</button>
                                                                        <?php }elseif($member['user_status']==6){ ?>
                                                                        <span class="text-danger">Account Locked Permanently</span>
                                                                        <?php } ?>
                                                                    </form>
                                                                </td>
                                                                <?php
                                                                    }
                                                                ?>
                                                            </tr>
                                                            <?php
                                                                    }
                                                                }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>	
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <script>

                    var table;
                    $(document).ready(function(e) {
                        createDatatable();
                    });

                    function createDatatable(){
                        $('#status').html('');
                        table=$('#bootstrap-data-table-export').DataTable({
                                dom: 'Bflrtip',
                                buttons: [
                                    {
                                        extend: 'excel',
                                        className: 'btn btn-info'
                                    },
                                    {
                                        extend: 'pdf',
                                        className: 'btn btn-info'
                                    }
                                ]
                            });
                        table.columns('.select-filter').every(function(){
                            var that = this;
                            var pos=$('#status');
                            // Create the select list and search operation
                            var select = $('<select class="form-control" />').appendTo(pos).on('change',function(){
                                            that.search("^" + $(this).val() + "$", true, false, true).draw();
                                        });
                                select.append('<option value=".+">All</option>');
                            // Get the search data for the first column and add to the select list
                            this.cache( 'search' ).sort().unique().each(function(d){
                                    select.append($('<option value="'+d+'">'+d+'</option>') );
                            });
                        });
                        $('#member_id').on('keyup',function(){
                            table.columns(1).search( this.value ).draw();
                        });
                    }
                    function validate(text){
                        if(!confirm("Confirm "+text+" this Member?")){
                            return false;
                        }
                    }
                    function validateDelete(){
                        if(!confirm("Confirm Delete this Member?")){
                            return false;
                        }
                    }
                </script>
    
