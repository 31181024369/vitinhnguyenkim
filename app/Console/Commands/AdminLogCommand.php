<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminLogEmail;


class AdminLogCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adminlog:expire';

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
        $adminlogs=DB::table('adminlogs')
        ->join('admin', 'admin.adminid', '=', 'adminlogs.adminid')
        ->select('adminlogs.*', 'admin.username','admin.display_name')->
        orderBy('time', 'desc')->take(100)->get();
        $data=json_decode($adminlogs,true);
        Mail::to('bao.bui@chinhnhan.vn')->send(new AdminLogEmail($data));
        $this->info('send email adminlog success.');

    }
}
