<?php
    $total_income=$total_payment=0;
    $income_amount=array_column($incomes,"amount");
    if(!empty($income_amount)){
        $total_income=array_sum($income_amount);
    }
    $payment_amount=array_column($payments,"payable");
    if(!empty($payment_amount)){
        $total_payment=array_sum($payment_amount);
    }
?>
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
                                        <div class="row">
                                            <div class="col-lg-3 col-6">
                                                <a href="#">            
                                                    <div class="card bg-success">
                                                        <div class="card-body">
                                                            <div class="inner">
                                                                <h3><?= $this->amount->toDecimal($total_income); ?></h3>
                                                                <p>Total Income</p>
                                                            </div>
                                                            <div class="icon">
                                                                <i class="fa fa-money"></i>
                                                            </div>
                                                            <!-- <a href="#" class="card-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="col-lg-3 col-6">
                                                <a href="#">            
                                                    <div class="card bg-danger">
                                                        <div class="card-body">
                                                            <div class="inner">
                                                                <h3><?= $this->amount->toDecimal($total_payment); ?></h3>
                                                                <p>Total Payment</p>
                                                            </div>
                                                            <div class="icon">
                                                                <i class="fa fa-money"></i>
                                                            </div>
                                                            <!-- <a href="#" class="card-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <ul class="nav nav-tabs mrg25B">
                                                    <li class="active"><a href="#income" class="nav-btn btn btn-success">Income</a></li>
                                                    <li><a href="#payments" class="nav-btn btn">Payments</a></li>
                                                </ul>
                                            </div>
                                        </div><br>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="tab-div" id="income">
                                                    <div class="table-responsive">
                                                        <table class="table data-table" id="income-table">
                                                            <thead>
                                                                <tr>
                                                                    <th>Sl No.</th>
                                                                    <th>Date</th>
                                                                    <th>Type</th>
                                                                    <th>Member ID</th>
                                                                    <th>Member Name</th>
                                                                    <th>Sponsor ID</th>
                                                                    <th>Sponsor Name</th>
                                                                    <th>Package</th>
                                                                    <th>E-Pins</th>
                                                                    <th>Amount</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                    $incomes=$incomes;
                                                                    if(is_array($incomes)){$i=0;
                                                                        foreach($incomes as $income){
                                                                            $i++;
                                                                ?>
                                                                <tr>
                                                                    <td><?= $i; ?></td>
                                                                    <td><?= date('d-m-Y',strtotime($income['approve_date'])); ?></td>
                                                                    <td><?= $income['type']; ?></td>
                                                                    <td><?= $income['username']; ?></td>
                                                                    <td><?= $income['name']; ?></td>
                                                                    <td><?= $income['susername']; ?></td>
                                                                    <td><?= $income['sname']; ?></td>
                                                                    <td><?= $income['package']; ?></td>
                                                                    <td><?= $income['quantity']; ?></td>
                                                                    <td><?= $this->amount->toDecimal($income['amount']); ?></td>
                                                                </tr>
                                                                <?php
                                                                        }
                                                                    }
                                                                ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="tab-div hidden" id="payments">
                                                    <div class="table-responsive">
                                                        <table class="table data-table" id="payment-table">
                                                            <thead>
                                                                <tr>
                                                                    <th>Sl No.</th>
                                                                    <th>Date</th>
                                                                    <th>Member ID</th>
                                                                    <th>Member Name</th>
                                                                    <th>Amount</th>
                                                                    <th>TDS</th>
                                                                    <th>Network Fees</th>
                                                                    <th>Paid</th>
                                                                    <th>Paid Amount (INR)</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                    $payments=$payments;
                                                                    if(is_array($payments)){$i=0;
                                                                        foreach($payments as $payment){
                                                                            $i++;
                                                                ?>
                                                                <tr>
                                                                    <td><?= $i; ?></td>
                                                                    <td><?= date('d-m-Y',strtotime($payment['approve_date'])); ?></td>
                                                                    <td><?= $payment['username']; ?></td>
                                                                    <td><?= $payment['name']; ?></td>
                                                                    <td><?= $this->amount->toDecimal($payment['amount']); ?></td>
                                                                    <td><?= $this->amount->toDecimal($payment['tds']); ?></td>
                                                                    <td><?= $this->amount->toDecimal($payment['fees']); ?></td>
                                                                    <td><?= $this->amount->toDecimal($payment['payable']); ?></td>
                                                                    <td><?= $this->amount->toDecimal($payment['payable']*CONV_RATE); ?></td>
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
                    </div>
                </section>
                <script>
                    $(document).ready(function(){
                        $('body').on('click','.nav-btn',function(e){
                            e.preventDefault();
                            let ref=$(this).attr('href');
                            /*$('.tab-div').addClass('hidden');
                            $(ref).removeClass('hidden');
                            $('.nav-btn').parent().removeClass('active');
                            $(this).parent().addClass('active');*/
                            $('.tab-div').addClass('hidden');
                            $(ref).removeClass('hidden');
                            $('.nav-btn').removeClass('btn-success');
                            $(this).addClass('btn-success');
                        });
                        $('#income-table').DataTable({
                            dom: 'Bflrtip',
                            buttons: [
                                {
                                    extend: 'excel',
                                    className: 'btn btn-info',
                                    messageTop: 'Incomes'
                                },
                                {
                                    extend: 'pdf',
                                    className: 'btn btn-info',
                                    messageTop: 'Incomes'
                                }
                            ]
                        });
                        $('#payment-table').DataTable({
                            dom: 'Bflrtip',
                            buttons: [
                                {
                                    extend: 'excel',
                                    className: 'btn btn-info',
                                    messageTop: 'Payments'
                                },
                                {
                                    extend: 'pdf',
                                    className: 'btn btn-info',
                                    messageTop: 'Payments'
                                }
                            ]
                        });
                    });
                    
                </script>
