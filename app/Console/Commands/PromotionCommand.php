<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Promotion;
use App\Models\PromotionDesc;

class PromotionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'promotion:expire';

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
        $nowFormatted =$now->format('Y-m-d\TH:i:s.u\Z');
        $promotion=Promotion::with('promotionDesc')->get();
        foreach ( $promotion as $value) {  //
            $timeStart= Carbon::createFromFormat("d/m/Y", $value->date_start_promotion);
            $timeEnd=Carbon::createFromFormat("d/m/Y",$value->date_end_promotion);
            $timeStartFormatted = $timeStart->format('Y-m-d\TH:i:s.u\Z');
            $timeEndFormatted = $timeEnd->format('Y-m-d\TH:i:s.u\Z');
            if ($timeStartFormatted > $nowFormatted ||  $timeEndFormatted < $nowFormatted) {
                $value->status=0;
                $value->save();  
            }
        }
        $this->info('promostion success.');
    
    }
}
