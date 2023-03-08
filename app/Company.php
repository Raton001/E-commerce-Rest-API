<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Company extends Model
{
    protected $table = 'company';

    public function getname($id) {
        $output = '';
        if (($model = static::find($id)) !== null) {
            $output = $model->name;
        }
        return $output;
    }


    public function getshortcode($id) {
        $output = '';
        if (($model = static::find($id)) !== null) {
            $output = $model->short_code;
        }
        return $output;
    }

}
