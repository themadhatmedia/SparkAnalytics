<?php

namespace App\Http\Controllers;

use App\Models\Payout;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReferralController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function index()
    {
        //Company
        if (\Auth::user()->user_type == 'company') {
            $myReferralCode = Auth::user()->referrance_code;

            $transactions = Transaction::all()->where('used_referrance', $myReferralCode);    

            $users = User::where('used_referrance', $myReferralCode)->get();

            $commission = Transaction::where('used_referrance', $myReferralCode)->sum('commission');
            $paidCommission = Payout::where('referrance_code', $myReferralCode)->get();
            $totalpaidCommission = Payout::where('referrance_code', $myReferralCode)->where('status', 'accept')->sum('amount');

            // $totalCommission = Transaction::where('used_referrance', $myReferralCode)
            //     ->sum('commission');

            return view('referral.index', compact( 'users', 'paidCommission', 'commission', 'transactions', 'totalpaidCommission'));
        }


        //Super Admin
        if (\Auth::user()->user_type == 'super admin') {
            $referralProgram = Utility::settings();
            $transactions = Transaction::all();
            $payouts = Payout::where('status', '=', NULL)->get();
            return view('referral.index', compact('referralProgram', 'transactions', 'payouts'));
        }
    }


    public function payoutstore(Request $request)
    {
        $user = \Auth::user();
        $referrance= Utility::settings();

        $commission = Transaction::where('uid', $user->id)->get();
        $total = count($commission);

        // Validate the incoming request data
        $validatedData = $request->validate([
            'amount' => 'required|numeric',
        ]);

        // Create a new payout instance
        $payout = new Payout();

        // Assign values from the request to the payout object
        $payout->company_id = $user->id;
        $payout->amount = $request->amount;
        $payout->referrance_code = $user->referrance_code;
        $payout->date = date('y-m-d');

        // Save the payout to the database
        $payout->save();

        // Redirect or return a response as needed
        return redirect()->route('referral')->with('success', 'Payout created successfully!');
    }

    public function savereferralSettings(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'plan_commission_rate' => 'required|string|max:255',
                'threshold_amount' => 'required|string|max:255',
                'guidelines' => 'required',
            ]
        );


        if ($validator->fails()) {
            $messages = "Fill the all filed";
            return redirect()->back()->with('error', $messages);
        }
        $post = $request->all();
        unset($post['_token']);
        if($request->referral_enable){
            $referral_enable = 'on';
        }else{
            $referral_enable = 'off';
        }
        $post['referral_enable']=$referral_enable;
        foreach ($post as $key => $data) {
            $arr = [
                $data,
                $key,
                Auth::user()->id,
            ];

            DB::insert(
                'insert into settings (`value`, `name`,`created_by`) values (?, ?, ?) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`) ',
                $arr
            );
        }



        return redirect()->back()->with('success', __('Referral Setting successfully updated.'));
    }

    public function storestatus(Request $request)
    {
        $user = Payout::find($request->id);
        $myReferralCode = Payout::where('id', $request->id)->value('referrance_code');
        $commission = Transaction::where('used_referrance', $myReferralCode)->sum('commission');
        $lessCommission = DB::table('payouts')->where('company_id', $user->company_id)->where('status', 'accept')->sum('amount');
        $aftercommissiomn = $commission - $lessCommission;

        // Validate the incoming request if needed
        $request->validate([
            'status' => 'in:accept,reject', // Validate the status field
        ]);
        $referral = Utility::settings();
        $amount = Payout::where('id', $request->id)->get('amount')->first();
        if ($request->status == 'accept') {
            if ($referral['threshold_amount'] <= $amount->amount && $aftercommissiomn >=  $amount->amount)  {

                $status = $request->status;
                $upd = Payout::where('id', $request->id)->update(['status' => $status]);

                // Redirect back or wherever you need to after storing the data
                if ($upd) {
                    return redirect()->back()->with('success', 'Status stored successfully.');
                } else {
                    return redirect()->back()->with('error', 'Status Not stored.');
                }
            } else {
                Payout::where('id', $request->id)->update(['status' => 'reject']);
                return redirect()->back()->with('error', 'Amount Is Invalid');
            }
        }

        $status = $request->status;
        $upd = Payout::where('id', $request->id)->update(['status' => $status]);

        // Redirect back or wherever you need to after storing the data
        if ($upd) {
            return redirect()->back()->with('success', 'Status stored successfully.');
        } else {
            return redirect()->back()->with('error', 'Status Not stored.');
        }
    }
}
