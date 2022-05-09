@extends('dashboard.layouts.app')
@section('title','Token')
@section('style')
<style type="text/css">


	@media print {
		.goog-te-banner-frame.skiptranslate {
			display: none !important;
		} 

		body * {
			visibility: hidden;
		}
		.qrcode{
			margin-left: 40px;
		}
		#print_area, #print_area * {
			visibility: visible;
		}
		#print_area {
			position: absolute;
			left: 0;
			top: 0;
			margin: 0;
		}
		
	}
</style>
@endsection

@section('content')

<div class="main-content notranslate">
	<div class="main-content-inner">
		<div class="breadcrumbs ace-save-state" id="breadcrumbs">
			<ul class="breadcrumb">
				<li>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="#">{{__('messages.Home')}}</a>
				</li>
				<li class="active">Order Token</li>
			</ul>
		</div>

		<div class="page-content">

			<div class="row">
				<div class="col-xs-12">
					<h3 class="header smaller lighter blue">Print Order Token 
						<div id="google_translate_element"></div></h3>
						
						<div class="clearfix">
							<div class="pull-right tableTools-container">
							</div>
						</div>


						<!-- div.table-responsive -->

						<!-- div.dataTables_borderWrap -->
						<div>
							<div class="row">
								<div class="col-xs-12">
									<!-- PAGE CONTENT BEGINS -->
									<!-- <div class="space-6"></div> -->

									<div class="row">
										<div class="col-sm-6 col-sm-offset-3">
											<div class="widget-box transparent" id="print_area" style="border: 1px solid #ccc;">
												<div class="widget-header widget-header-large">
													<div class="widget-toolbar hidden-480" style="float:right">
														<a href="#" onclick="window.print()">
															<i class="ace-icon fa fa-print"></i>
														</a>
													</div>

													<h3 class="widget-title grey lighter">
														<center><strong>Order Token</strong></center>
													</h3>


												</div>
												<div class="widget-toolbar no-border invoice-info" style="float:left;">
													<span class="invoice-info-label" style="margin-top: 5px; font-size: 15px;">{{__('messages.Token')}} #</span>
													<span class="red" style="margin-top: 5px; font-size: 18px;">{{$token->id}}</span>
												</div>

												<div class="widget-toolbar no-border invoice-info" style="float:right;">
													<span class="invoice-info-label">{{__('messages.Date')}}:</span>
													<span class="blue">{{ date('d/m/Y',strtotime($token->date_of_generation)) }}</span>
												</div>
												<div class="qrcode">
													@php
													$encoded_qr = base64_encode($token->id);
													@endphp
													<img src="https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl={{$encoded_qr}}&choe=UTF-8" title="Buffer / Token" />
												</div>

												<div class="widget-body">
													<div class="widget-main padding-16">
														<div class="row">
															<div class="col-sm-12">
																<div class="row">
																	<center><b style="font-size: 16px; width: 100%; text-decoration: underline;">
																		{{getModelById('Company',$token->company_id)->name}}
																		<br/>{{getModelById('Company',$token->company_id)->address1}}</b></center>

																	</div>

																	<div class="translate">


																		<table>
																			<col width="50%">
																			<col width="50%">

																			<tr>
																				<td><b>{{__('messages.To')}}</b></td>
																				<td><b>
																					@if($token->to_type == 1)
																					{{getModelById('Warehouse',$token->warehouse_id)->name}}
																					@elseif($token->to_type == 2)
																					{{getModelById('Retailer',$token->retailer_id)->name}} , 
																					{{getModelById('Retailer',$token->retailer_id)->address}}
																					@else
																					{{getModelById('Dealer',$token->dealer_id)->name}}
																					{{getModelById('Dealer',$token->dealer_id)->address1}}, {{getModelById('Dealer',$token->dealer_id)->address2}}
																					@endif

																				</b></td>
																			</tr>

																			<tr>
																				@if($token->token_type == 1)
																				<td><b>Rake</b></td>
																				<td><b>Rake Point</b></td>
																				@else
																				<td><b>{{__('messages.Warehouse')}}</b></td>
																				<td><b>{{getModelById('Warehouse',$token->from_warehouse_id)->name}}</b></td>

																				@endif
																			</tr>

																			<tr>
																				<td><b>{{__('messages.NameOfGoods')}}</b></td>
																				<td><b>{{getModelById('Product',$token->product_id)->name}}</b></td>

																			</tr>
																			
																			<tr>
																				<td><b>{{__('messages.company')}}</b></td>
																				<td><b>{{getModelById('ProductCompany',$token->product_company_id)->brand_name}}</b></td>

																			</tr>

																			<tr>
																				<td><b>{{__('messages.Quantity')}}</b></td>
																				<td><b>{{$token->quantity}} {{getModelById('Unit',$token->unit_id)->unit}}</b></td>
																			</tr>

																			<tr>
																				<td><b>{{__('messages.Warehouse')}} {{__('messages.Keeper')}}</b></td>
																				<td><b>{{is_null(getModelById('User',$token->warehouse_keeper_id)) ? "":getModelById('User',$token->warehouse_keeper_id)->name}}</b></td>
																			</tr>

																			<tr>
																				<td><b>{{__('messages.Transporter')}}</b></td>
																				<td><b>{{is_null(getModelById('Transporter',$token->transporter_id)) ? "":getModelById('Transporter',$token->transporter_id)->name}}</b></td>
																			</tr>
																		</table>
																		<ul class="list-unstyled spaced">
																			<br clear="all"><br clear="all">
																			<li>
																				<div class="col-sm-12"><span style="float: right; font-size: 16px;">&nbsp;{{getModelById('Company',$token->company_id)->name}}</span><strong style="float: right; font-size: 16px;">For : </strong>
																				</div>
																			</li>
																		</ul>
																	</div>
																</div><!-- /.col -->


															</div><!-- /.row -->




														</div>
													</div>
												</div>
											</div>
										</div>

										<!-- PAGE CONTENT ENDS -->
									</div><!-- /.col -->
								</div>
							</div>
						</div>
					</div>




				</div><!-- /.page-content -->
			</div>
		</div><!-- /.main-content -->
		@section('script')

		{{ Html::script("assets/js/jquery.dataTables.min.js")}}
		{{ Html::script("assets/js/jquery.dataTables.bootstrap.min.js")}}
		{{ Html::script("assets/js/dataTables.buttons.min.js")}}
		{{ Html::script("assets/js/buttons.flash.min.js")}}
		{{ Html::script("assets/js/buttons.html5.min.js")}}
		{{ Html::script("assets/js/buttons.print.min.js")}}
		{{ Html::script("assets/js/buttons.colVis.min.js")}}
		{{ Html::script("assets/js/dataTables.select.min.js")}}
		{{ Html::script("assets/js/ace-elements.min.js")}}
		{{ Html::script("assets/js/ace.min.js")}}


		
	</script>
	@endsection
	@endsection
