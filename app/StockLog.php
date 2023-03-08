<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StockLog extends Model
{
    protected $table = 'stock_log';
    public $timestamps = false;





    public function getdono($table, $id) {

         $output = '';
         //$do_no = '';

         if ($table == 'stock_in')

              $output = DB::table('stock_in')->select('do_no')->where('id', $id)->first();

         if ($table == 'grn')

               $do_no = DB::table('grn AS t1')->select('t2.doc_no')
                            ->leftjoin('delivery_order AS t2', 't1.do_id', '=', 't2.id')
                            ->where('t1.id',$id)->get();

              foreach($do_no as $data)
                        $value = $data->doc_no;
                        $output =$value;


         if ($table == 'purchasereturn_item')

          $output = DB::table('purchasereturn_item AS t1')->select('t2.ref')
                        ->leftjoin('purchasereturn AS t2', 't1.doc_id', '=', 't2.id')
                        ->where('t1.id',$id)->first();

        return $output;
    }


    public function getdono2($table, $id)
    {
       $output = '';

       if ($table == 'delivery_order_item')

        $do_no = DB::table('delivery_order_item AS t1')->select('t2.doc_no')
                      ->leftjoin('delivery_order AS t2', 't1.doc_id', '=', 't2.id')
                      ->where('t1.id',$id)->get();

        foreach($do_no as $data)
              $value = $data->doc_no;
              $output =$value;

        return $output;

    }



    public static function balancestockbyowner($product_id, $owner_type = '', $owner_id = '', $startdt = '', $enddt = '') {

        $sqlExt = "";
        if (!empty($owner_type))
            $sqlExt .= " AND owner_type='$owner_type'";

        if (!empty($owner_id))
            $sqlExt .= " AND owner_id='$owner_id'";

        //FORMULA = (stockin + grn_resale_qty) - (purchase_return + debit_note + delivery_order + stock adjustment + cash sales invoice) - online sales (sales from memberv2)

        $sqlstockin = "SELECT SUM(quantity) FROM stock_in WHERE product_id='$product_id'" . $sqlExt;
        if (!empty($enddt))
            $sqlstockin.= " AND (DATE(created_dt)<'$enddt' OR DATE(date_arrived)<'$enddt')";
        $stockin = (int) DB::select($sqlstockin);
                //  Yii::$app->db->createCommand($sqlstockin)->queryScalar();

        $sqlreturn = "SELECT SUM(quantity) FROM grn WHERE product_id='$product_id' AND status_inspection='checked'" . $sqlExt;
        if (!empty($enddt))
            $sqlreturn.= " AND (DATE(created_dt)<'$enddt' OR DATE(received_dt)<'$enddt')";
        $return = (int) DB::select($sqlreturn);


        $sqlpurchasereturn = "SELECT SUM(quantity) FROM purchasereturn_item WHERE product_id='$product_id'" . $sqlExt;
        if (!empty($enddt))
            $sqlpurchasereturn.= " AND doc_id IN (SELECT id FROM purchasereturn WHERE DATE(doc_date)<'$enddt')";
        $purchasereturn = (int)DB::select($sqlpurchasereturn);


        $sqldebitnote = "SELECT SUM(quantity) FROM debitnote_item WHERE product_id='$product_id'" . $sqlExt;
        if (!empty($enddt))
            $sqldebitnote.= " AND doc_id IN (SELECT id FROM debitnote WHERE DATE(doc_dt)<'$enddt')";
        $debitnote = (int)DB::select($sqldebitnote);


        $sqldeliveryorder = "SELECT SUM(quantity) FROM delivery_order_item WHERE product_id='$product_id'" . $sqlExt;
        if (!empty($enddt))
            $sqldeliveryorder.= " AND doc_id IN (SELECT id FROM delivery_order WHERE DATE(doc_dt)<'$enddt')";
        $deliveryorder = (int) DB::select($sqldeliveryorder);


        $sqlstockout = "SELECT SUM(quantity) FROM stock_out WHERE product_id='$product_id'" . $sqlExt;
        if (!empty($enddt))
            $sqlstockout.= " AND DATE(created_dt)<'$enddt'";
        $stockout = (int) DB::select($sqlstockout);


        $sqlinvoice = "SELECT SUM(quantity) FROM invoice_item WHERE descr='$product_id'" . $sqlExt;
        if (!empty($enddt))
            $sqlinvoice.= " AND doc_id IN (SELECT id FROM invoice WHERE DATE(doc_dt)<'$enddt' AND deleted_status=0)";
        $cashsalesinvoice = (int) DB::select($sqlinvoice);


        $sqlsales = "SELECT SUM(quantity) FROM orders WHERE product_id='$product_id' AND order_status='completed'" . $sqlExt;
        if (!empty($enddt))
            $sqlsales.= " AND DATE(order_date)<'$enddt'";
        $onlinesales = (int) DB::select($sqlsales);


        $balance = ($stockin + $return) - ($purchasereturn + $debitnote + $deliveryorder + $stockout + $cashsalesinvoice) - $onlinesales;

        return $balance;
    }





}
