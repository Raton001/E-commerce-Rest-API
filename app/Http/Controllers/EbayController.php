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
use \App\Http\Controllers\Controller as control;
use \App\Http\Controllers\ListingController as Listing;




use Illuminate\Support\Facades\Mail;
use App\Mail\OrderShipped;
use App\Mylaunchpack;
use App\myOldlisting;
use App\Order;
use Log;

class EbayController extends Controller
{
    use \App\Http\Controllers\HelperController;
    use \App\Http\Controllers\AuthMarketplaceController;
    use \App\Http\Controllers\CalculatorController;

    public $axisProductIdentifier = '';
    public $axisPackageIdentifier = '';
    public $marketplace = '';

    public function __construct()
    {
      $this->axisProductIdentifier = 'APR';
      $this->axisPackageIdentifier = 'APKG';
      $this->marketplace = 1;
      
    }
    
    public function subscribeNotification()
    {
            // $data = \App\Ebay::where(['user_id'=>Auth::id(), 'account'=>$account])->get();

            // $oauthToken = collect($data->toArray())->first();
            // $ch = curl_init();
            // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // curl_setopt($ch, CURLOPT_HTTPHEADER,  array("Authorization: Bearer {$oauthToken}","Content-Type: application/x-www-form-urlencoded"));
            // curl_setopt($ch,CURLOPT_URL,"https://api.ebay.com/commerce/notification/v1/subscription");
            // $shipmentPolicy = curl_exec($ch);
            // curl_close($ch);    
    }
    
    public function notification()
    {

        if (isset($_GET['challenge_code'])) {
            
        
        $challengeCode = $_GET['challenge_code'];
        $verificationToken = 'AxisNetworkTechnologyCodedProject2021';
        $endpoint = 'https://coded.axisdigitalleap.asia/ebay/notification/';
        $hash = hash_init('sha256');

        hash_update($hash, $challengeCode);
        hash_update($hash, $verificationToken);
        hash_update($hash, $endpoint);
        
        $responseHash = hash_final($hash);

        
        $data = ['challengeResponse'=>$responseHash];
        header('Content-Type: application/json');
        echo json_encode($data);
        }
        
        
      
    }
    
    public function authenticate() {
   
        return $this->authenticateEbay();
    }

    public function shipmentShop($request)
    {
      $username = $request->username;

      $shops = $this->getShipmentDetails($username);
      return $shops;
    }

    public function shipmentShopSave($request)
    {

      $input = $request->all();
      $userID = Auth::id();
      $marketplace = \Route::current()->parameter('marketplace');
      $account = $input['edit'];
      $smeID = 0;

      //if edit account name given else get the last row of this user and marketplace
if ($account) {
$query = "SELECT id FROM ebays WHERE user_id = $userID AND marketplace_id = '".$input['marketplaceID']."' AND account ='$account'  ORDER BY id DESC LIMIT 1";
} else {
  $query = "SELECT id FROM ebays WHERE user_id = $userID AND marketplace_id = '".$input['marketplaceID']."' ORDER BY id DESC LIMIT 1";
}
      
     
      //if sme get sme id
      if ($input['smeornot']) {
  
        if ($this->makeCurl("getsme", "&reg_no=".$input['reg_no'])) {
          $smeID = $this->makeCurl("getsme", "&reg_no=".$input['reg_no'])[0]->id;
        }

      }
      $data = DB::select($query);


      \App\Ebay::query()
      ->where('id', $data[0]->id)
        ->update([
        'axis_shop_id'=> $input['shop'],
        'axis_shop_name'=> $input['shopUserName'],
        'axis_user_id'=> $input['shopUserID'],
        'is_sme'=> $input['smeornot'],
        'sme_id'=> $smeID,
        'registration_no'=> $input['reg_no']

       ]);

        if (!\App\Setup::where(['user_id'=> $this->userID, 'marketplace_id'=>$marketplace])->exists()) {
    
          //mark setup complete
          $ebay = new \App\Setup;
          $ebay->user_id = $userID;
          $ebay->marketplace_id = $marketplace;

          $ebay->save();
        }


      //update session, reload store data
      $control = new control();
      $control->storeAccess(0, 1);


      return true;
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
            $limit = [];
            $active = [];
            $awaiting = [];

            foreach ($accounts as $k => $account) {
              foreach ($account as $key => $value) {

                $store[] = $value['account'];
                $limit[$value['account']] = $this->getAvailableSellingLimit($value['account']);
                $active[$value['account']] = $this->getActiveListing($value['account']);
                $awaiting[$value['account']] = $this->getAwaitingShipment($value['account']);

              }
            }

        return view('ebay.home', ['launchpacks'=>$launchpacks, 'menu'=>$this->menu(),'stores'=>$this->stores(), 'role'=>$role, 'store'=>$store, 'limit'=>$limit, 'active'=>$active, 'awaiting'=>$awaiting]);
    }
    public function refresh()
    {
    session_destroy();
    session_start();
     return redirect()->back()->with('success', 'Page Refreshed');
    }

    public function shipNow(Request $request)
    {

$input = $request->all();
unset($input['_token']);

$list = [];

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


    private function getAvailableSellingLimit($store)
    {
      if (isset($store)) {
        $accounts = $this->token($store);

      } 

      $EntriesPerPage = 10;
      $pagenumber = 1;
      $account = '';
     
      $orders = [];
      $sellings = [];
      $shipments = [];
      $myLaunches = [];

      $dateTime = new \DateTime();

      $endDate = date('Y-m-d');
      // $dateTime->modify('-31 day');
      $startDate = date('Y-m-01');
      $userID = Auth::id();

        if (!isset($_SESSION['selling_limit'][$accounts['account']][$userID])) {

               $sellings = $this->fireXmlApi('GetMyeBaySelling', 
              ['SellingSummary'=>['Include'=>'true'], 
              'ActiveList'=>['Include'=>'false'], 
              'UnsoldList'=>['Include'=>'false'],
              'SoldList'=>['Include'=>'false',
              'Pagination'=>[
                              'EntriesPerPage'=>1,
                              'PageNumber'=>1
                            ]],'DetailLevel'=>'ReturnAll'], 1131, true, $accounts['authnauth_token']);

               if (isset($sellings['Summary']['AmountLimitRemaining'])) {
                 
                   $output = $sellings['Summary']['AmountLimitRemaining'];
               } else {
                $output =  '-';
               }

               $_SESSION['selling_limit'][$accounts['account']] = array($userID=> $output);
              
      }
   
      return $_SESSION['selling_limit'][$accounts['account']][$userID];

    }


     private function getActiveListing($store)
    {
      if (isset($store)) {
        $accounts = $this->token($store);

      } 

      $EntriesPerPage = 10;
      $pagenumber = 1;
      $account = '';
     
      $orders = [];
      $sellings = [];
      $shipments = [];
      $myLaunches = [];

      $dateTime = new \DateTime();

      $endDate = date('Y-m-d');
      // $dateTime->modify('-31 day');
      $startDate = date('Y-m-01');
      $userID = Auth::id();

        if (!isset($_SESSION['active_listing'][$accounts['account']][$userID])) {

              $sellings[$accounts['account']][] = $this->fireXmlApi('GetMyeBaySelling', 
            ['SellingSummary'=>['Include'=>'false'], 
            'ActiveList'=>['Include'=>'true'], 
            'UnsoldList'=>['Include'=>'false'],
            'SoldList'=>['Include'=>'false',
            'Pagination'=>[
                            'EntriesPerPage'=>$EntriesPerPage,
                            'PageNumber'=>$pagenumber
                          ]],'DetailLevel'=>'ReturnAll'], 1131, true, $accounts['authnauth_token']);


               if (isset($sellings[$accounts['account']][0]['ActiveList'])) {
                $output = $sellings[$accounts['account']][0]['ActiveList']['PaginationResult']['TotalNumberOfEntries'];
               } else {
                $output = 0;
               }
            
               $_SESSION['active_listing'][$accounts['account']] = array($userID=>$output);
      }

      return $_SESSION['active_listing'][$accounts['account']][$userID];

    }


     private function getAwaitingShipment($store)
    {
      if (isset($store)) {
        $accounts = $this->token($store);

      } 

      $EntriesPerPage = 10;
      $pagenumber = 1;
      $account = '';
     
      $orders = [];
      $sellings = [];
      $shipments = [];
      $myLaunches = [];

      $dateTime = new \DateTime();

      $endDate = date('Y-m-d');
      // $dateTime->modify('-31 day');
      $startDate = date('Y-m-01');
      $userID = Auth::id();

        if (!isset($_SESSION['pending_shipment'][$accounts['account']][$userID])) {

              $shipments[$accounts['account']][] = $this->fireXmlApi('GetMyeBaySelling', 
            ['SellingSummary'=>['Include'=>'false'], 
            'ActiveList'=>['Include'=>'false'], 
            'UnsoldList'=>['Include'=>'false'],
            'SoldList'=>['Include'=>'true',
            'Pagination'=>[
                            'EntriesPerPage'=>$EntriesPerPage,
                            'PageNumber'=>$pagenumber
                          ]],'DetailLevel'=>'ReturnAll'], 1131, true, $accounts['authnauth_token']);

                         $selling = array_column($shipments[$accounts['account']], 'SoldList');

                        $entries = (sizeof($selling) > 0 ? $selling[0]['PaginationResult']['TotalNumberOfEntries'] :0);
                        $awaitingShipmentCount = 0;

                             if ($entries > 0) {

                              $sold = $selling;

                             
                              foreach ($sold as $key => $value) {

                                if (isset($value['OrderTransaction'])) {
                           
                                if (is_array($value['OrderTransaction'])) {
                                  foreach ($value['OrderTransaction'] as $k => $transac) {
                                   
                              if (!isset($transac['Transaction']['ShippedTime'])) {
                                
                                $awaitingShipmentCount++;
                              }
                            }
                          }
                        }
                      }
                    }


            
               $_SESSION['pending_shipment'][$accounts['account']] = array($userID=>$awaitingShipmentCount);
      }

      return $_SESSION['pending_shipment'][$accounts['account']][$userID];

    }



    public function index2(Request $request)
    {

      $input = $request->all();
      if (isset($input['store'])) {
        $accounts = $this->token($input['store']);

      } else {
        $accounts = $this->token();   
      }

      $EntriesPerPage = 10;
      $pagenumber = 1;
      $account = '';
     
      $orders = [];
      $sellings = [];
      $shipments = [];
      $myLaunches = [];

      $dateTime = new \DateTime();

      $endDate = date('Y-m-d');
      // $dateTime->modify('-31 day');
      $startDate = date('Y-m-01');
      $userID = Auth::id();

        if (!isset($_SESSION['selling_limit'][$accounts['account']][$userID])) {

               $sellings[$accounts['account']][] = $this->fireXmlApi('GetMyeBaySelling', 
              ['SellingSummary'=>['Include'=>'true'], 
              'ActiveList'=>['Include'=>'false'], 
              'UnsoldList'=>['Include'=>'false'],
              'SoldList'=>['Include'=>'false',
              'Pagination'=>[
                              'EntriesPerPage'=>1,
                              'PageNumber'=>1
                            ]],'DetailLevel'=>'ReturnAll'], 1131, true, $accounts['authnauth_token']);

               
               $_SESSION['selling_limit'][$accounts['account']] = array($userID=>$sellings);

                //process orders
      $orderTotal = [];

      }


      // foreach ($orders as $key => $order) {

      //   $totalAmount = 0;
      //   $totalEntries = 0;
      //   // if (isset($order['OrderArray'])) {
      //     foreach ($order as $k => $v) {

      //       if (isset($v['PaginationResult'])) {

         
      //       $totalEntries = $v['PaginationResult']['TotalNumberOfEntries'];
      //       if ($totalEntries <= 1) {
      //        foreach ($v['OrderArray'] as $k4 => $v4) {
      //          if ($v4['OrderStatus'] == 'Completed') {

      //                   $totalAmount += $v4['AmountPaid'];
                       
      //                   $orderTotal[$key]['totalAmount'] = $totalAmount;
      //                   $orderTotal[$key]['totalEntries'] = $totalEntries;


      //                 }//order status ends
      //           }
      //       } else {
      //           if (isset($v['OrderArray'])) {
      //             foreach ($v['OrderArray'] as $k2 => $v2) {
      //              foreach ($v2 as $k3 => $v3) {
      //               if (isset($v3['OrderStatus'])) {
      //                 if ($v3['OrderStatus'] == 'Completed') {

      //                   $totalAmount += $v3['AmountPaid'];
                       
      //                   $orderTotal[$key]['totalAmount'] = $totalAmount;
      //                   $orderTotal[$key]['totalEntries'] = $totalEntries;


      //                 }//order status ends
      //               }
                    
      //              }
      //             }
      //           } 
      //       }
      //          }
      //     }
      //   // }
       
      // }
      

        // SendOrderEmail::dispatchNow($sellings, $orderTotal, Auth::id());
      return $_SESSION['selling_limit'][$accounts['account']][$userID];
      // echo json_encode($sellings);
      // exit;
    }


    public function index3(Request $request)
    {
      $input = $request->all();

       if (isset($input['store'])) {
        $accounts = $this->token($input['store']);

      } else {
        $accounts = $this->token();   
      }


      $EntriesPerPage = 10;
      $pagenumber = 1;
      $account = '';
     
      $orders = [];
      $sellings = [];
      $shipments = [];
      $myLaunches = [];

      $dateTime = new \DateTime();

      $endDate = date('Y-m-d');
      // $dateTime->modify('-31 day');
      $startDate = date('Y-m-01');

      $userID = Auth::id();

      if (!isset($_SESSION['order_total'][$accounts['account']][$userID])) {

      
 $orders[$accounts['account']][] = $this->fireXmlApi('GetOrders', 
                  ['CreateTimeFrom'=>$startDate, 
                  'CreateTimeTo'=>$endDate, 
                  'Pagination'=>[
                                  'EntriesPerPage'=>$EntriesPerPage,
                                  'PageNumber'=>$pagenumber
                                ]], 1131, true, $accounts['authnauth_token']);



        //process orders
      $orderTotal = [];
      foreach ($orders as $key => $order) {

        $totalAmount = 0;
        $totalEntries = 0;
        // if (isset($order['OrderArray'])) {
          foreach ($order as $k => $v) {

            if (isset($v['PaginationResult'])) {

         
            $totalEntries = $v['PaginationResult']['TotalNumberOfEntries'];
            if ($totalEntries <= 1) {
             foreach ($v['OrderArray'] as $k4 => $v4) {
               if ($v4['OrderStatus'] == 'Completed') {

                        $totalAmount += $v4['AmountPaid'];
                       
                        $orderTotal[$key]['totalAmount'] = $totalAmount;
                        $orderTotal[$key]['totalEntries'] = $totalEntries;


                      }//order status ends
                }
            } else {
                if (isset($v['OrderArray'])) {
                  foreach ($v['OrderArray'] as $k2 => $v2) {
                   foreach ($v2 as $k3 => $v3) {
                    if (isset($v3['OrderStatus'])) {
                      if ($v3['OrderStatus'] == 'Completed') {

                        $totalAmount += $v3['AmountPaid'];
                       
                        $orderTotal[$key]['totalAmount'] = $totalAmount;
                        $orderTotal[$key]['totalEntries'] = $totalEntries;


                      }//order status ends
                    }
                    
                   }
                  }
                } 
            }
               }
          }
        // }
       
      }

      $_SESSION['order_total'][$accounts['account']] = array($userID=>$orderTotal);

      }

        // DashboardOrder::dispatchNow($orderTotal, Auth::id());
      // return $_SESSION['order_total'][$accounts['account']][$userID];
      return $_SESSION['order_total'];

       
    }

    public function index4(Request $request)
    {
      $input = $request->all();

      if (isset($input['store'])) {
        $accounts = $this->token($input['store']);

      } else {
        $accounts = $this->token();   
      }

      $awaitingShipment[$accounts['account']] = [];

      $EntriesPerPage = 10;
      $pagenumber = 1;
      $account = '';
     
      $orders = [];
      $sellings = [];
      $shipments = [];
      $myLaunches = [];

      $dateTime = new \DateTime();

      $endDate = date('Y-m-d');
      // $dateTime->modify('-31 day');
      $startDate = date('Y-m-01');

      $userID = Auth::id();
      if (!isset($_SESSION['active_listing'][$accounts['account']][$userID])) {
 //active listing
          $sellings[$accounts['account']][] = $this->fireXmlApi('GetMyeBaySelling', 
            ['SellingSummary'=>['Include'=>'false'], 
            'ActiveList'=>['Include'=>'true'], 
            'UnsoldList'=>['Include'=>'false'],
            'SoldList'=>['Include'=>'false',
            'Pagination'=>[
                            'EntriesPerPage'=>$EntriesPerPage,
                            'PageNumber'=>$pagenumber
                          ]],'DetailLevel'=>'ReturnAll'], 1131, true, $accounts['authnauth_token']);



          //pending shipment
          $shipments[$accounts['account']][] = $this->fireXmlApi('GetMyeBaySelling', 
            ['SellingSummary'=>['Include'=>'false'], 
            'ActiveList'=>['Include'=>'false'], 
            'UnsoldList'=>['Include'=>'false'],
            'SoldList'=>['Include'=>'true',
            'Pagination'=>[
                            'EntriesPerPage'=>$EntriesPerPage,
                            'PageNumber'=>$pagenumber
                          ]],'DetailLevel'=>'ReturnAll'], 1131, true, $accounts['authnauth_token']);


          $selling = array_column($shipments[$accounts['account']], 'SoldList');

          $entries = (sizeof($selling) > 0 ? $selling[0]['PaginationResult']['TotalNumberOfEntries'] :0);
          $awaitingShipmentCount = 0;

               if ($entries > 0) {

                $sold = $selling;

               
                foreach ($sold as $key => $value) {

                  if (isset($value['OrderTransaction'])) {
             
                  if (is_array($value['OrderTransaction'])) {
                    foreach ($value['OrderTransaction'] as $k => $transac) {
                     
                if (!isset($transac['Transaction']['ShippedTime'])) {
                  
                  $awaitingShipmentCount++;
                }
              }
            }
          }
        }
      }

        $awaitingShipment[$accounts['account']] = $awaitingShipmentCount;
         
        $activeListing = [];

     foreach ($sellings as $key => $value) {
      foreach ($value as $k => $v) {
        if (isset($v['ActiveList'])) {
          $activeListing[$key] = $v['ActiveList']['PaginationResult']['TotalNumberOfEntries'];
        }
       
       }
      }
     
     $data = array(
        'shipment'=>$awaitingShipment,
        'listing'=>$activeListing
      );

     $_SESSION['active_listing'][$accounts['account']] = array($userID=>$data);
    }

      

        // DashboardListing::dispatchNow($data, Auth::id());
      return $_SESSION['active_listing'][$accounts['account']][$userID];

       
    }


    public function api() {

      $accounts = $this->token();
            $sellings = [];

            foreach ($accounts as $k => $account) {
              foreach ($account as $key => $value) {

                // active listing
                $sellings[$value['account']][] = $this->fireXmlApi('GetMyeBaySelling', 
                  ['SellingSummary'=>['Include'=>'true'], 
                  'ActiveList'=>['Include'=>'false'], 
                  'UnsoldList'=>['Include'=>'false'],
                  'SoldList'=>['Include'=>'false',
                  'Pagination'=>[
                                  'EntriesPerPage'=>1,
                                  'PageNumber'=>1
                                ]],'DetailLevel'=>'ReturnAll'], 1131, true, $value['authnauth_token']);
                
                SendOrderEmail::dispatch($sellings);

                Log::info('Dispatched summary');

              }
            }

        
        return 'Dispatched order';

    }


    public function sortSelection(Request $request)
    {
        $input = $request->all();

        if (str_contains($input['selection'], '-')) { 
            $date = explode('-', $input['selection']);
            $month = $date[0];
            $year = $date[1];
            $launchpacks = DB::table('launchpacks as l')
                ->leftJoin('launchpack_listings as ll', 'll.id', '=', 'l.id')
                ->select('l.id', 'l.launch_name', 'l.sme_id', 'l.launch_date', 'll.package_id', 'll.template')
                ->whereMonth('l.launch_date', $month)
                ->get();
            $launchpacks = collect($launchpacks->toArray())->all();

        }
        
        ob_start();
        if (isset($launchpacks)) {

            foreach ($launchpacks as $key => $launchpack) {
                ?>
            <tr>
                <td class="text-bold-500">
                    <div class="checkbox">
                        <input type="checkbox" class="checkbox-input" id="checkbox1">
                        <label for="checkbox1"></label>
                    </div>
                </td>
                <td>
                    <?php 
                    echo $launchpack->launch_name;
                    ?>
                        
                    </td>
                
                
                <td><?php 
                $date=date_create($launchpack->launch_date);
                    echo date_format($date, "jS M y h:i a");
                ?></td>
                <td>$54.90</td>
                
            </tr>
                <?php
            }
        }

        $html = ob_get_contents();
        ob_end_clean();
        
        echo json_encode($html);
        exit;
        
    }

    public function searchSelection(Request $request)
    {
        $input = $request->all();
        $launchpacks = DB::table('launchpacks as l')
            ->leftJoin('launchpack_listings as ll', 'll.id', '=', 'l.id')
            ->select('l.id', 'l.launch_name', 'l.sme_id', 'l.launch_date', 'll.package_id', 'll.template')
            ->where('l.launch_name', 'like', '%' . $input['search'] . '%')
            ->get();
            $launchpacks = collect($launchpacks->toArray())->all();
                    ob_start();
        if (isset($launchpacks)) {

            foreach ($launchpacks as $key => $launchpack) {
                ?>
            <tr>
                <td class="text-bold-500">
                    <div class="checkbox">
                        <input type="checkbox" class="checkbox-input" id="checkbox1">
                        <label for="checkbox1"></label>
                    </div>
                </td>
                <td>
                    <?php 
                    echo $launchpack->launch_name;
                    ?>
                        
                    </td>
                
                
                <td><?php 
                $date=date_create($launchpack->launch_date);
                    echo date_format($date, "jS M y h:i a");
                ?></td>
                <td>$54.90</td>
                
            </tr>
                <?php
            }
        }

        $html = ob_get_contents();
        ob_end_clean();
        
        echo json_encode($html);
        exit;

    }

    public function bulkUploadForm(Request $request)
    {

    	return view('bulkupload', ['menu'=>$this->menu(), 'sme'=>$this->makeCurl("sme"), 'stores'=>$this->stores()]);
    }

    public function bulkUploadListing(Request $request)
    {
    
    $input = $request->all();
  
    $filePath = "../bulkupload/";
    $path = public_path($filePath);
    if(!\File::isDirectory($path)){
        \File::makeDirectory($path, 0777, true, true);
    }

 
    //upload the spreadsheet
   if ($request->file('spreadsheet') != null) {

        //upload picture
        // $request->validate([
        //     'spreadsheet' => 'required|mimes:xlsx,svg|max:20048',
        // ]);

        $file = $request->file('spreadsheet')->getClientOriginalName();


        $filename = pathinfo($file, PATHINFO_FILENAME);
        $extension = pathinfo($file, PATHINFO_EXTENSION);

        $spreadsheetName = time().$filename.'.'.$request->spreadsheet->extension();  

        $request->spreadsheet->move(public_path($filePath), $spreadsheetName);
    }


     $fileName = $spreadsheetName;

     $filepath = $filePath.$spreadsheetName;

     $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filepath);
     $spreadsheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
     //edit start from here
     $element_excel = array();


     $itemSpecs = [];

      
          //remove titles
        $spreadsheet = array_values($spreadsheet);
        unset($spreadsheet[0]);
 
    $sku = [];
    //remove unwanted rows
    unset($spreadsheet[0]);
    unset($spreadsheet[1]);
    unset($spreadsheet[2]);
    unset($spreadsheet[3]);



         foreach ($spreadsheet as $key => $value) {
            $sku[] = $value['A'];
            if ($value['A'] != null) {

            
              //check if package
              if ((substr($value['A'], 0, strlen('PKG')) === 'PKG') === true) { 
           
             $this->makeCurl("updatepackagemanufactorercode", "&manufacturer=".$value['B'].'&ebx='.$value['A']);


              } else {
               
             $this->makeCurl("updateproductmanufactorercode", "&manufacturer=".$value['B'].'&ebx='.$value['A']);

              }

             }
         }

    

    return redirect('/ebay/dashboard');
    }

    public function bulkUploadListing_old2(Request $request)
    {
    
    $input = $request->all();
  
    $filePath = "../bulkupload/";
    $path = public_path($filePath);
    if(!\File::isDirectory($path)){
        \File::makeDirectory($path, 0777, true, true);
    }

 
    //upload the spreadsheet
   if ($request->file('spreadsheet') != null) {

        //upload picture
        // $request->validate([
        //     'spreadsheet' => 'required|mimes:xlsx,svg|max:20048',
        // ]);

        $file = $request->file('spreadsheet')->getClientOriginalName();


        $filename = pathinfo($file, PATHINFO_FILENAME);
        $extension = pathinfo($file, PATHINFO_EXTENSION);

        $spreadsheetName = time().$filename.'.'.$request->spreadsheet->extension();  

        $request->spreadsheet->move(public_path($filePath), $spreadsheetName);
    }


     $fileName = $spreadsheetName;

     $filepath = $filePath.$spreadsheetName;

     $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filepath);
     $spreadsheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
     //edit start from here
     $element_excel = array();


     $itemSpecs = [];

      
          //remove titles
        $spreadsheet = array_values($spreadsheet);
        unset($spreadsheet[0]);
 
    $sku = [];
         foreach ($spreadsheet as $key => $value) {
            $sku[] = $value['B'];
             $this->makeCurl("updatemanufactorercode", "&manufacturer=".$value['A'].'&ebx='.$value['B']);
         }

    
exit;
    return redirect('/ebay/dashboard');
    }
    
    public function bulkUploadListing2(Request $request)
    {

    $input = $request->all();
    $filePath = "../bulkupload/";
    $path = public_path($filePath);
    if(!\File::isDirectory($path)){
        \File::makeDirectory($path, 0777, true, true);
    }

    //upload the spreadsheet
   if ($request->file('spreadsheet') != null) {

        //upload picture
        $request->validate([
            'spreadsheet' => 'required|mimes:xlsx,svg|max:2048',
        ]);

   
        $file = $request->file('spreadsheet')->getClientOriginalName();

        $filename = pathinfo($file, PATHINFO_FILENAME);
        $extension = pathinfo($file, PATHINFO_EXTENSION);

        $spreadsheetName = time().$filename.'.'.$request->spreadsheet->extension();  

        $request->spreadsheet->move(public_path($filePath), $spreadsheetName);
    }


     //load the file
     $smeID = $input['sme'];
     $launchpackTitle = $input['launchpackTitle'];

     $fileName = $spreadsheetName;

     $filepath = $filePath.$spreadsheetName;

     $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filepath);
     $spreadsheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
     //edit start from here
     $element_excel = array();
     //$category = array('Category ID','ApplicationData','Title','StartPrice','ebayCatID','Description','PictureURL');

      $keys = array(
        '*Category ID',
        '*StartPrice',
        '*Description',
        '*Package SKU',
        '*Title',
        '*PicURL'
       );

      $keys2 = array(
        'Category ID',
        'StartPrice',
        'Description',
        'Package SKU',
        'Title',
        'PicURL'
       );

      //get required fields
     foreach ($spreadsheet as $key => $value) {

      foreach($value as $k=>$v)
      {
        if (in_array($v, $keys)) {
          $v = str_replace('*', '', $v);
          $v = ($v=='Category ID' ? 'CategoryID' : $v);
          $v = ($v=='Package SKU' ? 'ApplicationData' : $v);
          $v = ($v=='PicURL' ? 'PictureURL' : $v);


          

          $element_excel[$v] = $k;
          
        }

        if (in_array($v, $keys2)) {
          $v = ($v=='Category ID' ? 'CategoryID' : $v);
          $v = ($v=='Package SKU' ? 'ApplicationData' : $v);
          $v = ($v=='PicURL' ? 'PictureURL' : $v);



          $element_excel[$v] = $k;

        }
      }
     };

     //get item specifics

     //reindex and fetch the 1st row
     $firstRow = array_values($spreadsheet)[0];
     $specs = [];
  
$i=0;
   foreach ($firstRow as $key => $row) {
       if (strpos($row, 'C:') !== false) {
        
        $specs[$i] = ['col'=>$key, 'spec'=>str_replace('C:', '', $row)];
        $i++;
       }
   }


     $itemSpecs = [];

      
          //remove titles
     $spreadsheet = array_values($spreadsheet);
         unset($spreadsheet[0]);

         foreach ($spreadsheet as $key => $value) {
          if ($value[$element_excel['ApplicationData']] != '') {
 

              foreach ($specs as $k => $v) {
           
                 $itemSpecs[$value[$element_excel['ApplicationData']]]['ItemSpecifics']['NameValueList'][$k] =['Name'=>$v['spec'], 'Value'=>$value[$v['col']]];
              }

              $itemSpecs[$value[$element_excel['ApplicationData']]]['CategoryID'] = $value[$element_excel['CategoryID']];
              $itemSpecs[$value[$element_excel['ApplicationData']]]['ApplicationData'] = $value[$element_excel['ApplicationData']];
              $itemSpecs[$value[$element_excel['ApplicationData']]]['Title'] = $value[$element_excel['Title']];
              $itemSpecs[$value[$element_excel['ApplicationData']]]['StartPrice'] = $value[$element_excel['StartPrice']];
              $itemSpecs[$value[$element_excel['ApplicationData']]]['ebayCatID'] = $value[$element_excel['CategoryID']];
              $itemSpecs[$value[$element_excel['ApplicationData']]]['Description'] = htmlentities('<![CDATA['.$value[$element_excel['Description']].']]>');
              $itemSpecs[$value[$element_excel['ApplicationData']]]['PictureURL'] = str_replace('|', ',', $value[$element_excel['PictureURL']]);



              // $applicationID = $this->makeCurl("getpackageid", "&sku=".$value[$element_excel['ApplicationData']])[0]->id;
              
              

              // $pricelist = $this->makeCurl("pricelist", "&package_id=$applicationID&weight=".$value[$input['weight']]."&totalCost=0&sellingPrice=".$value[$input['StartPrice']]."&netProfit=0&listTitle=".urlencode($value[$input['Title']])."&sme_id=$smeID");
             
             // }

          }
      }


      $listingpool = array();

     foreach ($itemSpecs as $key => $spec) {

     $template = $this->VerifyAddItemBody();

       $applicationID = $this->makeCurl("getpackageid", "&sku=".trim($spec['ApplicationData']))[0]->id;
 
       $template->Item->ItemSpecifics = $spec['ItemSpecifics'];
       $template->Item->ApplicationData = $applicationID;
       // $template->Item->MessageID = $applicationID;
       $template->Item->Title = $spec['Title'];
       $template->Item->StartPrice['StartPrice'] = $spec['StartPrice'];
       $template->Item->PrimaryCategory->CategoryID = $spec['ebayCatID'];
       $template->Item->Description = $spec['Description'];
       $template->Item->PictureDetails->PictureURL = $spec['PictureURL'];
       
       $listingpool[] = $template;
     }

      $ebay = new \App\Launchpack;
      $ebay->launch_name = $launchpackTitle;
      $ebay->sme_id = $smeID;
      $ebay->updated_by = Auth::id();

      // $ebay->launch_date = '2021-01-29 19:26:00';

      $ebay->save();
      $launcpackID = $ebay->id;

    foreach ($listingpool as $key => $pool) {

          $ebay = new \App\LaunchpackListing;
          $ebay->launchpack_id = $launcpackID;
          $ebay->package_id = $pool->Item->ApplicationData;
          $ebay->template = json_encode($pool->Item);

          $ebay->save();
    }

    return redirect('/ebay/dashboard');
    }

    public function Minify_Html($Html) 
    {
    $Search = array(
    '/(\n|^)(\x20+|\t)/',
    '/(\n|^)\/\/(.*?)(\n|$)/',
    '/\n/',
    '/\<\!--.*?-->/',
    '/(\x20+|\t)/', # Delete multispace (Without \n)
    '/\>\s+\</', # strip whitespaces between tags
    '/(\"|\')\s+\>/', # strip whitespaces between quotation ("') and end tags
    '/=\s+(\"|\')/'); # strip whitespaces between = "'

   $Replace = array(
    "\n",
    "\n",
    " ",
    "",
    " ",
    "><",
    "$1>",
    "=$1");

$Html = preg_replace($Search,$Replace,$Html);
return $Html;
  }

    public function bulkUploadListingOld(Request $request)
    {
    $input = $request->all();

    $filePath = "../bulkupload/";
    $path = public_path($filePath);
    if(!\File::isDirectory($path)){
        \File::makeDirectory($path, 0777, true, true);
    }
    //upload the spreadsheet
   if ($request->file('spreadsheet') != null) {

        //upload picture
        $request->validate([
            'spreadsheet' => 'required|mimes:xlsx,svg|max:2048',
        ]);

        $file = $request->file('spreadsheet')->getClientOriginalName();

        $filename = pathinfo($file, PATHINFO_FILENAME);
        $extension = pathinfo($file, PATHINFO_EXTENSION);

        $spreadsheetName = time().$filename.'.'.$request->spreadsheet->extension();  

        $request->spreadsheet->move(public_path($filePath), $spreadsheetName);
    }

     //load the file
     $smeID = $input['sme'];
     $launchpackTitle = $input['launchpackTitle'];

     $fileName = $spreadsheetName;

     $filepath = $filePath.$spreadsheetName;

     $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filepath);
     $spreadsheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

     $itemSpecs = [];

     $packageSKU = array_column($spreadsheet, $input['ApplicationData']);
     
     unset($packageSKU[0]);
      
          //remove titles
         unset($spreadsheet[0]);
         foreach ($spreadsheet as $key => $value) {
          if ($value[$input['ApplicationData']] != '') {

             if ($value[$input['ItemSpecifics'][0]]!= array_column($spreadsheet, $input['ItemSpecifics'][0])[0]) {
           		
           		$specs = $input['ItemSpecifics'];
           		foreach ($specs as $k => $v) {
           			 $itemSpecs[$value[$input['ApplicationData']]]['ItemSpecifics']['NameValueList'][$k] =['Name'=>trim(explode('C:', array_column($spreadsheet, $v)[0])[1]), 'Value'=>$value[$v]];
           		}
              
              $itemSpecs[$value[$input['ApplicationData']]]['CategoryID'] = $value[$input['CategoryID']];
              $itemSpecs[$value[$input['ApplicationData']]]['ApplicationData'] = $value[$input['ApplicationData']];
              $itemSpecs[$value[$input['ApplicationData']]]['Title'] = $value[$input['Title']];
              $itemSpecs[$value[$input['ApplicationData']]]['StartPrice'] = $value[$input['StartPrice']];
              $itemSpecs[$value[$input['ApplicationData']]]['ebayCatID'] = $value[$input['ebayCatID']];
              $itemSpecs[$value[$input['ApplicationData']]]['Description'] = $value[$input['Description']];
              $itemSpecs[$value[$input['ApplicationData']]]['PictureURL'] = str_replace('|', ',', $value[$input['PictureURL']]);



              $applicationID = $this->makeCurl("getpackageid", "&sku=".$value[$input['ApplicationData']])[0]->id;
              
              

              // $pricelist = $this->makeCurl("pricelist", "&package_id=$applicationID&weight=".$value[$input['weight']]."&totalCost=0&sellingPrice=".$value[$input['StartPrice']]."&netProfit=0&listTitle=".urlencode($value[$input['Title']])."&sme_id=$smeID");
             
             }

          }
      }


    
      $listingpool = array();

     foreach ($itemSpecs as $key => $spec) {

     $template = $this->VerifyAddItemBody();

       $applicationID = $this->makeCurl("getpackageid", "&sku=".$spec['ApplicationData'])[0]->id;
   
       $template->Item->ItemSpecifics = $spec['ItemSpecifics'];
       $template->Item->ApplicationData = $applicationID;
       // $template->Item->MessageID = $applicationID;
       $template->Item->Title = $spec['Title'];
       $template->Item->StartPrice['StartPrice'] = $spec['StartPrice'];
       $template->Item->PrimaryCategory->CategoryID = $spec['ebayCatID'];
       $template->Item->Description = $spec['Description'];
       $template->Item->PictureDetails->PictureURL = $spec['PictureURL'];
       
       $listingpool[] = $template;
     }

      $ebay = new \App\Launchpack;
      $ebay->launch_name = $launchpackTitle;
      $ebay->sme_id = $smeID;
      // $ebay->launch_date = '2021-01-29 19:26:00';

      $bay->updated_by = Auth::id();
      $ebay->save();
      $launcpackID = $ebay->id;

    foreach ($listingpool as $key => $pool) {

          $ebay = new \App\LaunchpackListing;
          $ebay->launchpack_id = $launcpackID;
          $ebay->package_id = $pool->Item->ApplicationData;
          $ebay->template = json_encode($pool->Item);

          $ebay->save();
    }

    return redirect('/ebay/dashboard');
    }

	public function updateLaunchpackStatus(Request $request)
	{
	    $id = $request->input('id');
	    $status = $request->input('status');

	    \App\Launchpack::query()
	    ->where('id', $id)
        ->update([
        'status'=> $status,
        'updated_by'=> Auth::id(),


        ]);
	            
	}

  public function accept()
  {

        $this->userID = Auth::id();

    
   
        if (isset($_GET['username'])) {
                //reset session every time new account added
              // @session_start();
              // session_destroy();
              // @session_start();


             \Session::put('ebayAccount', $_GET['username']);

             $status = $this->getEbayToken();

             if ($status) {
                 
                  return redirect('/ebay/setup/2');
             }
        }
   
 
        $account = \Session::get('ebayAccount');
        //for oath
        if (isset($_GET['code'])) {
            

        $refreshToken = \App\Ebay::where(['account'=>$account, 'user_id'=>$this->userID])
         ->pluck('oauth_refresh_token')
         ->first();

 
         //check expiry
        if (!$refreshToken) {
            
        $body = "grant_type=authorization_code&scope=https%253A%252F%252Fapi.ebay.com%252Foauth%252Fapi_scope%2520https%253A%252F%252Fapi.ebay.com%252Foauth%252Fapi_scope%252Fsell.marketing.readonly%2520https%253A%252F%252Fapi.ebay.com%252Foauth%252Fapi_scope%252Fsell.marketing%2520https%253A%252F%252Fapi.ebay.com%252Foauth%252Fapi_scope%252Fsell.inventory.readonly%2520https%253A%252F%252Fapi.ebay.com%252Foauth%252Fapi_scope%252Fsell.inventory%2520https%253A%252F%252Fapi.ebay.com%252Foauth%252Fapi_scope%252Fsell.account.readonly%2520https%253A%252F%252Fapi.ebay.com%252Foauth%252Fapi_scope%252Fsell.account%2520https%253A%252F%252Fapi.ebay.com%252Foauth%252Fapi_scope%252Fsell.fulfillment.readonly%2520https%253A%252F%252Fapi.ebay.com%252Foauth%252Fapi_scope%252Fsell.fulfillment%2520https%253A%252F%252Fapi.ebay.com%252Foauth%252Fapi_scope%252Fsell.analytics.readonly%2520https%253A%252F%252Fapi.ebay.com%252Foauth%252Fapi_scope%252Fsell.finances%2520https%253A%252F%252Fapi.ebay.com%252Foauth%252Fapi_scope%252Fsell.payment.dispute%2520https%253A%252F%252Fapi.ebay.com%252Foauth%252Fapi_scope%252Fcommerce.identity.readonly&redirect_uri=karen_nair-karennai-middle-chacvvjxx&code=".$_GET['code'];
        
      
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://api.ebay.com/identity/v1/oauth2/token");
       
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    "Authorization: Basic ".base64_encode('karennai-middlema-PRD-ec8ec878c-badd14db:PRD-c8ec878c259b-f82f-4889-9b86-2e2e'),
                    "Content-Type: application/x-www-form-urlencoded"
                ));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$body);
        
        $server_output = curl_exec($ch);
        
        curl_close($ch);
        
   
        $response = json_decode($server_output);
  
      if (isset($response->access_token)) {
        
        \App\Ebay::where(['user_id'=>$this->userID, 'account'=>$account])
              ->update([
                'oauth_token' => $response->access_token, 
                'oauth_token_expiry'=>$response->expires_in,
                'oauth_refresh_token' => $response->refresh_token, 
                'oauth_refresh_token_expiry'=>$response->refresh_token_expires_in,
                ]);
      
    
              //mark setup complete
           if (!\App\Setup::where(['user_id'=> $this->userID, 'marketplace_id'=>'ebay'])->exists()) {
    
          //mark setup complete
          $ebay = new \App\Setup;
          $ebay->user_id = $this->userID;
          $ebay->marketplace_id = 'ebay';
          $ebay->save();
        }
      }
  
              return redirect('/ebay/setup/3');
        } else {
            exit;
            //require user consent
            return Redirect::to('https://auth.ebay.com/oauth2/authorize?client_id=karennai-middlema-PRD-ec8ec878c-badd14db&response_type=code&redirect_uri=karen_nair-karennai-middle-chacvvjxx&scope=https://api.ebay.com/oauth/api_scope https://api.ebay.com/oauth/api_scope/sell.marketing.readonly https://api.ebay.com/oauth/api_scope/sell.marketing https://api.ebay.com/oauth/api_scope/sell.inventory.readonly https://api.ebay.com/oauth/api_scope/sell.inventory https://api.ebay.com/oauth/api_scope/sell.account.readonly https://api.ebay.com/oauth/api_scope/sell.account https://api.ebay.com/oauth/api_scope/sell.fulfillment.readonly https://api.ebay.com/oauth/api_scope/sell.fulfillment https://api.ebay.com/oauth/api_scope/sell.analytics.readonly https://api.ebay.com/oauth/api_scope/sell.finances https://api.ebay.com/oauth/api_scope/sell.payment.dispute https://api.ebay.com/oauth/api_scope/commerce.identity.readonly');
        }
        } 
         return redirect('/ebay/setup/3');
      
    }

	public function setupPage($firstTime)
	{

    return $this->setup($firstTime);

	}

public function setupPage2()
  {
    return $this->setupManual(Auth::id());

  }
  public function quicklaunch(Request $request)
  {

    $input = $request->all();
    $data = [];
    $stores = $input['store'];



    //selected launchpacks
    $launchpacks = DB::table('launchpacks as lp')
      ->join('launchpack_listings as lpl', 'lp.id', '=', 'lpl.launchpack_id')
      ->select('lp.id as launch_id', 'lpl.*')
      ->whereIn('lp.id',$input['id'])
      ->get();

    foreach ($stores as $store) {
      $data[$store]['tokens'] = $this->token($store, 1);
      $data[$store]['policy'] = $this->defaultPolicy($store);

    }
    
    $response = [];


foreach ($data as $key => $value) {

$userPreference = $this->fireXmlApi('GetUserPreferences',['ShowSellerPaymentPreferences'=>'true'], 1131, true, $value['tokens']['authnauth_token'], 0);

// $returnPolicy = $this->fireXmlApi('GetCategoryFeatures',['DetailLevel'=>'ReturnAll',
//   'FeatureID'=>'InternationalReturnsDurationValues'], 1131, true, $value['tokens']['authnauth_token'], 0);

// if (in_array('Days_30', $returnPolicy['SiteDefaults']['InternationalReturnsDurationValues']['InternationalReturnsDuration'])) {
// $returnPolicy = 'Days_30';
// } else {
//   $returnPolicy = $returnPolicy['SiteDefaults']['InternationalReturnsDurationValues']['InternationalReturnsDuration'][0];
// }

// $shippingPolicy = $this->fireXmlApi('GeteBayDetails',[
//   'SiteDetails'=>['Site'=>'US', 'SiteID'=>0],
//   'DetailName'=>'ShippingServiceDetails'], 1131, true, $value['tokens']['authnauth_token'], 0);

// $val = $shippingPolicy['ShippingServiceDetails'];
// echo "<pre>";
//  $i= 0;
       // foreach ($val as $kval => $kval2) {
       //  var_dump($kval2);
       // if (isset($kval2['InternationalService'])) {

      
// if ($kval2['InternationalService'] == 'true') {
// //   if ($i<=5) {
       
//        var_dump($kval2['ShippingService']);
// //      }
// //       $i++;

//         }
        // }
       // }
// exit;
//ReturnPolicyEnabled
$count = 0;


 // foreach ($value['policy'] as $k => $v) {

      foreach ($launchpacks as $k2 => $launchpack) {


    $obj = new \stdClass();

      $template = json_decode($launchpack->template);
      $template->PayPalEmailAddress = new \stdClass();
      // $template->ReturnPolicy = new \stdClass();

      $template->PayPalEmailAddress = $userPreference['SellerPaymentPreferences']['DefaultPayPalEmailAddress'];
      // $template->ReturnPolicy->InternationalReturnsWithinOption = $returnPolicy;

      // $template->ReturnPolicy->InternationalShippingCostPaidByOption = 'Seller';
      // $template->ReturnPolicy->ShippingCostPaidByOption = 'Buyer';

      // $template->ShippingDetails = new \stdClass();
      //  $template->ShippingDetails->ShippingServiceOptions = new \stdClass();
      // //   $template->ShippingDetails->ShippingType = 'Flat';

      //  // $template->ShippingDetails = new \stdClass();
      // $template->ShippingDetails->GlobalShipping = 'true';

      //   $i= 0;
      //  foreach ($val as $kval => $kval2) {
      //  if (isset($kval2['InternationalService'])) {

      //  if ($kval2['InternationalService'] == 'true') {
      //   // if ($i<=5) {
       
      //   $template->ShippingDetails->ShippingServiceOptions->ShippingService[$i] = $kval2['ShippingService'];

        

      //   // }
      //   $i++;

      //   }
      // }
        
      // }

      


    
           $template->SellerProfiles->SellerPaymentProfile->PaymentProfileID = $value['policy']['payment']['id'];
           $template->SellerProfiles->SellerPaymentProfile->PaymentProfileName = $value['policy']['payment']['name'];
     
        $template->SellerProfiles->SellerReturnProfile->ReturnProfileID = $value['policy']['return']['id'];
        $template->SellerProfiles->SellerReturnProfile->ReturnProfileName = $value['policy']['return']['name'];
     
        $template->SellerProfiles->SellerShippingProfile->ShippingProfileID = $value['policy']['shipment']['id'];
        $template->SellerProfiles->SellerShippingProfile->ShippingProfileName = $value['policy']['shipment']['name'];
       

        $obj->Item = $template;


$response[$key][] = $this->fireXmlApi('VerifyAddItem', $obj, 1131, true, $value['tokens']['authnauth_token'], 0);

      
    }


        // echo $count."<br/>";
// $response[$key][] = $this->fireXmlApi('VerifyAddItem', $obj, 1131, true, $value['tokens']['authnauth_token'], 0);
// $count++;
 // }




}

  echo json_encode($response);
  exit;
  }

  public function getTotalCost(Request $request)
  {
    $weight = $request->input('weight');
    $productCost = $request->input('productCost');

      $shipping = $this->makeCurl("shippingrate", "&weight=$weight");
     
      $postageCharge = array_column($shipping, 'total_shipping_cost')[0];

      $config = $this->getConfig()[0];


      $postageWithFuelSurcharge = ($config['fuel_surcharge']*$postageCharge) /100;

      $shippingRate = $postageWithFuelSurcharge + $postageCharge;

      $totalProductCost = ($shippingRate + $productCost);

      return [
        'rm'=>round($totalProductCost, 2, PHP_ROUND_HALF_UP), 
        'usd'=>round($totalProductCost / $config['conversion_rate'], 2, PHP_ROUND_HALF_UP)];
    
    }

    public function getNetProfit(Request $request)
    {
      $sellingPrice = $request->input('sellingPrice');//must be in usd
      $totalCost = $request->input('totalCost');//in myr


      //at least 1 active calculator present
      if (sizeof($this->getConfig())> 0) {

      
      $config = $this->getConfig()[0];
      $totalFees = $this->getTotalFees($sellingPrice);

      $paypalFtFeesAgainstCurrency = $config['conversion_rate'] - (($config['conversion_rate'] * $config['foreign_currency_rate'])/100);

      $balance = $sellingPrice - $totalFees;

      $convertionTo = round($balance * $paypalFtFeesAgainstCurrency, 2, PHP_ROUND_HALF_UP);//rm

      $totalSelling = $sellingPrice * $config['conversion_rate'];//rm

      $netProfit = $convertionTo - $totalCost;//rm

      return round($netProfit, 2, PHP_ROUND_HALF_UP);
      }
      return 'No active Calculator Found';

    }


   public function quicklaunch2(Request $request)
   {
    $input = $request->all();
    $data = [];


    //selected launchpacks
    $launchpacks = DB::table('launchpacks as lp')
      ->join('launchpack_listings as lpl', 'lp.id', '=', 'lpl.launchpack_id')
      ->select('lp.id as launch_id', 'lpl.*')
      ->whereIn('lp.id',$input['id'])
      ->get();

    foreach ($input['store'] as $store) {
      $data[$store]['tokens'] = $this->token($store, 1);
      $data[$store]['policy'] = $this->defaultPolicy($store);

    }
    
    $response = [];


foreach ($data as $key => $value) {

$userPreference = $this->fireXmlApi('GetUserPreferences',['ShowSellerPaymentPreferences'=>'true'], 1131, true, $value['tokens']['authnauth_token'], 0);

$count = 0;

      foreach ($launchpacks as $k2 => $launchpack) {


    $obj = new \stdClass();

      $template = json_decode($launchpack->template);
      $template->PayPalEmailAddress = new \stdClass();

      $template->PayPalEmailAddress = $userPreference['SellerPaymentPreferences']['DefaultPayPalEmailAddress'];

           $template->SellerProfiles->SellerPaymentProfile->PaymentProfileID = $value['policy']['payment']['id'];
           $template->SellerProfiles->SellerPaymentProfile->PaymentProfileName = $value['policy']['payment']['name'];
     
        $template->SellerProfiles->SellerReturnProfile->ReturnProfileID = $value['policy']['return']['id'];
        $template->SellerProfiles->SellerReturnProfile->ReturnProfileName = $value['policy']['return']['name'];
     
        $template->SellerProfiles->SellerShippingProfile->ShippingProfileID = $value['policy']['shipment']['id'];
        $template->SellerProfiles->SellerShippingProfile->ShippingProfileName = $value['policy']['shipment']['name'];
       

        $obj->Item = $template;


$response[$key][] = $this->fireXmlApi('AddItem', $obj, 1131, true, $value['tokens']['authnauth_token'], 0);

      $ebay = new \App\MyLaunchpack;
      $ebay->account = $value['tokens']['account'];
      $ebay->listing_id = $launchpack->id;
      $ebay->package_id = $launchpack->package_id;

      $ebay->status = $response[$key][0]['Ack'];
      $ebay->user_id = Auth::id();
      $ebay->save();
    }


}

  echo json_encode($response);
  exit;
  }

   public function launchPacks($userID, $request)
  { 

   
    $single = 0;
    $listingID = 0;

    $launchpack = \App\Launchpack::get();
    $launchpack = collect($launchpack->toArray())->all();

    $output = [];
    foreach ($launchpack as $key => $pack) {
 
    if ($request->route('listingid') != null) {

     $clause = ['launchpack_id'=> $pack['id'], 'id'=>$request->route('listingid')];
     $listingID = $request->route('listingid');
   } else {
     ['launchpack_id'=> $pack['id']];
   }
      $listing = \App\LaunchpackListing::where($clause)->get();
      $listing = collect($listing->toArray())->all();
      $output[$pack['id']] = array(
        'pack'=>$pack,
        'listing'=>$listing);

    }


     $i = 0;
     $result = [];
     $hiddenFields = '';


     foreach ($output as $key => $value) {

      $result[$key]['pack'][] = $value['pack'];

      foreach ($value['listing'] as $k => $v) {
       
        $i++;
        $template = json_decode($v['template']);

        $xml = $this->xmlToHtmlArray(['Item'=>$template], $i, 1);



        $v['template'] = $xml;
        $result[$key]['template'][] = $xml;
        $result[$key]['pricelist'] = $this->makeCurl("getpricelistbypkgid", "&package_id=".$v['package_id']);
        $result[$key]['pricelist2'][$v['package_id']] = $this->makeCurl("getpricelistbypkgid", "&package_id=".$v['package_id']);


              $nonEditableFields = $template;
              // unset($nonEditableFields->PictureDetails);
              // unset($nonEditableFields->Title);
              // unset($nonEditableFields->StartPrice);
              // unset($nonEditableFields->Description);
              // unset($nonEditableFields->PrimaryCategory);
              unset($nonEditableFields->messageID);
              unset($nonEditableFields->PostalCode);


              // unset($nonEditableFields->ItemSpecifics);
              unset($nonEditableFields->SellerProfiles);

              $hiddenFields.= $this->xmlToHtml(['Item'=>$nonEditableFields], $i, 0);

      }

     }

     //limit

      $summary = [];
      $tokens = $this->token();
      $controller = new Controller();
    
    // foreach ($tokens as $k => $token) {
    //   foreach ($token as $key => $value) {
    //      $summary[$value['account']] = $this->fireXmlApi('GetMyeBaySelling', 
    //     ['SellingSummary'=>['Include'=>'true'],
    //      'ActiveList'=>['Sort'=>'TimeLeft',
    //                     'Pagination'=>['EntriesPerPage'=>0, 'PageNumber'=>1]]
    //       ], 1131, true, $value['authnauth_token']);
        
    //   }
     
    // }


      if ($listingID > 0) {

        return view('launchpack-listing', ['sme'=>$this->makeCurl("sme"), 'stores'=>$this->stores(), 'launchpack'=>$result, 'hiddenFields'=>$hiddenFields, 'single'=>$single, 'summary'=>$controller->summary(), 'policies'=>$this->policy()]);
      }
    return view('launchpack', ['sme'=>$this->makeCurl("sme"), 'stores'=>$this->stores(), 'launchpack'=>$result, 'hiddenFields'=>$hiddenFields, 'single'=>$single, 'summary'=>$controller->summary(), 'policies'=>$this->policy()]);

  }

  public function launchPack($userID, $request)
  { 
   
    $id = $request->route('id');
    if (isset($id)) {
      $single = 1;
    $launchpack = \App\Launchpack::where(['status'=> 1, 'id'=>$id])->get();

    } else {
      $single = 0;

    $launchpack = \App\Launchpack::where('status', 1)->get();

    }
    $launchpack = collect($launchpack->toArray())->all();

    $output = [];
    foreach ($launchpack as $key => $pack) {
 
      $listing = \App\LaunchpackListing::where('launchpack_id', $pack['id'])->get();
      $listing = collect($listing->toArray())->all();
      $output[$pack['id']] = array(
        'pack'=>$pack,
        'listing'=>$listing);

    }

     $i = 0;
     $result = [];
     $hiddenFields = '';

     foreach ($output as $key => $value) {

      $result[$key]['pack'][] = $value['pack'];

      foreach ($value['listing'] as $k => $v) {
       
        $i++;
        $template = json_decode($v['template']);

  
        $xml = $this->xmlToHtmlArray(['Item'=>$template], $i, 1);


        $v['template'] = $xml;
        $result[$key]['template'][] = $xml;
        $result[$key]['listing'][$v['package_id']] = $v['id'];

        $result[$key]['pricelist'] = $this->makeCurl("getpricelistbypkgid", "&package_id=".$v['package_id']);
        $result[$key]['pricelist2'][$v['package_id']] = $this->makeCurl("getpricelistbypkgid", "&package_id=".$v['package_id']);


              $nonEditableFields = $template;
              // unset($nonEditableFields->PictureDetails);
              // unset($nonEditableFields->Title);
              // unset($nonEditableFields->StartPrice);
              unset($nonEditableFields->Description);
              // unset($nonEditableFields->PrimaryCategory);
              unset($nonEditableFields->ItemSpecifics);
              unset($nonEditableFields->SellerProfiles);

              $hiddenFields.= $this->xmlToHtml(['Item'=>$nonEditableFields], $i, 0);

      }

     }

     //limit

      $summary = [];
      $tokens = $this->token();
      $controller = new Controller();
    
    // foreach ($tokens as $k => $token) {
    //   foreach ($token as $key => $value) {
    //      $summary[$value['account']] = $this->fireXmlApi('GetMyeBaySelling', 
    //     ['SellingSummary'=>['Include'=>'true'],
    //      'ActiveList'=>['Sort'=>'TimeLeft',
    //                     'Pagination'=>['EntriesPerPage'=>0, 'PageNumber'=>1]]
    //       ], 1131, true, $value['authnauth_token']);
        
    //   }
     
    // }
    $data = \App\Calculator::where('status', 1)->get();
    $calculator = collect($data->toArray());

    return view('launchpack', ['sme'=>$this->makeCurl("sme"), 'stores'=>$this->stores(), 'launchpack'=>$result, 'hiddenFields'=>$hiddenFields, 'single'=>$single, 'summary'=>$controller->summary(), 'policies'=>$this->policy(), 'calculator'=>$calculator, 'menu'=>$this->menu()]);

  }

  private function str_replace_first($from, $to, $content)
    {
          $from = '/'.preg_quote($from, '/').'/';

          return preg_replace($from, $to, $content, 1);
    }


   private function handleListingLoopVerify($listings, $policies)
  {

    $xml = '';
    $response = [];

    foreach ($listings as $key => $listing) {
      $xml .= $this->processLoop($key, $listing, $policies);
    }

    //business policies
      if ($policies) {
       
        $xml.= htmlentities('<SellerProfiles>
            <SellerPaymentProfile>
              <PaymentProfileID>'.$policies->payment[0].'</PaymentProfileID>
              <PaymentProfileName>'.$policies->payment[1].'</PaymentProfileName>
           </SellerPaymentProfile>
            <SellerReturnProfile>
              <ReturnProfileID>'.$policies->return[0].'</ReturnProfileID>
              <ReturnProfileName>'.$policies->return[1].'</ReturnProfileName>
           </SellerReturnProfile>
            <SellerShippingProfile>
              <ShippingProfileID>'.$policies->shipment[0].'</ShippingProfileID>
              <ShippingProfileName>'.$policies->shipment[1].'</ShippingProfileName>
           </SellerShippingProfile>
         </SellerProfiles>');
        }
   
    return $xml;

  }
  public function verifyListing(Request $request)
{
  $inputs = $request->all();
  $entries = [];

  $tokens = $this->token();
  $policies = json_decode($inputs['policies']);

   foreach ($tokens as $k => $token) {
   foreach ($token as $key => $value) {

 
       if (in_array($value['account'], $inputs['store'])) {
        $authnauth_token[$value['account']] = $value['authnauth_token'];
       }
  }
     }


 $checked = $inputs['checked'];

 $form = $inputs['form'];
 $exist = [];
if (isset($checked)) {
  // foreach ($checked as $index => $check) {
     foreach ($form as $key => $value) {
         // var_dump($key);
         $pos = strpos($key, '_', strpos($key, '_') + 1);
         if (in_array(explode(substr($key,$pos), $key)[0], $checked)) {
          $exist[] = array($key=>$value);

         }
     }
   
  // }
}

  foreach ($exist as $key => $value) {
    foreach ($value as $k => $v) {
        if(strpos($k, 'count_') !== false) {

          $keystring = explode('count_', $k)[1];
          $index = explode('_', $keystring)[0];

          $entries[$index][$this->str_replace_first($index.'_', '', $keystring)] = $v;
        }
    }
        
      }

      //reindex
      $entries = array_values($entries);
      $itemArray = array();


$result = array();
$output = [];
  foreach ($entries as $key => $value) {
  

    foreach ($value as $k => $v) {
          $temp = &$result;

          foreach(explode('_', $k) as $key) {
             
              $temp =& $temp[$key];
          
          }
          $temp = $v;


    }    
$output[] = $result;

  foreach ($authnauth_token as $key => $token) {

    //handle each listing
    $xml[$key][] = $this->handleListingLoopVerify($result, $policies->$key);

  }

  }



//submit to ebay
  foreach ($authnauth_token as $key => $token) {
    $xmldata = [];
    foreach ($xml[$key] as $k => $v) {
    $xmldata = htmlentities('<Item>');

      $xmldata .= $v;
    $xmldata .= htmlentities('</Item>');


    $response[$key][] = $this->fireXmlApi('VerifyAddItem', $xmldata, 1131, true, $token, 1);

    }


  }



 echo json_encode($response);
 exit;
}


  public function addListing(Request $request)
{

  $inputs = $request->all();
  $entries = [];

  $tokens = $this->token();
  $policies = json_decode($inputs['policies']);

   foreach ($tokens as $k => $token) {
   foreach ($token as $key => $value) {

 
       if (in_array($value['account'], $inputs['store'])) {
        $authnauth_token[$value['account']] = $value['authnauth_token'];
       }
  }
     }


 $checked = $inputs['checked'];

 $form = $inputs['form'];
 $exist = [];
if (isset($checked)) {
  // foreach ($checked as $index => $check) {
     foreach ($form as $key => $value) {
         // var_dump($key);
         $pos = strpos($key, '_', strpos($key, '_') + 1);
         if (in_array(explode(substr($key,$pos), $key)[0], $checked)) {
          $exist[] = array($key=>$value);

         }
     }
   
  // }
}

  foreach ($exist as $key => $value) {
    foreach ($value as $k => $v) {
        if(strpos($k, 'count_') !== false) {

          $keystring = explode('count_', $k)[1];
          $index = explode('_', $keystring)[0];

          $entries[$index][$this->str_replace_first($index.'_', '', $keystring)] = $v;
        }
    }
        
      }

      //reindex
      $entries = array_values($entries);
      $itemArray = array();


$result = array();
$output = [];
  foreach ($entries as $key => $value) {
  

    foreach ($value as $k => $v) {
          $temp = &$result;

          foreach(explode('_', $k) as $key) {
             
              $temp =& $temp[$key];
          
          }
          $temp = $v;


    }    
$output[] = $result;

  foreach ($authnauth_token as $key => $token) {

    //handle each listing
    $xml[$key][] = $this->handleListingLoopVerify($result, $policies->$key);

  }

  }



//submit to ebay
  foreach ($authnauth_token as $key => $token) {
    $xmldata = [];
    foreach ($xml[$key] as $k => $v) {
    $xmldata = htmlentities('<Item>');

      $xmldata .= $v;
    $xmldata .= htmlentities('</Item>');


    $response[$key][] = $this->fireXmlApi('AddItem', $xmldata, 1131, true, $token, 1);

//save into mylaucnhpack

// $ebay = new \App\MyLaunchpack;
//       $ebay->account = $value['tokens']['account'];
//       $ebay->listing_id = $launchpack->id;
//       $ebay->package_id = $launchpack->package_id;

//       $ebay->status = $response[$key][0]['Ack'];
//       $ebay->user_id = Auth::id();
//       $ebay->save();

    }


  }



 echo json_encode($response);
 exit;
}

public function submitListing($userID, $request, $urlFrom)
  {
    $account = $request->route('account');
    $itemID =  $request->route('itemid');
    $token = $this->token($account);

    $input = $request->all();
    $entries = [];


      foreach ($input as $key => $value) {
        if(strpos($key, 'count_') !== false) {

          $keystring = explode('count_', $key)[1];
          $index = explode('_', $keystring)[0];

          $entries[$index][$this->str_replace_first($index.'_', '', $keystring)] = $value;
        }
      }

      //reindex
      $entries = array_values($entries);
      $itemArray = array();


  $result = array();
  foreach ($entries as $key => $value) {
    foreach ($value as $k => $v) {
          $temp = &$result;

          foreach(explode('_', $k) as $key) {
             
              $temp =& $temp[$key];
          
          }
          $temp = $v;


    }
  }


//depreciated keys
  unset($result['ListingDesigner']);
  unset($result['Seller']);
  unset($result['ShippingDetails']);
  unset($result['ProductListingDetails']);
  unset($result['ShippingPackageDetails']);

  

  

// echo "<pre>";
// var_dump($result);
// exit;

    $xml = '';
    $response = [];
  
    $xml .= htmlentities('<Item>');
    
    foreach ($result as $key => $value) {

      $xml .= $this->processLoop($key, $value);
      // $response[] =$this->fireXmlApi('VerifyAddItem', $result, 1131, true, $token);
    }
    $xml .= htmlentities('</Item>');


    // $response = $this->fireXmlApi('VerifyAddItem', $xml, 1131, true, $token['authnauth_token'], 1);
    //   echo "<pre>";
    //   var_dump($response);exit;

    // if ($response['Ack'] != 'Failure') {
    $response = $this->fireXmlApi('ReviseItem', $xml, 1131, true, $token['authnauth_token'], 1);

    // } else {
      // echo "<pre>";
      // var_dump($response);exit;
    // }


     \Session::flash('flash_message','Application has been successfully submitted.');

      // return redirect(route('listings33', ['marketplace' => 'ebay', 'response'=>'successfully edited']));
     $fromurl = '/ebay/'.$account.'/listing/edit/'.$itemID;
     return redirect($fromurl);

  }

  public function  quickEdit(Request $request)
  {
    $account = $request->route('account');
    $token = $this->token($account);

    $input = $request->all();

    $listing = $this->fireXmlApi('ReviseItem', 
       [
          'ItemID'=>$input['itemID'], 
          'Title'=>$input['title'],
          'StartPrice'=>[
            'currencyID'=>'USD',
            'StartPrice'=>$input['price']]], 1131, true, $token['authnauth_token']);
     echo json_encode($listing);
  exit;
  }

public function editListing($userID, $account, $itemID)
  {

$token = $this->token($account);


    $listing = $this->fireXmlApi('GetItem', 
        ['IncludeItemCompatibilityList'=>false, 
         'IncludeItemSpecifics'=>true,
         'IncludeTaxTable'=>true, 
         'IncludeWatchCount'=>true,
         'DetailLevel'=>'ReturnAll',
         'ItemID'=>$itemID], 1131, true, $token['authnauth_token']);

    $categoryID = $listing['Item']['PrimaryCategory']['CategoryID'];

    $nonEditableFields = $listing;

    $itemSpecs = [];
    foreach ($nonEditableFields['Item']['ItemSpecifics']['NameValueList'] as $key => $value) {
      unset($value['Source']);
      $itemSpecs[] = $value;
      
    }
    $nonEditableFields['Item']['ItemSpecifics']['NameValueList'] = $itemSpecs;

    unset($nonEditableFields['Item']['PictureDetails']);
    unset($nonEditableFields['Item']['Title']);
    unset($nonEditableFields['Item']['StartPrice']);
    unset($nonEditableFields['Item']['Description']);
    unset($nonEditableFields['Item']['PrimaryCategory']);
    unset($nonEditableFields['Item']['ItemSpecifics']);
    unset($nonEditableFields['Item']['SellerProfiles']);

    $recommended = $this->fireXmlApi('GetCategorySpecifics', 
        ['CategoryID'=>$categoryID], 1131, true, $token['authnauth_token']);

       $recommended = array_column($recommended, 'NameRecommendation');

             foreach ($recommended as $key => $value) {
              foreach ($value as $k => $v) {

                  $recommendedArr[$v['Name']] = array(
                    'Recommendation'=>(isset($v['ValueRecommendation']) ? array_column($v['ValueRecommendation'], 'Value') : ''),
                    'Rule'=>$v['ValidationRules']['UsageConstraint']
                    );

             }
           }

          

    return view('
      edit2-listing', ['hiddenFields'=>$this->xmlToHtml($nonEditableFields, 0),'listing'=>$this->xmlToHtmlArray($listing, 1), 'account'=>$account, 'itemID'=>$itemID, 'recommended'=>$recommendedArr, 'stores'=>$this->stores()]);

  }

public function getListing($userID, $account, $itemID)
  {

$token = $this->token($account);

    $listing = $this->fireXmlApi('GetItem', 
        ['IncludeItemCompatibilityList'=>false, 
         'IncludeItemSpecifics'=>true,
         'IncludeTaxTable'=>true, 
         'IncludeWatchCount'=>true,
         'DetailLevel'=>'ReturnAll',
         'ItemID'=>$itemID], 1131, true, $token['authnauth_token']);


    return view('listing', ['listing'=>$listing, 'account'=>$account, 'itemID'=>$itemID, 'stores'=>$this->stores()]);

  }

    public function myActivity(Request $request){

    $account = $request->route('account');

    $mylaunchpacks = Mylaunchpack::where('account', $account)->get();
    $oldlistings = myOldlisting::where('account',$account)->get();
    
    return view('myactivity',['mylaunchpacks' => $mylaunchpacks, 'oldlistings' => $oldlistings, 'account' => $account]);
  }
  public function getListings(Request $request, $internal = false, $keyword = false)
  {
    $account = '';
    $page = 1;
    $keyword = '';
    $EntriesPerPage = 100;
    
    if ($request->route('account')) {
      $account = $request->route('account');
    }
    if ($request->route('keyword')) {
      $keyword = $request->route('keyword');
      $EntriesPerPage = 200;
    }
    if ($request->route('page')) {
      $page = $request->route('page');
    }

    
    $query = "SELECT package_id FROM mylaunchpacks WHERE account = '".$account."'";
    $package_id = DB::select($query);
  
    $accounts = $this->token();


    if (!$accounts) {
      return redirect('ebay/authorization');
    }
    if (is_array($accounts)) {
      if ($accounts) {



        if ($internal) {

          ob_start();
        }

        $listings = [];

        if ($account != '') {
 

          foreach ($accounts as $k => $v) {
            foreach ($v as $key => $value) {
              if ($value['account'] == $account) {


                $listings[$value['account']] = $this->fireXmlApi(
                  'GetMyeBaySelling',
                  [
                    'SellingSummary' => ['Include' => 'true'],
                    'ActiveList' => [
                      'Sort' => 'TimeLeft',
                      'Pagination' => ['EntriesPerPage' => $EntriesPerPage, 'PageNumber' => $page]
                    ],
                    'DeletedFromSoldList' => ['Include' => 'true'],
                    'DeletedFromUnsoldList' => ['Include' => 'true'],
                    'SoldList' => ['Include' => 'true'],
                    'UnsoldList' => ['Include' => 'true'],

                  ],
                  1131,
                  true,
                  $value['authnauth_token']
                );


              }
            }
          }
        } else {


          foreach ($accounts as $k => $v) {
            foreach ($v as $key => $value) {

              $listings[$value['account']] = $this->fireXmlApi(
                'GetMyeBaySelling',
                [
                  'SellingSummary' => ['Include' => 'true'],
                  'ActiveList' => [
                    'Sort' => 'TimeLeft',
                    'Pagination' => ['EntriesPerPage' => 5, 'PageNumber' => 1]
                  ],
                  'DeletedFromSoldList' => ['Include' => 'true'],
                  'DeletedFromUnsoldList' => ['Include' => 'true'],
                  'DeletedFromUnsoldList' => ['Include' => 'true'],
                  'SoldList' => ['Include' => 'true'],
                  'UnsoldList' => ['Include' => 'true'],

                ],
                1131,
                true,
                $value['authnauth_token']
              );
            }
          }
        }


        //search result
        $searchResult = [];

        if ($keyword != '') {
      
          $items = $listings[$account]['ActiveList']['ItemArray'];
          foreach ($items as $k => $item) {
            foreach ($item as $value) {

              if (strpos($value['Title'], $keyword) !== false) {
                $searchResult[] = $value;
              }
            }
          }
        }

        $config = $this->getConfig()[0];


        if (sizeof($searchResult) > 0) {
          return view('search-listings', ['selling' => $searchResult, 'data' => array_keys($searchResult), 'account' => $account, 'config' => $config, 'source'=>'ebay']);
        }
        return view('listings', ['selling' => $listings, 'data' => array_keys($listings), 'account' => $account, 'config' => $config, 'mlp' => $package_id, 'stores'=>$this->stores(), 'source'=>'ebay']);
      }
    }
  }

  public function endListing($store, $itemID)
  {
    $account = $this->token($store);
    $reason = 'NotAvailable';
    $response = [];
    //check if application data exist
    $listing = $this->fireXmlApi('GetItem', 
        ['IncludeItemCompatibilityList'=>false, 
         'IncludeItemSpecifics'=>true,
         'IncludeTaxTable'=>true, 
         'IncludeWatchCount'=>true,
         'DetailLevel'=>'ReturnAll',
         'ItemID'=>$itemID], 1131, true, $account['authnauth_token']);

    
// $response['Ack'] = 'Success';
    $response = $this->fireXmlApi('EndItem', 
                  ['EndingReason'=>$reason, 
                  'ItemID'=>$itemID], 1131, true, $account['authnauth_token']);

    //if successfullly deleted
    if ($response['Ack'] == 'Success') {
      if (isset($listing['Item']['ApplicationData'])) {
        $packageID  = $listing['Item']['ApplicationData'];
      } else {
        $packageID  = 0;
      }
      
        if ($packageID != 0) {

          //get listing id
          $query = "SELECT id FROM launchpack_listings WHERE package_id = $packageID";
          $listingID = DB::select($query);


          //update mylaunchpacks
          $query = "UPDATE myLaunchpacks SET action = 'END', remark = '".$reason."' WHERE listing_id = ".$listingID[0]->id;
          DB::select($query);

          echo json_encode("new listing deleted successfully");exit;


        } else {

          //insert new record
          $ebay = new \App\MyOldlisting;
          $ebay->account = $account['account'];
          $ebay->item_id = $itemID;
          $ebay->action = 'END';
          $ebay->remark = $reason;

          $ebay->status = $response['Ack'];

          $ebay->user_id = Auth::id();

          $ebay->save();

          echo json_encode("old listing deleted successfully");exit;
        }
    } else {
      echo "<pre>";
      var_dump($response);
      exit;
    }

   
 

  }

  public function endListings(Request $request)
  {
    $store = $request->route('account');
    $account = $this->token($store);
    $input = $request->all();

    $reason = 'NotAvailable';
    $response = [];
    $packageID = [];
    $itemID = [];

    //check if application data exist
    foreach ($input['id'] as $key => $id) {
       $listing = $this->fireXmlApi('GetItem', 
        ['IncludeItemCompatibilityList'=>false, 
         'IncludeItemSpecifics'=>true,
         'IncludeTaxTable'=>true, 
         'IncludeWatchCount'=>true,
         'DetailLevel'=>'ReturnAll',
         'ItemID'=>$id], 1131, true, $account['authnauth_token']);

       if (isset($listing['Item']['ApplicationData'])) {
        $packageID[]  = $listing['Item']['ApplicationData'];
      } else {
        $packageID[]  = 0;
      }
       

      $itemID[]=[
        'EndItemRequestContainer'=>[
          'EndingReason'=>$reason,
          'ItemID'=>$id,
          'MessageID'=>$id]];
    }

// var_dump($itemID);exit;
    // delete
           $response = $this->fireXmlApi('EndItems', 
                  ['EndingReason'=>$reason,
                      'EndItemRequestContainer'=>$itemID], 1131, true, $account['authnauth_token']);
         

    //if successfullly deleted

        if (!in_array(0, $packageID)) {

          foreach ($packageID as $key => $pkg) {
            //get listing id
            $query = "SELECT id FROM launchpack_listings WHERE package_id = $pkg";
            $listingID = DB::select($query);


            //update mylaunchpacks
            $query = "UPDATE myLaunchpacks SET action = 'END', remark = '".$reason."' WHERE listing_id = ".$listingID[0]->id;
            DB::select($query);
          }
          

          echo json_encode("new listing deleted successfully");exit;


        } else {

          //insert new record
          $ebay = new \App\MyOldlisting;
          $ebay->account = $account['account'];
          $ebay->item_id = $itemID;
          $ebay->action = 'END';
          $ebay->remark = $reason;

          $ebay->status = $response['Ack'];

          $ebay->user_id = Auth::id();

          $ebay->save();

          echo json_encode("old listing deleted successfully");exit;
        }
   

  }

  public function createPromotion(Request $request)
  {
    $account = $request->route('account');
    $listingid = $request->route('listingid');
    $input = $request->all();
    $rules = [];
    $benefits = [];
    // date_default_timezone_set("UTC");

// $startDate = date("Y-d-mTG:i:sz", strtotime($input['startDate']));
// $endDate = date("Y-d-mTG:i:sz", strtotime($input['endDate']));

    foreach($input['rules'] as $rule) {
      $rules[$rule[0]] = $rule[1];
    }

        foreach($input['benefits'] as $benefit) {
      $benefits[$benefit[0]] = $benefit[1];
    }

    $accounts = $this->token($account);

    $data = \App\Ebay::where(['user_id'=>Auth::id(), 'account'=>$account])->get();
    $oauthToken = collect($data->toArray())->first();

    $oauthToken = $oauthToken['oauth_token'];
    $refreshToken =  $accounts['oauth_refresh_token'];

    $oauthToken = $this->getRefreshToken($refreshToken);

    $postfields = [
    "marketplaceId"=> "EBAY_US",
    "inventoryCriterion"=> [
        "listingIds"=> [$listingid],
        "inventoryCriterionType"=> "INVENTORY_BY_VALUE"
    ],
    "endDate"=> $input['endDate']."T20:00:00.000Z",
    "discountRules"=> [
        [
            "discountSpecification"=> $rules,
            "ruleOrder"=> 0,
            "discountBenefit"=> $benefits
        ]
    ],
    "name"=> htmlspecialchars("Buy 1 and get 2nd one 5% off -part 2"),
    "description"=> htmlspecialchars("ONLY Buy 1 and get 2nd one 5% off"),
    "startDate"=> $input['startDate']."T20:00:00.000Z",
    "promotionStatus"=> "DRAFT"
];

// $postfields = [
//     "marketplaceId"=> "EBAY_US",
//     "inventoryCriterion"=> [
//         "listingIds"=> [$listingid],
//         "inventoryCriterionType"=> "INVENTORY_BY_VALUE"
//     ],
//     "endDate"=> $input['endDate'],
//     "discountRules"=> [
//         [
//             "discountSpecification"=> [
//                 "numberOfDiscountedItems"=> 1,
//                 "forEachQuantity"=> 1
//             ],
//             "ruleOrder"=> 0,
//             "discountBenefit"=> [
//                 "percentageOffItem"=> "5"
//             ]
//         ]
//     ],
//     "name"=> htmlspecialchars("Buy 1 and get 2nd one 5% off -part 2"),
//     "description"=> htmlspecialchars("ONLY Buy 1 and get 2nd one 5% off"),
//     "startDate"=> $input['startDate'],
//     "promotionStatus"=> "DRAFT"
// ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER,  array("Authorization: Bearer {$oauthToken}","Content-Type: application/json"));
    curl_setopt($ch,CURLOPT_URL,"https://api.ebay.com/sell/marketing/v1/item_promotion");
    
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postfields));
    $promotion = curl_exec($ch);
    $promotion = json_decode($promotion);
    curl_close($ch);

    return json_encode($promotion);
    // return redirect()->back();


  }


  public function deletePromotion(Request $request)
  {
    $account = $request->route('account');
    $promotionID = $request->route('promotionid');


    $accounts = $this->token($account);

    $data = \App\Ebay::where(['user_id'=>Auth::id(), 'account'=>$account])->get();
    $oauthToken = collect($data->toArray())->first();

    $oauthToken = $oauthToken['oauth_token'];
    $refreshToken =  $accounts['oauth_refresh_token'];

    $oauthToken = $this->getRefreshToken($refreshToken);


    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER,  array("Authorization: Bearer {$oauthToken}","Content-Type: application/json"));
    //delete
    curl_setopt($ch,CURLOPT_URL,"https://api.ebay.com/sell/marketing/v1/item_promotion/".$promotionID);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");

    $promotion = curl_exec($ch);
    $promotion = json_decode($promotion);
    curl_close($ch);

    return redirect()->back();

  }

  public function getPromotion(Request $request)
    {
      $account = $request->route('account');
      $promotionID = $request->route('promotionid');

      $accounts = $this->token($account);

       $data = \App\Ebay::where(['user_id'=>Auth::id(), 'account'=>$account])->get();

    $oauthToken = collect($data->toArray())->first();


      $oauthToken = $oauthToken['oauth_token'];
      $refreshToken =  $accounts['oauth_refresh_token'];
      
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER,  array("Authorization: Bearer {$oauthToken}","Content-Type: application/json"));
    curl_setopt($ch,CURLOPT_URL,"https://api.ebay.com/sell/marketing/v1/item_promotion/".$promotionID);
    $traffic = curl_exec($ch);
    curl_close($ch);
    

    $trafficReport = json_decode($traffic);
        if (isset($trafficReport->errors)) {
       
        foreach($trafficReport->errors as $error) {
           if ($error->errorId == 1001) {
             
                $oauthToken = $this->getRefreshToken($refreshToken);
                // var_dump($oauthToken);exit;
          
                //request again
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER,  array("Authorization: Bearer {$oauthToken}","Content-Type: application/json"));
                curl_setopt($ch,CURLOPT_URL,"https://api.ebay.com/sell/marketing/v1/item_promotion/".$promotionID);
                $trafficReport = curl_exec($ch);
                $trafficReport = json_decode($trafficReport);
                curl_close($ch);
                
                     
    
           }
    }
    }

    return view('promotion', ['promotion'=>$trafficReport]);
    }

    public function getPromotions(Request $request)
    {
      $account = $request->route('account');
      $accounts = $this->token($account);

       $data = \App\Ebay::where(['user_id'=>Auth::id(), 'account'=>$account])->get();

    $oauthToken = collect($data->toArray())->first();


      $oauthToken = $oauthToken['oauth_token'];
      $refreshToken =  $accounts['oauth_refresh_token'];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER,  array("Authorization: Bearer {$oauthToken}","Content-Type: application/json"));
    curl_setopt($ch,CURLOPT_URL,"https://api.ebay.com/sell/marketing/v1/promotion?marketplace_id=EBAY_US");
    $traffic = curl_exec($ch);
    curl_close($ch);
  

    $trafficReport = json_decode($traffic);
        if (isset($trafficReport->errors)) {
       
        foreach($trafficReport->errors as $error) {
           if ($error->errorId == 1001) {
             
                $oauthToken = $this->getRefreshToken($refreshToken);
                // var_dump($oauthToken);exit;
          
                //request again
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER,  array("Authorization: Bearer {$oauthToken}","Content-Type: application/json"));
                curl_setopt($ch,CURLOPT_URL,"https://api.ebay.com/sell/marketing/v1/promotion?marketplace_id=EBAY_US");
                $trafficReport = curl_exec($ch);
                $trafficReport = json_decode($trafficReport);
                curl_close($ch);
                
                     
    
           }
    }
    }


    return view('promotions', ['promotions'=>$trafficReport->promotions, 'account'=>$account]);
    }

 public function getPendingShipment(Request $request)
  {

    $EntriesPerPage = 10;
    $pagenumber = 1;
    $account = '';
    $accounts = $this->token();

    $dateTime = new \DateTime();

    $endDate = $dateTime->format("Y-m-d");
    $dateTime->modify('-30 day');
    $startDate = $dateTime->format("Y-m-d");
    $sellings = [];
    $orders = [];

     if ($request->route('account')) {
      $account = $request->route('account');

       foreach ($accounts as $k => $v) {
        foreach ($v as $key => $value) {
         
       
        if ($value['account'] == $account) {
            
            $orders = $this->fireXmlApi('GetOrders', 
            ['CreateTimeFrom'=>$startDate, 
            'CreateTimeTo'=>$endDate, 
            'Pagination'=>[
                            'EntriesPerPage'=>$EntriesPerPage,
                            'PageNumber'=>$pagenumber
                          ]], 1131, true, $value['authnauth_token']);

            $sellings = $this->fireXmlApi('GetMyeBaySelling', 
            ['SellingSummary'=>['Include'=>'true'], 
            'ActiveList'=>['Include'=>'true'], 
            'UnsoldList'=>['Include'=>'true'],
            'SoldList'=>['Include'=>'true',
            'Pagination'=>[
                            'EntriesPerPage'=>$EntriesPerPage,
                            'PageNumber'=>$pagenumber
                          ]],'DetailLevel'=>'ReturnAll'], 1131, true, $value['authnauth_token']);


           
        } 
      }
       }

    }
$orderArr = [];
if (isset($sellings['SoldList'])) {
 $orders = $sellings['SoldList']['OrderTransactionArray']['OrderTransaction'];
       
    foreach($orders as $key=>$value) {
      if (isset($value['Order'])) {

     
      $order = $value['Order'];
      foreach ($order['TransactionArray'] as $k=>$v) {
        
       if (!isset($order['ShippedTime'])) {
        $orderArr[] = $order['OrderID'];
       }
      }
       }
    }
}
   

    // echo "<pre>";
    // var_dump($orders);exit;

    //getting order through getOrders api

// if ($orders['PaginationResult']['TotalNumberOfEntries'] == 1) {

//         $orderArr[] = $orders['OrderArray']['Order']['OrderID'];
//       } else {

//         foreach ($orders['OrderArray']['Order'] as $key => $order) {
//         if (!isset($order['ShippedTime'])) {
     
//           $orderArr[] = $order['OrderID'];
//         }
//       }
//     }

      // $orderID = '09-06943-73368';

      $dataPushCompile = http_build_query(['order_id'=>$orderArr], '', '&');
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, 'https://axisnet.asia/memberv2/API/gettrackingdetails.php');
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $dataPushCompile);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $data = curl_exec($ch);
      curl_close($ch);

      $trackingDetail = json_decode($data);

      // echo "<pre>";
      // var_dump($sellings);
      // exit;
      //getmyebay selling order id to be changed with getorders
     return view('pending-shipment', ['selling'=>$sellings,'orders'=>$orders,'account'=>$account, 'tracking'=>$trackingDetail, 'stores'=>$this->stores()]);
  }


public function createShipmentRequest2(Request $request)
    {

       $accounts = $this->token();
       $packageID = $request->input('packageID');
       $type = $request->input('type');
       $itemID = $request->route('itemid');

       $account = $request->route('account');
       $orderID = $request->route('orderid');

      $token = '';
       foreach ($accounts as $k => $v) {
       foreach ($v as $key => $value) {

        if ($value['account'] == $account) {
          $token = $value['authnauth_token'];
        }
      }
      }
      
      $orders = '';
        if ($orderID) {

             $orders = $this->fireXmlApi('GetOrders', 
            ['OrderIDArray'=>['OrderID'=>$orderID]], 1131, true, $token);


        }

        $ebayOrderID = $orders['OrderArray']['Order']['OrderID'];
        $customerEmail = $orders['OrderArray']['Order']['TransactionArray']['Transaction']['Buyer']['Email'];
        $customerName = $orders['OrderArray']['Order']['ShippingAddress']['Name'];
        $street1 = $orders['OrderArray']['Order']['ShippingAddress']['Street1'];

       if (!is_array($orders['OrderArray']['Order']['ShippingAddress']['Street2']) && $orders['OrderArray']['Order']['ShippingAddress']['Street2']!= '') {
        $street2 = $orders['OrderArray']['Order']['ShippingAddress']['Street2'];
       } else {
        $street2 = $orders['OrderArray']['Order']['ShippingAddress']['Street1'];
       }

        $city =  $orders['OrderArray']['Order']['ShippingAddress']['CityName'];
        $state = $orders['OrderArray']['Order']['ShippingAddress']['StateOrProvince'];
        $zipcode = $orders['OrderArray']['Order']['ShippingAddress']['PostalCode'];
        $country = $orders['OrderArray']['Order']['ShippingAddress']['CountryName'];
        $contact = $orders['OrderArray']['Order']['ShippingAddress']['Phone'];
        $total = $orders['OrderArray']['Order']['AmountPaid'];


           $product_arr = [];


        $userID = Auth::id();
        $shipment = $this->getShipmentDetails($account);

        $memberv2Username = $shipment[0]->memberv2_username;

      

        if (isset($orders['OrderArray']['Order']['TransactionArray'])) {
         
          // 5370
          if (!$packageID) {
            $packageID = $orders['OrderArray']['Order']['TransactionArray']['Transaction']['Item']['ApplicationData'];
          }
           
            
            if ($type=='package') {
           $productDetails = $this->makeCurl("packageproductbysku", "&packageid=$packageID");


            } else {
           $productDetails = $this->makeCurl("productbysku", "&packageid=$packageID");
            
            }


$itemTitle = '';

    foreach ($orders['OrderArray']['Order']['TransactionArray'] as $key => $value) {
      if (is_array($value)) {

        if (isset($value['Item']['ItemID'])) {

            // if ($value['Item']['ItemID'] == $itemID) {
             $itemTitle = $value['Item']['Title'];
             $amountPaid = $value['TransactionPrice'];

              // }
        } else {
           foreach ($value as $k => $v) {
              if ($v['Item']['ItemID'] == $itemID) {
             $itemTitle = $v['Item']['Title'];
             $amountPaid = $v['TransactionPrice'];

              }
            }
        }
           
          
      } else {
        $itemTitle = $orders['OrderArray']['Order']['TransactionArray']['Transaction']['Item']['Title'];
        $amountPaid = $orders['OrderArray']['Order']['AmountPaid'];
      }

    }


           //prepare order products

           foreach ($productDetails as $key => $item) {

            $product_arr[] = array(
                'po_product_id'=>$item->variant_id,
                'po_brand_id'=>$item->brand_id,
                'po_product_quantity'=>(isset($item->quantity)? $item->quantity: '1'),
                'po_listing_title'=>$itemTitle,
                'po_listing_url'=>'https://',
                'po_selling_mode'=>'Buy It Now',
                'po_final_selling_price'=>$amountPaid,
                'po_currency'=>'',
                'po_product_price'=>$item->price_member,
                'po_stock_mode'=>'company',
                'po_status'=>'pending',
                'po_date'=>date('d-m-Y')
            );
           }

        } 
      

       //prepare to generate invoice
       $invoice = array(
            'invoice_uid' => $shipment[0]->memberv2_id,
            'invoice_marketplace' => 'eBay',
            'invoice_shipment_date' => date('d-m-Y'),
            'invoice_shipping_mode' => 'dhl',
            'invoice_shipment_status' => 'pending',
            'invoice_shipment_mode' => 'company',
            'invoice_weight' => '0',
            'invoice_shipping_fee' => '0.00',
            'invoice_shipping_cost' => '0.00',
            'invoice_currency' => '',
            'invoice_final_selling_price' =>$total,
            'invoice_admin_remark' => 'Personal Use',
            'invoice_orderid' => $ebayOrderID,
            'invoice_package_id' => $packageID,
            'invoice_date' => date('d-m-Y'),
            'invoice_submit_time' => date("h:i:sa"),
            'shipment_ebay_id' => $shipment[0]->id,
            'shipment_customer_name' => $customerName,
            'shipment_customer_address1' => $street1,
            'shipment_customer_address2' => $street2,
            'shipment_city' => $city,
            'shipment_state' => $state,
            'shipment_postcode' => $zipcode,
            'shipment_country' => $country,
            'shipment_customer_contact' => $contact,
            'shipment_customer_email' => $customerEmail,
            'shipment_status' => 'pending',
            'shipment_date' => date('d-m-Y'),
            'product_arr' => $product_arr,
        );


        // if ($memberv2Username->memberv2 != '') {
     
        //     $data = array(
        //         'memberv2'=>$memberv2Username->memberv2,
        //         'uid'=>$memberv2Username->memberv2_uid,
        //         'mid'=>$memberv2Username->ebay_marketplace_id,
        //         'invoice'=>$invoice,
        //         'orderLineID'=>$orderID
        //     );
        // } else {


        //     $data = array(
        //         'memberv2'=>$memberv2Username->memberv2
        //     );
        // }
       $data = array(
                'memberv2'=>$shipment[0]->memberv2_username,
                'uid'=>$shipment[0]->memberv2_id,
                'mid'=>$shipment[0]->id,
                'invoice'=>$invoice,
                'orderLineID'=>$orderID
            );

       ob_start();
       ?>
       <form method="post" id="shipmentRequestForm" action="/ebay/order/shipnow">
   
         <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />

<section class="invoice-edit-wrapper">
                    <div class="row">
                        <!-- invoice view page -->
                        <div class="col-xl-9 col-md-8 col-12">
                          <?php
                          if (isset($data['invoice'])) {
                              $invoice = $data['invoice'];
                          ?>

                          <!--standard input-->

                          <input type="hidden" name="invoice_uid" value="<?php echo $shipment[0]->memberv2_id;?>">
                          <input type="hidden" name="invoice_marketplace" value="eBay">
                          <input type="hidden" name="invoice_shipment_date" value="<?php echo date('d-m-Y');?>">
                          <input type="hidden" name="invoice_shipping_mode" value="dhl">
                          <input type="hidden" name="invoice_shipment_status" value="pending">
                          <input type="hidden" name="invoice_shipment_mode" value="company">
                          <input type="hidden" name="invoice_weight" value="0">
                          <input type="hidden" name="invoice_shipping_fee" value="0.00">
                          <input type="hidden" name="invoice_shipping_fee" value="0.00">
                          <input type="hidden" name="invoice_shipping_cost" value="0.00">
                          <input type="hidden" name="invoice_currency" value="">
                          <input type="hidden" name="invoice_final_selling_price" value="<?php echo $total;?>">
                          <input type="hidden" name="invoice_admin_remark" value="Personal Use">
                          <input type="hidden" name="invoice_orderid" value="<?php echo $ebayOrderID;?>">
                          <input type="hidden" name="invoice_package_id" value="<?php echo $packageID;?>">




                            <div class="card">
                                <div class="card-body pb-0 mx-25">
                                    <!-- header section -->
                                    <div class="row mx-0">
                                        <div class="col-xl-4 col-md-12 d-flex align-items-center pl-0">
                                          <input type="text" name="orderlineID" value="<?php echo $orderID;?>">
                                            <h6 class="invoice-number mb-0 mr-75">Marketplace ID#</h6>
                                            <input type="text" class="form-control pt-25 w-50" placeholder="#000" name="invoice_uid" value="<?php echo $invoice['invoice_uid'];?>">
                                        </div>
                                        <div class="col-xl-8 col-md-12 px-0 pt-xl-0 pt-1">
                                            <div class="invoice-date-picker d-flex align-items-center justify-content-xl-end flex-wrap">
                                                <div class="d-flex align-items-center">
                                                    <small class="text-muted mr-75">Issue Date: </small>
                                                    <fieldset class="d-flex ">
                                                        <input type="text" class="form-control pickadate mr-2 mb-50 mb-sm-0" placeholder="Select Date" name="invoice_shipment_date" value="<?php echo $invoice['invoice_shipment_date'];?>">
                                                    </fieldset>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <small class="text-muted mr-75">Due Date: </small>
                                                    <fieldset class="d-flex">
                                                        <input type="text" class="form-control pickadate mb-50 mb-sm-0" placeholder="Select Date" name="invoice_submit_time" value="<?php echo $invoice['invoice_submit_time'];?>">
                                                    </fieldset>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <!-- logo and title -->
                                    <div class="row my-2 py-50">
                                        <div class="col-sm-6 col-12 order-2 order-sm-1">
                                            <h4 class="text-primary">Shipment Request</h4>
                                            <input type="text" name="shipment_customer_name" class="form-control" placeholder="Customer Name" value="<?php echo $customerName;?>">

                                            <input type="text" name="shipment_customer_email" class="form-control" placeholder="Customer Email" value="<?php echo $customerEmail;?>">

                                              <input type="text" name="shipment_customer_contact" class="form-control" placeholder="Customer Contact" value="<?php echo $contact;?>">

                                        </div>
                                        <div class="col-sm-6 col-12 order-1 order-sm-1 d-flex justify-content-end">
                                            <!-- <img src="{{ asset('images/pages/pixinvent-logo.png') }}" alt="logo" height="46" width="164"> -->
                                        </div>
                                    </div>
                                    <hr>
                                    <!-- invoice address and contact -->
                                    <div class="row invoice-info">
                                        <div class="col-lg-6 col-md-12 mt-25">
                                            <h6 class="invoice-to">Bill To</h6>
                                           <!--  <fieldset class="invoice-address form-group">
                                                <input type="text" class="form-control" placeholder="House no.">
                                            </fieldset> -->
                                            <fieldset class="invoice-address form-group">
                                                <textarea class="form-control" rows="4" name="shipment_customer_address1" placeholder="Landmark/Street"><?php echo $street1;?></textarea>
                                            </fieldset>

                                            <fieldset class="invoice-address form-group">
                                                <input type="text" name="shipment_customer_address2" class="form-control" placeholder="City" value="<?php echo ($street2 !=''? $street2 : '');?>">
                                            </fieldset>

                                            <fieldset class="invoice-address form-group">
                                                <input type="text" name="shipment_city" class="form-control" placeholder="City" value="<?php echo ($city !=''? $city : $street2);?>">
                                            </fieldset>

                                          
                                            
                                            <fieldset class="invoice-address form-group">
                                                <input type="text" name="shipment_postcode" class="form-control" placeholder="Pincode" value="<?php echo $zipcode;?>">
                                            </fieldset>
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                                <div class="card-body pt-50">
                                    <!-- product details table-->
                                    <div class="invoice-product-details ">
                                        <form class="form invoice-item-repeater">
                                            <div data-repeater-list="group-a">
                                                <div data-repeater-item>
                                                    <div class="row mb-50">
                                                        <div class="col-12 col-md-7 invoice-item-title">Item</div>
                                                        <!-- <div class="col-3 invoice-item-title">Cost</div> -->
                                                        <div class="col-12 col-md-3 invoice-item-title">Qty</div>
                                                        <div class="col-12 col-md-2 invoice-item-title">Price</div>
                                                    </div>

                                                     <div class="invoice-item d-flex border rounded mb-1">
                                                       

                                                        <div class="invoice-item-filed row pt-1 px-1">
                                                    <?php
                                                $i = 0;

                                                    foreach ($invoice['product_arr'] as $key => $product) {
                                                    $i++;
                                                    ?>
                                                    <!--standard input-->


                                                            <input  type="hidden" name="po_product_id_<?php echo $i;?>" class="form-control" placeholder="0" value="<?php echo $item->variant_id;?>">
                                                            <input  type="hidden" name="po_brand_id_<?php echo $i;?>" class="form-control" placeholder="0" value="<?php echo $item->brand_id;?>">
                                                            <input  type="hidden" name="po_listing_url_<?php echo $i;?>" class="form-control" placeholder="0" value="https://">

                                                            <input  type="hidden" name="po_final_selling_price_<?php echo $i;?>" class="form-control" placeholder="0" value="<?php echo $amountPaid;?>">
                                                            <input  type="hidden" name="po_currency_<?php echo $i;?>" class="form-control" placeholder="0" value="">
                                                            <input  type="hidden" name="po_stock_mode_<?php echo $i;?>" class="form-control" placeholder="0" value="company">
                                                            <input  type="hidden" name="po_status_<?php echo $i;?>" class="form-control" placeholder="0" value="pending">
                                                            <input  type="hidden" name="po_date_<?php echo $i;?>" class="form-control" placeholder="0" value="<?php echo date('d-m-Y');?>">


                                                            <div class="col-md-7 col-12 form-group">
                                                                <input  type="text" name="po_listing_title_<?php echo $i;?>" class="form-control" placeholder="0" value="<?php echo $product['po_listing_title'];?>">
                                                            </div>
                                                            <div class="col-md-3 col-12 form-group">
                                                                <input type="text" name="po_product_quantity_<?php echo $i;?>" class="form-control" placeholder="0" value="<?php echo $product['po_product_quantity'];?>">
                                                            </div>
                                                            <div class="col-md-2 col-12 form-group">
                                                               <strong class="text-primary align-middle">
                                                                  <input type="text" name="po_product_price_<?php echo $i;?>" value="<?php echo $product['po_product_price'];?>">
                                                                </strong>
                                                            </div>
                                                            
                                                        
                                             
                                                <?php
                                                }
                                                ?>

                                                        </div>
                                              
                                                    </div>
                                            </div>
                                          </div>
                                            
                                        </form>
                                    </div>
                                    <!-- invoice subtotal -->
                                    <hr>
                                    <div class="invoice-subtotal pt-50">
                                        <div class="row">
                                            <div class="col-md-5 col-12">
                                                
                                                <div class="form-group">
                                                   <span class="invoice-repeat-btn">Remark</span>
                                                    <input type="text" class="form-control" placeholder="Add client Note" name="invoice_admin_remark" value="<?php echo $invoice['invoice_admin_remark'];?>">
                                                </div>
                                            </div>
                                            <div class="col-lg-5 col-md-7 offset-lg-2 col-12">
                                                <ul class="list-group list-group-flush">
                                                   <!--  <li class="list-group-item d-flex justify-content-between border-0 pb-0">
                                                        <span class="invoice-subtotal-title">Subtotal</span>
                                                        <h6 class="invoice-subtotal-value mb-0">$00.00</h6>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between border-0 pb-0">
                                                        <span class="invoice-subtotal-title">Discount</span>
                                                        <h6 class="invoice-subtotal-value mb-0">$00.00</h6>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between border-0 pb-0">
                                                        <span class="invoice-subtotal-title">Tax</span>
                                                        <h6 class="invoice-subtotal-value mb-0">0.0%</h6>
                                                    </li>
                                                    <li class="list-group-item py-0 border-0 mt-25">
                                                        <hr>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between border-0 py-0">
                                                        <span class="invoice-subtotal-title">Invoice Total</span>
                                                        <h6 class="invoice-subtotal-value mb-0">$00.00</h6>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between border-0 pb-0">
                                                        <span class="invoice-subtotal-title">Paid to date</span>
                                                        <h6 class="invoice-subtotal-value mb-0">$00.00</h6>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between border-0 pb-0">
                                                        <span class="invoice-subtotal-title">Balance (USD)</span>
                                                        <h6 class="invoice-subtotal-value mb-0">$000</h6>
                                                    </li> -->
                                                    <li class="list-group-item border-0 pb-0">
                                                        <button type="submit" class="btn btn-primary btn-block subtotal-preview-btn" id="shipmentRequestFormSubmit">Submit</button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                          }
                   
                            ?>
                        </div>
                        <!-- invoice action  -->
                        <div class="col-xl-3 col-md-4 col-12">
                          <?php
                          if (isset($data['invoice'])) {
                              $invoice = $data['invoice'];
                          ?>
                            <div class="card invoice-action-wrapper shadow-none border">
                                <div class="card-body">
                                    <div class="invoice-action-btn mb-1">
                                        <button class="btn btn-primary btn-block invoice-send-btn">
                                            <i class="bx bx-send"></i>
                                            <span>Order ID : <?php echo $invoice['invoice_orderid'];?></span>
                                        </button>
                                    </div>
                                    <div class="invoice-action-btn mb-1">
                                        <button class="btn btn-light-primary btn-block">
                                            <span>Marketplace : <?php echo $invoice['invoice_marketplace'];?></span>
                                        </button>
                                    </div>
                                    
                                    <div class="invoice-action-btn mb-1">
                                        <button class="btn btn-light-primary btn-block">
                                          Status : <?php echo $invoice['invoice_shipment_status'];?>
                                        </button>
                                    </div>

                                    <div class="invoice-action-btn mb-1">
                                        <button class="btn btn-light-primary btn-block">
                                          Shipment Mode : <?php echo $invoice['invoice_shipment_mode'];?>
                                        </button>
                                    </div>

                                    <div class="invoice-action-btn mb-1">
                                        <button class="btn btn-light-primary btn-block">
                                          Shipping Mode : <?php echo $invoice['invoice_shipping_mode'];?>
                                        </button>
                                    </div>
                                  
                                </div>
                            </div>
                           
                            <?php
                          }
                          ?>
                        </div>
                    </div>
                </section>


                                             
                                                
                                             
                                          </form>
                                          <?php
       $html = ob_get_contents();
       ob_end_clean();
       return $html;
        
    }

    //format product array before memberv2 submission
    private function formatListing($listing, $products)
    {
      $formattedList = [];

      if (sizeof($products) > 0) {
        
        if (isset($products['products'])) {
          $pro_pkg =  $products['products'];
        } else if (isset($products['packages'])) {
          $pro_pkg =  $products['packages'];

        } else {
          $pro_pkg = $products;
        }
        
        
        foreach ($pro_pkg  as $key => $product) {
          $formattedList = array(
              'po_product_id'=>$product['variant_id'],
              'po_brand_id'=>$product['brand_id'],
              'po_product_quantity'=>(isset($product['quantity'])? $product['quantity'] : 1),
              'po_listing_title'=>$listing['title'],
              'po_listing_url'=>'https://',
              'po_selling_mode'=>'Buy It Now',
              'po_final_selling_price'=>$listing['TransactionPrice'],
              'po_currency'=>'',
              'po_product_price'=>$product['price_member'],
              'po_stock_mode'=>'company',
              'po_status'=>'pending',
              'po_date'=>date('d-m-Y')
          );

         }

         return $formattedList;
      }
    
    }

    //fetch products & packages based on the application data value
    private function getProductList($applicationData)
    {
      $list = [];
      $applicationData = explode('-', $applicationData);
      $packageID = $applicationData[1];

       if (strpos($applicationData[0], $this->axisProductIdentifier)!==false) {
        $list['products'] = $this->makeCurl("productbysku", "&packageid=$packageID"); 
      } else {
        $list['packages'] = $this->makeCurl("packageproductbysku", "&packageid=$packageID");
      }

      return $list;
    }

    public function getProductList2($request)
    {
      $list = [];
      $input = $request->all();
      $packageID = $input['packageID'];

      $type = $input['type'];

       if ($type != 'package') {
        $list['products'] = $this->makeCurl("productbysku", "&packageid=$packageID"); 
      } else {
        $list['packages'] = $this->makeCurl("packageproductbysku", "&packageid=$packageID");
      }

      return $list;
    }

    //check if ApplicationData in listing exist
    private function checkApplicationData($item)
    {

         if (isset($item['ApplicationData'])) {

          return $item['ApplicationData'];
        }

        return false;

         
    }

    //loop through multiple order items
    private function processOrderWithMultipleItem($items)
    {
      $listing = [];
      $products = [];
      $output = [];
      foreach ($items as $key => $item) {

        $listing = array(
          'ItemID'=>$item['Item']['ItemID'],
          'Title'=>$item['Item']['Title'],
          'QuantityPurchased'=>$item['QuantityPurchased'],
          'TransactionPrice'=>$item['TransactionPrice']
        );

        //check if ApplicationData exist
        $applicationData = $this->checkApplicationData($item['Item']);
        if ($applicationData) {

          //get product list
          $products = $this->getProductList($applicationData);
 
    
        } 
        $output[$key]['listing'] = $listing;
        $output[$key]['product'] = $products;

        // $output[$key]['formattedList'] = $this->formatListing($listing, $products);
      }
      
      
      return $output;
    }

    //single order item
    private function processOrderWithSingleItem($item)
    {
        $listing = [];
        $products = [];
        $output = [];

        $listing = array(
          'ItemID'=>$item['Item']['ItemID'],
          'Title'=>$item['Item']['Title'],
          'QuantityPurchased'=>$item['QuantityPurchased'],
          'TransactionPrice'=>$item['TransactionPrice']
        );

        //check if ApplicationData exist
        $applicationData = $this->checkApplicationData($item['Item']);
        if ($applicationData) {

          //get product list
          $products = $this->getProductList($applicationData);
        }
        
         $output[0]['listing'] = $listing;
         $output[0]['product'] = $products;
        // $output[0]['formattedList'] = $this->formatListing($listing, $products);
        

        return $output;
      
    }

  //display shipment request form for submission into memberv2
  public function createShipmentRequest(Request $request)
    {
       $account = $request->route('account');
       $orderID = $request->route('orderid');
       $token = $this->token($account)['authnauth_token'];
       $orders = $this->fireXmlApi('GetOrders', 
            ['OrderIDArray'=>['OrderID'=>$orderID]], 1131, true, $token);

        $userID = Auth::id();
        $shipment = $this->getShipmentDetails2($account);

        //only for demo///show manual shipment straight away
        // return view('shipment-manual', ['sme'=>$this->makeCurl("sme"), 'account'=>$account, 'orderID'=>$orderID, 'stores'=>$this->stores()]);


        if (isset($orders['OrderArray']['Order']['TransactionArray'])) {

          $order = $orders['OrderArray']['Order'];
          $transactionArray= $orders['OrderArray']['Order']['TransactionArray'];

          //check if order contains multiple items
          if (isset($transactionArray['Transaction'][0])) {
              $item = $this->processOrderWithMultipleItem($transactionArray['Transaction']);
          } else {
            //process order with single item
              $item = $this->processOrderWithSingleItem($transactionArray['Transaction']);
          }
          $email = '';


          if (isset($orders['OrderArray']['Order']['TransactionArray']['Transaction'][0])) {
            if (strpos($orders['OrderArray']['Order']['TransactionArray']['Transaction'][0]['Buyer']['Email'], '@') === 0) {
             $email = $orders['OrderArray']['Order']['TransactionArray']['Transaction'][0]['Buyer']['Email'];
            }
          } else {
            if (strpos($orders['OrderArray']['Order']['TransactionArray']['Transaction']['Buyer']['Email'], '@') === 0) {
             $email = $orders['OrderArray']['Order']['TransactionArray']['Transaction']['Buyer']['Email'];
            }
          }
          
        
          $orderDisplay = array(
            'order'=> array('id'=>$order['OrderID'],
            'status'=>$order['OrderStatus'],
            'paid'=>$order['AmountPaid'],
            'subtotal'=>$order['Subtotal'],
            'total'=>$order['Total'],
            'paidTime'=>$order['PaidTime'],
            'paymentMethod'=>$order['PaymentMethods']),
            'customer'=>array(
              'address'=>$order['ShippingAddress'],
              'userid'=>$order['BuyerUserID'],
              'email'=>$email
            ),
            'listing'=>$item,
            'shipment'=>$shipment
          );
          

        } else {
          
         
          echo "something not right";exit;
        }

        return view('ebay.shipment', ['data'=>$orderDisplay, 'stores'=>$this->stores(),'sme'=>$this->makeCurl("sme"), 'account'=>$account,'orderID'=>$order['OrderID']]);
        
    }



    public function createShipmentRequest3(Request $request)
    {

       $accounts = $this->token();
      
       $account = $request->route('account');
       $orderID = $request->route('orderlineid');
    
       $itemID = $request->route('itemid');

      $token = '';
       foreach ($accounts as $k => $v) {
       foreach ($v as $key => $value) {

        if ($value['account'] == $account) {
          $token = $value['authnauth_token'];
        }
      }
      }
      
      $orders = '';
        if ($orderID) {
           

             $orders = $this->fireXmlApi('GetOrders', 
            ['OrderIDArray'=>['OrderID'=>$orderID]], 1131, true, $token);


        }

           $product_arr = [];


        $userID = Auth::id();
        $shipment = $this->getShipmentDetails($account);

        $memberv2Username = $shipment[0]->memberv2_username;
        $title = '';

        if (isset($orders['OrderArray']['Order']['TransactionArray']['Transaction'])) {


  foreach ($orders['OrderArray']['Order']['TransactionArray'] as $key => $value) {

      if (is_array($value)) {

              if ($value['Item']['ItemID'] == $itemID) {
             $itemTitle = $value['Item']['Title'];
             $amountPaid = $value['TransactionPrice'];
             if (isset($value['Item']['ApplicationData'])) {
              $applicationData = $value['Item']['ApplicationData'];
              $applicationData = explode('-', $applicationData);
              $packageID = $applicationData[1];

               if (strpos($applicationData[0], 'APR')!=false) {
              $productDetails = $this->makeCurl("productbysku", "&packageid=$packageID");


                
              } else {
                $productDetails = $this->makeCurl("packageproductbysku", "&packageid=$packageID");
              }
             } else {
              return view('shipment-manual', ['sme'=>$this->makeCurl("sme"), 'account'=>$account, 'orderID'=>$orderID]);
             }
              
             

              $title = $v['Item']['Title'];
              $amountPaid = $v['TransactionPrice'];
              // $orderID = $orders['OrderID'];

              

              }
            
          
      } else {
        $packageID = $orders['OrderArray']['Order']['TransactionArray']['Transaction']['Item']['ApplicationData'];
        $title = $orders['OrderArray']['Order']['TransactionArray']['Transaction']['Item']['Title'];
              $amountPaid = $orders['OrderArray']['Order']['AmountPaid'];
              $orderID =$orders['OrderArray']['Order']['OrderID'];


        if (!isset($orders['OrderArray']['Order']['TransactionArray']['Transaction']['Item']['ApplicationData'])) {
           
               return view('shipment-manual', ['sme'=>$this->makeCurl("sme"), 'account'=>$account, 'orderID'=>$orderID]);

          }
       
      }

    }

         
          // 5370
          
           

           $productDetails = $this->makeCurl("packageproductbysku", "&packageid=$packageID");

           //prepare order products

           foreach ($productDetails as $key => $item) {

            $product_arr[] = array(
                'po_product_id'=>$item->variant_id,
                'po_brand_id'=>$item->brand_id,
                'po_product_quantity'=>$item->quantity,
                'po_listing_title'=>$title,
                'po_listing_url'=>'https://',
                'po_selling_mode'=>'Buy It Now',
                'po_final_selling_price'=>$amountPaid,
                'po_currency'=>'',
                'po_product_price'=>$item->price_member,
                'po_stock_mode'=>'company',
                'po_status'=>'pending',
                'po_date'=>date('d-m-Y')
            );
           }

        } else {
          
         
          echo "something not right";exit;
        }
      

       //prepare to generate invoice
       $invoice = array(
            'invoice_uid' => $shipment[0]->memberv2_id,
            'invoice_marketplace' => 'eBay',
            'invoice_shipment_date' => date('d-m-Y'),
            'invoice_shipping_mode' => 'dhl',
            'invoice_shipment_status' => 'pending',
            'invoice_shipment_mode' => 'company',
            'invoice_weight' => '0',
            'invoice_shipping_fee' => '0.00',
            'invoice_shipping_cost' => '0.00',
            'invoice_currency' => '',
            'invoice_final_selling_price' => $amountPaid,
            'invoice_admin_remark' => 'Personal Use',
            'invoice_orderid' => $orders['OrderArray']['Order']['OrderID'],
            'invoice_package_id' => $packageID,
            'invoice_date' => date('d-m-Y'),
            'invoice_submit_time' => date("h:i:sa"),
            'shipment_ebay_id' => $shipment[0]->id,
            'shipment_customer_name' => $orders['OrderArray']['Order']['ShippingAddress']['Name'],
            'shipment_customer_address1' => $orders['OrderArray']['Order']['ShippingAddress']['Street1'],
            'shipment_customer_address2' => $orders['OrderArray']['Order']['ShippingAddress']['Street2'],
            'shipment_city' => $orders['OrderArray']['Order']['ShippingAddress']['CityName'],
            'shipment_state' => $orders['OrderArray']['Order']['ShippingAddress']['StateOrProvince'],
            'shipment_postcode' => $orders['OrderArray']['Order']['ShippingAddress']['PostalCode'],
            'shipment_country' => $orders['OrderArray']['Order']['ShippingAddress']['CountryName'],
            'shipment_customer_contact' => $orders['OrderArray']['Order']['ShippingAddress']['Phone'],
            'shipment_customer_email' => '',
            'shipment_status' => 'pending',
            'shipment_date' => date('d-m-Y'),
            'product_arr' => $product_arr,
        );


        // if ($memberv2Username->memberv2 != '') {
     
        //     $data = array(
        //         'memberv2'=> 'chsteve',//$memberv2Username->memberv2,
        //         'uid'=>589,//$memberv2Username->memberv2_uid,
        //         'mid'=>793,//$memberv2Username->ebay_marketplace_id,
        //         'invoice'=>$invoice,
        //         'orderLineID'=>$orderID
        //     );
        // } else {


        //     $data = array(
        //         'memberv2'=>$memberv2Username->memberv2
        //     );
        // }

       $data = array(
                'memberv2'=>$shipment[0]->memberv2_username,
                'uid'=>$shipment[0]->memberv2_id,
                'mid'=>$shipment[0]->id,
                'invoice'=>$invoice,
                'orderLineID'=>$orderID
            );

        return view('shipment', ['data'=>$data]);
        
    }


  public function getPackages(Request $request)
  {
      $sme = $request->input('sme');
      $brand = $request->input('brand');

      $predefined = '';
      $loggedUser = Auth::user()->email;

      if (in_array($loggedUser, $this->whiteListedAcc())) {

     $qstring = "&sme=$sme&brand=$brand&email=$loggedUser";

      } else {
     
     $qstring = "&sme=$sme&brand=$brand";
      

      }

      return $this->makeCurl("package", $qstring);
      // $package = $this->makeCurl("package", "&sme=$sme&predefined=$predefined");

  }
    public function updateTrackingCode(Request $request)
    {
      echo "update tracking code";exit;
      $account = $request->route('account');
      $token =  $accounts = $this->token($account);
    
      $invoiceID = $_POST['invoiceID'];
      $orderID = $_POST['orderID'];
      $itemID = $_POST['itemID'];
      $oline = $_POST['oline'];


      $noApplicationData = false;

   // $listing = $this->fireXmlApi('GetItem', 
   //      ['IncludeItemCompatibilityList'=>false, 
   //       'IncludeItemSpecifics'=>true,
   //       'IncludeTaxTable'=>true, 
   //       'IncludeWatchCount'=>true,
   //       'DetailLevel'=>'ReturnAll',
   //       'ItemID'=>$itemID], 1131, true, $token['authnauth_token']);
   // echo "<pre>";
   // var_dump($listing);
// if ($noApplicationData) {
 // edit listing id
      // $listing = $this->fireXmlApi('ReviseItem', 
      //  [
      //     'ItemID'=>$itemID, 
      //     'ApplicationData'=>'APR-1440'], 1131, true, $token['authnauth_token']);
// }

// exit;

      $dataPushCompile = http_build_query(['invoice_id'=>$invoiceID], '', '&');

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, 'https://axisnet.asia/memberv2/API/gettrackingdetails.php');
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $dataPushCompile);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      $data = curl_exec($ch);
      curl_close($ch);

      $trackingDetail = json_decode($data);


// echo "<pre>";
// var_dump($trackingDetail);exit;

if ($trackingDetail != null) {
  // $ebayTrackingStatus = $this->xml->getRequestBody('CompleteSale', ['OrderLineItemID'=>$orderID, 'Shipment'=>['ShipmentTrackingDetails'=>['ShipmentTrackingNumber'=>$trackingDetail[0]->tracking_code, 'ShippingCarrierUsed'=>$trackingDetail[0]->shipping_mode]]], $ebayToken);

   $order[] = $this->fireXmlApi('CompleteSale', 
            ['OrderLineItemID'=>$oline, 'Shipment'=>['ShipmentTrackingDetails'=>['ShipmentTrackingNumber'=>$trackingDetail[0]->tracking_code, 'ShippingCarrierUsed'=>$trackingDetail[0]->shipping_mode]]], 
            1131, true, $token['authnauth_token']);


}
return true;
    }

public function getOrder(Request $request)
  {
    $EntriesPerPage = 100;
    $pagenumber = 1;
    $account = '';
    $orderid = base64_decode($request->route('orderid'));
    
    if ($request->route('page')) {
      $pagenumber = $request->route('page');

    }

    $accounts = $this->token();

    $input = $request->all();
    if (isset($input)) {

    }

    $dateTime = new \DateTime();

    if (isset($input['endDate'])) {
      $endDate = date_create($input['endDate']);
      $endDate = date_format($endDate,"Y-m-d");


    } else {
       $endDate = $dateTime->format("Y-m-d");

    }

   

    if (isset($input['startDate'])) {
      $startDate = date_create($input['startDate']);
      $startDate = date_format($startDate,"Y-m-d");



    } else {

      $dateTime->modify('first day of this month');

      $startDate = $dateTime->format("Y-m-d");

    }


    $sellings = [];
    $order = [];

    // $startDate = '2021-01-08';
    // $endDate = '2021-04-07';

     if ($request->route('account')) {
      $account = $request->route('account');

       foreach ($accounts as $k => $v) {
        foreach ($v as $key => $value) {
       
        if ($value['account'] == $account) {

   
            $order[] = $this->fireXmlApi('GetOrders', 
            ['OrderIDArray'=>['OrderID'=>$orderid]], 
            1131, true, $value['authnauth_token']);

           
        } 
      }
       }

    }


     return view('order', ['order'=>$order,'account'=>$account]);
  }
  public function getOrders(Request $request)
  {
    $EntriesPerPage = 100;
    $pagenumber = 1;
    $account = '';
    if ($request->route('page')) {
      $pagenumber = $request->route('page');

    }

    $accounts = $this->tokens();

    $input = $request->all();
    if (isset($input)) {

    }


if (isset($input['startEndDate'])) {


    $date = explode('-', $input['startEndDate']);
}
    $dateTime = new \DateTime();

    if (isset($date[1])) {
      $endDate = date_create($date[1]);
      $endDate = date_format($endDate,"Y-m-d");


    } else {
       $endDate = $dateTime->format("Y-m-d");

    }

   

    if (isset($date[0])) {
      $startDate = date_create($date[0]);
      $startDate = date_format($startDate,"Y-m-d");



    } else {

      $dateTime->modify('first day of this month');

      $startDate = $dateTime->format("Y-m-d");

    }

    $sellings = [];
    $orders = [];

    $startDate = date('Y-m-d',strtotime("-10 days"));
    $endDate = date('Y-m-d');


     if ($request->route('account')) {
      $account = $request->route('account');

       foreach ($accounts as $k => $value) {
        // foreach ($v as $key => $value) {
       
        if ($value['account'] == $account) {

   
            $orders[] = $this->fireXmlApi('GetOrders', 
            ['CreateTimeFrom'=>$startDate, 
            'CreateTimeTo'=>$endDate, 
            'OrderStatus'=>'All',
            'Pagination'=>[
                            'EntriesPerPage'=>$EntriesPerPage,
                            'PageNumber'=>$pagenumber
                          ]], 1131, true, $value['authnauth_token']);

            // $sellings = $this->fireXmlApi('GetMyeBaySelling', 
            // ['SellingSummary'=>['Include'=>'true'], 
            // 'ActiveList'=>['Include'=>'false'], 
            // 'UnsoldList'=>['Include'=>'false'],
            // 'SoldList'=>['Include'=>'true',
            // 'Pagination'=>[
            //                 'EntriesPerPage'=>$EntriesPerPage,
            //                 'PageNumber'=>$pagenumber
            //               ]],'DetailLevel'=>'ReturnAll'], 1131, true, $value['authnauth_token']);


           
        // } 
      }
       }

    }


    $orders2 = array_column(array_column($orders, 'OrderArray'), 'Order');
    //multiple orders
    if (isset($orders2[0])) {
      $orders2 = $orders2[0];
    }

    $status = array_column($orders2, 'OrderStatus');
 
    $data = [];
    if (sizeof($status)>0) {
 
      foreach ($status as $key => $value) {
        $a = array_column($orders2, 'OrderStatus');

        if ($a[array_keys($a)[0]] == $value) {
          $data[$value] = $orders2;
       

        }
      }
    }
    

    if (isset($input['internal'])) {
      return json_encode($data);
    }
     return view('orders', ['account'=>$account, 'accounts'=>$accounts, 'data'=>$data,'stores'=>$this->stores(), 'source'=>'ebay']);
    
  }

  public function calculator(Request $request)
  {
    $data = \App\Calculator::all();
    $config = collect($data->toArray());
    $account = $request->route('store');

      return view('calculator', ['calculatorConfig'=>$config, 'account'=>$account]);
  }

  public function editCalculator(Request $request)
  {
    $calculatorID = $request->route('calculatorid');
    $input = $request->all();
    $ajax = false;
    if (isset($input['ajax'])) {
        $ajax = true;
      }
     if (isset($input['input'])) {
        $input = $input['input'];
      }
      

    $data = \App\Calculator::where('id', $calculatorID)->get();
    $config = collect($data->toArray())->all();


    if ($request->isMethod('post')) {

          \App\Calculator::where(['id'=>$calculatorID])
              ->update([
                'conversion_rate' => $input['conversion_rate'], 
                'fuel_surcharge' => $input['fuel_surcharge'], 
                'foreign_currency_rate'=> $input['foreign_currency_rate'],
                'ebay_no_store' => $input['ebay_no_store'],
                'ebay_starter' => $input['ebay_starter'],
                'ebay_basic_auction_fixed_price' => $input['ebay_basic_auction_fixed_price'],
                'ebay_premium_auction' => $input['ebay_premium_auction'],
                'ebay_premium_fixed_price' => $input['ebay_premium_fixed_price'],
                'ebay_anchor_auction' => $input['ebay_anchor_auction'],
                'ebay_anchor_fixed_price' => $input['ebay_anchor_fixed_price'],
                'paypal_off_ebay' => $input['paypal_off_ebay'],
                'ebay_referal_no_store' => $input['ebay_referal_no_store'],
                'ebay_referal_any_store' => $input['ebay_referal_any_store'],
                'sst'=> $input['sst'],
                'payment_gateway_fee'=> $input['payment_gateway_fee'],
                'handling_fee'=> $input['handling_fee'],
                'updated_by'=> Auth::id(),


                ]);
    }

    if ($ajax) {
      return true;
    }
      return view('calculator-edit', ['config'=>array_shift($config), 'calculatorID'=>$calculatorID]);



  }

  public function newCalculator(Request $request)
  {
      $account = $request->route('store');

      return view('calculator-add', ['account'=>$account]);
  }

  public function saveCalculator(Request $request)
  {
      $input = $request->all();
      $account = $request->route('store');
      
      $ebay = new \App\Calculator;

      if (isset($input['input'])) {
        $input = $input['input'];
      }

      $ebay->marketplace_id = 1;
      $ebay->account = $account;

      
      $ebay->fuel_surcharge = $input['fuel_surcharge'];
      $ebay->conversion_rate = $input['conversion_rate'];
      $ebay->foreign_currency_rate = $input['foreign_currency_rate'];
      //listing fee
      $ebay->ebay_no_store = $input['ebay_no_store'];
      $ebay->ebay_starter = $input['ebay_starter'];
      $ebay->ebay_basic_auction_fixed_price = $input['ebay_basic_auction_fixed_price'];
      $ebay->ebay_premium_auction = $input['ebay_premium_auction'];
      $ebay->ebay_premium_fixed_price = $input['ebay_premium_fixed_price'];
      $ebay->ebay_anchor_auction = $input['ebay_anchor_auction'];
      $ebay->ebay_anchor_fixed_price = $input['ebay_anchor_fixed_price'];
      $ebay->paypal_off_ebay = $input['paypal_off_ebay'];


      $ebay->ebay_referal_no_store = $input['ebay_referal_no_store'];
      $ebay->ebay_referal_any_store = $input['ebay_referal_any_store'];


      $ebay->sst = $input['sst'];
      $ebay->payment_gateway_fee = $input['payment_gateway_fee'];

      $ebay->handling_fee = $input['handling_fee'];
      $ebay->created_by = Auth::id();

      $ebay->save();
      
      $data = \App\Calculator::all();
    $config = collect($data->toArray());

      return view('calculator', ['calculatorConfig'=>$config, 'account'=>$account]);

  }

  public function updateStatus(Request $request)
  {
    $id = $request->input('id');
    $status = $request->input('status');

    //deactivate all calculator
    \App\Calculator::query()
              ->update([
                'status'=> 0,
                'updated_by'=> Auth::id(),


                ]);
              //activate current
    \App\Calculator::where(['id'=>$id])
              ->update([
                'status'=> $status,
                'updated_by'=> Auth::id(),


                ]);

      return true;

  }

  public function users(Request $request)
  {
    $users = $this->makeCurl("users");
    return view('users', ['users'=>$users]);
  }

  public function programmes(Request $request)
  {
    $programmes = $this->makeCurl("packageaccess", "");

    foreach ($programmes as $key => $value) {
      
      if (isset($value->id)) {
      $programmes[$key]->totalPkg = $this->makeCurl('totalpackagesbyaccess', '&acc_id='.$value->id)[0]->total;
       

      }
    }


    return view('programmes', ['programmes'=>$programmes,'menu'=>$this->menu()]);
  }

  public function programmePackages(Request $request)
  {
    $access_id = $request->route('id');

    $packages = $this->makeCurl("packagebyaccess", "&acc_id=".$access_id);

    foreach ($packages as $key => $value) {

      
      if (isset($value->publish_level)) {
      $packages[$key]->user[] = $this->makeCurl('userbypackage', '&user_id='.$value->publish_level);
       

      }
    }
      

    return view('programme', ['packages'=>$packages,'menu'=>$this->menu()]);
  }

  

}
