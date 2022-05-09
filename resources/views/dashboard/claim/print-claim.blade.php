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

	*
	{
		margin:0px;
		padding:0px;
	}
	td
	{
		padding:2px;
	}
</style>
<link href="https://fonts.googleapis.com/css?family=Droid+Sans" rel="stylesheet"> 
</head>
<body>
	<div id="page-wrap" >
		<table width="100%" style="border:1px solid;" cellspacing="0" cellpadding="0" >
			<tr>
				<td style="height: 38px;border-bottom:1px solid;" valign="top" colspan="2">
					<p style="text-align:center;font-weight:bolder;margin-top: 15px; font-size: 15px;"><b>Tax Invoice</b></p>	
				</td>
			</tr>
			<tr>
				<td style="height:60px;" valign="top" colspan="2" >
					<p style="text-align:left; font-size: 15px; margin-left: 5px; font-weight:bold; margin-bottom:0px; margin-top:4px; "><u>Agent Details</u></p>
					<table  width="100%">
						<tr>
							<td><b>1.GSTIN</b></td>
							<td>{{$company->gst_no}}</td>
						</tr>
						<tr>
							<td><b>2.Name</b></td>
							<td>{{$company->name}}</td>
						</tr>
						<tr>
							<td><b>3.Address</b></td>
							<td>{{$company->address1}}</td>
						</tr>
						<tr>
							<td><b>4.Date of Invoice</b></td>
							<td>{{$company->created_at}}</td>
						</tr>
						<tr>
							<td><b>5.Serial No.Invoice</b></td>
							<td>2</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td style="border-top:1px solid">
					<p style="text-align:left; font-size: 15px; margin-left: 5px; font-weight:bold; margin-bottom:0px; margin-top:4px; "><u>Details of Reciever(Bill to) {{$product_company->abbreviation}}</u></p>
					<table  width="50%">
						<tr>
							<td><b>Name</b></td>
							<td>{{$product_company->name}}</td>
						</tr>
						<tr>
							<td><b>Address</b></td>
							<td>{{$product_company->address}}</td>
						</tr>
						<tr>
							<td><b>State</b></td>
							<td>{{$product_company->state}}</td>
						</tr>
						<tr>
							<td><b>State Code</b></td>
							<td>{{$product_company->abbreviation}}</td>
						</tr>
						<tr>
							<td><b>GSTIN/Unique Code</b></td>
							<td>{{$product_company->gst_no}}</td>
						</tr>
						<tr>
							<td><b>Month</b></td>
							<td>{{$product_company->created_at}}</td>
						</tr>
						<tr>
							<td><b>Product</b></td>
							<td>{{$product->product_name}}</td>
						</tr>
					</table>
				</td>
				<td style="border-top:1px solid">
					<p style="text-align:left; font-size: 15px; margin-left: 5px; font-weight:bold; margin-bottom:0px; margin-top:4px; "><u>Details of Shipped(Bill to) {{$product_company->abbreviation}}</u></p>
					<table  width="50%">
						<tr>
							<td>Name</td>
							<td>{{$product_company->name}}</td>
						</tr>
						<tr>
							<td>Address</td>
							<td>{{$product_company->address}}</td>
						</tr>
						<tr>
							<td>State</td>
							<td>{{$product_company->state}}</td>
						</tr>
						<tr>
							<td>State Code</td>
							<td>{{$product_company->abbreviation}}</td>
						</tr>
						<tr>
							<td>GSTIN/Unique Code</td>
							<td>{{$product_company->gst_no}}</td>
						</tr>
						<tr>
							<td>Month</td>
							<td>{{$product_company->created_at}}</td>
						</tr>
						<tr>
							<td>Product</td>
							<td>{{$product->product_name}}</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="4">
					<table style="text-align: center;" width="100%" border="1" cellspacing="0" cellpadding="0">
						<tr>
							<td>S.no</td>
							<td>Description of </br>goods</td>
							<td>HSN/SAC Code</td>
							<td>Qty</td>
							<td>Unit</td>
							<td>Rate </br>(per item)</td>
							<td>Total</td>
							<td>Dicount</td>
							<td>Taxable </br>@ Value</td>
							<td>
								<table  style="text-align: center;border: none;" width="100%" border="1" cellspacing="0" cellpadding="0" >
									<tr><td colspan="2">CGST</td></tr>
									<tr>
										<td>Rate</td>
										<td>Amount</td>
									</tr>
								</table>
							</td>
							<td><table  style="text-align: center;border: none;" width="100%" border="1" cellspacing="0" cellpadding="0">
								<tr><td colspan="2">SGST</td></tr>
								<tr>
									<td>Rate</td>
									<td>Amount</td>
								</tr>
							</table></td>
							<td><table  style="text-align: center;border: none;" width="100%" border="1" cellspacing="0" cellpadding="0">
								<tr><td colspan="2">IGST</td></tr>
								<tr>
									<td>Rate</td>
									<td>Amount</td>
								</tr>
							</table></td>
						</tr>
						@foreach($claims as $claim)
						<tr>
							<td>{{$claim->id}}</td>
							<td>{{$claim->claim_head}}</td>
							<td>{{$claim->rate}}</td>
							<td></td>
							<td>MT</td>
							<td>{{$claim->rate}}</td>
							<td>-------</td>
							<td>-------</td>
							<td>-------</td>
							<td><table style="text-align: center;border: none;margin: 0px;" width="100%"  cellspacing="0" cellpadding="0">
								<tr>
									<td style="border-right:1px solid">------</td>
									<td>-------</td>
								</tr>
							</table></td>
							<td><table style="text-align: center;border: none;margin: 0px;" width="100%"  cellspacing="0" cellpadding="0">
								<tr>
									<td style="border-right:1px solid">------</td>
									<td>-------</td>
								</tr>
							</table></td>
							<td><table style="text-align: center;border: none;margin: 0px;" width="100%"  cellspacing="0" cellpadding="0">
								<tr>
									<td style="border-right:1px solid">------</td>
									<td>-------</td>
								</tr>
							</table></td>
						</tr>
						@endforeach
						<tr><td colspan="13"></td></tr>
						<tr>
							<td></td>
							<td></td>
							<td>Total</td>
							<td>-------</td>
							<td></td>
							<td>---------</td>
							<td></td>
							<td>----------</td>
							<td></td>
							<td><table style="text-align: center;border: none;margin: 0px;" width="100%"  cellspacing="0" cellpadding="0">
								<tr>
									<td style="border-right:1px solid">------</td>
									<td>-------</td>
								</tr>
							</table></td>
							<td><table style="text-align: center;border: none;margin: 0px;" width="100%"  cellspacing="0" cellpadding="0">
								<tr>
									<td  style="border-right:1px solid">------</td>
									<td>-------</td>
								</tr>
							</table></td>
							<td>
								<table style="text-align: center;border: none;margin: 0px;" width="100%"  cellspacing="0" cellpadding="0">
									<tr>
										<td style="border-right:1px solid">------</td>
										<td>-------</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td colspan="13"></td>
						</tr>
						<tr>
							<td colspan="8" rowspan="3" style="padding:2px;">
							Total Invoice Value (In Figure)</br></br>
						Total Invoice Value (In words)</br></br>
					Amount of Tax subject to Reverse Charges</br></br>
				</td>
				<td>-------</td>
				<td>-------</td>
				<td>-------</td>
				<td>-------</td>
			</tr>
			<tr>
				<td colspan="4">--------</td>
			</tr>
			<tr>
				<td>--------</td>
				<td>---------</td>
				<td>----------</td>
				<td>----------</td>
			</tr>
		</table>
	</td>
</tr>
<tr style="text-align: center;">
	<td></br><b>Signature of HT</br>with stamp</b></td>
	<td></br><b>verifed by </br>AMM/S & D</b></td>
</tr>
</table>
<p style="text-align: center;">Original for receipient</p>
<p style="text-align: center;">Duplicate for Supplier</p>
</div>
</body>
</html>
