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

class ReportController extends Controller
{
    public function report_history()
    {
        $user=Auth::user();
        if(\Auth::user()->user_type=="company")
        {
            
            $data=Reports::where('created_by',$user->id)->get();
          
        }
        else
        {
            
            $data=Reports::where('created_by',$user->created_by)->get();
        }
        
        return view('report.history')->with('data',$data);
    }
    public function delete_history($id)
    {
        
        $data=Reports::where('id',$id)->first();
        if($data)
        {
            
            $data->delete();
            return redirect()->route('report-history')->with('success', __('Report history successfully deleted .'));
        } else {
            return redirect()->back()->with('error', __('Something is wrong.'));
        }
      
    }
    public function show_history($id)
    {
        
        $data=Reports::where('id',$id)->first();
        if($data)
        {
            
          return $data;
        }
      
    }
}
