<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Illuminate\Support\Facades\DB;

class CreateBundleShipmentRequestTest extends TestCase
{
    /**
     * Test bulk shipment request for shopee orders
     * @dataProvider accountProvider
     */
    public function testCreateBundleShipmentRequest($data)
    {

        $this->assertIsInt($data['userID']);

    }

     /**
     * Test bulk shipment request for shopee orders
     * @dataProvider accountProvider
     */
    public function testCreateBundleShipmentRequest2($data)
    {
        $this->assertIsString($data['account']);
        return $data;

    }

    /**
     * [testGetShipmentDetails2 description]
     * @dataProvider accountProvider
     */
   public function testGetShipmentDetails2($data)
   {

    $userID = $data['userID'];
    $stores = $data['account'];
    if (is_array($stores)) {
        $accounts = "'" . implode ( "', '", $stores ) . "'";
    } else {
      $accounts = "'" .$stores."'" ;
    }

     $query = "SELECT marketplace_id, account, axis_shop_id, axis_shop_name, axis_username, axis_user_id, sku_type, free_gift FROM ebays WHERE user_id = $userID AND account IN(".$accounts.")";

      $data = DB::select($query);
      $this->assertNotEmpty($data);
      return $data;
    }

    public function accountProvider()
    {
        return [[
        	['userID' =>11,
        	'account'=>'275920176'],
        ]];
    }
}
