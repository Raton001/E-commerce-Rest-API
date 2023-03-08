<?php

namespace App\Jobs;
use Illuminate\Support\Facades\Redis;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
 use \App\Http\Controllers\ShopeeController;

use Illuminate\Support\Facades\Mail;
use App\Mail\OrderShipped;
use App\Order;
use Log;
use App\Events\OrdersEvent;


class SendOrderEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $selling;
    public $order;

    public $user;

    public function __construct($url, $partner_id, $key, $shopid, $req_body, $account)
    {
       $this->url = $url;
       $this->partner_id = $partner_id;
       $this->key = $key;
       $this->shopid =  $shopid;
       $this->req_body = $req_body;
       $this->orders_more = 1;
       $this->account = $account;

    }

    private function callAgain()
    {
         static $count = 0;
         $count++;
        
         $orders = ShopeeController::getOrdersTest(
                $this->url, 
                $this->partner_id, 
                $this->key, 
                $this->shopid, 
                $this->req_body,
                $this->account);

           
                $this->orders_more = $orders['more'];
                
                return $orders;

                // $this->displayContent($orders['data'], $count);
                
                
                

               
    }

    private function displayContent($orders, $count)
    {
       $output = [];
       $keys = array_keys($orders);
        $i = 0;
        foreach ($keys as $key => $value) {
            $i++;
           
                foreach ($orders[$value] as $k => $v) {
                    $output[$value][] = [$i, $v->ordersn];
                 
                }
                
        }

        return  $output;


    }

    public function handle()
    {
     
   
        // Allow only 2 emails every 1 second
        Redis::throttle('any_key')->allow(2)->every(1)->then(function () {

    


                $data = $this->callAgain();

            event(new OrdersEvent('data', $data['data']));
            if ($data['more']) {
            Log::info('here');

                $this->handle();


            }

            // event(new DashboardEvent('pinky', $data, $this->user));

        }, function () {
            // Could not obtain lock; this job will be re-queued
            return $this->release(2);
        });

    }
}
