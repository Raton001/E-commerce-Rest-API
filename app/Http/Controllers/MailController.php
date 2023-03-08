<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\SendOrderEmail;

use Illuminate\Support\Facades\Mail;
use App\Mail\OrderShipped;
use App\Order;
use Log;


class MailController extends Controller
{
    public function index() {

      $accounts = $this->token();
            $sellings = [];

            foreach ($accounts as $k => $account) {
              foreach ($account as $key => $value) {

                //active listing
                $sellings[$value['account']][] = $this->fireXmlApi('GetMyeBaySelling', 
                  ['SellingSummary'=>['Include'=>'true'], 
                  'ActiveList'=>['Include'=>'false'], 
                  'UnsoldList'=>['Include'=>'false'],
                  'SoldList'=>['Include'=>'false',
                  'Pagination'=>[
                                  'EntriesPerPage'=>1,
                                  'PageNumber'=>1
                                ]],'DetailLevel'=>'ReturnAll'], 1131, true, $value['authnauth_token']);

              }
            }
        SendOrderEmail::dispatchNow($sellings);

        Log::info('Dispatched order pink');
        return view('home2');

       
    }

    public function index2() {

      $accounts = $this->token();
            $sellings = [];

            foreach ($accounts as $k => $account) {
              foreach ($account as $key => $value) {

                //active listing
                $sellings[$value['account']][] = $this->fireXmlApi('GetMyeBaySelling', 
                  ['SellingSummary'=>['Include'=>'true'], 
                  'ActiveList'=>['Include'=>'false'], 
                  'UnsoldList'=>['Include'=>'false'],
                  'SoldList'=>['Include'=>'false',
                  'Pagination'=>[
                                  'EntriesPerPage'=>1,
                                  'PageNumber'=>1
                                ]],'DetailLevel'=>'ReturnAll'], 1131, true, $value['authnauth_token']);

              }
            }
        SendOrderEmail::dispatchNow($sellings);

        Log::info('Dispatched order pink');
        return view('home2');

       
    }
}