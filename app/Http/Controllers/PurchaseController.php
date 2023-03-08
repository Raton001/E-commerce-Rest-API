<?php

namespace App\Http\Controllers;

use App\AxisUser;
use App\Company;
use App\Log;
use App\Notes;
use App\ParamList;
use App\Purchase;
use App\PurchaseItem;
use App\Quotation;
use App\QuotationItem;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use NumberFormatter;
use Ramsey\Uuid\Uuid;
use Svg\Tag\Rect;

class PurchaseController extends Controller
{
    public $menu_id = 42;
    public $menu_id2 = 49; //purchase order menu id

    public function quotation(Request $request)
    {
        if ($request->route('account'))
        {
            $account = $request->route('account');
        }

         $companies = DB::table('company AS c')
                      ->select('c.id', 'c.logo', 'c.name')
                      ->leftJoin('company_access AS a' ,'a.com_id' ,'=' ,'c.id')
                      ->WHERE( 'a.user_id', '=' , 38 ) ->orderBy('c.name', 'ASC')->get(); //id hard coded need to change

        $keys = ['10','4','1','2', '3', '11','12','13','14'];
        return view('finance.purchase.quotation', ['stores' => $this->stores() , 'keys' => $keys, 'page' => 'finance.purchase.cde-quotation', 'title' => 'quotation', 'form' => 'Analysis', 'skipHeader' => 1, 'companies' =>$companies]);
    }



    //set ajax for quatation
    public function getAjaxQuotation(Request $request)
    {
        $column =[];
        static $count = 0;

        $input = $request->all();

         $query= "SELECT q.id, q.uuid, q.company_id, q.doc_date, q.valid_dt, q.doc_no, q.customer_name FROM quotation q
                  LEFT JOIN company_access a ON a.com_id=q.company_id
                  LEFT JOIN company c ON q.company_id = c.id
                  GROUP BY q.id ORDER BY q.id DESC";

         $data =DB::select($query);

        foreach ($data as $value)
        {
            $count++;
            $column[$value->company_id][] = $this->setRowQuotation($value, $count);
        }
        return ['data' => $column];

    }

    //set row
    public function setRowQuotation($value, $count)
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

        <td><?php echo date("d M Y", strtotime($value->doc_date)) ?></td>
        <td><?php echo date("d M Y", strtotime($value->valid_dt))?></td>
        <td><?php echo $value->doc_no?></td>
        <td><?php

              $Company = new Company();

               echo $Company->getname($value->company_id);

             ?>
        </td>
        <td><?php echo $value->customer_name ?></td>
        <td><?php echo
                '<div class ="d-flex justify-content-between">
                    <a href="download-quotation/'. $value->uuid ,'" target="_blank"> <i class="fas fa-file-download fa-lg text-brand"></i> </a>
                    <a href="update-quotation/'.$value->uuid,'"> <i class="far fa-edit fa-lg text-success"></i> </a>
                    <a href="log-quotation/'.$value->uuid ,'"> <i class="fas fa-history fa-lg text-warning"></i> </a>
                </div>';
             ?>
        </td>
        <td>

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



    //add quotation form
    public function addQuotation(Request $request)
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

        return view('finance.purchase.add-quotation',
                    ['stores' => $this->stores() , 'page' => 'finance.salesReturn.cde-salesreturn',
                    'companies'=>$companies, 'sales_pic'=>$sales_pic, 'customers'=>$customers,
                    'states'=>$states, 'title' => 'add quotation',
                    'form' => 'Analysis', 'skipHeader' => 1]);
    }

    //create quotation
    public function createQuotation(Request $request)
    {
          $request->validate([
            'company_id' => 'required',
            'customer_name'=>'required',
            'doc_dt'=>'required',
            'valid_dt'=>'required'
            ]);

        $Company = new Company();
        $model = new Quotation();

        $output='';
        $doc ='';

        $countdoc = DB::table('quotation')->where('company_id', $request->company_id)
                   ->count() + 1;

        $doc_no = DB::table('company')->select('short_code')
                 ->where('id', $request->company_id)->get();

         foreach($doc_no as $value){
             $doc= $value->short_code;
         }
         $abc = $doc;

        $model->doc_date = $request->doc_dt;
        $model->valid_dt = $request->valid_dt;
        $model->created_dt = date("Y-m-d H:i:s",  strtotime("Now"));
        $model->created_by = 38; // should be auth user id
        $model->updated_dt = date("Y-m-d H:i:s",  strtotime("Now"));
        $model->updated_by = 38; // should be auth user id
        $model->uuid = Uuid::uuid4();
        $model->doc_no = $abc . '/QUO/' . date("y") . '/' . sprintf("%05d", $countdoc);
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

        if ($model->save()){
            $Log = new Log();
            $Log->insertlog($this->menu_id, 'create', $model->uuid);
        }

        if($model->save()){

        $button1 = '<button class="btn btn-outline-warning mt-3" type="button" data-toggle="modal" data-target="#Modal"  id="add" onclick="addproduct()" > Add Product </button>';
        $button2 = '<button class="btn btn-outline-info mt-3" type="button"   id="view" onclick="view()" > View Quotation  </button>';

        }

        return response()->json([$model, $button1, $button2]);

    }


    //add product quotation
    public function addProductQuotation(Request $request)
    {
        $model = new QuotationItem();

        $model->doc_id = $request->doc_id;
        $model->product_name = $request->product_name;
        $model->quantity = $request->quantity;
        $model->uom = $request->uom;
        $model->unit_price = $request->unit_price;
        $model->total_price = $request->total_price;
        $model->created_dt = date("Y-m-d H:i:s",  strtotime("Now"));
        $model->created_by = 38; //should be auth user id
        $model->updated_dt = date("Y-m-d H:i:s",  strtotime("Now"));
        $model->updated_by = 38; //should be auth user id

        if ($model->save())
            return '1';
        else
            return '0';

    }


    //update product list for quotation
   public function updateProductListQuotation(Request $request)
    {
        $doc_id = $request->doc_id;

        $output = '';

        $model = QuotationItem::where('doc_id' , $doc_id)->orderBy('id' , 'ASC')->get();

        if (!empty($model)) {
            $count = 0;
            $output .= '<table class="table table-bordered table-striped">
            <thead>
            <tr class="thead-dark">
            <th width="1%">No</th>
            <th>Description</th>
            <th width="10%">Qty</th>
            <th width="10%">UoM</th>
            <th width="10%">Unit Price</th>
            <th width="10%">Total</th>
            <th width="1%">Action</th>
            </tr>
            </thead><tbody>';
            foreach ($model as $row) {
                $count++;
                $output .= '<tr>
                <td>' . $count . '</td>
                <td>' . $row->product_name . '</td>
                <td>' . number_format($row->quantity) . '</td>
                <td>' . $row->uom . '</td>
                <td>RM ' . number_format((float) $row->unit_price, 2, '.', ',') . '</td>
                <td>RM ' . number_format((float) $row->total_price, 2, '.', ',') . '</td>
                <td align="center"><i class="fa-solid fa-trash-can" style="cursor:pointer;" title="Delete product" onclick="deleteproduct(' . $row->id . ')"></i></td>
                </tr>';
            }
            $output .= '</tbody></table>';
        }
          return $output;

    }


    //delete product quotation
    public function deleteProductQuotation(Request $request)
    {
        $status = 'failed';
        $id = $request->id;

        if (QuotationItem::find($id)->delete())
            $status = 'success';
        return $status;
    }


    //download quotation
    public function downloadQuotation(Request $request)
    {
       $uuid = $request->uuid;
       $ParamList = new ParamList();
       $User = new AxisUser();
       $Notes = new Notes();

       $model = Quotation::where('uuid', $uuid)->first();


        if (!empty($model)){
         $modelcom = Company::find($model->company_id);
          if (!empty($modelcom)){

            $header = '<table id="table-header" border="0" width="100%" cellpadding="0" cellspacing="0">
            <tr>
            <td rowspan="2"><img src="/assets/images/company_logo/' . $modelcom->logo . '" height="80"></td>
            <td align="right"><b>' . $modelcom->name . ' ('.$modelcom->reg_no.')</b></td>
            </tr>
            <tr>
            <td align="right" style="font-size:9px;">' . $modelcom->address1 .'<br> '.$modelcom->address2.'<br>'.
            $modelcom->postcode.' '.$modelcom->city.', '.$ParamList->getlabel(3,$modelcom->state).', Malaysia<br>
            (T) '.$modelcom->phone_no.'  (F) '.$modelcom->fax_no.'<br></td>
            </tr>
            </table>';
            $content = '<br><table border="0" width="100%" cellpadding="0" cellspacing="0">
            <tr>
            <td width="50%" valign="top">
            <br/><b>TO</b>
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
            <tr><td colspan="3" align="center"><h4><b>QUOTATION</b></h4><br/><br/></td></tr>
            <tr>
            <td valign="top">Date</td>
            <td valign="top">&nbsp;&nbsp;:&nbsp;&nbsp;</td>
            <td valign="top" align="left">' . date("dS M Y", strtotime($model->doc_date)) . '</td>
            </tr>
            <tr>
            <td valign="top">Quotation No</td>
            <td valign="top">&nbsp;&nbsp;:&nbsp;&nbsp;</td>
            <td valign="top" align="left">' . $model->doc_no . '</td>
            </tr>
            <tr>
            <td valign="top">Valid until</td>
            <td valign="top">&nbsp;&nbsp;:&nbsp;&nbsp;</td>
            <td valign="top" align="left">' . date("dS M Y", strtotime($model->valid_dt)) . '</td>
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
            $content .= '<table width="100%" cellpadding="5" cellspacing="1" border="1" style="background-color:gray;" id="table-product">
            <thead>
            <tr>
            <th width="5%">No</th>
            <th>Description</th>
            <th width="10%">Qty</th>
            <th width="10%">UoM</th>
            <th width="13%">Unit Price</th>
            <th width="13%">Total</th>
            </tr>
            </thead>
            <tbody>';
            $modelItem = QuotationItem::where('doc_id', $model->id)->orderBy('id' , 'ASC')->get();
            if (!empty($modelItem)){
                $countItem = 0;

                foreach ($modelItem as $rowItem){

                    $countItem++;
                    $content .= '<tr>
                    <td  align="center">' . $countItem . '</td>
                    <td>' . $rowItem->product_name . '</td>
                    <td>' . number_format($rowItem->quantity) . '</td>
                    <td>' . $rowItem->uom . '</td>
                    <td>RM ' . number_format((float) $rowItem->unit_price, 2, '.', ',') . '</td>
                    <td>RM ' . number_format((float) $rowItem->total_price, 2, '.', ',') . '</td>
                    </tr>';

                }
            } else {
                $content .= '<tr><td colspan="6">No record found</td></tr>';
            }
            $company_stamp = '<tr><td align="center" height="110"></td></tr>';
            if (!empty($modelcom->stamp))
            $company_stamp = '<tr><td align="center"><img src="/assets/images/company_logo/' . $modelcom->stamp . '" height="100"></td></tr>';

            $content .= '</tbody></table>';
            $content .= '<br><div style="font-size:15px;line-height:1.7em;">' . $Notes->getnotes('Quotation', $modelcom->id) . '</div>
            <table width="40%">' . $company_stamp . '<tr><td align="center" style="border-top:1px solid black;">Authorized Signature</td></tr></table>';

            $footer = '<table id="table-footer" border="0" width="100%" cellpadding="0" cellspacing="0">
            <tr><td align="center" style="font-size:15px;"><b>' . $modelcom->tagline . '</b><br>' . $modelcom->website . '
            </td>
            </tr>
            </table>';

        }
        return $header . $content . $footer ;

    }


}


//log quotation

  public function logQuotation(Request $request)
  {
     $uuid =  $request->uuid;

      $searchModel = DB::select("SELECT al.data_uuid AS uuid,al.action_datetime,al.action_type,m.title, m.status,m.url,u.name,u.role,pl.label
                    FROM log al
                    LEFT JOIN axis_user u ON al.user_id = u.id
                    LEFT JOIN menu m ON al.menu_id = m.id
                    LEFT JOIN param_list pl ON u.role = pl.code
                    WHERE al.data_uuid='$uuid' ORDER BY al.action_datetime DESC");

        return view('finance.purchase.log-quotation',['clog'=>$searchModel,'stores'=> $this->stores(),'skipHeader' => 1]);
  }



  //update quotation
  public function updateQuotation(Request $request)
  {
    if ($request->route('account'))
        {
            $account = $request->route('account');
        }

        $model = Quotation::where('uuid', $request->uuid)->first();

        //company
        $modelcom = DB::table('company AS c')
                       ->select('c.id AS company_id','c.name AS name')
                       ->leftJoin('quotation AS q', 'c.id', '=', 'q.company_id')
                       ->where('c.id', $model->company_id)->first();
        //company list
        $companies = DB::table('company AS c')
                        ->select('c.id AS company_id','c.name AS name')
                        ->leftJoin('company_access AS a' ,'a.com_id' ,'=' ,'c.id')
                        ->where( 'a.user_id', '=' , 38 )
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

        return view('finance.purchase.update-quotation',
                    ['stores' => $this->stores() , 'page' => 'finance.salesReturn.cde-salesreturn',
                    'companies'=>$companies, 'sales_pic'=>$sales_pic, 'customers'=>$customers,
                    'states'=>$states, 'title' => 'add quotation',
                    'model'=>$model,
                    'modelcom'=>$modelcom,
                    'form' => 'Analysis', 'skipHeader' => 1]);

  }


  //save updated quotation
  public function saveupdatedQuotation(Request $request)
  {
    $request->validate([
        'company_id' => 'required',
        'customer_name'=>'required',
        'doc_dt'=>'required',
        'valid_dt'=>'required'
        ]);

    // $Company = new Company();

    $model = Quotation::where('uuid', $request->uuid)->first();


    $model->doc_date = $request->doc_dt;
    $model->valid_dt = $request->valid_dt;
    $model->created_dt = date("Y-m-d H:i:s",  strtotime("Now"));
    $model->created_by = 38; // should be auth user id
    $model->updated_dt = date("Y-m-d H:i:s",  strtotime("Now"));
    $model->updated_by = 38; // should be auth user id
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
    $model->save();

    if ($model->save()){
        $Log = new Log();
        $Log->insertlog($this->menu_id, 'update', $model->uuid);
    }
    return response()->json($model, 200);

  }




    //*********** Purchase Order *************/

    //purchase order
    public function purchaseOrder(Request $request)
    {
        if ($request->route('account'))
        {
            $account = $request->route('account');
        }

         $companies = DB::table('company AS c')
                      ->select('c.id', 'c.logo', 'c.name')
                      ->leftJoin('company_access AS a' ,'a.com_id' ,'=' ,'c.id')
                      ->WHERE( 'a.user_id', '=' , 38 ) ->orderBy('c.name', 'ASC')->get(); //id hard coded need to change


        $keys = ['1', '0'];
        return view('finance.purchase.purchaseOrder.purchase-order', ['stores' => $this->stores() , 'keys' => $keys, 'page' => 'finance.purchase.purchaseOrder.cde-purchase-order', 'title' => 'Purchase Order', 'form' => 'Analysis', 'skipHeader' => 1, 'companies' =>$companies]);
    }


  //set ajax for purchase order
   public function getAjaxPurchaseOrder(Request $request)
   {
        $column =[];
        static $count = 0;

        $input = $request->all();

        $query= 'SELECT DISTINCT p.id, p.uuid, p.doc_date, p.doc_no, c.name,p.vendor_name, p.company_id, v.status  FROM purchase p
                 LEFT JOIN vendor v ON p.vendor_id = v.id
                 LEFT JOIN company_access a ON a.com_id = p.company_id
                 LEFT JOIN company c ON p.company_id = c.id ORDER BY p.id DESC';


        $data =DB::select($query);


        foreach ($data as $value)
        {
            $count++;
            $column[$value->status][] = $this->setRowPurchaseOrder($value, $count);
        }
        return ['data' => $column];
   }


   public function setRowPurchaseOrder($value, $count)
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

       <td><?php echo date("d M Y", strtotime($value->doc_date)) ?></td>
       <td><?php echo $value->doc_no?></td>
       <td><?php

             $Company = new Company();

              echo $Company->getname($value->company_id);

            ?>
       </td>
       <td><?php echo $value->vendor_name ?></td>
       <td><?php echo
                    '<div class ="d-flex justify-content-between">
                        <a href="download-PO/'.$value->uuid,'" target="_blank"> <i class="fas fa-file-download fa-lg text-brand"></i> </a>
                        <a href="update-PO/'.$value->uuid,'"> <i class="far fa-edit fa-lg text-success"></i> </a>
                        <a href="log-PO/'.$value->uuid,'"> <i class="fas fa-history fa-lg text-warning"></i> </a>
                    </div>';
           ?>
        </td>
       <td>

       </tr>
       <?php
       $html = ob_get_contents();
       ob_end_clean();
       return $this->minify_html($html);

   }

//add purchase order
public function addPurchaseOrder(Request $request)
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

//vendor
$vendors = DB::table('vendor')->where('status', 1)->orderBy('name', 'ASC')->get();


//vendor country
$countries = DB::table('param_list')->select('code', 'label')->where('cat_id', 21)->where('status', 1)->orderBy('sort', 'ASC')->get();
// dd($countries);

return view('finance.purchase.purchaseOrder.add-purchase-order',
                    ['stores' => $this->stores() ,
                    'companies'=>$companies,  'vendors'=>$vendors,
                    'countries'=>$countries,
                    'title' => 'add purchase order',
                    'form' => 'Analysis', 'skipHeader' => 1]);


 }

 //get vendor details
 public function getVendorDetails(Request $request)
 {
    $id = $request->id;
    $details = DB::table('vendor')->find($id);
    return response()->json( $details, 200);
 }

 //create purchase order
 public function createPurchaseOrder(Request $request)
 {
    $request->validate([
        'company_id' => 'required',
        'vendor_name'=>'required',
        'doc_dt'=>'required',
        ]);

$Company = new Company();
$model = new Purchase();

$doc ='';

$countdoc = DB::table('purchase')->where('company_id', $request->company_id)
                   ->count() + 1;
// var_dump($countdoc);
$doc_no = DB::table('company')->select('short_code')
                 ->where('id', $request->company_id)->get();

         foreach($doc_no as $value){
             $doc= $value->short_code;
         }

$abc = $doc;

$model->doc_date = $request->doc_dt;
$model->created_dt = date("Y-m-d H:i:s",  strtotime("Now"));
$model->created_by = 38; // should be auth user id
$model->updated_dt = date("Y-m-d H:i:s",  strtotime("Now"));
$model->updated_by = 38; // should be auth user id
$model->uuid = Uuid::uuid4();
$model->doc_no = $abc . '/PO/' . date("y") . '/' . sprintf("%05d", $countdoc);
$model->company_id= $request->company_id;
$model->vendor_id=$request->cust_id;
$model->vendor_name=$request->vendor_name;
$model->vendor_address1=$request->address;
$model->vendor_address2=$request->address2;
$model->vendor_postcode=$request->postcode;
$model->vendor_city=$request->city;
$model->vendor_state=$request->state;
$model->vendor_country=$request->country;
$model->vendor_pic=$request->contactperson;
$model->vendor_phone=$request->contactno;
$model->notes=$request->notes;

if ($model->save()){
    $Log = new Log();
    $Log->insertlog($this->menu_id2, 'create', $model->uuid);
}

if($model->save()){

$button1 = '<button class="btn btn-outline-warning mt-3" type="button" data-toggle="modal" data-target="#Modal"  id="add" onclick="addproduct()" > Add Product </button>';
$button2 = '<button class="btn btn-outline-info mt-3" type="button"   id="view" onclick="view()" > View PO </button>';
$button3 = '<button class="btn btn-outline-success mt-3" type="button"   id="save" onclick="save()" > Save </button>';


}

return response()->json([$model, $button1, $button2, $button3]);


}



//add product purchase order
public function addProductPurchaseOrder(Request $request)
{

    $model = new PurchaseItem();
    $model->purchase_id = $request->purchase_id;
    $model->quantity = 0;
    $model->unit_price = '0.00';
    $model->discount = 0;
    $model->total_price = '0.00';
    $model->created_dt = date("Y-m-d H:i:s",  strtotime("Now"));
    $model->created_by = 38; //should be auth user id
    $model->updated_dt = date("Y-m-d H:i:s",  strtotime("Now"));
    $model->updated_by = 38; //should be auth user id

    if ($model->save()) {
        return '<tr id="row_' . $model->id . '"  class="item">
        <td><input type="hidden" value="' . $model->id . '" name="item_id[]"><input type="text" class="form-control" value="' . $model->product_name . '" name="item_productname_' . $model->id . '"></td>
        <td><input type="text" class="form-control" value="' . $model->quantity . '" name="item_quantity_' . $model->id . '" onchange="updatetotal(' . $model->id . ')"></td>
        <td><input type="text" class="form-control" value="' . $model->uom . '" name="item_uom_' . $model->id . '"></td>
        <td><input type="text" class="form-control" value="' . $model->unit_price . '" name="item_unitprice_' . $model->id . '" onchange="updatetotal(' . $model->id . ')"></td>
        <td><input type="text" class="form-control" value="' . $model->discount . '" name="item_discount_' . $model->id . '" onchange="updatetotal(' . $model->id . ')"></td>
        <td><input type="text" class="form-control" value="' . $model->total_price . '" name="item_totalprice_' . $model->id . '"></td>
        <td align="center"><i class="fa fa-save" aria-hidden="true" style="cursor:pointer;" title="update" onclick="update(' . $model->id . ')"></i></td>
        <td align="center"><i class="fas fa-trash-alt kt-font-danger" style="cursor:pointer;" title="Delete product" onclick="deleteproduct(' . $model->id . ')"></i></td>
        </tr>';
    }
}


//update product PO
public function updateProductPurchaseOrder(Request $request)
{
    $uuid = $request->uuid;
    $itemid = $request->itemid;
    $item_productname = $request->item_productname;
    $item_quantity = $request->item_quantity;
    $item_uom = $request->item_uom;
    $item_unitprice = $request->item_unitprice;
    $item_discount = $request->item_discount;
    $item_totalprice = $request->item_totalprice;

    $model = Purchase::where('uuid', $uuid)->first();
    $model->updated_dt = date("Y-m-d H:i:s",  strtotime("Now"));
    $model->updated_by = 38; // should be auth id

    if($model->save()){

     if(!empty($itemid)){
        $modelItem = PurchaseItem::where('id', $itemid)->first();
        $modelItem->product_name = $item_productname;
        $modelItem->quantity = $item_quantity;
        $modelItem->uom = $item_uom;
        $modelItem->unit_price = $item_unitprice;
        $modelItem->discount =  $item_discount;
        $modelItem->total_price = $item_totalprice;
        $modelItem->save();
      }

    }

    $Log = new Log();
    $Log->insertlog($this->menu_id2, 'update', $model->uuid);

    return response()->json([ "message" => "Success"]);

}


//delete product PO
public function deleteProductPurchaseOrder(Request $request)
{
    $id = $request->id;
    $status = 'failed';

     if(PurchaseItem::find($id)->delete())
          $status = 'success';
     return $status;
}



//download or view PO
public function downloadPurchaseOrder(Request $request)
{
    $uuid = $request->uuid;
    $ParamList = new ParamList();
    $User = new AxisUser();
    $Notes = new Notes();
    $model = Purchase::where('uuid', $uuid)->first();

    if (!empty($model)){
        $modelcom = Company::find($model->company_id);
        if (!empty($modelcom)){

            $header = '<table id="table-header" border="0" width="100%" cellpadding="0" cellspacing="0">
            <tr>
            <td rowspan="2"><img src="/assets/images/company_logo/' . $modelcom->logo . '" height="80"></td>
            <td align="right"><b>' . $modelcom->name . ' ('.$modelcom->reg_no.')</b></td>
            </tr>
            <tr>
            <td align="right" style="font-size:9px;">' . $modelcom->address1 .'<br> '.$modelcom->address2.'<br>'.
            $modelcom->postcode.' '.$modelcom->city.', '.$ParamList->getlabel(3,$modelcom->state).', Malaysia<br>
            (T) '.$modelcom->phone_no.'  (F) '.$modelcom->fax_no.'<br></td>
            </tr>
            </table>';

            $content = '<br><table border="0" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                <td width="40%" valign="top">
                <table cellpadding="0" cellspacing="0">
                <tr><td>&nbsp;</td></tr>
                <tr><td>&nbsp;</td></tr>
                <tr><td>&nbsp;</td></tr>
                <tr><td>&nbsp;</td></tr>
                <tr><td>&nbsp;</td></tr>
                <tr><td colspan="3"><b>VENDOR</b></td></tr>
                <tr>
                <td valign="top">Company Name</td>
                <td valign="top">&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                <td valign="top">' . $model->vendor_name . '</td>
                </tr>
                <tr>
                <td valign="top">Address</td>
                <td valign="top">&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                <td valign="top">' . $model->vendor_address1 . ' ' . $model->vendor_address2 . ' ' . $model->vendor_postcode . ' ' . $model->vendor_city . ' ' . $model->vendor_state . ' ' . $ParamList->getlabel(21, $model->vendor_country) . '</td>
                </tr>
                <tr>
                <td valign="top">Contact Person</td>
                <td valign="top">&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                <td valign="top">' . $model->vendor_pic . '</td>
                </tr>
                <tr>
                <td valign="top">Contact No</td>
                <td valign="top">&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                <td valign="top">' . $model->vendor_phone . '</td>
                </tr>
                </table>
                </td>
                <td width="20%"></td>
                <td width="40%" valign="top">
                <table cellpadding="0" cellspacing="0">
                <tr><td colspan="3" align="center"><h5><b>PURCHASE ORDER</b></h5><br/><br/></td></tr>
                <tr>
                <td valign="top">Date</td>
                <td valign="top">&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                <td valign="top" align="left">' . date("dS M Y", strtotime($model->doc_date)) . '</td>
                </tr>
                <tr>
                <td valign="top">PO No</td>
                <td valign="top">&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                <td valign="top" align="left">' . $model->doc_no . '</td>
                </tr>
                <tr><td>&nbsp;</td></tr>
                <tr><td colspan="3"><b>SHIP TO</b></td></tr>
                <tr>
                <td valign="top">Company Name</td>
                <td valign="top">&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                <td valign="top">' . $modelcom->name . '</td>
                </tr>
                <tr>
                <td valign="top">Address</td>
                <td valign="top">&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                <td valign="top">' . $modelcom->address1 . ' ' . $modelcom->address2 . ' ' . $modelcom->postcode . ' ' . $modelcom->city . ' ' . $ParamList->getlabel(3, $modelcom->state) . '</td>
                </tr>
                <tr>
                <td valign="top">Contact Person</td>
                <td valign="top">&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                <td valign="top">' . $User->getusername($model->created_by) . '</td>
                </tr>
                <tr>
                <td valign="top">Contact No</td>
                <td valign="top">&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                <td valign="top">' . $User->getuserphone($model->created_by) . '</td>
                </tr>
                </table>
                </td>
                </tr>
                </table><br>';

            $footer = '<table id="table-footer" border="0" width="100%" cellpadding="0" cellspacing="0">
                <tr><td align="center" style="font-size:5px;"><b>' . $modelcom->tagline . '</b><br>' . $modelcom->website . '
                </td>
                </tr>
                </table>';
            $content .= '<table width="100%" cellpadding="5" cellspacing="1" border="1" style="background-color:gray;" id="table-product">
                <thead>
                <tr>
                <th width="5%">No</th>
                <th>Description</th>
                <th width="10%">Qty</th>
                <th width="10%">UoM</th>
                <th width="10%">Unit Price</th>
                <th width="10%">Discount</th>
                <th width="15%">Total</th>
                </tr>
                </thead>
                <tbody>';

            $modelItem = PurchaseItem::where('purchase_id', $model->id)->orderBy('id' , 'ASC')->get();
            $grandtotal = 0;
            if (!empty($modelItem)){
                $countItem = 0;
                foreach ($modelItem as $rowItem) {
                    $countItem++;
                    $content .= '<tr>
                    <td align="center">' . $countItem . '</td>
                    <td>' . $rowItem->product_name . '</td>
                    <td>' . number_format($rowItem->quantity) . '</td>
                    <td>' . $rowItem->uom . '</td>
                    <td>RM ' . number_format((float) $rowItem->unit_price, 2, '.', ',') . '</td>
                    <td>' . $rowItem->discount . '%</td>
                    <td>RM ' . number_format((float) $rowItem->total_price, 2, '.', ',') . '</td>
                    </tr>';
                    $grandtotal += $rowItem->total_price;

                }

            }else {
                $content .= '<tr><td colspan="7">No record found</td></tr>';
            }

            $content .= '</tbody>
                <tfoot>
                <tr>
                <td colspan="6" align="right">Grand Total</td>
                <td><b>RM ' . number_format((float) $grandtotal, 2, '.', ',') . '</b></td>
                </tr>
                </tfoot>
                </table>';

            if (!empty($model->notes))
            $content .= '<br/><p>Note / Reason : ' . $model->notes . '</p>';

             $company_stamp = '<tr><td align="center" height="50"></td></tr>';
                if (!empty($modelcom->stamp))
                 $company_stamp = '<tr><td align="center"><img src="/assets/images/company_logo/' . $modelcom->stamp . '" height="50"></td></tr>';

            $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);

            $content .= $f->format($grandtotal). '<hr><div style="font-size:15px;line-height:1.7em;">' . $Notes->getnotes('Purchase', $modelcom->id) . '</div>
                <table width="40%">
                ' . $company_stamp . '
                <tr><td align="center" style="border-top:1px solid black;">Authorized Signature</td></tr>
                </table>';

        }

    }

    return $header . $content . $footer ;

}


//log PO
public function logPO(Request $request)
{
    $uuid =  $request->uuid;

    $searchModel = DB::select("SELECT al.data_uuid AS uuid,al.action_datetime,al.action_type,m.title, m.status,m.url,u.name,u.role,pl.label
                  FROM log al
                  LEFT JOIN axis_user u ON al.user_id = u.id
                  LEFT JOIN menu m ON al.menu_id = m.id
                  LEFT JOIN param_list pl ON u.role = pl.code
                  WHERE al.data_uuid='$uuid' ORDER BY al.action_datetime DESC");

      return view('finance.purchase.purchaseOrder.log-PO',['clog'=>$searchModel,'stores'=> $this->stores(),'skipHeader' => 1]);
}


//update purchase Order
public function updatePO(Request $request)
{
    $uuid = $request->uuid;

    if ($request->route('account'))
    {
        $account = $request->route('account');
    }

   //purchase details
    $model = Purchase::where('uuid', $uuid)->first();


  //company name
 $modelcom = DB::table('company AS c')
                ->select('c.id AS company_id','c.name AS name')
                ->where('c.id', $model->company_id)->first();


//company list
 $companies = DB::table('company AS c')
                    ->select('c.id AS company_id','c.name AS name')
                    ->leftJoin('company_access AS a' ,'a.com_id' ,'=' ,'c.id')
                    ->WHERE( 'a.user_id', '=' , 38 )
                    ->orderBy('c.name', 'ASC')->get();

//vendor
 $vendors = DB::table('vendor')->where('status', 1)->orderBy('name', 'ASC')->get();


//vendor country
 $countries = DB::table('param_list')->select('code', 'label')->where('cat_id', 21)->where('status', 1)->orderBy('sort', 'ASC')->get();
// dd($countries);

    return view('finance.purchase.purchaseOrder.update-PO',
                    ['stores' => $this->stores() ,
                    'companies'=>$companies,  'vendors'=>$vendors,
                    'countries'=>$countries,   'model'=>$model,
                    'modelcom'=> $modelcom,
                    'title' => 'add purchase order',
                    'form' => 'Analysis', 'skipHeader' => 1]);

}

 //save updated PO
 public function saveupdatedPO(Request $request)
 {
    parse_str($request->value, $output);

    //var_dump($output);
    $uuid = $request->uuid;

    $model = Purchase::where('uuid', $uuid)->first();

    $model->updated_dt = date("Y-m-d H:i:s",  strtotime("Now"));
    $model->updated_by = 38; // should be auth user id
    $model->company_id= $request->company_id;
    $model->vendor_id=$request->cust_id;
    $model->vendor_name=$request->vendor_name;
    $model->vendor_address1=$request->address;
    $model->vendor_address2=$request->address2;
    $model->vendor_postcode=$request->postcode;
    $model->vendor_city=$request->city;
    $model->vendor_state=$request->state;
    $model->vendor_country=$request->country;
    $model->vendor_pic=$request->contactperson;
    $model->vendor_phone=$request->contactno;
    $model->notes=$request->notes;
    $model->doc_date = $request->doc_dt;

    if ($model->save()) {
        $item_arr = $output['item_id'];

        if (!empty($item_arr)) {
            foreach ($item_arr as $item_id) {
                if (($modelItem = PurchaseItem::find($item_id)) !== null) {
                    $modelItem->product_name = $output['item_productname_' . $item_id];
                    $modelItem->quantity = $output['item_quantity_' . $item_id];
                    $modelItem->uom = $output['item_uom_' . $item_id];
                    $modelItem->unit_price = $output['item_unitprice_' . $item_id];
                    $modelItem->discount = $output['item_discount_' . $item_id];
                    $modelItem->total_price = $output['item_totalprice_' . $item_id];
                    $modelItem->save();
                }
            }
        }

        $Log = new Log();
        $Log->insertlog($this->menu_id2, 'update', $model->uuid);

    }
    return response()->json("success");


 }








}
