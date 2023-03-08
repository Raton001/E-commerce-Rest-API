<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'product';


    public function getuom($id) {
        $output = '';
        if (($model = static::find($id)) !== null) {
            $output = $model->uom;
        }
        return $output;
    }



    public function getprice($id) {
        $output = '';
        if (($model = static::find($id)) !== null) {
            $output = $model->selling_price;
        }
        return $output;
    }


    public static function getname($id) {
        $output = '';
        if (($model = static::find($id)) !== null) {
            $output = $model->name;
        }
        return $output;

    }



    public static function getvendor($id) {
        $output = '';
        if (($model = static::find($id)) !== null) {
            $output = $model->vendor_id;
        }
        return $output;
    }


    public static function getsku($id) {
        $output = '';
        if (($model = static::find($id)) !== null) {
            $output = $model->sku;
        }
        return $output;
    }


}
