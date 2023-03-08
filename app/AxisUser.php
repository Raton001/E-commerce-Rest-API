<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AxisUser extends Model
{
    protected $table = 'axis_user';
    public $timestamps = false;


    public function getuserdetails($id) {
        $ParamList = new ParamList();
        $output = '';
        if (($model = static::find(['id' => $id])) !== null) {
            $output = $model->name . ' (' . $ParamList->getlabel(2, $model->role) . ')';
        }
        return $output;
    }


    public function getusername($id) {
        if (($model = static::find($id)) !== null) {
            return $model->name;
        } else {
            return '';
        }
    }

    public function getuserphone($id) {
        if (($model = static::find($id)) !== null) {
            return '+601' . $model->phone;
        } else {
            return '';
        }
    }




}
