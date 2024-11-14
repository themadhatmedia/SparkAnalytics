<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Alert;
use App\Models\AlertHistory;
use App\Models\Site;
use App\Models\Reports;
use App\Models\Utility;
use Session;
use Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AlertController extends Controller
{
     public function index()
    {

    	$user=Auth::user();
    	if(\Auth::user()->user_type=="company")
        {
            $site=Site::where('created_by',$user->id)->get();
    		$data=Alert::where('created_by',$user->id)->get();
          
    	}
    	else
    	{
            $site=Site::where('created_by',$user->created_by)->get();
    		$data=Alert::where('created_by',$user->created_by)->get();
    	}
    	$metric_option = $this->metric_option();
        return view('alert.default')->with('site',$site)->with('data',$data)->with('metric_option',$metric_option);
      
    }
    public function create(Request $request)
    {
    	$validation         = [];
        $validation['title']  = 'required';
        $validation['site_id'] = 'required';
        $validation['metric'] = 'required';
        $validation['duration']  = 'required';
        $validation['description']  = 'required';
      


        $validator = \Validator::make(
            $request->all(), $validation
        );

        if($validator->fails())
        {
            return redirect()->back()->with('error', $validator->errors()->first());
        }
        else
        {
            $user= Auth::user();

            $store=new Alert();
            $store->title=$request->get('title');
            $store->site_id=$request->get('site_id');
            $store->metric=$request->get('metric');
            $store->duration=$request->get('duration');
            $store->description=$request->get('description');
            $store->email_notification=$request->has('email_notification') ? 1 : 0;
            $store->slack_notification=$request->has('slack_notification') ? 1 : 0;
            if(\Auth::user()->user_type == 'company')
            {

                $store->created_by=$user->id;
            }
            else
            {
                $store->created_by=$user->created_by;
            }

            $store->save();
            if($store)
            {
               
                return redirect()->route('aletr')->with('success', __('Alert Added Successfully.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Something is wrong.'));
            }
        }
    }

    public function history()
    {
        $user=Auth::user();
        if(\Auth::user()->user_type=="company")
        {
            
            $data=AlertHistory::where('created_by',$user->id)->get();
          
        }
        else
        {
            
            $data=AlertHistory::where('created_by',$user->created_by)->get();
        }
        
        return view('alert.history')->with('data',$data);
    }

    public function delete_history($id)
    {
        
        $data=AlertHistory::where('id',$id)->first();
        if($data)
        {
            
            $data->delete();
            return redirect()->route('aletr-history')->with('success', __('Alert history successfully deleted .'));
        } else {
            return redirect()->back()->with('error', __('Something is wrong.'));
        }
      
    }
    

    


     
}
