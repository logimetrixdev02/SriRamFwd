@extends('dashboard.layouts.app')
@section('title','Products')
@section('content')

<div class="main-content">
	<div class="main-content-inner">
		<div class="breadcrumbs ace-save-state" id="breadcrumbs">
			<ul class="breadcrumb">
				<li>
					<i class="ace-icon fa fa-home home-icon"></i>
					<a href="#">Home</a>
				</li>
				<li class="active">Products</li>
			</ul>
		</div>

		<div class="page-content">

			<div class="row">
				<div class="col-xs-12">
					<h3 class="header smaller lighter blue">Products</h3>
					<div id="google_translate_element"></div>
					<div class="clearfix">
						<div class="pull-right tableTools-container">
						</div>
					</div>
					<div class="table-header">
						Results for "Latest Registered Products"
						<div class="widget-toolbar no-border">
							<a class="btn btn-xs bigger btn-danger dropdown-toggle"  data-toggle="modal" href='#addProductModal'>
								Add
								<i class="ace-icon fa fa-plus icon-on-right"></i>
							</a>
						</div>

					</div>

					<!-- div.table-responsive -->

					<!-- div.dataTables_borderWrap -->
					<div>
						<table id="dynamic-table" class="table table-striped table-bordered table-hover">
							<thead>
								<tr>

									<th>Unique ID</th>
									<th>Product Category</th>
									<th>Product</th>
									<th>Hindi Name</th>
									<th>HSN Code</th>
									<th>GST Slab</th>
									<th>IGST</th>
									<th>CGST</th>
									<th>SGST</th>
									<th></th>
								</tr>
							</thead>

							<tbody>
								@foreach($products as $product)
								<tr id="tr_{{$product->id}}">

									<td>{{$product->unique_id}}</td>
									<td>{{$product->product_category->category}}</td>
									<td>{{$product->name}}</td>
									<td>{{$product->hindi_name}}</td>
									<td>{{$product->hsn_code}}</td>
									<td>{{$product->gst_slab}}</td>
									<td>{{$product->igst}}</td>
									<td>{{$product->cgst}}</td>
									<td>{{$product->sgst}}</td>
									<td>
										<div class="hidden-sm hidden-xs btn-group">
											<a class="btn btn-xs btn-info" onclick="getEdit({{$product->id}})" >
												<i class="ace-icon fa fa-pencil bigger-120"></i>
											</a>

											<button class="btn btn-xs btn-danger" onclick="deleteProduct({{$product->id}})" >
												<i class="ace-icon fa fa-trash-o bigger-120"></i>
											</button>
										</div>
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>




		</div><!-- /.page-content -->
	</div>
</div><!-- /.main-content -->


<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Add New Product</h4>
			</div>
			<div class="modal-body">

				<form action="" role="form" id="addProductForm">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="name">Product Name</label>
								<input type="text" class="form-control" name="name" id="name" placeholder="Product Name">
								<span class="label label-danger" id="add_product_name_error" style="display: none;"></span>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="hsn_code">HSN Code</label>
								<input type="text" class="form-control" name="hsn_code" id="hsn_code" placeholder="HSN Code">
								<span class="label label-danger" id="add_hsn_code_error" style="display: none;"></span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="gst_slab">GST Slab (%)</label>
								<input type="text" class="form-control" name="gst_slab" id="gst_slab" placeholder="GST Slab" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
								<span class="label label-danger" id="add_gst_slab_error" style="display: none;"></span>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="igst">IGST (%)</label>
								<input type="text" class="form-control" name="igst" id="igst" placeholder="IGST" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
								<span class="label label-danger" id="add_igst_error" style="display: none;"></span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="cgst">CGST (%)</label>
								<input type="text" class="form-control" name="cgst" id="cgst" placeholder="CGST" oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
								<span class="label label-danger" id="add_cgst_error" style="display: none;"></span>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="sgst">SGST (%)</label>
								<input type="text" class="form-control" name="sgst" id="sgst" placeholder="SGST" oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
								<span class="label label-danger" id="add_sgst_error" style="display: none;"></span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="invoice_type">Invoice Type</label>
								<select name="invoice_type" id="invoice_type" class="form-control">
									<option value="">Select Invoice Type</option>
									@foreach($invoice_types as $invoice_type)
									<option value="{{$invoice_type->id}}">{{$invoice_type->invoice_type}}</option>
									@endforeach
								</select>
								<span class="label label-danger" id="add_invoice_type_error" style="display: none;"></span>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="product_category">Product Categories</label>
								<select name="product_category" id="product_category" class="form-control">
									@foreach($product_categories  as  $product_cat)
									<option value="{{$product_cat->id}}">{{$product_cat->category}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="weight_in_kg">Weight (in kg)</label>
								<input type="text" class="form-control"  value="" name="weight_in_kg" id="edit_weight_in_kg" placeholder="Weight" oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
								<span class="label label-danger" id="edit_weight_in_kg_error" style="display: none;"></span>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="hindi_name">Hindi Name</label>
								<input type="text" class="form-control convertHindi" name="hindi_name" id="hindi_name" placeholder="Product Name">
							</div>
						</div>

						
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" id="addProductBtn" class="btn btn-primary">Submit</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- Add Product Modal -->


<!-- Edit Product Modal -->

<div class="modal fade" id="editProductModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Update Product</h4>
			</div>
			<div class="modal-body" id="EditBody">


			</div>
		</div>
	</div>
</div>

<!-- Edit Product Modal -->
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


<script type="text/javascript">


	jQuery(function($) {
				//initiate dataTables plugin
				var myTable = 
				$('#dynamic-table').DataTable( {
					bAutoWidth: false,
					"aaSorting": [],
				} );
				
				$.fn.dataTable.Buttons.defaults.dom.container.className = 'dt-buttons btn-overlap btn-group btn-overlap';
				
				new $.fn.dataTable.Buttons( myTable, {
					buttons: [
					{
						"extend": "colvis",
						"text": "<i class='fa fa-search bigger-110 blue'></i> <span class='hidden'>Show/hide columns</span>",
						"className": "btn btn-white btn-primary btn-bold",
						columns: ':not(:first):not(:last)'
					},
					{
						"extend": "copy",
						"text": "<i class='fa fa-copy bigger-110 pink'></i> <span class='hidden'>Copy to clipboard</span>",
						"className": "btn btn-white btn-primary btn-bold"
					},
					{
						"extend": "csv",
						"text": "<i class='fa fa-database bigger-110 orange'></i> <span class='hidden'>Export to CSV</span>",
						"className": "btn btn-white btn-primary btn-bold"
					},
					{
						"extend": "excel",
						"text": "<i class='fa fa-file-excel-o bigger-110 green'></i> <span class='hidden'>Export to Excel</span>",
						"className": "btn btn-white btn-primary btn-bold"
					},
					{
						"extend": "pdf",
						"text": "<i class='fa fa-file-pdf-o bigger-110 red'></i> <span class='hidden'>Export to PDF</span>",
						"className": "btn btn-white btn-primary btn-bold"
					},
					{
						"extend": "print",
						"text": "<i class='fa fa-print bigger-110 grey'></i> <span class='hidden'>Print</span>",
						"className": "btn btn-white btn-primary btn-bold",
						autoPrint: false,
						message: 'This print was produced using the Print button for DataTables'
					}		  
					]
				} );
				myTable.buttons().container().appendTo( $('.tableTools-container') );
				
				//style the message box
				var defaultCopyAction = myTable.button(1).action();
				myTable.button(1).action(function (e, dt, button, config) {
					defaultCopyAction(e, dt, button, config);
					$('.dt-button-info').addClass('gritter-item-wrapper gritter-info gritter-center white');
				});
				
				
				var defaultColvisAction = myTable.button(0).action();
				myTable.button(0).action(function (e, dt, button, config) {
					
					defaultColvisAction(e, dt, button, config);
					
					
					if($('.dt-button-collection > .dropdown-menu').length == 0) {
						$('.dt-button-collection')
						.wrapInner('<ul class="dropdown-menu dropdown-light dropdown-caret dropdown-caret" />')
						.find('a').attr('href', '#').wrap("<li />")
					}
					$('.dt-button-collection').appendTo('.tableTools-container .dt-buttons')
				});

			})
		</script>


		<script>

			$(document).ready(function(){
				$('#addProductBtn').click(function(e){
					e.preventDefault();
					$.ajaxSetup({
						headers: {
							'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
						}
					});
					var cgst = $('#cgst').val();
					var sgst = $('#sgst').val();
					if (parseInt(cgst) !== parseInt(sgst)) {
						swal({
							title : "Mismatch CGST SGST!",
							text  : "Please enter equal CGST and SGST.",
							type  : "error"
						}, function(){
							$('#sgst').focus();
						});
						return false;
					} else {
						$('.loading-bg').show();
						$.ajax({
							url: $('#addProductForm').attr('action'),
							method: 'POST',
							data: $('#addProductForm').serialize(),
							success: function(data){
								$('.loading-bg').hide();
								if(!data.flag){
									showError('add_product_name_error',data.errors.name);
									showError('add_hsn_code_error',data.errors.hsn_code);
									showError('add_gst_slab_error',data.errors.gst_slab);
									showError('add_igst_error',data.errors.igst);
									showError('add_cgst_error',data.errors.cgst);
									showError('add_sgst_error',data.errors.sgst);
									showError('add_invoice_type_error',data.errors.invoice_type);
								}else{
									swal({
										title: "Success!",
										text: data.message,
										type: "success"
									}, function() {
										window.location.reload();
									});
								}
							}

						});
					}

				});

			});


			function getEdit(id){
				if(id == ""){
					swal('Error','Product id is missing','warning');
				}else{
					$.ajax({
						url: "{{url('/user/edit-product/')}}"+"/"+id,
						type: 'GET',
						success:function(data){
							$('#EditBody').html(data);
							$('#editProductModal').modal('toggle');
							OnLoad();
						}
					});
				}
			}

			function updateProduct(){
				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
					}
				});
				$('.loading-bg').show();
				$.ajax({
					url: $('#editProductForm').attr('action'),
					method: 'POST',
					data: $('#editProductForm').serialize(),
					success: function(data){
						$('.loading-bg').hide();
						console.log(data);
						if(!data.flag){
							showError('edit_name_error',data.errors.name);
							showError('edit_hsn_code_error',data.errors.hsn_code);
							showError('edit_gst_slab_error',data.errors.gst_slab);
							showError('edit_igst_error',data.errors.igst);
							showError('edit_cgst_error',data.errors.cgst);
							showError('edit_sgst_error',data.errors.sgst);
							showError('edit_invoice_type_error',data.errors.invoice_type);
						}else{
							swal({
								title: "Success!",
								text: data.message,
								type: "success"
							}, function() {
								window.location.reload();
							});
						}
					}

				});

			}

			function deleteProduct(id){
				if(id == ""){
					swal('Error','Product id is missing','warning');
				}else{
					swal({
						title: "Are you sure?",
						text: "You will not be able to recover this Product!",
						type: "warning",
						showCancelButton: true,
						confirmButtonColor: '#DD6B55',
						confirmButtonText: 'Yes, I am sure!',
						cancelButtonText: "No, cancel it!",
						closeOnConfirm: false,
						closeOnCancel: false
					},
					function(isConfirm){
						if (isConfirm){
							$.ajax({
								url: "{{url('/user/delete-product/')}}"+"/"+id,
								type: 'GET',
								success:function(data){
									if(data.flag){
										$('#tr_'+id).remove();
										swal("Success", "Product Deleted Successfully", "success");
									}else{
										swal("Error", data.message, "error");
									}
								}
							});
						} else {
							swal("Cancelled", "Your Product is safe :)", "error");
						}
					});
				}
			}
			function showError(id,error){
				if(typeof(error) === "undefined"){
					$('#'+id).hide();
				}else{
					$('#'+id).show();
					$('#'+id).text(error);
				}
			}
		</script>

		<script type="text/javascript">
			function googleTranslateElementInit() {
				new google.translate.TranslateElement({pageLanguage: 'en',includedLanguages: 'en,hi'}, 'google_translate_element');
			}
		</script>

		<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
		@endsection
		@endsection
