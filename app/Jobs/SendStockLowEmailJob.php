<?php

namespace App\Jobs;

use App\Mail\StockLowMail;
use App\Models\setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class SendStockLowEmailJob  implements ShouldQueue 
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $prod_name;

    public function __construct($prod_name)
    {
        $this->prod_name= $prod_name;
        
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = Setting::where('key','notifyemail')->get()->toArray();
        $email = new StockLowMail($this->prod_name);
        Mail::to($data[0]['value'])->send($email);
    }
}
