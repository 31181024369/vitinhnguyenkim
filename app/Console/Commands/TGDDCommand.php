<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use App\Models\CrawData;
use Illuminate\Console\Command;
use Symfony\Component\DomCrawler\Crawler;

class TGDDCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'crawl:data {path? : The path to scrape data from}';
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
        $baseUrl = 'https://www.thegioididong.com/tim-kiem?key=';
        $path = $this->argument('path') ?? $this->path ?? '/laptop-msi';
        $url = $baseUrl . $path;

        $client = new Client();
        $response = $client->get($url);

        $html = $response->getBody()->getContents();

        $crawler = new Crawler($html);

        // Use the crawler to extract the desired data from the HTML
        $crawler->filter('ul.listproduct li.item')->each(function (Crawler $node) {
            $name = $node->filter('h3')->text();
            $price = $node->filter('.price ')->text();
            $url = $node->filter('a')->attr('href');
            $price = preg_replace('/\D/', '', $price);
                $product = new CrawData();
                $product->name = $name;
                $product->price = $price;
                $product->url = 'https://www.thegioididong.com/'.$url;
                $product->save();
        });
        
        $this->info('Data crawled successfully.');
        $this->path = $path;
    }
}
