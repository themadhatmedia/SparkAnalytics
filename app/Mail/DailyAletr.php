<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;
use App\Models\AlertHistory;
use App\Models\Utility;
class DailyAletr extends Mailable
{
    use Queueable, SerializesModels;
    public $aletr;
    public $user;
    public $data;
    public $t_date;
    public $y_date;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($aletr,$user)
    {
        //
         $this->aletr = $aletr;
         $this->user = $user;
         $this->data=app('App\Http\Controllers\Controller')->daily_alter_data($this->aletr->site_id,$this->aletr->metric);
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
        $title='Average '. $this->aletr->title.' '.$this->data;
        $msg='On '.$t_date.' '. $this->aletr->metric.' '. $this->data.' compared to '.$y_date.'';
        $store=new AlertHistory();
        $store->title=$title;
        $store->aletr_id=$this->aletr->id;
        $store->description=$msg;
        $store->created_by=$this->user->id;
        $store->site_id=$this->aletr->site_id;
        $store->save();

        return $this->subject($title)
                    ->view('alert.email')->with('msg',$msg);
    }
}
