<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\StatisticsNotify;
use App\Models\StatisticsPages;
use App\Models\Member;
use App\Models\ProductDesc;
class StatisticsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'statistics:expire';

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
       
        $Statistics=StatisticsPages::with('member')->get();
        $data=[];
        foreach($Statistics as $item){
            $member=Member::where('mem_id',$item->id)->first();
            if($member)
            {
                $maKH=$member->MaKH!=null ? $member->MaKH: "";
                $name=$member->username!=null ? $member->username: "";
                $phone=$member->phone!=null ? $member->phone: "";
            }
            else{
                $maKH="";
                $name="";
                $phone="";
            }
            $nameProduct="";
            
            if(strlen(strstr($item->friendly_url, "/product-detail/")) > 0){
                
                $linkProduct=str_replace('/product-detail/','',$item->friendly_url);
               
                $prodDes=ProductDesc::where('friendly_url',$linkProduct)->first();
                    if($prodDes!=null)
                    {
                        $nameProduct=$prodDes->title;

                    }
                    else{
                        $nameProduct="";
                      
                    }
            }
         
            $data[]=[
                'maKH'=>$maKH,
                'url'=>$item->uri,
                'membername'=>$name,
                'phone'=>$phone,
                'nameProduct'=>$nameProduct,
                'count'=>$item->count,
                'date'=>$item->created_at->format('H:i:s'),
                'module'=>$item->module,
                'action'=>$item->action
            ];
        }
        //thepdt@nguyenkimvn.vn   bao.bui@chinhnhan.vn
        Mail::to('long542.nt@gmail.com')->send(new StatisticsNotify($data));
        // Mail::to('bao.bui@chinhnhan.vn')->send(new StatisticsNotify($data));
        // Mail::to('thepdt@nguyenkimvn.vn')->send(new StatisticsNotify($data));
        $this->info('send email success.');
       
    }
}
