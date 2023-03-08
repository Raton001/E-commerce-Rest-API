<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use App\Menu;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ProfileController extends Controller
{

    public function addMenu()
    {
        $parent_down = Menu::where('status',1)->get();
        $users = User::get();
        return view('menu-add', ['menu'=>$this->menu()], compact('parent_down', 'users'));
    }

    public function saveMenu(Request $request)
    {
        $validationRule = [
            'name' => 'required|unique:menus',
            'url' => 'required',
            'parent_menu' => 'required',
            'user_role' => 'required'
        ];

        $validationMessage = [
            'name.required' => 'Field Name is required',
            'url.required' => 'Field URL is required',
            'parent_menu.required' => 'Field Parent Menu is required',
            'user_role.required' => 'Field User Role is required',
            'name.unique' => 'The Name is Exist',
        ];

        $this->validate($request, $validationRule, $validationMessage);
        $menus = new Menu;

        $menus->name = $request->name;
        $menus->url = $request->url;
        $menus->parent = $request->parent_menu;
        $menus->position = "left";
        $menus->status = 1;
        $menus->created_by = Auth::id();
        $menus->user_role = implode(",", $request->user_role);
        
        if(isset($request->user_id)){
            $menus->user_id = $request->user_id;
        }
        try{
            $menus->save();
            return redirect()->back()->with('success', 'New Menu successfully created!');   
        }catch (\Exception $e){
            \Log::error($e);
            echo "<pre>";
            var_dump($e);exit;
            return  redirect()->back()->with('incomplete', 'The form is not completely filled!')->withInput($request->all);
        }
        
    }


    public function addGroup()
    {
        $parent_down = \App\Group::where('status',1)->get();
        $users = $this->makeCurl("users");
        return view('group-add', ['menu'=>$this->menu()], compact('parent_down', 'users'));
    }

    public function saveGroup(Request $request)
    {
        $validationRule = [
            'name' => 'required',
            'parent_menu' => 'required'
        ];

        $validationMessage = [
            'name.required' => 'Field Name is required',
            'parent_menu.required' => 'Field Parent Menu is required',
            'name.unique' => 'The Name is Exist',
        ];

        $this->validate($request, $validationRule, $validationMessage);
        $menus = new \App\Group;

        $menus->name = $request->name;
        // $menus->url = $request->url;
        $menus->parent = $request->parent_menu;
        // $menus->position = "left";
        $menus->status = 1;
        $menus->created_by = Auth::id();
        
        if(isset($request->user_id)){
            $menus->user_total = sizeof($request->user_id);
        }
        try{
            $menus->save();

            if ($menus->id) {

                //add users
                $users = $request->user_id;
                foreach ($users as $key => $user) {
                    $group = new \App\GroupUser;

                    $group->group_id = $menus->id;
                    $group->user_id = $user;
                    $group->status = 1;
                    $group->save();
                }
            }
            return redirect()->back()->with('success', 'New Group successfully created!');   
        }catch (\Exception $e){
            \Log::error($e);
            echo "<pre>";
            var_dump($e);exit;
            return  redirect()->back()->with('incomplete', 'The form is not completely filled!')->withInput($request->all);
        }
        
    }

    public function groups()
    {
        ob_start();
        $this->showGroupTree(0);
        $groupHtml = ob_get_contents();
        ob_end_clean();
        return $groupHtml;

       

        return view('groups', ['groups'=>$groupHtml]);
    }

    public function group(Request $request)
    {
        $id = $request->route('id');


        $group = \App\Group::where('id', $id)->get();
        $group = collect($group->toArray())->all();



        $ebxUsers = $this->makeCurl("users");
        $users = \App\GroupUser::where('group_id', $id)->get();
        $users = collect($users->toArray())->all();
       

        function myfunction($v)
        {
          return($v*$v);
        }

        $users = array_map(function($v) use ($ebxUsers) {
            if (in_array($v['id'], array_column($ebxUsers, 'id'))) {
                return $ebxUsers[array_search($v['id'], array_column($ebxUsers, 'id'))];
            }
          
        },$users);
   
        return view('group', ['group'=>$group, 'users'=>array_values(array_filter($users))]);
    }


}
