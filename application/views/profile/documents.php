<?php 
	if($acc_details['pan']!=''){
		$pansrc=base_url($acc_details['pan']);
		$pan='<button type="button" class="btn btn-sm btn-info mb-1 view" data-toggle="modal" data-target="#mediumModal" value="'.$pansrc.'">View PAN Card</button>';
	}
	else{
		$pan="Not Uploaded";
	}
	if($acc_details['cheque']!=''){
		$chequesrc=base_url($acc_details['cheque']);
		$cheque='<button type="button" class="btn btn-sm btn-info mb-1 view" data-toggle="modal" data-target="#mediumModal" value="'.$chequesrc.'">View Cheque</button>';
	}
	else{
		$cheque="Not Uploaded";
	}
	if($acc_details['kyc']==0){ $status="<span class='text-danger'>KYC Not Done</span>"; }
	elseif($acc_details['kyc']==1){ $status="<span class='text-success'>KYC Approved</span>"; }
	else{ $status="<span class='text-warning'>KYC Pending</span>"; }
?>
                      
<div class="content">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                	<div class="card-body card-block">
                    	<div class="row">
                        	<div class="col-md-12">
                            	<table class="table">
                                	<tr>
                                    	<th>Sl No.</th>
                                    	<th>PAN Card</th>
                                    	<th>Cancelled Cheque</th>
                                        <th>Confirmation</th>
                                    </tr>
                                    <tr>
                                    	<td>1</td>
                                    	<td><?php echo $pan; ?></td>
                                    	<td><?php echo $cheque; ?></td>
                                    	<td><?php echo $status; ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>	
<div class="modal fade" id="mediumModal" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediumModalLabel">Medium Modal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                	<span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img src="" alt="" id="img-popup" class="img-fluid">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
	$('.view').click(function(){
		var src=$(this).val();
		$('#img-popup').attr('src',src);
	});
</script>
    
    	
