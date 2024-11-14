<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Alert;
use Illuminate\Support\Facades\Log;
use App\Mail\MonthlyAlert;
use Mail;
use Carbon\Carbon;
use App\Models\Utility;
class AutoMonthlyAlert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:monthlyaletr';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::info('Monthly aletr done');
        $alerts = Alert::where('duration', "monthly")
                    ->get();
        if ($alerts->count() > 0) {
            foreach ($alerts as $alt) {

               $user=User::where('id',$alt->created_by)->orWhere('created_by',$alt->created_by)->get();
                if($user->count() > 0)
                {
                    foreach ($user as  $val) 
                    {

                        if($alt->email_notification==1)
                        {
                            if($val->user_type == 'company' || $val->user_type == 'super admin') 
                            {
                                Utility::getSMTPDetails($val->id);
                            }
                            else{
                                Utility::getSMTPDetails($val->created_by);
                            }
                           try{

                                Mail::to($val->email)->send(new MonthlyAlert($alt,$val));
                            }catch(\Throwable $e){
                                Log::info($e);
                            }

                        }
                        if($alt->slack_notification==1)
                        {
                            try{
                                $t_date=Carbon::now()->format('F d,Y');
                                $y_date=Carbon::now()->subDay()->format('F d,Y');
                                $data=app('App\Http\Controllers\Controller')->monthly_alter_data($alt->site_id,$alt->metric);
                                $msg='On '.$t_date.' '. $alt->metric.' '. $data.' compared to '.$y_date.'';
                                if($val->user_type=="company")
                                {
                                    $slack=app('App\Http\Controllers\Controller')->send_slack_msg($msg,$val->id);

                                } 
                            }catch(\Throwable $e){
                                Log::info($e);
                            }
                        }    
                        
                    }
                }
            }
        }
  
        return 0;
    }
}
