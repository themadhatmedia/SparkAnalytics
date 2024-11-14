<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Site;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Lab404\Impersonate\Models\Impersonate;
use App\Models\Utility;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, Impersonate;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type',
        'created_by',
        'email_verified_at',
        'plan',
        'plan_expire_date',
        'referrance_code',
        'used_referrance'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    public function dateFormat($date)
    {
        $settings = Utility::settings();

        return date($settings['site_date_format'], strtotime($date));
    }
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function creatorId()
    {

        if ($this->user_type == 'company' || $this->user_type == 'super admin') {
            return $this->id;
        } else {
            return $this->created_by;
        }
    }

    public function createById()
    {

        if ($this->user_type == 'super admin') {
            return $this->id;
        } else {
            return $this->created_by;
        }
    }
    public function currentLanguage()
    {
        return $this->lang;
    }

    public function plan()
    {
        return $this->hasOne('App\Models\Plan', 'id', 'plan');
    }
    public function assignPlan($planID, $Company_id, $frequency = '')
    {


        $plan = Plan::find($planID);

        $Company = User::where('id', $Company_id)->first();
        $sites = Site::where('created_by', '=', $Company->id)->get();
        if ($plan) {
            if ($Company->trial_expire_date != null); {
                $Company->trial_expire_date = null;
            }
            $sitescount = 0;
            foreach ($sites as $site) {
                $sitescount++;
                $site->is_active = $plan->max_site == -1 || $sitescount <= $plan->max_site ? 1 : 0;

                $site->save();

                $assetsCount = 0;
                foreach ($site->widget as $widget) {
                    $assetsCount++;
                    $widget->is_active = $plan->max_widget == -1 || $assetsCount <= $plan->max_widget ? 1 : 0;
                    $widget->save();
                }
            }
            if ($plan->max_site == -1) {
                $Company->is_plan_purchased = 1;
                $Company->save();
                $user = User::where('created_by', $Company_id)->update(['is_plan_purchased' => 1]);
            } else {

                $s_Count = 0;
                foreach ($sites as $site) {
                    $s_Count++;
                    if ($s_Count <= $plan->max_user) {
                        $Company->is_plan_purchased = 1;
                        $Company->save();
                        $user = User::where('created_by', $Company_id)->update(['is_plan_purchased' => 1]);
                    } else {
                        $Company->is_plan_purchased = 0;
                        $Company->save();
                        $user = User::where('created_by', $Company_id)->update(['is_plan_purchased' => 0]);
                    }
                }
            }
            $users     = User::where('created_by', '=', $Company_id)->where('user_type', '!=', 'super admin')->where('user_type', '!=', 'company')->get();
            if ($plan->max_user == -1) {
                foreach ($users as $user) {
                    $user->user_status = 1;
                    $user->save();
                }
            } else {
                $userCount = 0;
                foreach ($users as $user) {
                    $userCount++;
                    if ($userCount <= $plan->max_user) {
                        $user->user_status = 1;
                        $user->save();
                    } else {
                        $user->user_status = 0;
                        $user->save();
                    }
                }
            }
            $user = User::where('created_by', $Company_id)->update(['plan' => $planID]);
            $Company->plan = $plan->id;
            if ($frequency == 'weekly') {
                $user = User::where('created_by', $Company_id)->update(['plan_expire_date' => Carbon::now()->addWeeks(1)->isoFormat('YYYY-MM-DD')]);
                $Company->plan_expire_date = Carbon::now()->addWeeks(1)->isoFormat('YYYY-MM-DD');
            } elseif ($frequency == 'monthly') {
                $user = User::where('created_by', $Company_id)->update(['plan_expire_date' => Carbon::now()->addMonths(1)->isoFormat('YYYY-MM-DD')]);
                $Company->plan_expire_date = Carbon::now()->addMonths(1)->isoFormat('YYYY-MM-DD');
            } elseif ($frequency == 'annual') {
                $user = User::where('created_by', $Company_id)->update(['plan_expire_date' => Carbon::now()->addYears(1)->isoFormat('YYYY-MM-DD')]);
                $Company->plan_expire_date = Carbon::now()->addYears(1)->isoFormat('YYYY-MM-DD');
            } else {
                $Company->plan_expire_date = null;
                $user = User::where('created_by', $Company_id)->update(['plan_expire_date' => null]);
            }
            $Company->plan_type = $frequency;
            $user = User::where('created_by', $Company_id)->update(['plan_type' => $frequency]);

            $Company->save();
            return ['is_success' => true];
        } else {
            return [
                'is_success' => false,
                'error' => __('Plan is deleted.'),
            ];
        }
    }
}
