<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Site;
use App\Models\Widget;
use Session;
class ChartController extends Controller
{
	 
	public function get_chart_data(Request $request)
	{

		
		$siteid=$request->get('siteid');
		
		$duration=$request->get('chart_duration');
		$type=$request->get('type');
		$site=Site::where("id",$siteid)->first();
		$arrParam =  $this->getDurationFromText($duration);
		if($site)
        {
        	app('App\Http\Controllers\AnalyticsController')->genrate_accesstoken();
        	try {


        		if($type=='get_user_data')
        		{
        			$metrics='activeUsers';
        			$request_json='{"dimensions":[{"name":"'.$arrParam['dimension'].'"}],"metrics":[{"name":"'.$metrics.'"}],"dateRanges":[{"startDate":"'.$arrParam['StartDate'].'","endDate":"'.$arrParam['EndDate'].'"}],"orderBys":[{"dimension":{"orderType":"NUMERIC","dimensionName":"'.$arrParam['dimension'].'"}}],"keepEmptyRows":true}';
        		}
        		if($type=='bounceRateChart')
        		{
        			$metrics='bounceRate';
        			$request_json='{"dimensions":[{"name":"'.$arrParam['dimension'].'"}],"metrics":[{"name":"'.$metrics.'"}],"dateRanges":[{"startDate":"'.$arrParam['StartDate'].'","endDate":"'.$arrParam['EndDate'].'"}],"orderBys":[{"dimension":{"orderType":"NUMERIC","dimensionName":"'.$arrParam['dimension'].'"}}],"keepEmptyRows":true}';
        		}
        		if($type=="sessionDuration")
				{
					$metrics='averageSessionDuration';
        			$request_json='{"dimensions":[{"name":"'.$arrParam['dimension'].'"}],"metrics":[{"name":"'.$metrics.'"}],"dateRanges":[{"startDate":"'.$arrParam['StartDate'].'","endDate":"'.$arrParam['EndDate'].'"}],"orderBys":[{"dimension":{"orderType":"NUMERIC","dimensionName":"'.$arrParam['dimension'].'"}}],"keepEmptyRows":true}';
				}
				if($type=='session_by_device')
				{
					$metrics='averageSessionDuration';
					$request_json='{"dimensions":[{"name":"deviceCategory"}],"metrics":[{"name":"sessions"}],"dateRanges":[{"startDate":"2022-01-01","endDate":"2023-07-19"}],"keepEmptyRows":true,"metricAggregations":["TOTAL"]}';
				}
				if($type=="user-timeline-chart")
				{
					$request_json='{"dimensions":[{"name":"'.$arrParam['dimension'].'"}],"metrics":[{"name":"activeUsers"},{"name":"newUsers"}],"dateRanges":[{"startDate":"'.$arrParam['StartDate'].'","endDate":"'.$arrParam['EndDate'].'"}],"orderBys":[{"dimension":{"orderType":"NUMERIC","dimensionName":"'.$arrParam['dimension'].'"}}],"keepEmptyRows":true}';
				}
				if($type=="mapcontainer")
				{
					$request_json='{"dimensions":[{"name":"country"}],"metrics":[{"name":"activeUsers"}],"dateRanges":[{"startDate":"'.$arrParam['StartDate'].'","endDate":"'.$arrParam['EndDate'].'"}],"keepEmptyRows":true}';
				}


	    		$lng=!empty($site)?$site->createdBy->lang:'en';
	    		$html=' <a href="#"id="'.route("site.dashboard.link",[\Illuminate\Support\Facades\Crypt::encrypt($site->id),$request->chart_page,$lng]).'" class="btn  btn-primary"  onclick="copyToClipboard(this)" data-bs-toggle="tooltip"  data-original-title="Click to copy">Share Report</a>';
	    		

            	$data=$this->getReport($site, $request_json);

	    		$label=[];
	    		$dataset=[];
	    		$sum=0;
	    		$arrReturn             = [];
	    		if(!isset($data->error))
	    		{
	    			if($type=='user-timeline-chart')
			    	{
			    		$tempfirst=[];
			    		$sum_tempfirst=0;
			    		$tempsecond=[];
			    		$sum_tempsecond=0;
			    		foreach ($arrParam['arrField'] as $ke => $val) {
			    			if(isset($data->rows) && !empty($data->rows))
			    			{
				    			foreach ($data->rows as $key => $value) {
				    				if ($data->rows[$key]->dimensionValues[0]->value ==$ke)
				    				{
				    					$res_data_first=$data->rows[$key]->metricValues[0]->value;
				    					$sum_tempfirst+=$data->rows[$key]->metricValues[0]->value;
				    					$res_data_second=$data->rows[$key]->metricValues[1]->value;
				    					$sum_tempsecond+=$data->rows[$key]->metricValues[1]->value;
				    					break;
				    				}
				    				else
				    				{
				    
				    					$res_data_first=0;
				    					$res_data_second=0;
				    				}
				    			}
				    		}
				    		else
				    		{
				    			$res_data_first=0;
				    			$res_data_second=0;
				    		}
			    			$label[]=$val;
			    			$tempfirst[]=(int)$res_data_first;
			    			$tempsecond[]=(int)$res_data_second;
			    		}
			    		$dataset[0]=array("label"=>"Active Users","data"=>array_reverse($tempfirst),"backgroundColor"=>"transparent","borderColor"=>"#5e72e4","borderWidth"=>4,"tension"=>0.4);
			    		$dataset[1]=array("label"=>"New Users","data"=>array_reverse($tempsecond),"backgroundColor"=>"transparent","borderColor"=>"#11cdef","borderWidth"=>4,"tension"=>0.4);
		            	$arrResult['total']      = array("Active_Users" =>$sum_tempfirst,"New_Users" =>$sum_tempsecond);
			    		
			    		$arrReturn['labels']   = array_reverse($label);
			    		$arrReturn['datasets']   = $dataset;
			    	}
	    			elseif($type=='session_by_device')
					{
			    		
			    		if(isset($data->rows) && !empty($data->rows))
		    			{
		    				$total=$data->totals[0]->metricValues[0]->value;
				    		foreach ($data->rows as $key => $value) 
				    		{
			    				
		    					$label[]=ucfirst($data->rows[$key]->dimensionValues[0]->value);
		    					$res_data=($data->rows[$key]->metricValues[0]->value * 100) / $total;
				    			$dataset[]=(int)number_format($res_data,0);
		    				}
		    			}
		    			else
		    			{
		    				$label[]="No data found";
	    					$res_data=100;
			    			$dataset[]=(int)number_format($res_data,0);
		    			}

			    		$arrReturn['labels']   = $label;
			    		$arrReturn['datasets']   = $dataset;
		            	$arrResult['total']      = $sum;

			    	}
			    	elseif($type=='mapcontainer')
			    	{
			    		if(isset($data->rows) && !empty($data->rows))
			    		{

			    			foreach ($data->rows as $key => $value) 
			    			{
			    				$temp=array();

			    				$temp[]=$value->dimensionValues[0]->value;
			    				$temp[]=$value->metricValues[0]->value;
			    				$dataset[]=$temp;
			    			}
			    			$arrReturn['labels']=$label;
			    			$arrReturn['datasets']=$dataset;
			    		}
			    	}
			    	else
			    	{
			    		foreach ($arrParam['arrField'] as $ke => $val) 
			    		{
			    			if(isset($data->rows) && !empty($data->rows))
			    			{
			    				foreach ($data->rows as $key => $value) 
			    				{
				    				if ($data->rows[$key]->dimensionValues[0]->value ==$ke)
				    				{
				    					$res_data=$data->rows[$key]->metricValues[0]->value;
				    					$sum+=$data->rows[$key]->metricValues[0]->value;
				    					if($type=='bounceRateChart')
		        						{
		        							$res_data=$res_data * 100;
		        							$res_data=round($res_data);
		        						}
		        						if($type=='sessionDuration')
		        						{
		        							$res_data=round($res_data);
		        						}
				    					break;
				    				}
				    				else
				    				{
				    
				    					$res_data=0;
				    				}
				    			}
			    			}
			    			else
			    			{
			    				$res_data=0;
			    			}
			    			
			    			$label[]=$val;
			    			$dataset[]=$res_data;
			    		}

			    		$arrReturn['labels']   = array_reverse($label);
			    		$arrReturn['datasets']   = array_reverse($dataset);
		            	$arrResult['total']      = $sum;


			    	}
		    		
		    		$arrResult['is_success'] = 1;
		            $arrResult['data']       = $arrReturn;
		            $arrResult['link']      = $html;
	    		}
	    		else{


	    			if($data->error->code == 401)
	    			{
        				app('App\Http\Controllers\AnalyticsController')->genrate_accesstoken();
        				$this->get_chart_data($request);
	    			}
	    			$arrReturn    = [];
		    		$arrReturn['labels']   = [];
		    		$arrReturn['datasets']   = [];
		    		$arrResult['is_success'] = 0;
		    		$arrResult['message']   = $data->error->message;
		            $arrResult['data']       = $arrReturn;
		            $arrResult['total']      = 0;
		            $arrResult['link']      = '';
	    		}
	    		
    			
    		} catch (Exception $e) {
        		$arrReturn             = [];
	    		$arrReturn['labels']   = [];
	    		$arrReturn['datasets']   = [];
	    		$arrResult['is_success'] = 0;
	    		$arrResult['message']   = $e;
	            $arrResult['data']       = $arrReturn;
	            $arrResult['total']      = 0;
	            $arrResult['link']      = '';
        	}
        }
        else
        {
        	$arrReturn             = [];
    		$arrReturn['labels']   = [];
    		$arrReturn['datasets']   = [];
    		$arrResult['is_success'] = 0;
	    	$arrResult['message']   = __('Site note found');
            $arrResult['data']       = $arrReturn;
            $arrResult['total']      = 0;
            $arrResult['link']      = '';

        }
    	return $arrResult;
	}
	
	public function live_user(Request $request)
	{
		$site=Site::where("id",$request->siteid)->first();
		$arrResult = $this->getLiveUser($site);
	}

	
	public function active_page(Request $request)
	{
		$siteid=$request->get('siteid');
		$site=Site::where("id",$siteid)->first();
        $arrParam= $this->getDurationFromText('month');
        
       	$request_json='{"dimensions":[{"name":"pagePath"}],"metrics":[{"name":"screenPageViews"},{"name":"screenPageViewsPerUser"}],"dateRanges":[{"startDate":"'.$arrParam['StartDate'].'","endDate":"'.$arrParam['EndDate'].'"}],"keepEmptyRows":true}';
        $data=$this->getReport($site, $request_json);
       	$arrData         = [];
       	if(isset($data->rows) && !empty($data->rows))
		{
	        foreach ($data->rows as $key => $value)
	        {
	            $arrData[] = [
	                'PageUrl' => $data->rows[$key]->dimensionValues[0]->value,
	                'screenPageViews' => number_format($data->rows[$key]->metricValues[0]->value),
	                'screenPageViewsPerUser' => number_format($data->rows[$key]->metricValues[1]->value, 2),
	            ];
	        }
	    }

     	$arrResult['is_success'] = 1;
        $arrResult['data']       = $arrData;
        return $arrResult;
	}
}

