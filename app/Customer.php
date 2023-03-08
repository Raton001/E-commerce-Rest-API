<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customer';

    public function getcustomerdetails($id) {
        $output = '';
        if (($model = static::find($id)) !== null) {
            $output = $model->name . '<->' .
                    $model->address1 . '<->' .
                    $model->address2 . '<->' .
                    $model->postcode . '<->' .
                    $model->city . '<->' .
                    $model->state . '<->' .
                    $model->contact_name . '<->' .
                    $model->contact_no;
                  }

        return $output;
    }


    public function getcustomertype($id) {
        $output = '';
        if (($model = static::find($id)) !== null) {
            $output = $model->type;
        }
        return $output;
    }


    public function getparentid($id) {
        $output = '';
        if (($model = static::find($id)) !== null) {
            $output = $model->parent_id;
        }
        return $output;
    }


}
