<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use App\Models\Product;
use Illuminate\Console\Command;
use Symfony\Component\DomCrawler\Crawler;

class CrawlDataCommand extends Command
{
    protected $signature = 'crawl:data';

    protected $description = 'Crawl data from thegioididong.com/laptop';

    public function handle()
    {
        $url = 'https://www.thegioididong.com/laptop-msi';

        $client = new Client();
        $response = $client->get($url);

        $html = $response->getBody()->getContents();

        $crawler = new Crawler($html);

        // Use the crawler to extract the desired data from the HTML
        $crawler->filter('ul.listproduct li.item')->each(function (Crawler $node) {
            $name = $node->filter('h3')->text();
            $price = $node->filter('.price ')->text();
            $price = preg_replace('/\D/', '', $price);

                $product = new Product();
                $product->name = $name;
                $product->price = $price;
                $product->save();
        });

        $this->info('Data crawled successfully.');
    }
} 
// php artisan crawl:data
// namespace App\Console\Commands;

// use Illuminate\Console\Command;
// use Facebook\WebDriver\Remote\DesiredCapabilities;
// use Facebook\WebDriver\Remote\RemoteWebDriver;
// use Facebook\WebDriver\WebDriverBy;

// class CrawlDataCommand extends Command
// {
//     protected $signature = 'crawl:data';

//     protected $description = 'Crawl data from the website using Selenium';

//     public function handle()
//     {
//         // Khởi tạo WebDriver với trình duyệt và URL mục tiêu
//         $host = 'http://localhost:9515'; // Địa chỉ của WebDriver server (ví dụ: ChromeDriver)
//         $capabilities = DesiredCapabilities::chrome();
//         $driver = RemoteWebDriver::create($host, $capabilities);

//         // Điều hướng đến URL mục tiêu
//         $url = 'https://cellphones.com.vn/laptop.html'; // URL của trang web cần crawl
//         $driver->get($url);

//         // Trích xuất dữ liệu
//         $data = $driver->findElements(WebDriverBy::cssSelector('.product-info-container .product-info'));

//         foreach ($data as $item) {
//             $name = $item->findElement(WebDriverBy::cssSelector('h3'))->getText();
//             $price = $item->findElement(WebDriverBy::cssSelector('.product__price--show '))->getText();

//             // Xử lý và lưu dữ liệu theo nhu cầu của bạn
//             // Ví dụ: Lưu dữ liệu vào cơ sở dữ liệu bằng Eloquent Model.
//             $price = preg_replace('/\D/', '', $price);

//                 $product = new Product();
//                 $product->name = $name;
//                 $product->price = $price;
//                 $product->save();
//         }

//         // Đóng WebDriver sau khi hoàn thành
//         $driver->quit();

//         $this->info('Data crawled successfully.');
//     }
// }