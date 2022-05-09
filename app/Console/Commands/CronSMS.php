<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CronSMS extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:sms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sms are Sent';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
     $companyDis = \App\CompanyDi::where('is_paid', 0)->get();
     foreach ($companyDis as $companyDi) {
        $date_diff=date_diff($companyDi->due_date ,date('Y-m-d'));
        if($date_diff==7)
        {
            $mobile_number = \App\Dealer::where('id', $companyDi->dealer_id)->first('mobile_number');
            $message = "Your Payment is pending from  " . $companyDi->due_date . " the amount is " . $companyDi->total . "Please  pay  it";
            $response = SmsController::sendSms($mobile_number, $message);
            if ($response['error'] == 0 && !is_null($response['message']) && $response['message']->ErrorMessage == "Done") {
                $sms_report = \App\DealerSmsReport::where('retailer_id', $companyDi->retailer->id)->first();
                if (is_null($sms_report)) {
                    $sms_report = new \App\RetailerSmsReport();
                    $sms_report->company_id =$companyDi->product_company_id;
                    $sms_report->company_name = getModelById('Company',$companyDi->product_company_id)->name;
                    $sms_report->dealer_id = $companyDi->dealer_id;
                    $sms_report->dealer_id = getModelById('Dealer',$companyDi->dealer_id)->name;
                    $sms_report->message = $message;
                    $sms_report->mobile_number = $mobile_number;
                    $sms_report->save();
                }

            }
        } else {
            $response['flag']=false;
            $response['message']='Something Went Wrong';
        }
    }
}
}
