<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Google_Client;
use Google_Service_Analytics;
use Google_Service_AnalyticsReporting;
use Google_Service_AnalyticsReporting_DateRange;
use Google_Service_AnalyticsReporting_Metric;
use Google_Service_AnalyticsReporting_ReportRequest;
use Google_Service_AnalyticsReporting_GetReportsRequest;
use Google_Service_AnalyticsReporting_Dimension;
use Google_Service_AnalyticsReporting_OrderBy;
use Google_Service_AnalyticsReporting_Segment;
use Google_Service_AnalyticsReporting_DimensionFilter;
use Google_Service_AnalyticsReporting_DimensionFilterClause;
use App\Models\Site;
use App\Models\User;
use App\Models\Plan;
use App\Models\Order;
use App\Models\PlanRequest;
use App\Models\Credintials;
use Carbon\Carbon;
use App\Models\Settings;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AnalyticsController extends Controller
{
    public function index()
    {

        $client = $this->authmain();

        if (Session::get("access_token") != '' && !empty(Session::get("access_token"))) {

            $token = Session::get("access_token");

            $user = Auth::user();

            $access_check = Settings::where('created_by', $user->id)->where('name', "comapny_access_token")->first();
            if ($access_check) {
                $settings = Settings::where('created_by', $user->id)->where('name', "comapny_access_token")->first();
                $settings->name = "comapny_access_token";
                $settings->value = $token['access_token'];
                $settings->created_by = $user->id;
                $settings->save();
            } else {
                $settings = new Settings();
                $settings->name = "comapny_access_token";
                $settings->value = $token['access_token'];
                $settings->created_by = $user->id;
                $settings->save();
            }
            $refresh_check = Settings::where('created_by', $user->id)->where('name', "comapny_refresh_token")->first();
            if ($refresh_check) {

                $settings = Settings::where('created_by', $user->id)->where('name', "comapny_refresh_token")->first();
                $settings->name = "comapny_refresh_token";
                $settings->value = isset($token['refresh_token']) ? $token['refresh_token'] : '';
                $settings->created_by = $user->id;
                $settings->save();
            } else {

                $settings = new Settings();
                $settings->name = "comapny_refresh_token";
                $settings->value = isset($token['refresh_token']) ? $token['refresh_token'] : '';
                $settings->created_by = $user->id;
                $settings->save();
            }

            $client->setAccessToken(Session::get("access_token"));
            $analytics = new Google_Service_AnalyticsReporting($client);

            $account = $this->getProfiles($analytics);
            if ($account['is_success'] == 'true') {
                return view('admin.site.save')->with('account', $account['data']);
            } else {
                return redirect()->route('dashboard')->with('error', __('No accounts found for this user.'));
            }
        } else {
            $redirect_uri = '' . url('/') . '/oauth2callback';
            return redirect((filter_var($redirect_uri, FILTER_SANITIZE_URL)));
        }
    }

    public function oauth2callback()
    {
        $client = $this->authmain();
        if (!isset($_GET['code']) && empty($_GET['code'])) {
            $auth_url = $client->createAuthUrl();
            return redirect((filter_var($auth_url, FILTER_SANITIZE_URL)));
        } else {
            $client->authenticate($_GET['code']);
            $token = $client->getAccessToken();

            $user_token = Session::put("access_token", $token);

            $redirect_uri = '' . url('/') . '/add-site';
            return redirect((filter_var($redirect_uri, FILTER_SANITIZE_URL)));
        }
    }


    public function genrate_accesstoken()
    {
        $user = Auth::user();
        if ($user) {
            if ($user->user_type != "super admin") {
                if ($user->user_type == "company") {
                    $token_data = Site::where('created_by', $user->id)->orderBy('id', 'desc')->first();
                    if ($token_data) {

                        $refresh_token = $token_data->refreshToken;
                    } else {
                        $token_data = Settings::where('name', 'comapny_refresh_token')->where('created_by', $user->id)->first();
                        $refresh_token = $token_data->value;
                    }
                    $Credintials = Credintials::where('user_id', $user->id)->first();
                } else {
                    $token_data = Site::where('created_by', $user->created_by)->orderBy('id', 'desc')->first();
                    if ($token_data) {
                        $u_id = $token_data->created_by;


                        $refresh_token = $token_data->refreshToken;
                    } else {
                        $token_data = Settings::where('name', 'comapny_refresh_token')->where('created_by', $user->created_by)->first();
                        $u_id = $token_data->created_by;

                        $refresh_token = $token_data->value;
                    }
                    $Credintials = Credintials::where('user_id', $user->created_by)->first();
                }
                $arr = json_decode($Credintials->json);
                $client_id = $arr->web->client_id;
                $client_secret = $arr->web->client_secret;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://www.googleapis.com/oauth2/v4/token');
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $refresh_token,
                    'client_id' => $client_id,
                    'client_secret' => $client_secret,
                ]));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                $response = curl_exec($ch);
                curl_close($ch);


                if ($response === false) {
                    echo 'Error: ' . curl_error($ch);
                } else {
                    $data = json_decode($response, true);

                    if (isset($data['access_token'])) {

                        $data['refresh_token'] = $refresh_token;
                        $access_token = $data['access_token'];
                        Session::put("access_token", $data);
                        if ($user) {
                            if ($user->user_type == "company") {
                                $site = Site::where('created_by', $user->id)->update(['accessToken' => $access_token]);
                                $Settings = Settings::where('created_by', $user->id)->where('name', 'comapny_access_token')->update(['value' => $access_token]);
                                $Settings = Settings::where('created_by', $user->id)->where('name', 'comapny_refresh_token')->update(['value' => $refresh_token]);
                            } else {
                                $site = Site::where('created_by', $user->created_by)->update(['accessToken' => $access_token]);
                                $Settings = Settings::where('created_by', $user->created_by)->where('name', 'comapny_access_token')->update(['value' => $access_token]);
                                $Settings = Settings::where('created_by', $user->created_by)->where('name', 'comapny_refresh_token')->update(['value' => $refresh_token]);
                            }
                        } else {

                            try {
                                $currenturl = explode('link/', url()->previous());
                                $arr = explode('/', $currenturl[1]);
                                $id = $arr[0];
                                $site_id = \Illuminate\Support\Facades\Crypt::decrypt($id);
                                $site = Site::where('id', $site_id)->update(['accessToken' => $access_token]);
                            } catch (Exception $e) {
                                return view('share-link.error');
                            }
                        }
                    } else {
                        echo 'Error: ' . $data['error'];
                    }
                }
            }
        } else {

            try {
                $currenturl = explode('/link/', url()->previous());

                $arr = explode('/', $currenturl[1]);

                $id = $arr[0];
                $link_typeurl = explode('/', $currenturl[0]);

                $link_type = end($link_typeurl);

                $id = \Illuminate\Support\Facades\Crypt::decrypt($id);
                if ($link_type == "quickview") {

                    $token_data = User::where('id', $id)->orderBy('id', 'desc')->first();
                    if ($token_data->user_type == 'company') {
                        $u_id = $token_data->id;
                    } else {
                        $u_id = $token_data->created_by;
                    }
                    if ($token_data) {
                        $user_data = Settings::where('created_by', $u_id)->where('name', 'comapny_refresh_token')->first();
                        if ($user_data) {
                            $refresh_token = $user_data->value;
                        } else {
                            $token_data = Site::where('created_by', $id)->orderBy('id', 'desc')->first();
                            $refresh_token = $token_data->refreshToken;
                        }


                        $u_id = $token_data->created_by;
                    }
                } else {

                    $token_data = Site::where('id', $id)->orderBy('id', 'desc')->first();
                    if ($token_data) {

                        $refresh_token = $token_data->refreshToken;
                        $u_id = $token_data->created_by;
                    }
                }

                $Credintials = Credintials::where("user_id", $u_id)->first();
            } catch (Exception $e) {
                return view('share-link.error');
            }

            $arr = json_decode($Credintials->json);
            $client_id = $arr->web->client_id;
            $client_secret = $arr->web->client_secret;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://www.googleapis.com/oauth2/v4/token');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
                'grant_type' => 'refresh_token',
                'refresh_token' => $refresh_token,
                'client_id' => $client_id,
                'client_secret' => $client_secret,
            ]));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            curl_close($ch);


            if ($response === false) {
                echo 'Error: ' . curl_error($ch);
            } else {
                $data = json_decode($response, true);

                if (isset($data['access_token'])) {

                    $data['refresh_token'] = $refresh_token;
                    $access_token = $data['access_token'];
                    Session::put("access_token", $data);
                    if ($user) {
                        if ($user->user_type == "company") {
                            $site = Site::where('created_by', $user->id)->update(['accessToken' => $access_token]);
                            $Settings = Settings::where('created_by', $user->id)->where('name', 'comapny_access_token')->update(['value' => $access_token]);
                            $Settings = Settings::where('created_by', $user->id)->where('name', 'comapny_refresh_token')->update(['value' => $refresh_token]);
                        } else {
                            $site = Site::where('created_by', $user->created_by)->update(['accessToken' => $access_token]);
                            $Settings = Settings::where('created_by', $user->created_by)->where('name', 'comapny_access_token')->update(['value' => $access_token]);
                            $Settings = Settings::where('created_by', $user->created_by)->where('name', 'comapny_refresh_token')->update(['value' => $refresh_token]);
                        }
                    } else {

                        try {
                            $currenturl = explode('link/', url()->previous());
                            $arr = explode('/', $currenturl[1]);
                            $id = $arr[0];
                            $site_id = \Illuminate\Support\Facades\Crypt::decrypt($id);
                            $site = Site::where('id', $site_id)->update(['accessToken' => $access_token]);
                        } catch (Exception $e) {
                            return view('share-link.error');
                        }
                    }
                } else {
                    echo 'Error: ' . $data['error'];
                }
            }
        }
    }

    function getProfiles()
    {
        $token = Session::get("access_token");
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://analyticsadmin.googleapis.com/v1beta/accounts',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $token['access_token'] . ''
            ),
        ));

        $response = curl_exec($curl);
        
        curl_close($curl);
        $res_data = json_decode($response);
        $arrResult  = [];
        if (!isset($res_data->error)) {
            if (isset($res_data->accounts)) {

                foreach ($res_data->accounts as $item) {
                    $temp = explode('/', $item->name);

                    $data         = [];
                    $data['id']   = $temp[1];
                    $data['name'] = $item->displayName;
                    $ids[]        = $data;
                }
                $arrResult['is_success'] = true;
                $arrResult['data']       = $ids;
            } else {
                $arrResult['is_success'] = false;
                $arrResult['data']       = 'No accounts found for this user.';
            }
        } else {
            app('App\Http\Controllers\AnalyticsController')->genrate_accesstoken();
            $this->getProfiles();
        }
        return $arrResult;
    }


    public function channel_analytics()
    {
        if (Auth::user()->can('show channel analytic')) {
            $metric_option = $this->arrUsableMetrics();

            $user = Auth::user();
            if (Auth::user()->user_type != 'company') {
                $site = Site::where('created_by', $user->created_by)->get();
            } else {
                $site = Site::where('created_by', $user->id)->get();
            }
            if (count($site) > 0) {
                $dimension_option = $this->dimension('channel');
                return view('admin.analytics.channel')->with("site_data", $site)->with("metric_option", $metric_option)->with("dimension_option", $dimension_option);
            } else {
                return redirect()->back()->with('error', __('Something is wrong.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
    public function audience_analytics()
    {
        if (Auth::user()->can('show audience analytic')) {
            $user = Auth::user();
            if (Auth::user()->user_type != 'company') {
                $site = Site::where('created_by', $user->created_by)->get();
            } else {
                $site = Site::where('created_by', $user->id)->get();
            }
            $metric_option = $this->arrUsableMetrics();
            if (count($site) > 0) {
                $dimension_option = $this->dimension('audience');
                return view('admin.analytics.audience')->with("site_data", $site)->with("metric_option", $metric_option)->with("dimension_option", $dimension_option);
            } else {
                return redirect()->back()->with('error', __('Something is wrong.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
    public function seo_analysis()
    {
        if (\Auth::user()->can('show seo analytic')) {
            $user = Auth::user();
            if (\Auth::user()->user_type != 'company') {
                $site = Site::where('created_by', $user->created_by)->get();
            } else {
                $site = Site::where('created_by', $user->id)->get();
            }

            $metric_option = $this->arrUsableMetrics();
            if (count($site) > 0) {
                $dimension_option = $this->dimension('seo');

                return view('admin.analytics.seo')->with("site_data", $site)->with("metric_option", $metric_option)->with("dimension_option", $dimension_option);
            } else {
                return redirect()->back()->with('error', __('Something is wrong.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
    public function page_analytics()
    {
        if (\Auth::user()->can('show pages analytic')) {
            $user = Auth::user();
            if (\Auth::user()->user_type != 'company') {
                $site = Site::where('created_by', $user->created_by)->get();
            } else {
                $site = Site::where('created_by', $user->id)->get();
            }

            $metric_option = $this->arrUsableMetrics();
            if (count($site) > 0) {
                $dimension_option = $this->dimension('page');

                return view('admin.analytics.page')->with("site_data", $site)->with("metric_option", $metric_option)->with("dimension_option", $dimension_option);
            } else {
                return redirect()->back()->with('error', __('Something is wrong.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
    public function get_channel_data(Request $request)
    {


        $site = Site::where("id", $request->get('site'))->first();
        $lng = !empty($site) ? $site->createdBy->lang : 'en';

        $html = ' <a href="#"id="' . route("site.analyse.link", [\Illuminate\Support\Facades\Crypt::encrypt($site->id), \Illuminate\Support\Facades\Crypt::encrypt('channel'), $lng]) . '" class="btn  btn-primary"  onclick="copyToClipboard(this)" data-bs-toggle="tooltip"  data-original-title="{{__("Click to copy")}}">Share Report</a>';
        $dates                   = explode(" - ", $request->get('date'));
        $arrConfig               = [];
        $arrConfig['StartDate']  = date('Y-m-d', strtotime($dates[0]));
        $arrConfig['EndDate']    = date('Y-m-d', strtotime($dates[1]));
        $metric_option = $this->arrUsableMetrics();
        $met_arr = array();
        foreach ($metric_option as $key => $value) {
            $temp_met_arr = array();
            $temp_met_arr["name"] = $key;
            $met_arr[] = $temp_met_arr;
        }
        $var_metric = json_encode($met_arr);
        $request_json = '{"dimensions":[{"name":"date"},{"name":"' . $request->get('segment') . '"}],"metrics":' . $var_metric . ',"dateRanges":[{"startDate":"' . $arrConfig['StartDate'] . '","endDate":"' . $arrConfig['EndDate'] . '"}],"keepEmptyRows":true,"metricAggregations":["TOTAL"]}';
        $res_data = $this->getReport($site, $request_json);
        $arrData = [];
        $chart_arr = [];

        if (!isset($res_data->error)) {
            if (isset($res_data->totals) && !empty($res_data->totals) && isset($res_data->rows) && !empty($res_data->rows)) {
                foreach ($res_data->metricHeaders as $key => $value) {
                    if (strpos($res_data->totals[0]->metricValues[$key]->value, '.') !== false) {
                        $arrData[$value->name] = number_format($res_data->totals[0]->metricValues[$key]->value, 2);
                    } else {
                        $arrData[$value->name] = $res_data->totals[0]->metricValues[$key]->value;
                    }
                }
                foreach ($res_data->metricHeaders as $k => $value) {
                    $temp = array();
                    foreach ($res_data->rows as $key_rows => $value_rows) {
                        if (strpos($value_rows->metricValues[$k]->value, '.') !== false) {
                            $temp[$value_rows->dimensionValues[0]->value] = number_format($value_rows->metricValues[$k]->value, 2);
                        } else {
                            $temp[$value_rows->dimensionValues[0]->value] = $value_rows->metricValues[$k]->value;
                        }
                    }
                    $chart_arr[$value->name] = $temp;
                }
            } else {
                foreach ($res_data->metricHeaders as $key => $value) {
                    $arrData[$value->name] = 0;
                }
            }


            $arrResult['is_success'] = 1;
            $arrResult['data']       = $arrData;
            $arrResult['chart']       = $chart_arr;
            $arrResult['link']      = $html;
        } else {

            $arrResult['is_success'] = 0;
            $arrResult['data']       = [];
            $arrResult['chart']       = [];
            $arrResult['link']      = $html;
        }


        return $arrResult;
    }
    public function get_audience_data(Request $request)
    {
        $site = Site::where("id", $request->get('site'))->first();
        $lng = !empty($site) ? $site->createdBy->lang : 'en';
        $html = ' <a href="#"id="' . route("site.analyse.link", [\Illuminate\Support\Facades\Crypt::encrypt($site->id), \Illuminate\Support\Facades\Crypt::encrypt('audience'), $lng]) . '" class="btn  btn-primary"  onclick="copyToClipboard(this)" data-bs-toggle="tooltip"  data-original-title="{{__("Click to copy")}}">Share Report</a>';
        $dates                   = explode(" - ", $request->get('date'));
        $arrConfig               = [];
        $arrConfig['StartDate']  = date('Y-m-d', strtotime($dates[0]));
        $arrConfig['EndDate']    = date('Y-m-d', strtotime($dates[1]));
        $metric_option = $this->arrUsableMetrics();
        $met_arr = array();
        foreach ($metric_option as $key => $value) {
            $temp_met_arr = array();
            $temp_met_arr["name"] = $key;
            $met_arr[] = $temp_met_arr;
        }
        $var_metric = json_encode($met_arr);
        $request_json = '{"dimensions":[{"name":"' . $request->get('dimension') . '"}],"metrics":' . $var_metric . ',"dateRanges":[{"startDate":"' . $arrConfig['StartDate'] . '","endDate":"' . $arrConfig['EndDate'] . '"}],"keepEmptyRows":true,"metricAggregations":["TOTAL"]}';
        $res_data           = $this->getReport($site, $request_json);
        $arrData = [];
        $chart_arr = [];

        if (!isset($res_data->error)) {
            if (isset($res_data->totals) && !empty($res_data->totals) && isset($res_data->rows) && !empty($res_data->rows)) {
                foreach ($res_data->metricHeaders as $key => $value) {
                    if (strpos($res_data->totals[0]->metricValues[$key]->value, '.') !== false) {
                        $arrData[$value->name] = number_format($res_data->totals[0]->metricValues[$key]->value, 2);
                    } else {
                        $arrData[$value->name] = $res_data->totals[0]->metricValues[$key]->value;
                    }
                }
                foreach ($res_data->metricHeaders as $k => $value) {
                    $temp = array();
                    foreach ($res_data->rows as $key_rows => $value_rows) {
                        if (strpos($value_rows->metricValues[$k]->value, '.') !== false) {
                            $temp[$value_rows->dimensionValues[0]->value] = number_format($value_rows->metricValues[$k]->value, 2);
                        } else {
                            $temp[$value_rows->dimensionValues[0]->value] = $value_rows->metricValues[$k]->value;
                        }
                    }
                    $chart_arr[$value->name] = $temp;
                }
            } else {
                foreach ($res_data->metricHeaders as $key => $value) {
                    $arrData[$value->name] = 0;
                }
            }

            $arrResult['is_success'] = 1;
            $arrResult['data']       = $arrData;
            $arrResult['chart']       = $chart_arr;
            $arrResult['link']      = $html;
        } else {

            $arrResult['is_success'] = 0;
            $arrResult['data']       = [];
            $arrResult['chart']       = [];
            $arrResult['link']      = $html;
        }
        return $arrResult;
    }

    public function get_page_data(Request $request)
    {
        $site = Site::where("id", $request->get('site'))->first();
        $lng = !empty($site) ? $site->createdBy->lang : 'en';

        $html = ' <a href="#"id="' . route("site.analyse.link", [\Illuminate\Support\Facades\Crypt::encrypt($site->id), \Illuminate\Support\Facades\Crypt::encrypt('page'), $lng]) . '" class="btn  btn-primary"  onclick="copyToClipboard(this)" data-bs-toggle="tooltip"  data-original-title="{{__("Click to copy")}}">Share Report</a>';
        $dates                   = explode(" - ", $request->get('date'));
        $arrConfig               = [];
        $arrConfig['StartDate']  = date('Y-m-d', strtotime($dates[0]));
        $arrConfig['EndDate']    = date('Y-m-d', strtotime($dates[1]));
        $metric_option = $this->arrUsableMetrics();
        $met_arr = array();
        foreach ($metric_option as $key => $value) {
            $temp_met_arr = array();
            $temp_met_arr["name"] = $key;
            $met_arr[] = $temp_met_arr;
        }
        $var_metric = json_encode($met_arr);
        $request_json = '{"dimensions":[{"name":"date"},{"name":"' . $request->get('dimension') . '"}],"metrics":' . $var_metric . ',"dateRanges":[{"startDate":"' . $arrConfig['StartDate'] . '","endDate":"' . $arrConfig['EndDate'] . '"}],"keepEmptyRows":true,"metricAggregations":["TOTAL"]}';
        $res_data           = $this->getReport($site, $request_json);
        $chart_arr = [];
        if (!isset($res_data->error)) {
            if (isset($res_data->totals) && !empty($res_data->totals) && isset($res_data->rows) && !empty($res_data->rows)) {
                foreach ($res_data->metricHeaders as $key => $value) {
                    if (strpos($res_data->totals[0]->metricValues[$key]->value, '.') !== false) {
                        $arrData[$value->name] = number_format($res_data->totals[0]->metricValues[$key]->value, 2);
                    } else {
                        $arrData[$value->name] = $res_data->totals[0]->metricValues[$key]->value;
                    }
                }
                foreach ($res_data->metricHeaders as $k => $value) {
                    $temp = array();
                    foreach ($res_data->rows as $key_rows => $value_rows) {
                        if (strpos($value_rows->metricValues[$k]->value, '.') !== false) {
                            $temp[$value_rows->dimensionValues[0]->value] = number_format($value_rows->metricValues[$k]->value, 2);
                        } else {
                            $temp[$value_rows->dimensionValues[0]->value] = $value_rows->metricValues[$k]->value;
                        }
                    }
                    $chart_arr[$value->name] = $temp;
                }
            } else {
                foreach ($res_data->metricHeaders as $key => $value) {
                    $arrData[$value->name] = 0;
                }
            }
            $arrResult['is_success'] = 1;
            $arrResult['data']       = $arrData;
            $arrResult['chart']       = $chart_arr;
            $arrResult['link']      = $html;
        } else {

            $arrResult['is_success'] = 0;
            $arrResult['data']       = [];
            $arrResult['chart']       = [];
            $arrResult['link']      = $html;
        }
        return $arrResult;
    }
    public function get_seo_data(Request $request)
    {
        $site = Site::where("id", $request->get('site'))->first();
        $lng = !empty($site) ? $site->createdBy->lang : 'en';

        $html = ' <a href="#"id="' . route("site.analyse.link", [\Illuminate\Support\Facades\Crypt::encrypt($site->id), \Illuminate\Support\Facades\Crypt::encrypt('seo'), $lng]) . '" class="btn  btn-primary"  onclick="copyToClipboard(this)" data-bs-toggle="tooltip"  data-original-title="{{__("Click to copy")}}">Share Report</a>';
        $dates                   = explode(" - ", $request->get('date'));
        $arrConfig               = [];
        $arrConfig['StartDate']  = date('Y-m-d', strtotime($dates[0]));
        $arrConfig['EndDate']    = date('Y-m-d', strtotime($dates[1]));
        $arrConfig['dimensions'] = [$request->get('dimension')];
        $metric_option = $this->arrUsableMetrics();
        $met_arr = array();
        foreach ($metric_option as $key => $value) {
            $temp_met_arr = array();
            $temp_met_arr["name"] = $key;
            $met_arr[] = $temp_met_arr;
        }
        $var_metric = json_encode($met_arr);
        $request_json = '{"dimensions":[{"name":"' . $request->get('dimension') . '"}],"metrics":' . $var_metric . ',"dateRanges":[{"startDate":"' . $arrConfig['StartDate'] . '","endDate":"' . $arrConfig['EndDate'] . '"}],"keepEmptyRows":true,"metricAggregations":["TOTAL"]}';
        $res_data           = $this->getReport($site, $request_json);
        $chart_arr = [];

        if (!isset($res_data->error)) {
            if (isset($res_data->totals) && !empty($res_data->totals) && isset($res_data->rows) && !empty($res_data->rows)) {
                foreach ($res_data->metricHeaders as $key => $value) {
                    if (strpos($res_data->totals[0]->metricValues[$key]->value, '.') !== false) {
                        $arrData[$value->name] = number_format($res_data->totals[0]->metricValues[$key]->value, 2);
                    } else {
                        $arrData[$value->name] = $res_data->totals[0]->metricValues[$key]->value;
                    }
                }
                foreach ($res_data->metricHeaders as $k => $value) {
                    $temp = array();
                    foreach ($res_data->rows as $key_rows => $value_rows) {
                        if (strpos($value_rows->metricValues[$k]->value, '.') !== false) {
                            $temp[$value_rows->dimensionValues[0]->value] = number_format($value_rows->metricValues[$k]->value, 2);
                        } else {
                            $temp[$value_rows->dimensionValues[0]->value] = $value_rows->metricValues[$k]->value;
                        }
                    }
                    $chart_arr[$value->name] = $temp;
                }
            } else {
                foreach ($res_data->metricHeaders as $key => $value) {
                    $arrData[$value->name] = 0;
                }
            }
            $arrResult['is_success'] = 1;
            $arrResult['data']       = $arrData;
            $arrResult['chart']       = $chart_arr;
            $arrResult['link']      = $html;
        } else {
            $arrResult['is_success'] = 0;
            $arrResult['data']       = [];
            $arrResult['chart']       = [];
            $arrResult['link']      = $html;
        }
        return $arrResult;
    }
}
