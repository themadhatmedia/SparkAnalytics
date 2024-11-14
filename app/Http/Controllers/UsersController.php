<?php

namespace App\Http\Controllers;

use App\Mail\CommonEmailTemplate;
use App\Mail\SendUser;
use Illuminate\Http\Request;
use App\Models\Site;
use App\Models\User;
use App\Models\Plan;
use App\Models\Order;
use App\Models\Utility;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Session;
use Hash;
use Carbon\Carbon;
use Lab404\Impersonate\Models\Impersonate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class UsersController extends Controller
{
    public function CompnayInfo($id)
    {
        if (!empty($id)) {
            $data = $this->Counter($id);

            if ($data['is_success']) {
                $users_data = $data['response']['users_data'];
                return view('admin.user.userinfo', compact('id', 'users_data'));
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function Counter($id)
    {
        $response = [];
        if (!empty($id)) {
            $users = User::where('created_by', $id)->selectRaw('COUNT(*) as total_users, SUM(CASE WHEN user_status = 0 THEN 1 ELSE 0 END) as disable_users, SUM(CASE WHEN user_status = 1 THEN 1 ELSE 0 END) as active_users')->first();

            $users_data = [
                'owner_id' => $id,
                'total_users' => !empty($users->total_users) ? $users->total_users : 0,
                'disable_users' => !empty($users->disable_users) ? $users->disable_users : 0,
                'active_users' => !empty($users->active_users) ? $users->active_users : 0,
            ];

            $response['users_data'] = $users_data;

            return [
                'is_success' => true,
                'response' => $response,
            ];
        }
        return [
            'is_success' => false,
            'error' => 'Plan is deleted.',
        ];
    }


    public function UserUnable(Request $request)
    {
        if (!empty($request->id) && !empty($request->company_id)) {
            if ($request->name == 'user') {
                User::where('id', $request->id)->update(['user_status' => $request->is_disable]);
                $data = $this->Counter($request->company_id);
            }
            if ($data['is_success']) {
                $users_data = $data['response']['users_data'];
            }
            if ($request->is_disable == 1) {

                return response()->json(['success' => __('Successfully Unable.'), 'users_data' => $users_data]);
            } else {
                return response()->json(['success' => __('Successfull Disable.'), 'users_data' => $users_data]);
            }
        }
        return response()->json('error');
    }


    public function LoginWithAdmin(Request $request, User $user,  $id)
    {
        $user =    User::find($id);
        $from =     \Auth::user();
        if ($user && auth()->check()) {
            $manager = app('impersonate');
            $manager->take($from, $user);

            return redirect('dashboard');
        }
    }

    public function ExitAdmin(Request $request)
    {
        Auth::user()->leaveImpersonation($request->user());
        return redirect('dashboard');
    }

    public function index()
    {
        if (\Auth::user()->can('manage user')) {
            if (\Auth::user()->user_type == "super admin") {

                $data = Auth::user();
                $user = User::where('created_by', $data->id)->where('user_type', 'company')->get();
                foreach ($user as  $value) {
                    $site = Site::where('created_by', $value->id)->count();
                    if ($site) {
                        $value->site_count = $site;
                    } else {
                        $value->site_count = 0;
                    }
                    $plan = Plan::where('id', $value->plan)->first();
                    if ($plan) {
                        $value->plan_name = $plan->name;
                    } else {
                        $value->plan_name = "";
                    }
                }
                return view('admin.user.default')->with('user', $user);
            } else {
                if (\Auth::user()->user_type == "company") {
                    $data = Auth::user();

                    $user = User::where('created_by', $data->id)->get();

                    $role = Role::where('created_by', $data->id)->get();
                } else {
                    $data = Auth::user();

                    $user = User::where('created_by', $data->created_by)->where('id', '!=', $data->id)->get();

                    $role = Role::where('created_by', $data->created_by)->get();
                }

                return view('admin.user.default')->with('user', $user)->with('role', $role);
            }
        } else {
            return redirect()->route('dashboard')->with('error', __('Permission Denied.'));
        }
    }
    public function edit_user($id)
    {
        $data = User::where("id", $id)->first();
        $role = Role::where('name', $data->user_type)->first();
        if ($role) {
            $data->role_id = $role->id;
        } else {
            $data->role_id = 0;
        }

        return $data;
    }
    private function generateReferralCode()
    {

        $referrance_code = random_int(100000, 999999);
        while (User::where('referrance_code', $referrance_code)->exists()) {
            $referrance_code = random_int(100000, 999999); // Generate new referral code until unique
        }
        return $referrance_code;
    }
    public function save_user(Request $request)
    {
        $data = Auth::user();
        $referrance_code = $this->generateReferralCode();
        if (\Auth::user()->can('create user')) {
            if (\Auth::user()->user_type != "super admin") {
                $plan = Plan::where('id', $data->plan)->first();

                $expiryDate = Carbon::parse($data->plan_expire_date);
                $currentDate = Carbon::now();
                if (\Auth::user()->user_type != "company") {

                    $count = User::where('created_by', $data->created_by)->count();
                } else {
                    $count = User::where('created_by', $data->id)->count();
                }
                if ($plan) {
                    if ($currentDate->lt($expiryDate)) {
                        if ($plan->max_user <= $count) {
                            return redirect()->back()->with('error', __('Your User limit is over, Please upgrade plan.'));
                        }
                    } else {
                        return redirect()->back()->with('error', __('Your plan is expired. Please upgrade plan.'));
                    }
                } else {
                    return redirect()->back()->with('error', __('Default plan is deleted.'));
                }

                $validator = \Validator::make(
                    $request->all(),
                    [
                        'name' => 'required|max:120',
                        // 'email' => 'required|email|unique:users,email,NULL,id,created_by,' . \Auth::user()->creatorId(),
                        'email' => 'required|email|unique:users',
                        'role' => 'required',
                    ]
                );
            }
            if (\Auth::user()->user_type == "super admin") {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'name' => 'required|max:120',
                        'email' => 'required|email|unique:users,email,NULL,id,created_by,' . \Auth::user()->creatorId()

                    ]
                );
            }

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->route('users')->with('error', $messages->first());
            }
            if (!empty($request->password_switch) && $request->password_switch == 'on') {
                $validator = \Validator::make(
                    $request->all(),
                    ['password' => 'required|min:8']
                );

                if ($validator->fails()) {
                    return redirect()->back()->with('error', $validator->errors()->first());
                }
            }

            $userpassword = $request->input('password');
            $store = new User();
            $store->name = $request->get('name');
            $store->email = $request->get('email');
            $store->referrance_code = $referrance_code;
            $store->email_verified_at = Carbon::now();
            $store->is_login_enable = !empty($request->password_switch) && $request->password_switch == 'on' ? 1 : 0;
            $store->password =  !empty($userpassword) ? Hash::make($userpassword) : null;
            if (\Auth::user()->user_type == "super admin" || \Auth::user()->user_type == "company") {
                $store->created_by = $data->id;
            } else {
                $store->created_by = $data->created_by;
            }

            $lang = 'en';
            $company_setting = \App\Models\Utility::CompanySetting();
            $setting = \App\Models\Utility::settings();


            if (\Auth::user()->user_type == "super admin") {
                $store->lang = !empty($setting['default_language']) ? $setting['default_language'] : 'en';
            } else {
                $store->lang = !empty($company_setting['default_language']) ? $company_setting['default_language'] : 'en';
            }

            if (\Auth::user()->user_type == "super admin") {
                $store->user_type = "company";
                $store->plan = 1;
                $store->plan_expire_date = Carbon::now()->addDays(5);
            } else {
                $store->user_type = $request->get('role');
                if (\Auth::user()->is_json_upload == 1) {
                    $store->is_json_upload = 1;
                }
                if (!empty(\Auth::user()->plan) && \Auth::user()->plan != '') {
                    $store->is_json_upload = 1;
                    $store->plan = \Auth::user()->plan;
                    $store->plan_expire_date = \Auth::user()->plan_expire_date;
                }
            }
            $user = $request->email;
            $password = $request->password;
            try {
                Utility::getSMTPDetails(Auth::user()->id);
                Mail::to($user)->send(new SendUser($user, $password));
            } catch (\Throwable $th) {
                $smtp_error['status'] = false;
                $smtp_error['msg'] = $th->getMessage();
            }

            $store->save();

            if ($store) {
                if (\Auth::user()->user_type == "super admin") {
                    $role_r = Role::findByName('company');
                    $store->assignRole($role_r);
                } else {
                    $role_r = Role::findByName($request->get('role'));
                    $store->assignRole($role_r);
                }

                return redirect()->route('users')->with('success', __('User Added Successfully.'));
            } else {
                return redirect()->back()->with('error', __('Something is wrong.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
    public function delete_user($id)
    {
        if (\Auth::user()->can('delete user')) {
            $user = User::where('id', $id)->first();
            if ($user) {
                $user_site = Site::where('created_by', $user->id)->delete();
                $sub_user = User::where('created_by', $user->id)->delete();
                $user->delete();
                return redirect()->route('users')->with('success', __('User successfully deleted .'));
            } else {
                return redirect()->back()->with('error', __('Something is wrong.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
    public function update_user(Request $request)
    {
        $data = Auth::user();
        $id = $request->get('id');
        if (\Auth::user()->can('edit user')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required|max:120',
                    'email' => 'required|unique:users,email,' . $id . ',id,created_by,' . \Auth::user()->creatorId(),
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->route('users')->with('error', $messages->first());
            }

            $store = User::where('id', $request->get('id'))->where('created_by', $data->id)->first();


            if ($store) {
                if (\Auth::user()->user_type != 'super admin') {
                    $role = Role::findById($request->role);
                } else {
                    $role = Role::where('name', 'company')->first();
                }

                if ($role) {

                    $store->name = $request->get('name');
                    $store->user_type = $role->name;
                    $store->email = $request->get('email');

                    $store->save();
                    if (\Auth::user()->user_type != "super admin") {
                        $roles[] = $request->role;
                        $store->roles()->sync($roles);
                    }
                    return redirect()->route('users')->with('success', __('User Updated Successfully.'));
                } else {
                    return redirect()->back()->with('error', __('Something is wrong.'));
                }
            } else {
                return redirect()->back()->with('error', __('Something is wrong.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function changePlan($user_id)
    {
        if (\Auth::user()->can('manually change plan')) {
            $user = Auth::user();
            if ($user->user_type == 'super admin') {
                $plans = Plan::where('status',1)->get();
                $user  = User::find($user_id);

                return view('admin.user.change_plan', compact('plans', 'user'));
            } else {
                return redirect()->back()->with('error', __('Some Thing Is Wrong!'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
    public function manuallyActivatePlan(Request $request, $user_id, $plan_id, $duration)
    {
        if (\Auth::user()->can('manually change plan')) {
            $user       = User::find($user_id);
            $plan       = Plan::find($plan_id);
            $assignPlan = $this->assignPlan($plan->id, $user_id, $duration);
            if ($assignPlan['is_success'] == true && !empty($plan)) {
                $price      = $plan->{$duration . '_price'};
                if (!empty($user->payment_subscription_id) && $user->payment_subscription_id != '') {
                    try {
                        $user->cancel_subscription($user_id);
                    } catch (\Exception $exception) {
                        \Log::debug($exception->getMessage());
                    }
                }
                $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
                Order::create([
                    'order_id' => $orderID,
                    'name' => null,
                    'email' => null,
                    'card_number' => null,
                    'card_exp_month' => null,
                    'card_exp_year' => null,
                    'plan_name' => $plan->name,
                    'plan_id' => $plan->id,
                    'price' => $price,
                    'plan_type' => $duration,
                    'price_currency' => !empty(env('CURRENCY')) ? env('CURRENCY') : 'USD',
                    'txn_id' => '',
                    'payment_type' => __('Manually Upgrade By Super Admin'),
                    'payment_status' => 'succeeded',
                    'receipt' => null,
                    'user_id' => $user->id,
                ]);
                return redirect()->back()->with('success', __('Plan successfully upgraded.'));
            } else {
                return redirect()->back()->with('error', __('Plan fail to upgrade.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function account(Request $request)
    {
        $user             = Auth::user();


        return view('admin.user.account', compact('user'));
    }
    public function accountupdate(Request $request, $id = null)
    {
        $userDetail = \Auth::user();
        $user       = User::findOrFail($userDetail['id']);
        $this->validate(
            $request,
            [
                'name' => 'required|max:120',
                'email' => 'required|email|unique:users,email,' . $userDetail['id'],
            ]
        );

        if ($request->hasFile('avatar')) {
            $filenameWithExt = $request->file('avatar')->getClientOriginalName();
            $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension       = $request->file('avatar')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            $settings = Utility::getStorageSetting();
            $dir        = 'avatar/';
            $url = '';
            $dir        = 'avatars/';
            $path = Utility::upload_file($request, 'avatar', $fileNameToStore, $dir, []);

            if ($path['flag'] == 1) {
                $url = $path['url'];
            } else {
                return redirect()->back()->with('error', __($path['msg']));
            }

            $user->avatar  = $fileNameToStore;
        }

        $user['name']  = $request['name'];
        $user['email'] = $request['email'];
        $user->save();

        return redirect()->back()->with('success', __('Profile successfully updated.'));
    }


    public function deleteAvatar()
    {
        $objUser         = Auth::user();
        if (asset(\Storage::exists('avatars/' . $objUser->avatar))) {
            asset(\Storage::delete('avatars/' . $objUser->avatar));
        }
        $objUser->avatar = '';
        $objUser->save();

        return redirect()->back()->with('success', 'Avatar deleted successfully');
    }
    public function updatePassword(Request $request)
    {

        if (Auth::Check()) {
            $request->validate(
                [
                    'old_password' => 'required',
                    'password' => 'required|same:password',
                    'confirm_password' => 'required|same:password',
                ]
            );

            $objUser          = Auth::user();
            $request_data     = $request->All();
            $current_password = $objUser->password;

            if (Hash::check($request_data['old_password'], $current_password)) {
                $objUser->password = Hash::make($request_data['password']);;
                $objUser->save();

                return redirect()->back()->with('success', __('Password Updated Successfully.'));
            } elseif ($request->password != $request->confirm_password) {
                return redirect()->back()->with('error', __('Confirm Password Does not Match with New Password.'));
            } elseif ($objUser->password != $request->old_password) {
                return redirect()->back()->with('error', __('Please Enter your right old password.'));
            } else {
                return redirect()->back()->with('error', __('Please Enter Correct Current Password.'));
            }
        } else {
            return redirect()->back()->with('error', __('Some Thing Is Wrong.'));
        }
    }
    public function resetPassword(Request $request)
    {
        if (Auth::Check()) {



            $validator = \Validator::make(
                $request->all(),
                [
                    'password' => 'required|same:password|min:8',
                    'confirm_password' => 'required|same:password|min:8',

                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->route('users')->with('error', $messages->first());
            }

            $objUser          = User::where('id', $request->resete_id)->first();
            $request_data     = $request->All();


            if ($request->password == $request->confirm_password) {
                $objUser->password = Hash::make($request_data['password']);;
                $objUser->is_login_enable = 1;
                $objUser->save();

                return redirect()->back()->with('success', __('Password Updated Successfully!'));
            } else {

                return redirect()->back()->with('error', __('Confrom Password Does not Match with New Password'));
            }
        } else {

            return redirect()->back()->with('error', __('Some Thing Is Wrong!'));
        }
    }
    public function LoginManage($id)
    {
        $eId        = \Crypt::decrypt($id);
        $user = User::find($eId);
        if ($user->is_login_enable == 1) {
            $user->is_login_enable = 0;
            $user->save();
            return redirect()->route('users')->with('success', 'User login disable successfully.');
        } else {
            $user->is_login_enable = 1;
            $user->save();
            return redirect()->route('users')->with('success', 'User login enable successfully.');
        }
    }
}
