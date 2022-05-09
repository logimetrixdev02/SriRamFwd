<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::fallback(function () {
	$response = array();
	$response['flag'] = false;
	$response['message'] = "URL Not found";
	echo json_encode($response);
});


Route::middleware('auth:api')->get('/user', function (Request $request) {
	return $request->user();
});

Route::post('login','ApiController@login');
Route::post('adminwarehouse','ApiController@adminTokens');
Route::post('tokens','ApiController@tokens');
Route::post('orders','ApiController@orders');
Route::post('approved-orders','ApiController@approved_orders');
Route::post('order-details','ApiController@order_details');
Route::post('add-loading','ApiController@add_loading');
Route::post('print-loading-slip','ApiController@print_loading_slip');
Route::post('get-loading-slips','ApiController@get_loading_slips');
Route::post('token-list','ApiController@tokenList');
Route::post('products','ApiController@products');
Route::post('dealers','ApiController@dealers');
Route::post('retailers','ApiController@retailers');
Route::post('product-companies','ApiController@product_companies');
Route::post('units','ApiController@units');
Route::post('transporters','ApiController@transporters');
Route::post('add-new-transporter','ApiController@addTransporter');
Route::post('warehouses','ApiController@warehouses');
Route::post('token-details','ApiController@tokenDetails');
Route::post('master-rakes','ApiController@master_rakes');
Route::post('master-rake-details','ApiController@masterRakeDetails');

Route::post('token-list-rake','ApiController@tokenListRake');
Route::post('token-company-wise','ApiController@tokenCompanyWise');
Route::post('token-of-company-products-wise','ApiController@tokenOfCompanyProductsWise');
Route::post('token-of-company-party-wise','ApiController@tokenOfCompanyPartyWise');

Route::post('product-loading','ApiController@productLoading');
Route::post('product-unloading','ApiController@productUnloading');
Route::post('labour-slips','ApiController@labourSlips');
Route::post('loading-slips','ApiController@loadingSlips');
Route::post('new-loading-slips','ApiController@new_loading_slips');
Route::post('loading-slip-list','ApiController@loadingSlipList');
Route::post('unloading-slips','ApiController@unloadingSlips');
Route::post('loading-slip-details','ApiController@loadingSlipDetails');
Route::post('labour-slip-details','ApiController@labourSlipDetails');
Route::post('unloading-slip-details','ApiController@unloadingSlipDetails');
Route::post('direct-labour-payment-slips','ApiController@DirectlabourPaymentSlips');
Route::post('direct-labour-payment-slip-details','ApiController@directLabourPaymentSlipDetails');
Route::post('pay-labour','ApiController@payLabour');
Route::post('destinations','ApiController@destinations');
Route::post('pay-freight','ApiController@payFreight');
Route::post('direct-labour-payment','ApiController@directLabourPayment');
Route::post('application-modules','ApiController@applicationModules');
Route::get('dealer-payment','ApiController@checkDealerSmSData');

Route::post('wagon-unloading','ApiController@wagonUnloading');
Route::post('wagon-unloadings','ApiController@wagonUnloadings');
Route::post('wagon-unloading-details','ApiController@wagonUnloadingDetails');

Route::post('standardization','ApiController@standardization');
Route::post('standardization-details','ApiController@standardizationDetails');

Route::post('invoice-types','ApiController@invoice_types');
Route::post('loading-slip-invoices','ApiController@loadingSlipInvoices');
Route::post('loading-slip-invoice-details','ApiController@loadingSlipInvoiceDetails');
Route::post('receive-returned-product','ApiController@receivedReturnedProduct');
Route::post('returned-product-details','ApiController@returnedProductDetails');
Route::post('warehouse-transfer-loading','ApiController@WarehouseTransferLoading');
Route::post('warehouse-transfer-loadings','ApiController@WarehouseTransferLoadings');
Route::post('/warehouse-transfer-loading-list','ApiController@WarehouseTransferLoadingList');
Route::post('warehouse-transfer-loading-details','ApiController@warehouseTransferLoadingDetails');
Route::post('warehouse-transfer-unloading','ApiController@WarehouseTransferUnloading');
Route::post('warehouse-transfer-unloadings','ApiController@WarehouseTransferUnloadings');
Route::post('warehouse-transfer-unloading-list','ApiController@WarehouseTransferUnloadingList');
Route::post('warehouse-transfer-unloading-details','ApiController@warehouseTransferUnloadingDetails');
/*----------Marketing Manager APIs-------*/
Route::post('rake-total-tokens-loadings','ApiController@rakeTotalTokenLoading');
Route::post('get-rake-tokens','ApiController@getRakeTokens');
Route::post('rake-loadings','ApiController@getRakeLoadings');

Route::post('warehouse-total-tokens-loadings','ApiController@warehouseTotalTokenLoading');
Route::post('get-warehouse-tokens','ApiController@getWarehouseTokens');
Route::post('warehouse-loadings','ApiController@getWarehouseLoadings');
/*----------Marketing Manager APIs-------*/
/*----------Logistic Manager APIs-------*/
Route::post('rake-tokens','ApiController@rakeTokens');
Route::post('token-loadings','ApiController@tokenLoadings');
Route::post('rake-unloadings','ApiController@rakeUnloadings');
Route::post('warehouse-tokens','ApiController@warehouseTokens');
Route::post('rake-financial-details','ApiController@rakeFinancialDetails');
Route::post('rake-summary-details','ApiController@rakeSummaryDetails');
Route::post('new-rake-summary-details','ApiController@newRakeSummaryDetails');
Route::post('expense-list-rake','ApiController@expenseListRake');
Route::post('freight-payment-info','ApiController@freightPaymentInfo');

Route::post('rake-financial-report','ApiController@rakeFinancialReport');
Route::post('warehouse-financial-details','ApiController@warehouseFinancialDetails');
Route::post('warehouse-financial-report','ApiController@warehouseFinancialReport');
/*----------Logistic Manager APIs-------*/
/*----------Admin APIs-------*/
Route::post('daily-sales-purchase','ApiController@dailySalesPurchase');
Route::post('daily-warehouse-payment-reports','ApiController@dailyWarehousePaymentReports');
Route::post('rake-payment-reports','ApiController@rakePaymentReports');
Route::post('approve-daily-warehouse-expense-report','ApiController@approveDailyWarehouseExpenseReport');
Route::post('approve-rake-expense-report','ApiController@approveRakeExpenseReport');
Route::post('reject-report','ApiController@rejectReport');
Route::post('stock-check','ApiController@getPartyStock');

/*----------Admin APIs-------*/


Route::post('my-product-list','ApiController@my_product_list');

Route::post('dealer-list','ApiController@dealer_list');

Route::post('product-company-list','ApiController@product_company_list');

Route::post('product-company-list-dealer-wise','ApiController@product_company_list_dealer_wise');

Route::post('product-warehouse-list-dealer-wise','ApiController@product_warehouse_list_dealer_wise');

Route::post('product-warehouse-list','ApiController@product_warehouse_list');

Route::post('product-stock-list','ApiController@product_stock_list');

Route::post('company-product-stock-list','ApiController@company_product_stock_list');
Route::post('print_invoice_detail','ApiController@print_invoice_detail');
Route::post('company-product-in-warehouse','ApiController@company_product_in_warehouse');
Route::post('warehouse-list','ApiController@warehousesList');
Route::post('loadingapprovedwarehousesList','ApiController@loadingapprovedwarehousesList');
Route::post('warehouse-order-list','ApiController@warehousesOrderList');
Route::post('loading-orders-slip','ApiController@loadingorders');
Route::post('loading-slip-print','ApiController@loadingslipprint');
