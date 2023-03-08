<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MarketplaceController extends Controller
{
    public $marketplace;

    public function __construct()
    {
        $this->middleware('setup');
        session_start();
    }
    public function init(Request $request)
    {
        //dynamically include controller
        $marketplace = app('App\Http\Controllers\\' . ucfirst(\Route::current()->parameter('marketplace')) . 'Controller');

        $this->marketplace = new $marketplace($request);
        return $this->marketplace;
    }

    public function authenticate2(Request $request)
    {
        $this->init($request);
        return $this ->marketplace ->authenticate($request);
    }

    public function refresh(Request $request)
    {
        $this->init($request);
        return $this->marketplace->refresh($request);
    }

    public function getProductList2(Request $request)
    {
        $this->init($request);
        return $this ->marketplace ->getProductList2($request);
    }

    public function index(Request $request)
    {
        $this->init($request);
        return $this->marketplace ->index($request);
    }
    public function index2(Request $request)
    {
        $this->init($request);
        return $this ->marketplace ->index2($request);
    }

    public function index3(Request $request)
    {
        $this->init($request);
        return $this ->marketplace ->index3($request);
    }

    public function index4(Request $request)
    {
        $this->init($request);
        return $this->marketplace ->index4($request);
    }

    public function shipmentShop(Request $request)
    {
        $this->init($request);
        return $this ->marketplace  ->shipmentShop($request);
    }

    public function shipmentShopSave(Request $request)
    {
        $this->init($request);
        return $this ->marketplace->shipmentShopSave($request);
    }

    public function sortSelection(Request $request)
    {
        $this->init($request);
        return $this ->marketplace ->sortSelection($request);
    }

    public function searchSelection(Request $request)
    {
        $this->init($request);
        return $this->marketplace->searchSelection($request);
    }

    public function bulkUploadListing(Request $request)
    {
        $this->init($request);
        return $this ->marketplace->bulkUploadListing($request);
    }
    public function bulkUploadForm(Request $request)
    {
        $this->init($request);
        return $this ->marketplace->bulkUploadForm($request);
    }

    public function updateLaunchpackStatus(Request $request)
    {
        $this->init($request);
        return $this ->marketplace->updateLaunchpackStatus($request);
    }

    public function setupPage(Request $request)
    {
        $this->init($request);
        return $this ->marketplace ->setupPage(0);
    }

    public function getAccept(Request $request)
    {
        $this->init($request);
        return $this ->marketplace ->accept();
    }

    public function setupPage22(Request $request)
    {
        $this->init($request);
        return $this ->marketplace->setupPage(1);
    }


    public function setupPage3(Request $request)
    {
        $this->init($request);
        return $this->marketplace->setupPage(1);
    }

    public function setupPageEdit(Request $request)
    {
        $this->init($request);
        return $this ->marketplace->setupPage(1, 1);
    }

    public function setupPage2(Request $request)
    {
        $this->init($request);
        return $this ->marketplace  ->setupPage2($request);
    }

    public function quicklaunch(Request $request)
    {
        $this->init($request);
        return $this  ->marketplace ->quicklaunch($request);
    }
    public function quicklaunch2(Request $request)
    {
        $this->init($request);
        return $this ->marketplace ->quicklaunch2($request);
    }

    public function launchPack(Request $request)
    {
        $this->init($request);
        return $this  ->marketplace ->launchPack($request);
    }

    public function verifyListing(Request $request)
    {
        $this->init($request);
        return $this ->marketplace ->verifyListing($request);
    }

    public function addListing(Request $request)
    {
        $this->init($request);
        return $this ->marketplace ->addListing($request);
    }

    public function launchPacks(Request $request)
    {
        $this->init($request);
        return $this ->marketplace ->launchPacks(Auth::id() , $request);
    }

    public function getListing(Request $request)
    {
        $this->init($request);
        return $this ->marketplace ->getListing($request);
    }

    public function getListings(Request $request)
    {
        $this->init($request);
        return $this ->marketplace ->getListings($request);
    }

    public function getAjaxListings(Request $request)
    {
        $this->init($request);
        return $this->marketplace ->getAjaxListings($request);
    }

    public function myActivity(Request $request)
    {
        $this->init($request);
        return $this  ->marketplace   ->myActivity($request);
    }

    public function editListing(Request $request)
    {
        $this->init($request);
        return $this ->marketplace  ->editListing($request);
    }

    public function submitListing(Request $request)
    {
        $this->init($request);
        return $this ->marketplace ->submitListing(Auth::id() , $request, $request->url());
    }

    public function endListing(Request $request)
    {
        $this->init($request);
        return $this ->marketplace ->endListing($request->route('account') , $request->route('itemid'));
    }

    public function endListings(Request $request)
    {
        $this->init($request);
        return $this ->marketplace ->endListings($request);
    }

    public function getPromotions(Request $request)
    {
        $this->init($request);
        return $this->marketplace ->getPromotions($request);
    }

    public function getPromotion(Request $request)
    {
        $this->init($request);
        return $this ->marketplace  ->getPromotion($request);
    }

    public function createPromotion(Request $request)
    {
        $this->init($request);
        return $this->marketplace ->createPromotion($request);
    }

    public function deletePromotion(Request $request)
    {
        $this->init($request);
        return $this->marketplace ->deletePromotion($request);
    }

    public function getOrders(Request $request)
    {
        $this->init($request);
        return $this ->marketplace->getOrders($request);
    }
    public function getOrder(Request $request)
    {
        $this->init($request);
        return $this ->marketplace->getOrder($request);
    }
    public function getPendingShipment(Request $request)
    {
        $this->init($request);
        return $this ->marketplace->getPendingShipment($request);
    }

    public function shipNow(Request $request)
    {
        $this->init($request);
        return $this->marketplace ->shipNow($request);
    }

    public function updateTrackingCode(Request $request)
    {
        $this->init($request);
        return $this->marketplace ->updateTrackingCode($request);
    }

    public function createShipmentRequest(Request $request)
    {
        $this->init($request);
        return $this ->marketplace->createShipmentRequest($request);
    }

    public function createBundleShipmentRequest(Request $request)
    {
        $this->init($request);
        return $this ->marketplace  ->createBundleShipmentRequest($request);
    }

    public function createShipmentRequest2(Request $request)
    {
        $this->init($request);
        return $this  ->marketplace ->createShipmentRequest2($request);
    }

    public function createShipmentRequest3(Request $request)
    {
        $this->init($request);
        return $this  ->marketplace ->createShipmentRequest3($request);
    }

    public function getPackages(Request $request)
    {
        $this->init($request);
        return $this ->marketplace->getPackages($request);
    }

    public function quickEdit(Request $request)
    {
        $this->init($request);
        return $this ->marketplace->quickEdit($request);
    }

    public function getTotalCost(Request $request)
    {
        $this->init($request);
        return $this->marketplace ->getTotalCost($request);
    }

    public function getNetProfit(Request $request)
    {
        $this->init($request);
        return $this ->marketplace ->getNetProfit($request);
    }
    public function calculator(Request $request)
    {
        $this->init($request);
        return $this->marketplace->calculator($request);
    }

    public function newCalculator(Request $request)
    {
        $this->init($request);
        return $this ->marketplace ->newCalculator($request);
    }

    public function saveCalculator(Request $request)
    {
        $this->init($request);
        return $this->marketplace ->saveCalculator($request);
    }

    public function editCalculator(Request $request)
    {
        $this->init($request);
        return $this->marketplace->editCalculator($request);
    }

    public function updateStatus(Request $request)
    {
        $this->init($request);
        return $this ->marketplace ->updateStatus($request);
    }

    public function users(Request $request)
    {
        $this->init($request);
        return $this ->marketplace ->users($request);
    }

    public function programmes(Request $request)
    {
        $this->init($request);
        return $this->marketplace ->programmes($request);
    }

    public function programmePackages(Request $request)
    {
        $this->init($request);
        return $this ->marketplace ->programmePackages($request);
    }

    public function notification(Request $request)
    {
        $this->init($request);
        return $this->marketplace->notification($request);
    }

    public function shipmentRequests(Request $request)
    {
        $this->init($request);
        return $this->marketplace ->shipmentRequests($request);
    }

    public function userSetting(Request $request)
    {
        $this->init($request);
        if (!\App\UserSetting::where(['user_id' => Auth::id() ])->exists())
        {
            // return $this->marketplace->userSetting($request);
            $ebay = new \App\UserSetting;
            $ebay->user_id = Auth::id();
            $ebay->default_marketplace = $request->default_marketplace;
            $ebay->save();
        }

    }

    public function logistics(Request $request)
    {
        $this->init($request);

        $query = "SELECT * FROM shipped_orders ORDER BY created_at DESC";

        $orders = DB::select($query);
        $output = [];
        $dataPushCompile = ['order_id' => json_encode(array_column($orders, 'order_id')) ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://axisnet.asia/memberv2/API/getordersbystatus.php');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataPushCompile);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);

        $invoiceStatus = json_decode(rtrim($data, "'"));

        return view('logistics', ['orders' => $orders, 'invoice' => $invoiceStatus, 'stores' => $this->stores() ]);

    }
    public function shops(Request $request)
    {
        $output = array();

        $query = 'SELECT * FROM ebays e LEFT JOIN users u ON e.user_id = u.id';
        $data = DB::select($query);

        $shopsGroup = [];
        foreach ($data as $key => $shop)
        {

            if ($shop->marketplace_id == 2)
            {
                $shop->shopname = ShopeeController::getShopName($shop->account);

            }
            else
            {
                $shop->shopname = $shop->account;
            }
            $shopsGroup[$shop->marketplace_id][$shop->shopname][] = $shop;
        }
        return view('shops', ['shops' => $shopsGroup, 'stores' => $this->stores() ]);
    }

    public function getInvoice(Request $request)
    {
        $this->init($request);
        return $this ->marketplace ->getInvoice($request);
    }

    public function shop(Request $request)
    {
        $this->init($request);
        return $this->marketplace ->shop($request);
    }

    public function shipManually(Request $request)
    {
        $this->init($request);
        return $this ->marketplace ->shipManually($request);
    }
    public function shipManuallyForm(Request $request)
    {
        $this->init($request);
        return $this ->marketplace ->shipManuallyForm($request);
    }

    public function products(Request $request)
    {
        $this->init($request);
        return $this ->marketplace ->products($request);
    }

    public function getAjaxOrders(Request $request)
    {
        $this->init($request);
        return $this->marketplace->getAjaxOrders($request);
    }
    public function getAjaxInvoice(Request $request)
    {
        $this->init($request);
        return $this ->marketplace ->getAjaxInvoice($request);
    }
    public function createListing(Request $request)
    {
        $this->init($request);
        return $this  ->marketplace->createListing($request);
    }

    public function getProducts(Request $request)
    {
        $this->init($request);
        return $this  ->marketplace ->getProducts($request);
    }

    public function createAirwaybill(Request $request)
    {
        $this->init($request);
        return $this->marketplace ->createAirwaybill($request);
    }

    public function pushOnOrder(Request $request)
    {
        $this->init($request);
        return $this ->marketplace ->pushOnOrder($request);
    }

    //Viewing the userList
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
    public function getuserList(Request $request)
    {
        if ($request->route('account'))
        {
            $account = $request->route('account');
        }
        if ($request->route('marketplace'))
        {
            $marketplace = $request->route('marketplace');
        }
        $keys = ['trainee', 'waiting', 'suspended'];
        return view('userList', ['stores' => $this->stores() , 'keys' => $keys, 'page' => 'usercde', 'title' => 'User', 'form' => 'user']);
    }

    public function getAjaxUserList(Request $request)
    {
        $column = [];
        $input = $request->all();

        $offsetStr = '';
        if (isset($input['offset']))
        {
            $offsetStr = '?offset=' . $input["offset"];
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://axisnet.asia/memberv2/API/getmember.php' . $offsetStr);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $userStatus = curl_exec($ch);
        curl_close($ch);
        $userStatus = json_decode($userStatus, true);
        $status = array_unique(array_column($userStatus["data"], 'status'));
        static $count = 0;
        foreach ($userStatus["data"] as $key => $value)
        {
            $count++;

            $column[$value['status']][] = $this->setRowUserList($value, $count);
        }
        return ['userLists' => $column, 'more' => $userStatus["more"], 'offset' => $userStatus["offset"]];
    }
    public function setRowUserList($value, $count)
    {
        ob_start();

?>
            <tr>
              <td><?php echo $count; ?></td>
              <td>
                <fieldset>
                    <div class="checkbox checkbox-info checkbox-glow">

                        <input type="checkbox" name="userList[]" id="ship_<?php echo $value["id_user"] ?>" value="<?php echo $value['id_user'] ?>" >
                            <label for="ship_<?php echo $value["id_user"] ?>"></label>

                    </div>
                </fieldset>
              </td>
              <td><a href="/userList/<?php echo $value["id_user"] ?>/menu"><?php echo $value["id_user"] ?></a></td>
              <td><?php echo $value["name"] ?></td>
              <td><?php echo $value["username"] ?></td>
              <td><?php echo $value["icnumber"] ?></td>
              <td><?php echo $value["address"] ?></td>
              <td><?php echo $value["phone"] ?></td>
              <td><?php echo $value["email"] ?></td>
              <td><?php echo $value["status"] ?></td>
              <td><?php echo $value["staff"] ?></td>
              <td><?php echo $value["date_join"] ?></td>

            </tr>
              <?php
        $html = ob_get_contents();
        ob_end_clean();
        return $this->minify_html($html);
    }
    //user menu Invoice
    public function getUserMenu(Request $request)
    {
        if ($request->route('id_user'))
        {
            $id_user = $request->route('id_user');
        }

        $keys = ['completed', 'under process', 'pending', 'Deleted', ];
        return view('invoice.uinvoicehome', ['stores' => $this->stores() , 'keys' => $keys, 'title' => 'User Dashboard Invoice', 'page' => 'invoice.userinvoicecde', 'form' => 'invoice']);
    }
    public function getAjaxUserInvoice(Request $request)
    {
        $column = [];
        static $count = 0;
        $count = 0;
        $input = $request->all();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://axisnet.asia/memberv2/API/getinvoice.php?offset=8&marketplace=shopee&uid=967');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $invoiceStatus = curl_exec($ch);
        curl_close($ch);

        $invoiceStatus = json_decode($invoiceStatus, true);

        $status = array_unique(array_column($invoiceStatus["data"], 'shipment_status'));

        foreach ($invoiceStatus["data"] as $key => $value)
        {
            $count++;

            $column[$value['shipment_status']][] = $this->setRowUserInvoice($value, $count);
        }
        return ['userinvoice' => $column, 'more' => $invoiceStatus["more"]];
    }
    public function setRowUserInvoice($value, $count)
    {

        ob_start();
?>
      <tr>
       <td><?php echo $count; ?></td>
       <td>
          <fieldset>
             <div class="checkbox checkbox-info checkbox-glow">
                <input type="checkbox" name="invoice[]" id="ship_<?php echo $value["uid"] ?>" value="<?php echo $value['uid'] ?>" >
                <label for="ship_<?php echo $value["uid"] ?>"></label>
             </div>
          </fieldset>
       </td>
       <td><?php echo $value["id"] ?></td>
       <td><?php echo $value["uid"] ?></td>
       <td><?php echo $value["shipment_date"] ?></td>
       <td><?php echo $value["shipping_mode"] ?></td>
       <td><?php echo $value["shipment_status"] ?></td>
       <td><?php echo $value["shipment_mode"] ?></td>
       <td><?php echo $value["accountnumber"] ?></td>
       <td><?php echo $value["weight"] ?></td>
       <td><?php echo $value["tracking_code"] ?></td>
       <td><?php echo $value["shipping_fee"] ?></td>
       <td><?php echo $value["shipping_cost"] ?></td>

    </tr>
    <?php
        $html = ob_get_contents();
        ob_end_clean();
        return $this->minify_html($html);
    }
    // get own invoice
    public function getownInvoice(Request $request)
    {
        if ($request->route('account'))
        {
            $account = $request->route('account');
        }
        if ($request->route('marketplace'))
        {
            $marketplace = $request->route('marketplace');
        }
        $keys = ['completed', 'pending', 'under process', 'on hold', 'Deleted'];
        return view('invoice.owninvoice', ['stores' => $this->stores() , 'keys' => $keys, 'page' => 'invoice.owncde', 'title' => 'Own Invoice', 'form' => 'Invoice']);
    }
    public function getAjaxownInvoice(Request $request)
    {
        $column = [];
        static $count = 0;
        $count = 0;
        $input = $request->all();

        $offsetStr = '';
        if (isset($input['offset']))
        {
            $offsetStr = '?offset=' . $input["offset"];
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://axisnet.asia/memberv2/API/getowninvoice.php' . $offsetStr);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $invoiceStatus = curl_exec($ch);
        curl_close($ch);

        $invoiceStatus = json_decode($invoiceStatus, true);

        $status = array_unique(array_column($invoiceStatus["data"], 'shipment_status'));

        foreach ($invoiceStatus["data"] as $key => $value)
        {
            $count++;

            $column[$value['shipment_status']][] = $this->setRowownInvoice($value, $count);
        }
        return ['invoice' => $column, 'more' => $invoiceStatus["more"], 'offset' => $invoiceStatus["offset"]];
    }
    public function setRowownInvoice($value, $count)
    {
        $account = '';
        $marketplace = '';
        $action = '<a target="_blank" href="/' . $marketplace . '/' . $account . '/order/ship/' . $value["order_id"] . '" data-item-id="' . $value["order_id"] . '"><small class="text-muted"><i class="bx bx-show-alt"></i></span></a>';
        ob_start();
?>
     <tr>
     <td><?php echo $count; ?></td>
     <td>
         <fieldset>
             <div class="checkbox checkbox-info checkbox-glow">
                 <input type="checkbox" name="invoice[]" id="ship_<?php echo $value["id"] ?>" value="<?php echo $value['id'] ?>" >
                 <label for="ship_<?php echo $value["id"] ?>"></label>
             </div>
         </fieldset>
     </td>
     <td><?php echo (isset($value["id"]) ? $value["id"] : '') ?></td>
     <td><?php echo (isset($value["uid"]) ? $value["uid"] : '') ?></td>
     <td><?php echo (isset($value["shipment_date"]) ? $value["shipment_date"] : '') ?></td>
     <td><?php echo (isset($value["shipping_mode"]) ? $value["shipping_mode"] : '') ?></td>
     <td><?php echo (isset($value["shipment_status"]) ? $value["shipment_status"] : '') ?></td>
     <td><?php echo (isset($value["shipment_mode"]) ? $value["shipment_mode"] : '') ?></td>
     <td><?php echo (isset($value["tracking_code"]) ? $value["tracking_code"] : '') ?></td>
     <td><?php echo (isset($value["shipping_fee"]) ? $value["shipping_fee"] : '') ?></td>
     <td><?php echo (isset($value["shipping_cost"]) ? $value["shipping_cost"] : '') ?></td>
     <td><?php echo $action ?></td>
     </tr>
     <?php
        $html = ob_get_contents();
        ob_end_clean();
        return $this->minify_html($html);
    }

    //get company invoice
    public function getcompanyInvoice(Request $request)
    {
        $account = '';
        $marketplace = '';

        if ($request->route('account'))
        {
            $account = $request->route('account');
        }
        if ($request->route('marketplace'))
        {
            $marketplace = $request->route('marketplace');
        }

        $keys = ['completed', 'pending', 'on hold', 'under process', 'Deleted'];
        return view('invoice.owninvoice', ['account' => $account, 'marketplace' => $marketplace, 'stores' => $this->stores() , 'source' => 'shopee', 'keys' => $keys, 'page' => 'invoice.companyinvoice', 'title' => 'Company Invoice', 'form' => 'Invoice']);
    }
    public function getAjaxcompanyInvoice(Request $request)
    {
        $column = [];
        $count = 0;
        static $count = 0;
        $input = $request->all();

        $offsetStr = '';
        if (isset($input['offset']))
        {
            $offsetStr = '?offset=' . $input["offset"];
        }
        if ($request->route('account'))
        {
            $account = $request->route('account');
        }
        if ($request->route('marketplace'))
        {
            $marketplace = $request->route('marketplace');
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://axisnet.asia/memberv2/API/getinvoice.php' . $offsetStr);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $invoiceStatus = curl_exec($ch);
        curl_close($ch);

        $invoiceStatus = json_decode($invoiceStatus, true);

        $status = array_unique(array_column($invoiceStatus["data"], 'shipment_status'));

        foreach ($invoiceStatus["data"] as $key => $value)
        {
            $count++;

            $column[$value['shipment_status']][] = $this->setRowcompanyInvoice($value, $count);
        }
        return ['invoice' => $column, 'more' => $invoiceStatus["more"], 'offset' => $invoiceStatus["offset"]];
    }

    public function isCoded($invoiceId)
    {
        if (!\App\shippedOrder::where(['invoice_id' => $invoiceId])->exists())
        {
            return false;
        }
        return true;

    }
    public function setRowcompanyInvoice($value, $count)
    {
        $invoiceId = [];
        $isCoded = $this->isCoded($invoiceId);
        $account = '';
        $marketplace = '';
        $action = '<a target="_blank" href="/' . $marketplace . '/' . $account . '/order/ship/' . $value["order_id"] . '" data-item-id="' . $value["order_id"] . '"><small class="text-muted"><i class="bx bx-show-alt"></i></span></a>';
        ob_start();

?>
   <tr>
    <td><?php echo $count; ?></td>
    <td>
       <fieldset>
          <div class="checkbox checkbox-info checkbox-glow">
             <input type="checkbox" name="invoice[]" id="ship_<?php echo $value["id"] ?>" value="<?php echo $value['id'] ?>" >
             <label for="ship_<?php echo $value["id"] ?>"></label>
          </div>
       </fieldset>
    </td>
    <td><?php echo $value["id"] ?></td>
    <td><?php echo $value["uid"] ?></td>
    <td><?php echo $value["shipment_date"] ?></td>
    <td><?php echo $value["shipment_mode"] ?></td>
    <td><?php echo $value["shipment_status"] ?></td>
    <td><?php echo $value["shipping_mode"] ?></td>
    <td><?php echo $value["tracking_code"] ?></td>
    <td><?php echo $value["shipping_fee"] ?></td>
    <td><?php echo $value["date"] ?></td>
    <td>
        <?php echo (isset($value["airwaybill_url"]) ? '<span class="bullet bullet-success bullet-sm"></span>' : '<span class="bullet bullet-danger bullet-sm"></span>'); ?>
       <a target="_blank" href="<?php echo (isset($value["airwaybill_url"]) ? $value["airwaybill_url"] : ''); ?>">
       <small class="text-muted">
            <?php echo ($value["airwaybill_url"] != '' ? 'View' : '-'); ?>
          </small>
        </a>
      </td>
    <td><?php
        if ($isCoded) { echo "yes";}
        else{echo "no";} ?>
    </td>
 </tr>
 <?php
        $html = ob_get_contents();
        ob_end_clean();
        return $this->minify_html($html);
    }

    //get shop list
    public function getshopList(Request $request)
    {
        $account = '';
        $marketplace = '';
        $shopsGroup = [];

        if ($request->route('account'))
        {
            $account = $request->route('account');
        }
        if ($request->route('marketplace'))
        {
            $marketplace = $request->route('marketplace');
        }

        $keys = ['1', '2', '3'];
        return view('shoplist', ['stores' => $this->stores() ,'keys' => $keys, 'article'=>'supposed a name ', 'page' => 'shopcde', 'title' => 'Shop List', 'skipHeader' => 1]);
    }
    public function getAjaxshopData(Request $request)
    {
        $output = array();
        $column = [];
        $query = 'SELECT e.id,e.user_id,e.account,e.marketplace_id,e.axis_shop_name,e.axis_username,e.axis_shop_id,u.name,u.email, COUNT(*) AS total FROM ebays AS e LEFT JOIN users as u ON e.user_id = u.id GROUP BY e.account,e.axis_shop_name';
        $shopList = DB::select($query);
        $count = 0;
        foreach ($shopList as $key => $shop)
        {
            if ($shop->marketplace_id == 2)
            {
                $shop->shopname = ShopeeController::getShopName($shop->account);
            }
            else
            {
                $shop->shopname = $shop->account;
            }
            $count++;

            $column[$shop->marketplace_id][] = $this->setRowShopList($shop, $count);
        }
        return ['shopList' => $column];
    }

    public function setRowShopList($shop, $count)
    {

        ob_start();
?>

    <tr>
    <td><?php echo $count; ?></td>
    <td>
        <fieldset>
            <div class="checkbox checkbox-info checkbox-glow">
                <input type="checkbox" name="datanalysis[]" id="ship_<?php echo $shop->marketplace_id ?>" value="<?php echo $shop->marketplace_id ?>" >
                <label for="ship_<?php echo $shop->marketplace_id ?>"></label>
            </div>
        </fieldset>
    </td>
    <td><?php echo $shop->shopname ?></td>
    <td><?php echo $shop->total ?> </td>
    <td> <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">View</button>
    </td>
    </tr>


    <?php
        $html = ob_get_contents();
        ob_end_clean();

        return $this->minify_html($html);

    }

    //get Data Analysis (product)
    public function getDataAnalysis(Request $request)
    {
        if ($request->route('account'))
        {
            $account = $request->route('account');
        }
        if ($request->route('marketplace'))
        {
            $marketplace = $request->route('marketplace');
        }
        $keys = ['1', '0'];
        return view('data.home', ['stores' => $this->stores() , 'keys' => $keys, 'page' => 'data.homecde', 'title' => 'Product List Report', 'form' => 'Analysis', 'skipHeader' => 1]);
    }

    public function getAjaxDataAnalysis(Request $request)
    {
        $column = [];
        static $count = 0;

        $input = $request->all();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://axisnet.asia/memberv2/API/mining/getproduct.php?');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $datanalysis = curl_exec($ch);
        curl_close($ch);

        $datanalysis = json_decode($datanalysis, true);
        $status = array_unique(array_column($datanalysis["data"], 'product_status'));
        foreach ($datanalysis["data"] as $key => $value)
        {
            $count++;

            $column[$value['product_status']][] = $this->setRowdataAnalysis($value, $count);
        }
        return ['datanalysis' => $column];

    }
    public function setRowdataAnalysis($value, $count)
    {

        ob_start();
?>
    <tr>
    <td><?php echo $count; ?></td>
    <td>
        <fieldset>
            <div class="checkbox checkbox-info checkbox-glow">
                <input type="checkbox" name="datanalysis[]" id="ship_<?php echo $value["id"] ?>" value="<?php echo $value['id'] ?>" >
                <label for="ship_<?php echo $value["id"] ?>"></label>
            </div>
        </fieldset>
 </td>
     <td><a href="/smeData/<?php echo $value["id"] ?>"><?php echo $value["product_name"] ?></a></td>

     <td><a href="/getsmebrand/<?php echo $value["id"] ?>"><?php echo $value["brand_name"] ?></a></td>

     <td><?php echo (isset($value["category_name"]) ? $value["category_name"] : '') ?></td>
    <td>
    <?php
        if ($value["product_status"] == 1)
        {
            echo "Active";
        }
        else
        {
            echo "Inactive";
        }
    ?>
    </td>
    <td><?php echo (isset($value["sku"]) ? $value["sku"] : '') ?></td>
    <td><?php echo (isset($value["date"]) ? $value["date"] : '') ?></td>
    </tr>
    <?php
        $html = ob_get_contents();
        ob_end_clean();
        return $this->minify_html($html);
    }
     //get Data Analysis (sme)
     public function getSmeAnalysis(Request $request)
     {
         if ($request->route('account'))
         {
             $account = $request->route('account');
         }
         if ($request->route('marketplace'))
         {
             $marketplace = $request->route('marketplace');
         }
         $keys = ['1'];
         return view('data.home', ['stores' => $this->stores() , 'keys' => $keys, 'page' => 'data.smecde', 'title' => 'Product List Report', 'form' => 'Analysis', 'skipHeader' => 1]);
     }

     public function getAjaxDataSmeAnalysis(Request $request)
     {
         $column = [];
         static $count = 0;

         $input = $request->all();
         $ch = curl_init();
         curl_setopt($ch, CURLOPT_URL, 'https://axisnet.asia/memberv2/API/mining/getsme.php');
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
         $datanalysis = curl_exec($ch);
         curl_close($ch);
         $datanalysis = json_decode($datanalysis, true);

         foreach ($datanalysis as $key => $value)
         {
             $count++;

             $column[$value['status']][] = $this->setRowsmeAnalysis($value, $count);
         }
         return ['datanalysis' => $column];

     }
public function setRowsmeAnalysis($value, $count)
{

         ob_start();
 ?>
     <tr>
     <td><?php echo $count; ?></td>
     <td>
         <fieldset>
             <div class="checkbox checkbox-info checkbox-glow">
                 <input type="checkbox" name="datanalysis[]" id="ship_<?php echo $value["id"] ?>" value="<?php echo $value['id'] ?>" >
                 <label for="ship_<?php echo $value["id"] ?>"></label>
             </div>
         </fieldset>
  </td>
     <td><?php echo $value["id"] ?></td>
     <td> <a href="/brandData/<?php echo $value["id"] ?>" > <?php echo $value["name"] ?>  </a> </td>
     <td>
     <?php
         if ($value["status"] == 1)
         {
             echo "Active";
         }
         else
         {
             echo "Inactive";
         }
     ?>
     </td>

     </tr>
     <?php
         $html = ob_get_contents();
         ob_end_clean();
         return $this->minify_html($html);
     }
     //get Data Analysis (brand)
     public function getBrandAnalysis(Request $request)
     {
         $keys = ['1'];
         return view('data.home', ['stores' => $this->stores() , 'keys' => $keys, 'page' => 'data.brandcde', 'title' => 'Product List Report', 'form' => 'Analysis', 'skipHeader' => 1]);
     }

     public function getAjaxBrandAnalysis(Request $request )
     {
         $column = [];
         static $count = 0;

         if ($request->route('id')){
            $id= (int)$request->route('id');
         }


         $input = $request->all();
         $ch = curl_init();
         curl_setopt($ch, CURLOPT_URL, "https://axisnet.asia/memberv2/API/mining/getbrand.php?sme_id=$id");
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
         $datanalysis = curl_exec($ch);
         curl_close($ch);
         $datanalysis = json_decode($datanalysis, true);

         foreach ($datanalysis as $key => $value)
         {
             $count++;

             $column[$value['status']][] = $this->setRowBrandAnalysis($value, $count);
         }
         return ['datanalysis' => $column];

     }
     public function setRowBrandAnalysis($value, $count)
     {

         ob_start();
 ?>
     <tr>
     <td><?php echo $count; ?></td>
     <td>
         <fieldset>
             <div class="checkbox checkbox-info checkbox-glow">
                 <input type="checkbox" name="datanalysis[]" id="ship_<?php echo $value["id"] ?>" value="<?php echo $value['id'] ?>" >
                 <label for="ship_<?php echo $value["id"] ?>"></label>
             </div>
         </fieldset>
  </td>
     <td><?php echo $value["id"] ?></td>
     <td><?php echo $value["name"] ?></td>
     <td>
     <?php
         if ($value["status"] == 1)
         {
             echo "Active";
         }
         else
         {
             echo "Inactive";
         }
     ?>
     </td>

     </tr>
     <?php
         $html = ob_get_contents();
         ob_end_clean();
         return $this->minify_html($html);
     }

}
