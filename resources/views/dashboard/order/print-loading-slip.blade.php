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
				<li class="active">Print LoadingSlip </li>
			</ul>
		</div>

		<div class="page-content">

			<div class="row">
				<div class="col-xs-12">
					

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
										<a href="{{ url('/user/loading-slips') }}" class="btn btn-primary"><i class="fa fa-arrow-circle-left"></i></a>
									</div>
								</div>
								<div class="row" id="print_area" style="width:100%">
                                	   @for($i=0;$i<=1;$i++)

									<div class="col-sm-4 col-sm-offset-4" >
										<div class="widget-box transparent" style="border: 1px solid #ccc;">
											
												<div class="widget-toolbar no-border invoice-info" style="float:left;">

													<span class="invoice-info-label" style="margin-top: 5px; font-size: 15px;">{{__('messages.No')}} .#</span>
													<span class="red" style="margin-top: 5px; font-size: 18px;"><b>DA/OT/{{$loading_slip->order_id}}/LT/{{$loading_slip->id}}</b></span><br>

													
													<span class="invoice-info-label">{{__('messages.Date')}} :</span>
													<span class="blue"><b>{{ date('d/m/Y',strtotime($loading_slip->created_at)) }}</b></span>
												</div>
												<div class="widget-header widget-header-large">
													@php
													$encodedLoadingSlipQr = base64_encode('loading_slip,'.$loading_slip->id);
													@endphp
													<h3 class="widget-title grey lighter" style="text-align: center;">
														<img src="https://chart.googleapis.com/chart?chs=100x100&cht=qr&chl={{$encodedLoadingSlipQr}}&choe=UTF-8" title="Loading Slip" />

													</h3>


												</div>

												<div class="widget-body">
													<div class="widget-main padding-16">
														<div class="row">
															<div class="col-sm-12">
																<div class="row">
																	<center><b style="font-size: 16px; width: 100%;">
																		{{$company->name}} ( {{$company->city}} )</b></center>
																		<center><b style="font-size: 16px; width: 100%;"> 
																			@if($i==0)
																			Dirver Loading Slip</b></center>
																			@else
																			Clerk Loading Slip</b></center>
																			@endif
																		

																	</div>

																	<div>
																		<ul class="list-unstyled spaced">
																			<li>
																				<div class="col-sm-5"><strong style="float: left;font-size: 16px;">From</strong></div>
																				<div class="col-sm-6"> <span style="float: left;"><b>
																					@if($loading_slip->order_from == 2)
																						{{getModelById('Warehouse',$loading_slip->from_warehouse_id)->name}}
																					@else

																						@if($loading_slip->rake_point != null)
																						
																						{{getModelById('RakePoint',$loading_slip->rake_point)->rake_point}}
																						@endif
																					@endif
																				</b>
																				</span></div><br clear="all">
																			</li>
																			<li>
																				<div class="col-sm-5"><strong style="float: left;font-size: 16px;"> {{__('messages.Dealer')}}</strong></div>
																				<div class="col-sm-6"> <span style="float: left;"><b>
																					{{getdealer($loading_slip->dealer_id)->name}} ({{$loading_slip->dealer_id}}) </b>
																				</span></div><br clear="all">
																			</li>
																			<li>
																				<div class="col-sm-5"><strong style="float: left;font-size: 16px;"> {{__('messages.Retailer')}}</strong></div>
																				<div class="col-sm-6"> <span style="float: left;">
																					@if(!is_null($loading_slip->retailer_id))
																					{{getretailer($loading_slip->retailer_id)->name}} 
																					<br>
																					({{getretailer($loading_slip->retailer_id)->address}})
																					@endif
																				</span></div><br clear="all">
																			</li>


																			
																		
																			<li>
																				<div class="col-sm-5"><strong style="float: left;font-size: 16px;"> {{__('messages.Product')}}</strong></div>
																				<div class="col-sm-6"> <span style="float: left;"><b>{{getModelById('ProductCompany',$loading_slip->product_company_id)->brand_name}} ({{getModelById('Product',$loading_slip->product_id)->name}})</b></span></div><br clear="all">
																			</li>

																			<li>
																				<div class="col-sm-5"><strong style="float: left;font-size: 16px;">{{getModelById('Unit',$loading_slip->unit_id)->unit}}</strong></div>
																				<div class="col-sm-6"> <span style="float: left;"><b>{{$loading_slip->quantity}}</b></span></div><br clear="all">
																			</li>

																			

																			<li>
																				<div class="col-sm-5"><strong style="float: left;font-size: 16px;"> {{__('messages.Transporter')}}</strong></div>
																				<div class="col-sm-6"> <span style="float: left;">{{is_null(getModelById('Transporter',$loading_slip->transporter_id)) ? "":getModelById('Transporter',$loading_slip->transporter_id)->name}}</span></div><br clear="all">
																			</li>
																			<li>
																				<div class="col-sm-5"><strong style="float: left;font-size: 16px;">Transport Mode</strong></div>
																				<div class="col-sm-6"> <span style="float: left;">{{is_null(getModelById('TransportMode',$loading_slip->transport_mode)) ? "":getModelById('TransportMode',$loading_slip->transport_mode)->name}}</span></div><br clear="all">
																			</li>
																			<li>
																				<div class="col-sm-5"><strong style="float: left;font-size: 16px;"> {{__('messages.TruckNumber')}}</strong></div>
																				<div class="col-sm-6"> <span style="float: left;"><b>{{$loading_slip->vehicle_no}}</b></span></div><br clear="all">
																			</li>

																			
																			

																			<br clear="all"><br clear="all">
																			<li>
																				<strong style="float: right; font-size: 16px;"> {{__('messages.Signature')}} : </strong>
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
											<!-- <hr> -->
											@endfor
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

      		<script>

      			jQuery(function($) {

      				window.onafterprint = function(){
   window.close();
}
      			});
      		</script>
		@endsection
		@endsection
    