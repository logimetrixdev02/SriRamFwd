<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


/*---------------Authentication Rouets Start----------------------*/



Route::get('/', 'AuthController@index')->middleware('guest')->name('login');
Route::post('/', 'AuthController@login');
Route::get('/logout', 'AuthController@logout');
Route::get('/testmail', 'TestController@send_mail');
Route::post('/set-company', 'DashboardController@setCompany');
Route::post('/change-language', 'DashboardController@changeLanguage');

Route::get('/forget-password','AuthController@getForgetPassword');
Route::post('/forget-password','AuthController@postForgetPassword');
Route::get('/verify_reset_token/{token}','AuthController@verify_reset_token');
Route::post('/reset-password','AuthController@post_reset_password'); 



Route::group(['middleware' => ['auth']], function () {
	Route::get('/user', 'DashboardController@index');
	Route::get('/user/latest-sale-activities', 'DashboardController@latestSaleActivities');
	Route::get('/user/get-monthly-average/{month}/{year}', 'DashboardController@getMonthlyAverage');
	Route::get('/user/get-party-stock-sale-graph/{party}/{month}/{year}', 'DashboardController@getPartyStockSaleGraph');
	Route::get('/user/get-party-stock/{party}', 'DashboardController@getPartyStock');
	Route::get('/user/get-rake-graph/{rake_id}', 'DashboardController@getRakeGraph');
	Route::get('/user/get-sales-and-purchase/{date}', 'DashboardController@getSalesAndPurchase');
	
	Route::get('/profile','ProfileController@profile');
	Route::post('/postChangePassword','ProfileController@postChangePassword');
	Route::post('/profile/update/profile-pic','ProfileController@updateProfilePic');

});


Route::get('/admin/blank', 'DashboardController@blank');

/*-------Masters-------*/

// Route::group(['middleware' => ['auth','check_role']], function () {
Route::group(['middleware' => ['auth']], function () {
//role master
	Route::get('/user/quick-links', 'MasterController@quickLinks');
	Route::get('/marketing-manager', 'MarketingManagerController@dashboard');
	Route::get('/marketing-manager/rake', 'MarketingManagerController@rake');
	Route::get('/marketing-manager/warehouse', 'MarketingManagerController@warehouse');

	Route::get('/user/roles', 'MasterController@roles');
	Route::post('/user/roles', 'MasterController@addRole');
	Route::get('/user/edit-role/{role_id}', 'MasterController@getEditRole');
	Route::post('/user/edit-role', 'MasterController@updateRole');
	Route::get('/user/delete-role/{role_id}', 'MasterController@deleteRole');
	Route::get('/user/assign-permissions/{role_id}', 'MasterController@getRolePermissions');
	Route::get('/user/update-permissions/{role_id}/{module_id}/{sub_module_id}', 'MasterController@updateRolePermissions');

// Session Master
	Route::get('/user/sessions', 'MasterController@sessions');
	Route::post('/user/sessions', 'MasterController@addSession');
	Route::get('/user/edit-session/{session_id}', 'MasterController@getEditSession');
	Route::post('/user/edit-session', 'MasterController@updateSession');
	Route::get('/user/delete-session/{session_id}', 'MasterController@deleteSession');


	// Bank Master
	Route::get('/user/banks', 'MasterController@banks');
	Route::post('/user/banks', 'MasterController@addBank');
	Route::get('/user/edit-bank/{bank_id}', 'MasterController@getEditBank');
	Route::post('/user/edit-bank', 'MasterController@updateBank');
	Route::get('/user/delete-bank/{bank_id}', 'MasterController@deleteBank');

	// Bank Account Master
	Route::get('/user/bank-accounts', 'MasterController@bank_accounts');
	Route::post('/user/bank-accounts', 'MasterController@addBankAccount');
	Route::get('/user/edit-bank-account/{bank_account_id}', 'MasterController@getEditBankAccount');
	Route::post('/user/edit-bank-account', 'MasterController@updateBankAccount');
	Route::get('/user/delete-bank-account/{bank_account_id}', 'MasterController@deleteBankAccount');

//Warehouse Master
	Route::get('/user/warehouses', 'MasterController@warehouses');
	Route::post('/user/warehouses', 'MasterController@addWarehouse');
	Route::get('/user/edit-warehouse/{warehouse_id}', 'MasterController@getEditWarehouse');
	Route::post('/user/edit-warehouse', 'MasterController@updateWarehouse');
	Route::get('/user/delete-warehouse/{warehouse_id}', 'MasterController@deleteWarehouse');

	//invoice_type Master
	Route::get('/user/invoice-types', 'MasterController@invoice_types');
	Route::post('/user/invoice-types', 'MasterController@addInvoiceType');
	Route::get('/user/edit-invoice-type/{invoce_type_id}', 'MasterController@getEditInvoiceType');
	Route::post('/user/edit-invoice-type', 'MasterController@updateInvoiceType');
	Route::get('/user/delete-invoice-type/{invoce_type_id}', 'MasterController@deleteInvoiceType');


//Payment Mode master
	Route::get('/user/payment-modes', 'MasterController@payment_modes');
	Route::post('/user/payment-modes', 'MasterController@addPaymentMode');
	Route::get('/user/edit-payment-mode/{payment_mode_id}', 'MasterController@getEditPaymentMode');
	Route::post('/user/edit-payment-mode', 'MasterController@updatePaymentMode');
	Route::get('/user/delete-payment-mode/{payment_mode_id}', 'MasterController@deletePaymentMode');

//Rake master
	Route::get('/user/rakes', 'MasterController@rakes');
	Route::post('/user/rakes', 'MasterController@addRake');
	Route::get('/user/edit-rake/{rake_id}', 'MasterController@getEditRake');
	Route::post('/user/edit-rake', 'MasterController@updateRake');
	Route::get('/user/delete-rake/{rake_id}', 'MasterController@deleteRake');


	// Rake Point Master
	Route::get('/user/rake-points', 'MasterController@rake_points');
	Route::post('/user/rake-points', 'MasterController@addRakePoint');
	Route::get('/user/edit-rake-point/{rake_point_id}', 'MasterController@getEditRakePoint');
	Route::post('/user/edit-rake-point', 'MasterController@updateRakePoint');
	Route::get('/user/delete-rake-point/{rake_point_id}', 'MasterController@deleteRakePoint');


// Master Rake
	Route::get('/user/master-rakes', 'MasterController@masterRakes');
	Route::post('/user/master-rakes', 'MasterController@addMasterRake');
	Route::get('/user/edit-master-rake/{master_rake_id}', 'MasterController@getEditMasterRake');
	Route::post('/user/edit-master-rake', 'MasterController@updateMasterRake');
	Route::get('/user/delete-master-rake/{master_rake_id}', 'MasterController@deleteMasterRake');
	Route::get('/user/master-rake-details/{master_rake_id}', 'MasterController@masterRakeDetails');
	Route::get('/user/lock-master-rake/{master_rake_id}', 'RakeController@lockMasterRake');

//Account Master
	Route::get('/user/accounts', 'MasterController@accounts');
	Route::post('/user/accounts', 'MasterController@addAccount');
	Route::get('/user/edit-account/{account_id}', 'MasterController@getEditAccount');
	Route::post('/user/edit-account', 'MasterController@updateAccount');
	Route::get('/user/delete-account/{account_id}', 'MasterController@deleteAccount');

//Transporter master
	Route::get('/user/transporters', 'MasterController@transporters');
	Route::post('/user/transporters', 'MasterController@addTransporter');
	Route::get('/user/edit-transporter/{transporter_id}', 'MasterController@getEditTransporter');
	Route::post('/user/edit-transporter', 'MasterController@updateTransporter');
	Route::get('/user/delete-transporter/{transporter_id}', 'MasterController@deleteTransporter');

//Freight master
	Route::get('/user/freight-list', 'MasterController@freightList');
	Route::post('/user/freight-list', 'MasterController@addFreight');
	Route::get('/user/edit-freight/{freight_id}', 'MasterController@getEditFreight');
	Route::post('/user/edit-freight', 'MasterController@updateFreight');
	Route::get('/user/delete-freight/{freight_id}', 'MasterController@deleteFreight');

//District master
	Route::get('/user/districts', 'MasterController@districts');
	Route::post('/user/add-district', 'MasterController@addDistrict');
	Route::get('/user/edit-district/{district_id}', 'MasterController@getEditDistrict');
	Route::post('/user/edit-district', 'MasterController@updateDistrict');
	Route::get('/user/delete-district/{district_id}', 'MasterController@deleteDistrict');

//Company Master
	Route::get('/user/companies', 'MasterController@companies');
	Route::post('/user/companies', 'MasterController@addCompany');
	Route::get('/user/edit-company/{company_id}', 'MasterController@getEditCompany');
	Route::post('/user/edit-company', 'MasterController@updateCompany');
	Route::get('/user/delete-company/{company_id}', 'MasterController@deleteCompany');


//Dealer Master
	Route::get('/user/dealers', 'MasterController@dealers');
	Route::post('/user/dealers', 'MasterController@addDealer');
	Route::get('/user/edit-dealer/{dealer_id}', 'MasterController@getEditDealer');
	Route::post('/user/edit-dealer', 'MasterController@updateDealer');
	Route::get('/user/delete-dealer/{dealer_id}', 'MasterController@deleteDealer');
	Route::get('/user/export-dealer/', 'MasterController@exportDealer');
	Route::post('/user/import-dealer/', 'MasterController@importDealer');

//Retailers Master
	Route::get('/user/retailers', 'UserController@retailers');
	Route::post('/user/retailers', 'UserController@addRetailers');
	Route::get('/user/edit-retailer/{retailer_id}', 'UserController@getEditRetailer');
	Route::post('/user/edit-retailer', 'UserController@updateRetailer');
	Route::get('/user/delete-retailer/{retailer_id}', 'UserController@deleteRetailer');
	Route::get('/user/export-retailer/', 'UserController@exportRetailer');
	Route::post('/user/import-retailer/', 'UserController@importRetailer');

//Product Category master
	Route::get('/user/product-categories', 'MasterController@product_categories');
	Route::post('/user/product-categories', 'MasterController@addProductCategory');
	Route::get('/user/edit-product-category/{category_id}', 'MasterController@getEditProductCategory');
	Route::post('/user/edit-product-category', 'MasterController@updateProductCategory');
	Route::get('/user/delete-product-category/{category_id}', 'MasterController@deleteProductCategory');

//Product master
	Route::get('/user/products', 'MasterController@products');
	Route::post('/user/products', 'MasterController@addProduct');
	Route::get('/user/edit-product/{product_id}', 'MasterController@getEditProduct');
	Route::post('/user/edit-product', 'MasterController@updateProduct');
	Route::get('/user/delete-product/{product_id}', 'MasterController@deleteProduct');

//Product Companies master
	Route::get('/user/product-companies', 'MasterController@productCompanies');
	Route::post('/user/product-companies', 'MasterController@addProductCompany');
	Route::get('/user/edit-product-company/{product_company_id}', 'MasterController@getEditProductCompany');
	Route::post('/user/edit-product-company', 'MasterController@updateProductCompany');
	Route::get('/user/delete-product-company/{product_company_id}', 'MasterController@deleteProductCompany');

	//Modules
	Route::get('/user/module', 'MasterController@module');
	Route::post('/user/module', 'MasterController@addModule');
	Route::get('/user/edit-module/{module_id}', 'MasterController@getEditModule');
	Route::post('/user/edit-module', 'MasterController@updateModule');
	Route::get('/user/delete-module/{module_id}', 'MasterController@deleteModule');

	//Modules
	Route::get('/user/sub-module', 'MasterController@sub_module');
	Route::post('/user/sub-module', 'MasterController@addSubModule');
	Route::get('/user/edit-sub-module/{sub_module_id}', 'MasterController@getEditSubModule');
	Route::post('/user/edit-sub-module', 'MasterController@updateSubModule');
	Route::get('/user/delete-sub-module/{sub_module_id}', 'MasterController@deleteSubModule');



	/*-------Masters-------*/


	/*-------User Registration-------*/
	Route::get('/user/users', 'UserController@users');
	Route::get('/user/add-new-user', 'UserController@getAddUser');
	Route::post('/user/add-new-user', 'UserController@postAddUser');
	Route::get('/user/edit-user/{user_id}', 'UserController@getEditUser');
	Route::post('/user/edit-user/{user_id}', 'UserController@postEditUser');
	/*-------User Registration-------*/

	/*-------Invoice Routes-------*/
	Route::get('/user/punch-invoice', 'InvoiceController@getPunchInvoice');
	Route::post('/user/punch-invoice', 'InvoiceController@postPunchInvoice');
	Route::get('/user/generated-invoices', 'InvoiceController@getGeneratedInvoices');
	Route::get('/user/generate-invoice', 'InvoiceController@generateInvoice');
	Route::any('/user/loading-slip-invoice-payment', 'InvoiceController@getLoadingSlipInvoicePayment');
	Route::any('/user/comany-di-payment', 'InvoiceController@getCompanyDiPayment');

	Route::post('/user/punch-invoice', 'InvoiceController@postGeneratedInvoice');
	Route::get('/user/loading-slip-invoices-details/{loading_slip_invoice_id}', 'InvoiceController@loadingSlipInvoiceDetails');
	Route::get('/user/expot-as-xml/{loading_slip_invoice_id}', 'InvoiceController@exportAsXml');
	Route::any('/user/party-invoice-ledger', 'DealerController@get_party_ledger');
    Route::any('/user/payment','InvoiceController@payment');
     Route::any('/user/add-payment','InvoiceController@addPayment');
  

	/*-------Invoice Routes-------*/


	/*-------Dealers NF-------*/

	Route::get('/dealer/nf-lucknow-20', 'DealerController@get_dealer_info');
	Route::get('/dealer/nf-bahraich-16', 'DealerController@get_dealer_info');
	Route::get('/dealer/nf-balrampur-17', 'DealerController@get_dealer_info');
	Route::get('/dealer/nf-shrawasti-18', 'DealerController@get_dealer_info');
	Route::get('/dealer/nf-barabanki-19', 'DealerController@get_dealer_info');
	Route::get('/dealer/nf-gonda-21', 'DealerController@get_dealer_info');
	Route::post('/dealer/get-retailer-order', 'DealerController@get_retailer_order');
	Route::post('/dealer/get-order-details', 'DealerController@get_order_details');

	Route::get('/dealer/dispatched-order-list/{loading_id}', 'DealerController@dispatched_order_list');
	Route::get('/dealer/dispatched-order-list-by-road/{loading_id}', 'DealerController@dispatched_order_list_by_road');
	Route::get('/dealer/buffer-stocks/{id}', 'DealerController@get_dealer_buffer_stocks');
	Route::get('/dealer/buffer-stocks-edit/{id}', 'DealerController@getEdit_dealer_buffer_stocks');
	Route::get('/dealer/by-road-stocks-edit/{id}', 'DealerController@getEdit_dealer_by_road_stocks');
	Route::get('/dealer/generate-invoice/{id}', 'DealerController@generate_invoice');
	Route::post('/dealer/generate-invoice', 'DealerController@generate_invoice_now');
	Route::get('/dealer/pub/{rid}', 'DealerController@calculate_ladger_balance');
	

	//Route::post('/dealer/buffer-stocks', 'DealerController@addOrder');
	Route::any('/dealer/sale-point/{id}', 'DealerController@get_dealer_sale_point');
	Route::get('/dealer/day-book/{id}', 'DealerController@dealer_day_book');
	Route::get('/dealer/all-invoices', 'DealerController@all_invoices');
	Route::any('/dealer/party-ledgers', 'DealerController@get_party_ledger');
	Route::any('/dealer/by-road-sale-point/{id}', 'DealerController@get_dealer_by_road_sale_point');

	


	Route::get('/dealer/order/add-order', 'OrderController@getAddOrder');
    Route::post('/dealer/order/add-order', 'OrderController@addOrder');
    Route::get('/dealer/order/edit-order/{order_id}', 'OrderController@editOrder');
     Route::get('/dealer/order/delete-order/{order_id}', 'OrderController@deleteOrder');
     Route::post('/dealer/order/update-order', 'OrderController@updateOrder');
	Route::get('/dealer/requested-orders', 'OrderController@requested_order');
	Route::get('/dealer/approved-orders', 'OrderController@approved_order');
	Route::get('/dealer/order/dispatch-order/{order_id}', 'OrderController@dispatchOrder');
	Route::get('dealer/order/dispatch-orders', 'OrderController@all_dispatch_orders');

	Route::get('/dealer/orders', 'OrderController@allOrder');
	Route::post('/dealer/get-retailer-by-discrict', 'OrderController@get_retailer_by_discrict');
	Route::post('/dealer/get-retailer-by-dealer', 'OrderController@get_retailer_by_dealer');
	Route::post('/dealer/get-product-loading', 'DealerController@get_product_loading');
	Route::post('/dealer/add-dispached-to-retailer', 'DealerController@add_dispached_to_retailer');
	Route::post('/dealer/add-by-road-dispached-to-retailer', 'DealerController@add_by_road_dispached_to_retailer');


	Route::get('/dealer/dispached-item/{id}', 'DealerController@get_dispached_item');
	

	/*-------Dealers NF-------*/


	/*-------Rake Management-------*/

	Route::any('/user/rake-product-allotments', 'RakeController@rake_product_allotments');
	Route::get('/user/allot-product', 'RakeController@getAllotProduct');
	Route::post('/user/allot-product', 'RakeController@postAllotProduct');
	Route::get('/user/edit-allotment/{allotment_id}', 'RakeController@getEditAllotment');
	Route::post('/user/edit-allotment/{allotment_id}', 'RakeController@postEditAllotment');
	Route::get('/user/allotment-details/{allotment_id}', 'RakeController@getAllotmentDetails');
	Route::any('/user/rake-summary', 'RakeController@rake_summary');
	Route::any('/user/export-rake-summary/{rake_id}', 'RakeController@export_rake_summary');
	Route::any('user/rake-daily-report', 'RakeController@rake_daily_report');
	Route::get('user/export-rake-daily-report/{master_rake_id}/{from_date}', 'RakeController@export_rake_daily_report');



	/*-------Rake Management-------*/

	/*-------Token Generation-------*/

	Route::any('/user/generated-token', 'TokenController@generated_tokens');
	Route::any('/user/generated-report', 'TokenController@generated_token_report');
	Route::any('/user/generated-warehouse-token', 'TokenController@generated_warehouse_tokens');
	Route::get('/user/generate-token', 'TokenController@getGenerateToken');
	Route::post('/user/generate-token', 'TokenController@postGenerateToken');
	Route::get('/user/edit-token/{token_id}', 'TokenController@getEditToken');
	Route::post('/user/edit-token/{token_id}', 'TokenController@postEditToken');

	Route::get('/user/edit-token-quantity/{token_id}', 'TokenController@getEditTokenQuantity');
	Route::post('/user/edit-token-quantity/{token_id}', 'TokenController@postEditTokenQuantity');

	Route::get('/user/print-token/{token_id}', 'TokenController@printToken');
	Route::get('/user/product-loading', 'TokenController@getProductLoading');
	Route::post('/user/product-loading', 'TokenController@postProductLoading');
	Route::any('/user/product-loading-list', 'TokenController@product_loading');
	Route::any('/user/export-product-loadings/{master_rake_id}', 'TokenController@exportProductLoadings');
	Route::any('/user/loadings', 'TokenController@dateWiseLoadings');
	Route::any('/user/lifting-report', 'TokenController@liftingReport');
	

	// Route::get('/user/labour-payment', 'TokenController@getLabourPayment');
	// Route::post('/user/labour-payment', 'TokenController@postLabourPayment');





	Route::any('/user/labour-slips', 'TokenController@labourSlips');
	Route::get('/user/print-loading-slip/{loading_id}', 'TokenController@printLoadingSlip');

	Route::post('/user/token/tax-invoice/{token_id}', 'TokenController@printTaxInvoice');
	Route::get('/user/freight-payment', 'TokenController@freightPayment');
	Route::post('/user/freight-payment', 'TokenController@updateFreightPayment');
	Route::get('/user/labour-payment', 'TokenController@labourPayment');
	Route::post('/user/labour-payment', 'TokenController@payLabour');


	/*-------Token Generation-------*/



	/*-------Product Unloading from truck-------*/
	Route::any('/user/product-unloadings', 'UnloadingController@productUnloadings');
	Route::any('/user/canceled-unloadings', 'UnloadingController@canceledUnloadings');
	Route::any('/user/direct-unloadings', 'UnloadingController@directUnloadings');
	Route::any('/user/direct-unloading-labour-slips', 'UnloadingController@directUnloadingLabourSlips');
	Route::any('/user/unloading-labour-slips', 'UnloadingController@unloadingLabourSlips');
	
	Route::get('/user/pay-direct-unloading-freight', 'UnloadingController@getDirectUnloadingFreightPayment');
	Route::post('/user/pay-direct-unloading-freight', 'UnloadingController@postDirectUnloadingFreightPayment');

	Route::get('/user/print-unloading-slip/{loading_id}', 'UnloadingController@printUnloadingSlip');
	Route::any('/user/export-product-unloadings/{master_rake_id}', 'UnloadingController@exportProductUnloadings');


	Route::get('/user/pay-unloading-freight', 'UnloadingController@getUnloadingFreightPayment');
	Route::post('/user/pay-unloading-freight', 'UnloadingController@postUnloadingFreightPayment');

	Route::get('/user/unloading-labour-payment', 'UnloadingController@unloadingLabourPayment');
	Route::post('/user/unloading-labour-payment', 'UnloadingController@payUnloadingLabour');

	/*-------Product Unloading from truck-------*/


	/*-------Wagon Unloading-------*/
	Route::any('/user/wagon-unloadings', 'WagonController@wagonUnloadings');
	Route::get('/user/pay-wagon-unloading-labours', 'WagonController@wagonUnloadingPayment');
	Route::post('/user/pay-wagon-unloading-labours', 'WagonController@payWagonUnloadingLabour');
	Route::get('/user/print-wagon-unloading-slip/{id}', 'WagonController@printWagonUnloadingSlip');

	/*-------Wagon Unloading-------*/


	/*-------Direct Labour Payment-------*/
	Route::get('/user/direct-labour-payment', 'DirectLabourPaymentController@getDirectLabourPayment');
	Route::post('/user/direct-labour-payment', 'DirectLabourPaymentController@postDirectLabourPayment');

	Route::get('/user/pay-direct-labour-payment', 'DirectLabourPaymentController@labourPayment');
	Route::post('/user/pay-direct-labour-payment', 'DirectLabourPaymentController@payLabour');
	
	Route::any('/user/direct-labour-payments', 'DirectLabourPaymentController@directLabourPayments');
	Route::get('/user/print-direct-labour-payment-slip/{id}', 'DirectLabourPaymentController@printDirectLabourPlaymetSlip');

	/*-------Direct Labour Payment-------*/


	/*-------Standardization &  Payment-------*/
	Route::get('/user/pay-standardization', 'StandardizationController@standardization');
	Route::any('/user/standard-labour-payments', 'StandardizationController@labourPayments');
	Route::post('/user/pay-standardization', 'StandardizationController@payStandardization');
	Route::any('/user/standardizations', 'StandardizationController@standardizations');
	Route::get('/user/print-standardization-slip/{id}', 'StandardizationController@printStandardizationSlip');
	/*-------Standardization &  Payment-------*/


	/*-------return received &  Payment-------*/
	
	Route::any('/user/returned-products', 'ReturnProductController@index');
	
	Route::get('/user/print-returned-product-slip/{id}', 'ReturnProductController@printReturnedProductSlip');

	Route::get('/user/pay-returned-product-labour', 'ReturnProductController@getLabourPayment');
	Route::post('/user/pay-returned-product-labour', 'ReturnProductController@postLabourPayment');

	Route::get('/user/pay-returned-product-freight', 'ReturnProductController@getFreightPayment');
	Route::post('/user/pay-returned-product-freight', 'ReturnProductController@postFreightPayment');

	/*-------return received &  Payment-------*/


	/*-------Warehouse to warehouse transfer-------*/
	Route::any('/user/warehouse-transfer-loadings', 'WarehouseTransferController@warehouse_transfer_loadings');
	Route::any('/user/warehouse-transfer-unloadings', 'WarehouseTransferController@warehouse_transfer_unloadings');
	
	Route::get('/user/print-warehouse-transfer-loading-slip/{id}', 'WarehouseTransferController@printWarehouseTransferLoadingSlip');
	Route::get('/user/print-warehouse-transfer-unloading-slip/{id}', 'WarehouseTransferController@printWarehouseTransferUnloadingSlip');

	Route::get('/user/pay-warehouse-transfer-loading-labour', 'WarehouseTransferController@getLoadingLabourPayment');
	Route::post('/user/pay-warehouse-transfer-loading-labour', 'WarehouseTransferController@postLoadingLabourPayment');

	Route::get('/user/pay-warehouse-transfer-unloading-labour', 'WarehouseTransferController@getUnloadingLabourPayment');
	Route::post('/user/pay-warehouse-transfer-unloading-labour', 'WarehouseTransferController@postUnloadingLabourPayment');

	Route::get('/user/pay-warehouse-transfer-freight', 'WarehouseTransferController@getFreightPayment');
	Route::post('/user/pay-warehouse-transfer-freight', 'WarehouseTransferController@postFreightPayment');


	/*-------Warehouse to warehouse transfer-------*/



	/*-------Stock Management-------*/


	Route::any('/user/product-company-register', 'StockController@productCompanyRegister');
	Route::any('/user/party-register', 'StockController@partyRegister');
	Route::any('/user/godown-register', 'StockController@godownRegister');

	Route::any('/user/company-godown-stock', 'StockController@companyGodownStock');
	Route::any('/user/parties-stock', 'StockController@partiesStock');

	Route::any('/user/warehouse-report', 'StockController@warehouse_report');
	Route::any('/user/arrived-stock', 'StockController@arrived_stock');

	Route::post('/user/arrived-stock', 'StockController@post_arrived_stock');
	Route::any('/user/warehouse-daywise-report', 'StockController@warehouse_daywise_report');
	Route::any('/user/stock-report', 'StockController@stock_report');
	Route::any('/user/buffer-godown-report', 'StockController@buffer_godown_report');
	Route::any('/user/buffer-report', 'StockController@buffer_report');
	Route::get('/user/daily-stock-report', 'StockController@daily_stock_report');
	Route::any('/user/party-inventory', 'StockController@partyInventory');
	Route::any('/user/other-stock', 'StockController@otherStock');
	Route::get('/user/export-daily-stock-report', 'StockController@export_daily_stock_report');
	Route::any('/user/payment-rebate-report', 'StockController@paymentRebateReport');
	Route::any('/user/monthly-rebate-report', 'StockController@monthlyRebateReport');
	Route::any('/user/bank-statements', 'StockController@bankStatements');

	Route::any('/user/warehouse-loading-slips', 'StockController@warehouseLoadingSlips');
	Route::any('/user/warehouse-labour-slips', 'StockController@warehouseLabourSlips');

	Route::get('/user/opening-stock', 'StockController@opening_stock');
	Route::any('/user/party-opening-stock', 'StockController@partyOpeningStock');

	/*-------Stock Management-------*/




	Route::any('/user/loading-slip-invoices', 'InvoiceController@invoices');
	Route::get('/user/generate-loading-slip-invoice', 'InvoiceController@generateLoadingSlipInvoice');
	Route::post('/user/generate-loading-slip-invoice', 'InvoiceController@saveLoadingSlipInvoice');
	Route::post('/user/generate-multiple-loading-slip-invoice', 'InvoiceController@saveMultipleLoadingSlipInvoice');
	Route::get('/user/loading-slip-details/{loading_slip_id}', 'InvoiceController@loadingSlipDetails');
	Route::post('/user/save-loading-invoice-payment', 'PaymentController@saveLoadingInvoicePayment');
	Route::get('/user/loading-slip-invioce-payment-details/{loading_slip_id}', 'PaymentController@loadingSlipInvoicePaymentDetails');


	Route::get('/user/generate-manual-invoice', 'InvoiceController@generateManualInvoice');
	Route::post('/user/generated-manual-invoice', 'InvoiceController@printTaxInvoice');
	Route::post('/user/save-generated-invoice', 'InvoiceController@postGeneratedInvoice');

	Route::any('/user/company-di', 'InvoiceController@companyDi');
	Route::any('/user/pending-company-di', 'InvoiceController@pendingCompanyDi');
	Route::get('/user/generate-company-di', 'InvoiceController@getGenerateCompanyDi');

	Route::any('/user/company-invoice-ledger', 'InvoiceController@companyInvoiceLedger');

	Route::post('/user/save-company-di', 'InvoiceController@postGenerateCompanyDi');
	Route::post('/user/save-company-di-payment', 'PaymentController@saveCompanyDiPayment');
	Route::post('/user/save-warehouse-di-payment', 'PaymentController@saveWarehouseDiPayment');
	Route::post('/user/save-company-di-discount', 'PaymentController@saveCompanyDiDiscount');

	Route::any('/user/warehouse-di', 'InvoiceController@WarehouseDi');
	Route::get('/user/generate-warehouse-di', 'InvoiceController@getGenerateWarehouseDi');
	Route::post('/user/save-warehouse-di', 'InvoiceController@postGenerateWarehouseDi');


	


	/*-------Finance Report-------*/
	Route::any('/user/rake-payments', 'ReportController@rakePaymentReport');
	Route::any('/user/export-rake-payments', 'ReportController@exportRakePaymentReport');


	Route::any('/user/daily-warehouse-payments', 'ReportController@dailyPaymentWarehouseReport');
	Route::any('/user/export-daily-warehouse-payments', 'ReportController@exportDailyPaymentWarehouseReport');

	Route::any('/user/freight-payment-report', 'ReportController@freightReport');
	Route::any('/user/rake-report', 'ReportController@rakeReport');
	Route::any('/user/labour-payment-report', 'ReportController@labourPaymentReport');
	Route::any('/user/warehouse-labour-payment-report', 'ReportController@warehouseLabourPaymentReport');

	Route::any('/user/direct-labour-payment-report', 'ReportController@directLabourPaymentReport');
	Route::any('/user/outstanding-report', 'ReportController@outstandingReport');
	Route::get('/user/outstating-total-amount-details/{dealer_id}/{product_category_id}', 'ReportController@outstandingTotalAmountDetails');
	Route::any('/user/outstating-remaining-amount-details/{dealer_id}/{product_category_id}/{from}/{to}', 'ReportController@outstandingRemainingAmountDetails');


	/*-------Finance Report-------*/





	/*-------Approvals-------*/
	Route::any('/user/rake-payments-approvals', 'ApprovalController@rakePaymentApprovals');
	Route::get('/user/approve-rake-payments-report/{id}', 'ApprovalController@approveRakePaymentReport');
	Route::post('/user/reject-rake-payments-report', 'ApprovalController@rejectRakePaymentReport');
	Route::get('/user/rake-payments-report-rejections/{id}', 'ApprovalController@rakePaymentReportRejection');

	Route::any('/user/daily-warehouse-payments-approvals', 'ApprovalController@dailyWarehousePaymentApprovals');
	Route::get('/user/approve-daily-warehouse-payments-report/{id}', 'ApprovalController@approveDailyWarehousePaymentReport');
	Route::post('/user/reject-daily-warehouse-payments-report', 'ApprovalController@rejectDailyWarehousePaymentReport');
	Route::get('/user/daily-warehouse-payments-report-rejections/{id}', 'ApprovalController@dailyWarehousePaymentReportRejection');

	/*-------Approvals-------*/


	/*--------SMS Manager----------------*/
	Route::any('/user/to-retailer-sms-report', 'SmsController@retailerSMSReport');
	Route::any('/user/to-dealer-sms-report', 'SmsController@dealerSMSReport');
	Route::any('/user/to-custom-sms-report', 'SmsController@customSMSReport');
	
	/*--------SMS Manager----------------*/

	/*------------Claims-----------------*/
	Route::any('/user/claims', 'ClaimController@claimReport');
	Route::post('/user/generate-claim', 'ClaimController@postCLaimData');
	Route::get('/user/generate-claim', 'ClaimController@claimsData');
	Route::any('/user/generate-claims', 'ClaimController@generateClaim');
	Route::post('/user/print-claims', 'ClaimController@printClaim');


	/*------------Claims-----------------*/

});

Route::group(['middleware' => ['auth']], function () {

	/*-------Backend process-------*/
	Route::get('/backend/', 'BackendController@index');
	Route::any('/backend/update-opening-inventory', 'BackendController@updateOpeningInvetory');
	Route::any('/backend/update-inventory', 'BackendController@updateInvetory');
	Route::get('/backend/unload-products', 'BackendController@unloadProducts');
	Route::any('/backend/update-rake-token', 'BackendController@updateRakeToken');
	Route::any('/backend/update-warehouse-token', 'BackendController@updateWarehouseToken');
	Route::any('/backend/update-loadings', 'BackendController@updateLoading');
	Route::any('/backend/update-labour-payments', 'BackendController@updateLabourPayment');
	Route::any('/backend/update-direct-labour-payments', 'BackendController@updateDirectLabourPayment');
	Route::any('/backend/update-wagon-unloadings', 'BackendController@updateWagonUnloading');
	Route::any('/backend/update-unloading-labour-payments', 'BackendController@updateUnloadingLabourPayment');
	Route::any('/backend/update-wtl-freight-payments', 'BackendController@updateWtloadingFreightPayment');
	Route::any('/backend/update-wtl-labour-payments', 'BackendController@updateWtloadingLabourPayment');
	Route::any('/backend/update-wtul-labour-payments', 'BackendController@updateWtUnloadingLabourPayment');
	Route::any('/backend/adjust-stock', 'BackendController@adjustStock');



	/*-------Backend process-------*/


	/*-------User Error Log-------*/
	Route::any('/log/user-error-logs', 'LogController@userErrorLogs');
	/*-------User Error Log-------*/


	/*-------Ajax Calls-------*/

	Route::get('/get-master-rake-details/{master_rake_id}', 'AjaxController@masterRakeDetails');
	Route::get('/get-company-stock-details/{product_company_id}/{warehouse_id}/{product_id}', 'AjaxController@companyStockDetails');
	Route::get('/get-dealer-stock-details/{dealer_id}/{warehouse_id}/{product_brand_id}/{product_id}', 'AjaxController@dealerStockDetails');
	Route::get('/get-product-company-details/{product_company_id}', 'AjaxController@productCompanyDetails');
	Route::get('/get-labour-details/{master_rake_id}', 'AjaxController@labourDetails');

	Route::get('/get-warehouse-parties/{warehouse_id}', 'AjaxController@warehouseParties');
	Route::get('/get-party-products/{warehouse_id}/{product_company_id}/{dealer_id}', 'AjaxController@getPartyProducts');
	Route::get('/warehouse-inventory-product-details/{warehouse_id}/{product_company_id}/{product_id}', 'AjaxController@warehouseInventoryProductDetails');

	Route::get('/get-token-details/{token_id}', 'AjaxController@tokenDetails');
	Route::get('/get-dealer-rake-allotment/{master_rake_id}/{dealer_id}', 'AjaxController@dealerRakeAllotmentDetails');
	Route::get('/get-alloted-product-details/{master_rake_id}/{dealer_id}/{product_id}', 'AjaxController@allotedProductDetails');
	Route::get('/get-loading-slip-details/{loading_slip_id}', 'AjaxController@loadingSlipDetails');
	Route::get('/get-unloading-slip-details/{unloading_slip_id}', 'AjaxController@unloadingSlipDetails');

	Route::get('/get-warehouse-transfer-loading-details/{loading_id}', 'AjaxController@warehouseTransferLoadingDetails');
	Route::get('/get-warehouse-transfer-unloading-details/{unloading_id}', 'AjaxController@warehouseTransferUnloadingDetails');

	Route::get('/get-labour-slip-details/{labour_slip_id}', 'AjaxController@labourSlipDetails');
	Route::get('/get-unloading-labour-slip-details/{labour_slip_id}', 'AjaxController@unloadingLabourSlipDetails');
	Route::get('/get-direct-labour-slip-details/{labour_slip_id}', 'AjaxController@directLabourSlipDetails');
	Route::get('/get-standardization-slip-details/{slip_id}', 'AjaxController@standardizationSlipDetails');
	Route::get('/get-return-slip-details/{slip_id}', 'AjaxController@returnSlipDetails');
	Route::get('/get-wagon-unloading-details/{unloading_slip_id}', 'AjaxController@wagonUnloadingSlipDetails');
	Route::get('/get-stock-token-details/{stock_token_id}', 'AjaxController@stockTokenDetails');
	Route::get('/get-stock-token-list-by-dealer-id/{dealer_id}/{warehouse_id}', 'AjaxController@stockTokenListByDealer');
	Route::get('/get-product-details-from-inventory/{dealer_id}/{warehouse_id}/{product_id}', 'AjaxController@productDetailsFromInventory');
	Route::get('/get-hsn-code-by-product/{product_id}', 'AjaxController@hsnCodeByProduct');
	Route::get('/get-company-invoice-details/{invoice_id}', 'AjaxController@companyInvoiceDetails');

	/*-------Ajax Calls-------*/
	/*-------Make Invoice For Multiple Orders-------*/
	Route::post('/dealer/get-invoice-orders', 'DealerController@get_invoice_product_list');
	Route::post('/dealer/save-invoice', 'DealerController@save_invoice')->name('save_invoice');
	
	Route::get('/dealer/approved-order/{id}', 'DealerController@get_approved_order_details');
	
	Route::get('/dealer/preview-invoice/{id}', 'DealerController@previewInvoice');

});




Route::get('/user/rake-payments-detail/{id}', 'ReportController@rakePaymentDetail');
Route::get('/user/daily-warehouse-payments-detail/{id}', 'ReportController@dailyWarehousePaymentDetail');
Route::get('/dealer/day-book', 'DealerController@dayBook');