<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Site;
use App\Models\Plan;
use App\Models\Widget;
use App\Models\Settings;
use App\Models\Utility;
use Google_Client;
use Google_Service_Analytics;
use Session;
use Google_Service_AnalyticsReporting;
use Google_Service_AnalyticsReporting_DateRange;
use Google_Service_AnalyticsReporting_Metric;
use Google_Service_AnalyticsReporting_ReportRequest;
use Google_Service_AnalyticsReporting_GetReportsRequest;
use DataTables;
use DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SiteController extends Controller
{



    function getProperty(Request $request)
    {
        $token = Session::get("access_token");
        $id = $request->get('account_id');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://analyticsadmin.googleapis.com/v1beta/properties?filter=parent:accounts/' . $id,
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

            if (isset($res_data->properties)) {

                foreach ($res_data->properties as $item) {
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
                $arrResult['data']       = 'No Data Found.!';
            }
        } else {
            app('App\Http\Controllers\AnalyticsController')->genrate_accesstoken();
            $this->getProperty($request);
        }
        $output = "<option selected disabled>Select Account Id</option>";
        foreach ($arrResult['data'] as $value) {

            $output .= '<option value="' . $value['id'] . '" data-id="' . $value['name'] . '">' . $value['id'] . ' - ' . $value['name'] . '</option>';
        }

        return $output;
    }


    public function save_site(Request $request)
    {
        $user = Auth::user();
        $plan = Plan::find($user->plan);
        $expiryDate = Carbon::parse($user->plan_expire_date);
        $currentDate = Carbon::now();
        if ($plan) {
            if (\Auth::user()->user_type != "company") {

                $count = Site::where('created_by', $user->created_by)->count();
            } else {
                $count = Site::where('created_by', $user->id)->count();
            }

            if ($currentDate->lt($expiryDate)) {
                if ($plan->max_site > $count) {
                    $validation         = [];
                    $validation['account_id'] = 'required';
                    $validation['property_id']  = 'required';


                    $validator = \Validator::make(
                        $request->all(),
                        $validation
                    );

                    if ($validator->fails()) {
                        return redirect()->back()->with('error', $validator->errors()->first());
                    } else {
                        if (Session::get("access_token") != '' && !empty(Session::get("access_token"))) {
                            if (\Auth::user()->user_type == 'company') {
                                $check = Site::where('property_id', $request->get('property_id'))->where('created_by', $user->id)->first();
                            } else {
                                $check = Site::where('property_id', $request->get('property_id'))->where('created_by', $user->created_by)->first();
                            }
                            if ($check) {
                                return redirect()->back()->with('error', __('Site is already exist'));
                            } else {
                                $access_token = Settings::where('created_by', $user->id)->where('name', 'comapny_access_token')->first();
                                $refresh_token = Settings::where('created_by', $user->id)->where('name', 'comapny_refresh_token')->first();

                                $store = new Site();
                                $store->account_id = $request->get('account_id');
                                $store->site_name = $request->get('site_name');
                                $store->property_id = $request->get('property_id');
                                $store->property_name = $request->get('property_name');
                                $store->view_id = "";
                                $store->view_name = "";
                                $store->accessToken = $access_token->value;
                                $store->refreshToken = $refresh_token->value;
                                if (\Auth::user()->user_type == 'company') {

                                    $store->created_by = $user->id;
                                } else {
                                    $store->created_by = $user->created_by;
                                }

                                $store->save();
                                if ($store) {
                                    $store->share_setting = '{"standard":{"new_user_report":1,"user_report":1,"bounce_rate_report":1,"session_duration_report":1,"user_location_report":1,"live_user_report":1,"page_report":1,"device_report":1,"is_password":0,"password":null},"channel":{"firstUserDefaultChannelGroup":1,"sessionDefaultChannelGroup":1,"is_password":0,"password":null},"audience":{"country":1,"audienceName":1,"newVsReturning":1,"language":1,"is_password":0,"password":null},"page":{"pageTitle":1,"landingPage":1,"pagePath":1,"is_password":0,"password":null},"seo":{"browser":1,"operatingSystem":1,"mobileDeviceBranding":1,"is_password":0,"password":null},"dashboard":{"new_user_report":1,"user_report":1,"bounce_rate_report":1,"session_duration_report":1,"user_location_report":1,"live_user_report":1,"page_report":1,"device_report":1,"is_password":0,"password":null},"custom":{"share_metric":"activeUsers","share_metric_name":"Active users","share_dimension":"browser","share_dimension_name":"Browser","is_password":1,"password":"MTIzNDU2Nzg="}}';
                                    $store->save();
                                    return redirect()->route('dashboard')->with('success', __('Site Added Successfully.'));
                                } else {

                                    return redirect()->back()->with('error', __('Something is wrong.'));
                                }
                            }
                        } else {
                            return redirect()->back()->with('error', __('Session is expired.'));
                        }
                    }
                } else {
                    return redirect()->back()->with('error', __('Your Site limit is over, Please upgrade plan.'));
                }
            } else {
                return redirect()->back()->with('error', __('Your plan is expired. Please upgrade plan.'));
            }
        } else {
            return redirect()->back()->with('error', __('Default plan is deleted.'));
        }
    }

    public function site_standard($id)
    {
        $user = Auth::user();

        if ($user->user_type == "company") {
            $all_site = Site::where('created_by', $user->id)->get();

            if ($id == 0) {
                $data = Site::where('created_by', $user->id)->first();
            } else {
                $data = Site::where('id', $id)->where('created_by', $user->id)->first();
            }
        } else {
            $all_site = Site::where('created_by', $user->created_by)->get();

            if ($id == 0) {
                $data = Site::where('created_by', $user->created_by)->first();
            } else {
                $data = Site::where('id', $id)->where('created_by', $user->created_by)->first();
            }
        }

        return view('admin.site.site-standard')->with('data', $data)->with("all_site", $all_site);
    }
    public function manage_site()
    {

        return view('admin.site.manage-site');
    }

    public function site_list()
    {
        $data = Site::get();
        return DataTables::of($data)
            ->editColumn('site_name', function ($data) {
                return $data->site_name;
            })

            ->editColumn('account_id', function ($data) {
                return $data->account_id;
            })
            ->editColumn('property_id', function ($data) {
                return $data->property_id;
            })
            ->editColumn('view_id', function ($data) {
                return $data->view_id;
            })
            ->editColumn('action', function ($data) {
                $edit = route('edit-site', ['id' => $data->id]);
                $delete = route('delete-site', ['id' => $data->id]);
                return '

                <a class="btn btn-info"  data-bs-toggle="modal"  onclick="edit_site(' . $data->id . ')" data-bs-target="#edit_site_modal">Update</a>


                <a onclick="delete_record(' . "'" . $delete . "'" . ')" rel="tooltip"  class="btn btn-danger" data-original-title="Remove" style="margin-right: 10px;color:white !important">Delete</a>';
            })
            ->addIndexColumn()
            ->make(true);
    }

    public function edit_site($id)
    {
        $data = Site::where('id', $id)->first();

        return $data;
    }
    public function delete_site($id)
    {
        $data = Site::where('id', $id)->first();
        if ($data) {
            $widget = Widget::where('site_id', $data->id)->delete();
            $data->delete();

            return redirect()->route('manage-site')->with('success', __('Site Deleted Successfully.'));
        } else {

            return redirect()->back()->with('error', __('Something is wrong.'));
        }
    }

    public function site_dashboard_link($id, $type, $lang, Request $request)
    {
        try {

            $id = \Illuminate\Support\Facades\Crypt::decrypt($id);

            $data = Site::where('id', $id)->first();

            $json = json_decode($data->share_setting);

            \Session::put('lang', $lang);

            \App::setLocale($lang);

            $companySettings['title_text']      = \DB::table('settings')->where('created_by', $data->created_by)->where('name', 'title_text')->first();
            $companySettings['footer_text']     = \DB::table('settings')->where('created_by', $data->created_by)->where('name', 'footer_text')->first();
            $companySettings['company_favicon'] = \DB::table('settings')->where('created_by', $data->created_by)->where('name', 'company_favicon')->first();
            $companySettings['company_logo']    = \DB::table('settings')->where('created_by', $data->created_by)->where('name', 'company_logo')->first();
            $languages                          = Utility::languages();

            $currantLang = \Session::get('lang');
            if (empty($currantLang)) {
                $currantLang = !empty($data->createdBy) ? $data->createdBy->lang : 'en';
            }



            if (\Session::get('copy_pass_true' . $id) == $json->$type->password . '-' . $id) {

                return view('share-link.site-standard')->with('data', $data)->with('json', $json->$type)->with('companySettings', $companySettings)->with('currantLang', $currantLang)->with('languages', $languages);
            } else {

                if (!isset($json->$type->is_password) || $json->$type->is_password != '1') {

                    return view('share-link.site-standard')->with('data', $data)->with('json', $json->$type)->with('companySettings', $companySettings)->with('currantLang', $currantLang)->with('languages', $languages);
                } elseif (isset($json->$type->is_password) && $json->$type->is_password == '1' && $request->password == base64_decode($json->$type->password)) {

                    \Session::put('copy_pass_true' . $id, $json->$type->password . '-' . $id);

                    return view('share-link.site-standard')->with('data', $data)->with('json', $json->$type)->with('companySettings', $companySettings)->with('currantLang', $currantLang)->with('languages', $languages);
                } else {

                    $route = 'site.dashboard.link';
                    $param = [\Illuminate\Support\Facades\Crypt::encrypt($id), 'en'];
                    return view('share-link.share_link_password', compact('id', 'route', 'param'));
                }
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Not found');
        }
    }

    public function site_analyse_link($id, $type, $lang, Request $request)
    {
        try {
            $id = \Illuminate\Support\Facades\Crypt::decrypt($id);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', __('This Page Not Found.'));
        }
        $type = \Illuminate\Support\Facades\Crypt::decrypt($type);
        $data = Site::where('id', $id)->first();
        $json = json_decode($data->share_setting);

        $status = 0;
        foreach ($json as $key => $value) {
            if ($key == $type) {
                $status = 1;
            }
        }
        if ($status != 1) {
            return view('share-link.error');
        }
        \Session::put('lang', $lang);
        \App::setLocale($lang);
        $metric_option = $this->arrUsableMetrics();
        $companySettings['title_text']      = \DB::table('settings')->where('created_by', $data->created_by)->where('name', 'title_text')->first();
        $companySettings['footer_text']     = \DB::table('settings')->where('created_by', $data->created_by)->where('name', 'footer_text')->first();
        $companySettings['company_favicon'] = \DB::table('settings')->where('created_by', $data->created_by)->where('name', 'company_favicon')->first();
        $companySettings['company_logo']    = \DB::table('settings')->where('created_by', $data->created_by)->where('name', 'company_logo')->first();
        $languages                          = Utility::languages();

        $currantLang = \Session::get('lang');
        if (empty($currantLang)) {
            $currantLang = !empty($data->createdBy) ? $data->createdBy->lang : 'en';
        }

        if (\Session::get('copy_pass_true' . $id) == $json->$type->password . '-' . $id) {
            $dimension_option = $this->dimension($type);
            return view('share-link.' . $type . '')->with('type', $type)->with('data', $data)->with("metric_option", $metric_option)->with('json', $json->$type)->with('companySettings', $companySettings)->with('currantLang', $currantLang)->with('languages', $languages)->with("dimension_option", $dimension_option);
        } else {
            $error = '';
            if (!isset($json->$type->is_password) || $json->$type->is_password != '1') {
                $error = '';
                $dimension_option = $this->dimension($type);
                return view('share-link.' . $type . '')->with('type', $type)->with('data', $data)->with("metric_option", $metric_option)->with('json', $json->$type)->with('companySettings', $companySettings)->with('currantLang', $currantLang)->with('languages', $languages)->with("dimension_option", $dimension_option);
            } elseif (isset($json->$type->is_password) && $json->$type->is_password == '1' && $request->password == base64_decode($json->$type->password)) {
                $error = '';
                \Session::put('copy_pass_true' . $id, $json->$type->password . '-' . $id);
                $dimension_option = $this->dimension($type);
                return view('share-link.' . $type . '')->with('type', $type)->with('data', $data)->with("metric_option", $metric_option)->with('json', $json->$type)->with('companySettings', $companySettings)->with('currantLang', $currantLang)->with('languages', $languages)->with("dimension_option", $dimension_option);
            } else {
                if (!empty($request) && $request->password) {
                    $error = 'Please enter valid password';
                }
                $route = 'site.analyse.link';
                $dimension_option = $this->dimension($type);
                $param = [\Illuminate\Support\Facades\Crypt::encrypt($id), \Illuminate\Support\Facades\Crypt::encrypt($type), 'en'];
                return view('share-link.share_link_password', compact('id', 'type', 'route', 'param', 'error'));


            }
        }
    }
    public function show_site_share_setting($id, $type)
    {

        $site = Site::where('id', $id)->first();
        $lng = !empty($site) ? $site->createdBy->lang : 'en';
        if ($type == "dashboard") {
            $html = ' <a href="#"id="' . route("site.dashboard.link", [\Illuminate\Support\Facades\Crypt::encrypt($site->id), 'dashboard', $lng]) . '" class="btn  btn-primary"  onclick="copyToClipboard(this)" data-bs-toggle="tooltip"  data-original-title="{{__("Click to copy")}}">Share Report</a>';
        } elseif ($type == "standard") {
            $html = ' <a href="#"id="' . route("site.dashboard.link", [\Illuminate\Support\Facades\Crypt::encrypt($site->id), 'standard', $lng]) . '" class="btn  btn-primary"  onclick="copyToClipboard(this)" data-bs-toggle="tooltip"  data-original-title="{{__("Click to copy")}}">Share Report</a>';
        } else {
            $html = ' <a href="#"id="' . route("site.analyse.link", [\Illuminate\Support\Facades\Crypt::encrypt($site->id), \Illuminate\Support\Facades\Crypt::encrypt($type), $lng]) . '" class="btn  btn-primary"  onclick="copyToClipboard(this)" data-bs-toggle="tooltip"  data-original-title="{{__("Click to copy")}}">Share Report</a>';
        }



        $main_array = json_decode($site->share_setting);
        $json_arr = array();
        $data = array();
        if (!empty($main_array)) {
            foreach ($main_array as $key => $value) {
                if ($key == $type) {
                    if ($value->is_password == 1) {
                        $value->password = base64_decode($value->password);
                    }

                    $json_arr = $value;
                }
            }
        }
        $data['type'] = $type;
        $data['json'] = $json_arr;
        $data['link'] = $html;

        return $data;
    }
    public function site_share_setting(Request $request, $type)
    {
        $site = Site::where('id', $request->share_site)->first();
        $lng = !empty($site) ? $site->createdBy->lang : 'en';

        $main_json = $site->share_setting;
        $main_array = json_decode($main_json);

        if (!empty($main_array)) {
            foreach ($main_array as $key => $value) {
                if ($key == $type) {
                    unset($main_array->$key);
                }
            }
        }
        if ($type == "standard") {
            $data = array(
                "new_user_report" => $request->has('new_user_report') ? 1 : 0,
                "user_report" => $request->has('user_report') ? 1 : 0,
                "bounce_rate_report" => $request->has('bounce_rate_report') ? 1 : 0,
                "session_duration_report" => $request->has('session_duration_report') ? 1 : 0,
                "user_location_report" => $request->has('user_location_report') ? 1 : 0,
                "live_user_report" => $request->has('live_user_report') ? 1 : 0,
                "page_report" => $request->has('page_report') ? 1 : 0,
                "device_report" => $request->has('device_report') ? 1 : 0,
                "is_password" => $request->has('is_password') ? 1 : 0,
                "password" => $request->has('is_password') ? base64_encode($request->password) : null,
            );
        }

        if ($type == "dashboard") {
            $data = array(
                "new_user_report" => $request->has('new_user_report') ? 1 : 0,
                "user_report" => $request->has('user_report') ? 1 : 0,
                "bounce_rate_report" => $request->has('bounce_rate_report') ? 1 : 0,
                "session_duration_report" => $request->has('session_duration_report') ? 1 : 0,
                "user_location_report" => $request->has('user_location_report') ? 1 : 0,
                "live_user_report" => $request->has('live_user_report') ? 1 : 0,
                "page_report" => $request->has('page_report') ? 1 : 0,
                "device_report" => $request->has('device_report') ? 1 : 0,
                "is_password" => $request->has('is_password') ? 1 : 0,
                "password" => $request->has('is_password') ? base64_encode($request->password) : null,
            );
        }
        if ($type == "standard") {
            $data = array(
                "new_user_report" => $request->has('new_user_report') ? 1 : 0,
                "user_report" => $request->has('user_report') ? 1 : 0,
                "bounce_rate_report" => $request->has('bounce_rate_report') ? 1 : 0,
                "session_duration_report" => $request->has('session_duration_report') ? 1 : 0,
                "user_location_report" => $request->has('user_location_report') ? 1 : 0,
                "live_user_report" => $request->has('live_user_report') ? 1 : 0,
                "page_report" => $request->has('page_report') ? 1 : 0,
                "device_report" => $request->has('device_report') ? 1 : 0,
                "is_password" => $request->has('is_password') ? 1 : 0,
                "password" => $request->has('is_password') ? base64_encode($request->password) : null,
            );
        }
        if ($type == "channel") {
            $dimension_option = $this->dimension('channel');
            $data = array();
            foreach ($dimension_option as $key => $value) {
                $data[$key] = $request->has($key) ? 1 : 0;
            }
            $data["is_password"] = $request->has('is_password') ? 1 : 0;
            $data["password"] = $request->has('is_password') ? base64_encode($request->password) : null;
        }
        if ($type == "audience") {
            $dimension_option = $this->dimension('audience');
            $data = array();
            foreach ($dimension_option as $key => $value) {
                $data[$key] = $request->has($key) ? 1 : 0;
            }
            $data["is_password"] = $request->has('is_password') ? 1 : 0;
            $data["password"] = $request->has('is_password') ? base64_encode($request->password) : null;
        }
        if ($type == "page") {
            $dimension_option = $this->dimension('page');
            $data = array();
            foreach ($dimension_option as $key => $value) {
                $data[$key] = $request->has($key) ? 1 : 0;
            }
            $data["is_password"] = $request->has('is_password') ? 1 : 0;
            $data["password"] = $request->has('is_password') ? base64_encode($request->password) : null;
        }
        if ($type == "seo") {
            $dimension_option = $this->dimension('seo');
            $data = array();
            foreach ($dimension_option as $key => $value) {
                $data[$key] = $request->has($key) ? 1 : 0;
            }
            $data["is_password"] = $request->has('is_password') ? 1 : 0;
            $data["password"] = $request->has('is_password') ? base64_encode($request->password) : null;
        }
        if ($type == "custom") {

            $data = array(
                "share_metric" => $request->share_met,
                "share_metric_name" => $request->share_metric,
                "share_dimension" => $request->share_dim,
                "share_dimension_name" => $request->share_dimension,
                "is_password" => $request->has('is_password') ? 1 : 0,
                "password" => $request->has('is_password') ? base64_encode($request->password) : null,
            );
        }
        
        if (!empty($main_array)) {
            
            $main_array->$type = $data;
        } else {
            $main_array[$type] = $data;
        }
        $json = json_encode($main_array);
        $site->share_setting = $json;
        $site->save();
        if ($type == "seo") {
            $type = 'seo-analysis';
        }
        if ($type == "standard") {
            return redirect()->back()->with('success', __('Setting Save Successfully'));
        } elseif ($type == "custom") {
            $html = '<a href="#"id="' . route("site.analyse.link", [\Illuminate\Support\Facades\Crypt::encrypt($site->id), \Illuminate\Support\Facades\Crypt::encrypt($type), $lng]) . '" class="btn  btn-primary"  onclick="copyToClipboard(this)" data-bs-toggle="tooltip"  data-original-title="{{__("Click to copy")}}">Share Report</a>';
            return $html;
        } else {
            return redirect()->route($type)->with('success', __('Setting Save Successfully'));
        }
    }
}
