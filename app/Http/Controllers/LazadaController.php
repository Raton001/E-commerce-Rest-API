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
use \App\Http\Controllers\LazopClient as lazoclient;
use \App\Http\Controllers\LazopRequest as lazoreq;


class LazadaController extends Controller
{
    use \App\Http\Controllers\HelperController;
    use \App\Http\Controllers\AuthMarketplaceController;
    use \App\Http\Controllers\CalculatorController;

     public $marketplace = '';

     public function __construct()
    {
      $this->marketplace = 3;

    }
  public function setupPage($firstTime)
  {

    return $this->setup($firstTime);

  }

    public function authenticate() {

        return $this->authenticateLazada();
    }

public function accept()
  {

                $this->userID = Auth::id();
//   echo "<pre>";
//   var_dump($_GET);exit;
        $code = $_GET['code'];

        $main_url = "https://auth.lazada.com/rest";
        $path = "/auth/token/create";
        $app_key = (int)102326;
        $secret_key = "HKr03p116qyjDdYHpXLW5CbA1EWk7j7O";

        $url = $main_url . $path;

        $c = new LazopClient($main_url,$app_key,$secret_key);
        $request = new LazopRequest('/auth/token/create');
        $request->addApiParam('code',$code);

        $result = $c->execute($request);


        $result = json_decode($result, true);


        // if (!isset($result['code'])) {

              $ebay = new \App\Ebay;
          $ebay->user_id = $this->userID;
          $ebay->account = $result['account'];
          $ebay->marketplace_id =3;
          $ebay->code = $code;
          $ebay->access_token = $result['access_token'];
          $ebay->refresh_token = $result['refresh_token'];
          $ebay->expiry = $result['expires_in'];



          $ebay->save();

           //mark setup complete
           if (!\App\Setup::where(['user_id'=> $this->userID, 'marketplace_id'=>'lazada'])->exists()) {

              //mark setup complete
              $ebay = new \App\Setup;
              $ebay->user_id = $this->userID;
              $ebay->marketplace_id = 'lazada';
              $ebay->save();
            }
        // } else {
        //     echo "error";exit;
        // }

         return redirect('/lazada/setup/3');

    }

  public function index()
    {

        $role = $this->userRole();
        $store  = [];

          $query = 'SELECT l.id, l.launch_name, l.launch_date, l.status, ll.package_id, ll.template, (select COUNT(*) FROM launchpack_listings where launchpack_id = l.id) listing, (select COUNT(*) FROM mylaunchpacks ml LEFT JOIN launchpack_listings ll2 ON ml.listing_id = ll2.id where ml.listing_id = ll.id) mylisting FROM launchpacks l LEFT JOIN launchpack_listings ll ON l.id = ll.launchpack_id';

          //show only active for non ME
          if ($role != 1) {
            $query .= ' WHERE l.status = 1';
          }
          // $query .= ' GROUP BY l.id, l.launch_name, l.launch_date, l.status, ll.package_id, ll.template';
          // echo $query;exit;

           $data = DB::select($query);


            $launchpacks = [];


            $price = 0;
            $mylisting = 0;
            foreach ($data as $key => $value) {

            $mylisting += $value->mylisting;

            $launchpacks[$value->id]['id'] = $value->id;

            $launchpacks[$value->id]['name'] = $value->launch_name;
            $launchpacks[$value->id]['date'] = $value->launch_date;
            $launchpacks[$value->id]['status'] = $value->status;
            $launchpacks[$value->id]['mylisting'] = $mylisting;
            $launchpacks[$value->id]['listing'] = $value->listing;



            $template = json_decode($value->template);


            if (gettype($template) != null) {
             $query2 = "SELECT COUNT(*) as count FROM `launchpack_listings` WHERE launchpack_id = ".$value->id;
             $count = DB::select($query2);
            if (isset($template->StartPrice)) {
              $price += $template->StartPrice->StartPrice;
            }

              // $launchpacks[$value->id]['count'] = $value->listing;
             //  $launchpacks[$value->id]['listed'] = $value->mylisting;

              $launchpacks[$value->id]['price'] = $price;
              if (isset($template->StartPrice)) {
                  $launchpacks[$value->id]['currency'] = $template->StartPrice->currencyID;
              }

            }



            }

            $accounts = $this->token();
            $sellings = [];

            foreach ($accounts as $k => $account) {
              foreach ($account as $key => $value) {

                $store[] = $value['account'];

              }
            }



        return view('lazada.home', ['launchpacks'=>$launchpacks, 'menu'=>$this->menu(), 'role'=>$role, 'store'=>$store, 'stores'=>$this->stores()]);
    }


  public function getOrders($request)
  {

    date_default_timezone_set("Asia/Kuala_Lumpur");

    $accounts = $this->tokens();
    $account = $request->route('account');
     $userID = Auth::id();
     $output = [];
      $invoiceStatus = [];

    foreach ($accounts as $k => $value) {
      if ($value['account'] == $account) {

        $access_token = $value['access_token'];
     $timestamp = time();

    $main_url = "https://api.lazada.com.my/rest";
    $path = "/orders/get";
    $app_key = (int)102326;
    $secret_key = "HKr03p116qyjDdYHpXLW5CbA1EWk7j7O";
    $code = $value['code'];
    $s = date('Y-m-d',strtotime("-10 days"))." 23:00:00";
    $e = date('Y-m-d')." 23:59:00";

     $date_start = new \DateTime($s); // YYYY-MM-DD
    $date_end = new \DateTime($e); // YYYY-MM-DD

    $time_start = $date_start->format(\DateTime::ATOM);
    $time_end = $date_end->format(\DateTime::ATOM);





    $c = new lazoclient($main_url,$app_key,$secret_key);
    $request = new lazoreq($path, 'GET');

    $request->addApiParam('access_token',$access_token);

    // $request->addApiParam('update_before',$time_end);
    // $request->addApiParam('update_after', $time_start);
    // echo "2021-07-06T16:00:00+08:00";

 $request->addApiParam('update_before',$time_end);
    $request->addApiParam('update_after', $time_start);

        $request->addApiParam('created_before',$time_end);
    $request->addApiParam('created_after', $time_start);

    $request->addApiParam('sort_direction','DESC');
    // $request->addApiParam('offset','100');
    // $request->addApiParam('limit','100');
    $request->addApiParam('sort_by','updated_at');

    // $request->addApiParam('status','ready_to_ship');


    $result = $c->execute($request);


  $orders = json_decode($result, true);


  $status = [];

// echo "<pre>";
// var_dump($orders);
// exit;

  if (isset($orders['data'])) {



  $orderIDs = array_column($orders['data']['orders'], 'order_id');



      $dataPushCompile = ['order_id'=>json_encode($orderIDs)];
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, 'https://axisnet.asia/memberv2/API/getordersbystatus.php');
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $dataPushCompile);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $data = curl_exec($ch);
      curl_close($ch);



      $invoiceStatus = json_decode(rtrim($data, "'"));


      $output = [
        'new'=>$orders['data']['orders']
      ];

  // foreach ($orders['data']['orders'] as $key => $value) {

  //   if (isset($invoiceStatus->{$value['order_id']})) {
  //   $value['statuses'][0] = $invoiceStatus->{$value['order_id']}->status;
  //   $value['invoice_id'] = $invoiceStatus->{$value['order_id']}->invoice_id;
  //   $output[$value['statuses'][0]][] = $value;


  //   }

  // }

    $status = array_column($orders['data']['orders'], 'statuses');

      }
    }

     }



  return view('orders', ['account'=>$account,'data'=>$output,'stores'=>$this->stores(), 'source'=>'lazada', 'axisStatus'=>$invoiceStatus
  ]);
  }

  public function getOrder($request)
  {

    date_default_timezone_set("Asia/Kuala_Lumpur");

    $accounts = $this->tokens();
    $account = $request->route('account');
    $orderid = $request->route('orderid');

     $userID = Auth::id();
     $output = [];
$invoiceStatus = [];

    foreach ($accounts as $k => $value) {
      if ($value['account'] == $account) {

        $access_token = $value['access_token'];
     $timestamp = time();

    $main_url = "https://api.lazada.com.my/rest";
    $path = "/order/get";
    $app_key = (int)102326;
    $secret_key = "HKr03p116qyjDdYHpXLW5CbA1EWk7j7O";
    $code = $value['code'];
    $s = date('Y-m-d',strtotime("-10 days"))." 23:00:00";
    $e = date('Y-m-d')." 23:59:00";

     $date_start = new \DateTime($s); // YYYY-MM-DD
    $date_end = new \DateTime($e); // YYYY-MM-DD

    $time_start = $date_start->format(\DateTime::ATOM);
    $time_end = $date_end->format(\DateTime::ATOM);





    $c = new lazoclient($main_url,$app_key,$secret_key);
    $request = new lazoreq($path, 'GET');

    $request->addApiParam('access_token',$access_token);

    // $request->addApiParam('update_before',$time_end);
    // $request->addApiParam('update_after', $time_start);
    // echo "2021-07-06T16:00:00+08:00";

 $request->addApiParam('update_before',$time_end);
    $request->addApiParam('update_after', $time_start);

        $request->addApiParam('created_before',$time_end);
    $request->addApiParam('created_after', $time_start);

    $request->addApiParam('sort_direction','DESC');
    // $request->addApiParam('offset','0');
    // $request->addApiParam('limit','100');
    $request->addApiParam('sort_by','updated_at');

    $request->addApiParam('status','ready_to_ship');

$request->addApiParam('order_id',$orderid);
    $result = $c->execute($request);


  $orders = json_decode($result, true);


  $status = [];

// echo "<pre>";
// var_dump($orders);
// exit;

  if (isset($orders['data'])) {



  $orderIDs = array_column($orders['data'], 'order_id');



      $dataPushCompile = ['order_id'=>json_encode($orderIDs)];
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, 'https://axisnet.asia/memberv2/API/getordersbystatus.php');
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $dataPushCompile);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $data = curl_exec($ch);
      curl_close($ch);



      $invoiceStatus = json_decode(rtrim($data, "'"));


      $output = [
        'new'=>$orders['data']
      ];

  // foreach ($orders['data']['orders'] as $key => $value) {

  //   if (isset($invoiceStatus->{$value['order_id']})) {
  //   $value['statuses'][0] = $invoiceStatus->{$value['order_id']}->status;
  //   $value['invoice_id'] = $invoiceStatus->{$value['order_id']}->invoice_id;
  //   $output[$value['statuses'][0]][] = $value;


  //   }

  // }

    $status = array_column($orders['data'], 'statuses');

      }
    }

     }



  return view('single-order', ['account'=>$account,'data'=>$output,'stores'=>$this->stores(), 'source'=>'lazada', 'axisStatus'=>$invoiceStatus
  ]);
  }

public function refresh()
{
    session_destroy();
    session_start();
     return redirect()->back()->with('success', 'Page Refreshed');
}

  public function getListings(Request $request, $internal = false, $keyword = false)
  {
     $accounts = $this->tokens();
    $account = $request->route('account');
     $userID = Auth::id();
     $output = [];


    foreach ($accounts as $k => $value) {
      if ($value['account'] == $account) {

        $access_token = $value['access_token'];
      }
    }

     date_default_timezone_set("Asia/Kuala_Lumpur");


    $timestamp = time();

    $main_url = "https://api.lazada.com.my/rest";
    $path = "/products/get";
    $app_key = (int)102326;
    $secret_key = "HKr03p116qyjDdYHpXLW5CbA1EWk7j7O";


    $c = new LazopClient($main_url,$app_key,$secret_key);
    $request = new LazopRequest($path, 'GET');
    // $request->addApiParam('access_token',$access_token);
    $request->addApiParam('filter','live');
    $request->addApiParam('update_before','2021-10-15T09:00:00+0800');
    $request->addApiParam('create_before','2021-10-15T09:00:00+0800');
    $request->addApiParam('create_after','2021-01-01T00:00:00+0800');
    $request->addApiParam('update_after','2021-01-01T00:00:00+0800');
    $request->addApiParam('offset','0');
    $request->addApiParam('limit','10');
    // $request->addApiParam('options','1');
    // $request->addApiParam('sku_seller_list',' [\"39817:01:01\", \"Apple 6S Black\"]');
    $result = $c->execute($request, $access_token);

    $listings = json_decode($result, true);

    if (isset($listings['data']['products']) && sizeof($listings['data']['products']) > 0) {


    foreach ($listings['data']['products'] as $key => $listing) {
      $output[array_column($listing['skus'], 'Status')[0]][] = array(
        'listing'=>$listing
      );
    }
    }

     return view('listings', ['selling' => $output, 'data' => array_keys($output), 'status', 'account' => $account, 'stores'=>$this->stores(), 'source'=>'lazada']);

  }

private function formatListing($listing, $vendor= false, $freeGift= false, $totalAmount)
    {

      $formattedList = [];
      $packageID = [];
      $variationSku = '';
      $items = (array)$listing;

      if (sizeof($items) > 0) {


        foreach ($listing  as $key => $product) {
            if ($product->sku == '' && $product->shop_sku == '') {
                return;
            }
          $sku = $product->sku;
          if (!$product->sku) {
            $sku = $product->shop_sku;
          }
          if ($product->shop_sku) {
              $variationSku = $product->shop_sku;
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


if ($sku == '') {
    return false;
}

  //check if package
  if ((substr($sku, 0, strlen('PKG')) === 'PKG') === true) {


      $pro_pkg = $this->makeCurl("getlistbysku", "&sku=".$sku."&quantity=1");
       $type ='package';

//check null
if ($pro_pkg) {


    if (sizeof($pro_pkg) <= 0){


        if($variationSku) {
                       $pro_pkg = $this->makeCurl("getlistbysku", "&sku=".$variationSku."&quantity=1");
                    }


      }

    }


  } else {


      //product
      $pro_pkg = $this->makeCurl("getproductlistbysku", "&sku=".$sku."&quantity=1");

       $type ='product';
      if (sizeof($pro_pkg) <= 0){


        if($variationSku) {

                       $pro_pkg = $this->makeCurl("getproductlistbysku", "&sku=".$variationSku."&quantity=1");
                    }


      }
  }

  if (sizeof($pro_pkg) <= 0) {

      return false;
  }

        if (isset($pro_pkg[0])) {
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
              'po_listing_title'=>$product->name,
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

            if (isset($pro_pkg->{$type})) {
        $quantity = 1;
        $price = 0.00;
        $variantPrice = 0.00;

        if (isset($pro_pkg->{$type}->quantity)) {
          $quantity = $pro_pkg->{$type}->quantity;// * $product->variation_quantity_purchased;
          // $quantity = $pro_pkg->package->quantity;
          //
          $price = $pro_pkg->{$type}->price;//$pro_pkg->{$type}->quantity * $pro_pkg->{$type}->price;
          // $price = $pro_pkg->package->price;


        }


         if (isset($product->variation_original_price)) {

          $variantPrice = $product->variation_original_price;//$pro_pkg->{$type}->quantity * $product->variation_original_price;

        }
        if ($type == 'product') {
            $iddd = 'product_id';
        }else {
            $iddd = 'package_id';
        }
         $packageID[] = $pro_pkg->{$type}->{$iddd};



          $formattedList[] = array(
              'po_product_id'=>$pro_pkg->{$type}->variant_id,
              'po_brand_id'=>$pro_pkg->{$type}->brand_id,
              'po_product_quantity'=>$quantity,
              'po_listing_title'=>$product->item_name,
              'po_listing_url'=>$product->product_detail_url,
              'po_selling_mode'=>'Buy It Now',
              'po_final_selling_price'=>$totalAmount,
              'po_currency'=>'MYR',
              'po_product_price'=>$price,
              'po_stock_mode'=>'company',
              'po_status'=>'pending',
              'po_date'=>date('d-m-Y')
          );

            }

            /*non variation ends*/

        }


         }


         return [$formattedList, $packageID];
      }

    }

private function formatOrder($order, $shipment, $items, $shopID, $orderItem)
{

  $products = $items[0];
  $packageID = implode(',', $items[1]);
  $address = explode(',', $order->address_shipping->address1);

foreach($address as $k=>$add) {

    if (trim($add) == $order->address_shipping->post_code || trim($add) == $order->address_shipping->address4) {
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

if (sizeof(array_column($orderItem, 'order_item_id'))>0) {
$items = implode(',', array_column($orderItem, 'order_item_id'));
}

//$order->ordersn
// $airwayBills = $this->getAirwayBill($shopID, $order->order_id, $items);


// if (isset($airwayBills[0]->airway_bill)) {
// $airway = $airwayBills[0]->airway_bill;
// } else {
//   $airway = '';
// }
  $airway = '';


       return array(
            'invoice_uid' => $shipment[0]->axis_user_id,
            'invoice_marketplace' => 'Lazada',
            'invoice_shipment_date' => date('d-m-Y'),
            'invoice_shipping_mode' => 'Lazada Logistic',
            'invoice_shipment_status' => 'pending',
            'invoice_shipment_mode' => 'company',
            'invoice_weight' => '0',
            'invoice_shipping_fee' => '0.00',
            'invoice_shipping_cost' => '0.00',
            'invoice_currency' => 'MYR',
            'invoice_final_selling_price' => $order->price,
            'invoice_admin_remark' => 'via API',
            'invoice_orderid' => $order->order_id,
            'invoice_package_id' => $packageID,
            'invoice_date' => date('d-m-Y'),
            'invoice_submit_time' => date("h:i:sa"),
            'shipment_ebay_id' => $shipment[0]->axis_shop_id,
            'shipment_customer_name' =>  $order->address_shipping->first_name,
            'shipment_customer_address1' => $address[0],
            'shipment_customer_address2' => $add,
            'shipment_city' =>  $order->address_shipping->city,
            'shipment_state' =>  $order->address_shipping->address4,
            'shipment_postcode' =>  $order->address_shipping->post_code,
            'shipment_country' =>  'MY',//$order->address_shipping->country,
            'shipment_customer_contact' =>  $order->address_shipping->phone,
            'shipment_customer_email' => '',
            'shipment_status' => 'pending',
            'shipment_date' => date('d-m-Y'),
            'product_arr' => $products,
            'airway' => $airway
        );
}

  public function createBundleShipmentRequest(Request $request)
  {
    //   echo "<pre>";
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

  // array_push($input['orders'], '306123947729243');

    foreach ($input['orders'] as $key => $value) {
      $orders[$value]['order'] = $this->getOrderDetail($value, $account, 0);
      $orders[$value]['order_item'] = $this->getOrderItem($value, $account, 0);

    }


    $listing = [];

$j=0;

if (gettype($orders)=='array') {

    foreach ($orders as $key => $value) {

 $j++;

// echo $j."<br/>";
// foreach($value as $k=>$v) {



$listingTmp = $value;


      // unset($listing->$value->items);
      $listing[$value['order']->data->order_id] = $listingTmp;

      if ($listingTmp['order']->data->statuses[0] == 'pending') {


        // $listing[$listingTmp->order_id]->items
        $products = $this->formatListing($listingTmp['order_item']->data, $skuType, $freeGift, $listingTmp['order']->data->price);
    if (!$products) {
        return redirect()->back()->with('error', "There's a problem with the SKU");
    }
          $formattedData = $this->formatOrder($listingTmp['order']->data, $shipment, $products, $account, $listingTmp['order_item']->data);
    //   var_dump($formattedData);

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




        //   insert into member v2 starts
            $dataPushCompile = http_build_query($formattedData, '', '&');
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
              $ebay->order_id = $formattedData['invoice_orderid'];
              $ebay->marketplace = 2;
              $ebay->shop = $account;
              $ebay->created_by = Auth::id();
              $ebay->save();
            }

          //memberv2 ends


    //   }
}

// }
}


    } else {


         $j++;
         $listingTmp = $orders;
         $listing[$listingTmp[0]->order_id] = $listingTmp;
        //single order
        if ($listingTmp['order']->data->statuses[0] == 'pending') {

            echo $j."<br/>";
            // $listing[$listingTmp->order_id]->items
            $products = $this->formatListing($listingTmp['order_item']->data, $skuType, $freeGift, $listingTmp['order']->data->paid_price);

                if (!$products) {

                    return redirect()->back()->with('error', "There's a problem with the SKU");
                }

            $formattedData = $this->formatOrder($listingTmp['order']->data, $shipment, $products, $account, $listingTmp['order_item']->data);
    //   var_dump($formattedData);



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

            //  insert into member v2 starts
            $dataPushCompile = http_build_query($formattedData, '', '&');
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
              $ebay->order_id = $formattedData['invoice_orderid'];
              $ebay->marketplace = 2;
              $ebay->shop = $account;
              $ebay->created_by = Auth::id();
              $ebay->save();
            }
                }


    }
    // exit;
     session_destroy();
     return redirect()->back()->with('success', 'Shipment Requests Successfully Submitted');

  }

 public function getAirwayBill($account, $orderID, $items)
    {

        $value = $this->token($account);

        $access_token = $value['access_token'];
        $timestamp = time();
        $main_url = "https://api.lazada.com.my/rest";
        $path = "/order/document/get";
        $app_key = (int)102326;
        $secret_key = "HKr03p116qyjDdYHpXLW5CbA1EWk7j7O";
        $code = $value['code'];
        $c = new LazopClient($main_url,$app_key,$secret_key);
        $request = new LazopRequest($path,'GET');
        $output = [];
        // foreach ($orderSn as $key => $order) {
        $request->addApiParam('doc_type','shippingLabel');
        //
        $request->addApiParam('order_item_ids',$items);
        $result = $c->execute($request, $access_token);
        $bills = json_decode($result);

      var_dump($bills);
                return $bills;
    }


private function getOrderItem($orderSn, $account, $byStatus = 1)
  {

    $value = $this->token($account);

       $access_token = $value['access_token'];
     $timestamp = time();
    $main_url = "https://api.lazada.com.my/rest";
    $path = "/order/items/get";
    $app_key = (int)102326;
    $secret_key = "HKr03p116qyjDdYHpXLW5CbA1EWk7j7O";
    $code = $value['code'];
$c = new LazopClient($main_url,$app_key,$secret_key);
$request = new LazopRequest($path,'GET');
$output = [];
// foreach ($orderSn as $key => $order) {
$request->addApiParam('order_id',$orderSn);
$result = $c->execute($request, $access_token);
$output = json_decode($result);




  return $output;

  }

  private function getOrderDetail($orderSn, $account, $byStatus = 1)
  {

    $value = $this->token($account);

       $access_token = $value['access_token'];
     $timestamp = time();
    $main_url = "https://api.lazada.com.my/rest";
    $path = "/order/get";
    $app_key = (int)102326;
    $secret_key = "HKr03p116qyjDdYHpXLW5CbA1EWk7j7O";
    $code = $value['code'];
$c = new LazopClient($main_url,$app_key,$secret_key);
$request = new LazopRequest($path,'GET');
$output = [];
// foreach ($orderSn as $key => $order) {
$request->addApiParam('order_id',$orderSn);
$result = $c->execute($request, $access_token);
$output = json_decode($result);




  return $output;

  }

      public function shipmentShop($request)
    {
      $username = $request->username;

      $shops = $this->getShipmentDetails($username);
      return $shops;
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
      '/=\s+(\"|\')/'); # strip whitespaces between = "'

     $replace = array(
      "\n",
      "\n",
      " ",
      "",
      " ",
      "><",
      "$1>",
      "=$1");

      $html = preg_replace($search,$replace,$html);
      return $html;
  }
    //getInvoice
    public function getInvoice(Request $request){
      if ($request->route('account')) {
        $account = $request->route('account');
      }
      if ($request->route('marketplace')) {
        $marketplace = $request->route('marketplace');
      }

      $keys = ['completed', 'Deleted','under process'];
      return view('lazada.invoice',
        ['account'=>$account,
        'marketplace'=>$marketplace,
        'stores'=>$this->stores(),
        'source'=>'lazada',
        'keys'=>$keys,
        'page'=>'invoice.cde',
        'title'=>'Invoice',
        'form'=>'Invoice']);
       }
      public function getAjaxInvoice(Request $request){
        $column =[];
        $input = $request->all();

        if ($request->route('account')){
          $account = $request->route('account');
        }
        if ($request->route('marketplace')){
          $marketplace = $request->route('marketplace');
        }
        $offsetStr = '';
        if (isset($input['offset'])) {
          $offsetStr = '&offset='.$input["offset"];
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://axisnet.asia/memberv2/API/getinvoice.php?marketplace=lazada'.$offsetStr);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $invoiceStatus = curl_exec($ch);
        curl_close($ch);

        $invoiceStatus = json_decode($invoiceStatus,true);
        $status = array_unique(array_column($invoiceStatus["data"], 'shipment_status'));

        static $count = 0;

        foreach ($invoiceStatus["data"] as $key => $value) {
          $count++;

          $column[$value['shipment_status']][] = $this->setRowInvoice(
            $value, $count,$marketplace,$account);
        }
        return ['invoice'=>$column,'more'=>$invoiceStatus["more"],'offset'=>$invoiceStatus["offset"]];

      }
      Public function isCoded($invoiceId)
        {
          if (!\App\shippedOrder::where(['invoice_id'=> $invoiceId])->exists()) {
            return false;
          }
          return true;

        }
      public function setRowInvoice($value, $count,$marketplace,$account)
        {
          $invoiceId = [];
          $isCoded = $this->isCoded($invoiceId);
          $action='<a target="_blank" href="/'.$marketplace.'/'.$account.'/order/ship/'.$value["order_id"].'" data-item-id="'.$value["order_id"].'"><small class="text-muted"><i class="bx bx-show-alt"></i></span></a>';
          ob_start();

            ?>
            <tr>
              <td><?php echo $count;?></td>
              <td>
                <fieldset>
                    <div class="checkbox checkbox-info checkbox-glow">

                        <input type="checkbox" name="invoice[]" id="ship_<?php echo $value["id"]?>" value="<?php echo $value['id']?>" >
                            <label for="ship_<?php echo $value["id"]?>"></label>

                    </div>
                </fieldset>
              </td>
              <td><?php echo $value["id"]?></td>
              <td><?php echo $value["order_id"]?></td>
              <td><?php echo $value["shipment_date"]?></td>
              <td><?php echo $value["shipment_mode"]?></td>
              <td><?php echo $value["shipment_status"]?></td>
              <td><?php echo $value["shipping_mode"]?></td>
              <td><?php echo $value["tracking_code"]?></td>
              <td><?php echo $value["shipping_fee"]?></td>
              <td><?php echo $value["final_selling_price"]?></td>
              <td>
                <?php echo (isset($value["airwaybill_url"]) ? '<span class="bullet bullet-success bullet-sm"></span>': '<span class="bullet bullet-danger bullet-sm"></span>');?>
                <a target="_blank" href="<?php echo (isset($value["airwaybill_url"]) ? $value["airwaybill_url"]: '');?>">
                <small class="text-muted">
                <?php echo ($value["airwaybill_url"] !='' ? 'View': '-');?>
                </small>
                </a>
              </td>
              <td><?php
                if($isCoded){
                  echo "yes";
                  }else{
                  echo "no";
                  }
                ?>
              </td>
              <td><?php echo $action ?></td>

            </tr>
              <?php

            $html = ob_get_contents();
            ob_end_clean();
            return $this->minify_html($html);
        }


}
