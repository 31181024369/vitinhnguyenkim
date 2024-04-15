<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ProductFlashSale;
use Carbon\Carbon;

class FlashSalseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flashsale:expire';

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
        //return 0;
        // $now = Carbon::now();
        // $nowFormatted = $now->format('Y-m-d\TH:i:s.u\Z');
        
        // $listFlashSaleProduct = ProductFlashSale::where('status', 1)->get();
        // foreach ($listFlashSaleProduct as $value) {
        //     $timeStart = Carbon::createFromFormat("d/m/Y", $value->start_time);
        //     $timeEnd = Carbon::createFromFormat("d/m/Y", $value->end_time);
        //     $timeStartFormatted = $timeStart->format('Y-m-d\TH:i:s.u\Z');
        //     $timeEndFormatted = $timeEnd->format('Y-m-d\TH:i:s.u\Z');
        //     if ($timeStartFormatted < $nowFormatted &&  $timeEndFormatted < $nowFormatted) {
        //         $value->status=0;
        //         $value->save();
        //     }
        // }
        $now = Carbon::now();
            $nowFormatted = $now->format('m/d/Y');
            $listFlashSaleProduct = ProductFlashSale::where('status', 1)->get();
            foreach ($listFlashSaleProduct as $value) {
                $timeStart=Carbon::parse($value->start_time)->format('m/d/Y');
                $timeEnd=Carbon::parse($value->end_time)->format('m/d/Y');
                if (  strtotime($timeStart)> strtotime($nowFormatted) || strtotime($timeEnd) < strtotime($nowFormatted)) {
                    $value->status=0;
                    $value->save();
                    
                }
            }
        $this->info('flash sale success.');

    }
}
