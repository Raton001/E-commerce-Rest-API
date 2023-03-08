<?php

namespace App\Http\Controllers;

use App\AxisUser;
use App\Company;
use App\Creditnote;
use App\CreditnoteItem;
use App\Customer;
use App\DeliveryOrder;
use App\DeliveryOrderItem;
use App\Grn;
use App\GrnParent;
use App\Invoice;
use App\InvoiceItem;
use App\Log;
use App\Notes;
use App\ParamList;
use App\Product;
use App\StockLog;
use DateTime;
use Facade\FlareClient\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;
use PhpOffice\PhpSpreadsheet\Helper\Html;
//use PhpOffice\PhpSpreadsheet\Writer\Pdf;
use Ramsey\Uuid\Uuid;
//use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpSpreadsheet\Writer\Pdf;

use function React\Promise\Stream\first;

use Illuminate\Http\Request;

class CashInvoiceController extends Controller
{
    public $menu_id = 59; //cash Invoice Menu


    public function downloadCashInvoice($uuid)
      {
        $notes = new Notes();

        $invoicedata= DB::table('invoice')->where('uuid',$uuid)->first(); //model

        $companydata = DB::table('company')->find($invoicedata->com_id); //modelcom


        $stateparam = DB::table('param_list')->select('label')->where(['cat_id' => 3 , 'code' =>$companydata->state])->first();

        $paramcustomer = DB::table('param_list')->select('label')->where(['cat_id' => 3 , 'code' =>$invoicedata->customer_state])->first();

        $daysparam = DB::table('param_list')->select('label')->where(['cat_id' => 26 , 'code' =>$invoicedata->term])->first();


        $user= DB::table('axis_user')->where('id',$invoicedata->sales_pic)->first();

        $items = DB::table('invoice_item')->where('doc_id', $invoicedata->id)->orderBy('id', 'ASC')->get(); //modelItem

        $companynote = DB::table('company')->find($companydata->id);

        $notes = DB::table('notes')->select('notes')->where('type','Invoice')->first();


        $output ='';

        if($notes !== null){
            $output = $notes->notes;
            $output = str_replace("#COMPANY_NAME#",$companydata ->name, $output);
            $output = str_replace("#COMPANY_EMAIL#", $companydata->email, $output);
            $output = str_replace("#COMPANY_BANK_NAME#", $companydata->bank_name, $output);
            $output = str_replace("#COMPANY_BANK_ACCNO#",$companydata->bank_acc, $output);
        }
        $note= $output;

        // $pdf = PDF::loadView('finance.deliveryOrder.invoicedownload', compact('invoicedata', 'companydata', 'stateparam', 'daysparam', 'user', 'items','note'));

        // return $pdf->setPaper('a4', 'landscape')->download('Invoice ' .$invoicedata->invoice_no . '.pdf');

        return view('finance.cashSalesInvoice.download-cash-invoice',compact('invoicedata', 'companydata', 'paramcustomer', 'stateparam', 'daysparam', 'user', 'items','note'));
      }

      //log
      public function logCashInvoice($uuid)
      {
         $searchModel = DB::select("SELECT al.data_uuid AS uuid,al.action_datetime,al.action_type,m.title, m.status,m.url,u.name,u.role,pl.label
                                     FROM log al
                                     LEFT JOIN axis_user u ON al.user_id = u.id
                                     LEFT JOIN menu m ON al.menu_id = m.id
                                     LEFT JOIN param_list pl ON u.role = pl.code
                                     WHERE al.data_uuid='$uuid' ORDER BY al.action_datetime DESC");

       return view('finance.cashSalesInvoice.log',['clog'=>$searchModel,'stores'=> $this->stores(),'skipHeader' => 1]);
      }


 //create cash sale invoice

 public function createCashSalesInvoice(Request $request)
 {

  //company list
  $companies = DB::table('company AS c')
                ->select('c.id AS company_id','c.name AS name')
                ->leftJoin('company_access AS a' ,'a.com_id' ,'=' ,'c.id')
                ->WHERE( 'a.user_id', '=' , 38 )
                ->orderBy('c.name', 'ASC')->get();


 //invoice term
   $invoiceterm =   DB::table('param_list')->select('label','code')->where(['cat_id' => 26, 'status' => 1])->orderBy('sort' , 'ASC')->first();


//sales pic
 $sales_pic= DB::table('axis_user')
                ->select('id AS sales_pic', 'name')
                ->orderBy('name', 'ASC')->get();
//customers
 $customers = DB::table('customer')
                ->select('id AS customer_id', 'name')
                ->orderBy('name', 'ASC')->get();
 //days
  $daysparam = DB::table('param_list')->select('code','label')->where(['cat_id' => 26, 'status' => 1])->orderBy('sort' , 'ASC')->get();



//state
 $stateparam = DB::table('param_list')->select('code','label')->where(['cat_id' => 3 , 'status' => 1])->orderBy('sort' , 'ASC')->get();


    return view('finance.cashSalesInvoice.create-cash-invoice',
                ['stores' => $this->stores(),
                'companies'=> $companies,
                'invoiceterm'=> $invoiceterm,
                'sales_pic'=>$sales_pic, 'customers'=>$customers,
                'states'=>$stateparam, 'daysparam'=>$daysparam,
                'title' => 'Create Cash Sale Invoice',
                'form' => 'Analysis', 'skipHeader' => 1]);

}

//save cash sale invoice
 public function saveCashSalesInvoice(Request $request)
  {
    $Company = new Company();
    $model = new Invoice();

    $output='';
    $footer ='';

    $request->validate([
        'company_id' => 'required',
        'customer_name'=>'required',
        'sales_pic'=>'required',

        ]);

    $countdoc = Invoice::where('com_id', $request->company_id)->where('type', '!=', 'transfer')->count() + 1;

    $model->invoice_no = $Company->getshortcode($request->company_id) . '/INV/' . date("y") . '/' . sprintf("%05d", $countdoc);


    $model->type = 'cash';
    $model->doc_dt = $request->doc_dt;
    $model->created_dt =  date("Y-m-d H:i:s",  strtotime("Now"));
    $model->created_by = 38; // should be auth user id
    $model->updated_dt =  date("Y-m-d H:i:s",  strtotime("Now"));
    $model->updated_by = 38; // should be auth user id
    $model->uuid = Uuid::uuid4();
    $model->term = $request->term;
    $model->payment_due =$request->payment_due;
    $model->com_id= $request->company_id;
    $model->sales_pic= $request->sales_pic;
    $model->customer_id= $request->cust_id;
    $model->customer_name= $request->customer_name;
    $model->customer_address1= $request->address;
    $model->customer_address2= $request->address2;
    $model->customer_city= $request->city;
    $model->customer_postcode= $request->postcode;
    $model->customer_state= $request->state;
    $model->customer_pic= $request->contactperson;
    $model->customer_phone= $request->contactno;
    $model->remarks= $request->remark;
    $model->grandtotal = '0.00';

    if($model->save()){
        $Log = new Log();
        $Log->insertlog($this->menu_id, 'create', $model->uuid);
    }

    if($model->save()){
        $output = '<table class="table table-bordered table-striped" id="wrapper_product_list">
        <thead>
            <tr class="thead-dark">
                <th width="1%">Action</th>
                <th>Description</th>
                <th>Stock Owner</th>
                <th width="10%">Qty</th>
                <th width="10%">UoM</th>
                <th width="10%">Unit Price (RM)</th>
                <th width="10%">Discount (%)</th>
                <th width="10%">Total (RM)</th>

            </tr>
        </thead>
        <tbody>';

       $output .= '</tbody>';

           $output .= '<tfoot>
                    <tr style="height:60px;">
                        <th colspan="7" class="text-right" style="vertical-align: middle;">Sub Total</th>
                        <td style="vertical-align: middle;"><span id="subtotal"></span></td>
                    </tr>
                    <tr>
                        <th colspan="6" class="text-right" style="vertical-align: middle;">
                            Tax
                            <span data-toggle="kt-tooltip" data-placement="top" data-original-title="Fill the TAX input if needed only">
                                <i class="fa fa-info-circle"></i>
                            </span>
                        </th>
                    <td class="pb-0">
                        <div class="form-group-custom" onchange="updategrandtotal()"  >
                            <div class="input-group-append"><span class="input-group-text"> <input style="width:80%" type="text" id="invoice-tax" > %</span></div>
                        </div>
                    </td>
                    <td style="vertical-align: middle;"><span  id="taxvalue"></span></td>

                </tr>
            <tr style="height:60px;">
                <th colspan="7" class="text-right" style="vertical-align: middle;">Grand Total</th>
                 <td class="pb-0" id="invoice-grandtotal">
              </td>
         </tr>';
       '</tfoot>';
      $output .= '</table>';

    }

    $button = '<button type="submit" class="btn btn-primary mt-3  wow zoomIn"  onclick="saveall()" id="saveall">Save</button>';

    $button1 = '<button class="btn btn-outline-warning mt-3" type="button" data-toggle="modal" data-target="#Modal"  id="add" onclick="addproduct()" > Add Product </button>';
    $button2 = '<button class="btn btn-info mt-3 wow zoomIn" onclick="view()">View Invoice</button>';

  return response()->json([$model, $output ,$button, $button1, $button2]);

  }


 //product list cash sale
 public function productListCashSale(Request $request)
 {
        $com_id = $request->com_id;
        $doc_id = $request->doc_id;
        $output = '';
        $count = 0;

        $modelProduct = Product::where('com_id', $com_id)->where('status' , 1)->orderBy('name', 'ASC')->get();
        //dd($modelProduct);
        if (!empty($modelProduct)) {
            $output .= '<div class="row">';
            foreach ($modelProduct as $rowProduct) {
                if (($modelItem = InvoiceItem::find(['doc_id' => $doc_id, 'descr' => $rowProduct->id])) != null) {
                    $count++;
                    $output .= '<div class="col-6">
                    <label class="kt-checkbox">
                    <input type="checkbox" value="' . $rowProduct->id . '" class="product_id"> ' . $rowProduct->name . '
                    <span></span>
                    </label>
                    </div>';
                }
            }
            $output .= '</div><div class="kt-font-bold kt-font-danger" id="error_message">Please select at lease one (1) product</div>';
        }
        if ($count == 0)
            $output = 'All product has been added to the Invoice or no product found under this company';
        return $output;
 }



 //add product cash sale
 public function addProductCashSales(Request $request)
 {
    $output = '';
    $Product = new Product();
    $ParamList = new ParamList();
    $output = '';

    if (!empty($request->items)) {
        foreach ($request->items as $item) {

            $model = new InvoiceItem();
            $model->doc_id = $request->doc_id;
            $model->descr = $item;
            $model->uom = $ParamList->getlabel(9, $Product->getuom($model->descr));
            $model->quantity = '0';
            $model->unit_price = $Product->getprice($model->descr);
            $model->discount = '0';
            $model->total = '0.00';
            $model->created_dt = date("Y-m-d H:i:s",  strtotime("Now"));
            $model->created_by = 38; // should be auth user id
            $model->updated_dt = date("Y-m-d H:i:s",  strtotime("Now"));
            $model->updated_by = 38; // should be auth user id
            $model->owner_type = 'company';
            $model->owner_id = $request->company_id;

            if ($model->save()) {

                //insert into stock_log
                $modelStockLog = new StockLog();
                $modelStockLog->product_id = $item;
                $modelStockLog->action_type = 'stockout';
                $modelStockLog->table_related = 'invoice_item';
                $modelStockLog->data_id = $model->id;
                $modelStockLog->action_by = 38; // should be auth user id
                $modelStockLog->action_datetime = date("Y-m-d H:i:s",  strtotime("Now"));
                $modelStockLog->owner_type = $model->owner_type;
                $modelStockLog->owner_id = $model->owner_id;

                if ($modelStockLog->save()) {
                    $output .= '<tr id="rowItem_' . $model->id . '">
                    <td align="center" style="vertical-align:middle;"><i class="fas fa-trash-alt font-danger" style="cursor:pointer;" title="Delete product" onclick="deleteproduct(' . $model->id . ')"></i></td>
                    <td style="vertical-align:middle;">
                    <input type="hidden" value="' . $model->id . '" name="item_id[]">
                    ' . $Product->getname($model->descr) . '
                    </td>
                    <td>
                    <select class="form-control kt-selectpicker" name="item_ownertype_' . $model->id . '">
                    <option value="company" selected>Company</option>
                    <option value="vendor">Vendor</option>
                    </select>
                    </td>
                    <td><input type="text" class="form-control" value="' . $model->quantity . '" name="item_quantity_' . $model->id . '" onchange="updatetotal(' . $model->id . ')"></td>
                    <td><input type="text" class="form-control" value="' . $model->uom . '" name="item_uom_' . $model->id . '"></td>
                    <td><input type="text" class="form-control" value="' . $model->unit_price . '" name="item_unitprice_' . $model->id . '" onchange="updatetotal(' . $model->id . ')"></td>
                    <td><input type="text" class="form-control" value="' . $model->discount . '" name="item_discount_' . $model->id . '" onchange="updatetotal(' . $model->id . ')"></td>
                    <td><input type="text" class="form-control item_total" value="' . $model->total . '" name="item_total_' . $model->id . '"></td>
                    </tr>';
                }
            }
        }
    }

    return $output;

 }







 //delete product cash sales

 public function deleteProductCashSales(Request $request)
 {
    $id = $request->id;
    $status = 'failed';
    $data = InvoiceItem::where('id',$id)->first();
    if ($data->delete()) {
        $stocklog = StockLog::where(['action_type' => 'stockout', 'table_related' => 'invoice_item', 'data_id' => $id])->first();
        if ($stocklog->delete())
            $status = 'success';
    }
    return $status;
 }


//save or update all

public function saveAll(Request $request)
{
    parse_str($request->value, $output);
    $uuid = $request->uuid;

    $model = Invoice::where('uuid',$uuid)->first();
    $model->updated_dt = date("Y-m-d H:i:s,",  strtotime("Now"));
    $model->updated_by = 38; // should be Auth user id
    $model->term = $request->term;
    $model->payment_due =$request->payment_due;
    $model->com_id= $request->company_id;
    $model->sales_pic= $request->sales_pic;
    $model->customer_id= $request->cust_id;
    $model->customer_name= $request->customer_name;
    $model->customer_address1= $request->address;
    $model->customer_address2= $request->address2;
    $model->customer_postcode= $request->postcode;
    $model->customer_city= $request->city;
    $model->customer_state= $request->state;
    $model->customer_pic= $request->contactperson;
    $model->customer_phone= $request->contactno;
    $model->remarks= $request->remark;
    $model->grandtotal = $request->grandtotal;
    $model->tax = $request->tax;

    if ($model->save()) {

        if (!empty($output['item_id'])) {
            $item_arr = $output['item_id'];
            foreach ($item_arr as $item_id) {
                if (($modelItem = InvoiceItem::find($item_id)) !== null) {
                    $modelItem->quantity = $output['item_quantity_' . $item_id];
                    $modelItem->uom = $output['item_uom_' . $item_id];
                    $modelItem->unit_price = $output['item_unitprice_' . $item_id];
                    $modelItem->discount = $output['item_discount_' . $item_id];
                    $modelItem->total = $output['item_total_' . $item_id];
                    $modelItem->owner_type = $output['item_ownertype_' . $item_id];

                    $owner_id = '';
                    if ($modelItem->owner_type == 'company')
                        $owner_id = $model->com_id;
                    if ($modelItem->owner_type == 'vendor') {
                        $Product = new Product();
                        $owner_id = $Product->getvendor($modelItem->descr);
                    }
                    $modelItem->owner_id = $owner_id;
                    if ($modelItem->save()) {
                        //update data in stock log
                        $modelStockLog = StockLog::where(['table_related' => 'invoice_item', 'data_id' => $modelItem->id, 'product_id' => $modelItem->product_id])->first();
                        if ($modelStockLog != null) {
                            $modelStockLog->owner_type = $modelItem->owner_type;
                            $modelStockLog->owner_id = $modelItem->owner_id;
                            $modelStockLog->save();
                        }
                    }
                }
            }
        }

        $Log = new Log();
        $Log->insertlog($this->menu_id, 'update', $model->uuid);
    }

    return response()->json("success");

}


//edit cash sale invoice
public function editCashSaleInvoice(Request $request)
{
    $uuid = $request->uuid;

 $model = Invoice::where('uuid', $uuid)->first();

//company list
 $companies = DB::table('company AS c')
                ->select('c.id AS company_id','c.name AS name')
                ->leftJoin('company_access AS a' ,'a.com_id' ,'=' ,'c.id')
                ->WHERE( 'a.user_id', '=' , 38 )
                ->orderBy('c.name', 'ASC')->get();
 //company name
 $company = DB::table('company AS c')
                ->select('c.id AS company_id','c.name AS name')
                ->leftJoin('company_access AS a' ,'a.com_id' ,'=' ,'c.id')
                ->WHERE( 'a.user_id', '=' , 38 )
                ->where('c.id', $model->com_id)->first();


 //customers
 $customers = DB::table('customer')
                ->select('id AS customer_id', 'name')
                ->orderBy('name', 'ASC')->get();

 //invoice term
 $invoiceterm =   DB::table('param_list')->select('label','code')->where(['cat_id' => 26, 'status' => 1])->orderBy('sort' , 'ASC')->first();

 //days
 $daysparam = DB::table('param_list')->select('code','label')->where(['cat_id' => 26, 'status' => 1])->orderBy('sort' , 'ASC')->get();


 //sales pic
 $sales_pic= DB::table('axis_user')
                ->select('id AS sales_pic', 'name')
                ->orderBy('name', 'ASC')->get();
 //user
 $user= DB::table('axis_user')
                ->select('id', 'name')
                ->where('id', $model->sales_pic)->first();

return view('finance.cashSalesInvoice.edit-cash-sale-invoice',
                ['stores' => $this->stores(),
                'companies'=> $companies,
                'invoiceterm'=> $invoiceterm,
                'sales_pic'=>$sales_pic, 'customers'=>$customers,
                'daysparam'=>$daysparam,
                'model'=>$model,
                'user'=>$user,
                'company'=>$company,
                'title' => 'Update Cash Sales Invoice',
                'form' => 'Analysis', 'skipHeader' => 1]);

}




}
