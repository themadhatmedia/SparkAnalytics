<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Site;
use App\Models\Plan;
use App\Models\Widget;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Session;
class CustomController extends Controller
{
    public function custom_dashboard()
    {
        if(\Auth::user()->can('show custom analytic'))
        {
            $metrics=$this->metric_option();
            $user=Auth::user();
            if(\Auth::user()->user_type !='company')
            {
                $site_data=Site::where('created_by',$user->created_by)->get();
            }
            else
            {
                $site_data=Site::where('created_by',$user->id)->get();
            }
            
            return view('admin.custom.default')->with("site_data",$site_data)->with("metrics",$metrics); 
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    	
    }

     public function get_dimension()
    {
    	$metrics=$this->dimension();

                                    
    	$html="<option selected disabled value='0'>Dimension</option>";
    	foreach ($metrics as $key => $value) {
    		$html.="<option value='".$key."' data-name=".$value.">".$value."</option>";
    	}

    	return $html;
    	
    }

    public function custom_chart(Request $request)
    {

    	$site=Site::where("id",$request->get('site_id'))->first();
    	$metrics    = ucfirst(str_replace("ga:", "", $request->get('metric')));
        $dimension  = ucfirst(str_replace("ga:", "", $request->get('dimension')));
        $arrMetrics = [$request->get('metric') => $request->get('metric')];

        $arrParam = $this->getDurationFromText($request->get('chart_duration'));

        $tmpDate = explode("-", $request->get('chart_duration'));

        $arrConfig               = [];
        $arrConfig['StartDate']  = date('Y-m-d', strtotime($tmpDate[0]));
        $arrConfig['EndDate']    = date('Y-m-d', strtotime($tmpDate[1]));
        
        $request_json='{"dimensions":[{"name":"'.$request->get('dimension').'"}],"metrics":[{"name":"'.$request->get('metric').'"}],"dateRanges":[{"startDate":"'.$arrConfig['StartDate'].'","endDate":"'.$arrConfig['EndDate'].'"}],"limit":"13","keepEmptyRows":true}';
        $res_data=$this->getReport($site, $request_json);

        if(!isset($res_data->error))
        {
            $arrData         = [];
            if(isset($res_data->rows))
            {
                foreach($res_data->rows as $key => $record)
                {
                    
                    $arrData[$res_data->rows[$key]->dimensionValues[0]->value]= intval($res_data->rows[$key]->metricValues[0]->value);
                }

                $arrReturn               = [];
                $arrReturn['labels']     = array_keys($arrData);
                $arrReturn['datasets'][] = [
                    'label' => $dimension,
                    'data' => array_values($arrData),
                    'backgroundColor' => "rgba(94, 113, 228, 1)",
                ];
                $arrResult['is_success'] = 1;
                $arrResult['data']       = $arrReturn;
            }
            else
            {
                
                $arrResult['is_success'] = 0;
                $arrResult['message']    = __('Data not found');
            }
        }
        else
        {
            $arrResult['is_success'] = 0;
            $arrResult['message']    = $res_data->error->message;
        }
        return $arrResult;
    }

    public function custom_share_chart(Request $request)
    {

        $site=Site::where("id",$request->get('id'))->first();
        $json=json_decode($site->share_setting);
       
        $metrics    = ucfirst($json->custom->share_metric);
        $dimension  = ucfirst($json->custom->share_dimension);
        $arrMetrics = [$json->custom->share_metric => $json->custom->share_metric];

        $arrParam = $this->getDurationFromText($request->get('chart_duration'));

        $tmpDate = explode("-", $request->get('chart_duration'));

        $arrConfig               = [];
        $arrConfig['StartDate']  = date('Y-m-d', strtotime($tmpDate[0]));
        $arrConfig['EndDate']    = date('Y-m-d', strtotime($tmpDate[1]));
        $arrConfig['dimensions'] = [$json->custom->share_dimension];
        $arrConfig['sort']       = [
            'field' => $json->custom->share_metric,
            'order' => 'DESCENDING',
        ];
        $request_json='{"dimensions":[{"name":"'.$json->custom->share_dimension.'"}],"metrics":[{"name":"'.$json->custom->share_metric.'"}],"dateRanges":[{"startDate":"'.$arrConfig['StartDate'].'","endDate":"'.$arrConfig['EndDate'].'"}],"limit":"13","keepEmptyRows":true}';
        $res_data=$this->getReport($site, $request_json);
       
        if(!isset($res_data->error))
        {
            $arrData         = [];
            if(isset($res_data->rows))
            {
                foreach($res_data->rows as $key => $record)
                {
                    
                    $arrData[$res_data->rows[$key]->dimensionValues[0]->value]= intval($res_data->rows[$key]->metricValues[0]->value);
                }

                $arrReturn               = [];
                $arrReturn['labels']     = array_keys($arrData);
                $arrReturn['datasets'][] = [
                    'label' => $dimension,
                    'data' => array_values($arrData),
                    'backgroundColor' => "rgba(94, 113, 228, 1)",
                ];
                $arrResult['is_success'] = 1;
                $arrResult['data']       = $arrReturn;
            }
            else
            {
                
                $arrResult['is_success'] = 0;
                $arrResult['message']    = __('Data not found');
            }
        }
        else
        {
            $arrResult['is_success'] = 0;
            $arrResult['message']    =$res_data->error->message;
        }
        return $arrResult;
    }
}
