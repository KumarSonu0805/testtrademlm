<!DOCTYPE html>
<html moznomarginboxes mozdisallowselectionprint>
    <head>
        <meta charset="UTF-8">
        <title>Invoice</title>
		<style>
        body{
            font-family: Calibri;
        }
        .BorderXnull{
            border-left: 0;
            border-right: 0;
        }
        table td,table th, table td p{
            font-size: 14px;
        }
        .BorderYnull{
            border-top: 0;
            border-bottom: 0;
        }
        .BorderLeft0{
            border-left: 0;
        }
        .BorderRight0{
            border-right: 0;
        }
        .BorderTop0{
            border-top: 0;
        }
        .BorderBottom0{
            border-bottom: 0;
        }
        .TaxInvoice{
            height: 30px;
        }
        .invoiceTtl{
            margin: 0;
            padding: 0;
            text-align: right;
            padding-right: 140px;
        }
        .recipient{
            text-align:center;
            text-transform: uppercase;
            font-style: italic;
            margin-top: 8px;
        }
        tr.BillerDetails{
            height: 200px;
        }
        .BillerDetails td img{
            width: 200px;
            padding:5px 0 0 5px;
        }
        td p{
            margin: 0;
            padding:0 10px;
        }
        .BillerDetails td p.title strong{
            color:#171717;
            text-transform: uppercase;
            font-size: 15px;
        }
        .BillerDetails td p strong{
            color:#171717;
        }
        .BuyerDetails table{
            border-top: 0;
            border-bottom:0;
        }
        tr.BuyerDetails{
            height: 110px;
        }
        .BuyerDetails td p.BuyerName strong{
            color:#171717;
            text-transform: uppercase;
        }
        .BuyerDetails td{
            border-top:0;
            border-bottom:0;
        }
        tr.Description{
            height: 450px;
        }
        tr.Description table th,tr.Description table td{
            padding:0 10px;
        }
        tr.Description table thead>tr,tr.Description table thead>th{
            height: 35px;
        }
        tr.Description table thead th{
            text-align: left;
        }
        tr.Description table tbody>tr,.tr.Description table tbody>td{
            height: 30px;
        }
        .SubTotalRow,.DicountRow,.TaxRow,.PayableAmountRow{
            font-weight: 600;
        }
        .SubTotal,.Discount,.Tax,.PayableAmount{
            text-align: right;
            padding-right: 35px !important;
        }
        .rounding{
          font-size: 12px;
          text-align: center;
        }
        tr.InWords p{
          padding: 0;
        }
        .BankDetails{
            height: 110px;
        }
        .Declaration{
            height: 120px;
        }
        .Jurisdiction{
            height: 30px;
        }
        .Jurisdiction p{
            font-size: 13px;
        }
        .Jurisdiction p:first-child{
            text-transform: uppercase;
            text-decoration: underline;
        }
    </style>
        <style type="text/css" media="print">
			@page {
					margin:0 10px;
					/*size:8.27in 11.69in ;
					/*height:3508 px;
					width:2480 px;
					/*size: auto;   auto is the initial value */
					/*margin:0;   this affects the margin in the printer settings 
			  		-webkit-print-color-adjust:exact;*/
			}
			@media print{
				table {page-break-inside: avoid;}
				#buttons{
						display:none;
				}
				#invoice{
					margin-top:20px;
  				}
			}
		</style>
    </head>
    
    <body>		
	<div id="invoice" style="width:600px;">
		<table width="100%" height="1050" border="0" align="center" cellspacing="0" cellpadding="0">
			<tr class="TaxInvoice">
				<td valign="middle">
					<table width="100%" height="30" border="0" align="center" cellspacing="0" cellpadding="0">
						<tr>
							<td width="50%"><h2 class="invoiceTtl">Tax Invoice</h2></td>
							<td width="25%"><p class="recipient">Original for recipient</p></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr class="BillerDetails">
				<td width="100%" valign="top">
					<table width="100%" height="200" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td width="50%" valign="top">
								<table width="100%" height="200" border="1" cellspacing="0" cellpadding="0">
									<tr>
										<td class="BorderRight0">
											<p class="title"><strong>OKLIFE CHOICE SERVICES PVT. LTD.</strong></p>
											<p>HNO-26, JHARIYA, JAMSHEDPUR</p>
											<p><strong>State:</strong> Jharkhand, <strong>Code:</strong> 20, <strong>Pin:</strong> 832301</p>
											<p><strong>Call:</strong> +91 9735770660</p>
											<p><strong>Email:</strong> support@just10.world</p>
											<p><strong>GSTIN: 20AADCO1158M1ZL</strong></p>
										</td>
									</tr>
								</table>
							</td>
                            <?php
                                $invoice_no=$activated['id'];
                                while(strlen($invoice_no)<4){
                                    $invoice_no='0'.$invoice_no;
                                }
                                $order_no=$epin['id'];
                                while(strlen($order_no)<4){
                                    $order_no='0'.$order_no;
                                }
                            ?>
							<td width="25%" valign="top" class="BorderXnull">
								<table width="100%" height="200" border="1" cellspacing="0" cellpadding="0">
									<tr><td valign="middle" class="BorderXnull"><p><strong>Invoice No:</strong></p><p><?= 'J10'.date('Y',strtotime($member['activation_date'])).$invoice_no; ?></p></td></tr>
									<tr><td valign="middle" class="BorderXnull"><p><strong>Supplier's Ref:</strong></p><p> - </p></td></tr>
									<tr><td valign="middle" class="BorderXnull"><p><strong>Buyers Order No:</strong></p><p><?= 'J10'.date('Y',strtotime($epin['added_on'])).$order_no; ?></p></td></tr>
								</table>
							</td>
							<td width="25%" valign="top">
								<table width="100%" height="200" border="1" cellspacing="0" cellpadding="0">
									<tr><td valign="middle" class="BorderLeft0"><p><strong>Dated:</strong></p><p><?= date('d-m-Y',strtotime($member['activation_date'])); ?></p></td></tr>
									<tr><td valign="middle" class="BorderLeft0"><p><strong>Payment Mode:</strong></p><p><?php //if($invoice['payment_mode']==''){echo " - ";}else{ echo ucfirst($invoice['payment_mode']); }?></p></td></tr>
									<tr><td valign="middle" class="BorderLeft0"><p><strong>Dispatch Mode:</strong></p><p><?php //echo ucfirst($invoice['transport_mode']); ?></p></td></tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr class="BuyerDetails">
				<td valign="top">
					<table width="100%" height="110" border="1" cellspacing="0" cellpadding="0">
						<tr>
							<td width="50%">
								<p class="BuyerName"><u>Billed To</u> : <strong><?= $member['name']; ?></strong></p>
								<p><?= $member['address'].', '.$member['district']; ?></p>
								<p><strong>State:</strong> <?= $member['state']; ?>, </p>
								<p><strong>Contact:</strong> <?= $member['mobile']; ?>, <strong>Email:</strong> <?= $member['email']; ?></p>
							</td>
							<td width="50%">
								<p class="BuyerName"><u>Billed To</u> : <strong><?= $member['name']; ?></strong></p>
								<p><?= $member['address'].', '.$member['district']; ?></p>
								<p><strong>State:</strong> <?= $member['state']; ?>, </p>
								<p><strong>Contact:</strong> <?= $member['mobile']; ?>, <strong>Email:</strong> <?= $member['email']; ?></p>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr class="Description">
				<td valign="top">
					<table width="100%" height="450" border="1" cellspacing="0" cellpadding="0">
						<thead>
							<tr height="30">
								<th align="center" width="5%" >S.No.</th>
								<th align="center" >Description</th>
								<th align="center" >HSN/SAC</th>
								<th align="center">Qty.</th>
								<th align="center" >Rate(₹)</th>
								<th align="center" >Amount(₹)</th>
							</tr>
						</thead>
						<tbody>
                            <?php
                                $amount=$package['amount'];
                                if($epin['deduct']==1){
                                    $amount-=50;
                                }
                                $gst=18;
                                $taxable=round(($amount*100)/118);
                                $gstvalue=0.18*$taxable;
                                $temp=$taxable+$gstvalue;
                                $roundoff=$amount-$temp;
                                
                            ?>
							<tr height="30">
								<td align="center">1</td>
								<td style="padding:0px 5px;">On-line Content Service</td>
								<td align="center">99843</td>
								<td align="center">1</td>
								<td align="center"><?php echo $this->amount->toDecimal($taxable); ?></td>
								<td align="right"><?php echo $this->amount->toDecimal($taxable); ?></td>
							</tr>
							<tr id="blank">
								<td></td><td></td><td></td>
								<td></td><td></td><td></td>
							</tr>
							<tr>
								<td colspan="5" class="SubTotal"> <b>CGST TAX</b> </td>
								<td align="right"><?php echo $this->amount->toDecimal($gstvalue/2);?></td>
							</tr>
							<tr>
								<td colspan="5" class="SubTotal"> <b>SGST TAX</b> </td>
								<td align="right"><?php echo $this->amount->toDecimal($gstvalue/2);?></td>
							</tr>
							<tr>
								<td colspan="5" class="SubTotal"><b>ROUNDING OFF</b> </td>
								<td align="right"><?php echo $this->amount->twoDigits($roundoff); ?></td>
                            </tr>
							<tr class="PayableAmountRow">
								<td colspan="5" class="PayableAmount">Net Payable Amount</td>
								<td align="right"><?php echo $this->amount->toDecimal($amount); ?></td>
							</tr>
							<tr >
								<td colspan="6" style="text-align: center;">
								<p>Net Payable Amount (In Words)</p>
								<p style="font-size: 18px; "><strong><span>INR: </span><?php echo $this->amount->to_words($amount)." Only";?></strong></p>
								</td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
			<tr class="BankDetails">
				<td valign="top" width="100%">
					<table width="100%" height="110" border="1" cellspacing="0" cellpadding="0" class="BorderYnull">
						<tr>
							<td valign="top" class="BorderBottom0">
								<p><u>Our Bank Details </u></p>
                                <p><strong>Bank : </strong>State bank of India</p>
                                <p><strong>Branch : </strong>CHAKULIA</p>
                                <p><strong>Account No</strong> : 39807204400</p>
                                <p><strong>IFSC</strong> : SBIN0006352</p>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr class="Declaration">
				<td valign="top" width="100%">
					<table width="100%" height="120" border="1" cellspacing="0" cellpadding="0" class="BorderTop0">
						<tr>
							<td valign="middle" width="60%" class="BorderTop0">
								<p><strong><u>Declaration</u></strong></p>
								<ol style="width:350px; font-weight:300; font-size:13px;">
									<li>All Disputes are subject to Ranchi Jurisdiction only.</li>
									<li>Goods once sold will not be exchanged or returned.</li>
									<li>Any kind of goods returned by customers, 18% of goods amount or GST amount will be deducted.</li>
									<li>Our responsibility ceases once the consignment is dispatched from the shop.</li>
								</ol>
							</td>
							<td width="40%" align="right" class="BorderLeft0">
								<p><strong><?php echo "For ".PROJECT_NAME; ?></strong></p>
								<br><br><br><br>
								<p><strong>Authorized Signature</strong></p>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr class="Jurisdiction">
				<td align="center">
					<p><strong>Subject to Ranchi Jurisdiction</strong></p>
					<p>This is a Computer Generated Invoice</p>
				</td>
			</tr>
			<tr class="Jurisdiction" id="buttons">
				<td align="center">
					<button type="button" class="btn btn-danger" onclick="window.print();" 
								style="background-color:#F70004; height:30px; width:70px; border-radius:5px; color:#FFFFFF; font-size:14px;" >Print</button>
					<button type="button" onclick="closeThis();" class="btn btn-default"
								style="background-color:#F70004; height:30px; width:70px; border-radius:5px; color:#FFFFFF; font-size:14px;">Close</button>
						
				</td>
			</tr>
		</table>
    </div>
        <script language="javascript">
        	function closeThis(){
					window.location="<?= $this->session->role=='admin'?base_url('members/activedownline/'):base_url('profile/'); ?>";
			}
        </script>
    </body>
</html>