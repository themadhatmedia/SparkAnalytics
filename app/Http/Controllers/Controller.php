<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Google_Client;
use Google_Service_Analytics;
use Session;
use Google_Service_AnalyticsReporting;
use Google_Service_AnalyticsReporting_DateRange;
use Google_Service_AnalyticsReporting_Metric;
use Google_Service_AnalyticsReporting_ReportRequest;
use Google_Service_AnalyticsReporting_GetReportsRequest;
use Google_Service_AnalyticsReporting_Dimension;
use Google_Service_AnalyticsReporting_OrderBy;
use Google_Service_AnalyticsReporting_Segment;
use Google_Service_AnalyticsReporting_DimensionFilter;
use Google_Service_AnalyticsData;
use Google_Service_AnalyticsData_RunRealtimeReportRequest;
use Google_Service_AnalyticsReporting_DimensionFilterClause;
use Google_Service_AnalyticsData_Metric;
use App\Models\Site;
use App\Models\User;
use App\Models\Plan;
use App\Models\Order;
use App\Models\PlanRequest;
use App\Models\Credintials;
use Carbon\Carbon;
use App\Models\Utility;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\Settings;
use Exception;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function authmain($user_id=0)
    {


            if($user_id==0)
            {
                $user= Auth::user();

            }
            else
            {
                $user= User::find($user_id);

            }
            if($user)
            {


                if($user->user_type == 'company')
                {
                    $credintial=Credintials::where("user_id",$user->id)->first();
                }
                else{
                    $credintial=Credintials::where("user_id",$user->created_by)->first();
                }
            }
            else
            {
                try {
                    $currenturl=explode('/link/', url()->previous());

                    $link_typeurl=explode('/', $currenturl[0]);
                    $link_type=end($link_typeurl);
                    $arr=explode('/', $currenturl[1]);
                    $id=$arr[0];

                    $id = \Illuminate\Support\Facades\Crypt::decrypt($id);
                    if($link_type=='quickview')
                    {
                        $data=User::where('id',$id)->first();
                        if($data->user_type=='company')
                        {
                            $credintial=Credintials::where("user_id",$data->id)->first();
                        }
                        else
                        {
                            $credintial=Credintials::where("user_id",$data->created_by)->first();
                        }
                    }
                    else
                    {
                        $data=Site::where('id',$id)->first();
                        $credintial=Credintials::where("user_id",$data->created_by)->first();
                    }
                } catch (Exception $e) {
                    return view('share-link.error');
                }
            }

            $file_name='credintial_' . time() . rand() . '.json';
            $directory = storage_path('uploads/');
            $file = $directory . $file_name;
            touch($file);
            file_put_contents($file, $credintial->json);
            try{
                if(file_exists($file)){
                    $client = new Google_Client();
                    $client->setAuthConfig(storage_path('uploads/'.$file_name));
                    $client->setRedirectUri(''.url('/').'/oauth2callback');
                    $client->addScope(Google_Service_Analytics::ANALYTICS_READONLY);
                    $client->setAccessType('offline');
                    $directory = storage_path('uploads/');
                    File::deleteDirectory($directory, true);
                    return $client;
                }
               else{
                    return 0;
               }
            }catch (Exception $e){
                return redirect()->route('dashboard')->with('error', 'Google Client Not Found');
            }

    }
    function gaDateFormat($date)
    {
        $year = substr($date, 0, 4) . '-' . substr($date, 4);
        $date = substr($year, 0, 7) . '-' . substr($year, 7);

        return $date;
    }

    public function metric_option()
    {
        $metric_option = [
            "activeUsers" => "Active users",
            "sessions" => "Sessions",
            "newUsers" => "New users",
            "bounceRate" => "Bounce rate",
            "engagementRate" => "Engagement rate",
            "screenPageViewsPerSession" => "Views per session",
            "screenPageViews" => "Views",
            "eventsPerSession" => "Events per session",
            "transactions" => "All transactions",
            "sessionsPerUser" => "Sessions Per User",
            "totalRevenue" => "Total revenue",
        ];
        return $metric_option;
    }
    public function arrUsableMetrics()
    {
        $arrUsableMetrics = [
            "activeUsers" => "Active users",
            "sessions" => "Sessions",
            "newUsers" => "New users",
            "bounceRate" => "Bounce rate",
            "engagementRate" => "Engagement rate",
            "screenPageViewsPerSession" => "Views per session",
            "screenPageViews" => "Views",
            "eventsPerSession" => "Events per session",
            "transactions" => "All transactions",
            "sessionsPerUser" => "Sessions Per User",

        ];
        return $arrUsableMetrics;
    }
    public function arrTimeframe()
    {
        $arrTimeframe = [
            'today' => 'Today',
            'yesterday' => 'Yesterday',
            '7daysAgo' => 'Last 7 days',
            '15daysAgo' => 'Last 15 days',
            '30daysAgo' => 'Last 30 days',
        ];
        return $arrTimeframe;
    }
    public function dimension($type="")
    {
        if($type=="audience")
        {
             $dimension = [
                "country" => __("Region"),
                "audienceName" => __("Audience"),
                "newVsReturning" => __("New Vs Returning"),
                "language" => __("Language"),
            ];
        }
        elseif($type=="channel")
        {
             $dimension = [
                "firstUserDefaultChannelGroup" => __("First User Default Channel Group"),
                "sessionDefaultChannelGroup" => __("Session Default Channel Group"),
            ];
        }
        elseif($type=="page")
        {
             $dimension = [
                "pageTitle" => __("Page title"),
                "landingPage" => __("Landing Page"),
                "pagePath" => __("Exit Page"),
            ];
        }
        elseif($type=="seo")
        {
             $dimension = [
                "browser" => __("Browser"),
                "operatingSystem" => __("Operating System"),
                "mobileDeviceBranding" => __("Device"),
            ];
        }
        else
        {
            $dimension=[
                'medium' => __('Medium'),
                'source' => __('Source'),
                'browser' => __('Browser'),
                'operatingSystem' => __('Operating System'),
                'deviceCategory' => __('Device Category'),
                'language' => __('Language'),
                'screenResolution' => __('Screen Resolution'),
            ];
        }

        return $dimension;
    }


     public function date_filter_option()
    {

        $date_option = [
            "".date('Y-m-d')." - ".date('Y-m-d')."" => "Today",
            "".date('Y-m-d',strtotime("-1 days"))." - ".date('Y-m-d',strtotime("-1 days"))."" => "Yesterday",
            "".date('Y-m-d',strtotime("-6 days"))." - ".date('Y-m-d')."" => "Last 7 Days",
            "".date('Y-m-d',strtotime("-29 days"))." - ".date('Y-m-d')."" => "Last 30 Days",
            "".date('Y-m-01')." - ".date('Y-m-t')."" => "This Month",
            "".date('Y-m-01',strtotime("-1 months"))." - ".date('Y-m-t',strtotime("-1 months"))."" => "Last Month",

        ];
    }


    function getReport($site, $request_json)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://analyticsdata.googleapis.com/v1beta/properties/'.$site->property_id.':runReport',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>$request_json,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer '.$site->accessToken.''
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $res_data=json_decode($response);
        return $res_data;
    }

    function getDurationFromText($duration)
    {


        $arrDate  = [];
        $arrField = [];
        $duration = strtolower($duration);

        if($duration == "today")
        {
            $arrDate['dimension'] = "hour";
            $arrDate['EndDate']   = date('Y-m-d');
            $startDate            = date('Y-m-d');
            for($i = 0; $i <= 23; $i++)
            {
                if($i <= 9)
                {
                    $arrField['0' . $i] = $i;
                }
                else
                {
                    $arrField[$i] = $i;
                }
            }

            $arrDate['StartDate'] = $startDate;
            $arrDate['arrField']  = $arrField;
        }
        elseif($duration == "yesterday")
        {
            $arrDate['dimension'] = "date";
            $arrDate['EndDate']   = date('Y-m-d');
            $startDate            = date('Y-m-d');
            for($i = 1; $i <= 2; $i++)
            {
                $startDate                                  = date('Y-m-d', strtotime('-1 day', strtotime($startDate)));
                $arrField[date('l', strtotime($startDate))] = date('l', strtotime($startDate));
            }

            $arrDate['StartDate'] = $startDate;
            $arrDate['arrField']  = $arrField;
        }
        elseif($duration == "week" || $duration == "7daysago")
        {
            $arrDate['dimension'] = "dayOfWeekName";
            $arrDate['EndDate']   = date('Y-m-d');
            $startDate            = $arrDate['EndDate'];
            for($i = 1; $i <= 7; $i++)
            {
                $startDate                                  = date('Y-m-d', strtotime('-1 day', strtotime($startDate)));
                $arrField[date('l', strtotime($startDate))] = date('l', strtotime($startDate));
            }
            $arrDate['StartDate'] = $startDate;
            $arrDate['arrField']  = $arrField;
        }
        elseif($duration == "15daysago")
        {
            $arrDate['dimension'] = "date";
            $arrDate['EndDate']   = date('Y-m-d');
            $startDate            = $arrDate['EndDate'];
            for($i = 1; $i <= 15; $i++)
            {
                $arrField[date('Ymd', strtotime($startDate))] = date('d-m-Y', strtotime($startDate));
                $startDate                                    = date('Y-m-d', strtotime('-1 day', strtotime($startDate)));
            }
            $arrField[date('Ymd', strtotime($startDate))] = date('d-m-Y', strtotime($startDate));

            $arrDate['StartDate'] = $startDate;
            $arrDate['arrField']  = $arrField;

        }
        elseif($duration == "month" || $duration == "30daysago")
        {
            $arrDate['dimension'] = "date";
            $arrDate['EndDate']   = date('Y-m-d');
            $startDate            = $arrDate['EndDate'];

            for($i = 1; $i <= 30; $i++)
            {
                $startDate                                    = date('Y-m-d', strtotime('-1 day', strtotime($startDate)));
                $arrField[date('Ymd', strtotime($startDate))] = date('d-m-Y', strtotime($startDate));
            }
            $arrDate['StartDate'] = $startDate;
            $arrDate['arrField']  = $arrField;
        }
        elseif($duration == "year")
        {

            $arrDate['dimension'] = "yearMonth";
            $arrDate['EndDate']   = date('Y-m-d', strtotime('+1 month', time()));
            $startDate            = $arrDate['EndDate'];
            for($i = 1; $i <= 12; $i++)
            {
                $startDate                                   = date('Y-m-d', strtotime('-1 month', strtotime($startDate)));
                $arrField[date('Ym', strtotime($startDate))] = date('M', strtotime($startDate));
            }
            $arrDate['StartDate'] = $startDate;
            $arrDate['arrField']  = $arrField;
        }

        return $arrDate;
    }
    function getLiveUser($objSite)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://analyticsdata.googleapis.com/v1beta/properties/'.$objSite->property_id.':runRealtimeReport',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{
          "metrics": [{ "name": "activeUsers" }]
        }',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Bearer '.$objSite->accessToken.''
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $res_data=json_decode($response);
        if(!isset($res_data->error))
        {
            if(isset($res_data->rows))
            {
                $res['is_success'] = 1;
                $res['liveUser']=$res_data->rows[0]->metricValues[0]->value;
            }else
            {
                $res['is_success'] = 1;
            }
        }
        else
        {
            $res['is_success'] = 0;

        }

        echo json_encode($res);
    }


    public function assignPlan($planID,$Company_id, $frequency = '')
    {


        $plan = Plan::find($planID);

        $Company=User::where('id',$Company_id)->first();
               $sites= Site::where('created_by', '=', $Company->id)->get();
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
            if ($plan->max_site == -1)
            {
                $Company->is_plan_purchased = 1;
                $Company->save();
                $user=User::where('created_by',$Company_id)->update(['is_plan_purchased'=>1]);
            }
            else
            {

                $s_Count = 0;
                foreach ($sites as $site) {
                    $s_Count++;
                    if ($s_Count <= $plan->max_user) {
                        $Company->is_plan_purchased = 1;
                        $Company->save();
                        $user=User::where('created_by',$Company_id)->update(['is_plan_purchased'=>1]);

                    } else {
                        $Company->is_plan_purchased = 0;
                        $Company->save();
                        $user=User::where('created_by',$Company_id)->update(['is_plan_purchased'=>0]);
                    }
                }
            }
            $users     = User::where('created_by', '=',$Company_id)->where('user_type', '!=', 'super admin')->where('user_type', '!=', 'company')->get();
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
           $user=User::where('created_by',$Company_id)->update(['plan'=>$planID]);
            $Company->plan = $plan->id;
            if ($frequency == 'weekly') {
                $user=User::where('created_by',$Company_id)->update(['plan_expire_date'=>Carbon::now()->addWeeks(1)->isoFormat('YYYY-MM-DD')]);
                $Company->plan_expire_date = Carbon::now()->addWeeks(1)->isoFormat('YYYY-MM-DD');
            } elseif ($frequency == 'monthly') {
                $user=User::where('created_by',$Company_id)->update(['plan_expire_date'=>Carbon::now()->addMonths(1)->isoFormat('YYYY-MM-DD')]);
                $Company->plan_expire_date = Carbon::now()->addMonths(1)->isoFormat('YYYY-MM-DD');
            } elseif ($frequency == 'annual') {
                $user=User::where('created_by',$Company_id)->update(['plan_expire_date'=>Carbon::now()->addYears(1)->isoFormat('YYYY-MM-DD')]);
                $Company->plan_expire_date = Carbon::now()->addYears(1)->isoFormat('YYYY-MM-DD');
            } else {
                $Company->plan_expire_date = null;
                $user=User::where('created_by',$Company_id)->update(['plan_expire_date'=>null]);
            }
            $Company->plan_type = $frequency;
             $user=User::where('created_by',$Company_id)->update(['plan_type'=>$frequency]);

            $Company->save();
            return ['is_success' => true];
        } else {
            return [
                'is_success' => false,
                'error' => __('Plan is deleted.'),
            ];
        }
    }
    public function daily_alter_data($siteid,$metric)
    {

        $duration='yesterday';
        $site=Site::where("id",$siteid)->first();
        $arrConfig= [];
        $metric_option=$this->metric_option();
        $met_name='';
        $met_main_arr=array();
        foreach ($metric_option as $ke => $met_val) {
            if($ke==$metric)
            {
                $arrMetrics = ["name"=>$ke];
                $met_main_arr[]=$arrMetrics;
                $met_name=$met_val;
                break;
            }
        }
        $request_metric=json_encode($met_main_arr);
        $arrParam =  $this->getDurationFromText($duration);
        $arrConfig['dimensions'] = [$arrParam['dimension']];
        $arrConfig['StartDate']  = $arrParam['StartDate'];
        $arrConfig['EndDate']    = $arrParam['EndDate'];

        $request_json='{"dimensions":[{"name":"'.$arrParam['dimension'].'"}],"metrics":'.$request_metric.',"dateRanges":[{"startDate":"'.$arrConfig['StartDate'].'","endDate":"'.$arrConfig['EndDate'].'"}],"orderBys":[{"dimension":{"orderType":"NUMERIC","dimensionName":"'.$arrParam['dimension'].'"}}],"keepEmptyRows":true}';
        $res_data=$this->getReport($site, $request_json);

        $y_data=0;
        $t_data=0;
        if(!isset($res_data->error))
        {
            $data=array();
            if(isset($res_data->rows))
            {
                foreach ($res_data->rows as $key => $value)
                {
                    if($key!=0)
                    {
                        $data[]=$value;
                    }
                }
                if(!empty($data))
                {
                    foreach ($data as $k => $val) {
                        if($k==0)
                        {
                            $y_data=$data[$k]->metricValues[0]->value;
                        }
                        else
                        {
                            $t_data=$data[$k]->metricValues[0]->value;
                        }
                    }
                    if($y_data>$t_data)
                    {
                        $sum=$y_data/$t_data;
                        $res="increased " .number_format($sum, 2)."x";

                    }
                    else
                    {
                        $sum=$t_data/$y_data;
                        $res="decreased " .number_format($sum, 2)."x";
                    }

                }
            }
            else
            {
                $res="No data available";
            }

            return $res;
        }
    }
    public function week_alter_data($siteid,$metric)
    {

        $duration='7daysago';
        $site=Site::where("id",$siteid)->first();
        $arrConfig= [];
        $metric_option=$this->metric_option();
        $met_name='';
        $met_main_arr=array();
        foreach ($metric_option as $ke => $met_val) {
            if($ke==$metric)
            {
                $arrMetrics = ["name"=>$ke];
                $met_main_arr[]=$arrMetrics;
                $met_name=$met_val;
                break;
            }
        }
        $request_metric=json_encode($met_main_arr);
        $arrParam =  $this->getDurationFromText($duration);
        $arrConfig['dimensions'] = [$arrParam['dimension']];
        $arrConfig['StartDate']  = $arrParam['StartDate'];
        $arrConfig['EndDate']    = $arrParam['EndDate'];

        $request_json='{"dimensions":[{"name":"'.$arrParam['dimension'].'"}],"metrics":'.$request_metric.',"dateRanges":[{"startDate":"'.$arrConfig['StartDate'].'","endDate":"'.$arrConfig['EndDate'].'"}],"orderBys":[{"dimension":{"orderType":"NUMERIC","dimensionName":"'.$arrParam['dimension'].'"}}],"keepEmptyRows":true}';
        $res_data=$this->getReport($site, $request_json);

        $y_data=0;
        $t_data=0;
        if(!isset($res_data->error))
        {
            $start_day=date('l', strtotime(' -7 day'));

            $end_day=date('l', strtotime(' -1 day'));

            $data=array();
            if(isset($res_data->rows))
            {
                foreach ($res_data->rows as $key => $value)  {

                    if($res_data->rows[$key]->dimensionValues[0]->value==$start_day )
                    {
                        $data['start']=$value;
                    }
                    if($res_data->rows[$key]->dimensionValues[0]->value==$end_day)
                    {
                        $data['end']=$value;
                    }

                }

                if(!empty($data))
                {

                    foreach ($data as $k => $val) {
                       if($k=='start')
                       {
                            $s_date=$data[$k]->metricValues[0]->value;
                       }
                       else
                       {
                            $e_date=$data[$k]->metricValues[0]->value;
                       }



                    }
                    if($e_date>$s_date)
                    {
                        $sum=$e_date/$s_date;
                        $res="increased " .number_format($sum, 2)."x";

                    }
                    else
                    {
                        $sum=$s_date/$e_date;
                        $res="decreased " .number_format($sum, 2)."x";
                    }



                }
            }
            else
            {
                $res="No data available";
            }

            return $res;


        }

    }
    public function monthly_alter_data($siteid,$metric)
    {
        $duration='30daysago';
        $site=Site::where("id",$siteid)->first();
        $arrConfig= [];
        $metric_option=$this->metric_option();
        $met_name='';
        $met_main_arr=array();
        foreach ($metric_option as $ke => $met_val) {
            if($ke==$metric)
            {
                $arrMetrics = ["name"=>$ke];
                $met_main_arr[]=$arrMetrics;
                $met_name=$met_val;
                break;
            }
        }
        $request_metric=json_encode($met_main_arr);
        $arrParam =  $this->getDurationFromText($duration);
        $arrConfig['dimensions'] = [$arrParam['dimension']];
        $arrConfig['StartDate']  = $arrParam['StartDate'];
        $arrConfig['EndDate']    = $arrParam['EndDate'];


        $request_json='{"dimensions":[{"name":"'.$arrParam['dimension'].'"}],"metrics":'.$request_metric.',"dateRanges":[{"startDate":"'.$arrConfig['StartDate'].'","endDate":"'.$arrConfig['EndDate'].'"}],"orderBys":[{"dimension":{"orderType":"NUMERIC","dimensionName":"'.$arrParam['dimension'].'"}}],"keepEmptyRows":true}';
        $res_data=$this->getReport($site, $request_json);

        $s_data=0;
        $e_data=0;
        if(!isset($res_data->error))
        {
            $data=array();
            if(isset($res_data->rows))
            {
                foreach ($res_data->rows as $key => $value) {
                    if($key==0)
                    {
                        $data['start']=$value;
                    }
                    if($key==30)
                    {
                        $data['end']=$value;
                    }

                }
                if(!empty($data))
                {
                    foreach ($data as $k => $val) {
                       if($k=='start')
                       {
                            $s_data=$data[$k]->metricValues[0]->value;
                       }
                       else
                       {
                            $e_data=$data[$k]->metricValues[0]->value;
                       }



                    }

                    if($e_data>$s_data)
                    {
                        $sum=$e_data/$s_data;
                        $res="increased " .number_format($sum, 2)."x";

                    }
                    else
                    {
                        $sum=$s_data/$e_data;
                        $res="decreased " .number_format($sum, 2)."x";
                    }

                    return $res;
                }
            }
            else
            {
                $res="No data available";
            }

            return $res;


        }

    }
    public static function send_slack_msg($msg,$created_id=0) {



        if($created_id==0){

            $settings  = Utility::settings($created_id);
        }else{
            $settings  = Utility::settings($created_id);
        }


        try{
            if(isset($settings['slack_webhook']) && !empty($settings['slack_webhook'])){

                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, $settings['slack_webhook']);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['text' => $msg]));

                $headers = array();
                $headers[] = 'Content-Type: application/json';
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $result = curl_exec($ch);

                if (curl_errno($ch)) {
                    echo 'Error:' . curl_error($ch);
                }
                curl_close($ch);
            }
        }
        catch(\Throwable $e){
                            Log::info($e);
                        }

    }


    public function daily_report_data($siteid)
    {
        $duration='yesterday';
        $site=Site::where("id",$siteid)->first();
        $arrConfig= [];
        $arrMetrics=[];

        $metric_option=$this->metric_option();
        $i=1;
        foreach ($metric_option as $ke => $met_val) {
                if($i<=10)
                {
                    $arrMetrics[] = ["name"=>$ke];
                }
                $i++;
        }
        $request_metric=json_encode($arrMetrics);
        $arrParam =  $this->getDurationFromText($duration);
        $arrConfig['dimensions'] = [$arrParam['dimension']];
        $arrConfig['StartDate']  = $arrParam['StartDate'];
        $arrConfig['EndDate']    = $arrParam['EndDate'];

        $request_json='{"dimensions":[{"name":"'.$arrParam['dimension'].'"}],"metrics":'.$request_metric.',"dateRanges":[{"startDate":"'.$arrConfig['StartDate'].'","endDate":"'.$arrConfig['EndDate'].'"}],"orderBys":[{"dimension":{"orderType":"NUMERIC","dimensionName":"'.$arrParam['dimension'].'"}}],"keepEmptyRows":true}';
        $res_data=$this->getReport($site, $request_json);

        $y_data=0;
        $t_data=0;
        $dt_arr=array();
        $html="";
        if(!isset($res_data->error))
        {
            $data=array();

            foreach ($res_data->rows as $key => $value) {
                if($key!=0)
                {
                    $data[]=$value;
                }

            }
            if(!empty($data))
            {

                foreach ($data as $k => $val) {
                    if($k==0)
                    {
                        $y_data=$data[$k]->metricValues;
                    }
                    else
                    {
                        $t_data=$data[$k]->metricValues;
                    }
                }
                $temp=0;

                if($y_data!=0&&$t_data!=0)
                {
                    foreach ($y_data as $key => $value)
                    {

                        if($y_data[$key]->value !=0 && $t_data[$key]->value!=0)
                        {
                            $temp=number_format($y_data[$key]->value, 2)-number_format($t_data[$key]->value, 2);
                            $sum=($temp*100)/$y_data[$key]->value;

                        }
                        else{
                            $sum=100;

                        }
                        $dt_arr[$key]=array(
                            "previous"=>number_format($y_data[$key]->value, 2),
                            "current"=>number_format($t_data[$key]->value, 2),
                            "change"=>number_format($sum, 2),
                        );
                    }

                }


            }

        }

        return json_encode($dt_arr);

    }

    public function weekly_report_data($siteid)
    {

        $duration='7daysago';
        $site=Site::where("id",$siteid)->first();
        $arrConfig= [];
        $arrMetrics=[];

        $metric_option=$this->metric_option();
        $i=1;
        foreach ($metric_option as $ke => $met_val) {
                if($i<=10)
                {
                    $arrMetrics[] = ["name"=>$ke];
                }
                $i++;
        }
        $request_metric=json_encode($arrMetrics);
        $arrParam =  $this->getDurationFromText($duration);
        $arrConfig['dimensions'] = [$arrParam['dimension']];
        $arrConfig['StartDate']  = $arrParam['StartDate'];
        $arrConfig['EndDate']    = $arrParam['EndDate'];

        $request_json='{"dimensions":[{"name":"'.$arrParam['dimension'].'"}],"metrics":'.$request_metric.',"dateRanges":[{"startDate":"'.$arrConfig['StartDate'].'","endDate":"'.$arrConfig['EndDate'].'"}],"orderBys":[{"dimension":{"orderType":"NUMERIC","dimensionName":"'.$arrParam['dimension'].'"}}],"keepEmptyRows":true}';
        $res_data=$this->getReport($site, $request_json);
        $start_day=date('l', strtotime(' -7 day'));

        $end_day=date('l', strtotime(' -1 day'));
        $y_data=0;
        $t_data=0;
        $dt_arr=array();
        $html="";
        if(!isset($res_data->error))
        {
            $data=array();

            foreach ($res_data->rows as $key => $value) {


                if($res_data->rows[$key]->dimensionValues[0]->value==$start_day )
                {
                    $data['start']=$value;
                }
                if($res_data->rows[$key]->dimensionValues[0]->value==$end_day)
                {
                    $data['end']=$value;
                }

            }


            if(!empty($data))
            {

                foreach ($data as $k => $val) {
                    if($k=='start')
                    {
                        $y_data=$data[$k]->metricValues;
                    }
                    else
                    {
                        $t_data=$data[$k]->metricValues;
                    }
                }
                $temp=0;

                if($y_data!=0&&$t_data!=0)
                {
                     foreach ($y_data as $key => $value)
                    {

                        if($y_data[$key]->value !=0 && $t_data[$key]->value!=0)
                        {
                            $temp=number_format($y_data[$key]->value, 2)-number_format($t_data[$key]->value, 2);
                            $sum=($temp*100)/$y_data[$key]->value;

                        }
                        else{
                            $sum=100;

                        }
                        $dt_arr[$key]=array(
                            "previous"=>number_format($y_data[$key]->value, 2),
                            "current"=>number_format($t_data[$key]->value, 2),
                            "change"=>number_format($sum, 2),
                        );
                    }

                }


            }

        }

        return json_encode($dt_arr);

    }

    public function monthly_report_data($siteid)
    {

        $duration='30daysago';
        $site=Site::where("id",$siteid)->first();
        $arrConfig= [];
        $arrMetrics=[];

        $metric_option=$this->metric_option();
        $i=1;
        foreach ($metric_option as $ke => $met_val) {
                if($i<=10)
                {
                    $arrMetrics[] = ["name"=>$ke];
                }
                $i++;
        }
        $request_metric=json_encode($arrMetrics);
        $arrParam =  $this->getDurationFromText($duration);
        $arrConfig['dimensions'] = [$arrParam['dimension']];
        $arrConfig['StartDate']  = $arrParam['StartDate'];
        $arrConfig['EndDate']    = $arrParam['EndDate'];

        $request_json='{"dimensions":[{"name":"'.$arrParam['dimension'].'"}],"metrics":'.$request_metric.',"dateRanges":[{"startDate":"'.$arrConfig['StartDate'].'","endDate":"'.$arrConfig['EndDate'].'"}],"orderBys":[{"dimension":{"orderType":"NUMERIC","dimensionName":"'.$arrParam['dimension'].'"}}],"keepEmptyRows":true}';
        $res_data=$this->getReport($site, $request_json);
        $y_data=0;
        $t_data=0;
        $dt_arr=array();
        $html="";
        if(!isset($res_data->error))
        {
            $data=array();

            foreach ($res_data->rows as $key => $value) {

                 if($key==0)
                {
                    $data['start']=$value;
                }
                if($key==30)
                {
                    $data['end']=$value;
                }

            }


            if(!empty($data))
            {

                foreach ($data as $k => $val) {
                    if($k=='start')
                    {
                        $y_data=$data[$k]->metricValues;
                    }
                    else
                    {
                        $t_data=$data[$k]->metricValues;
                    }
                }
                $temp=0;

                if($y_data!=0&&$t_data!=0)
                {
                    foreach ($y_data as $key => $value)
                    {

                        if($y_data[$key]->value !=0 && $t_data[$key]->value!=0)
                        {
                            $temp=number_format($y_data[$key]->value, 2)-number_format($t_data[$key]->value, 2);
                            $sum=($temp*100)/$y_data[$key]->value;

                        }
                        else{
                            $sum=100;

                        }
                        $dt_arr[$key]=array(
                            "previous"=>number_format($y_data[$key]->value, 2),
                            "current"=>number_format($t_data[$key]->value, 2),
                            "change"=>number_format($sum, 2),
                        );
                    }

                }
            }

        }
        return json_encode($dt_arr);
    }
}
