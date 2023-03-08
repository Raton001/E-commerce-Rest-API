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
use App\Log;
use App\Notes;
use App\ParamList;
use App\Product;
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
use NumberFormatter;
use PhpOffice\PhpSpreadsheet\Reader\Xml\Style\NumberFormat;

class SalesInvoiceController extends Controller
{
    public $menu_id = 52; //sales invoice


    //download sales Invoice
    public function downloadSalesInvoice($uuid)
      {

        $notes = new Notes();

        $invoicedata= DB::table('invoice')->where('uuid',$uuid)->first(); //model

        $companydata = DB::table('company')->find($invoicedata->com_id); //modelcom


        $stateparam = DB::table('param_list')->select('label')->where(['cat_id' => 3 , 'code' =>$companydata->state])->first();

        $paramcustomer = DB::table('param_list')->select('label')->where(['cat_id' => 3 , 'code' =>$invoicedata->customer_state])->first();

        $daysparam = DB::table('param_list')->select('label')->where(['cat_id' => 26 , 'code' =>$invoicedata->term])->first();

        $user= DB::table('axis_user')->where('id',$invoicedata->sales_pic)->first();

        $items = DB::table('delivery_order_item')->where('doc_id', $invoicedata->do_no)->orderBy('id', 'ASC')->get(); //modelItem

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

        return view('finance.salesInvoice.download-sales-invoice',compact('invoicedata', 'companydata', 'paramcustomer', 'stateparam', 'daysparam', 'user', 'items','note'));

      }


      //Create Sales Invoice

      public function createSalesInvoice(Request $request)
      {
            $Company = new Company();
            $DeliveryOrder = new DeliveryOrder();
            $Customer = new Customer();
            $output = 'failed';
            $modeldo = DeliveryOrder::find($request->do_no);
            //dd($modeldo);
         if($modeldo != null){
            $model = new Invoice();
            $model->uuid = Uuid::uuid4();
            $model->com_id = $DeliveryOrder->getcompanyid($request->do_no);
            //dd($model->com_id);
            $model->do_no = $request->do_no;
            $model->type = 'do';
            $model->grandtotal = number_format((float) DeliveryOrderItem::where(['doc_id' => $model->do_no])->sum('total'), 2, '.', '');
            $model->created_dt = date("Y-m-d H:i:s");
            $model->created_by = 38; //Auth User Id
            $model->updated_dt = date("Y-m-d H:i:s");
            $model->updated_by = 38; // Auth User Id
            $countdoc = (int) Invoice::where(['com_id' => $model->com_id])->where('type','!=', 'transfer')->count() + 1;
            $model->invoice_no = $Company->getshortcode($model->com_id) . '/INV/' . date("y") . '/' . sprintf("%05d", $countdoc);

            $model->sales_pic = $modeldo->sales_pic;
            $model->doc_dt = $modeldo->doc_dt;
            $model->term = 'end_60';
            $model->payment_due = date('Y-m-t', strtotime($model->doc_dt . ' + 60 days'));
            $model->po_ref = $modeldo->po_ref;

            if ($Customer->getcustomertype($modeldo->customer_id) == 'parent') {
                $model->customer_id = $modeldo->customer_id;
                $model->customer_name = $modeldo->customer_name;
                $model->customer_address1 = $modeldo->customer_address1;
                $model->customer_address2 = $modeldo->customer_address2;
                $model->customer_postcode = $modeldo->customer_postcode;
                $model->customer_city = $modeldo->customer_city;
                $model->customer_state = $modeldo->customer_state;
                $model->customer_pic = $modeldo->customer_pic;
                $model->customer_phone = $modeldo->customer_phone;
            } else {
                if (($modelcustomer = Customer::find($Customer->getparentid($modeldo->customer_id))) !== null) {
                    $model->customer_id = $modelcustomer->id;
                    $model->customer_name = $modelcustomer->name;
                    $model->customer_address1 = $modelcustomer->address1;
                    $model->customer_address2 = $modelcustomer->address2;
                    $model->customer_postcode = $modelcustomer->postcode;
                    $model->customer_city = $modelcustomer->city;
                    $model->customer_state = $modelcustomer->state;
                    $model->customer_pic = $modelcustomer->contact_name;
                    $model->customer_phone = $modelcustomer->contact_no;
                }
            }

            if ($model->save()) {
                $Log = new Log();
                $Log->insertlog($this->menu_id, 'create', $model->uuid);
                $output = 'success';
            }

         }
         return $output;

      }


      //update sales Invoice
    public function updateSalesInvoice()
     {

     }



     //log sales Invoice
    public function logSalesInvoice($uuid)
     {
        $searchModel = DB::select("SELECT al.data_uuid AS uuid,al.action_datetime,al.action_type,m.title, m.status,m.url,u.name,u.role,pl.label
                                    FROM log al
                                    LEFT JOIN axis_user u ON al.user_id = u.id
                                    LEFT JOIN menu m ON al.menu_id = m.id
                                    LEFT JOIN param_list pl ON u.role = pl.code
                                    WHERE al.data_uuid='$uuid' ORDER BY al.action_datetime DESC");

      return view('finance.salesInvoice.log',['clog'=>$searchModel,'stores'=> $this->stores(),'skipHeader' => 1]);
     }



}
