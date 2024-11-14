<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Models\Plan;
use App\Models\UserCoupon;
class CouponController extends Controller
{
    public function index()
    {
        if(\Auth::user()->can('manage coupon'))
        {
            if(\Auth::user()->user_type == 'super admin')
            {
                $coupons = Coupon::get();

                return view('admin.coupon.index', compact('coupons'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }


    public function create()
    {
        if(\Auth::user()->can('manage coupon'))
        {
            return view('coupon.create');
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

 	public function edit_coupon($id)
    {
    	$data=Coupon::where("id",$id)->first();
    	return $data;
    }
    public function save_coupon(Request $request)
    {
        if(\Auth::user()->can('manage coupon'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'name'     => 'required',
                                   'discount' => 'required|numeric',
                                   'limit'    => 'required|numeric',
                                   'code'     => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            if($request->coupon_id==0)
            {
            	$coupon= new Coupon();
            }
            else
            {
            	$coupon= Coupon::find($request->coupon_id);
            	if(!$coupon)
            	{
            		 return redirect()->back()->with('error', __('Coupon not found.'));
            	}

            }

            $coupon->name     = $request->name;
            $coupon->discount = $request->discount;
            $coupon->limit    = $request->limit;
            $coupon->code     = strtoupper($request->code);

            $coupon->save();

            return redirect()->route('coupon')->with('success', __('Coupon successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function show($id)
    {
        $userCoupons = UserCoupon::where('coupon',$id)->get();

        return view('admin.coupon.view', compact('userCoupons'));
    }


    public function edit(Coupon $coupon)
    {
        if(\Auth::user()->can('manage coupon'))
        {
            return view('coupon.edit', compact('coupon'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }




    public function destroy_coupon($id)
    {

        if(\Auth::user()->can('manage coupon'))
        {
        	$coupon=Coupon::where('id',$id)->first();
        	if($coupon)
        	{
        		$coupon->delete();
        		return redirect()->route('coupon')->with('success', __('Coupon successfully deleted.'));
        	}
        	else
        	{
        		return redirect()->back()->with('error', __('Something went wrong.'));
        	}
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function applyCoupon(Request $request)
    {
        $decrypt_id=decrypt($request->plan_id);
        $plan = Plan::find($decrypt_id);

        $frequency = $request->frequency;

        if($plan && $request->coupon != '')
        {

            $original_price = self::formatPrice((float)$plan->{$frequency . '_price'});

            $coupons = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();

            if(!empty($coupons))
            {
                $usedCoupun     = $coupons->used_coupon();
                if($coupons->limit == $usedCoupun)
                {
                    return response()->json(['is_success' => false, 'final_price' => $original_price, 'price' => number_format((float)$plan->{$frequency . '_price'}, 2), 'message' => __('This coupon code has expired.')]);
                }
                else
                {
                    $discount_value = ((float)$plan->{$frequency . '_price'} / 100) * $coupons->discount;
                    $plan_price     = (float)$plan->{$frequency . '_price'} - $discount_value;
                    $price          = self::formatPrice((float)$plan->{$frequency . '_price'} - $discount_value);
                    $discount_value =  '-' . self::formatPrice($discount_value);
                    return response()->json(['is_success' => true, 'discount_price' => $discount_value, 'final_price' => $price, 'price' => number_format($plan_price, 2), 'message' => __('Coupon code has applied successfully.')]);
                    // return response()->json(['is_success' => true, 'discount_price' => $discount_value, 'final_price' => (env('CURRENCY_SYMBOL') ? env('CURRENCY_SYMBOL') : '$') .$price, 'price' => number_format($plan_price, 2), 'message' => __('Coupon code has applied successfully.')]);
                }
            }
            else
            {
                return response()->json(['is_success' => false, 'final_price' => (env('CURRENCY_SYMBOL') ? env('CURRENCY_SYMBOL') : '$') .$original_price, 'price' => number_format((float)$plan->{$frequency . '_price'}, 2),'message' => __('This coupon code is invalid or has expired.')]);
            }
        }
    }

    public static function formatPrice($price) {
        return env('CURRENCY_SYMBOL') . number_format($price);
    }
}
