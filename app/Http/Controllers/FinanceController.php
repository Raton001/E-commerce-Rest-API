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
use App\StockLog;
use DateTime;
use Facade\FlareClient\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use PhpOffice\PhpSpreadsheet\Helper\Html;
//use PhpOffice\PhpSpreadsheet\Writer\Pdf;
use Ramsey\Uuid\Uuid;
//use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpSpreadsheet\Writer\Pdf;

use function React\Promise\Stream\first;

class FinanceController extends Controller
{

    public $menu_id = 46; //creditnote

    public $menu_id2= 54; //invoice

    public $menu_id3= 48; //Grnparent

    public $menu_id4 = 52; //delete invoice

    public $menu_id5 = 45; //delivery Order






    //**********************Delivery Order Starts Here********************/

    //delivery Order
    public function deliveryOrder ( Request $request)
    {
        if ($request->route('account'))
         {
             $account = $request->route('account');
         }

          $companies = DB::table('company AS c')
                       ->select('c.id', 'c.logo', 'c.name')
                       ->leftJoin('company_access AS a' ,'a.com_id' ,'=' ,'c.id')
                       ->WHERE( 'a.user_id', '=' , 38 ) ->orderBy('c.name', 'ASC')->get(); //id hard coded need to change

         $keys = ['Draft', 'In-Transit', 'Consigned'];
         return view('finance.deliveryOrder.deliveryorder', ['stores' => $this->stores() , 'keys' => $keys, 'page' => 'finance.deliveryOrder.cde-deliveryorder', 'title' => 'delivery order', 'form' => 'Analysis', 'skipHeader' => 1, 'companies' =>$companies]);
    }

   //set ajax for delivery order
    public function getAjaxDeliveryOrder(Request $request)
    {
        $column = [];
        static $count = 0;

        $input = $request->all();

         $query= 'SELECT  DISTINCT deo.id AS id, deo.doc_dt, deo.uuid, deo.doc_no, deo.status, c.id AS companyid, c.name AS companyname,
                  deo.customer_name, deo.sales_pic, u.name AS username, deo.uploaded_file
                  FROM delivery_order deo
                  LEFT JOIN company_access ca ON ca.com_id=deo.company_id
                  LEFT JOIN company c ON deo.company_id=c.id
                  LEFT JOIN delivery_order_item di ON deo.id=di.doc_id
                  LEFT JOIN product p ON di.product_id=p.id
                  LEFT JOIN axis_user u ON u.id=deo.sales_pic
                  ORDER BY deo.id DESC';

         $data =DB::select($query);

        foreach ($data as $value)
        {
            $count++;
            $column[$value->status][] = $this->setRowDeliveryOrder($value, $count);
        }
        return ['data' => $column];

    }


    // set row for delivery order

    public function setRowDeliveryOrder($value, $count)
    {
        ob_start();
        ?>
     <tr>
        <td><?php echo $count; ?></td>
        <td>
            <fieldset>
                <div class="checkbox checkbox-info checkbox-glow">
                    <input type="checkbox" name="data[]" id="ship_<?php echo $value->id ?>" value="<?php echo $value->id ?>" >
                    <label for="ship_<?php echo $value->id ?>"></label>
                </div>
            </fieldset>
        </td>

        <td><?php echo date("d M Y", strtotime($value->doc_dt)) ?></td>
        <td><?php echo $value->doc_no?></td>
        <td>

             <?php if($value->status == 'Consigned')
             {
               echo '<a  target="_blank" href="assets/images/signed_do/'.$value->uploaded_file,'"> Consigned </a>';
             }
               else
                  echo $value->status
              ?>
        </td>
        <td><?php echo $value->companyname ?></td>
        <td><?php echo $value->customer_name ?></td>
        <td><?php echo $value->username ?> </td>
        <td>
         <?php

            $invoice = DB::table('invoice')->select('uuid')
                       ->where('do_no', $value->id )->first();

            if ( $invoice == '')

                   echo '<a type="submit"  href="/invoice-generate/'.$value->id . '/'. $value->companyid,'"  class="btn  btn-success"> generate </a>';

            else
                echo
                    '<div class ="d-flex justify-content-between">
                        <a href="invoice-download/'.$invoice->uuid,'" class="btn btn-warning mr-1" target="_blank"> Download </a>
                        <a href="invoice-update/'.$invoice->uuid,'" class="btn btn-primary" id="update">Update</a>
                    </div>'
             ?>
       </td>
        <td>
            <?php

             echo
                '<div class ="d-flex justify-content-between">
                    <a href="download-delivery-order/'.$value->uuid,'"><i class="fas fa-file-download fa-lg text-info" target="_blank"></i></a>
                    <a href="delivery-order-changelog/'.$value->uuid,'"> <i class="fas fa-history fa-lg text-warning"></i> </a>
                    <a href="delete-delivery-order/'.$value->id,'"> <i class="far fa-trash-alt text-danger" style="cursor:pointer";  onclick ="return confirm(\'Are You sure want to delete\')"></i> </a>';

                if($value->status != 'Consigned' )
                      echo  '<a href="edit-DO/'.$value->uuid,'"><i class="far fa-edit fa-lg text-success"></i></a>';
                 else
                     echo '';

                 if($value->status == 'In-Transit' )
                   echo '<a href="upload-DO/'.$value->uuid,'"> <i class="fas fa-upload fa-lg"></i></a>';
                  else
                  echo '';

                  '</div>';
            ?>
        </td>
    </tr>
        <?php
            $html = ob_get_contents();
            ob_end_clean();
            return $this->minify_html($html);

    }

    private function minify_html($html)
    {
        $search = array(
            '/(\n|^)(\x20+|\t)/',
            '/(\n|^)\/\/(.*?)(\n|$)/',
            '/\n/',
            '/\<\!--.*?-->/',
            '/(\x20+|\t)/', # Delete multispace (Without \n)
            '/\>\s+\</', # strip whitespaces between tags
            '/(\"|\')\s+\>/', # strip whitespaces between quotation ("') and end tags
            '/=\s+(\"|\')/'
        ); # strip whitespaces between = "'
        $replace = array(
            "\n",
            "\n",
            " ",
            "",
            " ",
            "><",
            "$1>",
            "=$1"
        );
        $html = preg_replace($search, $replace, $html);
        return $html;
    }


   //Invoice Generate for Delivery Order

   public function invoiceGenerate(Request $request)
   {
      $Company = new Company();

      $do = DB::table('delivery_order')->where('id', $request->id)->first();  // getting all data from delivery order table

       $output='';

    //    $shortcode = DB::table('company')->select('short_code')->where('id', $request->companyid )->get();  //getting short code from company table

    //     foreach($shortcode as $value){
    //         $output= $value->short_code;
    //     }
    //     $code = $output;

      //$grandtotal = DB::table('delivery_order_item')->where('doc_id',$request->id)->sum('total');

      $customertype = DB::table('customer')->select('type')->where('id', $do->customer_id)->first();  //selecting customer type
      $parentid = DB::table('customer')->where('id', $do->customer_id)->first();

    if($request->id !== ''){

       if($do !== null)
       {
           $invoice = new Invoice();  //model
           $invoice->uuid = Uuid::uuid4();
           $invoice->com_id = $request->companyid;
           $invoice->do_no = $request->id;
           $invoice->type = 'do';
           $invoice->grandtotal= number_format((float) DeliveryOrderItem::where(['doc_id' => $invoice->do_no])->sum('total'), 2, '.', '');
           $invoice->created_dt = date("Y-m-d H:i:s",  strtotime("Now"));
           $invoice->created_by = '38'; // should be Auth user id
           $invoice->updated_dt = date("Y-m-d H:i:s",  strtotime("Now"));
           $invoice->updated_by = '38'; // should to be Auth user id

           $countdoc = (int) DB::table('invoice')->where('com_id', $invoice->com_id)->where('type', '!=', 'transfer')->count() + 1;

           $invoice->invoice_no = $Company->getshortcode($invoice->com_id) . '/INV/' . date("y") . '/' . sprintf("%05d", $countdoc);

           $invoice->sales_pic = $do->sales_pic;
           $invoice->doc_dt = $do->doc_dt;
           $invoice->term = 'end_60';
           $invoice->payment_due = date('Y-m-t',strtotime($invoice->doc_dt . '+ 60 days'));
           $invoice->po_ref = $do->po_ref;

           if($customertype->type == 'parent')
           {
               $invoice->customer_id = $do->customer_id;
               $invoice->customer_name = $do->customer_name;
               $invoice->customer_address1 = $do->customer_address1;
               $invoice->customer_address2 = $do->customer_address2;
               $invoice->customer_postcode = $do->customer_postcode;
               $invoice->customer_city = $do->customer_city;
               $invoice->customer_state = $do->customer_state;
               $invoice->customer_pic = $do->customer_pic;
               $invoice->customer_phone = $do->customer_phone;
           }else{

                if($parentid->parent_id !== null ){
                    $invoice->customer_id =    $parentid->id;
                    $invoice->customer_name =  $parentid->name;
                    $invoice->customer_address1 =  $parentid->address1;
                    $invoice->customer_address2 =  $parentid->address2;
                    $invoice->customer_postcode = $parentid->postcode;
                    $invoice->customer_city =  $parentid->city;
                    $invoice->customer_state =  $parentid->state;
                    $invoice->customer_pic =  $parentid->contact_name;
                    $invoice->customer_phone =  $parentid->contact_no;

                }
           }
             if ($invoice->save()){
                    $log = new Log();

                    $log->user_id = '38';
                    $log->action_datetime = date("Y-m-d H:i:s",  strtotime("Now"));
                    $log->menu_id=$this->menu_id2;
                    $log->action_type = 'create';
                    $log->data_uuid = $invoice->uuid;
                    $log->save();
             }

            return redirect()->back()->with('success','Invoice has been Generated');
       }

     }

   }


   //Upload DO for Delivery Order
   public function uploadDO(Request $request, $uuid)
   {
         $model = DeliveryOrder::where('uuid', $uuid)->first();

         if ($request->route('account'))
         {
             $account = $request->route('account');
         }

         return view('finance.deliveryOrder.upload-do',

                       ['stores' => $this->stores(),
                         'model'=>$model,
                         'title' => 'Delivery Order',
                         'form' => 'Analysis', 'skipHeader' => 1]);
   }


   //Save DO
   public function saveDo(Request $request)
   {
        $uuid = $request->uuid;
        $file = $request->do;
        $model = DeliveryOrder::where('uuid', $uuid)->first();

       if(!empty($file)){
            $filename = date("YmdHis",  strtotime("Now")). '.' . $file->getClientOriginalExtension();
            $request->do->move('assets/images/signed_do', $filename);
            $model->uploaded_file = $filename;
            $model->status = 'Consigned';
       }

       if($model->save()){
            $Log = new Log();
            $Log->insertlog($this->menu_id5, 'upload', $model->uuid);
       }

       return redirect()->route('delivery.order')->with('success', 'File Successfully Uploadd');

   }




   //Download Invoice for Delivery Order
   public function invoiceDownload (Request $request)
   {
        $notes = new Notes();
        $invoicedata= DB::table('invoice')->where('uuid',$request->uuid)->first(); //model

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

        return view('finance.deliveryOrder.invoicedownload',compact('invoicedata', 'companydata', 'paramcustomer', 'stateparam', 'daysparam', 'user', 'items','note'));
   }

 //update invoice for delivery order

  public function invoiceupdate(Request $request)
   {
        if ($request->route('account'))
        {
            $account = $request->route('account');
        }

        $invoice = Invoice::where('uuid', $request->uuid)->first();


       //sales person
        $sales_person= DB::table('axis_user')
                        ->select('id AS sales_pic', 'name')
                        ->where('id',$invoice->sales_pic)->first();


        //invoice term
        $invoiceterm =   DB::table('param_list')->select('label','code')->where(['cat_id' => 26, 'code' => $invoice->term])->first();


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

         //dd($daysparam);

       //state
        $stateparam = DB::table('param_list')->select('code','label')->where(['cat_id' => 3 , 'status' => 1])->orderBy('sort' , 'ASC')->get();

       // delivery order item
        $items = DB::table('delivery_order_item')->where('doc_id', $invoice->do_no)->orderBy('id', 'ASC')->get(); //modelitem

       return view('finance.deliveryOrder.updateinvoice',
                    ['stores' => $this->stores(),
                     'invoice'=>$invoice,
                     'sales_person'=> $sales_person,
                     'invoiceterm'=>$invoiceterm,
                     'itmes'=>$items,
                     'sales_pic'=>$sales_pic, 'customers'=>$customers,
                     'states'=>$stateparam, 'daysparam'=>$daysparam,
                     'title' => 'Update Invoice',
                     'form' => 'Analysis', 'skipHeader' => 1]);
   }


   //calculate payment due

   public function calpaymentdue(Request $request)
   {

        $output = '';
        $invoice_term_arr = explode('_', $request->invoice_term);

        // dd($invoice_term_arr);

        if ($invoice_term_arr[0] == 'end') {
            $start_date =  date('Y-m-d', strtotime($request->invoice_dt));
            $output = date('Y-m-t', strtotime($start_date . ' + ' . $invoice_term_arr[1] . ' days'));
        } else {
            $start_date = date("Y-m-d", strtotime($request->invoice_dt));
            $output = date('Y-m-d', strtotime($start_date . ' + ' . $invoice_term_arr[1] . ' days'));
        }

        return response()->json($output, 200);
}


//save updated Invoice

    public function saveUpdatedInvoice(Request $request)
    {
        $items =$request->items;

        parse_str($request->value, $output);

        $uuid = $request->uuid;

        $model = Invoice::where('uuid', $uuid)->first();

        $model->updated_dt = date("Y-m-d H:i:s,",  strtotime("Now"));
        $model->updated_by = 38; // should be Auth user id
        $model->doc_dt = $request->invoice_doc_dt;
        $model->term = $request->invoice_term;
        $model->payment_due = $request->invoice_due;
        $model->customer_id = $request->cust_id;
        $model->customer_name =     $request->customer_name;
        $model->customer_address1 = $request->address;
        $model->customer_address2 = $request->address2;
        $model->customer_postcode = $request->postcode;
        $model->customer_city =     $request->city;
        $model->customer_state =    $request->state;
        $model->customer_pic =      $request->contactperson;
        $model->customer_phone =    $request->contactno;
        $model->tax = $request->tax;
        $model->grandtotal = $request->grandtotal;

        if ($model->save()) {

            if (!empty($_POST['items'])) {
                $item_arr = $_POST['items'];
                foreach ($item_arr as $item_id) {
                    if (($modelItem = DeliveryOrderItem::find($item_id)) !== null) {
                        //var_dump($modelItem);
                         $modelItem->quantity = $output['item_quantity_' . $item_id];
                         $modelItem->uom =  $output['item_uom_' . $item_id];
                         $modelItem->unit_price =  $output['item_unitprice_' . $item_id];
                         $modelItem->discount =  $output['item_discount_' . $item_id];
                         $modelItem->total =  $output['item_total_' . $item_id];
                         $modelItem->save();
                    }
                }
            }
            $Log = new Log();
            $Log->insertlog($this->menu_id4, 'update', $model->uuid);
        }
       return response()->json("success");

    }



    //Add delivery Order
    public function addDeliveryOrder( Request $request)
    {

        if ($request->route('account'))
        {
            $account = $request->route('account');
        }

        //company list
        $companies = DB::table('company AS c')
                        ->select('c.id AS company_id','c.name AS name')
                        ->leftJoin('company_access AS a' ,'a.com_id' ,'=' ,'c.id')
                        ->WHERE( 'a.user_id', '=' , 38 )
                        ->orderBy('c.name', 'ASC')->get();
        //sales person
        $sales_pic= DB::table('axis_user')
                        ->select('id AS sales_pic', 'name')
                        ->orderBy('name', 'ASC')->get();
        //customers
        $customers = DB::table('customer')
                        ->select('id AS customer_id', 'name')
                        ->orderBy('name', 'ASC')->get();
        //states
        $states = DB::table('param_list')
                        ->select('code', 'label', 'sort')
                        ->where(['cat_id' => 3, 'status'=>1 ])
                        ->orderBy('sort', 'ASC')->get();

        return view('finance.deliveryOrder.add-deliveryOrder',
                    ['stores' => $this->stores() , 'page' => 'finance.salesReturn.cde-salesreturn',
                    'companies'=>$companies, 'sales_pic'=>$sales_pic, 'customers'=>$customers,
                    'states'=>$states, 'title' => 'add delivery order',
                    'form' => 'Analysis', 'skipHeader' => 1]);

    }
     //customer details
    public function getCusmoterDetails ($id)
    {
        $details = DB::table('customer')->find($id);
        return response()->json( $details, 200);
    }

    //contact person choose
    public function chooseContactPerson( $id, $type)
    {
        $output ='<table class="table table-striped table-dark" id="table">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Position</th>
                        <th scope="col">Phone No</th>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody id="tablebody">';

      $person = DB::table('contact')->where(['type'=>$type, 'data_id'=>$id])->get();

       if (!empty($person)) {
         $count = 0;

         foreach ( $person as $row){
             $count++;
             $val = $row->name . "<->" . $row->phone;
            $output .='<tr>
                    <td>' . $count . '</td>
                    <td>' . $row->name . '</td>
                    <td>' . $row->position . '</td>
                    <td>' . $row->phone . '</td>
                    <td style="vertical-align:middle;"><button class="btn btn-primary" data-dismiss="modal" onclick="changecontact(\'' . $val . '\')">Choose</button></td>
            </tr>' ;
         }

       }else{
           $output .='<tr> <td> No record found </td></tr>';
       }

       $output.='</tbody></table>';
       return response()->json($output, 200);


    }


    //store delivery order
    public function storeDeliveryOrder (Request $request)
    {
       $countdoc = DB::table('delivery_order')->where('company_id', $request->company_id)
                   ->count() + 1;

       $request->validate([
         'company_id' => 'required',
         'customer_name'=>'required',

         ]);

        $store = new DeliveryOrder();

        $output='';
        $doc = '';

        $doc_no= DB::table('company')->select('short_code')
                 ->where('id', $request->company_id)->get();

         foreach($doc_no as $value){
             $doc= $value->short_code;
         }
         $abc = $doc;

        $store ->uuid=Uuid::uuid4();
        $store->status= 'Draft';
        $store->doc_no= $abc. '/DO/' . date("y") . '/' . sprintf("%05d" , $countdoc);
        $store->doc_dt = $request->doc_dt;
        $store->po_ref = $request->po_ref;
        $store->company_id= $request->company_id;
        $store->customer_id=$request->cust_id;
        $store->customer_name=$request->customer_name;
        $store->customer_address1=$request->address;
        $store->customer_address2=$request->address2;
        $store->customer_postcode=$request->postcode;
        $store->customer_city=$request->city;
        $store->customer_state=$request->state;
        $store->customer_pic=$request->contactperson;
        $store->customer_phone=$request->contactno;
        $store->sales_pic=$request->sales_pic;
        $store->created_dt= date("Y-m-d H:i:s",  strtotime("Now"));
        $store->created_by = 38; // hard coded supposed to be auth()->user()->id
        $store->updated_dt= date("Y-m-d H:i:s",  strtotime("Now"));
        $store->updated_by= 38; // hard coded supposed to be auth()->user()->id

      if($store->save()){
            $Log = new Log();
            $Log->insertlog($this->menu_id5, 'create', $store->uuid);
       }

       if($store->save()){
        $output ='<table class="table table-striped table-dark" id="wrapper_product_list">
                    <thead>
                    <tr class="thead-dark">
                        <th>SKU</th>
                        <th>Description</th>
                        <th width="10%">Stock Owner</th>
                        <th width="11%">Qty</th>
                        <th width="10%">UoM</th>
                        <th width="10%">Unit Price (RM)</th>
                        <th width="10%">Discount (%)</th>
                        <th width="10%">Total (RM)</th>
                        <th>Remarks</th>
                        <th width="1%">Action</th>
                    </tr>
                    </thead>
                    <tbody id="tablebody">';

                    $modelItem = DeliveryOrderItem::where('doc_id', $store->id)->orderBy('id', 'ASC')->get();
                    $Product = new Product();
                    $StockLog = new StockLog();
                    if (!empty($modelItem)) {
                        foreach ($modelItem as $rowItem) {
                            $ownertypeV = '';
                            $ownertypeC = '';
                            if ($rowItem->owner_type == 'vendor')
                                $ownertypeV = 'selected';
                            if ($rowItem->owner_type == 'company')
                                $ownertypeC = 'selected';

                            $stock_owned_vendor = $StockLog->balancestockbyowner($rowItem->product_id, 'vendor', $Product->getvendor($rowItem->product_id));
                            $stock_owned_com = $StockLog->balancestockbyowner($rowItem->product_id, 'company', $store->company_id);
                            $stock_all = $stock_owned_vendor + $stock_owned_com;

                        echo '<tr id="rowItem_' . $rowItem->id . '">
                                <td style="vertical-align:middle;">' . $Product->getsku($rowItem->product_id) . '</td>
                                <td style="vertical-align:middle;">
                                    <input type="hidden" value="' . $rowItem->id . '" name="item_id[]">
                                    ' . $Product->getname($rowItem->product_id) . '
                                </td>
                                <td>
                                    <select class="form-control kt-selectpicker" name="item_ownertype_' . $rowItem->id . '">
                                        <option value="company" ' . $ownertypeC . '>Company</option>
                                        <option value="vendor" ' . $ownertypeV . '>Vendor</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" class="form-control" value="' . $rowItem->quantity . '" name="item_quantity_' . $rowItem->id . '" onchange="updatetotal(' . $rowItem->id . ')">
                                    <span class="kt-badge  badge badge-inline badge badge-success mt-2 tooltip-stock" data-toggle="tooltip" data-placement="bottom" data-html="true" title="Vendor : ' . number_format($stock_owned_vendor) . '<br>Company : ' . number_format($stock_owned_com) . '">Current stock : ' . number_format($stock_all) . '</span>
                                </td>
                                <td><input type="text" class="form-control" value="' . $rowItem->uom . '" name="item_uom_' . $rowItem->id . '"></td>
                                <td><input type="text" class="form-control" value="' . $rowItem->unit_price . '" name="item_unitprice_' . $rowItem->id . '" onchange="updatetotal(' . $rowItem->id . ')"></td>
                                <td><input type="text" class="form-control" value="' . $rowItem->discount . '" name="item_discount_' . $rowItem->id . '" onchange="updatetotal(' . $rowItem->id . ')"></td>
                                <td><input type="text" class="form-control" value="' . $rowItem->total . '" name="item_total_' . $rowItem->id . '"></td>
                                <td><input type="text" class="form-control" value="' . $rowItem->remarks . '" name="item_remarks_' . $rowItem->id . '"></td>
                                <td align="center"><i class="fa fa-save" aria-hidden="true" style="cursor:pointer;" title="update" onclick="update(' . $rowItem->id . ')"></i></td>
                                <td align="center"><i class="fas fa-trash-alt font-danger" style="cursor:pointer;" title="Delete product" onclick="deleteproduct(' . $rowItem->id . ')"></i></td>
                            </tr>';
                    }
                }
            $output.='</tbody></table>';

        $button1 = '<button class="btn btn-outline-warning mt-3" type="button" data-toggle="modal" data-target="#Modal"  id="add" onclick="addproduct()" > Add Product </button>';
        // $button2 = '<button class="btn btn-info mt-3" type="button"  onclick="update()" > Update </button>';

    }

        return response()->json([$store, $output, $button1]);
 }


 //edit DO
    public function editDO(Request $request)
    {
        $uuid = $request->uuid;

        $model = DeliveryOrder::where('uuid', $uuid)->first();

          //company name
        $modelcom = DB::table('company AS c')
                    ->select('c.id AS company_id','c.name AS name')
                    ->where('c.id', $model->company_id)->first();
         //sales person
          $sales_person = DB::table('axis_user')
                            ->select('id', 'name')
                            ->where('id', $model->sales_pic)
                            ->orderBy('name', 'ASC')->first();

        //company list
        $companies = DB::table('company AS c')
                        ->select('c.id AS company_id','c.name AS name')
                        ->leftJoin('company_access AS a' ,'a.com_id' ,'=' ,'c.id')
                        ->WHERE( 'a.user_id', '=' , 38 )
                        ->orderBy('c.name', 'ASC')->get();
        //sales person
        $sales_pic= DB::table('axis_user')
                        ->select('id AS sales_pic', 'name')
                        ->orderBy('name', 'ASC')->get();
        //customers
        $customers = DB::table('customer')
                        ->select('id AS customer_id', 'name')
                        ->orderBy('name', 'ASC')->get();
        //states
        $states = DB::table('param_list')
                        ->select('code', 'label', 'sort')
                        ->where(['cat_id' => 3, 'status'=>1 ])
                        ->orderBy('sort', 'ASC')->get();
        //customer state
        $customer_state = DB::table('param_list')
                        ->select('code', 'label', 'sort')
                        ->where(['cat_id' => 3, 'status'=>1 ])
                        ->where('code', $model->customer_state)
                        ->orderBy('sort', 'ASC')->first();
         //dd($customer_state);


     return view('finance.deliveryOrder.edit-DO',
                ['stores' => $this->stores(),
                'modelcom'=> $modelcom,
                'model'=> $model,
                'companies'=>$companies, 'sales_pic'=>$sales_pic, 'customers'=>$customers,
                'states'=>$states, 'title' => 'add delivery order',
                'sales_person' => $sales_person,
                'customer_state'=> $customer_state ,
                'form' => 'Analysis', 'skipHeader' => 1]);
 }




  //save updated DO
    public function saveUpdatedDO(Request $request)
    {
      parse_str($request->value, $output);

      $uuid = $request->uuid;
      $model = DeliveryOrder::where('uuid', $uuid)->first();
      $original_status = $model->status;
      $model->updated_dt = date("Y-m-d H:i:s",  strtotime("Now"));
      $model->updated_by = 38; // should be auth user id
      $model->doc_dt = $request->doc_dt;
      $model->po_ref = $request->po_ref;
      $model->company_id= $request->company_id;
      $model->customer_id=$request->cust_id;
      $model->customer_name=$request->customer_name;
      $model->customer_address1=$request->address;
      $model->customer_address2=$request->address2;
      $model->customer_postcode=$request->postcode;
      $model->customer_city=$request->city;
      $model->customer_state=$request->state;
      $model->customer_pic=$request->contactperson;
      $model->customer_phone=$request->contactno;
      $model->sales_pic=$request->sales_pic;

      if (!empty($output['item_id']) && $original_status == 'Draft') {
         $model->status = 'In-Transit';
        } else if (empty($output['item_id']) && $original_status == 'In-Transit') {
            $model->status = 'Draft';
         }

        if ($model->save()) {
            if (!empty($output['item_id'])) {
                $item_arr = $output['item_id'];
                foreach ($item_arr as $item_id) {
                    if (($modelItem = DeliveryOrderItem::find($item_id)) !== null) {
                        $modelItem->uom = $output['item_uom_' . $item_id];
                        $modelItem->quantity = $output['item_quantity_' . $item_id];
                        $modelItem->unit_price = $output['item_unitprice_' . $item_id];
                        $modelItem->discount = $output['item_discount_' . $item_id];
                        $modelItem->total = $output['item_total_' . $item_id];
                        $modelItem->remarks = $output['item_remarks_' . $item_id];
                        $modelItem->owner_type = $output['item_ownertype_' . $item_id];
                        $owner_id = '';
                        if ($modelItem->owner_type == 'company')
                            $owner_id = $model->company_id;
                        if ($modelItem->owner_type == 'vendor') {
                            $Product = new Product();
                            $owner_id = $Product->getvendor($modelItem->product_id);
                        }
                        $modelItem->owner_id = $owner_id;
                        if ($modelItem->save()) {

                            $modelStockLog = StockLog::where(['table_related' => 'delivery_order_item', 'data_id' => $modelItem->id, 'product_id' => $modelItem->product_id])->first();
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
            $Log->insertlog($this->menu_id5, 'update', $model->uuid);
         }

      return response()->json("success");

    }


    //Product List for Delivery order
    public function productList(Request $request)
    {
        $com_id = $request->com_id;
        $doc_id = $request->doc_id;
        $product_keyword = $request->product_keyword;
        $output = '';
        $count = 0;
        $modelProduct = Product::where(['com_id' => $com_id])->where(['status' => 1])->where( 'name', 'like', '%' . $product_keyword . '%')->orderBy('name' , 'ASC')->get();

        if(!empty($modelProduct)){
            $output .= '<div class="row">';
            foreach($modelProduct as $rowProduct){
                if(($modelItem = DeliveryOrderItem::find(['doc_id' => $doc_id, 'product_id' => $rowProduct->id])) != null){

                   $count++;
                    $output .= '<div class="col-6">
                        <label class="kt-checkbox">
                            <input type="checkbox" value="' . $rowProduct->id . '" class="product_id">
                            ' . $rowProduct->name . '<label class="badge badge--inline badge bg-dark ml-3">' . $rowProduct->sku . '</label>
                            <span></span>
                        </label>
                    </div>';

                }

            }
            $output .= '</div><div class="kt-font-bold kt-font-danger" id="error_message">Please select at least one (1) product</div>';

        }
        if ($count == 0)
            $output = 'All product has been added to the Debit Note or no product found under this company';


        return $output;

    }


    // Save product for delivery order
    public function addProductDeliveryOrder(Request $request)
    {
        $output = '';
        $Product = new Product();
        $ParamList = new ParamList();
        $StockLog = new StockLog();

        $doc_id = $request->doc_id;
        $company_id = $request->company_id;
        $items = $request->items;

      //dd($items);

        if (!empty($items)){
            foreach($items as $item){
                $model = new DeliveryOrderItem();
                $model->doc_id =  $doc_id;
                $model->product_id = $item;
                $model->uom = $ParamList->getlabel(9, $Product->getuom($model->product_id));
                $model->quantity = '0';
                $model->unit_price = $Product->getprice($model->product_id);
                $model->discount = '0';
                $model->total = '0';
                $model->created_dt = date("Y-m-d H:i:s",  strtotime("Now"));
                $model->created_by = 38; // should be auth user id
                $model->updated_dt = date("Y-m-d H:i:s",  strtotime("Now"));
                $model->updated_by = 38; // should be auth user id
                $model->owner_type = 'vendor';
                $model->owner_id = $Product->getvendor($model->product_id);
                $model->save();


                if($model->save()){

                    $modelStockLog = new StockLog();
                    $modelStockLog->product_id = $model->product_id;
                    $modelStockLog->action_type = 'stockout';
                    $modelStockLog->table_related = 'delivery_order_item';
                    $modelStockLog->data_id = $model->id;

                    $modelStockLog->do_no = $StockLog->getdono2($modelStockLog->table_related, $modelStockLog->data_id);
                    $modelStockLog->action_by = 38; // should be auth user id
                    $modelStockLog->action_datetime = date("Y-m-d H:i:s",  strtotime("Now"));
                    $modelStockLog->owner_type = $model->owner_type;
                    $modelStockLog->owner_id = $model->owner_id;

                    $modelStockLog->save();

                    $stock_owned_vendor = $StockLog->balancestockbyowner($model->product_id, 'vendor', $Product->getvendor($model->product_id));
                    $stock_owned_com = $StockLog->balancestockbyowner($model->product_id, 'company', $request->company_id);
                    $stock_all = $stock_owned_vendor + $stock_owned_com;

                    $output .= '<tr id="rowItem_' . $model->id . '">
                        <td style="vertical-align:middle;">' . $Product->getsku($model->product_id) . '</td>
                        <td style="vertical-align:middle;">
                            <input type="hidden" value="' . $model->id . '" name="item_id[]">
                            ' . $Product->getname($model->product_id) . '
                        </td>

                        <td>
                        <select class="form-control kt-selectpicker" name="item_ownertype_' . $model->id . '">
                            <option value="company">Company</option>
                            <option value="vendor" selected>Vendor</option>
                        </select>
                    </td>
                    <td>
                    <input type="text" class="form-control" value="' . $model->quantity . '" name="item_quantity_' . $model->id . '" onchange="updatetotal(' . $model->id . ')">
                            <span class="kt-badge badge badge-inline  badge badge-success mt-2 tooltip-stock" data-toggle="tooltip" data-placement="bottom" data-html="true" title="Vendor : ' . number_format($stock_owned_vendor) . '<br>Company : ' . number_format($stock_owned_com) . '">Current stock : ' . number_format($stock_all) . '</span>
                        </td>
                        <td><input type="text" class="form-control" value="' . $model->uom . '" name="item_uom_' . $model->id . '"></td>
                        <td><input type="text" class="form-control" value="' . $model->unit_price . '" name="item_unitprice_' . $model->id . '" onchange="updatetotal(' . $model->id . ')"></td>
                        <td><input type="text" class="form-control" value="' . $model->discount . '" name="item_discount_' . $model->id . '" onchange="updatetotal(' . $model->id . ')"></td>
                        <td><input type="text" class="form-control" value="' . $model->total . '" name="item_total_' . $model->id . '"></td>
                        <td><input type="text" class="form-control" value="' . $model->remarks . '" name="item_remarks_' . $model->id . '"></td>
                        <td align="center"><i class="fa fa-save" aria-hidden="true" style="cursor:pointer;" title="update" onclick="update(' . $model->id . ')"></i></td>
                        <td align="center"><i class="fas fa-trash-alt text-danger" style="cursor:pointer;" title="Delete product" onclick="deleteproduct(' . $model->id . ')"></i></td>
                    </tr>';

                }

            }

        }

        return $output;

    }



    //update Product Delivery Order

    public function updateProductDeliveryOrder(Request $request)
    {
        $uuid = $request->uuid;
        $item_id = $request->itemid;
        $uom = $request->item_uom;
        $quantity = $request->item_quantity;
        $unit_price = $request->item_unit_price;
        $discount= $request->item_discount;
        $total= $request->item_total;
        $remarks = $request->item_remarks;
        $onwer_type = $request->item_onwer_type;


        $model = DeliveryOrder::where('uuid', $uuid)->first();
        //dd($model);
        $original_status = $model->status;
        $model->updated_dt = date("Y-m-d H:i:s",  strtotime("Now"));
        $model->updated_by = 38; //should be auth user id

        if (!empty($item_id) && $original_status == 'Draft') {
            $model->status = 'In-Transit';
        }else if (empty($item_id) && $original_status == 'In-Transit') {
            $model->status = 'Draft';
          }

        if($model->save()){
            if(!empty($item_id)){
                    $modelItem = DeliveryOrderItem::where('id', $item_id)->first();
                if  ($modelItem != null){
                    $modelItem->uom = $uom  ;
                    $modelItem->quantity = $quantity;
                    $modelItem->unit_price = $unit_price;
                    $modelItem->discount = $discount;
                    $modelItem->total = $total;
                    $modelItem->remarks = $remarks;
                    $modelItem->owner_type = $onwer_type;
                    $owner_id = '';
                    if ($modelItem->owner_type == 'company')
                        $owner_id = $model->company_id;
                    if ($modelItem->owner_type == 'vendor') {
                        $Product = new Product();
                        $owner_id = $Product->getvendor($modelItem->product_id);
                    }
                    $modelItem->owner_id = $owner_id;
                    if($modelItem->save()){
                         $modelStockLog = StockLog::where(['table_related' => 'delivery_order_item', 'data_id' => $modelItem->id, 'product_id' => $modelItem->product_id])->first();
                        if ($modelStockLog != null) {
                            $modelStockLog->owner_type = $modelItem->owner_type;
                            $modelStockLog->owner_id = $modelItem->owner_id;
                            $modelStockLog->save();
                        }

                    }

                }

            }

            $Log = new Log();
            $Log->insertlog($this->menu_id5, 'update', $model->uuid);

        }


        return response()->json([ "message" => "Success"]);

    }



 //Delete Product from Delivery Order

  public function deleteProductDeliveryOrder(Request $request)
    {
       $id = $request->id;

       $status = 'failed';
       $data= DeliveryOrderItem::where('id', $id)->first();

       if ($data->delete()) {
            $stocklog = StockLog::where(['action_type' => 'stockout', 'table_related' => 'delivery_order_item', 'data_id' => $id])->first();
           if ($stocklog->delete())
               $status = 'success';
     }
       return $status;

    }




   // Download delivery Order

   public function downloadDeliveryOrder($uuid)
   {
        $ParamList = new ParamList();
        $User = new AxisUser();
        $Notes = new Notes();
        $model = DeliveryOrder::where('uuid', $uuid)->first();
       // dd($model);

       if (!empty($model)) {
        $modelcom = Company::find($model->company_id);
        if (!empty($modelcom)) {
            $header = '<table id="table-header" border="0" width="100%" cellpadding="0" cellspacing="0">
            <tr>
            <td rowspan="2"><img src="/assets/images/company_logo/' . $modelcom->logo . '" height="80"></td>
            <td align="right"><b>' . $modelcom->name . ' ('.$modelcom->reg_no.')</b></td>
            </tr>
            <tr>
            <td align="right" style="font-size:15px;">' . $modelcom->address1 .'<br> '.$modelcom->address2.'<br>'.
            $modelcom->postcode.' '.$modelcom->city.', '.$ParamList->getlabel(3,$modelcom->state).', Malaysia<br>
            (T) '.$modelcom->phone_no.'  (F) '.$modelcom->fax_no.'<br></td>
            </tr>
            </table>';
            $content = '<br><table border="0" width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="50%" valign="top">
                            <br/><b>SHIP TO</b>
                            <table cellpadding="0" cellspacing="0">
                                <tr>
                                    <td valign="top">Company Name</td>
                                    <td valign="top">&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                                    <td valign="top">' . $model->customer_name . '</td>
                                </tr>
                                <tr>
                                    <td valign="top">Address</td>
                                    <td valign="top">&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                                    <td valign="top">' . $model->customer_address1 . ' ' . $model->customer_address2 . ' ' . $model->customer_postcode . ' ' . $model->customer_city . ' ' . $ParamList->getlabel(3, $model->customer_state) . '</td>
                                </tr>
                                <tr>
                                    <td valign="top">Contact Person</td>
                                    <td valign="top">&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                                    <td valign="top">' . $model->customer_pic . '</td>
                                </tr>
                                <tr>
                                    <td valign="top">Contact No</td>
                                    <td valign="top">&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                                    <td valign="top">' . $model->customer_phone . '</td>
                                </tr>
                            </table>
                        </td>
                        <td width="50%" valign="top" align="right">
                            <table cellpadding="0" cellspacing="0" style="padding-right:1em;">
                                <tr><td colspan="3" align="center"><h4><b>DELIVERY ORDER</b></h4><br/><br/></td></tr>
                                <tr>
                                    <td valign="top">Date</td>
                                    <td valign="top">&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                                    <td valign="top" align="left">' . date("dS M Y", strtotime($model->doc_dt)) . '</td>
                                </tr>
                                <tr>
                                    <td valign="top">DO Number</td>
                                    <td valign="top">&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                                    <td valign="top" align="left">' . $model->doc_no . '</td>
                                </tr>
                                <tr>
                                    <td valign="top">Customer PO ref</td>
                                    <td valign="top">&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                                    <td valign="top" align="left">' . $model->po_ref . '</td>
                                </tr>
                                <tr>
                                    <td valign="top">Prepared by</td>
                                    <td valign="top">&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                                    <td valign="top" align="left">' . $User->getusername($model->created_by) . '</td>
                                </tr>
                                <tr>
                                    <td valign="top">Contact No</td>
                                    <td valign="top">&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                                    <td valign="top" align="left">' . $User->getuserphone($model->created_by) . '</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table><br>';
            $content .= '<table width="100%" cellpadding="5" cellspacing="1" border="1" style="background-color:white;" id="table-product">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>SKU</th>
                            <th>Description</th>
                            <th width="10%">UoM</th>
                            <th width="10%">Qty</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>';

            $modelItem = DeliveryOrderItem::where(['doc_id' => $model->id])->orderBy('id' , 'ASC')->get();
            $Product = new Product();
            if (!empty($modelItem)) {
                $countItem = 0;
                foreach ($modelItem as $rowItem) {
                    $countItem++;
                    $content .= '<tr>
                            <td align="center">' . $countItem . '</td>
                            <td>' . $Product->getsku($rowItem->product_id) . '</td>
                            <td>' . $Product->getname($rowItem->product_id) . '</td>
                            <td>' . $rowItem->uom . '</td>
                            <td>' . number_format($rowItem->quantity) . '</td>
                            <td>' . $rowItem->remarks . '</td>
                        </tr>';
                }
            } else {
                $content .= '<tr><td colspan="6">No record found</td></tr>';
            }

            $company_stamp = '<tr><td align="center" height="110"></td><td></td><td></td></tr>';
            if (!empty($modelcom->stamp))
                $company_stamp = '<tr><td align="center"><img src="/assets/images/company_logo/' . $modelcom->stamp . '" height="100"></td><td></td><td></td></tr>';

            $content .= '</tbody></table>';
            $content .= '<br><div style="font-size:20px;line-height:1.7em;">' . $Notes->getnotes('Deliveryorder', $modelcom->id) . '</div>
                <table width="100%">
                    ' . $company_stamp . '
                    <tr>
                        <td align="center" style="border-top:1px solid black;" width="30%">Authorized Signature</td>
                        <td></td>
                        <td align="center" style="border-top:1px solid black;" width="30%">Customer Chop and sign</td>
                    </tr>
                </table>';


            $footer = '<table id="table-footer" border="0" width="100%" cellpadding="0" cellspacing="0">
            <tr><td align="center" style="font-size:15px;"><b>' . $modelcom->tagline . '</b><br>' . $modelcom->website . '
            </td>
            </tr>
            </table>';

         return $header . $content . $footer;

        }
     }

}



    //Delete Delivery Order

    public function deleteDeliveryOrder(Request $request, $id)
    {
        $data = DeliveryOrder::find($id);

        if (!empty($data)) {
            $data->deleted_status = 1;
            if ($data->save()) {
                $Log = new Log();
                $Log->insertlog($this->menu_id, 'delete', $data->uuid);
            }
        }
       $data->delete();
        return redirect()->back()->with('success', 'Delivery Order Deleted Successfully');
    }


    // Log For Delivery Order

    public function getDeliveryorderLog($uuid)
    {

        $searchModel = DB::select("SELECT al.data_uuid AS uuid,al.action_datetime,al.action_type,m.title, m.status,m.url,u.name,u.role,pl.label
                                    FROM log al
                                    LEFT JOIN axis_user u ON al.user_id = u.id
                                    LEFT JOIN menu m ON al.menu_id = m.id
                                    LEFT JOIN param_list pl ON u.role = pl.code
                                    WHERE al.data_uuid='$uuid' ORDER BY al.action_datetime DESC");

        return view('finance.deliveryOrder.log',['clog'=>$searchModel,'stores'=> $this->stores(),'skipHeader' => 1]);

    }





    //**********************Sales Return Starts here********************/

    // sales return
    public function salesreturn(Request $request)
    {
        if ($request->route('account'))
        {
            $account = $request->route('account');
        }

        $keys = ['Consigned'];
        return view('finance.salesReturn.home', ['stores' => $this->stores() , 'page' => 'finance.salesReturn.cde-salesreturn', 'keys' => $keys, 'title' => 'sales return', 'form' => 'Analysis', 'skipHeader' => 1]);
    }


    //set ajax for sales return

    public function getAjaxSalesReturn(Request $request)
    {
        $column = [];
        static $count = 0;

        $input = $request->all();

         $query= 'SELECT DISTINCT gp.id AS id, gp.cn_id, deo.status, gp.uuid, deo.doc_no AS donumber, c.name AS company, deo.customer_name AS customer , gp.received_dt AS datercv
                 FROM grn_parent gp
                 LEFT JOIN company_access a ON a.com_id=gp.company_id
                 LEFT JOIN company c ON gp.company_id=c.id
                 LEFT JOIN delivery_order deo ON gp.do_id=deo.id
                 ORDER BY gp.id DESC';

         $data =DB::select($query);

        foreach ($data as $value)
        {
            $count++;
            $column[$value->status][]= $this->setRowSalesReturn($value, $count);
        }
        return ['data' => $column];
    }

    // set row for sales return

    public function setRowSalesReturn($value, $count)
    {
    ob_start();
    ?>
        <tr>
        <td><?php echo $count; ?></td>
         <td>
            <fieldset>
                <div class="checkbox checkbox-info checkbox-glow">
                    <input type="checkbox" name="data[]" id="ship_<?php echo $value->id ?>" value="<?php echo $value->id ?>" >
                    <label for="ship_<?php echo $value->id ?>"></label>
                </div>
            </fieldset>
         </td>

        <td><?php echo date("d M Y", strtotime($value->datercv)) ?></td>
        <td><?php echo $value->donumber ?></td>
        <td><?php echo $value->company ?></td>
        <td><?php echo $value->customer ?></td>

        <td><?php

            $creditnote= DB::table('creditnote')->select('uuid')
                           ->where('id', $value->cn_id)->first();

         if ($value->cn_id == '')
           echo '<a type="submit"  href="/generate-sales-return-credit-note/'.$value->id ,'"  class="btn  btn-success"> generate </a>';
         else
            echo '<div class ="d-flex justify-content-between">
                    <a href="credit-note-download/'. $creditnote->uuid,'" class="btn btn-warning mr-1" target="_blank"> Download </a>
                    <a href="update-credit-note/'.$creditnote->uuid,'" class="btn btn-primary" id="update">Update</a>
               </div>'
           ?>
        </td>
        <td><?php

             echo
                '<div class ="d-flex">
                    <a href="edit-sales-return/'.$value->uuid,'" class="mr-2"><i class="far fa-edit fa-lg"></i></a>
                    <a href="salesreturn-changelog/'.$value->uuid,'" > <i class="fas fa-history fa-lg"></i></a>
                </div>';


          ?>
        </td>

        </tr>
        <?php
            $html = ob_get_contents();
            ob_end_clean();
            return $this->minify_html($html);

    }


    //Add Sales Return

    public function addSalesReturn(Request $request)
    {
        if ($request->route('account'))
        {
            $account = $request->route('account');
        }

        // $models = Grnparent::select('id')->get();
        // dd( $model ->id);

        $do_arr = DB::table('delivery_order AS deo')->select('deo.id', 'deo.doc_no')->leftJoin('company_access AS a', 'a.com_id', '=', 'deo.company_id')
                    ->where('a.user_id', '38')
                    ->where('deo.status', 'Consigned')
                    ->orderBy('deo.doc_no','ASC')->get();

         return view('finance.salesReturn.add-sales-return',
                        ['stores' => $this->stores() ,
                        'do_arr'=>$do_arr,
                        'title' => 'add Seles Return',
                        'form' => 'Analysis', 'skipHeader' => 1]);
    }


    //Get Do Details

    public function getDoDetails(Request $request)
    {
            $id = $request->id;
            $output = '';
            if (($model = DeliveryOrder::find($id)) !== null) {
                $Company = new Company;
                $output.= $model->company_id . '<->' . $Company->getname($model->company_id);
                $output.= '<->' . $model->customer_id . '<->' . $model->customer_name . '<->';
            }

            return response()->json($output, 200);
    }

    // Create Sales Return

    public function createSalesReturn(Request $request)
    {

            $request->validate([
            'id'=>'required',
            'datercv'=>'required',
            ]);

           $model = new GrnParent();
           $model->do_id= $request->id;
           $model->customer_id = $request->customer_id;
           $model->company_id = $request->company_id;
           $model->received_dt = $request->datercv;
           $model->created_dt = date("Y-m-d H:i:s",  strtotime("Now"));
           $model->created_by = '38';  //hardcoded should be auth user Id
           $model->updated_dt = date("Y-m-d H:i:s");
           $model->updated_by = '38'; //hardcoded should be auth user Id
           $model->uuid = Uuid::uuid4();
           if ($model->save()) {
                $log = new Log();
                $log->user_id='38';  // should be auth User id
                $log->action_datetime = date("Y-m-d H:i:s",  strtotime("Now"));
                $log->menu_id=$this->menu_id3;
                $log->action_type='create';
                $log->data_uuid=$model->uuid;
                $log->save();
        }
        if ($model->save()){

            $output ='<table class="table table-striped table-dark" id="wrapper_product_list">
                    <thead>
                    <tr class="thead-dark">
                        <th scope="col">Product</th>
                        <th scope="col">Status Inspection</th>
                        <th scope="col">Balance Qty</th>
                        <th scope="col">Qty (Return)</th>
                        <th scope="col">Remarks</th>
                        <th scope="col">Delete</th>
                    </tr>
                    </thead>
                    <tbody id="tablebody">';

        $modelItem = Grn::where('parent_id', $model->id)->orderBy('id', 'ASC')->get();
        $Product = new Product();
        $DeliveryOrderItem = new DeliveryOrderItem();

        if (!empty($modelItem)) {
            foreach ($modelItem as $rowItem) {
                $deletebtn = '';
                $inputdisabled = 'readonly';
                $formreadonly = 'form-readonly';
                if ($rowItem->status_inspection == 'pending') {
                    $inputdisabled = '';
                    $formreadonly = '';
                    $deletebtn = '<i class="fas fa-trash-alt kt-font-danger" style="cursor:pointer;" title="Delete product" onclick="deleteproduct(' . $rowItem->id . ')"></i>';
                }
                $output .= '<tr id="rowItem_' . $rowItem->id . '">
                            <td style="vertical-align:middle;">
                            <input type="hidden" value="' . $rowItem->id . '" name="item_id[]">
                            ' . $Product->getname($rowItem->product_id) . '
                        </td>
                        <td style="vertical-align:middle;">' . ucwords($rowItem->status_inspection) . '</td>
                        <td style="vertical-align:middle;">' . $DeliveryOrderItem->getqtysend($rowItem->do_id, $rowItem->product_id) . '</td>
                        <td><input ' . $inputdisabled . ' type="text" class="form-control ' . $formreadonly . '" value="' . $rowItem->quantity . '" name="item_quantity_' . $rowItem->id . '" onchange="updatetotal(' . $rowItem->id . ')"></td>
                        <td><input type="text" class="form-control" value="' . $rowItem->remarks . '" name="item_remarks_' . $rowItem->id . '"></td>
                        <td align="center"><i class="fa fa-save" aria-hidden="true" style="cursor:pointer;" title="update" onclick="update(' . $rowItem->id . ')"></i></td>
                        <td align="center">' . $deletebtn . '</td>
                    </tr>';
            }
        }

         $output.='</tbody></table>';

          $button = '<button class="btn btn-outline-warning mt-3" type="button" data-toggle="modal" data-target="#myModal"  id="add" onclick="addproduct()" > Add Product </button>';

    }

          return response()->json([$model, $output, $button]);
    }


   // get product List

   public function getProductlist($id,$pid)
   {
        $do_id= $id;

        $parent_id=$pid;

        $model = DeliveryOrderItem::where('doc_id', $do_id)->orderBy('id' , 'ASC')->get();
        $count = 0;
        $output = '';
        if (!empty($model)) {
            foreach ($model as $row) {
                $count++;
                $Product = new Product();
                if (($modelItem = Grn::find(['parent_id' => $parent_id, 'product_id' => $row->product_id])) != null) {
                    $output.= '<div class="col-6">
                              <label class="kt-checkbox">
                              <input type="checkbox" value="' . $row->product_id . '" class="product_id"> ' . $Product->getname($row->product_id) . '
                            <span></span>
                        </label>
                    </div>';
                }
            }
        }

        if ($count == 0)
            return 'All product has been added to the GRN or no product found under this company';
        else
            return '<div class="row">' . $output . '</div><div class="kt-font-bold kt-font-danger" id="error_message">Please select at lease one (1) product</div>';
   }




   //Add Product for Sales Return

   public function addProduct(Request $request)
   {
        $output = '';
        $do_id = $request->do_id;
        $cust_id = $request->cust_id;
        $com_id = $request->com_id;
        $received_dt = $request->received_dt;
        $parent_id =$request->parent_id;

        $Product = new Product();
        $DeliveryOrderItem = new DeliveryOrderItem();

        if(!empty($request->items)){

            foreach($request->items as $item) {
              //Insert into Grn
                $model = new Grn();
                $model->do_id = $do_id;
                $model->cust_id = $cust_id;
                $model->com_id = $com_id;
                $model->product_id = $item;
                $model->quantity = 0;
                $model->received_dt = $received_dt;
                $model->created_dt = date("Y-m-d H:i:s",  strtotime("Now"));
                $model->created_by = 38; //should be Auth User Id
                $model->updated_dt = date("Y-m-d H:i:s",  strtotime("Now"));
                $model->updated_by = 38; //should be Auth user id
                $model->uuid = Uuid::uuid4();
                $model->parent_id = $parent_id;
                $model->status_inspection = 'pending';


                $model->owner_type = $DeliveryOrderItem->getownertype($model->do_id, $model->product_id);
                $model->owner_id = $DeliveryOrderItem->getownerid($model->do_id, $model->product_id);

            if($model->save()){
                if(($modelparent = GrnParent::find($parent_id)) != null){
                    $cn_id = $modelparent->cn_id;
                      if(!empty($cn_id)){

                        $Product = new Product();
                        $ParamList = new ParamList();
                        $modelcnitem = new CreditnoteItem();

                        $modelcnitem->doc_id = $cn_id;
                        $modelcnitem->type = 'product';
                        $modelcnitem->product_id = $model->product_id;
                        $modelcnitem->quantity = 0;
                        $modelcnitem->discount = '0';
                        $modelcnitem->uom = $ParamList->getlabel(9, $Product->getuom($model->product_id));
                        $modelcnitem->unit_price = $Product->getprice($model->product_id);
                        $modelcnitem->total = '0.00';
                        $modelcnitem->created_dt = date("Y-m-d H:i:s",  strtotime("Now"));
                        $modelcnitem->created_by = 38; // Should be auth user id
                        $modelcnitem->updated_dt = date("Y-m-d H:i:s",  strtotime("Now"));
                        $modelcnitem->updated_by =38; // Should br auth user id
                        $modelcnitem->save();
                    }

                }

                $modelStockLog = new StockLog();
                $modelStockLog->product_id = $model->product_id;
                $modelStockLog->action_type = 'stockin';
                $modelStockLog->table_related = 'grn';
                $modelStockLog->data_id = $model->id;
                $modelStockLog->company_id = $model->com_id;

                $modelStockLog->do_no = $modelStockLog->getdono($modelStockLog->table_related, $modelStockLog->data_id);
                $modelStockLog->action_by = 38; //Should be auth User id
                $modelStockLog->action_datetime = date("Y-m-d H:i:s",  strtotime("Now"));
                $modelStockLog->owner_type = $model->owner_type;
                $modelStockLog->owner_id = $model->owner_id;

                if($modelStockLog->save()){

                    $output.='<tr id="rowItem_' . $model->id . '">
                            <td style="vertical-align:middle;">
                                <input type="hidden" value="' . $model->id . '" name="item_id[]">
                                ' . $Product->getname($model->product_id) . '
                            </td>
                            <td style="vertical-align:middle;">' . ucwords($model->status_inspection) . '</td>
                            <td style="vertical-align:middle;">' . $DeliveryOrderItem->getqtysend($model->do_id, $model->product_id) . '</td>
                            <td><input type="text" class="form-control" value="' . $model->quantity . '" name="item_quantity_' . $model->id . '"></td>
                            <td><input type="text" class="form-control" value="' . $model->remarks . '" name="item_remarks_' . $model->id . '"></td>
                            <td align="center"><i class="fa fa-save" aria-hidden="true" style="cursor:pointer;" title="update" onclick="update(' . $model->id . ')"></i></td>
                            <td align="center"><i class="fa fa-trash-alt text-danger" style="cursor:pointer;" title="Delete product" onclick="deleteproduct(' . $model->id . ')"></i></td>
                         </tr>';

                }


            }

          }

      }
        return $output;

    }

    // Update Product for sales return

    public function updateProductSalesReturn (Request $request)
    {
        $uuid = $request->uuid;
        $item_id = $request->itemid;
        $quantity = $request->item_quantity;
        $remarks = $request->item_remarks;

        $model = GrnParent::where('uuid', $uuid)->first();

        $model->updated_dt = date("Y-m-d H:i:s",  strtotime("Now"));
        $model->updated_by = 38; // should be auth user id

       if($model->save()){

        if(!empty($item_id)){
            $modelItem = Grn::where('id', $item_id)->first();
            if($modelItem != null){
                $modelItem->quantity = $quantity;
                $modelItem->remarks = $remarks;
                $modelItem->received_dt = $model->received_dt;
                $modelItem->save();

                if(!empty($model->cn_id)){
                 $modelCnItem = CreditnoteItem::where(['doc_id' => $model->cn_id, 'product_id' => $modelItem->product_id, 'type' => 'product'])->first();
                    if( $modelCnItem != null){
                        $modelCnItem->quantity = $modelItem->quantity;
                        $total = number_format((float) ($modelItem->quantity * $modelCnItem->unit_price), 2, '.', ',');
                        $modelCnItem->total = $total;
                        $modelCnItem->save();

                    }

                }

            }

        }
    }
        $Log = new Log();
        $Log->insertlog($this->menu_id3, 'update', $model->uuid);

        return response()->json([ "message" => "Success"]);

    }

    //edit sales return
    public function editSalesReturn(Request $request)
    {
      $uuid = $request->uuid;

      $model = GrnParent::where('uuid', $uuid)->first();

      //DO details
      $do_arr = DB::table('delivery_order AS deo')->select('deo.id', 'deo.doc_no')
                        ->leftJoin('company_access AS a', 'a.com_id', '=', 'deo.company_id')
                        ->where('a.user_id', '38')
                        ->where('deo.status', 'Consigned')
                        ->orderBy('deo.doc_no','ASC')->get();
      //doc nunber
      $do_no = DB::table('delivery_order AS deo')->select('deo.id', 'deo.doc_no', 'deo.customer_name')
                        ->leftJoin('company_access AS a', 'a.com_id', '=', 'deo.company_id')
                        ->where('a.user_id', '38')
                        ->where('deo.status', 'Consigned')
                        ->where('deo.id', $model->do_id)
                        ->orderBy('deo.doc_no','ASC')->first();
       //company
      $Company = new Company;
      $modelcom = $Company->getname($model->company_id);


     return view('finance.salesReturn.edit-sales-return',
                    ['stores' => $this->stores() ,
                    'do_arr'=>$do_arr,
                    'model'=>$model,
                    'do_no'=>$do_no,
                    'modelcom'=>$modelcom,
                    'title' => 'add Seles Return',
                    'form' => 'Analysis', 'skipHeader' => 1]);

    }


    //save updated sales return
   public function saveUpdatedSalesReturn(Request $request)
    {
        parse_str($request->value, $output);


        $uuid = $request->uuid;
        $model = GrnParent::where('uuid', $uuid)->first();
        $model->updated_dt = date("Y-m-d H:i:s",  strtotime("Now"));
        $model->updated_by = 38; //should be auth user id
        $model->received_dt = $request->datercv;

        if ($model->save()){

            if (!empty($output['item_id'])) {
                $item_arr = $output['item_id'];
                foreach ($item_arr as $item_id) {
                    if (($modelItem = Grn::find($item_id)) !== null) {
                        $modelItem->quantity = $output['item_quantity_' . $item_id];
                        $modelItem->remarks = $output['item_remarks_' . $item_id];
                        $modelItem->received_dt = $model->received_dt;
                        $modelItem->save();

                        if (!empty($model->cn_id)) {
                            if (($modelCnItem = CreditnoteItem::find(['doc_id' => $model->cn_id, 'product_id' => $modelItem->product_id, 'type' => 'product'])) !== null) {
                                $modelCnItem->quantity = $modelItem->quantity;
                                $total = number_format((float) ($modelItem->quantity * $modelCnItem->unit_price), 2, '.', ',');
                                $modelCnItem->total = $total;
                                $modelCnItem->save();
                            }
                        }
                    }
                }
            }
            $Log = new Log();
            $Log->insertlog($this->menu_id3, 'update', $model->uuid);
        }

        return response()->json("success");


    }



    //delete product from  Sales Return

    public function deleteSalesReturnProduct(Request $request)
    {
       $id = $request->id;
       //dd($id);
       $status = 'success';

       if (($model = Grn::find($id)) != null) {

        //delete from stock_log
         $data= StockLog::where('data_id', $id)->where('action_type', 'stockin')->where('table_related', 'grn');
         $data->delete();
        if (!$data) {
             $status = 'failed';
          }

      //delete from creditnote_item
        if (($modelparent = GrnParent::find($model->parent_id)) != null) {
            $cn_id = $modelparent->cn_id;
            if (!empty($cn_id)) {
                $data = "DELETE FROM creditnote_item WHERE doc_id=$cn_id AND product_id=$model->product_id AND type='product'";
                if (!$data) {
                    $status = 'failed';
                }
            }
        }

         //delete from grn
         $data =Grn::find($id)->delete();
         if (!$data) {
            $status = 'failed';
        }
      }
       return $status;

  }


    // download credit note Sales return
    public function creditDownload (Request $request)
    {
       $invoicedata =  DB::table('creditnote')->where('uuid',$request->uuid)->first(); //model

       $user = DB::table('axis_user')->find($invoicedata->created_by);

       $items = DB::table('creditnote_item')->where('doc_id', $invoicedata->id)->orderBy('id', 'ASC')->get(); //modelItem

       $companydata = DB::table('company')->find($invoicedata->company_id); //modelcom

       $stateparam = DB::table('param_list')->select('label')->where(['cat_id' => 3 , 'code' =>$companydata->state])->first();

       $paramcustomer = DB::table('param_list')->select('label')->where(['cat_id' => 3 , 'code' =>$invoicedata->customer_state])->first();

       $notes = DB::table('notes')->select('notes')->where('type','Creditnote')->first();

       $output ='';

       if($notes !== null){
           $output = $notes->notes;
           $output = str_replace("#COMPANY_NAME#",$companydata ->name, $output);
           $output = str_replace("#COMPANY_EMAIL#", $companydata->email, $output);
           $output = str_replace("#COMPANY_BANK_NAME#", $companydata->bank_name, $output);
           $output = str_replace("#COMPANY_BANK_ACCNO#",$companydata->bank_acc, $output);
       }
       $note= $output;

       return view('finance.salesReturn.download-credit-note', compact('invoicedata', 'companydata', 'paramcustomer', 'stateparam', 'user', 'items', 'note'));

    }


    //Update Credit Note
    public function updateCreditNote(Request $request)
    {
       $uuid = $request->uuid;
       $model = Creditnote::where('uuid', $uuid)->first();
       dd($model);

    }


    // Generate Sales Return Credit Note
    public function generateSalesReturnCreditNote(Request $request)
    {
        $status = 'failed';
        $grnparentid = $request->id;

        $Product = new Product();
        $ParamList = new ParamList();

        $model = GrnParent::find($grnparentid);

        $output='';

        $shortcode = DB::table('company')->select('short_code')->where('id', $model->company_id )->get(); //getting short code from company table

        foreach($shortcode as $value){
            $output= $value->short_code;
        }
        $code = $output;

        if($model != null){
           $countdoc = (int)DB::table('creditnote')->where('company_id', $model->company_id)->count() + 1;
           $modelcn = new Creditnote();
           $modelcn->uuid = Uuid::uuid4();
           $modelcn->type = 'product';
           $modelcn->doc_no = $code . '/CN/'. date("y") . '/' . sprintf("%05d", $countdoc);
           $modelcn->doc_dt = date("Y-m-d",  strtotime("Now"));
           $modelcn->company_id = $model->company_id;
           $modelcn->customer_id = $model->customer_id;

           $Customer = new Customer();
           $customer_details = $Customer->getcustomerdetails($modelcn->customer_id);

           if (!empty($customer_details)) {
            $customer_arr = explode('<->', $customer_details);
            $modelcn->customer_name = $customer_arr[0];
            $modelcn->customer_address1 = $customer_arr[1];
            $modelcn->customer_address2 = $customer_arr[2];
            $modelcn->customer_postcode = $customer_arr[3];
            $modelcn->customer_city = $customer_arr[4];
            $modelcn->customer_state = $customer_arr[5];
            $modelcn->customer_pic = $customer_arr[6];
            $modelcn->customer_phone = $customer_arr[7];
         }

            $modelcn->created_dt = date("Y-m-d H:i:s",  strtotime("Now"));
            $modelcn->created_by = '38';
            $modelcn->updated_dt = date("Y-m-d H:i:s",  strtotime("Now"));
            $modelcn->updated_by = '38';
            $grandtotal = 0;

            if ($modelcn->save()){
                $modelgrnitem = Grn::where('parent_id', $grnparentid)->orderBy('id', 'ASC')->get();
                //dd($modelgrnitem);

                if (!empty($modelgrnitem)) {
                    foreach ($modelgrnitem as $rowgrnitem) {
                        $modelcnitem = new CreditnoteItem();
                        $modelcnitem->doc_id = $modelcn->id;
                        $modelcnitem->type = 'product';
                        $modelcnitem->product_id = $rowgrnitem->product_id;

                        $modelcnitem->quantity = $rowgrnitem->quantity;
                        $modelcnitem->discount = '0';
                        $modelcnitem->uom = $ParamList->getlabel(9, $Product->getuom($modelcnitem->product_id));
                        $modelcnitem->unit_price = $Product->getprice($modelcnitem->product_id);

                        $total = $modelcnitem->quantity * $modelcnitem->unit_price;
                        $grandtotal+= $total;
                        $modelcnitem->total = number_format((float) $total, 2, '.', '');

                        $modelcnitem->created_dt = date("Y-m-d H:i:s",  strtotime("Now"));
                        $modelcnitem->created_by = '38';
                        $modelcnitem->updated_dt = date("Y-m-d H:i:s",  strtotime("Now"));
                        $modelcnitem->updated_by = '38';
                        $modelcnitem->save();
                    }
                }

                Creditnote::where('id',$modelcn->id)->update(['grandtotal'=> number_format((float) $grandtotal, 2, '.', '') ]);

                    $log = new Log();
                    $log->user_id='38';
                    $log->action_datetime = date("Y-m-d H:i:s",  strtotime("Now"));
                    $log->menu_id=$this->menu_id;
                    $log->action_type='create';
                    $log->data_uuid=$modelcn->uuid;
                    $log->save();

                // $log->insertlog($this->menu_id, 'create', $modelcn->uuid);

                 $model->cn_id= $modelcn->id;
                 $model->save();

            }

        return redirect()->back()->with('success', 'Credit Note has been generated');
        }
    }


    //Sales Retrun Log

    public function salesReturnLog($uuid)
    {
        $searchModel = DB::select("SELECT al.data_uuid AS uuid,al.action_datetime,al.action_type,m.title, m.status,m.url,u.name,u.role,pl.label
                                    FROM log al
                                    LEFT JOIN axis_user u ON al.user_id = u.id
                                    LEFT JOIN menu m ON al.menu_id = m.id
                                    LEFT JOIN param_list pl ON u.role = pl.code
                                    WHERE al.data_uuid='$uuid' ORDER BY al.action_datetime DESC");

     return view('finance.salesReturn.log',['clog'=>$searchModel,'stores'=> $this->stores(),'skipHeader' => 1]);

    }



    //Return Stock Handling

    public function returnStockHandling( Request $request)
    {
        if ($request->route('account'))
        {
            $account = $request->route('account');
        }
        $keys = ['checked', 'pending'];
        return view('finance.returnStockHandling.home', ['stores' => $this->stores() , 'page' => 'finance.returnStockHandling.cde-returnStockHandling' , 'keys' => $keys, 'title' => 'return stock', 'form' => 'Analysis', 'skipHeader' => 1]);
    }
    //set ajax for return stock
    public function getAjaxReturnStockHandling( Request $request)
    {
        $column = [];
        static $count = 0;

        $input = $request->all();

         $query= 'SELECT DISTINCT grn.id AS id, grn.received_dt AS rcvdate, p.name AS productname, grn.status_inspection , grn.quantity, grn.resale_qty, grn.noresale_qty, grn.noresale_action
                  FROM grn
                  LEFT JOIN company_access ca ON ca.com_id = grn.com_id
                  LEFT JOIN company c ON grn.com_id=c.id
                  LEFT JOIN product p ON grn.product_id=p.id
                  LEFT JOIN delivery_order deo ON grn.do_id=deo.id
                  ORDER BY grn.id DESC';

         $data =DB::select($query);


        foreach ($data  as $value)
        {
            $count++;
            $column[$value->status_inspection][]= $this->setRowReturnStockHandling($value, $count);
        }
        return ['data' => $column];
    }

    // set row for return stock handling

    public function setRowReturnStockHandling ($value, $count)
    {
    ob_start();
    ?>
        <tr>
        <td><?php echo $count; ?></td>
        <td>
            <fieldset>
                <div class="checkbox checkbox-info checkbox-glow">
                    <input type="checkbox" name="data[]" id="ship_<?php echo $value->id ?>" value="<?php echo $value->id ?>" >
                    <label for="ship_<?php echo $value->id ?>"></label>
                </div>
            </fieldset>
         </td>

        <td><?php echo date("d M Y", strtotime($value->rcvdate)) ?></td>
        <td><?php echo $value->productname ?></td>
        <td><?php echo $value->status_inspection ?></td>
        <td><?php echo $value->quantity ?></td>
        <td><?php echo $value->resale_qty ?></td>
        <td><?php echo $value->noresale_qty ?></td>
        <td><?php echo $value->noresale_action ?></td>
        <td><?php
            if($value->status_inspection == 'pending')
              echo'<a   href="#" class="mr-1"> <i class="far fa-edit fa-lg text-success"></i> </a>'
             ?>
        </td>

        </tr>
        <?php
            $html = ob_get_contents();
            ob_end_clean();
            return $this->minify_html($html);

    }

//****************************Sales Starts Here **************************/

// Sales Invoice Overview

    public function salesInvoiceOverview( Request $request)
    {
        if ($request->route('account'))
        {
            $account = $request->route('account');
        }

         $companies = DB::table('company AS c')
                      ->select('c.id', 'c.logo', 'c.name')
                      ->leftJoin('company_access AS a' ,'a.com_id' ,'=' ,'c.id')
                      ->WHERE( 'a.user_id', '=' , 38 ) ->orderBy('c.name', 'ASC')->get(); //id hard coded need to change with auth User id

        $keys = ['Pending', 'Paid'];
        return view('finance.salesInvoice.overview', ['stores' => $this->stores() , 'keys' => $keys, 'page' => 'finance.salesInvoice.cde-sales-invoice-overview', 'title' => 'sales invoice overview', 'form' => 'Analysis', 'skipHeader' => 1, 'companies' =>$companies]);
    }

    //get Sales Invoice Overview

 public function getAjaxSalesInvoiceOverview( Request $request)
    {
        $column = [];
        static $count = 0;

        $input = $request->all();

         $query= 'SELECT DISTINCT i.id, i.doc_dt, i.uuid, i.payment_due, i.invoice_no, i.type,
                    i.customer_name,  i.payment_status,  i.grandtotal, u.name AS username, u.role
                    FROM invoice i
                    LEFT JOIN company_access ca ON ca.com_id=i.com_id
                    LEFT JOIN company c ON i.com_id=c.id
                    LEFT JOIN axis_user u ON u.id=i.sales_pic
                    ORDER BY i.id DESC';

         $data =DB::select($query);


        foreach ($data as $value)
        {
            $count++;
            $column[$value->payment_status][]= $this->setRowSalesInvoiceOverview($value, $count);
        }
        return ['data' => $column];
    }


    // set row for sales Invoice Overview
    public function setRowSalesInvoiceOverview ($value, $count)
    {
    ob_start();
    ?>
        <tr>
        <td><?php echo $count; ?></td>
        <td>
            <fieldset>
                <div class="checkbox checkbox-info checkbox-glow">
                    <input type="checkbox" name="data[]" id="ship_<?php echo $value->id ?>" value="<?php echo $value->id ?>" >
                    <label for="ship_<?php echo $value->id ?>"></label>
                </div>
            </fieldset>
         </td>


        <td><?php echo date("d M Y", strtotime($value->doc_dt)) ?></td>
        <td><?php echo date("d M Y", strtotime($value->payment_due)) ?></td>
        <td><?php  echo $value->invoice_no ?></td>
        <td><?php if ($value->type == 'do')
                  echo "Sales Invoice";
                  if ($value->type == 'manual')
                  echo "Service Invoice";
                  if ($value->type == 'cash')
                  echo "Cash Sales Invoice";
                  if ($value->type == 'reseller')
                  echo "Reseller Extra Invoice";
                  ?></td>
        <td><?php echo $value->customer_name?></td>
        <td><?php

            // if($value->role == 'mgt' || $value->role == 'Admin' || $value->role == 'Fin'){
            //     if ($value->payment_status == 'Pending'){
            //         if($value->type != 'reseller')
            //            {
            //             echo
            //             $value->payment_status,  '<a href="#"><i class="fas fa-check-square fa-lg kt-font-success"> </i></a>';
            //            }
            //             else{
            //             // reseller
            //               echo $value->payment_status, 'hello2', '<a href="#"><i class="fas fa-check-square fa-lg kt-font-success"></i></a>';
            //              }
            //          //echo $value->payment_status;

            //     }elseif($value->payment_status == 'Partial'){
            //         if($value->type = 'reseller'){

            //             echo $value->payment_status, 'hello3', '<a  href="#"><i class="fas fa-check-square fa-lg kt-font-success"></i></a>';
            //             //view
            //             echo $value->payment_status, 'hello4','<a  href="#"><i class="fas fa-check-square fa-lg kt-font-success"></i></a>';
            //         }else{
            //             //reseller
            //             echo $value->payment_status, 'hello5', '<a  href="#"><i class="fas fa-check-square fa-lg kt-font-success"></i></a>';
            //             echo $value->payment_status, 'hello6', '<a  href="#"><i class="fas fa-check-square fa-lg kt-font-success"></i></a>';
            //         }
            //         //echo $value->payment_status;

            //     }else
            //         {
            //             if($value->type != 'reseller')
            //             {
            //                 echo $value->payment_status, '<a  href="#"></i></a>';
            //             }else
            //             {
            //                 echo $value->payment_status,  '<a  href="#"><i class="fas fa-check-square fa-lg kt-font-success"></i></a>';
            //             }
            //        }

            // }else{
            //       echo $value->payment_status, 'hello9', '<a  href="#"></a>';
            //      }

                 echo $value->payment_status, '<a  href="#"></a>';

               ?>
             </td>
        <td><?php echo 'MYR ' .number_format((float) $value->grandtotal, 2, '.', ',')?></td>
        <td><?php echo $value->username?></td>
        <td><?php

            // download
              if ($value->type == 'do')
               echo'<a  href="sales-invoice-download/'.$value->uuid,'" class="mr-1" target="_blank"> <i class="fas fa-file-download fa-lg text-brand"></i> </a>';
              if ($value->type == 'manual')
               echo '<a href="service-invoice-download/'.$value->uuid,'" class="mr-1" target="_blank"> <i class="fas fa-file-download fa-lg text-brand"></i> </a>';
              if ($value->type == 'cash')
               echo '<a href="cash-invoice-download/'.$value->uuid,'" class="mr-1" target="_blank"> <i class="fas fa-file-download fa-lg text-brand"></i> </a>';
              if ($value->type == 'reseller')
               echo '<a  href="reseller-invoice-download/'.$value->uuid,'" class="mr-1" target="_blank"> <i class="fas fa-file-download fa-lg text-brand"></i> </a>';

             //update
              if ($value->type == 'do')
               echo '<a   href="invoice-update/'.$value->uuid,'" class="mr-1"> <i class="far fa-edit fa-lg text-success"></i> </a>';
              if ($value->type == 'manual')
               echo '<a   href="#" class="mr-1"> <i class="far fa-edit fa-lg text-success"></i> </a>';
              if ($value->type == 'cash')
               echo '<a  href="#" class="mr-1"> <i class="far fa-edit fa-lg text-success"></i> </a>';
              if ($value->type == 'reseller')
               echo '<a   href="#" class="mr-1"> <i class="far fa-edit fa-lg text-success"></i> </a>';

              //log
              if ($value->type == 'do')
               echo '<a href="sales-invoice-changelog/'.$value->uuid,'" class="mr-1"> <i class="fas fa-history fa-lg text-warning"></i> </a>';
              if ($value->type == 'manual')
               echo '<a href="service-invoice-changelog/'.$value->uuid,'" class="mr-1"> <i class="fas fa-history fa-lg text-warning"></i> </a>';
              if ($value->type == 'cash')
               echo '<a href="cash-sale-invoice-changelog/'.$value->uuid,'" class="mr-1"> <i class="fas fa-history fa-lg text-warning"></i> </a>';
              if ($value->type == 'reseller')
               echo '<a href="#" class="mr-1"> <i class="fas fa-history fa-lg text-warning"></i> </a>';

              echo  '<a href="delete-invoice/'.$value->id,'"> <i class="far fa-trash-alt text-danger" style="cursor:pointer";  onclick ="return confirm(\'Are You sure want to delete?\')"></i> </a>';
              ?>
        </td>


        </tr>
        <?php
            $html = ob_get_contents();
            ob_end_clean();
            return $this->minify_html($html);

    }

//delete Invoice
    public function deleteInvoice($id)
    {
        $data = Invoice::find($id);

        if (!empty($data)) {
            $data->deleted_status = 1;
            if ($data->save()) {
                $Log = new Log();
                $Log->insertlog($this->menu_id4, 'delete', $data->uuid);
            }
        }
       $data->delete();


        return redirect()->back()->with('success', 'Invoice Deleted Successfully');
    }


    // Sales Invoice
    public function salesInvoice(Request $request)
    {
        if ($request->route('account'))
        {
            $account = $request->route('account');
        }

         $companies = DB::table('company AS c')
                      ->select('c.id', 'c.logo', 'c.name')
                      ->leftJoin('company_access AS a' ,'a.com_id' ,'=' ,'c.id')
                      ->WHERE( 'a.user_id', '=' , 38 ) ->orderBy('c.name', 'ASC')->get(); //id hard coded need to change

       //do number from delivery order table
        $query = 'SELECT deo.id, deo.doc_no
                   FROM delivery_order deo
                   LEFT JOIN company_access a ON a.com_id=deo.company_id
                   LEFT JOIN invoice i ON i.do_no=deo.id
                   WHERE a.user_id = 38 AND i.id IS null ORDER BY deo.doc_no ASC'; //Auth User id

         $doArr= DB::select($query);

        $keys = ['Pending', 'Paid'];
        return view('finance.salesInvoice.salesInvoice', ['stores' => $this->stores() , 'keys' => $keys, 'page' => 'finance.salesInvoice.cde-sales-invoice', 'title' => 'sales invoice', 'form' => 'Analysis', 'skipHeader' => 1, 'companies' =>$companies, 'doArr'=>$doArr]);
    }

    //set  Ajax Sales Invoice

    public function getAjaxSalesInvoice (Request $request)
    {
        $column = [];
        static $count = 0;

        $input = $request->all();

         $query= 'SELECT DISTINCT i.id, i.doc_dt, i.uuid, i.payment_due, i.invoice_no, i.type, i.customer_name, i.payment_status, i.grandtotal, u.name AS username, u.role
                    FROM invoice i
                    LEFT JOIN company_access ca ON ca.com_id=i.com_id
                    LEFT JOIN company c ON i.com_id=c.id
                    LEFT JOIN delivery_order deo ON deo.id=i.do_no
                    LEFT JOIN delivery_order_item doi ON deo.id=doi.doc_id
                    LEFT JOIN product p ON doi.product_id=p.id
                    LEFT JOIN axis_user u ON u.id=i.sales_pic
                    WHERE i.type = "do" ORDER BY i.id DESC';

         $data =DB::select($query);


        foreach ($data as $value)
        {
            $count++;
            $column[$value->payment_status][]= $this->setRowSalesInvoice ($value, $count);
        }
        return ['data' => $column];
    }


    // set row for sales Invoice

    public function setRowSalesInvoice($value, $count)
    {
    ob_start();
    ?>
        <tr>
        <td><?php echo $count; ?></td>
        <td>
            <fieldset>
                <div class="checkbox checkbox-info checkbox-glow">
                    <input type="checkbox" name="data[]" id="ship_<?php echo $value->id ?>" value="<?php echo $value->id ?>" >
                    <label for="ship_<?php echo $value->id ?>"></label>
                </div>
            </fieldset>
         </td>


        <td><?php echo date("d M Y", strtotime($value->doc_dt)) ?></td>
        <td><?php echo date("d M Y", strtotime($value->payment_due)) ?></td>
        <td><?php echo $value->invoice_no ?></td>
        <td><?php echo $value->customer_name?></td>
        <td><?php echo $value->payment_status ?></td>
        <td><?php echo 'MYR ' .number_format((float) $value->grandtotal, 2, '.', ',')?></td>
        <td><?php
               echo $value->username

               ?>
               </td>

        <td><?php
              echo
                '<div class ="d-flex justify-content-between">
                 <a   href="sales-invoice-download/'.$value->uuid,'" target="_blank"> <i class="fas fa-file-download fa-lg text-brand"></i> </a>
                 <a   href="invoice-update/'.$value->uuid,'"> <i class="far fa-edit fa-lg text-success"></i> </a>
                 <a   href="sales-invoice-changelog/'.$value->uuid,'"> <i class="fas fa-history fa-lg text-warning"></i> </a>
                 <a   href="delete-invoice/'.$value->id,'"> <i class="far fa-trash-alt text-danger" style="cursor:pointer";  onclick ="return confirm(\'Are You sure want to delete?\')"></i> </a>
                 </div>';
          ?>
        </td>


        </tr>
        <?php
            $html = ob_get_contents();
            ob_end_clean();
            return $this->minify_html($html);
    }

// Service Invoice

    public function serviceInvoice(Request $request)
    {
        if ($request->route('account'))
        {
            $account = $request->route('account');
        }

         $companies = DB::table('company AS c')
                      ->select('c.id', 'c.logo', 'c.name')
                      ->leftJoin('company_access AS a' ,'a.com_id' ,'=' ,'c.id')
                      ->WHERE( 'a.user_id', '=' , 38 ) ->orderBy('c.name', 'ASC')->get(); //id hard coded need to change


        $keys = ['Pending', 'Paid'];
        return view('finance.serviceInvoice.home', ['stores' => $this->stores() , 'keys' => $keys, 'page' => 'finance.serviceInvoice.cde-service-invoice', 'title' => 'service invoice', 'form' => 'Analysis', 'skipHeader' => 1, 'companies' =>$companies]);
    }


    // Ajax Service Invoice

    public function getAjaxServiceInvoice (Request $request)
    {
        $column = [];
        static $count = 0;

        $input = $request->all();

         $query= 'SELECT DISTINCT i.id,i.doc_dt, i.uuid, i.payment_due,i.invoice_no,i.type,i.customer_name, i.payment_status, i.grandtotal,u.name AS username, u.role
         FROM invoice i
         LEFT JOIN company_access ca ON ca.com_id=i.com_id
         LEFT JOIN company c ON i.com_id=c.id
         LEFT JOIN axis_user u ON u.id=i.sales_pic
         WHERE i.type = "manual" ORDER BY i.id DESC';

         $data =DB::select($query);


        foreach ($data as $value)
        {
            $count++;
            $column[$value->payment_status][]= $this->setRowServiceInvoice ($value, $count);
        }
        return ['data' => $column];
    }


    // set row for Service Invoice

    public function setRowServiceInvoice($value, $count)
    {
    ob_start();
    ?>
        <tr>
        <td><?php echo $count; ?></td>
        <td>
            <fieldset>
                <div class="checkbox checkbox-info checkbox-glow">
                    <input type="checkbox" name="data[]" id="ship_<?php echo $value->id ?>" value="<?php echo $value->id ?>" >
                    <label for="ship_<?php echo $value->id ?>"></label>
                </div>
            </fieldset>
         </td>

        <td><?php echo date("d M Y", strtotime($value->doc_dt)) ?></td>
        <td><?php echo date("d M Y", strtotime($value->payment_due)) ?></td>
        <td><?php echo $value->invoice_no ?></td>
        <td><?php echo $value->customer_name?></td>
        <td><?php echo $value->payment_status ?></td>
        <td><?php echo 'MYR ' .number_format((float) $value->grandtotal, 2, '.', ',')?></td>
        <td><?php echo $value->username?></td>
        <td><?php
             echo
                '<div class ="d-flex justify-content-between">
                    <a   href="service-invoice-download/'.$value->uuid,'" target="_blank"> <i class="fas fa-file-download fa-lg text-brand"></i> </a>
                    <a   href="#"> <i class="far fa-edit fa-lg text-success"></i> </a>
                    <a   href="service-invoice-changelog/'.$value->uuid,'"> <i class="fas fa-history fa-lg text-warning"></i> </a>
                    <a   href="delete-invoice/'.$value->id,'"> <i class="far fa-trash-alt text-danger" style="cursor:pointer";  onclick ="return confirm(\'Are You sure want to delete?\')"></i> </a>
                </div>';
            ?>
        </td>

        </tr>
        <?php
            $html = ob_get_contents();
            ob_end_clean();
            return $this->minify_html($html);
    }


    //Cash Sales Invoice

    public function cashSalesInvoice (Request $request)
    {
        if ($request->route('account'))
        {
            $account = $request->route('account');
        }

         $companies = DB::table('company AS c')
                      ->select('c.id', 'c.logo', 'c.name')
                      ->leftJoin('company_access AS a' ,'a.com_id' ,'=' ,'c.id')
                      ->WHERE( 'a.user_id', '=' , 38 ) ->orderBy('c.name', 'ASC')->get(); //id hard coded need to change

        $keys = ['Pending', 'Paid'];
        return view('finance.cashSalesInvoice.home', ['stores' => $this->stores() , 'keys' => $keys, 'page' => 'finance.cashSalesInvoice.cde-cash-sales-invoice', 'title' => 'service invoice', 'form' => 'Analysis', 'skipHeader' => 1, 'companies' =>$companies]);
    }


    // Ajax Cash Sales Invoice

    public function getAjaxCashSalesInvoice(Request $request)
    {
        $column = [];
        static $count = 0;

        $input = $request->all();

         $query= 'SELECT DISTINCT i.id,i.doc_dt,i.payment_due, i.uuid, i.invoice_no,i.type,i.customer_name, i.payment_status, i.grandtotal,u.name AS username, u.role
                    FROM invoice i
                    LEFT JOIN company_access ca ON ca.com_id=i.com_id
                    LEFT JOIN company c ON i.com_id=c.id
                    LEFT JOIN axis_user u ON u.id=i.sales_pic
                    WHERE i.type="cash" ORDER BY i.id DESC';

         $data =DB::select($query);


       foreach ($data as $value)
        {
            $count++;
            $column[$value->payment_status][]= $this->setRowCashSalesInvoice ($value, $count);
        }
        return ['data' => $column];
    }

    // Set row cash sales invoice
    public function setRowCashSalesInvoice ($value, $count)
    {
    ob_start();
    ?>
        <tr>
        <td><?php echo $count; ?></td>
        <td>
            <fieldset>
                <div class="checkbox checkbox-info checkbox-glow">
                    <input type="checkbox" name="data[]" id="ship_<?php echo $value->id ?>" value="<?php echo $value->id ?>" >
                    <label for="ship_<?php echo $value->id ?>"></label>
                </div>
            </fieldset>
         </td>

        <td><?php echo date("d M Y", strtotime($value->doc_dt)) ?></td>
        <td><?php echo date("d M Y", strtotime($value->payment_due)) ?></td>
        <td><?php echo $value->invoice_no ?></td>
        <td><?php echo $value->customer_name?></td>
        <td><?php echo $value->payment_status ?></td>
        <td><?php echo 'MYR ' .number_format((float) $value->grandtotal, 2, '.', ',')?></td>
        <td><?php echo $value->username?></td>
        <td><?php
               echo
                '<div class ="d-flex justify-content-between">
                        <a   href="cash-invoice-download/'.$value->uuid,'" target="_blank"> <i class="fas fa-file-download fa-lg text-brand"></i> </a>
                        <a   href="edit-cash-sale-invoice/'.$value->uuid,'"> <i class="far fa-edit fa-lg text-success"></i> </a>
                        <a   href="cash-sale-invoice-changelog/'.$value->uuid,'"> <i class="fas fa-history fa-lg text-warning"></i> </a>
                        <a   href="delete-invoice/'.$value->id,'"> <i class="far fa-trash-alt text-danger" style="cursor:pointer";  onclick ="return confirm(\'Are You sure want to delete?\')"></i> </a>
                    </div>';


            ?>

         </td>

        </tr>
        <?php
            $html = ob_get_contents();
            ob_end_clean();
            return $this->minify_html($html);
    }

 //Reseller Invoice

   public function resellerInvoice (Request $request)
   {
    if ($request->route('account'))
    {
        $account = $request->route('account');
    }

     $companies = DB::table('company AS c')
                  ->select('c.id', 'c.logo', 'c.name')
                  ->leftJoin('company_access AS a' ,'a.com_id' ,'=' ,'c.id')
                  ->WHERE( 'a.user_id', '=' , 38 ) ->orderBy('c.name', 'ASC')->get(); //id hard coded need to change

     //dd($companies );
    $keys = ['Pending', 'Paid'];
    return view('finance.resellerInvoice.home', ['stores' => $this->stores() , 'keys' => $keys, 'page' => 'finance.resellerInvoice.cde-reseller-invoice', 'title' => 'service invoice', 'form' => 'Analysis', 'skipHeader' => 1, 'companies' =>$companies]);
   }


   // Ajax Reseller Invoice

   public function getAjaxResellerInvoice ( Request $request)
   {
        $column = [];
        static $count = 0;

        $input = $request->all();

        $query= 'SELECT DISTINCT i.id,i.doc_dt,i.payment_due, i.uuid, i.invoice_no,i.type,i.customer_name, i.payment_status, i.grandtotal,u.name AS username, u.role
                            FROM invoice i
                            LEFT JOIN company_access ca ON ca.com_id=i.com_id
                            LEFT JOIN company c ON i.com_id=c.id
                            LEFT JOIN axis_user u ON u.id=i.sales_pic
                            WHERE i.type="reseller"
                            ORDER BY i.id DESC';

        $data =DB::select($query);


    foreach ($data as $value)
        {
            $count++;
            $column[$value->payment_status][]= $this->setRowResellerInvoice ($value, $count);
        }
        return ['data' => $column];
   }

   // set row reseller Invoice

   public function setRowResellerInvoice ($value, $count)
   {
   ob_start();
   ?>
       <tr>
       <td><?php echo $count; ?></td>
       <td>
           <fieldset>
               <div class="checkbox checkbox-info checkbox-glow">
                   <input type="checkbox" name="data[]" id="ship_<?php echo $value->id ?>" value="<?php echo $value->id ?>" >
                   <label for="ship_<?php echo $value->id ?>"></label>
               </div>
           </fieldset>
        </td>

       <td><?php echo date("d M Y", strtotime($value->doc_dt)) ?></td>
       <td><?php echo date("d M Y", strtotime($value->payment_due)) ?></td>
       <td><?php echo $value->invoice_no ?></td>
       <td><?php echo $value->customer_name?></td>
       <td><?php echo $value->payment_status ?></td>
       <td><?php echo 'MYR ' .number_format((float) $value->grandtotal, 2, '.', ',')?></td>
       <td><?php echo $value->username?></td>
       <td><?php echo
                '<div class ="d-flex justify-content-between">
                        <a   href="reseller-invoice-download/'.$value->uuid,'" target="_blank"> <i class="fas fa-file-download fa-lg text-brand"></i> </a>
                        <a   href="#"> <i class="far fa-edit fa-lg text-success"></i> </a>
                        <a   href="reseller-invoice-changelog/'.$value->uuid,'"> <i class="fas fa-history fa-lg text-warning"></i> </a>
                        <a   href="delete-invoice/'.$value->id,'"> <i class="far fa-trash-alt text-danger" style="cursor:pointer";  onclick ="return confirm(\'Are You sure want to delete?\')"></i> </a>
                </div>';

           ?>
        </td>

       </tr>
       <?php
           $html = ob_get_contents();
           ob_end_clean();
           return $this->minify_html($html);
   }

}










