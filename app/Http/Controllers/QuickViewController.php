<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\Settings;
use App\Models\User;
use App\Models\Utility;
use App\Models\Site;
use App\Models\Credintials;
use Artisan;
use DB;
use Illuminate\Support\Facades\Auth;

class QuickViewController extends Controller
{
    public function quick_view($id)
    {
        $user = Auth::user();
        if (\Auth::user()->can('show quick view')) {
            if ((\Auth::user()->user_type == 'company')) {
                $site_data = Site::where('created_by', $user->id)->get();
            } else {
                $site_data = Site::where('created_by', $user->created_by)->get();
            }
            $metrics = $this->metric_option();
            $arrTimeframe = $this->arrTimeframe();
            return view('admin.quick_view.default')->with('site_data', $site_data)->with("metrics", $metrics)->with("arrTimeframe", $arrTimeframe);
        } else {
            return redirect()->back()->with('error', __('Permission Denied'));
        }
    }


    public function quick_view_data(Request $request)
    {
        $user = Auth::user();

        $siteid = $request->get('siteid');
        $site = Site::where("id", $siteid)->first();
        if ($site) {
            $graph = $site->graph;
            $lng = 'en';
            if ($user) {
                $html = ' <a href="#"id="' . route("quickview.share.link", [\Illuminate\Support\Facades\Crypt::encrypt($user->id), $lng]) . '" class="btn  btn-primary"  onclick="copyToClipboard(this)" data-bs-toggle="tooltip"  data-original-title="{{__("Click to copy")}}">Share Report</a>';
            } else {
                $html = ' <a href="#"></a>';
            }

            $arrCommonMetrics = $this->metric_option();
            $arrMetrics       = [
                $site->top_left => $site->top_left,
                $site->top_right => $site->top_right,
                $site->bottom_left => $site->bottom_left,
                $site->bottom_right => $site->bottom_right,
            ];
            $arrMet           = [
                'top_left' => $site->top_left,
                'top_right' => $site->top_right,
                'bottom_left' => $site->bottom_left,
                'bottom_right' => $site->bottom_right,
            ];

            $arrConfig              = [];
            $arrConfig['StartDate'] = $site->timeframe;
            $arrConfig['EndDate']   = 'today';
            $request_json = '{"metrics":[{"name":"' . $site->top_left . '"},{"name":"' . $site->top_right . '"},{"name":"' . $site->bottom_left . '"},{"name":"' . $site->bottom_right . '"}],"dateRanges":[{"startDate":"' . $site->timeframe . '","endDate":"today"}],"keepEmptyRows":true}';

            $data = $this->getReport($site, $request_json);
            $arrData        = [];
            $site->graph        = $arrCommonMetrics[$site->graph];
            $arrResult['site']       = $site;
            $arrResult['link']       = $html;
            $i = 0;
            if (!isset($data->error)) {
                if (isset($data->rows) && !empty($data->rows)) {
                    foreach ($arrMet as $key => $value) {
                        if (strpos($data->rows[0]->metricValues[$i]->value, '.') !== false) {

                            $arrData[$key] = array($value => number_format($data->rows[0]->metricValues[$i]->value, 2));
                        } else {
                            $arrData[$key] = array($value => $data->rows[0]->metricValues[$i]->value);
                        }
                        $i++;
                    }
                } else {
                    foreach ($arrMet as $key => $value) {

                        $arrData[$key] = array($value => 0);
                        $i++;
                    }
                }
                $arrData['metrics'] = $arrData;


                $arrResult['data']['is_success'] = 1;
                $arrResult['data']['data']       = $arrData;
            } else {
                if ($data->error->code == 401) {
                    app('App\Http\Controllers\AnalyticsController')->genrate_accesstoken();
                    $this->quick_view_data($request);
                }
                $arrResult['data']['is_success'] = 0;
                $arrResult['data']['message'] = $data->error->message;
            }
            $arrChartParam = $this->getDurationFromText($site->timeframe);
            $chart_request_json = '{"dimensions":[{"name":"date"}],"metrics":[{"name":"' . $graph . '"}],"dateRanges":[{"startDate":"' . $site->timeframe . '","endDate":"today"}],"orderBys":[{"dimension":{"orderType":"NUMERIC","dimensionName":"date"}}],"keepEmptyRows":true}';
            $chartdata = $this->getReport($site, $chart_request_json);
            $label = [];
            $dataset = [];
            $sum = 0;
            if (!isset($chartdata->error)) {
                if (isset($chartdata->rows) && !empty($data->rows)) {
                    foreach ($arrChartParam['arrField'] as $ke => $val) {

                        foreach ($chartdata->rows as $key => $value) {
                            if ($chartdata->rows[$key]->dimensionValues[0]->value == $ke) {
                                $res_data = $chartdata->rows[$key]->metricValues[0]->value;
                                $sum += $chartdata->rows[$key]->metricValues[0]->value;

                                break;
                            } else {

                                $res_data = 0;
                            }
                        }
                        $label[] = $val;
                        $dataset[] = $res_data;
                    }
                } else {
                    foreach ($arrChartParam['arrField'] as $ke => $val) {
                        $label[] = $val;
                        $dataset[] = 0;
                    }
                }
                $arrReturn             = [];
                $arrReturn['labels']   = array_reverse(array_values($label));
                $arrReturn['datasets'] = array_reverse($dataset);
                $arrResult['chart']['is_success'] = 1;
                $arrResult['chart']['data']       = $arrReturn;
            } else {
                if ($chartdata->error->code == 401) {
                    app('App\Http\Controllers\AnalyticsController')->genrate_accesstoken();
                    $this->quick_view_data($request);
                }
                $arrResult['data']['is_success'] = 0;
                $arrResult['data']['message'] = $chartdata->error->message;
            }
            return $arrResult;
        }
    }
    public function edit_quick_view_data(Request $request)
    {
        $id = $request->get('id');
        $data = Site::where('id', $id)->first();
        return $data;
    }

    public function save_quick_view_data(Request $request)
    {

        if (\Auth::user()->can('edit site')) {
            $store = Site::where('id', $_POST['edit_id'])->first();
            $store->timeframe = $_POST['time_frame'];
            $store->graph = $_POST['graph'];
            $store->graph_type = $_POST['graph_type'];
            $store->graph_color = $_POST['graph_color'];
            $store->top_left = $_POST['top_left'];
            $store->top_right = $_POST['top_right'];
            $store->bottom_left = $_POST['bottom_left'];
            $store->bottom_right = $_POST['bottom_right'];
            $store->save();
            return response()->json(["stutas" => 1, 'success' => __('Data Update Successfully'), 'data' => $store]);
        } else {
            return response()->json(["stutas" => 0, 'error' => __('Permission Denied.')]);
        }
    }


    public function show_quickview_share_setting()
    {
        $user = Auth::user();
        if ($user->user_type == "company") {
            $site = Site::select('id', 'site_name')->where('created_by', $user->id)->get();
        } else {
            $site = Site::select('id', 'site_name')->where('created_by', $user->created_by)->get();
        }


        $lng = 'en';
        $html = ' <a href="#"id="' . route("quickview.share.link", [\Illuminate\Support\Facades\Crypt::encrypt($user->id), $lng]) . '" class="btn  btn-primary"  onclick="copyToClipboard(this)" data-bs-toggle="tooltip"  data-original-title="{{__("Click to copy")}}">Share Report</a>';



        $data = array();
        $json = json_decode($user->quick_view_share);
        if (isset($json->password) && !empty($json->password)) {

            $json->password = base64_decode($json->password);
        }

        $data['allowed_setting'] = $json;
        $data['json'] = $site;
        $data['link'] = $html;

        return $data;
    }
    public function quickview_share_setting(Request $request)
    {
        $user = Auth::user();
        $res = $request->all();
        $data = array();
        $ids = array();
        foreach ($res as $key => $value) {
            if ($key != "_token" && $key != "user_id" && $key != "is_password" && $key != "password") {
                $ids[] = $value;
            }
        }


        $data = array(
            "allowed_site_id" => implode(",", $ids),
            "is_password" => $request->has('is_password') ? 1 : 0,
            "password" => $request->has('is_password') ? base64_encode($request->password) : null,
        );
        $json = json_encode($data);

        $user->quick_view_share = $json;
        $user->save();

        return redirect()->route('quick-view', ['0'])->with('success', __('Setting Save Successfully'));
    }

    public function quickview_link($id, $lang, Request $request)
    {
        try{
        $id = \Illuminate\Support\Facades\Crypt::decrypt($id);
        
        $user = User::where('id', $id)->first();
        if ($user->user_type == "company") {
            $u_id = $user->id;
        } else {
            $u_id = $user->created_by;
        }
        
        $data = array();
        $json = json_decode($user->quick_view_share);

        $ids = explode(",", $json->allowed_site_id);
        foreach ($ids as $value) {
            $site = Site::where('id', $value)->first();
            $data[] = $site;
        }

        \Session::put('lang', $lang);

        \App::setLocale($lang);

        $companySettings['title_text']      = \DB::table('settings')->where('created_by', $u_id)->where('name', 'title_text')->first();
        $companySettings['footer_text']     = \DB::table('settings')->where('created_by', $u_id)->where('name', 'footer_text')->first();
        $companySettings['company_favicon'] = \DB::table('settings')->where('created_by', $u_id)->where('name', 'company_favicon')->first();
        $companySettings['company_logo']    = \DB::table('settings')->where('created_by', $u_id)->where('name', 'company_logo')->first();
        $languages                          = Utility::languages();

        $currantLang = \Session::get('lang');
        if (empty($currantLang)) {
            $currantLang = !empty($user) ? $user->lang : 'en';
        }


        if (\Session::get('copy_pass_true' . $id) == $json->password . '-' . $id) {

            return view('share-link.quick-view')->with('data', $user)->with('site_data', $data)->with('companySettings', $companySettings)->with('currantLang', $currantLang)->with('languages', $languages);;
        } else {

            if (!isset($json->is_password) || $json->is_password != '1') {

                return view('share-link.quick-view')->with('data', $user)->with('site_data', $data)->with('companySettings', $companySettings)->with('currantLang', $currantLang)->with('languages', $languages);;
            } elseif (isset($json->is_password) && $json->is_password == '1' && $request->password == base64_decode($json->password)) {

                \Session::put('copy_pass_true' . $id, $json->password . '-' . $id);

                return view('share-link.quick-view')->with('data', $user)->with('site_data', $data)->with('companySettings', $companySettings)->with('currantLang', $currantLang)->with('languages', $languages);;
            } else {

                $route = 'quickview.share.link';
                $param = [\Illuminate\Support\Facades\Crypt::encrypt($id), 'en'];

                return view('share-link.share_link_password', compact('id', 'route', 'param'));
            }
        }
    }catch (Exception $e){
        return redirect()->back()->with('error', 'Not found');
    }
    }
}
