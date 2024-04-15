<?php

namespace App\Http\Controllers;

use App\Models\CrawData;
use Illuminate\Http\Request;
use Facebook\WebDriver\WebDriverBy;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\DomCrawler\Crawler;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverExpectedCondition;
use GuzzleHttp\Client;



class CrawController extends Controller
{
    
    public function getPathForm(Request $request)
    {
        $key = $request->key;
        $baseUrl = 'https://www.thegioididong.com/tim-kiem?key='.$key;
        $client = new Client();
        $response = $client->get($baseUrl);

        $html = $response->getBody()->getContents();

        $crawler = new Crawler($html);
        $data = [];
        $productNodes = $crawler->filter('ul.listproduct li.item');
        if ($productNodes->count() > 0) {
            $productNodes->each(function (Crawler $node) use (&$data) {
                $name = $node->filter('h3')->text() ??null;
                $checkClassPrice = $node->filter('.price')->count();
                $url = $node->filter('a')->attr('href')??null;
                // $price = preg_replace('/\D/', '', $price);
                if ($checkClassPrice > 0) {
                    $product = [
                        'name' => $name,
                        'price' => $node->filter('.price')->text(),
                        'url' => 'https://www.thegioididong.com' . $url,
                        'website' => 'https://www.thegioididong.com',
                    ];
            
                    $data[] = $product;
                }
            });

            return response()->json([
                'data' => $data,
            ]);
        } else {
            return response()->json('No results found');
        }
    }
    public function getCrawlData(Request $request)
    {
        $path = $request->input('path');
        Artisan::call('crawl:data', ['path' => $path]);
        return  redirect('/crawl-data')->with('success', 'Data crawled successfully.');
    }
}
