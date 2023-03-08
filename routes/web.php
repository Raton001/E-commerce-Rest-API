<?php

use Illuminate\Support\Facades\Route;




// **********  finance  *********
  //Delivery Order
Route::get('/deliveryorder', 'FinanceController@deliveryOrder')->name('delivery.order');
Route::post('/getAjaxDeliveryOrder', 'FinanceController@getAjaxDeliveryOrder');
Route::get('/add-delivery-order','FinanceController@addDeliveryOrder')->name('add.delivery.order');
Route::get('/getDetails/{id}','FinanceController@getCusmoterDetails')->name('getDetails');
// Route::get('/choose-contact-person/{id}/{type}','FinanceController@chooseContactPerson')->name('choose.contact.person');
Route::post('/store-delivery-order', 'FinanceController@storeDeliveryOrder');

Route::get('/edit-DO/{uuid}', 'FinanceController@editDO');
Route::post('/save-updated-DO', 'FinanceController@saveUpdatedDO');

Route::post('/get-product-list', 'FinanceController@productList');
Route::post('/add-product-delivery-order', 'FinanceController@addProductDeliveryOrder');
Route::post('/update-product-delivery-order', 'FinanceController@updateProductDeliveryOrder');
Route::post('/delete-product-delivery-order', 'FinanceController@deleteProductDeliveryOrder');

Route::get('/download-delivery-order/{uuid}', 'FinanceController@downloadDeliveryOrder')->name('DO-Dolwnload');

Route::get('{role}/{module}/{sub}/invoice-generate/{id}/{companyid}','FinanceController@invoiceGenerate')->name('invoice.generate');
Route::get('{role}/{module}/{sub}/invoice-download/{uuid}', 'FinanceController@invoiceDownload');
Route::get('/invoice-update/{uuid}', 'FinanceController@invoiceupdate');
Route::post('/save-updated-invoice', 'FinanceController@saveUpdatedInvoice');

Route::get('/upload-DO/{uuid}', 'FinanceController@uploadDO');
Route::post('{role}/{module}/{sub}/DO-save', 'FinanceController@saveDo')->name('save-do');

Route::post('/calpaymentdue', 'FinanceController@calpaymentdue');
Route::get('/delete-delivery-order/{id}', 'FinanceController@deleteDeliveryOrder');
Route::get('/delivery-order-changelog/{uuid}','FinanceController@getDeliveryorderLog');




//Sales Return
Route::get('/sales-return', 'FinanceController@salesreturn')->name('sales.return');
Route::post('/getAjaxSalesReturn', 'FinanceController@getAjaxSalesReturn');
Route::get('/add-sales-return','FinanceController@addSalesReturn')->name('add.sales.return');
Route::get('/get-do-details/{id}','FinanceController@getDoDetails')->name('get.do.details');
Route::post('/create-sales-return', 'FinanceController@createSalesReturn');
Route::get('/{role}/{module}/{sub}/get-product-list/{do_id}/{parent_id}', 'FinanceController@getProductlist');
Route::post('/add-product','FinanceController@addProduct');

Route::get('/edit-sales-return/{uuid}', 'FinanceController@editSalesReturn');
Route::post('/save-updated-sales-return', 'FinanceController@saveUpdatedSalesReturn');

Route::post('/update-product-sales-return', 'FinanceController@updateProductSalesReturn');

Route::post('/delete-product', 'FinanceController@deleteSalesReturnProduct');

Route::get('/credit-note-download/{uuid}', 'FinanceController@creditDownload');
Route::get('/update-credit-note/{uuid}', 'FinanceController@updateCreditNote');
Route::get('/generate-sales-return-credit-note/{id}','FinanceController@generateSalesReturnCreditNote');
Route::get('/salesreturn-changelog/{uuid}','FinanceController@salesReturnLog');




// Return stock handling
Route::get('/return-stock', 'FinanceController@returnStockHandling')->name('return.stock');
Route::post('/getAjaxReturnStockHandling', 'FinanceController@getAjaxReturnStockHandling');


//Invoice Overview
Route::get('/sales-invoice-overview', 'FinanceController@salesInvoiceOverview')->name('sales.invoice.overview');
Route::post('/getAjaxSalesInvoiceOverview', 'FinanceController@getAjaxSalesInvoiceOverview');
Route::get('/delete-invoice/{id}', 'FinanceController@deleteInvoice');

// Sales Invoice
Route::get('/sales-invoice', 'FinanceController@salesInvoice')->name('sales.invoice');
Route::post('/getAjaxSalesInvoice', 'FinanceController@getAjaxSalesInvoice');
Route::get('/sales-invoice-download/{uuid}', 'SalesInvoiceController@downloadSalesInvoice');
Route::get('/sales-invoice-update/{uuid}',   'SalesInvoiceController@updateSalesInvoice');
Route::get('/sales-invoice-changelog/{uuid}','SalesInvoiceController@logSalesInvoice');
Route::post('/create-sales-invoice', 'SalesInvoiceController@createSalesInvoice');




//Service Invoice
Route::get('/service-invoice', 'FinanceController@serviceInvoice')->name('service.invoice');
Route::post('/getAjaxServiceInvoice', 'FinanceController@getAjaxServiceInvoice');
Route::get('/service-invoice-download/{uuid}', 'ServiceInvoiceController@downloadServiceInvoice');
Route::get('/service-invoice-changelog/{uuid}','ServiceInvoiceController@logServiceInvoice');


//Cash Sales Invoice
Route::get('/cash-sales-invoice', 'FinanceController@cashSalesInvoice')->name('cash.sales.invoice');
Route::post('/getAjaxCashSalesInvoice', 'FinanceController@getAjaxCashSalesInvoice');
Route::get('/cash-invoice-download/{uuid}', 'CashInvoiceController@downloadCashInvoice');
Route::get('/cash-sale-invoice-changelog/{uuid}','CashInvoiceController@logCashInvoice');
Route::get('/create-cash-sale-invoice', 'CashInvoiceController@createCashSalesInvoice')->name('create.cash.sale.invoice');
Route::post('/save-cash-sale-invoice', 'CashInvoiceController@saveCashSalesInvoice');
Route::post('/product-list-cash-sales', 'CashInvoiceController@productListCashSale');
Route::post('/add-product-cash-sale', 'CashInvoiceController@addProductCashSales');
Route::post('/delete-product-cash-sale', 'CashInvoiceController@deleteProductCashSales');
Route::post('/save-all', 'CashInvoiceController@saveAll');
Route::get('/edit-cash-sale-invoice/{uuid}', 'CashInvoiceController@editCashSaleInvoice');



//reseller Invoice
Route::get('/reseller-invoice', 'FinanceController@resellerInvoice')->name('reseller.invoice');
Route::post('/getAjaxResellerInvoice', 'FinanceController@getAjaxResellerInvoice')->name('soa');
Route::get('/reseller-invoice-download/{uuid}', 'ResellerInvoiceController@downloadResellerInvoice');
Route::get('/reseller-invoice-changelog/{uuid}','ResellerInvoiceController@logResellerInvoice');


//************** statement of account (SOA) ******************
//Customer SOA
Route::get('/customer-soa', 'SOAController@customerSOA')->name('customer.soa');
Route::post('/get-customer-soa', 'SOAController@getCustomerSOA');
Route::get('/download-customer-soa/{id}/{searchyear}/{searchmonth}/{company_id}', 'SOAController@downloadCustomerSOA');

//Member SOA
Route::get('/member-soa', 'SOAController@memberSOA')->name('member.soa');
Route::post('/get-member-soa', 'SOAController@getMemberSOA');
Route::get('/download-member-soa/{id}/{searchyear}/{searchmonth}', 'SOAController@downloadMemberSOA');


//Vendor SOA
Route::get('/vendor-soa', 'SOAController@vendorSOA')->name('vendor.soa');
Route::post('/get-vendor-soa', 'SOAController@getVendorSOA');
Route::get('/download-vendor-soa/{id}/{searchyear}/{searchmonth}/{company_id}', 'SOAController@downloadVendorSOA');



//************** purchase ******************
//quotation
Route::get('/quotation',  'PurchaseController@quotation')->name('quotation');
Route::post('/getAjaxQuotation', 'PurchaseController@getAjaxQuotation');
Route::get('/add-quotation','PurchaseController@addQuotation')->name('add.quotation');
Route::post('/create-quotation', 'PurchaseController@createQuotation');
Route::post('/add-product-quotation', 'PurchaseController@addProductQuotation');
Route::post('/update-product-list-quotation', 'PurchaseController@updateProductListQuotation');
Route::post('/delete-product-quotation', 'PurchaseController@deleteProductQuotation');
Route::get('/download-quotation/{uuid}', 'PurchaseController@downloadQuotation');
Route::get('/log-quotation/{uuid}', 'PurchaseController@logQuotation');
Route::get('/update-quotation/{uuid}', 'PurchaseController@updateQuotation');
Route::post('/save-updated-quotation', 'PurchaseController@saveupdatedQuotation');


//purchase order
Route::get('/purchase-order',  'PurchaseController@purchaseOrder')->name('purchase.order');
Route::post('/getAjaxPurchaseOrder', 'PurchaseController@getAjaxPurchaseOrder');
Route::get('/add-purchase-order','PurchaseController@addPurchaseOrder')->name('add.purchase.order');
Route::get('/getVendorDetails/{id}', 'PurchaseController@getVendorDetails')->name('getVendorDetails');
Route::post('/create-purchase-order', 'PurchaseController@createPurchaseOrder');
Route::post('/addproduct-purchase-order', 'PurchaseController@addProductPurchaseOrder');
Route::post('/update-product-PO', 'PurchaseController@updateProductPurchaseOrder');
Route::post('/delete-product-PO', 'PurchaseController@deleteProductPurchaseOrder');
Route::get('/download-PO/{uuid}', 'PurchaseController@downloadPurchaseOrder');
Route::get('/log-PO/{uuid}', 'PurchaseController@logPO');
Route::get('/update-PO/{uuid}', 'PurchaseController@updatePO');
Route::post('/save-updated-PO', 'PurchaseController@saveupdatedPO');

Route::post('/add-product-credit', 'CreditNoteController@addProductcredit');
Route::post('/update-product-credit', 'CreditNoteController@updateProductcredit');
Route::post('/delete-product-credit', 'CreditNoteController@deleteProductcredit');
Route::post('/add-product-debit', 'DebitNoteController@addProductdebit');
Route::post('/update-product-debit', 'DebitNoteController@updateProductdebit');
Route::post('/delete-product-debit', 'DebitNoteController@deleteProductdebit');
