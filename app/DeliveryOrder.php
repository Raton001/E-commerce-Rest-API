<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveryOrder extends Model
{
    protected $table = 'delivery_order';
    public $timestamps = false;


    public function getcompanyid($id) {
        $output = '';
        if (($model = static::find($id)) !== null) {
            $output = $model->company_id;
        }
        return $output;
    }

    public function getdocno($id) {
        $output = '';
        if (($model = static::find($id)) !== null) {
            $output = $model->doc_no;
        }
        return $output;
    }

}
