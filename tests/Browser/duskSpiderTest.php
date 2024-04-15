<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\CrawData;
use Facebook\WebDriver\WebDriverBy;

class duskSpiderTest extends DuskTestCase
{
    protected static $domain = 'nguyenkim.com';
    protected static $startUrl = 'https://www.nguyenkim.com/';

    // public function setUp(): void{
    //     parent::setUp();
    //     $this->artisan('migrate:fresh');
    // }
    public function urlSpider()
    {
        $startingLink = CrawData::create([
            'url' => self::$startUrl,
            'isCrawled' => false,
        ]);

        $this->browse(function (Browser $browser) use ($startingLink) {
            $this->getLinks($browser, $startingLink);
        });
       
    }
    
    protected function getLinks(Browser $browser, $currentUrl){
        $this->processCurrentUrl($browser, $currentUrl);
      
        try{
            foreach(CrawData::where('isCrawled', false)->get() as $link) {
                $this->getLinks($browser, $link);
            }
        }catch(Exception $e){

        }
    }
    protected function processCurrentUrl(Browser $browser, $currentUrl){
        if(CrawData::where('url', $currentUrl->url)->first()->isCrawled == true)
            return;
        $browser->visit($currentUrl->url);
        $linkElements = $browser->driver->findElements(WebDriverBy::tagName('a'));
        foreach($linkElements as $element){
            $href = $element->getAttribute('href');
            $href = $this->trimUrl($href);
            if($this->isValidUrl($href)){
                CrawData::create([
                    'url' => $href,
                    'isCrawled' => false,
                ]);
            }
        }
        $currentUrl->isCrawled = true;
        $currentUrl->status  = $this->getHttpStatus($currentUrl->url);
        $currentUrl->name = $browser->driver->getTitle();
        $currentUrl->save();
    }
    protected function isValidUrl($url){
        $parsed_url = parse_url($url);
        if(isset($parsed_url['host'])){
            if(strpos($parsed_url['host'], self::$domain) !== false && !CrawData::where('url', $url)->exists()){
                return true;
            }
        }
        return false;
    }

    protected function trimUrl($url){
        $url = strtok($url, '#');
        $url = rtrim($url,"/");
        return $url;
    }

    protected function getHttpStatus($url){
        $headers = get_headers($url, 1);
        return intval(substr($headers[0], 9, 3));
    }
}
