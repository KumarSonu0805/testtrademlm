
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
                                            <div class="col-md-5">
                                                <?php echo form_open('wallet/transferamount', 'id="myform"'); ?>
                                                    <div class="form-group">
                                                        <?php
                                                            echo create_form_input("text","","Member ID",true,'',array("id"=>"username")); 
                                                            echo create_form_input("hidden","reg_to","",false,'0',array("id"=>"reg_to")); 
                                                        ?>
                                                        <div id="name" class="lead"></div>
                                                    </div>
                                                    <div class="form-group">
                                                        <?php
                                                            echo create_form_input('text','','Available Balance',false,$wallet['actualwallet'],array("id"=>"available","readonly"=>"true"));
                                                        ?>
                                                    </div>
                                                    <div class="form-group">
                                                        <?php
                                                            echo create_form_input('number','amount','Amount',true,'',array("id"=>"amount","autocomplete"=>"off"));
                                                            echo create_form_input("hidden","reg_from","",false,$user['id']); 
                                                        ?>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12" id="btn-div">
                                                            <input type="submit" name="transferamount" value="Transfer Amount" id="validate-btn" class="btn btn-sm btn-success">
                                                        </div>
                                                    </div>
                                                <?php echo form_close(); ?>

                                            </div>
                                            <div class="col-md-7">
                                                <div class="table-responsive" id="result">
                                                    <table class="table table-bordered data-table" id="bootstrap-data-table-export">
                                                        <thead>
                                                            <tr>
                                                                <th>Sl No.</th>
                                                                <th>Date Time</th>
                                                                <th>Receiver's Id</th>
                                                                <th>Receiver's Name</th>
                                                                <th>Amount</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                $transfers=$transfers;
                                                                if(is_array($transfers)){$i=0;
                                                                    foreach($transfers as $transfer){
                                                                        $i++;
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $i; ?></td>
                                                                <td><?php echo date('d-m-Y H:i A',strtotime($transfer['added_on'])); ?></td>
                                                                <td><?php echo $transfer['to_id']; ?></td>
                                                                <td><?php echo $transfer['to_name']; ?></td>
                                                                <td><?php echo $this->amount->toDecimal($transfer['amount']); ?></td>
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
                        $('#username').keyup(function(){
                            $('#name').html('');
                            $('#reg_to').val('');
                            $('#submit-btn').attr('disabled',true);
                        });
                        $('#username').blur(function(){
                            var username=$(this).val();
                            var status='all';
                            if(username!='' && username!='admin'){
                            
                                status="not self,activated";
                                $.ajax({
                                    type:"POST",
                                    url:"<?php echo base_url("members/getmember/"); ?>",
                                    data:{username:username,status:status},
                                    success: function(data){
                                        data=JSON.parse(data);
                                        if(data['regid']!=0){
                                            $('#name').html("<span class='text-success'>"+data['name']+"</span>");
                                            $('#reg_to').val(data['regid']);
                                            $('#submit-btn').prop("disabled",false);
                                        }
                                        else{$('#name').html("<span class='text-danger'>"+data['name']+"</span>");}
                                    }
                                });
                            }
                        });
                        $('#amount').keyup(function(){
                            var quantity=Number($(this).val());
                            var avl=Number($('#available').val());
                            if(isNaN(avl)){avl=0;}
                            if(quantity>avl){
                                alert("Balance Not available!");
                                $('#amount').val('');
                            }
                        });
                        $('body').on('change','#package_id',function(){
                            var type='p2pwallet';
                            var package_id=$(this).val();
                            $.ajax({
                                type:"post",
                                url:"<?= base_url('wallet/getwalletbalance'); ?>",
                                data:{type:type,package_id:package_id},
                                success:function(data){
                                    var id="#"+type;
                                    $('#available').val(data);
                                }
                            });
                        })
                        $('#submit-btn').click(function(){
                            if($('#amount').get(0).checkValidity()){
                                $('#username,#amount').attr('readonly',true);
                            }
                            else{
                                $('#amount').get(0).reportValidity();
                                return false; 
                            }
                            $('#timer').removeClass('hidden');
                            $('#resend').addClass('hidden');
                            var username=$('#username').val();
                            var amount=$('#amount').val();
                            $.ajax({
                                type:"POST",
                                url:"<?php echo base_url("wallet/generateotp/"); ?>",
                                data:{username:username,amount:amount,generateotp:'generateotp'},
                                beforeSend: function(){
                                    $('#btn-div').find('img').removeClass("hidden");
                                    $('#btn-div').children().not('img').addClass('hidden');
                                },
                                success: function(data){
                                    $('#btn-div').find('img').addClass("hidden");
                                    $('#otp-row,#validate-btn,#timer').removeClass("hidden");
                                    timer=setInterval(startTimer, 1000);
                                }
                            });
                        });
                        $('#resend').click(function(){
                            $('#timer').removeClass('hidden');
                            $('#resend').addClass('hidden');
                            var username=$('#username').val();
                            var amount=$('#amount').val();
                            $.ajax({
                                type:"POST",
                                url:"<?php echo base_url("wallet/resendotp/"); ?>",
                                data:{username:username,amount:amount,resendotp:'resendotp'},
                                beforeSend: function(){
                                    $('#btn-div').find('img').removeClass("hidden");
                                    $('#btn-div').children().not('img').addClass('hidden');
                                },
                                success: function(data){
                                    $('#btn-div').find('img').addClass("hidden");
                                    $('#otp-row,#validate-btn,#timer').removeClass("hidden");
                                    timer=setInterval(startTimer, 1000);
                                }
                            });
                        });
                        createDatatable();
                    });

                    function createDatatable(){
                        $('#status').html('');
                        table=$('#bootstrap-data-table-export').DataTable();
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
                        $('#result').find('table').parent().addClass('table-responsive');
                    }
                    var remaining=30;
                    function startTimer(){
                        remaining--;
                        var msg="Resend Verification Code in "+remaining+" seconds.";
                        $('#timer').text(msg);
                        if(remaining==0){
                            remaining=30;
                            msg="Resend OTP in "+remaining+" seconds.";
                            $('#timer').text(msg);
                            $('#resend').removeClass('hidden');
                            $('#timer').addClass('hidden');
                            clearInterval(timer);
                        }
                    }
                </script>
