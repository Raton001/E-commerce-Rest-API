<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'log';

    public $timestamps = false;


    public function insertlog($menu_id, $action_type, $data_uuid) {
        $model = new Log();
        $model->user_id = 38; //should be Auth user id
        $model->action_datetime = date("Y-m-d H:i:s");
        $model->menu_id = $menu_id;
        $model->action_type = $action_type;
        $model->data_uuid = $data_uuid;
        $model->save();
    }


    public function gettypedesc($type) {
        $output = '';
        if ($type == 'create')
            $output = 'created';
        if ($type == 'update')
            $output = 'updated';
        if ($type == 'status0')
            $output = 'change status to inactive';
        if ($type == 'status1')
            $output = 'change status to active';
        if ($type == 'delete')
            $output = 'deleted';
        return $output;
    }


}
