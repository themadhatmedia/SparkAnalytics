<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LoginDetails;
use App\Models\User;
use App\Models\Utility;
use File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Mail\CommonEmailTemplate;
use App\Models\location;
use App\Models\LocationSetting;
use Illuminate\Support\Facades\Mail;
use App\Models\Plan;
use App\Models\Order;
use \Carbon\Carbon;
use GeoIp2\Database\Reader;
use Jenssegers\Agent\Facades\Agent;
use Illuminate\Support\Facades\DB;

class UserlogController extends Controller
{

    public function index(Request $request)
    {
        $time = date_create($request->month);
        $firstDayofMOnth = (date_format($time, 'Y-m-d'));
        $lastDayofMonth =    \Carbon\Carbon::parse($request->month)->endOfMonth()->toDateString();

        $objUser =Auth::user();

        $usersList = User::where('created_by', '=', $objUser->creatorId())
                    ->whereNotIn('user_type' , ['super admin' , 'company'])->get()->pluck('name', 'id')->prepend('All','');
        
        
        if($request->month == null){
            $users = DB::table('login_details')
            ->join('users', 'login_details.user_id', '=', 'users.id')
            ->select(DB::raw('login_details.*, users.name as user_name , users.user_type as type'))
                    ->where(['login_details.created_by' => $objUser->id])
                    ->whereMonth('date', date('m'))->whereYear('date', date('Y'));
        }
        else
        {
            $users = DB::table('login_details')
            ->join('users', 'login_details.user_id', '=', 'users.id')
            ->select(DB::raw('login_details.*, users.name as user_name , users.user_type as type'))
            ->where(['login_details.created_by' => $objUser->id]);
        }

        if(!empty($request->month))
        {
            $users->where('date', '>=', $firstDayofMOnth);
            $users->where('date', '<=', $lastDayofMonth);
        }

        if(!empty($request->user))
        {
            $users->where(['user_id'  => $request->user]); 
        }
        $users = $users->get();

        return view('user_log.index',compact('users'  , 'usersList'));
    }



    public function view($id)
    {
        $users = LoginDetails::find($id);
        return view('user_log.view' , compact('users'));
    }


    public function destroy($id)
    {
        $user = LoginDetails::find($id);
        if ($user) {
            
                $user->delete();
           
            return redirect()->back()->with('success', __('User Logs successfully deleted .'));
        } else {
            return redirect()->back()->with('error', __('Something is wrong.'));
        }
    }

    public function getlogDetail($id)
    {
        $objUser = \Auth::user();

        $users = DB::table('login_details')
        ->join('users', 'login_details.user_id', '=', 'users.id')
        ->select(DB::raw('login_details.*, users.name as user_name , users.email as user_email'))
        ->where(['login_details.user_id' => $id])->get();
        
        $usersList = User::where('created_by', '=', $objUser->creatorId())
                        ->whereNotIn('user_type' , ['super admin' , 'company'])->get()->pluck('name', 'id');

        return view('user_log.index', compact('users' , 'usersList'));
    }
}