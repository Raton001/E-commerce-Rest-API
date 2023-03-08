<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AcademyController extends Controller
{

  public function main(Request $request)
  {
    if ($request->route('account'))
         {
             $account = $request->route('account');
         }

     return view('Academy.home', ['stores' => $this->stores() ,   'title' => 'Academy', 'form' => 'Analysis', 'skipHeader' => 1]);
  }


  public function uploadContent(Request $request)
  {

    if ($request->route('account'))
    {
        $account = $request->route('account');
    }
    return view('Academy.upload_content', ['stores' => $this->stores() ,   'title' => 'Uplaod Content', 'form' => 'Analysis', 'skipHeader' => 1]);

  }

}
