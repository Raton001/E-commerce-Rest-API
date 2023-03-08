<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use \App\Http\Controllers\Controller as Control;
use Illuminate\Support\Facades\DB;




Trait AuthMarketplaceController
{
  // use \App\Http\Controllers\HelperController;

	public $standardValues;
	public $header;
	public $standardVersion;
	public $appID = 'karennai-middlema-PRD-ec8ec878c-badd14db';
	public $devID = '4d4e58de-1277-4bdf-a853-9d308d9e6e4a';
	public $certID = 'PRD-c8ec878c259b-f82f-4889-9b86-2e2e';
	public $ruName = 'karen_nair-karennai-middle-chacvvjxx';
	public $url = [];
	public $version = [];
	public $token = [];
	public $userID;
	public $auth;
  //shopee
  public $partnerID = 2000902;//2001324
  public $shopeeKey = '545004a2bd8b2d219c7a286a1f2b77ff50ed4952f3ddc1c706568ae2c811224e';//'dd675f15896b4ab97ed5a6a1b38572b7e1b8129a5f15846766141393079a1ec4';
  public $shopeeRedirectUrl = 'https://coded.axisdigitalleap.com/shopee/accept';
  public $shopID = '275920176';

  /**
   * Checks if logged in user has authenticated into any of the marketplace
   * @param  integer  $userID         logged in user ID
   * @param  boolean $firstTimeSetup Determines if first time viewing the setup screen
   * @return array                  array of tokens for all the shops of the current marketplace
   */
  public function setup($firstTimeSetup,$skipSetupChecking = false, $shopid = false)
  {
     $userID = Auth::id();
     $shopname = '';

      if ($skipSetupChecking) {
        return;
      }
      
      
      
      
      
      $editableAccount = \Route::current()->parameter('account');

      $marketplace = \Route::current()->parameter('marketplace');
      if (!$firstTimeSetup) {
        //check if setup complete
        if (!\App\Setup::where(['user_id'=> $userID, 'marketplace_id'=> $marketplace])->exists()) {
          $firstTimeSetup = 1;
        }
      }
      

      $accountFlag = false;
      $shipment = [];
      if ($marketplace == 'ebay') {
        $marketplaceID = 1;
      } else if ($marketplace == 'shopee') {
        $marketplaceID = 2;

      }else if ($marketplace == 'lazada') {
        $marketplaceID = 3;

      }else {
        $marketplaceID = 4;

      }
      if ($shopid) {
        $shopname = $this->getShopName($shopid);
      }


      $auth = $this->ebayAuthNAuth($userID, $marketplaceID, $shopid);
      // echo "<pre>";
      // var_dump($auth);
      // exit;

      $control = new Control();
      $businessPolicy = $control->policy();

      if (isset($auth['authnauth'])) {
        $accounts = array_column($auth['authnauth'], 'account');

        $shipment = $this->getShipmentDetails2($accounts);

        $accountFlag = true;

      }


      return response(view('setup', [
        'firstTime'=>$firstTimeSetup, 
        'consent'=>$auth,
        'account'=>($accountFlag === true ? $auth : true),
        'policies'=>($businessPolicy != null ? $businessPolicy : ''),
        'menu'=>$control->menu(),
        'shipment'=>$shipment,
        'marketplaceID'=>$marketplaceID,
        'marketplace'=>$marketplace,
        'stores'=>$control->stores(),
        'edit'=>($editableAccount ? $editableAccount : ''),
        'shopname'=>$shopname
      ]));
  }

public function authenticateShopee()
{

  /*****push notification verification****/
  // $url = 'https://coded.axisdigitalleap.com';
  // $partner_key = $this->partnerID;
  // $authorization = '';
  // $base_string = $url + '|' + $request_body;
  // $sign = hash_hmac('sha256', $base, $partner_key);

  //  $request_body = json_encode(array(
  //        'shop_id' =>(int)275920176,
  //        'code'=>1,
  //         'partner_id' => (int)$this->partnerID,
  //         'shopid' => (int)$shop_id,
  //         'timestamp' => time())); 

  /**ends**/

  //v1
  //hash
 // $token = hash('sha256', $this->shopeeKey.$this->shopeeRedirectUrl);
  // $url = "https://partner.shopeemobile.com/api/v1/shop/auth_partner?id=$this->partnerID&token=$token&redirect=$this->shopeeRedirectUrl";

//v2 sign
$host = "https://partner.shopeemobile.com";
$path = "/api/v2/shop/auth_partner";
$redirect = $this->shopeeRedirectUrl;
$partner = $this->partnerID;
$partnerKey = $this->shopeeKey;
$time = time();
$base = $partner.$path.$time;
$sign = hash_hmac('sha256', $base, $partnerKey);


  $url = "https://partner.shopeemobile.com/api/v2/shop/auth_partner?partner_id=$this->partnerID&redirect=$this->shopeeRedirectUrl&sign=".$sign."&amp;timestamp=".$time;

   
//         $host = "https://partner.shopeemobile.com";
//         $path = "/api/v2/auth/token/get";

//         $partner = $this->partnerID;
//         $partnerKey = $this->shopeeKey;
//         $time = time();
//         $base = $partner.$path.$time;
//         $sign = hash_hmac('sha256', $base, $partnerKey);

//         $body = array(
//           "code"=>"656d74535a4d6b5550476f65674f6471",
//           "partner_id"=>2000902,
//           "shop_id"=>275920176,
//           "timestamp"=>$time
//         );


//         $url = "https://partner.shopeemobile.com/api/v2/auth/token/get?sign=".$sign."&partner_id=2000902&timestamp=".$time;

//          $ch = curl_init();
//         curl_setopt($ch, CURLOPT_POST, 1);
//         curl_setopt($ch, CURLOPT_URL, $url);
//         // curl_setopt($ch, CURLOPT_TIMEOUT, 10);
//         // curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
//         // curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
//         curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
//         curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($body));
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
//         $result = curl_exec($ch);
//         $result = json_decode($result);


//           curl_close($ch);
//           if (!$result->error) {

          
//             //update db
//            \App\Ebay::where(['user_id'=>11, 'account'=>275920176])
//               ->update([
//                 'access_token' => $result->access_token, 
//                 'refresh_token' => $result->refresh_token
//                 ]);
//           } else {
//             echo "<pre>";
//             var_dump($result);

//             $path = "/api/v2/auth/access_token/get";
//             $url = "https://partner.shopeemobile.com/api/v2/auth/access_token/get?sign=".$sign."&partner_id=2000902&timestamp=".$time;

//             $body = array(
//           "refresh_token"=>"6c57495175626b6f44586a617a535643",
//           "partner_id"=>2000902,
//           "shop_id"=>275920176,
//           "timestamp"=>$time
//         );

//             $ch = curl_init();
//         curl_setopt($ch, CURLOPT_POST, 1);
//         curl_setopt($ch, CURLOPT_URL, $url);
//         // curl_setopt($ch, CURLOPT_TIMEOUT, 10);
//         // curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
//         // curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
//         curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
//         curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($body));
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
//         $result = curl_exec($ch);
//         $result = json_decode($result);
// echo "<pre>";
// var_dump($result);

//           curl_close($ch);

//           exit;
          // }

  

?>
<a href="<?php echo $url;?>">If not redirected to Shopee automatically, click this link</a>
<?php
 // header("Location:".$url);

}


public function authenticateLazada()
{
  $url = "https://auth.lazada.com/oauth/authorize?response_type=code&force_auth=true&redirect_uri=http://coded.axisdigitalleap.com/lazada/accept&client_id=102326";
?>
<a href="<?php echo $url;?>">If not redirected to Lazada automatically, click this link</a>
<?php

}

public function authenticateEbay()
{
    $getSessionID = $this->fireXmlApi('GetSessionID', ['RuName'=>$this->ruName], $this->version('standard'));
$userID = Auth::id();
          $apple =  $getSessionID['SessionID'];
       
        //   if (!\App\Session::where(['user_id'=>$userID])->exists()) {
        

              $ebay = new \App\Session;
              $ebay->user_id = $userID;
              $ebay->session = $apple;
              $ebay->save();
            // }
    
    
          //get session
          $getConsent = 'https://signin.ebay.com/ws/eBayISAPI.dll?SignIn&runame='.$this->ruName.'&SessID='.$apple;

          return Redirect($getConsent);
}

  public function setupManual()
  {
      $control = new Control();
      $businessPolicy = $control->policy();
      $accountFlag = false;
      $firstTimeSetup = false;
      $shipment = [];
      
      $getSessionID = $this->fireXmlApi('GetSessionID', ['RuName'=>$this->ruName], $this->version('standard'));
      $apple =  $getSessionID['SessionID'];
       \Session::put('$apple', $apple);

      //get session
      $getConsent = 'https://signin.ebay.com/ws/eBayISAPI.dll?SignIn&runame='.$this->ruName.'&SessID='.$apple;
  return response(view('setup-manual', [
        'firstTime'=>$firstTimeSetup, 
        'consent'=>$getConsent,
        'account'=>($accountFlag === true ? $auth : true),
        'policies'=>($businessPolicy != null ? $businessPolicy : ''),
        'menu'=>$control->menu(),
        'shipment'=>$shipment
      ]));
  }

  public function whiteListedAcc()
  {
    return array(
      'imah@axisnet.asia',
      'nazrin@axisnet.asia',

    );
  }

    private function getTraffic($oauthToken, $refreshToken)
    {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER,  array("Authorization: Bearer {$oauthToken}","Content-Type: application/x-www-form-urlencoded"));
    curl_setopt($ch,CURLOPT_URL,"https://api.ebay.com/sell/analytics/v1/traffic_report?dimension=string&metric=string&filter=FilterField&sort=SortField");
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
                curl_setopt($ch, CURLOPT_HTTPHEADER,  array("Authorization: Bearer {$oauthToken}","Content-Type: application/x-www-form-urlencoded"));
                curl_setopt($ch,CURLOPT_URL,"https://api.ebay.com/sell/analytics/v1/traffic_report?dimension=string&metric=string&filter=FilterField&sort=SortField");
                $trafficReport = curl_exec($ch);
                $trafficReport = json_decode($trafficReport);
                curl_close($ch);
                
                     
    
           }
    }
    }



    var_dump($trafficReport);exit;
    }


  public function getDefaultBusinessPolicies($account = false)
  {
    $data = \App\DefaultBusinessPolicy::where('created_by', Auth::id())->get();
    $defaultBusinessPolicy = collect($data->toArray())->all();
    $policies = [];
    foreach ($defaultBusinessPolicy as $key => $value) {
      $policies[$key] = $value;
    }
    return $policies;
  }

       
  public function getShipmentDetails($stores)
  {
    $marketplace = \Route::current()->parameter('marketplace');

    if (is_array($stores)) {
      $stores = implode(',', $stores);
    }


    return HelperController::makeStaticCurl("shipmentdetails", "&account=".urlencode($stores)."&marketplace=".urlencode($marketplace));
  }

  public function getShipmentDetails2($stores)
  {
    $userID = Auth::id();
    if (is_array($stores)) {
        $accounts = "'" . implode ( "', '", $stores ) . "'";
    } else {
      $accounts = "'" .$stores."'" ;
    }


     $query = "SELECT marketplace_id, account, axis_shop_id, axis_shop_name, axis_username, axis_user_id, sku_type, free_gift, is_sme, sme_id, registration_no FROM ebays WHERE user_id = $userID AND account IN(".$accounts.")";

      $data = DB::select($query);

      return $data;
  }
   


	private function getRefreshToken($refreshToken)
    {
            $scope = "https%3A%2F%2Fapi.ebay.com%2Foauth%2Fapi_scope%20https%3A%2F%2Fapi.ebay.com%2Foauth%2Fapi_scope%2Fsell.marketing.readonly%20https%3A%2F%2Fapi.ebay.com%2Foauth%2Fapi_scope%2Fsell.marketing%20https%3A%2F%2Fapi.ebay.com%2Foauth%2Fapi_scope%2Fsell.inventory.readonly%20https%3A%2F%2Fapi.ebay.com%2Foauth%2Fapi_scope%2Fsell.inventory%20https%3A%2F%2Fapi.ebay.com%2Foauth%2Fapi_scope%2Fsell.account.readonly%20https%3A%2F%2Fapi.ebay.com%2Foauth%2Fapi_scope%2Fsell.account%20https%3A%2F%2Fapi.ebay.com%2Foauth%2Fapi_scope%2Fsell.fulfillment.readonly%20https%3A%2F%2Fapi.ebay.com%2Foauth%2Fapi_scope%2Fsell.fulfillment%20https%3A%2F%2Fapi.ebay.com%2Foauth%2Fapi_scope%2Fsell.analytics.readonly%20https%3A%2F%2Fapi.ebay.com%2Foauth%2Fapi_scope%2Fsell.finances%20https%3A%2F%2Fapi.ebay.com%2Foauth%2Fapi_scope%2Fsell.payment.dispute%20https%3A%2F%2Fapi.ebay.com%2Foauth%2Fapi_scope%2Fcommerce.identity.readonly";
             //refresh token
             $body = "grant_type=refresh_token&scope=".$scope."&refresh_token=".$refreshToken;
        
      
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


            return $response->access_token;

  
    }

	private function getShipmentPolicy($oauthToken, $refreshToken)
    {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER,  array("Authorization: Bearer {$oauthToken}","Content-Type: application/x-www-form-urlencoded"));
    curl_setopt($ch,CURLOPT_URL,"https://api.ebay.com/sell/account/v1/fulfillment_policy?marketplace_id=EBAY_US");
    $shipmentPolicy = curl_exec($ch);
    curl_close($ch);
    

    $shipmentPolicy = json_decode($shipmentPolicy);
    if (isset($shipmentPolicy->errors)) {
       
        foreach($shipmentPolicy->errors as $error) {
           if ($error->errorId == 1001) {
             
                $oauthToken = $this->getRefreshToken($refreshToken);
                
          
                //request again
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER,  array("Authorization: Bearer {$oauthToken}","Content-Type: application/x-www-form-urlencoded"));
                curl_setopt($ch,CURLOPT_URL,"https://api.ebay.com/sell/account/v1/fulfillment_policy?marketplace_id=EBAY_US");
                $shipmentPolicy = curl_exec($ch);
                $shipmentPolicy = json_decode($shipmentPolicy);
                curl_close($ch);
                
                     
    
           }
    }
    }
    


    $shipment  = [];
    if (isset($shipmentPolicy)) {
      if (isset($shipmentPolicy->fulfillmentPolicies)) {
        foreach($shipmentPolicy->fulfillmentPolicies as $fullfillment) {

          $shipment[$fullfillment->fulfillmentPolicyId]['name'] = $fullfillment->name;
          $shipment[$fullfillment->fulfillmentPolicyId]['default'] = array_column($fullfillment->categoryTypes, 'default')[0];

          

        }
      }
      
    }

    
    return $shipment;
    }
    
    
    
    private function getPaymentPolicy($oauthToken, $refreshToken)
    {

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER,  array("Authorization: Bearer {$oauthToken}","Content-Type: application/x-www-form-urlencoded"));
    curl_setopt($ch,CURLOPT_URL,"https://api.ebay.com/sell/account/v1/payment_policy?marketplace_id=EBAY_US");
    $paymentPolicy = curl_exec($ch);
    curl_close($ch);
    

    $paymentPolicy = json_decode($paymentPolicy);
    if (isset($paymentPolicy->errors)) {
       
        foreach($paymentPolicy->errors as $error) {
           if ($error->errorId == 1001) {
         
                $oauthToken = $this->getRefreshToken($refreshToken);
                
          
                //request again
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER,  array("Authorization: Bearer {$oauthToken}","Content-Type: application/x-www-form-urlencoded"));
                curl_setopt($ch,CURLOPT_URL,"https://api.ebay.com/sell/account/v1/payment_policy?marketplace_id=EBAY_US");
                $paymentPolicy = curl_exec($ch);
                $paymentPolicy = json_decode($paymentPolicy);
                curl_close($ch);
                
                     
    
           }
    }
    }
    

    $payment  = [];
    if (isset($paymentPolicy)) {

      if (isset($paymentPolicy->paymentPolicies)) {
        foreach($paymentPolicy->paymentPolicies as $fullfillment) {
            // $payment[$fullfillment->paymentPolicyId] = $fullfillment->name;

            $payment[$fullfillment->paymentPolicyId]['name'] = $fullfillment->name;
            $payment[$fullfillment->paymentPolicyId]['default'] = array_column($fullfillment->categoryTypes, 'default')[0];

        }
      }
      
    }

    return $payment;
    }
    
    
    private function getReturnPolicy($oauthToken, $refreshToken)
    {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER,  array("Authorization: Bearer {$oauthToken}","Content-Type: application/x-www-form-urlencoded"));
    curl_setopt($ch,CURLOPT_URL,"https://api.ebay.com/sell/account/v1/return_policy?marketplace_id=EBAY_US");
    $returnPolicy = curl_exec($ch);
    curl_close($ch);
    

    $returnPolicy = json_decode($returnPolicy);
    if (isset($returnPolicy->errors)) {
       
        foreach($returnPolicy->errors as $error) {
           if ($error->errorId == 1001) {
          
                $oauthToken = $this->getRefreshToken($refreshToken);
                
          
                //request again
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER,  array("Authorization: Bearer {$oauthToken}","Content-Type: application/x-www-form-urlencoded"));
                curl_setopt($ch,CURLOPT_URL,"https://api.ebay.com/sell/account/v1/return_policy?marketplace_id=EBAY_US");
                $returnPolicy = curl_exec($ch);
                $returnPolicy = json_decode($returnPolicy);
                curl_close($ch);
                
                     
    
           }
    }
    }
    

    $return  = [];
    if (isset($returnPolicy)) {
      if (isset($returnPolicy->returnPolicies)) {
        foreach($returnPolicy->returnPolicies as $fullfillment) {
            // $return[$fullfillment->returnPolicyId] = $fullfillment->name;

            $return[$fullfillment->returnPolicyId]['name'] = $fullfillment->name;
            $return[$fullfillment->returnPolicyId]['default'] = array_column($fullfillment->categoryTypes, 'default')[0];
        }
      }
      
    }
    
    return $return;
    }
	public function getBusinessPolicies($account)
    {
    $data = \App\Ebay::where(['user_id'=>Auth::id(), 'account'=>$account])->get();

    $oauthToken = collect($data->toArray())->first();


    $policies = array(
      'shipment'=>$this->getShipmentPolicy($oauthToken['oauth_token'], $oauthToken['oauth_refresh_token']),
      'payment'=>$this->getPaymentPolicy($oauthToken['oauth_token'], $oauthToken['oauth_refresh_token']),
      'return'=>$this->getReturnPolicy($oauthToken['oauth_token'], $oauthToken['oauth_refresh_token'])
    );

    return $policies;
    }

	public function getUserAuth()
	{
	   $this->auth = new \stdClass();
	   $marketplace = \Route::current()->parameter('marketplace');
     
      if ($marketplace == 'ebay') {
        $marketplaceID = 1;
      } else if ($marketplace == 'shopee') {
        $marketplaceID = 2;

      } else if ($marketplace == 'lazada') {
        $marketplaceID = 3;

      }else  {
        $marketplaceID = 4;

      }


	   $this->auth->accesstoken = $this->ebayAuthNAuth(Auth::id(), $marketplaceID);
 
 
     if (is_array($this->auth->accesstoken)) {


        $account = array_column($this->auth->accesstoken['authnauth'], 'account');

      $stores = [];

      foreach ($account as $key => $value) {
       
          $stores[$value] = $this->getBusinessPolicies($value);
      }

 
      $this->auth->policies = $stores;

      return $this->auth;
     }
     
	}

	// public function accept()
 //  {

 //        $this->userID = Auth::id();

 //        if (isset($_GET['shop_id'])) {
          
 //              $ebay = new \App\Ebay;
 //          $ebay->user_id = $this->userID;
 //          $ebay->account = $_GET['shop_id'];
 //          $ebay->save();
          
 //           //mark setup complete
 //           if (!\App\Setup::where('user_id', $this->userID )->exists()) {
    
 //          //mark setup complete
 //          $ebay = new \App\Setup;
 //          $ebay->user_id = $this->userID;
 //          $ebay->marketplace_id = 'shopee';
 //          $ebay->save();
 //        }
 //        }
   
 //        if (isset($_GET['username'])) {
 //                //reset session every time new account added
 //              // @session_start();
 //              // session_destroy();

 //             \Session::put('ebayAccount', $_GET['username']);

 //             $status = $this->getEbayToken();

 //             if ($status) {
                 
 //                  return redirect('/ebay/setup/2');
 //             }
 //        }
   
 
 //        $account = \Session::get('ebayAccount');
 //        //for oath
 //        if (isset($_GET['code'])) {
            

 //        $refreshToken = \App\Ebay::where(['account'=>$account, 'user_id'=>$this->userID])
 //         ->pluck('oauth_refresh_token')
 //         ->first();

 
 //         //check expiry
 //        if (!$refreshToken) {
            
 //        $body = "grant_type=authorization_code&scope=https%253A%252F%252Fapi.ebay.com%252Foauth%252Fapi_scope%2520https%253A%252F%252Fapi.ebay.com%252Foauth%252Fapi_scope%252Fsell.marketing.readonly%2520https%253A%252F%252Fapi.ebay.com%252Foauth%252Fapi_scope%252Fsell.marketing%2520https%253A%252F%252Fapi.ebay.com%252Foauth%252Fapi_scope%252Fsell.inventory.readonly%2520https%253A%252F%252Fapi.ebay.com%252Foauth%252Fapi_scope%252Fsell.inventory%2520https%253A%252F%252Fapi.ebay.com%252Foauth%252Fapi_scope%252Fsell.account.readonly%2520https%253A%252F%252Fapi.ebay.com%252Foauth%252Fapi_scope%252Fsell.account%2520https%253A%252F%252Fapi.ebay.com%252Foauth%252Fapi_scope%252Fsell.fulfillment.readonly%2520https%253A%252F%252Fapi.ebay.com%252Foauth%252Fapi_scope%252Fsell.fulfillment%2520https%253A%252F%252Fapi.ebay.com%252Foauth%252Fapi_scope%252Fsell.analytics.readonly%2520https%253A%252F%252Fapi.ebay.com%252Foauth%252Fapi_scope%252Fsell.finances%2520https%253A%252F%252Fapi.ebay.com%252Foauth%252Fapi_scope%252Fsell.payment.dispute%2520https%253A%252F%252Fapi.ebay.com%252Foauth%252Fapi_scope%252Fcommerce.identity.readonly&redirect_uri=karen_nair-karennai-middle-chacvvjxx&code=".$_GET['code'];
        
      
 //        $ch = curl_init();
 //        curl_setopt($ch, CURLOPT_URL,"https://api.ebay.com/identity/v1/oauth2/token");
       
        
 //        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 //        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
 //                    "Authorization: Basic ".base64_encode('karennai-middlema-PRD-ec8ec878c-badd14db:PRD-c8ec878c259b-f82f-4889-9b86-2e2e'),
 //                    "Content-Type: application/x-www-form-urlencoded"
 //                ));
 //        curl_setopt($ch, CURLOPT_POST, 1);
 //        curl_setopt($ch, CURLOPT_POSTFIELDS,$body);
        
 //        $server_output = curl_exec($ch);
        
 //        curl_close($ch);
        
   
 //        $response = json_decode($server_output);
  
	// 		if (isset($response->access_token)) {
        
        // \App\Ebay::where(['user_id'=>$this->userID, 'account'=>$account])
		      //     ->update([
		      //     	'oauth_token' => $response->access_token, 
		      //     	'oauth_token_expiry'=>$response->expires_in,
		      //     	'oauth_refresh_token' => $response->refresh_token, 
		      //     	'oauth_refresh_token_expiry'=>$response->refresh_token_expires_in,
		      //     	]);
			
		
 //              //mark setup complete
 //           if (!\App\Setup::where('user_id', $this->userID )->exists()) {
    
 //    			//mark setup complete
 //    			$ebay = new \App\Setup;
 //    			$ebay->user_id = $this->userID;
 //    			$ebay->marketplace_id = 'ebay';
 //    			$ebay->save();
 //    		}
	// 		}
	
 //              return redirect('/ebay/setup/complete');
 //        } else {
 //            exit;
 //            //require user consent
 //            return Redirect::to('https://auth.ebay.com/oauth2/authorize?client_id=karennai-middlema-PRD-ec8ec878c-badd14db&response_type=code&redirect_uri=karen_nair-karennai-middle-chacvvjxx&scope=https://api.ebay.com/oauth/api_scope https://api.ebay.com/oauth/api_scope/sell.marketing.readonly https://api.ebay.com/oauth/api_scope/sell.marketing https://api.ebay.com/oauth/api_scope/sell.inventory.readonly https://api.ebay.com/oauth/api_scope/sell.inventory https://api.ebay.com/oauth/api_scope/sell.account.readonly https://api.ebay.com/oauth/api_scope/sell.account https://api.ebay.com/oauth/api_scope/sell.fulfillment.readonly https://api.ebay.com/oauth/api_scope/sell.fulfillment https://api.ebay.com/oauth/api_scope/sell.analytics.readonly https://api.ebay.com/oauth/api_scope/sell.finances https://api.ebay.com/oauth/api_scope/sell.payment.dispute https://api.ebay.com/oauth/api_scope/commerce.identity.readonly');
 //        }
 //        } 
 //         return redirect('/ebay/setup/complete');
			
 //    }
    
	public function getEbayConsent()
	{
		return json_encode('https://signin.ebay.com/ws/eBayISAPI.dll?SignIn&runame='.$this->ruName.'&SessID='.$this->getSessionID($this->userID, true, true));
	}

	private function getSessionID($userID = false, $expiredSession = false, $fresh = false)
	{	
		if ($fresh) {
			$getSessionID = $this->fireXmlApi('GetSessionID', ['RuName'=>$this->ruName], $this->version('standard'));

			if ($getSessionID['Ack'] != 'Success') {
				echo "GetSessionID unsuccessful";
				exit;
			}

			\Session::put('ebaySession', $getSessionID['SessionID']);

		} else {
					if (!\Session::get('ebaySession') || $expiredSession == true) {
			//fetch new session from ebay
	    	$getSessionID = $this->fireXmlApi('GetSessionID', ['RuName'=>$this->ruName], $this->version('standard'));

			if ($getSessionID['Ack'] != 'Success') {
				echo "GetSessionID unsuccessful";
				exit;
			}

			\Session::put('ebaySession', $getSessionID['SessionID']);
			}
		}

		
		$ebaySession = \Session::get('ebaySession');

        return $ebaySession;
	}

	public function getEbayToken()
	{
    // @session_start();
// 		$output = [];

		$this->userID = Auth::id();

		// $ebaySession = \Session::get('$apple');
        $ebaySession = \App\Session::where(['user_id'=>$this->userID])
         ->pluck('session')
         ->last();
    
       $ebaySession = urldecode($ebaySession);

		$fetchToken = $this->fireXmlApi('FetchToken', ['SessionID'=>$ebaySession], $this->version('standard'));
		//delete the session
        // \App\Session::where('user_id', '==', $this->userID)->delete();
		if ($fetchToken['Ack'] == 'Success') {
			

		//get user details
// 		$userDetails = $this->fireXmlApi('GetUser', ['IncludeFeatureEligibility'=>true, 'DetailLevel'=>'ReturnAll'], $this->version('standard'), true, $fetchToken['eBayAuthToken']);
// 		if ($userDetails['Ack'] == 'Success') {

			//calculate the expiry
			       
			$time = strtotime($fetchToken['HardExpirationTime']);
       $dateInLocal = date("Y-m-d H:i:s", $time);

       $now = time();
       $your_date = strtotime($dateInLocal);
       $datediff = $your_date - $now;
       //days
       $daysLeft = round($datediff / (60 * 60 * 24));

    //   $output = array(
    //   	'account'=>\Session::get('ebayAccount'),
    //   	'authnauth_token'=>true,
    //   	'expiry'=>$daysLeft.' days'
    //   );

			//save the access token
			$ebay = new \App\Ebay;
			$ebay->user_id = $this->userID;
			$ebay->marketplace_id =1;
			$ebay->authnauth_token = $fetchToken['eBayAuthToken'];
			$ebay->authnauth_token_expiry = $fetchToken['HardExpirationTime'];
			$ebay->account = \Session::get('ebayAccount');
			$ebay->save();


// 		}

// 		return json_encode($output);
return true;
		} else {

// echo "<pre>";
// var_dump($ebaySession);
// exit;
//either redirect to ebay or return false to initiate from coded
       $getSessionID = $this->fireXmlApi('GetSessionID', ['RuName'=>$this->ruName], $this->version('standard'));
      $apple =  $getSessionID['SessionID'];

       $ebay = new \App\Session;
          $ebay->user_id = $this->userID;
          $ebay->session = $apple;
          $ebay->save();


      $getConsent = 'https://signin.ebay.com/ws/eBayISAPI.dll?SignIn&runame='.$this->ruName.'&SessID='.$apple;
			header("Location: $getConsent");
      die();
		}

	}

	public function getAccessToken($userID, $account)
	{
		$data = \App\Ebay::where(['user_id'=> $userID, 'account'=>$account])->get(['account','authnauth_token', 'authnauth_token_expiry', 'oauth_refresh_token', 'oauth_refresh_token_expiry', 'method', 'code', 'access_token', 'refresh_token', 'expiry']);
		$token = collect($data->toArray())->first();
		return $token;
	}
	public function ebayAuthNAuth($userID = false, $marketplaceID = false, $shopID = false)
	{
        if (!$userID) {
        $userID = Auth::id();
    
        }
        if (!$marketplaceID) {
          $marketplaceID = 1;
        }

        if ($shopID) {
          $data = \App\Ebay::where(['user_id'=> $userID, 'marketplace_id'=>$marketplaceID, 'account'=>$shopID])->get(['account','authnauth_token', 'authnauth_token_expiry', 'oauth_refresh_token', 'oauth_refresh_token_expiry', 'method', 'code', 'access_token', 'refresh_token', 'expiry']);
        } else {
          $data = \App\Ebay::where(['user_id'=> $userID, 'marketplace_id'=>$marketplaceID])->get(['account','authnauth_token', 'authnauth_token_expiry', 'oauth_refresh_token', 'oauth_refresh_token_expiry', 'method', 'code', 'access_token', 'refresh_token', 'expiry']);
        }

		  $token = collect($data->toArray())->all();


		if (sizeof($token) > 0) {

		 $i = 0;
  
         foreach ($token as $key => $value) {

         	//calculate the expiry
			       
		$time = strtotime($value['authnauth_token_expiry']);
       $dateInLocal = date("Y-m-d H:i:s", $time);

       $now = time();
       $your_date = strtotime($dateInLocal);
       $datediff = $your_date - $now;
       //days
       $daysLeft = round($datediff / (60 * 60 * 24));


       $time2 = strtotime($value['oauth_refresh_token_expiry']);
       $dateInLocal2 = date("Y-m-d H:i:s", $time2);

       $now = time();
       $your_date2 = strtotime($dateInLocal2);
       $datediff2 = $your_date2 - $now;
       //days
       $daysLeft2 = round($datediff2 / (60 * 60 * 24));

       $value['authnauth_token_expiry'] = $daysLeft.' days';
       $value['oauth_refresh_token_expiry'] = $daysLeft2.' days';



         	$i++;
         	$this->token['authnauth'][$i] = $value;

          //add shopname for shopee
          if ($marketplaceID == 2) {
          
          $this->token['authnauth'][$i]['shopname'] = ShopeeController::getShopName($value['account']);

        
          }
         }
   

		return $this->token;
		} 
		return false;
	
// 		  $getSessionID = $this->fireXmlApi('GetSessionID', ['RuName'=>$this->ruName], $this->version('standard'));

//           $apple =  $getSessionID['SessionID'];
          
//           if (!\App\Session::where(['user_id'=>$userID])->exists()) {
        
//               //mark setup complete
//               $ebay = new \App\Session;
//               $ebay->user_id = $userID;
//               $ebay->session = $apple;
//               $ebay->save();
//             }
    
    
//           //get session
//           $getConsent = 'https://signin.ebay.com/ws/eBayISAPI.dll?SignIn&runame='.$this->ruName.'&SessID='.$apple;

// 			return $getConsent;
		/**below not needed*/
    // else {
    //   echo "no ebay token found. SignIn or Manage an eBay account.";exit;
    // }
			 $getSessionID = $this->fireXmlApi('GetSessionID', ['RuName'=>$this->ruName], $this->version('standard'));

		$fetchToken = $this->fireXmlApi('FetchToken', ['SessionID'=>urldecode($getSessionID['SessionID'])], $this->version('standard'));
	
		if ($fetchToken['Ack'] == 'Success') {

			// if (!\App\Ebay::where('user_id', $userID )->exists()) {
			// 	//insert
			// 	$ebay = new \App\Ebay;
			// 	$ebay->user_id = $userID;
			// 	$ebay->authnauth_token = $fetchToken['eBayAuthToken'];
			// 	$ebay->authnauth_token_expiry = $fetchToken['HardExpirationTime'];

			// 	$ebay->save();
			// } else {
			// 	//update
			// 	\App\Ebay::where('user_id', $userID)
		 //          ->update([
		 //          	'authnauth_token' => $fetchToken['eBayAuthToken'], 
		 //          	'authnauth_token_expiry'=>$fetchToken['HardExpirationTime']]);
			// }

			//insert
				//get user details
		$userDetails = $this->fireXmlApi('GetUser', ['IncludeFeatureEligibility'=>true, 'DetailLevel'=>'ReturnAll'], $this->version('standard'), true, $fetchToken['eBayAuthToken']);
		if ($userDetails['Ack'] == 'Success') {


			//save the access token
			$ebay = new \App\Ebay;
			$ebay->user_id = $userID;
			$ebay->authnauth_token = $fetchToken['eBayAuthToken'];
			$ebay->authnauth_token_expiry = $fetchToken['HardExpirationTime'];
			$ebay->account = $userDetails['User']['UserID'];
			$ebay->save();

			// //save the access token
			// $ebay = new \App\Setup;
			// $ebay->user_id = $this->userID;
			// $ebay->marketplace_id = 'ebay';
			// $ebay->save();

}



		} else {

			 $getSessionID = $this->fireXmlApi('GetSessionID', ['RuName'=>$this->ruName], $this->version('standard'));
    // @session_start();
    //     session_destroy();
      $apple =  $getSessionID['SessionID'];
       // \Session::put('$apple', $apple);
  
       if (!\App\Session::where(['user_id'=>$userID])->exists()) {
    
          //mark setup complete
          $ebay = new \App\Session;
          $ebay->user_id = $userID;
          $ebay->session = $apple;
          $ebay->save();
        }


      //get session
      $getConsent = 'https://signin.ebay.com/ws/eBayISAPI.dll?SignIn&runame='.$this->ruName.'&SessID='.$apple;

			return $getConsent;
		}

	}

	
	private function formatIntoXML($array, $api = false)
	{


    $xml = '';
    foreach ($array as $key => $value) {
      $xml .= $this->processLoop($key, $value, $api);
    }
		

		return $xml;
	}

	private function processLoop($key, $value, $api = false)
	{
		$xml = '';
		

		switch (gettype($value)) {
			case 'string':

      if ($api == 'EndItem') {
          $xml.= htmlentities('<'.$key.'>');

      }else {
        if ($key != 'EndingReason') {
          $xml.= htmlentities('<'.$key.'>');

        }
      }
      
				
				
				if ($key == 'Description') {

					// $xml.=htmlentities('<![CDATA['.$value.']]>');
          // $xml.=htmlentities('<![CDATA['.$value.']]>');
              $xml.=$value;



					// $xml.=htmlentities('');

				} else if ($key == 'Title') {

              $xml.=htmlentities(htmlspecialchars($value));
          
        } else {


				

                if ($api == 'EndItem') {
                    $xml.= $value;

                }else {
                  if ($key != 'EndingReason') {
                    $xml.= $value;

                  }
                }


				}

        if ($api == 'EndItem') {
          $xml.= htmlentities('</'.$key.'>');

      }else {
        if ($key != 'EndingReason') {
          $xml.= htmlentities('</'.$key.'>');

        }
      }

				return $xml;
				break;
			case 'integer':
				$xml.= htmlentities('<'.$key.'>');
				$xml.= $value;
				$xml.= htmlentities('</'.$key.'>');
				return $xml;
				break;
			case 'boolean':
				$xml.= htmlentities('<'.$key.'>');

				$xml.= ($value ? $value : 0);
				$xml.= htmlentities('</'.$key.'>');
				return $xml;
				break;

			case 'array':


			if (!is_int($key)) {

				if ($key == 'StartPrice') {
					$xml.= htmlentities('<'.$key.' currencyID="'.$value['currencyID'].'">');


				} 
        else if ($key != 'EndItemRequestContainer') {
				$xml.= htmlentities('<'.$key.'>');	
				}
			}

				if ($key == 'StartPrice') {
          
					// $xml.= htmlentities('<'.$key.' currencyID="'.$value['currencyID'].'">');
          if (isset($value['StartPrice'])) {
          $xml.= $value['StartPrice'];

          }

					// $xml.= htmlentities('</'.$key.'>');

				} else {


				foreach ($value as $k => $v) {

				if($k == 'PictureURL') {
           if (is_string($v)) {

         $pic = explode(',', $v);

          foreach ($pic as $vpic) {
            if ($vpic !='') {

              $xml.= htmlentities('<'.$k.'>');
               $xml.= $vpic;
               $xml.= htmlentities('</'.$k.'>');
            }
             
          }

           } else {
            
         foreach ($v as $kvv => $vvv) {


          if ($k === 'PictureURL') {

             $pic = explode(',', $vvv);
             foreach ($pic as $vpic) {
              if ($vpic !='') {

                if (!is_int($k)) {
                  $xml.= htmlentities('<'.$k.'>');
                 $xml.= $vpic;
                 $xml.= htmlentities('</'.$k.'>');
                }
                
               }
               
              }
          } else {

               $xml.= htmlentities('<'.$kvv.'>');
          
              foreach ($vvv as $kvv3 => $vvvvv) {
             
                if ($kvv == 'EndItemRequestContainer') {
             
                    // foreach ($vvvvv as $kv5 => $vv5) {

                   
                     if (!is_int($kvv3)) {
                        $xml.= htmlentities('<'.$kvv3.'>');
                       $xml.= $vvvvv;
                       $xml.= htmlentities('</'.$kvv3.'>');
                      }
                    // }
                    

              }

              }

              $xml.= htmlentities('</'.$kvv.'>');
      
          }
         

          
          
         }
       
           }

           
        } else {

					if (is_string($v) || is_int($v) || is_bool($v)) {

						if (!is_int($k)) {
						$xml.= htmlentities('<'.$k.'>');
						}
						if ($k == 'CategoryName') {
							$xml.= htmlentities(htmlspecialchars($v));
							// $xml.= htmlspecialchars('Health & Beauty:Vitamins & Lifestyle Supplements:Vitamins & Minerals');


						} else {

							$xml.= $v;

						}
						if (!is_int($k)) {
						$xml.= htmlentities('</'.$k.'>');
						}
					} else {

			if (!is_int($k)) {
				if($k != 'NameValueList') {
				$xml.= htmlentities('<'.$k.'>');
				}
			}
			
			if (is_array($v) || is_object($v)) {

						foreach ($v as $k1 => $v2) {

              if ($k1 == 'EndItemRequestContainer') {
                    $xml.= htmlentities('<'.$k1.'>');
                 
                    foreach ($v2 as $kv5 => $vv5) {

                   
                     if (!is_int($kv5)) {
                        $xml.= htmlentities('<'.$kv5.'>');
                       $xml.= $vv5;
                       $xml.= htmlentities('</'.$kv5.'>');
                      }
                    }
                    $xml.= htmlentities('</'.$k1.'>');

              } else {

              if (is_string($v2) || is_int($v2) || is_bool($v2)) {
                if (!is_int($k1)) {
                $xml.= htmlentities('<'.$k1.'>');
                }
                $xml.= $v2;
                if (!is_int($k1)) {
                $xml.= htmlentities('</'.$k1.'>');
                }
              } else {
                //item specific
                if($k == 'NameValueList') {
                  $xml.= htmlentities('<'.$k.'>');

                }

                foreach ($v2 as $k2 => $v3) {
               
                  if ($k2 == 'Source') {
                    
                    unset($value[$k1][$k2]);
                  }
                  if (is_string($v3) || is_int($v3) || is_bool($v3)) {
                    if (!is_int($k2)) {
                      $xml.= htmlentities('<'.$k2.'>');
                    }

                      $xml.= htmlentities(htmlspecialchars($v3));
                      // $xml.= $v3;

                    if (!is_int($k2)) {
                      $xml.= htmlentities('</'.$k2.'>');
                    }
                  } else {

                    if (is_array($v3)) {
                
                      foreach ($v3 as $k3 => $v4) {
                  
                  
                        if (is_string($v4) || is_int($v4) || is_bool($v4)) {
                          if (!is_int($k3)) {
                            $xml.= htmlentities('<'.$k3.'>');
                          }
                            $xml.= htmlentities(htmlspecialchars($v4));
                            // $xml.= $v4;

                          if (!is_int($k3)) {
                            $xml.= htmlentities('</'.$k3.'>');
                          }
                        } else {
                          
                        }
                        
                      }
                    }


                  }

                }

                if($k == 'NameValueList') {
                  $xml.= htmlentities('</'.$k.'>');

                }
                
              }

              }


						}
						}
					
			if (!is_int($k)) {
				if($k != 'NameValueList') {
				$xml.= htmlentities('</'.$k.'>');
				}
			}
		}
			}

					}
				}
			
			if (!is_int($key) && $key!='EndItemRequestContainer') {
				$xml.= htmlentities('</'.$key.'>');
			}
		

			
				return $xml;
				
				break;
			case 'object':

				if (!is_int($key)) {
          if ($key == 'StartPrice') {
            $xml.= htmlentities('<'.$key.' currencyID="'.$value['currencyID'].'">');
          } else {
          $xml.= htmlentities('<'.$key.'>');
            
          }
      }



        if ($key == 'StartPrice') {
 
          // $xml.= htmlentities('<'.$key.' currencyID="'.$value['currencyID'].'">');
          $xml.= $value['StartPrice'];

          // $xml.= htmlentities('</'.$key.'>');

        } else {

        
        foreach ($value as $k => $v) {

          // if ($k != 'PayPalEmailAddress' && $k !='InternationalReturnsWithinOption') {


            if (is_string($v) || is_int($v) || is_bool($v)) {

            if (!is_int($k)) {
            $xml.= htmlentities('<'.$k.'>');
            }

             if ($k == 'CategoryName') {
              $xml.= htmlentities(htmlspecialchars($v));
              // $xml.= htmlspecialchars('Health & Beauty:Vitamins & Lifestyle Supplements:Vitamins & Minerals');


            } else if ($k == 'Description') {
              // $xml.=htmlentities('<![CDATA['.$v.']]>');

              //if upload listing via bulk upload file which already has cdata stored as description, skip adding it again
              $xml.=$v;



            } else if ($k == 'Title') {
              $xml.=htmlentities(htmlspecialchars($v));
              
            }  else {
              $xml.= $v;

            }
            if (!is_int($k)) {
            $xml.= htmlentities('</'.$k.'>');
            }
          } else {

            if ($k == 'PictureDetails') {

                $pic = explode(',', $v->PictureURL);
              $xml.= htmlentities('<'.$k.'>');

                foreach ($pic as $vpic) {
                  if ($vpic !='') {
                    $xml.= htmlentities('<PictureURL>');
                     $xml.= $vpic;
                     $xml.= htmlentities('</PictureURL>');
                  }
                   
                }

              $xml.= htmlentities('</'.$k.'>');

            } else if ($k == 'StartPrice') {
           
              $xml.= htmlentities('<'.$k.' currencyID="'.$v->currencyID.'">');
              $xml.= $v->StartPrice;
              $xml.= htmlentities('</'.$k.'>');

            }  else if ($k == 'ShippingDetails') {
           
             if (isset($v->ShippingServiceOptions->ShippingService)) {

            
                  $xml.= htmlentities('<ShippingDetails>');

                  if (isset($v->GlobalShipping)) {


                   $xml.= htmlentities('<GlobalShipping>');
                        $xml.= 'true';

                      $xml.= htmlentities('</GlobalShipping>');
                  } else {
                    $xml.='<ShippingServiceOptions>';
                  }


              foreach ($v->ShippingServiceOptions->ShippingService as $kser => $vser) {
                  $xml.= htmlentities('<ShippingService>');
                    $xml.= $vser;

                  $xml.= htmlentities('</ShippingService>');

               
              }
                  $xml.= htmlentities('</ShippingServiceOptions></ShippingDetails>');
              }

               
             
            } else {

        
        if (!is_int($k)) {
          if($k != 'NameValueList') {
          $xml.= htmlentities('<'.$k.'>');
          }
        }

        
      
      if (is_object($v) || is_array($v)) {

            foreach ($v as $k1 => $v2) {
                

              if (is_string($v2) || is_int($v2) || is_bool($v2)) {
                if (!is_int($k1)) {
                $xml.= htmlentities('<'.$k1.'>');
                }
                $xml.= $v2;
                if (!is_int($k1)) {
                $xml.= htmlentities('</'.$k1.'>');
                }
              } else {
                //item specific
                
                if (is_object($v2) || is_array($v2)) {
                  if(!is_int($k1)) {
                  if($k1 != 'NameValueList') {
                  $xml.= htmlentities('<'.$k1.'>');
                  }
                  }

                foreach ($v2 as $k2 => $v3) {
                  if($k1 == 'NameValueList') {

                  $xml.= htmlentities('<'.$k1.'>');
                  }

         
                  if ($k2 == 'Source') {
                    $value = (array)$value;
                   
                    unset($value[$k1][$k2]);
                  }
                  if (is_string($v3) || is_int($v3) || is_bool($v3)) {
                    if (!is_int($k2)) {
                      $xml.= htmlentities('<'.$k2.'>');
                    }

                    if($k1 == 'NameValueList') {
                  $xml.= htmlentities(htmlspecialchars($v3));
                  } else {
                    //contact hour from cannot be urlencode
                    $xml.= $v3;
                  }
                      
                      // $xml.= $v3;

                    if (!is_int($k2)) {
                      $xml.= htmlentities('</'.$k2.'>');
                    }
                  } else {

                    if (is_object($v3) || is_array($v3)) {
                
                      foreach ($v3 as $k3 => $v4) {
                  
                  
                        if (is_string($v4) || is_int($v4) || is_bool($v4)) {
                          if (!is_int($k3)) {
                            $xml.= htmlentities('<'.$k3.'>');
                          }
                            $xml.= htmlentities(htmlspecialchars($v4));
                            // $xml.= $v4;

                          if (!is_int($k3)) {
                            $xml.= htmlentities('</'.$k3.'>');
                          }
                        } else {
                          
                        }
                        
                      }
                    }


                  }

                  if($k1 == 'NameValueList') {
                  $xml.= htmlentities('</'.$k1.'>');
                  }

                }
                

                if(!is_int($k1)) {
                  if($k1 != 'NameValueList') {
                  $xml.= htmlentities('</'.$k1.'>');
                  }
                  }


                }

                
                
              }
            }
            }
        
      if (!is_int($k)) {
        if($k != 'NameValueList') {
        $xml.= htmlentities('</'.$k.'>');
        }
      }
          // }
}
    }

          }
        }
      
      if (!is_int($key)) {
        $xml.= htmlentities('</'.$key.'>');
      }
    
      
        return $xml;
				break;

			default:
			
				break;

		}

	}

	private function url($environment)
	{
	    $this->url['production'] = 'https://api.ebay.com/ws/api.dll';

	    return $this->url[$environment];
	}

	private function version($api)
	{
	    $this->version['standard'] = 1131;
	    
	    return $this->version[$api];
	}

	private function standardBody($version)
	{
		return array(
        'ErrorLanguage'=>'en_US',
        'Version'=>$version,
        'WarningLevel'=>'High',
      	);
	}

	private function headers($api, $version)
	{
		return array (
	            // 'Content-Type' => 'text/xml; charset=UTF8',
				'Content-Type'=> 'application/x-www-form-urlencoded',
	            'X-EBAY-API-COMPATIBILITY-LEVEL' => $version,
	            'X-EBAY-API-CALL-NAME' => $api,
	            'X-EBAY-API-SITEID' => 0,
	            'X-EBAY-API-APP-NAME'=>$this->appID,
	            'X-EBAY-API-DEV-NAME'=>$this->devID,
	            'X-EBAY-API-CERT-NAME'=>$this->certID
	        );
	}

    public function fireXmlApi(
    	$api, 
    	$xmlBody, 
    	$version, 
    	$credentials = false, 
    	$token = false, 
    	$skipFormating = false)
    	{

		$xml = htmlentities("<?xml version='1.0' encoding='utf-8'?><{$api}Request xmlns='urn:ebay:apis:eBLBaseComponents'>");
		if ($credentials) {
		 $xml .= htmlentities("<RequesterCredentials><eBayAuthToken>".$token."</eBayAuthToken></RequesterCredentials>");

		}

		if (!$skipFormating) {

       
       if ($api == 'ReviseItem') {
        $xml .= htmlentities("<Item>");
        $xml .= $this->formatIntoXML($xmlBody);
        $xml .= htmlentities("</Item>");

        } else {
        
        

          $xml .= $this->formatIntoXML($xmlBody, $api);
        }
  
			
		} else {
     
        $xml .= $xmlBody;
      
			
		}


		

		$xml .=$this->formatIntoXML($this->standardBody($version));
		$xml .= htmlentities("</{$api}Request>");

		// if ($api == 'CompleteSale') {

		// echo html_entity_decode($xml);
  //   exit;
		// }

      //   if ($api == 'EndItem') {
      // var_dump(html_entity_decode($xml));
      // exit;
      // }

		$options = [
		    'headers' =>$this->headers($api, $version),
		    'body' => html_entity_decode($xml)
		];

		$client = new Client();

		$response = $client->request('POST', $this->url('production'), $options);
		$encode_response = json_encode(simplexml_load_string($response->getBody()));
		$decode_response = json_decode($encode_response, TRUE);
		

    // if ($api == 'VerifyAddItem') {
    //   echo "<pre>";
    //   var_dump($decode_response);
    //   exit;
    // }
		

		return $decode_response;
    }

    public function xmlToHtmlArray($xml, $counter)
    {
    	// if (!is_array($xml)) {
    	// 	return;
    	// }

    	$html = [];
    	switch (gettype($xml)) {
    		case 'object':

    		$data = $xml->Item;
    			
    			break;
    		case 'array':
       
    		$data = $xml['Item'];
    			
    			break;
    		
    		default:
    			$data = $xml;
    			break;
    	}

    	foreach ($data as $key => $value) {
    		switch (gettype($value)) {
    			case 'string':
    				$html[$key] = array(
    					'name'=>"count_".$counter."_".$key,
    					'value'=>$value);
    				
    				break;
    			case 'boolean':
    				$html[$key] = array(
    					'name'=>"count_".$counter."_".$key,
    					'value'=>$value);
    				
    				break;
    			case 'integer':
    				$html[$key] = array(
    					'name'=>"count_".$counter."_".$key,
    					'value'=>$value);
    				
    				break;
    			case 'array':

    				foreach ($value as $k => $v) {
    					if (is_string($v) || is_int($v) || is_bool($v)) {
    						$html[$key][$k] = array(
    							'name'=>"count_".$counter."_".$key."_".$k,
    							'value'=>$v);
    					}

    					if (is_array($v)) {

    						foreach ($v as $k2 => $v2) {

    							if (is_string($v2) || is_int($v2) || is_bool($v2)) {
    								$html[$key][$k][$k2] = array(
    									'name'=>"count_".$counter."_".$key."_".$k."_".$k2,
    									'value'=>$v2);
    								
    							
    							} 

    							if (is_array($v2)) {
    								//item specific
    								
    								foreach ($v2 as $k3 => $v3) {

    									if ($k3 == 'Source') {

    										unset($html[$key][$k][$k2][$k3]);

    									}

    									$html[$key][$k][$k2][$k3] = array(
    									'name'=>"count_".$counter."_".$key."_".$k."_".$k2."_".$k3,
    									'value'=>$v3);

    									
    								}
    							}


    						}
    					}
    				}
    				break;

    			case 'object':
 
    			//usually for launchpack
    				foreach ($value as $k => $v) {
            
    					if (is_string($v) || is_int($v) || is_bool($v) || is_float($v)) {
    						$html[$key][$k] = array(
    							'name'=>"count_".$counter."_".$key."_".$k,
    							'value'=>$v);
    					}

              if (is_array($v)) {
         
                foreach ($v as $k2 => $v2) {
                 if (is_object($v2)) {
                  
                    //item specific
                    
                    foreach ($v2 as $k3 => $v3) {
                      $html[$key][$k][$k2][$k3] = array(
                                    'name'=>"count_".$counter."_".$key."_".$k."_".$k2."_".$k3,
                                    'value'=>$v3);
                        }
                    }
                 }

              }

    					if (is_object($v)) {
              
    						foreach ($v as $k2 => $v2) {

    							if (is_string($v2) || is_int($v2) || is_bool($v2)) {
    								$html[$key][$k][$k2] = array(
    									'name'=>"count_".$counter."_".$key."_".$k."_".$k2,
    									'value'=>$v2);
    								
    							
    							} 


    							if (is_object($v2)) {
                  
    								//item specific
                    
							    	foreach ($v2 as $k3 => $v3) {
							    		$html[$key][$k][$k2][$k3] = array(
							    									'name'=>"count_".$counter."_".$key."_".$k."_".$k2."_".$k3,
							    									'value'=>$v3);
							    			}
							    	}

								

    							}


    						}
    					}
    				
    				break;

    			default:
    				# code...
    				break;
    		}
    	}

    	return $html;
    }

    public function xmlToHtml($xml, $counter, $display = false)
    {
    	// if (!is_array($xml)) {
    	// 	return;
    	// }
    	switch (gettype($xml)) {
    		case 'object':
    		$data = $xml->Item;
    			
    			break;
    		case 'array':
    		$data = $xml['Item'];
    			
    			break;
    		
    		default:
    			$data = $xml;
    			break;
    	}
    	$html = '';
    	


    	$visibility = ($display==1 ? 'text' : 'hidden');

    	foreach ($data as $key => $value) {
    		switch (gettype($value)) {
    			case 'string':
    				$html.="<input class='form-control' type='".$visibility."' data-input name='count_".$counter."_".$key."' value='".$value."'>";
    				break;
    			case 'boolean':
    				
    				$html.="<input class='form-control' type='".$visibility."' data-input name='count_".$counter."_".$key."' value='".$value."'>";
    				break;
    			case 'integer':
    				$html.="<input class='form-control' type='".$visibility."' data-input name='count_".$counter."_".$key."' value='".$value."'>";
    				break;
    			case 'array':

    				foreach ($value as $k => $v) {
    					

    					if (is_string($v) || is_int($v) || is_bool($v)) {
    						
    						$html.="<input class='form-control' type='".$visibility."' data-input name='count_".$counter."_".$key."_".$k."' value='".$v."'>";
    					}

    					if (is_array($v) || is_object($v)) {

    						foreach ($v as $k2 => $v2) {
    							if (is_string($v2) || is_int($v2) || is_bool($v2)) {
    								
    							    $html.="<input class='form-control' type='".$visibility."' data-input name='count_".$counter."_".$key."_".$k."_".$k2."' value='".$v2."'>";
    							} 

    							if (is_array($v2)) {
    								//item specific
    						
    								foreach ($v2 as $k3 => $v3) {
    									
			    						if (is_string($v3)) {
			    							 $html.="<input class='form-control' type='".$visibility."' data-input name='count_".$counter."_".$key."_".$k."_".$k2."_".$k3."' value='".$v3."'>";
			    						} else {
			    							
			    							foreach ($v3 as $v4) {

			    								if (is_string($v4)) {
			    									$html.="<input class='form-control' type='".$visibility."' data-input name='count_".$counter."_".$key."_".$k."_".$k2."_".$k3."' value='".$v4."'>";
			    								} else {

			    								}
			    								
			    							}
			    						
			    						}
    							       
    								}
    							}

    							if (is_object($v2)) {
    							
    								foreach ($v2 as $k3 => $v3) {
    									
			    						if (is_string($v3)) {
			    							 $html.="<input class='form-control' type='".$visibility."' data-input name='count_".$counter."_".$key."_".$k."_".$k2."_".$k3."' value='".$v3."'>";
			    						} else {
			    							
			    							foreach ($v3 as $v4) {
			    								if (is_string($v4)) {
			    									$html.="<input class='form-control' type='".$visibility."' data-input name='count_".$counter."_".$key."_".$k."_".$k2."_".$k3."' value='".$v4."'>";
			    								} else {

			    								}
			    								
			    							}
			    						
			    						}
    							       
    								}
    							}
    						}
    					}
    				}
    				break;
    		case 'object':

    				foreach ($value as $k => $v) {
    					

    					if (is_string($v) || is_int($v) || is_bool($v)) {
    						
    						$html.="<input class='form-control' type='".$visibility."' data-input name='count_".$counter."_".$key."_".$k."' value='".$v."'>";
    					}

    					if (is_array($v) || is_object($v)) {


    						foreach ($v as $k2 => $v2) {
                  
    							if (is_string($v2) || is_int($v2) || is_bool($v2)) {
    								

                
    							    $html.="<input class='form-control' type='".$visibility."' data-input name='count_".$counter."_".$key."_".$k."_".$k2."' value='".$v2."'>";
    							} 
                 

    							if (is_array($v2)) {
    								//item specific
    								foreach ($v2 as $k3 => $v3) {

			    						if (is_string($v3)) {
			    							 $html.="<input class='form-control' type='".$visibility."' data-input name='count_".$counter."_".$key."_".$k."_".$k2."_".$k3."' value='".$v3."'>";
			    						} else {
			    							
			    							foreach ($v3 as $v4) {

			    								if (is_string($v4)) {
			    									$html.="<input class='form-control' type='".$visibility."' data-input name='count_".$counter."_".$key."_".$k."_".$k2."_".$k3."' value='".$v4."'>";
			    								} else {

			    								}
			    								
			    							}
			    						
			    						}
    							       
    								}
    							}

    							if (is_object($v2)) {
    							
                 
    								foreach ($v2 as $k3 => $v3) {
    									
			    						if (is_string($v3) || is_int($v3) || is_bool($v3)) {

                       
			    							 $html="<input class='form-control' type='".$visibility."' data-input name='count_".$counter."_".$key."_".$k."_".$k2."_".$k3."' value='".trim($v3)."'>";
                         
			    						} else {

			    							if ($v3 != null) {


                          foreach ($v3 as $v4) {
                            if (is_string($v4)) {
                              $html.="<input class='form-control' type='".$visibility."' data-input name='count_".$counter."_".$key."_".$k."_".$k2."_".$k3."' value='".$v4."'>";
                            } else {

                            }
                            
                          }


                        }
			    						
			    						}
    							       
    								}

                      

    							}
    						}
    					}
    				}
    				break;

    				
    			default:
    				# code...
    				break;
    		}
    	}

    	return $html;
    }
}
