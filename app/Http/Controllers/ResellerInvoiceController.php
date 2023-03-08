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

class ResellerInvoiceController extends Controller
{
    public function downloadResellerInvoice($uuid)
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

        return view('finance.resellerInvoice.download-reseller-invoice',compact('invoicedata', 'companydata', 'paramcustomer', 'stateparam', 'daysparam', 'user', 'items','note'));
      }


      //log

      public function logResellerInvoice($uuid)
      {
         $searchModel = DB::select("SELECT al.data_uuid AS uuid,al.action_datetime,al.action_type,m.title, m.status,m.url,u.name,u.role,pl.label
                                     FROM log al
                                     LEFT JOIN axis_user u ON al.user_id = u.id
                                     LEFT JOIN menu m ON al.menu_id = m.id
                                     LEFT JOIN param_list pl ON u.role = pl.code
                                     WHERE al.data_uuid='$uuid' ORDER BY al.action_datetime DESC");

       return view('finance.resellerInvoice.log',['clog'=>$searchModel,'stores'=> $this->stores(),'skipHeader' => 1]);
      }


}
