@extends('dashboard.layouts.app')
@section('title','Loading Slip')
@section('style')
<style type="text/css">
	@media 
	print {
		body * {
			visibility: hidden;
		}
		#print_area, #print_area * {
			visibility: visible;
		}
		#print_area {
			position: absolute;
			left: 0;
			top: 0;
		}
	}
</style>
@endsection
@section('content')

<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs ace-save-state" id="breadcrumbs">
			<ul class="breadcrumb">
				<li>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="#"> {{__('messages.Home')}}</a>
				</li>
				<li class="active"> {{__('messages.LoadingSlip')}}</li>
			</ul>
		</div>

		<div class="page-content">

			<div class="row">
				<div class="col-xs-12">
					<h3 class="header smaller lighter blue">{{__('messages.Print')}} {{__('messages.LoadingSlip')}}</h3>

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
									<div class="col-md-12">
										<a href="javascript:;" onclick="window.print();" class="btn btn-primary"><i class="fa fa-print"></i></a>
									</div>
									<div class="col-sm-4 col-sm-offset-4" id="print_area">
										<div class="widget-box transparent" style="border: 1px solid #ccc;">
											
											<div class="widget-toolbar no-border invoice-info" style="float:left;">

												<span class="invoice-info-label" style="margin-top: 5px; font-size: 15px;">{{__('messages.No')}}. #</span>
												<span class="red" style="margin-top: 5px; font-size: 18px;">{{$labour_payment->id}}</span>

												<div class="space"></div><div class="space"></div>
												<span class="invoice-info-label">{{__('messages.Date')}} :</span>
												<span class="blue">{{ date('d/m/Y',strtotime($labour_payment->created_at)) }}</span>
											</div>
											<div class="widget-header widget-header-large">
												@php
												$encodedLabourSlipQr = base64_encode('standardization_slip,'.$labour_payment->id);
												@endphp

												<h3 class="widget-title grey lighter" style="float:right">
													<img src="https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl={{$encodedLabourSlipQr}}&choe=UTF-8" title="Labour Payment Slip" />
													
												</h3>

												
											</div>

											<div class="widget-body">
												<div class="widget-main padding-16">
													<div class="row">
														<div class="col-sm-12">
															<div class="row">
															</div>

															<div>
																<ul class="list-unstyled spaced">
																	<li>

																		<li>
																			<div class="col-sm-5"><strong style="float: left;font-size: 16px;"> {{__('messages.Labour')}} {{__('messages.Name')}}</strong></div>
																			<div class="col-sm-6"> <span style="float: left;">{{$labour_payment->labour_name}}</span></div><br clear="all">
																		</li>


																		<li>
																			<div class="col-sm-5"><strong style="float: left;font-size: 16px;"> Party</strong></div>
																			<div class="col-sm-6"> <span style="float: left;">
																				@if(!is_null(getModelById('Dealer',$labour_payment->dealer_id)))
																				{{ getModelById('Dealer',$labour_payment->dealer_id)->name}}
																				@elseif(!is_null(getModelById('ProductCompany',$labour_payment->product_company_id)))
																				{{ getModelById('ProductCompany',$labour_payment->product_company_id)->name}}
																				@endif
																			</span></div><br clear="all">
																		</li>

																		<li>
																			<div class="col-sm-5"><strong style="float: left;font-size: 16px;"> {{__('messages.Warehouse')}}</strong></div>
																			<div class="col-sm-6"> <span style="float: left;">
																				@if(!is_null(getModelById('Warehouse',$labour_payment->warehouse_id)))
																				{{ getModelById('Warehouse',$labour_payment->warehouse_id)->name}}
																				@endif
																			</span></div><br clear="all">
																		</li>

																		<li>
																			<div class="col-sm-5"><strong style="float: left;font-size: 16px;"> {{__('messages.Product')}}</strong></div>
																			<div class="col-sm-6"> <span style="float: left;">
																				@if(!is_null(getModelById('Product',$labour_payment->product_id)))
																				{{ getModelById('Product',$labour_payment->product_id)->name}}
																			@endif</span></div><br clear="all">
																		</li>
																		<li>
																			<div class="col-sm-5"><strong style="float: left;font-size: 16px;"> Open Quantity</strong></div>
																			<div class="col-sm-6"> <span style="float: left;">{{$labour_payment->open_quantity}}</span></div><br clear="all">
																		</li><li>
																			<div class="col-sm-5"><strong style="float: left;font-size: 16px;"> Packed Quantity</strong></div>
																			<div class="col-sm-6"> <span style="float: left;">
																				{{$labour_payment->packed_quantity}}</span></div><br clear="all">
																			</li>
																			<li>
																<div class="col-sm-5"><strong style="float: left;font-size: 16px;">Slip Generated By</strong></div>
																<div class="col-sm-6"> <span style="float: left;">{{is_null(getModelById('User',$labour_payment->user_id)) ? "":getModelById('User',$labour_payment->user_id)->name}}</span></div><br clear="all">
															</li>


																			<br clear="all"><br clear="all">
																			<li>
																				<strong style="float: right; font-size: 16px;">  {{__('messages.Signature')}} :</strong>
																				<br>
																				<div class="col-sm-12"><span style="float: right; font-size: 16px;"></span>
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


		@endsection
		@endsection
