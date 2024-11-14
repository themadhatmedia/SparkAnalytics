<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Site;
use App\Models\ReportSetting;
use App\Models\Alert;
use Illuminate\Support\Facades\Log;
use App\Mail\WeeklyReport;
use Mail;
use Carbon\Carbon;
use App\Models\Utility;
class AutoweeklyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:weeklyreport';

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
        Log::info('Weekly Report done');
        $reports=ReportSetting::where('is_weekly',1)->get();
        if($reports->count() > 0)
        {
            foreach ($reports as $report) 
            {
                $user=User::where('id',$report->created_by)->first();
                if($user)
                {
                    $sites=Site::where('created_by',$user->id)->get();
                    if($sites->count() > 0)
                    {
                        foreach ($sites as  $site) 
                        {
                            if($report->email_notification==1)
                            {
                                if ($user->user_type == 'company' || $user->user_type == 'super admin') {
                                    Utility::getSMTPDetails($user->id);
                                } else {
                                    Utility::getSMTPDetails($user->created_by);
                                }
                               try{
                                    Mail::to($user->email)->send(new WeeklyReport($site,$user));
                                }catch(\Throwable $e){
                                    Log::info($e);
                                }


                            }
                        }
                        
                    }

                }
            }
        }
        return 0;
    }
}
