<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\StatisticsPages;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
class delStatisticsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delStatistics:expire';

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
        // $StatisticsPages=StatisticsPages::where('id_static_page',1158)->delete();
        // StatisticsPages::all()->delete();
        $currentDateTime = Carbon::now();
        $newDateTime = Carbon::now()->subMonth();
        DB::table('statistics_pages')->whereMonth('created_at',$newDateTime )->delete();
        $this->info('delete statistics success.');
    }
}
