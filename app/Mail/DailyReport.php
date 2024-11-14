<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;
use App\Models\Reports;
class DailyReport extends Mailable
{
    use Queueable, SerializesModels;
    public $site;
    public $user;
    public $data;
    public $t_date;
    public $y_date;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($site,$user)
    {
        //
         $this->site = $site;
         $this->user = $user;
         $this->data=app('App\Http\Controllers\Controller')->daily_report_data($this->site->id);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $t_date=Carbon::now()->format('F d,Y');
        $y_date=Carbon::now()->subDay()->format('F d,Y');
        $title="Here`s how your business performed during the day of ".$t_date." compared to ".$y_date.". Keep up the great work!";
        
        $store=new Reports();
        $store->title=$title;
        $store->data=$this->data;
        $store->report_type="Daily report";
        $store->created_by=$this->user->id;
        $store->site_id=$this->site->id;
        $store->save();
      
        return $this->view('report.email')->with('report',$store);
    }
}
