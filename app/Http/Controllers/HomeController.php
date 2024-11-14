<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Settings;
use App\Models\User;
use App\Models\Utility;
use App\Models\Site;
use App\Models\Credintials;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\AdminPaymentSettings;

use Artisan;
use DB;
use App\Models\Plan;
use Illuminate\Support\Facades\Auth;
class HomeController extends Controller
{


    public function dashboard()
    {


        $user=Auth::user();

        if((\Auth::user()->user_type == 'super admin'))
        {
            $currency=AdminPaymentSettings::where('name','currency_symbol')->first();
            if($currency)
            {
                $currency=$currency->value;
            }
            else
            {
                $currency="";
            }

            $mostPlans        = Plan::where('id', function ($query) {
                    $query->select('plan_id')->from('Orders')->groupBy('plan_id')->orderBy(\DB::raw('COUNT(plan_id)'))->limit(1);
                })->first();
            $totalOrderAmount = Order::sum('price');
            $chartData = $this->getOrderChart(['duration' => 'week']);
            $totalPaidUsers   = User::where('user_type', 'company')->where('plan', '!=', '1')->where('plan', '!=', '0')->count();
            $data=User::where('created_by' ,Auth::user()->id)->count();
            $plan_data=Plan::all();
            $plan=count($plan_data);
            $Order_data=Order::all();
            $Order=count($Order_data);
            $Coupon_data=Coupon::all();
            $Coupon=count($Coupon_data);
            return view('admin.dashboard')->with('data',$data)->with('plan',$plan)->with('Order',$Order)->with('Coupon',$Coupon)->with('chartData',$chartData)->with('totalPaidUsers',$totalPaidUsers)->with('totalOrderAmount',$totalOrderAmount)->with('mostPlans',$mostPlans)->with('currency',$currency);
        }
        elseif((\Auth::user()->user_type == 'company'))
        {
            $site=Site::where('created_by',$user->id)->get();
            return view('admin.dashboard')->with('site',$site);
        }
        else
        {
            $site=Site::where('created_by',$user->created_by)->get();
             return view('admin.dashboard')->with('site',$site);
        }

    }
    public function landingPage()
    {

        if (!file_exists(storage_path() . "/installed")) {
            header('location:install');
            die;
         }

        $setting = Utility::settings();
        if ($setting['display_landing'] == 'on' && \Schema::hasTable('landing_page_settings')) {

            // return view('layouts.landing');
            return view('landingpage::layouts.landingpage',compact('setting'));
        } else {
            return redirect('login');
        }
    }
     public function getOrderChart($arrParam)
    {
        $arrDuration = [];
        if ($arrParam['duration']) {
            if ($arrParam['duration'] == 'week') {
                $previous_week = strtotime("-1 week +1 day");
                for ($i = 0; $i < 7; $i++) {
                    $arrDuration[date('Y-m-d', $previous_week)] = date('d-m', $previous_week);
                    $previous_week = strtotime(date('Y-m-d', $previous_week) . " +1 day");
                }
            }
        }
        $arrTask = [];
        $arrTask['label'] = [];
        $arrTask['data'] = [];
        foreach ($arrDuration as $date => $label) {
            $data = Order::select(\DB::raw('count(*) as total'))->whereDate('created_at', '=', $date)->first();
            $arrTask['label'][] = $label;
            $arrTask['data'][] = $data->total;
        }


        return $arrTask;
    }
    public function save_json(Request $request)
    {

         $user= Auth::user();
         if($user->user_type=="company")
         {
            if($request->has('json_file'))
            {
                $store=Credintials::where('user_id',$user->id)->first();
                if($store)
                {
                    $store=Credintials::where('id',$store->id)->first();
                }
                else
                {
                    $store=new Credintials();

                }
                $store->user_id=$user->id;
                $store->json=$request->file('json_file')->getContent();
                $store->save();
                if($store)
                {
                    $user->is_json_upload=1;
                    $user->save();
                    $sub_user=User::where('created_by',$user->id)->update(['is_json_upload' =>1]);
                    return redirect()->route('dashboard')->with('success', __('Your Credintials has been saved'));
                }
                else
                {
                    return redirect()->back()->with('error', __('Something want worng.'));
                }
            }
            else
            {
                 return redirect()->back()->with('error', __('Something want worng.'));
            }


         }
         else
        {
             return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

}
