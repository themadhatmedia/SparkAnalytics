<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{

    public function index(){

        if(\Auth::user()->can('manage role'))
        {
            $roles = Role::where('created_by', '=', \Auth::user()->creatorId())->get();

            return view('role.index')->with('roles', $roles);
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }


    public function create(){


        if(\Auth::user()->can('create role'))
        {

            $user = \Auth::user();
            if($user->type == 'super admin')
            {
                $permissions = Permission::all()->pluck('name', 'id')->toArray();
            }
            else
            {
                $permissions = new Collection();
                foreach($user->roles as $role)
                {
                    $permissions = $permissions->merge($role->permissions);
                }
                $permissions = $permissions->pluck('name', 'id')->toArray();
            }

            return view('role.create', ['permissions' => $permissions]);
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }

    }


    public function store(Request $request){

        
        
        if(\Auth::user()->can('create role'))
        {
            
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:100|unique:roles,name,NULL,id,created_by,' . \Auth::user()->creatorId(),
                'permissions' => 'required',
            ]);

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
                
                return redirect()->back()->with('error', $messages->first());
            }
            
            $name             = $request['name'];
            $role             = new Role();
            $role->name       = $name;
            $role->created_by = \Auth::user()->creatorId();
            
            $permissions      = $request['permissions'];
            $role->save();

            foreach($permissions as $permission)
            {
                $p = Permission::where('id', '=', $permission)->firstOrFail();
                $role->givePermissionTo($p);
            }

            return redirect()->route('roles.index')->with( 'success', __('Role successfully created.'), 'Role ' . $role->name . ' added!'
            );
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    
    public function edit($id){
        if(\Auth::user()->can('edit role'))
        {
          

            $role=Role::where('id',$id)->first();
            $user = \Auth::user();
            if($user->type == 'super admin')
            {
                $permissions = Permission::all()->pluck('name', 'id')->toArray();
            }
            else
            {

                $permissions = new Collection();
                foreach($user->roles as $role1)
                {
                    $permissions = $permissions->merge($role1->permissions);
                }
                $permissions = $permissions->pluck('name', 'id')->toArray();
            }


            return view('role.edit', compact('role', 'permissions'));
        }
        else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }


    }


    public function update(Request $request, $id){

        if(\Auth::user()->can('edit role'))
        {
            
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:100|unique:roles,name,' .$id . ',id,created_by,' . \Auth::user()->creatorId(),
                'permissions' => 'required',
            ]);

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $input       = $request->except(['permissions']);
            $permissions = $request['permissions'];
            $role=Role::where('id',$id)->first();
            $role->fill($input)->save();

            $p_all = Permission::all();

            foreach($p_all as $p)
            {
                $role->revokePermissionTo($p);
            }

            foreach($permissions as $permission)
            {
                $p = Permission::where('id', '=', $permission)->firstOrFail();
                $role->givePermissionTo($p);
            }

            return redirect()->route('roles.index')->with('success',
                __('Role successfully updated.'), 'Role ' . $role->name . ' updated!'
            );
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }


    public function destroy($id){

        if(\Auth::user()->can('delete role'))
        {
            $role=Role::where('id',$id)->first();
            $role->delete();

            return redirect()->route('roles.index')->with(
                'success', __('Role successfully deleted.')
            );
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
