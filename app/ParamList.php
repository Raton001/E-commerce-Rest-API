<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ParamList extends Model
{
    protected $table = 'param_list';


    public function getlabel($cat_id, $code) {
        $output= '';
        if (($models = static::find(['cat_id' => $cat_id, 'code' => $code])) !== null) {
          foreach($models as $model){
                $output= $model->label;
            }
            return $output;
        }

    }




}
