<?php
namespace App\Http\Controllers\API\Admin;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Facebook\WebDriver\WebDriverBy;
use App\Http\Controllers\Controller;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Symfony\Component\DomCrawler\Crawler;

class CrawController extends Controller
{
    // public function craw(Request $request)
    // {
    //     $driver = RemoteWebDriver::create('http://localhost:9515', \Facebook\WebDriver\Remote\DesiredCapabilities::chrome());
    //     $key = 'X1402ZA-EB100W';
    
    //     $driver->get('https://www.nguyenkim.com/tim-kiem.html?tu-khoa='.$key.'');
        
    //     $productNameElements = $driver->findElements(WebDriverBy::xpath('//div[@class="product-title"]'));
    //     $productPriceElements = $driver->findElements(WebDriverBy::xpath('//div[@class="product-price"]'));
    //     for($i = 0;$i < count($productNameElements);$i++) {
    //         $data[] = [
    //             'name' => $productNameElements[$i]->getText(),
    //             'price' => $productPriceElements[$i]->getText()
    //         ];
    //     }
    //     $driver->quit();

    //     return response()->json($data ?? 'Khoong');
    // }
    
    public function craw(Request $request,$key)
    {
        $key =$request->key;
        $urlWebsites = [
            ['https://fptshop.com.vn/tim-kiem/'.$key.'' => [
                'name' => '//div[@class="cdt-product__info"]',
                'price' => '//div[@class="cdt-product__show-promo"]',
                'webName' => 'fptshop',
                'homePage' => 'https://fptshop.com.vn',
                // 'linkDetail' => '//div[@class="cdt-product__info"]'
            ]],
            ['https://cellphones.com.vn/catalogsearch/result?q='.$key.'' => [
                'name' => '//div[@class="product__name"]',
                'price' => '//div[@class="box-info__box-price"]',
                'webName' => 'Cellphones',
                'homePage' => 'https://cellphones.com.vn',
                // 'linkDetail' => '//div[@class="product-info"]'
            ]],
            ['https://www.nguyenkim.com/tim-kiem.html?tu-khoa='.$key.'' => [
                'name' => '//div[@class="product-title"]',
                'price' => '//div[@class="product-price"]',
                'webName' => 'NguyenKim',
                'homePage' => 'https://www.nguyenkim.com',
                // 'linkDetail' => '//div[@class="product-title"]'
            ]],
            ['https://phongvu.vn/search?router=productListing&query='.$key.'' => [
                'name' => '//div[@class="css-1ybkowq"]',
                'price' => '//div[@class="css-kgkvir"]',
                'webName' => 'PhongVu',
                'homePage' => 'https://phongvu.vn',
                // 'linkDetail' => '//div[@class="product-card"]'
            ]],
            
        ];
        
        $data = [];
        
        foreach ($urlWebsites as $website) {
            $url = array_keys($website)[0];
            $className = $website[$url]['name'];
            $classPrice = $website[$url]['price'];
            $classLink = $website[$url]['homePage'];
            $webName = $website[$url]['webName'];
            // $classLinkDetail = $website[$url]['linkDetail']; 
            $driver = RemoteWebDriver::create('http://localhost:9515', \Facebook\WebDriver\Remote\DesiredCapabilities::chrome());
            $driver->get($url);
            $productNameElements = $driver->findElements(WebDriverBy::xpath($className));
            $productPriceElements = $driver->findElements(WebDriverBy::xpath($classPrice));
            // $linkDetailElements = $driver->findElements(WebDriverBy::xpath($classLinkDetail));
            for ($i = 0; $i < count($productNameElements); $i++) {
                $name = $productNameElements[$i] ?? null; 
                $price = $productPriceElements[$i] ?? null;
                if ($name && $price ) {
                    // $linkDetail = $linkDetailElements[$i]->findElement(WebDriverBy::tagName('a'))->getAttribute('href'); 
                    $data[] = [
                        'Congcheck' => $classLink,
                        'ProductId' => null,
                        'ProductName' => $name->getText(),
                        'Price' => $price->getText(),
                        'Thumbnail' => null,
                        'Link' =>  null,
                    ];
                }
            }
            $driver->quit();
        }
        $baseUrl = 'https://www.thegioididong.com/tim-kiem?key='.$key;
        $client = new Client();
        $response = $client->get($baseUrl);

        $html = $response->getBody()->getContents();

        $crawler = new Crawler($html);
        $dataTGDD = [];
        $productNodes = $crawler->filter('ul.listproduct li.item');
        if ($productNodes->count() > 0) {
            $productNodes->each(function (Crawler $node) use (&$dataTGDD) {
                $name = $node->filter('h3')->text() ??null;
                $checkClassPrice = $node->filter('.price')->count();
                $url = $node->filter('a')->attr('href')??null;
                // $price = preg_replace('/\D/', '', $price);
                if ($checkClassPrice > 0) {
                    $product = [
                        'Congcheck' => 'https://www.thegioididong.com',
                        'productId' => null,
                        'ProductName' => $name,
                        'Price' => $node->filter('.price')->text(),
                        'Thumbnail' => null,
                        'Link' => 'https://www.thegioididong.com' . $url,
                    ];
            
                    $dataTGDD[] = $product;
                }
            });

        $mergedData = array_merge($data, $dataTGDD);
        if (count($mergedData) > 0) {
            return response()->json([
                'data' => $mergedData,
            ]);
        } else {
            return response()->json('No results found');
        }
    
    }
}
}