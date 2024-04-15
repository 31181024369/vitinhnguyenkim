<?php

namespace App\Observers;

use App\Models\Log;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log as LogManager;

class ProductObserver
{
    /**
     * Handle the Coupon "created" event.
     *
     * @param  \App\Models\Product  $coupon
     * @return void
     */
    public function created(Product $product)
    {
        LogManager::info('Product '.$product->id.'  has been save.');
        $saveActionProduct = new Log();
        $saveActionProduct -> user_id = Auth::guard('admin')->user()->name;
        $saveActionProduct -> product_id = $product->id;
        $saveActionProduct -> description = 'Product '.$product->id.'  has been save.'.
        $product->price ?? ''.'.' . $product->price_old ?? ''. $product->status ?? ''.
        $product->picture ?? ''.$product->tecnology.Auth::guard('admin')->user()->name;
        $saveActionProduct -> action = 'Create product new';
        $saveActionProduct -> save();
    }

    /**
     * Handle the Coupon "updated" event.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return void
     */
    public function updated(Product $product)
    {
        LogManager::info('product '.$product->id.'  has been updated.');
        $saveActionProduct = new Log();
        $saveActionProduct -> user_id = $product->IDAdmin;
        $saveActionProduct -> product_id = $product->id;
        $saveActionProduct -> description = 'product '.$product->id.'  has been updated.'.
            $product->price ?? ''.'.' . $product->price_old ?? ''. $product->status ?? ''.$product->picture ?? '' ;
        $saveActionProduct -> action = $product->id.' updated product by '.Auth::guard('admin')->user()->name;
        $saveActionProduct -> save();
    }

    /**
     * Handle the Coupon "deleted" event.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return void
     */
    public function deleted(Product $product)
    {
        LogManager::info('product '.$product->id.'  has been deleted.');
        $saveActionProduct = new Log();
        $saveActionProduct -> user_id = $product->IDAdmin;
        $saveActionProduct -> product_id = $product->id;
        $saveActionProduct -> description = 'product '.$product->id.'  has been deleted.';
        $saveActionProduct -> action = $product->id.' deleted product by '.Auth::guard('admin')->user()->name;
        $saveActionProduct -> save();
    }

    /**
     * Handle the Coupon "restored" event.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return void
     */
    public function restored(Coupon $coupon)
    {
        //
    }

    /**
     * Handle the Coupon "force deleted" event.
     *
     * @param  \App\Models\Coupon  $coupon
     * @return void
     */
    public function forceDeleted(Coupon $coupon)
    {
        //
    }
}
