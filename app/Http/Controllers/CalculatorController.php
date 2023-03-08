<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

Trait CalculatorController
{
  
  	public function getConfig()
  	{
  		$data = \App\Calculator::where('status', 1)->get();
    	$config = collect($data->toArray())->all();

    	return $config;
  	}

    protected function getTotalFees($sellingPrice)
    {

      $config = $this->getConfig()[0];
    
      $listingFee = array(
        $config['ebay_no_store'],
        $config['ebay_starter'],
        $config['ebay_basic_auction_fixed_price'],
        $config['ebay_premium_auction'],
        $config['ebay_premium_fixed_price'],
        $config['ebay_anchor_auction'],
        $config['ebay_anchor_fixed_price'],
        $config['paypal_off_ebay']);
      
      //get the highest among the listing fee
      $listingFee = max($listingFee);

      //referal fee
      $referalFee = array(
        $config['ebay_referal_no_store'],
        $config['ebay_referal_any_store'],

      );
      $referalFee = ($sellingPrice*max($referalFee))/100;

      //marketplace tax collection
      $marketplaceTax = ($listingFee * $config['sst'])/100 + ($referalFee * $config['sst'])/100;

      // $paymentGatewayFee = round(($sellingPrice * $paypal) *2, 1) /2;
      $paymentGatewayFee = ($sellingPrice * $config['payment_gateway_fee']) / 100;

      $handlingFee = $config['handling_fee'];

  return round($listingFee + $referalFee + $marketplaceTax + $paymentGatewayFee + $handlingFee, 2, PHP_ROUND_HALF_UP);

    }
}
