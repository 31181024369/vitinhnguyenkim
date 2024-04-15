<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Coupon;
use Illuminate\Console\Command;

class ExpireCoupons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coupons:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
        $now = Carbon::now();
        $carbon = strtotime($now);
        $datetime = date('Y-m-d H:i:s', $carbon);
        $expiredCoupons = Coupon::where('EndCouponDate', '<=', $datetime)
                                 ->where('status_id','=','1')->get();
        foreach ($expiredCoupons as $coupon) {
            $coupon->status_id = 0;
            $coupon->save();
        }
        info('Expired coupons have been updated.');
    }
}
