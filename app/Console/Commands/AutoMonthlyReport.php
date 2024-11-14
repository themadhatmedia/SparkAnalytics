<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Site;
use App\Models\ReportSetting;
use App\Models\Alert;
use Illuminate\Support\Facades\Log;
use App\Mail\MonthlyReport;
use Mail;
use Carbon\Carbon;
use App\Models\Utility;
class AutoMonthlyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:monthlyreport';

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
        Log::info('Monthly Report done');
        $reports=ReportSetting::where('is_monthly',1)->get();
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
                                    Mail::to($user->email)->send(new MonthlyReport($site,$user));
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
