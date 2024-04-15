<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\CrawData;
use Illuminate\Console\Command;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Firefox\FirefoxOptions;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;

class CrawlDataSeleniumCommand extends Command
{
    protected $signature = 'crawl:website';

    protected $description = 'Crawl data from the website using Selenium';

    // public function handle()
    // {
    //     $host = 'http://localhost:9515'; 
    //     $chromeOptions = new ChromeOptions();
    //     $capabilities = DesiredCapabilities::chrome();
    //     $capabilities->setCapability(ChromeOptions::CAPABILITY, $chromeOptions->toArray());
        
    //     $driver = RemoteWebDriver::create($host, $capabilities);
    //     $url = 'https://www.nguyenkim.com/laptop-may-tinh-xach-tay/'; 
    //     $driver->get($url);

    //     $data = $driver->findElements(WebDriverBy::cssSelector('.owl-stage-outer '));
    //     foreach ($data as $item) {
    //         $name = $item->findElement(WebDriverBy::cssSelector('.product-title"'))->getText();
    //         $price = $item->findElement(WebDriverBy::cssSelector('.product__price--show'))->getText();
    //         $price = preg_replace('/\D/', '', $price);
    //         $product = new CrawData();
    //         $product->name = $name;
    //         $product->price = $price;
    //         $product->save();
    //     }
    //     $driver->quit();
    //     $this->info('Data crawled successfully.');
    // }
}
