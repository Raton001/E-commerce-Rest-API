<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveryOrderItem extends Model
{
    protected $table = 'delivery_order_item';
    public $timestamps = false;



   public static function getqtysend($doc_id, $product_id) {

        $totalsend = 0;
          //get quantity send
        if (($models = static::find(['doc_id' => $doc_id, 'product_id' => $product_id])) !== null) {
            foreach ($models as $model){
                $totalsend = $model->quantity;
            }

        }

        //get product already received
        $totalreturn = 0;
        $modelgrn = Grn::where(['do_id' => $doc_id])->where(['product_id' => $product_id])->get();
        if (!empty($modelgrn)) {
            foreach ($modelgrn as $rowgrn) {
                $totalreturn+=$rowgrn->quantity;
            }
        }
        $output = $totalsend - $totalreturn;
        return $output;
    }



    //onwer type
    public function getownertype($doc_id, $product_id) {
        $output = '';
        if (($models = static::find(['doc_id' => $doc_id, 'product_id' => $product_id])) !== null) {
          foreach($models as $model){
            $output = $model->owner_type;
          }

        }
        return $output;
    }

  //owner Id
    public function getownerid($doc_id, $product_id) {
        $output = '';
        if (($models = static::find(['doc_id' => $doc_id, 'product_id' => $product_id])) !== null) {
           foreach($models as $model){
            $output = $model->owner_id;
           }

        }
        return $output;
    }


}
