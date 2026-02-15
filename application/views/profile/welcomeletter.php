
<style>
    td{
        text-align: justify;
    }
    .table{
        height: 900px;
    }
    .letter-details{
        list-style:none;
    }
    .letter-details label{
        font-weight:600;
        width: 200px;
    }
    p{
        text-align: justify;
    }

</style>   
<style type="text/css" media="print">
    .blank{
        height: 308px;
    }
    #print-btn{
        display:none;
    }
    p{
        text-align: justify;
    }
</style>
<?php
if(!empty($print) && $print===true){
?>
<script>
    window.onload=function(){window.print();}
</script>
<?php
    }
?>
<div class="container">
    <div class="content">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body card-block">
                            <div class="row">
                                <div class="col-md-12" id="welcome-letter">
                                    <table border="1" class="table" align="center" cellpadding="2" cellspacing="0">
                                        <tr>
                                            <td class="" valign="middle" height="10" align="center" width="120" style="border-right: 0;">
                                                <img src="<?= file_url("assets/images/vishwashanti-foundation.png") ?>" alt="" height="120">
                                            </td>
                                            <td align="right" style="border-left: 0;">
                                                <h5 style="text-align: right;"><?= PROJECT_NAME ?></h5>
                                                <p style="text-align: right;margin:0;">C/O -Mrina Singh, Behind Sainik School, Bandha,</p>
                                                <p style="text-align: right;margin:0;">Baijnathpur, Deoghar Court, Deoghar - 814112, Jharkhand</p>
                                                <p style="text-align: right;margin:0;">Email: vishwashantianandfoundation@gmail.com</p>
                                                <p style="text-align: right;margin:0;">Mobile:+91-7903017553/9006319498</p>
                                                <p style="text-align: right;margin:0;">80G Reg. No.:AAJCV2781KF20231</p>
                                                <p style="text-align: right;margin:0;">CIN No.:U86909JH2023NPL020155</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <ul style="padding: 0; list-style: none;">
                                                    <li>To,</li>
                                                    <li><strong><?= $member['name'].' ('.$user['username'].')'; ?></strong></li>
                                                    <li>C/O- <?= $member['father']; ?></li>
                                                    <li><?= $member['address']; ?></li>
                                                    <li><?= $member['district'].','.$member['state']; ?></li>
                                                    <li>Mobile No.- <?= $member['mobile']; ?></li>
                                                </ul>
                                                <p class="text-dark">Sub: Membership Approval</p>
                                                <p class="text-dark">Dear <?= $member['name']; ?>,</p>
                                                <p class="text-dark">
                                                    Greetings from Vishwashanti Anand Aashram Foundation.
                                                    We are delighted to inform your membership has been approved. You are now a member of Vishwashanti Anand Aashram Foundation.</p>
                                                <p class="text-dark">We are glad to have you with us, here are  your account details:</p>
                                                <ul class="letter-details" style="padding:5px;list-style:none;">
                                                    <li><label>Member ID </label> : <?= $user['username']; ?></li>
                                                    <li><label>Date of joining</label> : <?= date('d-m-Y',strtotime($member['date'])); ?></li>
                                                    <li><label>Membership Level </label> : <?= $package['package']; ?></li>
                                                    <li><label>Donation Amount </label> : ₹ <?= $package['amount']; ?></li>
                                                    <li><label>UP ID </label> : <?= $member['susername'].' - '.$member['sname']; ?></li>
                                                </ul>
                                                <p class="text-dark">You can log in with the following username and password.</p>
                                                <ul class="letter-details" style="padding:5px;list-style:none;">
                                                    <li><label>Username</label> : <?= $user['username']; ?></li>
                                                    <li><label>Password</label> : <?= $user['vp']; ?></li>
                                                </ul>
                                                <p class="text-dark">We expect your active participation to achieve the Foundation’s goals.</p>
                                                <p class="text-dark">Thanking you</p>
                                                <p class="text-dark">Sincerely yours,</p>
                                                <p class="text-dark"><?= PROJECT_NAME; ?></p>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                                <div class="col-md-12 text-center"><button type="button" class="btn btn-sm btn-danger" onClick="printFunc()"><i class="fa fa-print"></i> Print</button></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>	
</div>	
    
<script>
	function printFunc() {
		var divToPrint = document.getElementById('welcome-letter');
		var htmlToPrint = '' +
			'<style type="text/css">' +
			'table{ width:595px;height:842px; }'+
			'h5{text-align:center;font-size: 15px;font-weight: 700; margin:0; padding:0; margin-bottom: 10px;}'+
			'p{font-size: 14px;margin-bottom: 5px;}' +
			'div{float:left;}' +
			'</style>';
		htmlToPrint += divToPrint.outerHTML;
		newWin = window.open("");
		newWin.document.write(htmlToPrint);
		newWin.print();
		newWin.close();
    }
</script>
    
    	
    	