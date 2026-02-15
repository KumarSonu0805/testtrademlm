
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
                                            <div class="col-md-12" id="idcard">
                                                <table class="table" align="center" cellpadding="5" cellspacing="0">
                                                    <tr>
                                                        <th class="text-center" valign="middle">
                                                        <img src="<?= file_url("assets/images/vishwashanti-foundation.png") ?>" alt="" height="50" style="float: left;">
                                                            <h4 class="mb-2"><?= PROJECT_NAME ?></h4>
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <td valign="middle" colspan="2">
                                                            <h5>Identity Card</h5>
                                                            <div class="pull-left">
                                                                <p>Member ID : <?= $user['username']; ?></p>
                                                                <p>Name : <?= $user['name']; ?></p>
                                                                <p>UP ID : <?= $member['susername']; ?></p>
                                                                <p>Package : <?= $package['package']; ?> </p>
                                                                <p>Mobile No : <?= $user['mobile']; ?> </p>
                                                                <p>Address : <?= $member['address'].', '.$member['district']; ?></p>
                                                            </div>
                                                            <img src="<?php if($member['photo']!=''){echo file_url($member['photo']);}else{echo file_url('assets/images/avatar.png');} ?>" alt="<?= $user['name']; ?>" height="100" class="pull-right">
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
                </section>
<script>
	function printFunc() {
		var divToPrint = document.getElementById('idcard');
		var htmlToPrint = '' +
			'<style type="text/css">' +
			'table{ width:350px;height:450px; }'+
			'th,td{border:1px solid #000; vertical-align:middle;}'+
			'h5{text-align:center;font-size: 15px;font-weight: 700; margin:0; padding:0; margin-bottom: 10px;}'+
			'p{font-size: 14px;margin-bottom: 5px;}' +
			'div{float:left;}' +
			'img{float:right;}' +
			'</style>';
		htmlToPrint += divToPrint.outerHTML;
		newWin = window.open("");
		newWin.document.write(htmlToPrint);
		newWin.print();
		newWin.close();
    }
</script>
    
    	
