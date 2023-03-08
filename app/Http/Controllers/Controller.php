<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\User;

use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    use \App\Http\Controllers\HelperController;
    use \App\Http\Controllers\AuthMarketplaceController;

    public function __construct() {

    }

    public function menu()
    {
    	ob_start();
        $this->showCategoryTree(0);
        $menuHtml = ob_get_contents();
        ob_end_clean();
        return $menuHtml;
    }

    public function userRole()
    {
        $role = User::where('id', Auth::id())->get(['role']);
        $role = collect($role->toArray())->all();
        if (isset($role[0])) {
        return $role[0]['role'];

        }
    }
    public function policy($storename = false, $auth = false, $oauth = false)
    {
        $policies = [];
            if ($this->storeAccess()) {
                $store = $this->storeAccess()['policies'];

            if ($store != null) {
                foreach ($store as $key => $value) {
                    if (isset($storename)) {
                        if ($key == $storename) {
                            return array($key=>$value);
                        }
                    }

                    $policies[] = array($key=>$value);
                }
            }
        }

        return $policies;
    }

    public function defaultPolicy($storename = false, $auth = false, $oauth = false)
    {
        $policies = [];
            if ($this->storeAccess()) {
                $store = $this->storeAccess()['policies'];
            if ($store != null) {
                foreach ($store as $key => $value) {
                    if (isset($storename)) {
                        if ($key == $storename) {
                            $default = [];
                            foreach ($value['shipment'] as $k => $v) {

                                if ($v['default']) {
                                    $default['shipment'] = ['id'=>$k, 'name'=>$v['name']];

                                }

                            }

                            foreach ($value['payment'] as $k => $v) {

                                if ($v['default']) {
                                    $default['payment'] = ['id'=>$k, 'name'=>$v['name']];

                                }

                            }

                            foreach ($value['return'] as $k => $v) {

                                if ($v['default']) {
                                    $default['return'] = ['id'=>$k, 'name'=>$v['name']];

                                }

                            }

                            return $default;

                        }
                    }

                    $policies[] = array($key=>$value);
                }
            }
        }

        return $policies;
    }

    public function summary($store = false)
    {
        $summary = [];
        // if (Cache::store('redis')->get('summary') === null) {

         $tokens = $this->token();

          foreach ($tokens as $key => $token) {
            foreach ($token as $k => $v) {
              $summary[$v['account']] = $this->fireXmlApi('GetMyeBaySelling',
                ['SellingSummary'=>['Include'=>'true'],
                'ActiveList'=>['Include'=>'false'],
                'UnsoldList'=>['Include'=>'false'],
                'SoldList'=>['Include'=>'false',
                'Pagination'=>[
                                'EntriesPerPage'=>10,
                                'PageNumber'=>1
                              ]],'DetailLevel'=>'ReturnSummary'], 1131, true, $v['authnauth_token']);
            }
          }

          // Cache::store('redis')->put('summary', $summary, $this->validity);

        // } else {

        //  $summary = Cache::store('redis')->get('summary');
        // }

        if ($store) {
            return $summary[$store];
        }

        return $summary;

    }

    public function stores($method = false)
    {
        $stores = $this->storeAccess();

        if ($stores != null) {
            if ($method) {

                $output = [];
                foreach ($stores['token'] as $key => $store) {

                    if ($method == $store['method']) {
                        if (isset($store['shopname'])) {

                        $output[] = array(
                            'id'=>$store['account'],
                            'name'=>$store['shopname']
                        );

                        } else {

                        $output[] = $store['account'];

                        }

                    }

                }

                return $output;
               }


        if (array_column($stores['token'], 'shopname')) {
            return [
                'id'=>array_column($stores['token'], 'account'),
                'name'=>array_column($stores['token'], 'shopname')
            ];
        }

                return array_column($stores['token'], 'account');
        }

    }

    private function getMarketplace()
    {
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

      return $marketplaceID;
    }

    public static function shopName($shopID)
    {
        return $this->getShopName($shopID);
    }

    protected function storeAccess($marketplace = false, $clearChache = false)
    {
        @session_start();

        if ($clearChache) {
          session_destroy();

        }


        $this->ebay = array();
        if (!$marketplace) {
            $marketplace = $this->getMarketplace();

        }

        if (!isset($_SESSION['access'][$marketplace])) {

            $token = $this->getUserAuth(0, $marketplace);

               if ($token != null) {

                $_SESSION['access'][$marketplace] = array(
                    'token'=>$token->accesstoken['authnauth'],
                    'policies'=>$token->policies
                );
               }

        }
        if (isset($_SESSION['access'][$marketplace])) {

        return $_SESSION['access'][$marketplace];

        }

    }

    public function token($storename = false, $auth = false, $oauth = false)
    {
        $tokens = [];

        if ($this->storeAccess()) {


        $store = $this->storeAccess()['token'];


        if ($store != null) {

        foreach ($store as $key => $value) {

            if (!$storename) {

                $tokens[] = array($key=>$value);
            }

            if ($value['account'] == $storename) {

                return $value;
            }
        }
        }

        }
        return $tokens;
    }

    public function tokens($storename = false, $auth = false, $oauth = false)
    {
        $tokens = [];
        $store = $this->storeAccess()['token'];

        if ($store != null) {

        foreach ($store as $key => $value) {

            if (!$storename) {

                $tokens[$key] = $value;
            }

            if ($value['account'] == $storename) {

                return $value;
            }
        }
        }


        return $tokens;
    }
}
