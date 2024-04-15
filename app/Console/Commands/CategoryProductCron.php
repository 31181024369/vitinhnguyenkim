<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redis;

class CategoryProductCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:cron';

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
        // Redis::set('a','1');
        try {
            info('This running command every minutes');
            $categories = Category::with('categoryDesc', 'products', 'products.productDesc', 'subCategories')
                ->orderBy('cat_id', 'ASC')->where('display', 1)->get();
        
            $client = new Client();
            $response = $client->get("http://mediank.ketnoi365.com/api/product-avatar");
            $listItemImg = json_decode($response->getBody(), true);
        
            $finalData = [];
            foreach ($categories as $category) {
                $categoryData = [
                    'category_id' => $category->cat_id,
                    'category_name' => $category->categoryDesc->cat_name ?? null,
                    'parentid' => $category->parentid,
                    'friendCategory' => $category->categoryDesc->friendly_url ?? null,
                    'friendTitleCategory' => $category->categoryDesc->friendly_title ?? null,
                    'products' => [],
                ];
                foreach ($category->products->take(12) as $product) {
                    if (isset($listItemImg[$product->product_id])) {
                        $product->picture = $listItemImg[$product->product_id];
                    }
                    $productData = [
                        'productId' => substr(Crypt::encryptString($product->product_id), 2),
                        'productTitle' => $product->productDesc->title ?? null,
                        'picture' => $product->picture ?? null,
                        'price' => $product->price ?? null,
                        'priceOld' => $product->price_old ?? null,
                        'friendUrl' => $product->productDesc->friendly_url ?? null,
                        'friendTitle' => $product->productDesc->friendly_title ?? null,
                        'technology' => $dataValue ?? null,
                    ];
                    $categoryData['products'][] = $productData;
                }
                if ($categoryData['parentid'] === 0) {
                    $finalData[] = $categoryData;
                } else {
                    $found = false;
                    foreach ($finalData as &$finalItem) {
                        if ($finalItem['category_id'] === $categoryData['parentid']) {
                            $finalItem['subcategories'][] = $categoryData;
                            $found = true;
                            break;
                        }
                    }
                    if (!$found) {
                        $finalData[] = $categoryData;
                    }
                }
            }
            Redis::set('list_', json_encode($finalData));
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ]);
        }
    }
}
