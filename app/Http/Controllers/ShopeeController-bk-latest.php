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

class ShopeeController extends Controller
{
    use \App\Http\Controllers\HelperController;
    use \App\Http\Controllers\AuthMarketplaceController;
    use \App\Http\Controllers\CalculatorController;

    public $marketplace = '';

    public function __construct()
    {
      $this->marketplace = 2;

    }

  public function setupPage($firstTime)
  {
    $shopid = '';
    $shipment = '';

    if (\Route::current()->parameter('account')) {

      $shopid = \Route::current()->parameter('account');

    }
    return $this->setup($firstTime, 0, $shopid);

   }
   public function authenticate() {
   
        return $this->authenticateShopee();
    }

    public function index($request)
    {
        $marketplace = $request->route('marketplace');

        $role = $this->userRole();
        $store  = [];
        $propkg = [];


        $accounts = $this->token();
        $sellings = [];

  
        foreach ($accounts as $k => $account) {
          foreach ($account as $key => $value) {
            $store[$value['account']] = $value['shopname'];


            // $active[$value['shopname']]  = $this->getActiveListing($value['account']);
            // $awaiting[$value['shopname']] = $this->getAwaitingShipment($value['account']);

        $shipment = $this->getShipmentDetails2($value['account']);

      

        $productlisting[$value['account']] = $this->makeCurl("getproductlisting", "&sme_id=".($shipment[0]->sme_id ? $shipment[0]->sme_id : 0).'&market=local');
        $packagelisting[$value['account']] = $this->makeCurl("getpackagelisting", "&sme_id=".($shipment[0]->sme_id ? $shipment[0]->sme_id : 0).'&market=local');


         $propkg[$value['account']] = array(
          'product'=>(array)$productlisting[$value['account']],
          'package'=>(array)$packagelisting[$value['account']]
         );

 
        /*variation starts*/
            //$launchpacks = [];
            
          
            // $price = 0;
            // $mylisting = 0;

            // foreach ($productlisting as $k => $v) {

            //   foreach ($v as $key => $value) {
            //     if (isset($value->id)) {

                
            //     $variations = $this->makeCurl("getproductvariation", "&pid=".$value->id);

            //     $launchpacks[$value->id]= array(
            //       'id'=>$value->id,
            //       'ebx_product_id'=>$k,
            //       'name'=>$value->name,
            //       'date'=>$value->created_dt,
            //       'status'=>$value->status,
            //       'price'=>$value->selling_price,
            //       'variation'=>sizeof($variations)
            //     ); 
            //     }
            //   }
             

            // }

        /*variation ends*/
    
           
          }
          
        }

        return view('shopee.home', ['products'=>$propkg, 'menu'=>$this->menu(), 'role'=>$role, 'store'=>$store, 'stores'=>$this->stores(), 'summary'=>$this->dashboardSummary($store), 'marketplace'=>$marketplace]);
    }

    private function getAwaitingShipment($account)
    {


 $date_start = date('Y-m-d',strtotime("-10 days"));
       $date_end = date('Y-m-d');
    $shopid = (int)$account;
    $shopname = $this->getShopName($shopid);

$EntriesPerPage = 10;
$offset = 0;
$time_start = "00:00:00";
$timestamp_start = strtotime($date_start.' '.$time_start);
$time_end = "23:59:00";

$timestamp_end = strtotime($date_end.' '.$time_end);

$url = "https://partner.shopeemobile.com/api/v1/orders/get";

$partner_id = (int)$this->partnerID;
$key = $this->shopeeKey;

$req_body = json_encode(array(
    'create_time_from' => $timestamp_start,
    'create_time_to' => $timestamp_end,
    'partner_id' => $partner_id,
    'shopid' => $shopid,
    'timestamp' => time(),
    'order_status'=>'READY_TO_SHIP',
    'pagination_entries_per_page'=>$EntriesPerPage,
    'pagination_offset' => (int)$offset
));

$unhashed = $url .'|'. $req_body;
$hash = hash_hmac('sha256', $unhashed, $key);

$headers = array(
    'Authorization: ' . $hash,
    'Content-Type: application/json; charset=utf-8'
);

// Request
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
// curl_setopt($ch, CURLOPT_HEADER, TRUE); // Includes the header in the output
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req_body);
$result = curl_exec($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$orders = json_decode($result);

return sizeof($orders->orders);
    }

    private function getActiveListing($account)
    {
    $page = 1;
    $limit = 10;
    $offset = 0;
    
    $shop_id = (int)$account;

    $url = "https://partner.shopeemobile.com/api/v1/items/get";

    $partner_id = (int)2000902;
    $key = "545004a2bd8b2d219c7a286a1f2b77ff50ed4952f3ddc1c706568ae2c811224e";
    

    
    $req_body = json_encode(array(
        'pagination_offset' => 0,
        'pagination_entries_per_page' => $limit,
        'partner_id' => $partner_id,
        'shopid' => $shop_id,
        'pagination_offset'=>$offset,
        'timestamp' => time()
    ));

    $unhashed = $url .'|'. $req_body;
    $hash = hash_hmac('sha256', $unhashed, $key);

    $headers = array(
        'Authorization: ' . $hash,
        'Content-Type: application/json; charset=utf-8'
    );

    // Request
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    // curl_setopt($ch, CURLOPT_HEADER, TRUE); // Includes the header in the output
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $req_body);
    $result = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $output = [];
    $listings = json_decode($result,true);
    return $listings['total'];

    }

  public function accept()
  {
  
        $this->userID = Auth::id();
      
        if (isset($_GET['shop_id'])) {
          
              $ebay = new \App\Ebay;
          $ebay->user_id = $this->userID;
          $ebay->account = $_GET['shop_id'];
          $ebay->marketplace_id =2;

          if (isset($_GET['code'])) {
          $ebay->shopee_code =$_GET['code'];

          }
          $ebay->save();
          
           //mark setup complete
           if (!\App\Setup::where(['user_id'=> $this->userID, 'marketplace_id'=>'shopee'])->exists()) {
    
          //mark setup complete
          $ebay = new \App\Setup;
          $ebay->user_id = $this->userID;
          $ebay->marketplace_id = 'shopee';
          $ebay->save();
        }
        }
   
         return redirect('/shopee/setup/3');
      
    }

public function getOrders(Request $request) {


  date_default_timezone_set("Asia/Kuala_Lumpur");

    
    $input = $request->all();

 
    $userID = Auth::id();
    $EntriesPerPage = 100;
    $pagenumber = 1;
    $account = '';
    $offset = 0;

    if (isset($input['start-end-date'])) {
      $dates = explode('-', $input['start-end-date']);
      $date_start = date('Y-m-d', strtotime($dates[0]));
      $date_end = date('Y-m-d', strtotime($dates[1]));

    } else {
       $date_start = date('Y-m-d',strtotime("-10 days"));
       $date_end = date('Y-m-d');
    }

    if ($request->route('account')) {
        $account = $request->route('account');
    }

    if ($request->route('offset')) {

      $pagenumber = $request->route('offset');
      $offset = $pagenumber * $EntriesPerPage;//$request->route('offset');
  
      // $pagenumber++;

    }
    
  
    
     if ($request->route('marketplace')) {
      $marketplace = $request->route('marketplace');

    }
    // echo "<pre>";

    // var_dump($offset);
    // var_dump($pagenumber);exit;

    $accounts = $this->tokens();


    $shopid = (int)$account;
    $shopname = $this->getShopName($shopid);


$time_start = "00:00:00";
$timestamp_start = strtotime($date_start.' '.$time_start);
$time_end = "23:59:00";

$timestamp_end = strtotime($date_end.' '.$time_end);

$url = "https://partner.shopeemobile.com/api/v1/orders/basics";

$partner_id = (int)$this->partnerID;
$key = $this->shopeeKey;

$req_body = json_encode(array(
    'create_time_from' => $timestamp_start,
    'create_time_to' => $timestamp_end,
    'partner_id' => $partner_id,
    'shopid' => $shopid,
    'timestamp' => time(),
    'pagination_entries_per_page'=>$EntriesPerPage,
    'pagination_offset' => (int)$offset
));

$unhashed = $url .'|'. $req_body;
$hash = hash_hmac('sha256', $unhashed, $key);

$headers = array(
    'Authorization: ' . $hash,
    'Content-Type: application/json; charset=utf-8'
);

// Request
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
// curl_setopt($ch, CURLOPT_HEADER, TRUE); // Includes the header in the output
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req_body);
$result = curl_exec($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$orders = json_decode($result);

$data = [];
//segregate by status
foreach ($orders->orders as $key => $value) {
  $data[$value->order_status][] = $value;
}

$invoiceStatus = [];

//cross check with memberv2
if (isset($data['READY_TO_SHIP'])) {

  $orderIDs = array_column($data['READY_TO_SHIP'], 'ordersn');

      $dataPushCompile = ['order_id'=>json_encode($orderIDs)];


      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, 'https://axisnet.asia/memberv2/API/getordersbystatus.php');
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $dataPushCompile);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $invoiceStatus = curl_exec($ch);
      curl_close($ch);

      $invoiceStatus = json_decode(rtrim($invoiceStatus, "'"));

      $output = [];
      $airwayBills = [];

       // $pendingOrders = array_keys((array)$invoiceStatus);
       // if (sizeof($pendingOrders) > 0) {


        $airwayBills = $this->getAirwayBill($account, $orderIDs);

        if (isset($airwayBills)) {


         foreach ($airwayBills as $key => $value) {
          if (in_array($value->ordersn, array_column($data['READY_TO_SHIP'], 'ordersn'))) {
   
           
            $data['READY_TO_SHIP'][array_search($value->ordersn, array_column($data['READY_TO_SHIP'], 'ordersn'))]->airway = $value->airway_bill;
         

          }
        }





        }



 

       // }


}



  return view('orders', ['shopname'=>$shopname, 'account'=>$account, 'accounts'=>$accounts, 'data'=>$data,'stores'=>$this->stores(), 'source'=>'shopee', 'axisStatus' => $invoiceStatus,'pagination'=>$orders->more,'marketplace'=>$marketplace,'page'=>$pagenumber]);
}
  public function getOrders_old(Request $request)
  {
    date_default_timezone_set("Asia/Kuala_Lumpur");

    
    $input = $request->all();
    $userID = Auth::id();
    $EntriesPerPage = 100;
    $pagenumber = 1;
    $account = '';
    $offset = 0;
  
    //request from ajax
    if (isset($input['internal'])) {
        $dates = explode('-', $input['startEndDate']);
        $date_start = $dates[0];
        $date_end = $dates[1];
         $account = $input['account'];

         $date=date_create($date_start);
         $date_start = date_format($date,"Y-m-d");


         $date=date_create($date_end);
         $date_end = date_format($date,"Y-m-d");


    } else {
      //yesterday
        $date_start = date('Y-m-d',strtotime("-10 days"));
        $date_end = date('Y-m-d');
          if ($request->route('account')) {
        $account = $request->route('account');
      }

    }
        
    if ($request->route('page')) {
      $pagenumber = $request->route('page');

    }
    
    if ($request->route('offset')) {
      $offset = $pagenumber * $EntriesPerPage;//$request->route('offset');
      $pagenumber++;
    }
    
     if ($request->route('marketplace')) {
      $marketplace = $request->route('marketplace');

    }

    $accounts = $this->tokens();


    $shopid = (int)$account;
    $shopname = $this->getShopName($shopid);


$time_start = "00:00:00";
$timestamp_start = strtotime($date_start.' '.$time_start);
$time_end = "23:59:00";

$timestamp_end = strtotime($date_end.' '.$time_end);

session_destroy();

if (isset($_SESSION['orders'][$account][$userID][$date_start.'-'.$date_end][$pagenumber])) {

$sessionData = $_SESSION['orders'][$account][$userID][$date_start.'-'.$date_end][$pagenumber];

if (isset($sessionData['READY_TO_SHIP'])) {

  $orderIDs = array_column($sessionData['READY_TO_SHIP'], 'ordersn');


      $dataPushCompile = ['order_id'=>json_encode($orderIDs)];


      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, 'https://axisnet.asia/memberv2/API/getordersbystatus.php');
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $dataPushCompile);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $data = curl_exec($ch);
      curl_close($ch);

      $invoiceStatus = json_decode(rtrim($data, "'"));

      $output = [];


      foreach ($sessionData['READY_TO_SHIP'] as $key => $value) {
       if (isset($value->ordersn)) {
           
       
        $airwaybill = $this->getAirwayBill($account, $value->ordersn);
      if ($airwaybill) {
        $value->airway_bill = $airwaybill;
      }
        if (isset($invoiceStatus->{$value->ordersn})) {
        $value->order_status = $invoiceStatus->{$value->ordersn}->status;
        $value->invoice_id = $invoiceStatus->{$value->ordersn}->invoice_id;


        }
       }
      }


}


if (isset($input['internal'])) {
 

  // ob_start();
  return view('orders2', ['account'=>$account,'data'=>$_SESSION['orders'][$account][$userID][$date_start.'-'.$date_end],'stores'=>$this->stores(), 'source'=>'shopee', 'last_fetched'=>$_SESSION['orders'][$account][$userID]['last_fetched']]);
  // $html = ob_get_contents();
  // ob_end_clean();
  // return $html;
}


    
  return view('orders', ['shopname'=>$shopname, 'account'=>$account,'data'=>$_SESSION['orders'][$account][$userID][$date_start.'-'.$date_end],'stores'=>$this->stores(), 'source'=>'shopee', 'last_fetched'=>$_SESSION['orders'][$account][$userID]['last_fetched']]);

}

$url = "https://partner.shopeemobile.com/api/v1/orders/basics";

$partner_id = (int)$this->partnerID;
$key = $this->shopeeKey;

$req_body = json_encode(array(
    'create_time_from' => $timestamp_start,
    'create_time_to' => $timestamp_end,
    'partner_id' => $partner_id,
    'shopid' => $shopid,
    'timestamp' => time(),
    'pagination_entries_per_page'=>$EntriesPerPage,
    'pagination_offset' => (int)$offset
));

$unhashed = $url .'|'. $req_body;
$hash = hash_hmac('sha256', $unhashed, $key);

$headers = array(
    'Authorization: ' . $hash,
    'Content-Type: application/json; charset=utf-8'
);

// Request
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
// curl_setopt($ch, CURLOPT_HEADER, TRUE); // Includes the header in the output
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req_body);
$result = curl_exec($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$orders = json_decode($result);

echo "<pre>";
var_dump($orders);
exit;
    $data = [];

    /**orders starts*/

        $ordersIDs = array_column((array)$orders->orders, 'ordersn');
        $totalOrders = sizeof($ordersIDs);

         static $i;
          if ($totalOrders > 50) {
           
              for ($i=0; $i< round($totalOrders/50);) {
                   $removed = array_slice($ordersIDs, 0, 50);
                   $removed = $ordersIDs;
   
                   $data[] = $this->getOrderDetail($removed, $account);
                   
                   array_splice($ordersIDs, 0, 50);
                  
                    $i++;
              }
          }

         
          //if still left
          if ($totalOrders > 0) {
         
              $i++;
         
              $data[] = $this->getOrderDetail($ordersIDs, $account);
                  
          }
         
    /*orders ends*/

$last_fetched = date('Y-m-d H:i:s');



    // foreach ($orders->orders as $key => $order) {
    //  $data[$order->order_status][] = $this->getOrderDetail([$order->ordersn], $account);
    // }

    $sessionData = $_SESSION['orders'][$account][$userID] = [$date_start.'-'.$date_end => $data];


$_SESSION['orders'][$account][$userID]['last_fetched'] = $last_fetched;


//cross check with memberv2
if (isset(array_column($sessionData, 'READY_TO_SHIP')[0])) {

  $orderIDs = array_column(array_column($sessionData, 'READY_TO_SHIP')[0], 'ordersn');


      $dataPushCompile = ['order_id'=>json_encode($orderIDs)];


      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, 'https://axisnet.asia/memberv2/API/getordersbystatus.php');
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $dataPushCompile);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $data = curl_exec($ch);
      curl_close($ch);

      $invoiceStatus = json_decode(rtrim($data, "'"));

      $output = [];
    
    $readyToShip = array_column($sessionData, 'READY_TO_SHIP')[0];
    
      foreach ($readyToShip as $key => $value) {
        $airwaybill = $this->getAirwayBill($account, $value->ordersn);
       if ($airwaybill) {
        $value->airway_bill = $airwaybill;
       }
        if (isset($invoiceStatus->{$value->ordersn})) {
        $value->order_status = $invoiceStatus->{$value->ordersn}->status;
        $value->invoice_id = $invoiceStatus->{$value->ordersn}->invoice_id;


        }
      }


}


    if (isset($input['internal'])) {
    
       return view('orders2', ['shopname'=>$shopname, 'account'=>$account,'data'=>$_SESSION['orders'][$account][$userID][$date_start.'-'.$date_end],'stores'=>$this->stores(), 'source'=>'shopee', 'last_fetched'=>(isset($_SESSION['orders'])? $_SESSION['orders'][$account][$userID]['last_fetched']: '')]);
    }
     return view('orders', ['shopname'=>$shopname, 'account'=>$account, 'accounts'=>$accounts, 'data'=>$sessionData[$date_start.'-'.$date_end],'stores'=>$this->stores(), 'source'=>'shopee', 'last_fetched'=>(isset($_SESSION['orders'])? $_SESSION['orders'][$account][$userID]['last_fetched']: ''),'pagination'=>$orders->more,'marketplace'=>$marketplace, 'page'=>$pagenumber]);
    
  }
function msectime() {
     list($msec, $sec) = explode(' ', microtime());
     return $sec . '000';
  }

protected function generateSign($apiName,$params)
  {
    ksort($params);

    $stringToBeSigned = '';
    $stringToBeSigned .= $apiName;
    foreach ($params as $k => $v)
    {
      $stringToBeSigned .= "$k$v";
    }
    unset($k, $v);

    return strtoupper($this->hmac_sha256($stringToBeSigned,'3op8LAHqWyehXbNaEATsuXXPbr50yjaw'));
  }


  function hmac_sha256($data, $key){
      return hash_hmac('sha256', $data, $key);
  }


  private function getOrderDetail($orderSn, $shopID, $byStatus = 1)
  {

    $shopid = (int)$shopID;

// $date_start = "2021-06-30 00:00:00";
// $timestamp_start = strtotime($date_start);

// $timestamp_end = time();

$url = "https://partner.shopeemobile.com/api/v1/orders/detail";

$partner_id = (int)$this->partnerID;
$key = $this->shopeeKey;

$order_sn = $orderSn;

$req_body = json_encode(array(
    'ordersn_list' => $order_sn,
    'partner_id' => $partner_id,
    'shopid' => $shopid,
    'timestamp' => time()
));

$unhashed = $url .'|'. $req_body;
$hash = hash_hmac('sha256', $unhashed, $key);

$headers = array(
    'Authorization: ' . $hash,
    'Content-Type: application/json; charset=utf-8'
);

// Request
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
// curl_setopt($ch, CURLOPT_HEADER, TRUE); // Includes the header in the output
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req_body);
$result = curl_exec($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
    
  $result = json_decode($result);

  if (isset($result->orders[0])) {
    if (sizeof($result->orders) > 1) {
  $order = $result->orders;

    } else {
  $order = $result->orders[0];

    }
  } else {
    $order = [];
  }
  if (!$byStatus) {
    return $order;
  }
  $output = [];
  foreach ($order as $key => $value) {
   $output[$value->order_status][] = $value;
  }
  return $output;

  }

  //format product array before memberv2 submission
    private function formatListing($listing, $vendor= false, $freeGift= false, $totalAmount)
    {
   
      $formattedList = [];
      $packageID = [];
      $variationSku = '';

      if (sizeof($listing) > 0) {
        foreach ($listing  as $key => $product) {
            if ($product->item_sku == '' && $product->variation_sku == '') {
                return;
            }
          $sku = trim($product->item_sku);
          if (!$product->item_sku) {
            $sku = trim($product->variation_sku);
          }
          if ($product->variation_sku) {
              $variationSku = trim($product->variation_sku);
          }
          

    if ($vendor == 'manufacturer_code') {
        // product (bio nut)
        // $pro_pkg = $this->makeCurl("getproductlistbysku", "&sku=".$sku."&quantity=1");
        
    
                $ebxsku = $this->makeCurl("getsku", "&manufacturer=".$sku);
             
                if (isset($ebxsku[0]->sku)) {
                    $sku = $ebxsku[0]->sku;
                } else {
                    
                    //check if its a package instead
                    $ebxsku = $this->makeCurl("getpackagesku", "&manufacturer=".$sku);
                     if (isset($ebxsku[0]->sku)) {
                    $sku = $ebxsku[0]->sku;
                    } else {
                        
                        //then its product with variation
                        if($variationSku) {
                            $ebxsku = $this->makeCurl("getsku", "&manufacturer=".$variationSku);
                            
                            if (isset($ebxsku[0]->sku)) {
                              $sku = $ebxsku[0]->sku;
                            } else {
                                //else package with variation
                                 $ebxsku = $this->makeCurl("getpackagesku", "&manufacturer=".$variationSku);
                                 if (isset($ebxsku[0]->sku)) {
                                  $sku = $ebxsku[0]->sku;
                            }
                            }
                        }
                    }
                    
                }


                
    } 

// var_dump($sku);exit;
if ($sku == '') {
    return false;
}

  //check if package
  if ((substr($sku, 0, strlen('PKG')) === 'PKG') === true) { 
    

      $pro_pkg = $this->makeCurl("getlistbysku", "&sku=".$sku."&quantity=".$product->variation_quantity_purchased);
       $type ='package';

//check null
if ($pro_pkg) {


    if (sizeof((array)$pro_pkg) <= 0){

      
        if($variationSku) {
                       $pro_pkg = $this->makeCurl("getlistbysku", "&sku=".$variationSku."&quantity=".$product->variation_quantity_purchased);
                    }
                    
           
      }

    }
       
      
  } else {


      //product
      $pro_pkg = $this->makeCurl("getproductlistbysku", "&sku=".$sku."&quantity=".$product->variation_quantity_purchased);


       $type ='product';
      if (sizeof((array)$pro_pkg) <= 0){

      
        if($variationSku) {
         
                       $pro_pkg = $this->makeCurl("getproductlistbysku", "&sku=".$variationSku."&quantity=".$product->variation_quantity_purchased);
                    }
                    
           
      }
  }

  if (sizeof((array)$pro_pkg) <= 0) {
      return false;
  }
  // $pro_pkg = (object)$pro_pkg;
      // echo "<pre>";
      // var_dump($pro_pkg);exit;



        if (isset($pro_pkg[0]->{$type})) {
         foreach($pro_pkg as $pkg) {
             /*variation starts*/
            
         
            if (isset($pkg->{$type})) {

        $quantity = 1;
        $price = 0.00;
        $variantPrice = 0.00;

        if (isset($pkg->{$type}->quantity)) {
          $quantity = $pkg->{$type}->quantity;//* $product->variation_quantity_purchased;
          // $quantity = $pro_pkg->package->quantity;
          
          
          //always send price of 1 quantity to memberv2. as there it multiplies with quantity
          $price = $pkg->{$type}->variant_price;//$pkg->{$type}->quantity * $pkg->{$type}->price;
          // $price = $pkg->package->price;
          

        }


         if (isset($product->variation_original_price)) {
         
          $variantPrice = $product->variation_original_price;//$pkg->{$type}->quantity * $product->variation_original_price;

        }
        if ($type == 'product') {
            $iddd = 'variant_id';
        }else {
            $iddd = 'package_id';
        }


         $packageID[] = $pkg->{$type}->{$iddd};

  
          
          $formattedList[] = array(
              'po_product_id'=>$pkg->{$type}->variant_id,
              'po_brand_id'=>$pkg->{$type}->brand_id,
              'po_product_quantity'=>$quantity,
              'po_listing_title'=>$product->item_name,
              'po_listing_url'=>'https://',
              'po_selling_mode'=>'Buy It Now',
              'po_final_selling_price'=>$totalAmount,
              'po_currency'=>'MYR',
              'po_product_price'=>$price,
              'po_stock_mode'=>'company',
              'po_status'=>'pending',
              'po_date'=>date('d-m-Y')
          );
      
            }
             /*variation ends*/
         }
        } else {
            
            /*non variation starts*/
              
            if (isset($pro_pkg[0]->{$type})) {
        $quantity = 1;
        $price = 0.00;
        $variantPrice = 0.00;

        if (isset($pro_pkg[0]->quantity)) {
          $quantity = $pro_pkg[0]->quantity;// * $product->variation_quantity_purchased;
          // $quantity = $pro_pkg->package->quantity;
          // 
          $price = $pro_pkg->price;//$pro_pkg->{$type}->quantity * $pro_pkg->{$type}->price;
          // $price = $pro_pkg->package->price;
          

        }


         if (isset($product->variation_original_price)) {
         
          $variantPrice = $product->variation_original_price;//$pro_pkg->{$type}->quantity * $product->variation_original_price;

        }


        if ($type == 'product') {
            $iddd = 'variant_id';
        }else {
            $iddd = 'package_id';
        }

        if (isset($pro_pkg[0]->{$type})) {

        
         $packageID[] = $pro_pkg[0]->{$type}->{$iddd};

  
          
          $formattedList[] = array(
              'po_product_id'=>$pro_pkg[0]->{$type}->variant_id,
              'po_brand_id'=>$pro_pkg[0]->{$type}->brand_id,
              'po_product_quantity'=>$quantity,
              'po_listing_title'=>$product->item_name,
              'po_listing_url'=>'https://',
              'po_selling_mode'=>'Buy It Now',
              'po_final_selling_price'=>$totalAmount,
              'po_currency'=>'MYR',
              'po_product_price'=>$price,
              'po_stock_mode'=>'company',
              'po_status'=>'pending',
              'po_date'=>date('d-m-Y')
          );
      
      }
            }
            
            /*non variation ends*/
            
        }
        

         }

         return [$formattedList, $packageID];
      }
    
    }

private function formatOrder($order, $shipment, $items, $shopID)
{
  $products = $items[0];

  $packageID = implode(',', $items[1]);

// var_dump($packageID);exit;

  $address = explode(',', $order->recipient_address->full_address);

foreach($address as $k=>$add) {

    if (trim($add) == $order->recipient_address->zipcode || trim($add) == $order->recipient_address->state) {
        unset($address[$k]);
    }
}
$add = '';
if (isset($address[1])) {
    $add = $address[1];
}

if (isset($address[2])) {
    $add .= $address[2];
}

//$order->ordersn
$airwayBills = $this->getAirwayBill($shopID, $order->ordersn);


if (isset($airwayBills[0]->airway_bill)) {
$airway = $airwayBills[0]->airway_bill;
} else {
  $airway = '';
}


       return array(
            'invoice_uid' => $shipment[0]->axis_user_id,
            'invoice_marketplace' => 'Shopee',
            'invoice_shipment_date' => date('d-m-Y'),
            'invoice_shipping_mode' => 'Shopee Logistic',
            'invoice_shipment_status' => 'pending',
            'invoice_shipment_mode' => 'company',
            'invoice_weight' => '0',
            'invoice_shipping_fee' => '0.00',
            'invoice_shipping_cost' => '0.00',
            'invoice_currency' => 'MYR',
            'invoice_final_selling_price' => $order->total_amount,
            'invoice_admin_remark' => 'via API',
            'invoice_orderid' => $order->ordersn,
            'invoice_package_id' => $packageID,
            'invoice_date' => date('d-m-Y'),
            'invoice_submit_time' => date("h:i:sa"),
            'shipment_ebay_id' => $shipment[0]->axis_shop_id,
            'shipment_customer_name' =>  $order->recipient_address->name,
            'shipment_customer_address1' => $address[0],
            'shipment_customer_address2' => $add,
            'shipment_city' =>  $order->recipient_address->city,
            'shipment_state' =>  $order->recipient_address->state,
            'shipment_postcode' =>  $order->recipient_address->zipcode,
            'shipment_country' =>  $order->recipient_address->country,
            'shipment_customer_contact' =>  $order->recipient_address->phone,
            'shipment_customer_email' => '',
            'shipment_status' => 'pending',
            'shipment_date' => date('d-m-Y'),
            'product_arr' => $products,
            'airway' => $airway
        );
}

public function refresh()
{
    session_destroy();
    session_start();
     return redirect()->back()->with('success', 'Page Refreshed');
}

    
    public function shipmentRequests(Request $request)
    {
        $account = $request->route('account');
        $userID = Auth::id();
        
        
        $query = "SELECT * FROM shipped_orders WHERE shop = $account AND created_by = '".$userID."' AND marketplace = 2 ORDER BY created_at DESC";
   
      $orders = DB::select($query);
      $output = [];
    
  
        $dataPushCompile = ['order_id'=>json_encode(array_column($orders, 'order_id'))];


      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, 'https://axisnet.asia/memberv2/API/getordersbystatus.php');
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $dataPushCompile);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $data = curl_exec($ch);
      curl_close($ch);

      $invoiceStatus = json_decode(rtrim($data, "'"));
      

       return view('shopee.shipment-requests', ['orders'=>$orders, 'invoice'=>$invoiceStatus, 'stores'=>$this->stores()]);
      
    }
    
  public function createBundleShipmentRequest(Request $request)
  {
    echo "<pre>";
    /*get required data*/
    $account = $request->route('account');
    $userID = Auth::id();
    $shipment = $this->getShipmentDetails2($account);
    $skuType = $shipment[0]->sku_type;
    $freeGift = $shipment[0]->free_gift;
    $input = $request->all();

    /*fetch shipped orders*/
     $shipped = DB::table('shipped_orders')
                
                ->select('order_id')
                ->where(['created_by'=> $userID, 'shop'=>$account])
                ->get();
            $shipped = collect($shipped->toArray())->all();
        
           if ($shipped) {
                $shipped = (array)$shipped;
         
               $duplicates = array_intersect(array_column($shipped, 'order_id'), $input['orders']);
        
              if (sizeof($duplicates) > 0) {
                  foreach($duplicates as $duplicate) {
                      unset($input['orders'][array_search($duplicate, $input['orders'])]);
                    // var_dump($input['orders'][array_search($duplicate, $input['orders'])]);
                
                  }
                  $input['orders'] = array_values($input['orders']);
              }
           }


    
    $totalOrders = sizeof($input['orders']);
   static $i;
    if ($totalOrders > 50) {
     
        for ($i=0; $i< round($totalOrders/50);) {
            
             $removed = array_slice($input['orders'], 0, 50);
            //  $removed = $input['orders'];
        
             $orders[] = $this->getOrderDetail($removed, $account, 0);
             
             array_splice($input['orders'], 0, 50);
            
              $i++;
        }
    }

   
    //if still left
    if ($totalOrders > 0) {
   
        $i++;
        $orders[] = $this->getOrderDetail($input['orders'], $account, 0);
            
    }


if (sizeof($orders) == 2) {
    
$orders = array_merge($orders[0], $orders[1]);
} else if (sizeof($orders) == 3) {
    
$orders = array_merge($orders[0], $orders[1], $orders[2]);
} else if (sizeof($orders) == 1){
    $orders = $orders[0];
}


    $listing = [];

$j=0;

if (gettype($orders)=='array') {

    foreach ($orders as $key => $value) {
 $j++;
// echo $j."<br/>";
// foreach($value as $v) {

$listingTmp = $value;

      // unset($listing->$value->items);
      $listing[$listingTmp->ordersn] = $listingTmp;
    
      if ($listingTmp->order_status == 'READY_TO_SHIP') {
        $products = $this->formatListing($listing[$listingTmp->ordersn]->items, $skuType, $freeGift, $listingTmp->total_amount);
    if (!$products) {
      
        return redirect()->back()->with('error', "There's a problem with the SKU");
    }
          $formattedData = $this->formatOrder($listingTmp, $shipment, $products, $account);

        //get free gift if assigned
          if ($freeGift) {
              $gift = $this->makeCurl("getproductlistbysku", "&sku=".$freeGift."&quantity=1");
        
              $gift[0]->product->quantity = 1;
             
                  $formattedData['product_arr'][] = array(
                  'po_product_id'=>$gift[0]->product->variant_id,
                  'po_brand_id'=>$gift[0]->product->brand_id,
                  'po_product_quantity'=>$gift[0]->product->quantity,
                  'po_listing_title'=>$gift[0]->product->product_name,
                  'po_listing_url'=>'https://',
                  'po_selling_mode'=>'Buy It Now',
                  'po_final_selling_price'=>$gift[0]->product->variant_price,
                  'po_currency'=>'MYR',
                  'po_product_price'=>$gift[0]->product->variant_price,
                  'po_stock_mode'=>'company',
                  'po_status'=>'pending',
                  'po_date'=>date('d-m-Y')
              );
          
              
          }
          
              
      var_dump($formattedData);
 
        
          // insert into member v2 starts
          //   $dataPushCompile = http_build_query($formattedData, '', '&');
          //   $ch = curl_init();
          //   curl_setopt($ch, CURLOPT_URL, 'https://axisnet.asia/memberv2/API/addorders.php');
          //   curl_setopt($ch, CURLOPT_POST, 1);
          //   curl_setopt($ch, CURLOPT_POSTFIELDS, $dataPushCompile);
          //   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          //   $data = curl_exec($ch);
          //   curl_close($ch);

          //   $output = json_decode($data, true);

          //   if ($output['status'] == 'success') {
          //     $invoiceID = $output['invoice_id'];


          //     //keep the record in coded
          //     $ebay = new \App\shippedOrder;
          //     $ebay->invoice_id = $invoiceID;
          //     $ebay->order_id = $formattedData['invoice_orderid'];
          //     $ebay->marketplace = 2;
          //     $ebay->shop = $account;
          //     $ebay->created_by = Auth::id();
          //     $ebay->save();
          //   }
        
          // memberv2 ends
 

    //   }
}

}


    } else {
      

         $j++;
         $listingTmp = $orders;
         $listing[$listingTmp->ordersn] = $listingTmp;
        //single order
        if ($listingTmp->order_status == 'READY_TO_SHIP') {
            echo $j."<br/>";
            $products = $this->formatListing($listing[$listingTmp->ordersn]->items, $skuType, $freeGift, $listingTmp->total_amount);
       
    
                if (!$products) {

                    return redirect()->back()->with('error', "There's a problem with the SKU");
                }   
                
                 // $items = [$products[0][0]];
               
            $formattedData = $this->formatOrder($listingTmp, $shipment, $products, $account);

     
      
                    //get free gift if assigned
          if ($freeGift) {
              $gift = $this->makeCurl("getproductlistbysku", "&sku=".$freeGift."&quantity=1");
              $gift[0]->product->quantity = 1;
      
                  $formattedData['product_arr'][] = array(
                  'po_product_id'=>$gift[0]->product->variant_id,
                  'po_brand_id'=>$gift[0]->product->brand_id,
                  'po_product_quantity'=>$gift[0]->product->quantity,
                  'po_listing_title'=>$gift[0]->product->product_name,
                  'po_listing_url'=>'https://',
                  'po_selling_mode'=>'Buy It Now',
                  'po_final_selling_price'=>$gift[0]->product->variant_price,
                  'po_currency'=>'MYR',
                  'po_product_price'=>$gift[0]->product->variant_price,
                  'po_stock_mode'=>'company',
                  'po_status'=>'pending',
                  'po_date'=>date('d-m-Y')
              );
          
              
          }
      var_dump($formattedData);
          
 
            //  insert into member v2 starts
            // $dataPushCompile = http_build_query($formattedData, '', '&');
            // $ch = curl_init();
            // curl_setopt($ch, CURLOPT_URL, 'https://axisnet.asia/memberv2/API/addorders.php');
            // curl_setopt($ch, CURLOPT_POST, 1);
            // curl_setopt($ch, CURLOPT_POSTFIELDS, $dataPushCompile);
            // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            // $data = curl_exec($ch);
            // curl_close($ch);

            // $output = json_decode($data, true);

            // if ($output['status'] == 'success') {
            //   $invoiceID = $output['invoice_id'];


            //   //keep the record in coded
            //   $ebay = new \App\shippedOrder;
            //   $ebay->invoice_id = $invoiceID;
            //   $ebay->order_id = $formattedData['invoice_orderid'];
            //   $ebay->marketplace = 2;
            //   $ebay->shop = $account;
            //   $ebay->created_by = Auth::id();
            //   $ebay->save();
            // }
                }
           
        
    }

    exit;
     session_destroy();
     return redirect()->back()->with('success', 'Shipment Requests Successfully Submitted');

  }
      public function shipmentShop($request)
    {
      $username = $request->username;

      $shops = $this->getShipmentDetails($username);
      return $shops;
    }

    
     public function shipNow(Request $request)
    {

$input = $request->all();
unset($input['_token']);

$list = [];
echo "<pre>";
var_dump($input);exit;

foreach ($input['data'] as $key => $value) {
$list[] = $this->formatListing($value['listing'], $value['product']);


}
$packageID = 0;
$invoice = array(
          'invoice_uid' => $input['axis_user_id'],
          'invoice_marketplace' => 'eBay',
          'invoice_shipment_date' => date('d-m-Y'),
          'invoice_shipping_mode' => 'dhl',
          'invoice_shipment_status' => 'pending',
          'invoice_shipment_mode' => 'company',
          'invoice_weight' => '0',
          'invoice_shipping_fee' => '0.00',
          'invoice_shipping_cost' => '0.00',
          'invoice_currency' => '',
          'invoice_final_selling_price' =>$input['total'],
          'invoice_admin_remark' => 'Personal Use',
          'invoice_orderid' => $input['order_id'],
          'invoice_package_id' => $packageID,
          'invoice_date' => date('d-m-Y'),
          'invoice_submit_time' => date("h:i:sa"),
          'shipment_ebay_id' => $input['axis_shop_id'],
          'shipment_customer_name' => $input['customer_name'],
          'shipment_customer_address1' => $input['street1'],
          'shipment_customer_address2' => $input['street2'],
          'shipment_city' => $input['city'],
          'shipment_state' => $input['state'],
          'shipment_postcode' => $input['postal'],
          'shipment_country' => $input['country'],
          'shipment_customer_contact' => $input['phone'],
          'shipment_customer_email' => '',
          'shipment_status' => 'pending',
          'shipment_date' => date('d-m-Y'),
          'product_arr' => $list,
      );   
       
echo "<pre>";
var_dump($invoice);
exit;
       // save into memberv2

      // var_dump($invoice);
      // exit;
        
        // $dataPushCompile = http_build_query($invoice, '', '&');
        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, 'https://axisnet.asia/memberv2/API/addorders.php');
        // curl_setopt($ch, CURLOPT_POST, 1);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $dataPushCompile);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // $data = curl_exec($ch);
        // curl_close($ch);

        // $outputArr = json_decode($data, true);

        // $invoiceStatus = [];

        // if ($outputArr['status'] != 'success') {
        //    $invoiceStatus[] = 'error';
        // } else{

        //     $invoiceStatus[] = $outputArr['invoice_id'];

        //     $id = DB::table('shipment')->insertGetId([
        //       'orderline_id'=>$invoice['orderlineID'],
        //       'invoice_id' => $outputArr['invoice_id'],
        //       'uid' => $shipment['invoice_uid']
        //     ]);

        // }

        // echo "<pre>";
        // var_dump($invoiceStatus);
        exit;
      // return redirect()->action('Listing@searchOrders');
      return redirect('orders');

    }
    
    public function createShipmentRequest(Request $request)
    {
     $account = $request->route('account');
     $orderID = $request->route('orderid');
  

      $userID = Auth::id();
      $shipment = $this->getShipmentDetails2($account);

      $order = $this->getOrderDetail([$orderID], $account, 0);
      $products = [];

      $email= '';
      $item =[];
      if (isset($order->items)) {
        $item = $order->items;

        foreach ($item as $key => $value) {
          $sku = $value->item_sku;
          if (!$value->item_sku) {
            $sku = $value->variation_sku;
          }
         
         $ebxSkuData = $this->makeCurl("getsku", "&manufacturer=".$sku);
         
         if ($ebxSkuData) {
            $ebxsku = $this->makeCurl("getsku", "&manufacturer=".$sku)[0]->sku;
         } else {
             $ebxsku = $sku;
         }
   
          if ((substr($ebxsku, 0, strlen('PKG')) === 'PKG') === true) { 
              $pro_pkg = $this->makeCurl("getlistbysku", "&sku=".$ebxsku."&quantity=".$value->variation_quantity_purchased);
              $type ='package';
              $products[$key]['listing'] = $value;
          } else {
              //product
              $pro_pkg = $this->makeCurl("getproductlistbysku", "&sku=".$ebxsku."&quantity=".$value->variation_quantity_purchased);
              $type ='product';
          }

        
        //  if ((substr($ebxsku, 0, strlen('PKG')) === 'PKG') === true) { 
          $products[$key]['listing'] = $value;

             $products[$key][$type][] = $pro_pkg;
          
        //   //order income price
          $products[$key]['listing']->total_amount = $order->escrow_amount;

        //  } else {

        //  }

        }
      
      }

      
      $orderDisplay = array(
            'order'=> array('id'=>$orderID,
            'status'=>$order->order_status,
            'paid'=>$order->total_amount,
            'subtotal'=>$order->total_amount,
            'total'=>$order->total_amount,
            'paidTime'=>$order->pay_time,
            'paymentMethod'=>$order->payment_method),
            'customer'=>array(
              'address'=>$order->recipient_address,
              'userid'=>$order->buyer_username,
              'email'=>$email
            ),
            'listing'=>$products,
            'shipment'=>$shipment
          );

       return view('shopee.shipment', ['data'=>$orderDisplay, 'stores'=>$this->stores(),'sme'=>$this->makeCurl("sme"), 'account'=>$account,'orderID'=>$orderID, 'marketplace'=>2]);

    }
        private function makeHash($url, $body)
    {
      return hash_hmac('sha256', $url .'|'. $body, $this->shopeeKey);
    }
    
     public function getShopInfo($shop_id)
    {
     

      $shop_id = (int)$shop_id;
      $url = "https://partner.shopeemobile.com/api/v1/shop/get";

      $partner_id = (int)2000902;
      $key = "545004a2bd8b2d219c7a286a1f2b77ff50ed4952f3ddc1c706568ae2c811224e";

      $req_body = json_encode(array(
          'partner_id' => $partner_id,
          'shopid' => $shop_id,
          'timestamp' => time()
      ));

      $unhashed = $url .'|'. $req_body;
      $hash = hash_hmac('sha256', $unhashed, $key);

      $headers = array(
          'Authorization: ' . $hash,
          'Content-Type: application/json; charset=utf-8'
      );

      // Request
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      // curl_setopt($ch, CURLOPT_HEADER, TRUE); // Includes the header in the output
      curl_setopt($ch, CURLOPT_POST, TRUE);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $req_body);
      $result = curl_exec($ch);
      $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      curl_close($ch);
      $info = json_decode($result,true);
       return $info;
    }
    
     private function getLaunchpack($marketplace)
    {

        $store  = [];
  
        $productlisting = $this->makeCurl("productlisting", "&pid=0");
            $launchpacks = [];
            
          
            $price = 0;
            $mylisting = 0;

            foreach ($productlisting as $k => $v) {

              foreach ($v as $key => $value) {
                if (isset($value->id)) {

                
                $variations = $this->makeCurl("getproductvariation", "&pid=".$value->id);

                $launchpacks[$value->id]= array(
                  'id'=>$value->id,
                  'ebx_product_id'=>$k,
                  'name'=>$value->name,
                  'date'=>$value->created_dt,
                  'status'=>$value->status,
                  'price'=>$value->selling_price,
                  'variation'=>sizeof($variations)
                ); 
                }

              }
             

            }

            return $launchpacks;
    }
    
    private function getshortOrders($request, $account)
    {
    date_default_timezone_set("Asia/Kuala_Lumpur");
    $input = $request->all();
    $userID = Auth::id();
    $EntriesPerPage = 10;
    $pagenumber = 1;


    $accounts = $this->token($account);
    $date_start = date('Y-m-d',strtotime("-10 days"));
    $date_end = date('Y-m-d');
    

    $shopid = (int)$account;
    $shopname = $this->getShopName($shopid);


    $time_start = "00:00:00";
    $timestamp_start = strtotime($date_start.' '.$time_start);
    $time_end = "23:59:00";

    $timestamp_end = strtotime($date_end.' '.$time_end);

    $url = "https://partner.shopeemobile.com/api/v1/orders/basics";

    $body = json_encode(array(
        'create_time_from' => $timestamp_start,
        'create_time_to' => $timestamp_end,
        'partner_id' => (int)$this->partnerID,
        'shopid' => $shopid,
        'timestamp' => time(),
        'pagination_entries_per_page'=>$EntriesPerPage
    ));


    $data = $this->makePostCurl($url, $this->makeHash($url, $body), $body);
    $last_fetched = $data['lastFetched'];

    $orders = $data['data'];

    return $orders;

    }
      public function shop(Request $request)
    {
      $shopid = $request->route('shopid');
      $shopname = $this->getShopName($shopid);

      //managers
      $query = "SELECT e.account, u.name as username, r.name as rolename FROM ebays e LEFT JOIN users u ON e.user_id = u.id LEFT JOIN roles r ON r.id = u.role WHERE e.account = $shopid";
      $managers = DB::select($query);

      //listings
      $marketplace = $request->route('marketplace');
    $page = 1;
    $limit = 9;
    $offset = 0;
    
    if ($request->route('page')) {
      $page = $request->route('page');
      $offset = $page - 1;
    }
    $shop_id = (int)$shopid;

    $url = "https://partner.shopeemobile.com/api/v1/items/get";

    $partner_id = (int)2000902;
    $key = "545004a2bd8b2d219c7a286a1f2b77ff50ed4952f3ddc1c706568ae2c811224e";
    

    
    $req_body = json_encode(array(
        'pagination_offset' => 0,
        'pagination_entries_per_page' => $limit,
        'partner_id' => $partner_id,
        'shopid' => $shop_id,
        'pagination_offset'=>$offset,
        'timestamp' => time()
    ));

    $unhashed = $url .'|'. $req_body;
    $hash = hash_hmac('sha256', $unhashed, $key);

    $headers = array(
        'Authorization: ' . $hash,
        'Content-Type: application/json; charset=utf-8'
    );

    // Request
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    // curl_setopt($ch, CURLOPT_HEADER, TRUE); // Includes the header in the output
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $req_body);
    $result = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $output = [];
    $listings = json_decode($result,true);

    $listingGallery = [];
    foreach ($listings['items'] as $key => $listing) {
      $listingGallery[] = $this->getListingDetail($listing['item_id'], $shop_id)['item']['images'][0];
    }

    $orders = $this->getshortOrders($request, $shopid);
    $launchpacks = $this->getLaunchpack($marketplace);
    
    $profile = $this->getShopInfo($shopid);

      return view('shop', ['shopname'=>$shopname, 'stores'=>$this->stores(), 'managers'=>$managers, 'listings'=>$listingGallery, 'orders'=>$orders, 'launchpacks'=>$launchpacks, 'profile'=>$profile]);
    }

    public static function getShopName($shop_id)
    {
      if (!isset($_SESSION['shopee']['shopname'][$shop_id])) {

      $shop_id = (int)$shop_id;
      $url = "https://partner.shopeemobile.com/api/v1/shop/get";

      $partner_id = (int)2000902;
      $key = "545004a2bd8b2d219c7a286a1f2b77ff50ed4952f3ddc1c706568ae2c811224e";

      $req_body = json_encode(array(
          'partner_id' => $partner_id,
          'shopid' => $shop_id,
          'timestamp' => time()
      ));

      $unhashed = $url .'|'. $req_body;
      $hash = hash_hmac('sha256', $unhashed, $key);

      $headers = array(
          'Authorization: ' . $hash,
          'Content-Type: application/json; charset=utf-8'
      );

      // Request
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      // curl_setopt($ch, CURLOPT_HEADER, TRUE); // Includes the header in the output
      curl_setopt($ch, CURLOPT_POST, TRUE);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $req_body);
      $result = curl_exec($ch);
      $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      curl_close($ch);
      $info = json_decode($result,true);
        
        if (isset($info['shop_name'])) {
            $_SESSION['shopee']['shopname'][$shop_id] = $info['shop_name'];
        } else {
            $_SESSION['shopee']['shopname'][$shop_id] = 'NO SHOP NAME';
        }
      
      }
      return $_SESSION['shopee']['shopname'][$shop_id];
    }

    public function getAirwayBill($shopID, $orderID)
    {
 
      if(gettype($orderID) == 'string') {
      $order_sn = [$orderID];

      } else {
      $order_sn = $orderID;

      }

      //check total order as limit is 50
      $totalOrders = sizeof($order_sn);
      $bills = [];
 
        static $i;
        if ($totalOrders > 50) {
     
            for ($i=0; $i<=round($totalOrders/50);) {
             
                 $removed = array_slice($order_sn, 0, 50);

                 if (isset($this->fireLimitedAirwaybills($removed, $shopID)[0])) {
                  $bills[] = $this->fireLimitedAirwaybills($removed, $shopID)[0];
                 }
                

                 array_splice($order_sn, 0, 50);
            
                 $i++;

            }
        } else {
      
         $bill = $this->fireLimitedAirwaybills($order_sn, $shopID);
          if (isset($bill)) {
            return $bill[0];
          }
    
        }



       if (isset($bills)) {


        return array_merge(...$bills);
       }
    }

    private function fireLimitedAirwaybills($removed, $shopID){

      $shopid = (int)$shopID;

      $url = "https://partner.shopeemobile.com/api/v1/logistics/airway_bill/get_mass";

      $partner_id = (int)2000902;
      $key = "545004a2bd8b2d219c7a286a1f2b77ff50ed4952f3ddc1c706568ae2c811224e";


                     /**fire get airway bill*/

          $req_body = json_encode(array(
                    'ordersn_list' => $removed,
                    'partner_id' => $partner_id,
                    'shopid' => $shopid,
                    'timestamp' => time()
                ));

                $unhashed = $url .'|'. $req_body;
                $hash = hash_hmac('sha256', $unhashed, $key);

                $headers = array(
                    'Authorization: ' . $hash,
                    'Content-Type: application/json; charset=utf-8'
                );

                // Request
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                // curl_setopt($ch, CURLOPT_HEADER, TRUE); // Includes the header in the output
                curl_setopt($ch, CURLOPT_POST, TRUE);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $req_body);
                $result = curl_exec($ch);
                $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                 /**ends*/
                 $bills = [];
                $output = json_decode($result);
                if (isset($output->result->airway_bills)) {
                 
                  $bills[] = $output->result->airway_bills;
             
                }

                return $bills;
    }
    public function dashboardSummary($shop_ids)
    {

      $output = [];
      foreach ($shop_ids as $key => $shop_id) {
      
      $shopid = (int)$key;

      $url = "https://partner.shopeemobile.com/api/v1/shop/performance";

      $partner_id = (int)2000902;
      $key = "545004a2bd8b2d219c7a286a1f2b77ff50ed4952f3ddc1c706568ae2c811224e";

      $req_body = json_encode(array(
          'partner_id' => $partner_id,
          'shopid' => $shopid,
          'timestamp' => time()
      ));

      $unhashed = $url .'|'. $req_body;
      $hash = hash_hmac('sha256', $unhashed, $key);

      $headers = array(
          'Authorization: ' . $hash,
          'Content-Type: application/json; charset=utf-8'
      );

      // Request
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      // curl_setopt($ch, CURLOPT_HEADER, TRUE); // Includes the header in the output
      curl_setopt($ch, CURLOPT_POST, TRUE);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $req_body);
      $result = curl_exec($ch);
      $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      curl_close($ch);

      $output[$shopid] = json_decode($result, true);
    }
    return $output;
    }

    private function getListingDetail($itemID, $shopID)
    {
         date_default_timezone_set("Asia/Kuala_Lumpur");
      $shopid = (int)$shopID;

$url = "https://partner.shopeemobile.com/api/v1/item/get";

$partner_id = (int)2000902;
$key = "545004a2bd8b2d219c7a286a1f2b77ff50ed4952f3ddc1c706568ae2c811224e";

// $update_from = strtotime(date('Y-m-d 00:00:00')); //max should be 15 days gap 
// $update_to = strtotime(date('Y-m-d 00:00:00', strtotime($update_from. ' +30 days')));

$item_id = (int)$itemID;

$req_body = json_encode(array(
    'item_id' => $item_id,
    'partner_id' => $partner_id,
    'shopid' => $shopid,
    'timestamp' => time()
));

$unhashed = $url .'|'. $req_body;
$hash = hash_hmac('sha256', $unhashed, $key);

$headers = array(
    'Authorization: ' . $hash,
    'Content-Type: application/json; charset=utf-8'
);

// Request
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
// curl_setopt($ch, CURLOPT_HEADER, TRUE); // Includes the header in the output
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req_body);
$result = curl_exec($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
return json_decode($result, true);
    }

  public function getListings(Request $request, $internal = false, $keyword = false)
  {
    $account = '';
     if ($request->route('account')) {
      $account = $request->route('account');
    }
   $marketplace = $request->route('marketplace');
    $page = 1;
    $limit = 10;
    $offset = 0;
    
    if ($request->route('page')) {
      $page = $request->route('page');
      $offset = $page - 1;
    }
    $shop_id = (int)$account;

    $url = "https://partner.shopeemobile.com/api/v1/items/get";

    $partner_id = (int)2000902;
    $key = "545004a2bd8b2d219c7a286a1f2b77ff50ed4952f3ddc1c706568ae2c811224e";
    

    
    $req_body = json_encode(array(
        'pagination_offset' => 0,
        'pagination_entries_per_page' => $limit,
        'partner_id' => $partner_id,
        'shopid' => $shop_id,
        'pagination_offset'=>$offset,
        'timestamp' => time()
    ));

    $unhashed = $url .'|'. $req_body;
    $hash = hash_hmac('sha256', $unhashed, $key);

    $headers = array(
        'Authorization: ' . $hash,
        'Content-Type: application/json; charset=utf-8'
    );

    // Request
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    // curl_setopt($ch, CURLOPT_HEADER, TRUE); // Includes the header in the output
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $req_body);
    $result = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $output = [];
    $listings = json_decode($result,true);
    

    if (isset($listings['items']) && sizeof($listings['items']) > 0) {

    
    foreach ($listings['items'] as $key => $listing) {
      
      $output[$listing['status']][] = array(
        'listing'=>$listing,
        'detail'=>$this->getListingDetail($listing['item_id'], $shop_id)
      );
    }
    }
    
    $totalPages = $listings['total']/$limit;
    $pagination = array(
            'total'=>$listings['total'],
            'current_page'=>$page,
            'limit'=>$limit,
            'total_pages'=>$totalPages
        );

     return view('listings', [
         'selling' => $output, 
         'data' => array_column(array_column(array_values($output), 'listing'), 'status'), 
         'account' => $account, 
         'stores'=>$this->stores(), 
         'source'=>'shopee', 
         'pagination'=>$pagination,
         'shop'=>$shop_id,
         'marketplace'=>$marketplace]);

  }

  private function getAttributes($shop_id, $productID = false)
  {
    if (!$productID) {

      $tpm_base_url4 = 'https://partner.shopeemobile.com/api/v1/item/attributes/get'; // ending with /
        $private_key4  = $this->shopeeKey;

        // Body (json encoded array)
        $request_body4 = json_encode(array(
           'category_id' =>(int)101213,
            'partner_id' => (int)$this->partnerID,
            'shopid' => (int)$shop_id,
            'timestamp' => time())); 

        // Calculate the HMAC ($hash)
        $unhashed4 = $tpm_base_url4 . '|' . $request_body4;
        $hash4     = hash_hmac('sha256', $unhashed4, $private_key4);

        // Request headers
        $headers4 = array(
            'Authorization: ' . $hash4,
            'Content-Type: application/json; charset=utf-8'
        );

        // Request
        $ch4 = curl_init($tpm_base_url4);
        curl_setopt($ch4, CURLOPT_HTTPHEADER, $headers4);
        curl_setopt($ch4, CURLOPT_RETURNTRANSFER, TRUE);
        // curl_setopt($ch4, CURLOPT_HEADER, TRUE); // Includes the header in the output
        curl_setopt($ch4, CURLOPT_POST, TRUE);
        curl_setopt($ch4, CURLOPT_POSTFIELDS, $request_body4);
        $result4 = curl_exec($ch4);
        // $status4 = curl_getinfo($ch4, CURLINFO_HTTP_CODE);
        curl_close($ch4);

        return json_decode($result4);
    } else {
      //fetch from database
        $attributes = $this->makeCurl("productattribute", "&pid=$productID");
        return $attributes;
    }
  }

  private function getLogistics($shop_id)
  {
      $tpm_base_url4 = 'https://partner.shopeemobile.com/api/v1/logistics/channel/get'; // ending with /
        $private_key4  = $this->shopeeKey;

        // Body (json encoded array)
        $request_body4 = json_encode(array(
            'partner_id' => (int)$this->partnerID,
            'shopid' => (int)$shop_id,
            'timestamp' => time())); // root project

        // Calculate the HMAC ($hash)
        $unhashed4 = $tpm_base_url4 . '|' . $request_body4;
        $hash4     = hash_hmac('sha256', $unhashed4, $private_key4);

        // Request headers
        $headers4 = array(
            'Authorization: ' . $hash4,
            'Content-Type: application/json; charset=utf-8'
        );

        // Request
        $ch4 = curl_init($tpm_base_url4);
        curl_setopt($ch4, CURLOPT_HTTPHEADER, $headers4);
        curl_setopt($ch4, CURLOPT_RETURNTRANSFER, TRUE);
        // curl_setopt($ch4, CURLOPT_HEADER, TRUE); // Includes the header in the output
        curl_setopt($ch4, CURLOPT_POST, TRUE);
        curl_setopt($ch4, CURLOPT_POSTFIELDS, $request_body4);
        $result4 = curl_exec($ch4);
        // $status4 = curl_getinfo($ch4, CURLINFO_HTTP_CODE);
        curl_close($ch4);

        return json_decode($result4);
  }

  public function addPackageListing(Request $request, $package)
  {
      $input = $request->input();

      $shop_id = (int)($request->route('account')? $request->route('account') : $input['store']);

      $logistic_list =array();
      $pro = explode('_', $package);
      echo "<pre>";
      var_dump($pro);

      
  }

   public function addListing(Request $request)
   {

      $input = $request->input();

      $shop_id = (int)($request->route('account')? $request->route('account') : $input['store']);

      $logistic_list =array();

      foreach ($input['product'] as $key => $value) {
       if ($value != null) {
        $pro = explode('_', $value);

        if ((substr($pro[2], 0, strlen('PKG')) === 'PKG') === true) {
          
          $this->addPackageListing($request, $value);
        } else {

       $ebxProductID = $pro[0];
       $easProductID = $pro[1];

        $sellingPrice = $this->makeCurl("getebxproductprice", "&pid=$ebxProductID");

         if (!isset($sellingPrice[0])) {
          return;
        }
        $sellingPrice = $sellingPrice[0];


        // $product = $this->makeCurl("geteasproductlisting", "&pid=$easProductID");
        $product = (array)$this->makeCurl("getproductlisting", "&pid=$ebxProductID");

        

        if (!isset(array_values($product)[0]->product)) {
          return;
        }


      $product = array_values($product)[0]->product->{0};




        $gallery = $this->makeCurl("getproductgallery", "&pid=$ebxProductID");
        $description = $this->makeCurl("getproductdescription", "&pid=$ebxProductID");



        $images_list = [];

        $imagepath = 'http://ebx.axisdigitalleap.asia/web/uploads/product/';
    
        //add image
        $galleryImage = explode(',', $gallery[0]->photo);

        if (isset($galleryImage) && sizeof($galleryImage) > 0) {
           foreach ($galleryImage as $k => $img) {

          $images_list[$k] = array('url'=>($img == ''? "http://axisnet.asia/memberv2/admin/productimages/11108790(99743).jpg": rtrim($imagepath.$img,',')));
              
          }
        } else {
          $images_list[0] = array('url'=>"");

        }


      $logistics = $this->getLogistics($shop_id);
      $attributes = $this->getAttributes($shop_id, $product->id);


      $sAttributes = [];

      $i = 0;
 
      // foreach ($attributes as $key => $value) {
      //   if (is_array($value)) {

      //   foreach ($value as $k => $v) {
      //     if ($v->is_mandatory) {
      //     $i++;
      //     $sampleAttributes = new \stdClass();
          
      //     $sampleAttributes->attributes_id = $v->attribute_id;
      //     $sampleAttributes->value = $v->options[0];
      //     $sAttributes[] = $sampleAttributes;
      //     }
         
      //   }
      //   }

      // }
      $category_id = 0;

      foreach ($attributes as $k => $v) {
 
          $i++;
          $sampleAttributes = new \stdClass();
          $sampleAttributes->attributes_id = (int)$v->name;
          $sampleAttributes->value = $v->value;
          $sAttributes[$v->name] = $sampleAttributes;
          $category_id = $v->shopee_cat_id;

          // $sampleAttributes->attributes_id = (int)$v->name;
          // $sampleAttributes->attribute_name = $v->label;
          // $sampleAttributes->is_mandatory = true;
          // $sampleAttributes->attribute_type = 'STRING_TYPE';
          // $sampleAttributes->input_type = 'COMBO_BOX';
          // $sampleAttributes->options = $v->name;
          
          // $values = new \stdClass();
          // $values->original_value = '';
          // $values->translate_value = '';
          // $sampleAttributes->values = $values;



          
          $sAttributes[] = $sampleAttributes;
          $category_id = $v->shopee_cat_id;



      }

     $a = [];
     foreach ($sAttributes as $akey => $avalue) {
       $a[$avalue->attributes_id] = $avalue;
     }

     $a = array_values($a);
     // echo "<pre>";
     // var_dump($a);exit;
      $i = 0;

      foreach ($logistics as $key => $value) {
       if (gettype($value) == 'array') {

       
        foreach ($value as $k => $v) {
        
             $i++;
             if ($v->enabled) {

                 $logis = new \stdClass();

                 $logis->logistic_id = (int)$v->logistic_id;
                 $logis->enabled = $v->enabled;
                 $logistic_list[$i] = $logis;
             // $logistic_list[$i] = array('logistic_id'=>(int)$v->logistic_id,'enabled'=>$v->enabled);

             }
         
         

         } 
         }       
      }


      // $url = "https://partner.shopeemobile.com/api/v1/shop/get";
      $url = 'https://partner.shopeemobile.com/api/v1/item/add';
      $partner_id = (int)$this->partnerID;
      $key = $this->shopeeKey;


unset($logistic_list[3]);

$logistic_list = array_values($logistic_list);


      $logis = new \stdClass();
      $logis->logistic_id = 2000;
      $logis->enabled = true;

      //quantity yet to decide
      $qty = 100;
      $req_body = json_encode(array(
            'partner_id' =>(int)$partner_id,
            'shopid' => (int)$shop_id,
            'timestamp' => time(),
            'category_id' =>(int)$category_id,
            'name' => $product->listing_name,
            'description' => $description[0]->shopee_descr,
            'item_sku'=> $product->sku,
            'price' => (float)$sellingPrice->selling_price,
            'stock' => (int)$qty,
            'images' =>$images_list,
            'logistics' => [$logis],
            'weight' => (float)($product->weight == 0 ? '1.5':$product->weight),
            'condition' => "NEW",
            'attributes'=>$a,
            'status' => "NORMAL"));

      $unhashed = $url .'|'. $req_body;
      $hash = hash_hmac('sha256', $unhashed, $key);

      $headers = array(
          'Authorization: ' . $hash,
          'Content-Type: application/json; charset=utf-8'
      );

      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      // curl_setopt($ch, CURLOPT_HEADER, TRUE); // Includes the header in the output
      curl_setopt($ch, CURLOPT_POST, TRUE);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $req_body);
      $result = curl_exec($ch);
      $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      curl_close($ch);
      $info = json_decode($result,true);

      if (isset($info['error'])) {
        echo json_encode($info['msg']);exit;
      }
      // echo "<pre>";
      // var_dump($info);
      // exit;
      // var_dump($info['item_id']);
      // var_dump($info['msg']);

      //add variations
      $this->addVariationsListing($product->id, $info['item_id'], $shop_id, $images_list, $product->listing_name);
      }
      }
      }
    return true;
    }

    public function addVariationsListing($productID, $itemID, $shop_id, $images_list, $productName)
    {

      $variations = $this->makeCurl("getproductvariation", "&pid=$productID");

      $model = new \stdClass();

      foreach ($variations as $key => $variation) {
        $model->$key = new \stdClass();


        $model->$key->name = ($variation->name == '' ? $productName : $variation->name);
        $model->$key->stock = $variation->quantity;
        $model->$key->price = $variation->price_member;
        $model->$key->variation_sku = $variation->variant_sku;
      }
 
      $partner_id = (int)$this->partnerID;
      $key = $this->shopeeKey;
      $url = "https://partner.shopeemobile.com/api/v1/item/add_variations";


      $req_body = json_encode(array(
            'partner_id' =>(int)$partner_id,
            'shopid' => (int)$shop_id,
            'timestamp' => time(),
            'item_id' =>(int)$itemID,
            'variations' => [$model]));

     
      $unhashed = $url .'|'. $req_body;
      $hash = hash_hmac('sha256', $unhashed, $key);

      $headers = array(
          'Authorization: ' . $hash,
          'Content-Type: application/json; charset=utf-8'
      );


      //variations
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      // curl_setopt($ch, CURLOPT_HEADER, TRUE);
      curl_setopt($ch, CURLOPT_POST, TRUE);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $req_body);
      $result = curl_exec($ch);
      $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      curl_close($ch);
      $info = json_decode($result,true);

    return $info;
    }

}
