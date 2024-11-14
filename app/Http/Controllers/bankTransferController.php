<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\Plan;
use App\Models\User;
use App\Models\UserCoupon;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\File;


class bankTransferController extends Controller
{
    public $currancy;
    public $currancy_symbol;

    public function status(Request $request)
    {
       
        $this->planpaymentSetting();

        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

        $objUser = \Auth::user();
        $planID  = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan    = Plan::find($planID);
        $frequency = $request->stripe_payment_frequency;

        $order = Order::where('plan_id' , $plan->id)->where('payment_status' , 'Pending')->where('user_id',$objUser->id)->first();
        if($order){
            return redirect()->route('plans')->with('error', __('You already send Payment request to this plan.'));
        }
        
        if ($plan) {
           
            $price = (float)$plan->{$request->stripe_payment_frequency . '_price'};

                if (!empty($request->coupon)) {
                    $coupons = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                    if (!empty($coupons)) {
                        $usedCoupun     = $coupons->used_coupon();
                        $discount_value = ($plan->{$request->stripe_payment_frequency . '_price'} / 100) * $coupons->discount;
                        $price          = $plan->{$request->stripe_payment_frequency . '_price'} - $discount_value;


                        $userCoupon         = new UserCoupon();
                        $userCoupon->user   = $objUser->id;
                        $userCoupon->coupon = $coupons->id;
                        $userCoupon->order  = $orderID;
                        $userCoupon->save();
    
                        $usedCoupun = $coupons->used_coupon();
                        if($coupons->limit <= $usedCoupun)
                        {
                            $coupons->is_active = 0;
                            $coupons->save();
                        }


                        if ($coupons->limit == $usedCoupun) {
                            return redirect()->back()->with('error', __('This coupon code has expired.'));
                        }
                    } else {
                        return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                    }
                }
               
                if ($request->payment_receipt) {
                   
                $request->validate(
                    [
                        'payment_receipt' => 'image|max:20480',
                        ]
                    );
                    
                    $validation = [
                        'max:' . '20480',
                    ];
                    
                //     $dir       = storage_path() . '/receipt' ;
                // if (!is_dir($dir)) {
                //     File::makeDirectory($dir, $mode = 0777, true, true);
                // }
                
                $dir = 'receipt/';
                $filenameWithExt = $request->file('payment_receipt')->getClientOriginalName();
                $path = Utility::upload_file($request, 'payment_receipt', $filenameWithExt, $dir, $validation);
                    if ($path['flag'] == 1) {
                        $payment_receipt = $path['url'];
                    } else {
                        return redirect()->back()->with('error', __($path['msg']));
                    }
                   
                    Order::create(
                        [
                            'order_id' => $orderID,
                            'name' => $request->name,
                            'card_number' => '',
                            'card_exp_month' => '',
                            'card_exp_year' => '',
                            'plan_name' => $plan->name,
                            'plan_id' => $plan->id,
                            'price' => $price==null?0:$price,
                            'price_currency' => $this->currancy,
                            'txn_id' => '',
                            'payment_type' => __('Bank Transfer'),
                            'payment_status' => 'Pending',
                            'payment_frequency'=>$request->stripe_payment_frequency,
                            'receipt' => $payment_receipt,
                            'user_id' => $objUser->id,
                            ]
                        );
                    } 

            return redirect()->route('plans')->with('success',__('Plan payment request send successfully'));
    }

    }

    public function edit($id)
    {
        $this->planpaymentSetting();

        $order = Order::where('id', $id)->first();
        $bank_details = $this->bank_details;

        return view('order.edit',compact('order' , 'bank_details'));

    }

    public function acceptRequest($id, $response , $frequency)
    {

        $order = Order::where('id',$id)->first();

        if(!empty($order))
        {
            $user = User::find($order->user_id);
            $plan = Plan::find($order->plan_id);

            if($response == 1)
            {
                $assignPlan = $this->assignPlan($plan->id,$user->id, $order->payment_frequency);


                $order->payment_status  = 'success';
                $order->save();

                return redirect()->back()->with('success', __('Plan successfully upgraded.'));
            }
            else
            {
                $order->payment_status  = 'Rejected';
                $order->save();

                return redirect()->back()->with('success', __('Request Rejected Successfully.'));
            }
        }
    }


    public function destroy($id)
    {

        $order = Order::where('id', $id)->first();

        if($order)
        {
            $order->delete();

        }
        return redirect()->back()->with('success', __('Order Successfully Deleted'));

    }

    public function planpaymentSetting()
    {


        $admin_payment_setting = Utility::getAdminPaymentSetting();

        $this->currancy_symbol = isset($admin_payment_setting['currency_symbol']) ? $admin_payment_setting['currency_symbol'] : '';
        $this->currancy = isset($admin_payment_setting['currency']) ? $admin_payment_setting['currency'] : '';

        $this->bank_details = isset($admin_payment_setting['bank_details']) ? $admin_payment_setting['bank_details'] : '';
       
    }

    public function frequency()
    {
        $frequency = $request->stripe_payment_frequency;
       
    }
}
?>