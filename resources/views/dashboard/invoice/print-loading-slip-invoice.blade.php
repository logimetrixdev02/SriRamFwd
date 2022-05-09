<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	
	<script src="https://use.fontawesome.com/e2d16502eb.js"></script>
	<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
	<style>
		#page-wrap { width: 297mm; height:210mm; margin: 0 auto; }
		td {
			font-size:12px;
		}
		@media all {
			.page-break	{ display: none; }
		}
		@media print {
			.page-break	{ display: block; page-break-before: always; }
		}
		body{
			font-family: 'Droid Sans', sans-serif;
		}
	</style>
	<link href="https://fonts.googleapis.com/css?family=Droid+Sans" rel="stylesheet"> 
</head>
<body>
	<div id="page-wrap" >
		<table width="100%" style="border:1px solid;" cellspacing="0" cellpadding="0">
			<tr>
				<td style="height: 38px;border-bottom:1px solid;" valign="top">
					<p style="text-align:center;font-weight:bolder;margin-top: 15px; font-size: 15px;"><b>Tax Invoice</b></p>	
				</td>
			</tr>
			<tr>
				<td style="height:60px;" valign="top">
					<table width="100%" cellspacing="0" cellpadding="0">
						<tr>
							<td width="50%" style="height:60px; border-right:1px solid;">
								<p style="text-align:left; font-size: 15px; margin-left: 5px; font-weight:bold; margin-bottom:0px; margin-top:4px; ">{{ ucwords(strtolower($company->name)) }}  </p>
								<p style="text-align:left; margin-left: 5px; margin-top:4px; margin-bottom:0px;">{{$company->address1}}, {{$company->city}}</p>
								<p style="text-align:left; margin-left: 5px;  margin-bottom:0px; margin-top:4px;">GSTIN/UIN : {{$company->gst_no}}</p>
								<p style="text-align:left; margin-left: 5px;  margin-bottom:0px; margin-top:4px;">State Name : Uttar Pradesh, Code : 09</p>
							</td>
							<td width="50%" style="height:60px; padding-left:10px; margin: 0px;" valign="">
								<table cellspacing="0" cellpadding="0" width="100%" style="margin:0px; padding: 0px;">
									
									<tr>
										<td style="height:40px;  ">Invoice No. <br/>
											<strong>{{getModelById('InvoiceType',$invoice_data['invoice_type_id'])->invoice_type}} {{$invoice_data['invoice_number']}}</strong>
										</td>
										<td style="height:20px; ">e-Way Bill No. <br/>
											<strong>{{$invoice_data['eway_bill_no']}}</strong>
										</td>
										<td style="height:20px; margin-left: 5px;">Dated <br/>
											<strong>{{date('d-M-Y', strtotime($invoice_data['invoice_date']))}}</strong></td>
										</tr>
										<tr>
											<td style="height:20px; ">Delivery Note</td>
											<td style="height:20px; ;"> </td>
											<td style="height:20px; margin-left: 5px;">Mode/Terms of Payment </td>
											<td style="height:20px; "> </td>
										</tr>
										<tr>
											<td style="height:20px; ">Supplier's Ref.</td>
											<td style="height:20px;"> </td>
											<td style="height:20px; margin-left: 5px;">Other Reference(s) </td>
											<td style="height:20px; "> </td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="100%">
						<table width="100%" cellspacing="0" cellpadding="0">
							<tr>
								<td width="50%" style="border-top:1px solid; height:100px; border-right:1px solid; " valign="top">
									<!-- Invoice Details -->
									<table>
										<tr>
											<td width="50%" style="height:60px; ">
												<p style="text-align:left; margin-left: 5px; margin-top:4px; margin-bottom:0px;">Buyer</p>
												<p style="text-align:left; font-size: 15px; margin-left: 5px; font-weight:bold; margin-bottom:0px; margin-top:4px; ">
													{{getModelById('Retailer',$invoice_data['retailer_id'])->name}}

												</p>
												<p style="text-align:left; margin-left: 5px;  margin-bottom:0px; margin-top:4px;">{{getModelById('Retailer',$invoice_data['retailer_id'])->address1}} </p>
												<p style="text-align:left; margin-left: 5px;  margin-bottom:0px; margin-top:4px;">GSTIN/UIN : {{getModelById('Retailer',$invoice_data['retailer_id'])->gst_number}} </p>
											</td>
										</tr>
									</table>
									<!-- Invoice Details -->
								</td>
								<td width="50%" style="border-top:1px solid; padding-left:10px;margin: 0px;" valign="">
									<!-- Invoice Other Details -->
									<table cellspacing="0" cellpadding="0" width="100%" style="margin:0px; padding: 0px;">
										<tr>
											<td style="height:20px; ">Buyer Order No.</td>
											<td style="height:20px; "> </td>
											<td style="height:20px; margin-left: 5px;">Dated</td>
											<td style="height:20px; "> </td>
										</tr>
										<tr>
											<td style="height:20px; ">Despatch Document No.</td>
											<td style="height:20px; "> </td>
											<td style="height:20px; margin-left: 5px;">Delivery Note Date </td>
											<td style="height:20px; "> </td>
										</tr>
										<tr>
											<td style="height:20px; ">Despatched through</td>
											<td style="height:20px; ">{{$invoice_data['dispatched_through']}}</td>
											<td style="height:20px; margin-left: 5px;">Destination </td>
											<td style="height:20px; ">{{$invoice_data['destination']}}</td>
										</tr>
										<tr>
											<td style="height:20px; ">Terms of Delivery</td>
											<td style="height:20px;">{{$invoice_data['terms_of_delivery']}}</td>
										</tr>
									</table>
									<!-- Invoice Other Details -->
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="100%">
						<table cellspacing="0" cellpadding="0" width="100%">
							<tr>
								<td style="border-top:1px solid; border-right:1px solid; height:20px; text-align:center;" width="3%">
									Sr. No.
								</td>
								<td width="24%" style="border-top:1px solid; border-right:1px solid; text-align:center; height:20px;">
									Description of Goods
								</td>
								<td width="8%" style="border-top:1px solid; border-right:1px solid; text-align:center; height:20px;">
									HSN Code
								</td>
								<td width="8%" style="border-top:1px solid; border-right:1px solid; text-align:center; height:20px;">
									Quantity
								</td>
								<td width="8%" style="border-top:1px solid; border-right:1px solid; text-align:center; height:20px;">
									RATE
								</td>
								<td width="9%" style="border-top:1px solid; text-align:center; height:20px;">
									Amount
								</td>

							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="100%">
						<table cellspacing="0" cellpadding="0" width="100%">
							@if(count($product_id) > 0)
							@php $i = 0; 
							$final_show_amount = 0; 
							$products_total_amount = 0; 
							$total_cgst = 0;
							$total_sgst = 0;
							$total_quantity = 0;
							@endphp
							@foreach($product_id as $product)
							@php 
							$total_quantity = $total_quantity + $quantity[$i];
							$product_details = getModelById('Product',$product);
							$cgst_percentage = $product_details->cgst;
							$sgst_percentage = $product_details->sgst;

							$new_product_rate = $product_rate[$i] - $freight_discount[$i];

							$new_product_rate = $new_product_rate * 100 / (100 + $product_details->cgst + $product_details->sgst);

							$total_amount = $new_product_rate * $quantity[$i];
							$products_total_amount = $products_total_amount + $total_amount;
							$cgst_amount = ($cgst_percentage / 100) * $total_amount;
							$sgst_amount = ($sgst_percentage / 100) * $total_amount;
							$total_cgst = $total_cgst + $cgst_amount;
							$total_sgst = $total_sgst + $sgst_amount;
							$final_show_amount = $products_total_amount + $total_sgst + $total_cgst+$tcs;
							@endphp
							<tr>
								<td style="border-top:1px solid; border-right:1px solid; height:30px; text-align:center;" width="3%">{{ $i + 1}}
								</td>
								<td width="24%" style="border-top:1px solid; border-right:1px solid; text-align:left; height:30px; padding-left: 5px;">{{$product_details->name}}</td>
								<td width="8%" style="border-top:1px solid; border-right:1px solid; text-align:center; height:30px;">{{$product_details->hsn_code}}</td>
								<td width="8%" style="border-top:1px solid; border-right:1px solid; text-align:center; height:30px;">{{$quantity[$i]}} {{is_null(getModelById('Unit',$product_unit[$i]))?"":getModelById('Unit',$product_unit[$i])->unit}}s</td>
								<td width="8%" style="border-top:1px solid; border-right:1px solid; text-align:center; height:30px;">{{$new_product_rate}}</td>
								<td width="9%" style="border-top:1px solid; text-align:center; height:30px; padding-left: 12px;"><b>{{$total_amount}}</b></td>

							</tr>
							@php $i++; @endphp
							@endforeach
							@endif
							<tr>
								<td colspan="5" style="border-top:1px solid; border-right:1px solid; text-align:center; height:30px;"></td>
								<td style="border-top:1px solid; text-align:center; height:30px; padding-left: 12px;">{{$products_total_amount}}</td>
							</tr>
							<tr>
								<td colspan="5" style=" border-right:1px solid; text-align:right; height:30px; padding-right: 5px;">CGST</td>
								<td style="border-top:1px solid; text-align:center; height:30px; padding-left: 12px;"><b>{{$total_cgst}}</b></td>
							</tr>
							<tr>
								<td colspan="5" style=" border-right:1px solid; text-align:right; height:30px; padding-right: 5px;">SGST</td>
								<td style="border-top:1px solid; text-align:center; height:30px; padding-left: 12px;"><b>{{$total_sgst}}</b></td>
							</tr>
							@if($tcs != 0 )
							<tr>
								<td colspan="5" style=" border-right:1px solid; text-align:right; height:30px; padding-right: 5px;">TCS</td>
								<td style="border-top:1px solid; text-align:center; height:30px; padding-left: 12px;"><b>{{$tcs}}</b></td>
							</tr>
							@endif
							<tr>
								<td colspan="2" style="border-top:1px solid; border-right:1px solid; text-align:right; height:30px; padding-right: 5px;">Total</td>
								<td style="border-top:1px solid; border-right:1px solid; text-align:right; height:30px;"></td>
								<td style="border-top:1px solid;border-top:1px solid; border-right:1px solid; text-align:center; height:30px;">{{$total_quantity}} {{is_null(getModelById('Unit',$product_unit[0]))?"":getModelById('Unit',$product_unit[0])->unit}}s</td>
								<td style="border-top:1px solid; border-right:1px solid; text-align:right; height:30px;"></td>
								<td style="border-top:1px solid; text-align:center; height:30px; padding-left: 10px;"><i class="fa fa-rupee" style="float: left; font-size: 16px;"></i><b>{{$final_show_amount}}</b></td>
							</tr>
						</table>
					</td>
				</tr>

				<tr>
					<td width="100%">
						<table cellspacing="0" cellpadding="0" width="100%">
							<tr>
								<td style="border-top:1px solid; height:30px; padding: 5px; text-align:left;" width="3%" colspan="6">Amount Chargeable (in words)<br/>
									<strong>INR {{ucwords(getIndianCurrency($final_show_amount))}} Only
									</strong>
								</td>
								<td style="border-top:1px solid; height:30px; margin-right: 3px; text-align:right;" width="3%" colspan="6">E. & O.E &nbsp;&nbsp;</td>

							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="100%">
						<table cellspacing="0" cellpadding="0" width="100%">
							<tr rowspan="2">
								<td width="40%" style="border-top:1px solid; border-right:1px solid; text-align:center; height:20px;">HSN/SAC</td>
								<td width="15%" style="border-top:1px solid; border-right:1px solid; text-align:center; height:20px;">Taxable Value</td>
								<td colspan="2" width="15%" style="border-top:1px solid; border-right:1px solid; text-align:center; height:20px;">Central Tax</td>
								<td colspan="2" width="15%" style="border-top:1px solid; border-right:1px solid; text-align:center; height:20px;">State Tax</td>
								<td width="15%" style="border-top:1px solid; text-align:center; height:20px;">Total Tax Amount</td>
							</tr>
							<tr>
								<td width="40%" style="border-right:1px solid; text-align:center; height:20px;"></td>
								<td width="15%" style="border-right:1px solid; text-align:center; height:20px;"></td>
								<td width="7.5%" style="border-top:1px solid; border-right:1px solid; text-align:center; height:20px;">Rate</td>
								<td width="7.5%" style="border-top:1px solid; border-right:1px solid; text-align:center; height:20px;">Amount</td>
								<td width="7.5%" style="border-top:1px solid; border-right:1px solid; text-align:center; height:20px;">Rate</td>
								<td width="7.5%" style="border-top:1px solid; border-right:1px solid; text-align:center; height:20px;">Amount</td>
								<td width="15%" style="text-align:center; height:20px;"></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="100%">
						<table cellspacing="0" cellpadding="0" width="100%">
							@php
							$j = 0;
							$total_cgst_amount = 0;
							$total_sgst_amount = 0;
							$total_taxable_amount = 0;
							$total_tax = 0;
							@endphp
							@foreach($product_id as $product)
							@php
							$product_details = getModelById('Product',$product);
							
							$new_product_rate = $product_rate[$j] - $freight_discount[$j];

							
							$new_product_rate = $new_product_rate * 100 / (100 + $product_details->cgst + $product_details->sgst);

							$total_amount =  $new_product_rate * $quantity[$j];
							$total_taxable_amount = $total_taxable_amount + $total_amount;
							$cgst_percentage = $product_details->cgst;
							$sgst_percentage = $product_details->sgst;
							$cgst_amount = ($cgst_percentage / 100) * $total_amount;
							$total_cgst_amount = $total_cgst_amount + $cgst_amount;
							$sgst_amount = ($sgst_percentage / 100) * $total_amount;
							$total_sgst_amount = $total_sgst_amount + $sgst_amount;
							$total_tax_amount = $cgst_amount + $sgst_amount;
							$total_tax = $total_tax + $total_tax_amount;
							@endphp
							<tr>
								<td width="40%" style="border-top:1px solid; border-right:1px solid; text-align:center; height:20px;">{{$product_details->hsn_code}}</td>
								<td width="15%" style="border-top:1px solid; border-right:1px solid; text-align:center; height:20px;">{{$total_amount}}</td>
								<td width="7.5%" style="border-top:1px solid; border-right:1px solid; text-align:center; height:20px;">{{$product_details->cgst}} %</td>
								<td width="7.5%" style="border-top:1px solid; border-right:1px solid; text-align:center; height:20px;">{{$cgst_amount}}</td>
								<td width="7.5%" style="border-top:1px solid; border-right:1px solid; text-align:center; height:20px;">{{$product_details->sgst}} %</td>
								<td width="7.5%" style="border-top:1px solid; border-right:1px solid; text-align:center; height:20px;">{{$sgst_amount}}</td>
								<td width="15%" style="border-top:1px solid; text-align:center; height:20px;">{{$total_tax_amount}}</td>
							</tr>
							@php
							$j++;
							@endphp
							@endforeach
							<tr>
								<td width="40%" style="border-top:1px solid; border-right:1px solid; text-align:right; height:20px; padding-right: 10px;"><b>Total</b></td>
								<td width="15%" style="border-top:1px solid; border-right:1px solid; text-align:center; height:20px;"><b>{{$total_taxable_amount}}</b></td>
								<td width="7.5%" style="border-top:1px solid; border-right:1px solid; text-align:center; height:20px;"></td>
								<td width="7.5%" style="border-top:1px solid; border-right:1px solid; text-align:center; height:20px;"><b>{{$total_cgst_amount}}</b></td>
								<td width="7.5%" style="border-top:1px solid; border-right:1px solid; text-align:center; height:20px;"></td>
								<td width="7.5%" style="border-top:1px solid; border-right:1px solid; text-align:center; height:20px;"><b>{{$total_cgst_amount}}</b></td>
								<td width="15%" style="border-top:1px solid; text-align:center; height:20px;"><b>{{$total_tax}}</b></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="100%">
						<table cellspacing="0" cellpadding="0" width="100%">
							<tr>
								<td style="border-top:1px solid; height:30px; padding: 5px; text-align:left;" width="3%" colspan="6">Tax Amount (in words) :
									@if($total_tax)
									<br/>
									<strong>INR {{ ucwords(getIndianCurrency($total_tax)) }} Only
									</strong>
									@else
									<strong>NIL</strong>							
									@endif
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="100%">
						<table cellspacing="0" cellpadding="0" width="100%">
							<tr>
								<td colspan="4" width="100%" style="border-top:1px solid; padding: 5px; text-align:center;" ><b>Company's Bank Details</b>
								</td>
							</tr>
							<tr>
								<td  rowspan="3" colspan="2" style="border-top:1px solid; border-right:1px solid; padding: 5px; text-align:left;" width="50%" > Company's PAN Number : <b>{{$company->pan_number}}</b>
								</td>
								<td style="border-top:1px solid; padding: 5px; text-align:left;" width="15%" >Bank Name
								</td>
								<td style="border-top:1px solid; padding: 5px; text-align:left;" width="35%" >{{$company->bank_name}}
								</td>
							</tr>
							<tr>

								<td style=" padding: 5px; text-align:left;" width="15%" >Account Number
								</td>
								<td style=" padding: 5px; text-align:left;" width="35%" >{{$company->bank_account_number}}
								</td>
							</tr>
							<tr>

								<td style=" padding: 5px; text-align:left;" width="15%" >Branch & IFS Code
								</td>
								<td style=" padding: 5px; text-align:left;" width="35%" >{{$company->bank_branch_name}} & {{$company->bank_ifsc_code}}
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="100%">
						<table cellspacing="0" cellpadding="0" width="100%">
							<tr>
								<td style="border-top:1px solid; border-right:1px solid;  height:70px; padding-left:10px;" width="50%">Declaration : 
									<br />
									<br />
									We declare that this invoice shows the actual price of the goods described and that all particulars are true and correct.
								</td>
								<td width="50%" style="border-top:1px solid; text-align:right; height:70px; padding:10px 10px 10px 10px;">
									<b>for {{ strtoupper($company->name) }}</b>
									<br />
									<br />
									<br />
									<br />
									Authorised Signatory
								</td>
							</tr>
						</table> 
					</td>
				</tr>
			</table>
			<p style="text-align: center;">SUBJECT TO SITAPUR JURISDICTION</p>
			<p style="text-align: center;">This is a computer generated invoice</p>
		</div>
	</body>
	</html>
