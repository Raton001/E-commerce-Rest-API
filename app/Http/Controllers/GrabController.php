<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use App\Jobs\SendOrderEmail;
use App\Jobs\DashboardOrder;
use App\Jobs\DashboardListing;
use \App\Http\Controllers\Controller;
use \App\Http\Controllers\ListingController as Listing;


use Illuminate\Support\Facades\Mail;
use App\Mail\OrderShipped;
use App\Mylaunchpack;
use App\myOldlisting;
use App\Order;
use Log;

class GrabController extends Controller
{
    use \App\Http\Controllers\HelperController;
    use \App\Http\Controllers\AuthMarketplaceController;
    use \App\Http\Controllers\CalculatorController;

    public $marketplace = '';

    public function __construct()
    {
      $this->marketplace = 5;

    }

    public function shipManuallyForm(Request $request)
    {
      $account = $request->route('account');
      return view('grab.shipment-manual', ['account'=>$account,'stores'=>$this->stores()]);
    }

    public function products()
    {
      $product = $_POST['productname'];
      $products = $this->makeCurl("getproductbyname", "&product=".urlencode($product));
      
      ob_start();
      if (sizeof($products) > 0) {
        ?>
        <table>
          <th>#</th>
          <th>Name</th>
          <th>SKU</th>
          <th>Quantity</th>
        <?php
        foreach($products as $key=>$product) {
          ?>
          <tr>
            <td><input type="checkbox" name="products[]" value="<?php echo $product->id;?>"></td>

            <td><?php echo $product->name;?></td>
            <td><?php echo $product->sku;?></td>
            <td><input type="number" name="quantity[]"></td>


          </tr>
          <?php
        }
        ?>
        </table>
        <?php
      }
      $html  = ob_get_contents();
      ob_get_clean();
      return $html;

    }

    public function shipManually(request $request)
    {
      $input = $request->all();
      $quantity = [];
      $items = [];
      foreach ($input['quantity'] as $key => $value) {
        if ($value != NULL) {
          $quantity[] = $value;
        }
      }
      foreach ($input['products'] as $key => $value) {
       $items[$value] = $quantity[$key];
      }
      
      $product = implode(',', $input['products']);
      $products = $this->makeCurl("getproductbyid", "&product=".$product);


      $productList = [];
      foreach ($products as $key => $value) {

        $productsList[$key]['po_product_id'] = $value->id;
        $productsList[$key]['po_brand_id'] = $value->brand_id;
        $productsList[$key]['po_product_quantity'] = $items[$value->id];
        $productsList[$key]['po_listing_title'] = $value->name;
        $productsList[$key]['po_listing_url'] = 'https://';
        $productsList[$key]['po_selling_mode'] = 'Buy It Now';
        $productsList[$key]['po_final_selling_price'] = $input['final_selling_price'];
        $productsList[$key]['po_currency'] = 'RM';
        $productsList[$key]['po_product_price'] = $value->selling_price;
        $productsList[$key]['po_stock_mode'] = 'company';
        $productsList[$key]['po_status'] = 'pending';
        $productsList[$key]['po_date'] = date('d-m-Y');



      }
      $invoice = [];
      $invoice['invoice_uid'] = $input['uid'];
      $invoice['invoice_marketplace'] = $input['marketplace'];
      $invoice['invoice_shipment_date'] = date('d-m-Y');
      $invoice['invoice_shipping_mode'] = (!$input['shipping_mode'] ? $input['shipping_mode_new']: $input['shipping_mode']);
      $invoice['invoice_shipment_status'] = 'pending';
      $invoice['invoice_shipment_mode'] = 'company';
      $invoice['invoice_weight'] = '0';
      $invoice['invoice_shipping_fee'] = '0.00';
      $invoice['invoice_shipping_cost'] = '0.00';
      $invoice['invoice_currency'] = 'MYR';
      $invoice['invoice_final_selling_price'] = $input['final_selling_price'];
      $invoice['invoice_admin_remark'] = 'via API';
      $invoice['invoice_orderid'] = $input['order_id'];
      $invoice['invoice_package_id'] = $product;
      $invoice['invoice_date'] = date('d-m-Y');
      $invoice['invoice_submit_time'] = date("h:i:sa");
      $invoice['shipment_ebay_id'] = $input['shop_id'];
      $invoice['shipment_customer_name'] = $input['customer_name'];
      $invoice['shipment_customer_address1'] = $input['address_1'];
      $invoice['shipment_customer_address2'] = $input['address_2'];
      $invoice['shipment_city'] = $input['city'];
      $invoice['shipment_state'] = $input['state'];
      $invoice['shipment_postcode'] = $input['postcode'];
      $invoice['shipment_country'] = $input['country'];
      $invoice['shipment_customer_contact'] = $input['contact'];
      $invoice['shipment_customer_email'] = $input['email'];
      $invoice['shipment_status'] = 'pending';
      $invoice['shipment_date'] = date('d-m-Y');
      $invoice['product_arr'] = $productsList;
      $invoice['airway'] = $input['airway_bill'];

      


      // echo "<pre>";

      // var_dump($invoice);
      // exit;
         // insert into member v2 starts
            $dataPushCompile = http_build_query($invoice, '', '&');
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://axisnet.asia/memberv2/API/addorders.php');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataPushCompile);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $data = curl_exec($ch);
            curl_close($ch);

            $output = json_decode($data, true);

            if ($output['status'] == 'success') {
              $invoiceID = $output['invoice_id'];


              //keep the record in coded
              $ebay = new \App\shippedOrder;
              $ebay->invoice_id = $invoiceID;
              $ebay->order_id = $invoice['invoice_orderid'];
              $ebay->marketplace = 5;//grab
              $ebay->shop = 'none';
              $ebay->created_by = Auth::id();
              $ebay->save();
            }
        
          // memberv2 ends

            return redirect()->back()->with('success', "Shipment Submitted");
    }
}