<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notes extends Model
{
    protected $table = 'notes';


    public function getnotes($type, $com_id) {
        $output = '';
        $model =Notes::select('notes')->where('type',$type)->first();
        $modelcom = Company::find($com_id);
        if ($model != null && $modelcom != null) {
            $output = $model->notes;
            $output = str_replace("#COMPANY_NAME#", $modelcom->name, $output);
            $output = str_replace("#COMPANY_EMAIL#", $modelcom->email, $output);
            $output = str_replace("#COMPANY_BANK_NAME#", $modelcom->bank_name, $output);
            $output = str_replace("#COMPANY_BANK_ACCNO#", $modelcom->bank_acc, $output);
        }
        return $output;
    }
}
