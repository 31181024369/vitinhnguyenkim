<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\API\Admin\AdposController;
use App\Http\Controllers\Test\TestController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\Admin\FaqsController;
use App\Http\Controllers\API\Admin\ChartController;
use App\Http\Controllers\API\Admin\NewsController;
use App\Http\Controllers\API\Admin\RoleController;
use App\Http\Controllers\API\Admin\AdminController;
use App\Http\Controllers\API\Admin\BrandController;
use App\Http\Controllers\API\Admin\PropertiesController;
use App\Http\Controllers\API\Admin\AdminMenuController;
use App\Http\Controllers\API\Member\OrderController;
use App\Http\Controllers\API\Admin\AdvertiseController;
use App\Http\Controllers\API\Admin\GuideController;
use App\Http\Controllers\API\Admin\CouponController;
use App\Http\Controllers\API\Admin\OptionController;
use App\Http\Controllers\API\Chat\MessageController;
use App\Http\Controllers\API\ProductPriceController;
//use App\Http\Controllers\API\Admin\CommentController;
use App\Http\Controllers\API\Admin\ContactController;
use App\Http\Controllers\API\Admin\ServiceController;
use App\Http\Controllers\API\Admin\SettingController;
use App\Http\Controllers\API\Admin\SupportController;
use App\Http\Controllers\API\Chat\ChatUserController;
use App\Http\Controllers\API\Admin\CategoryController;
use App\Http\Controllers\API\Admin\CategorySController;
use App\Http\Controllers\API\Admin\ListCartController;
use App\Http\Controllers\API\Admin\MailTempController;
use App\Http\Controllers\API\Chat\ChatAdminController;
use App\Http\Controllers\API\Admin\PromotionController;
use App\Http\Controllers\API\Member\CheckoutController;
use App\Http\Controllers\API\Admin\DepartmentController;
use App\Http\Controllers\API\Admin\LoginAdminController;
//use App\Http\Controllers\API\Admin\ProductFlashSaleController;
use App\Http\Controllers\API\Admin\MemberGroupController;
use App\Http\Controllers\API\Admin\OrderStatusController;
use App\Http\Controllers\API\Member\RepurchaseController;
use App\Http\Controllers\API\Admin\ComboProductController;
use App\Http\Controllers\API\Admin\ContactQouteController;
use App\Http\Controllers\API\Admin\ContactStaffController;
use App\Http\Controllers\API\Admin\CouponStatusController;
use App\Http\Controllers\API\Admin\FaqsCategoryController;
use App\Http\Controllers\API\Admin\ImportExportController;
use App\Http\Controllers\API\Admin\IntroductionController;
use App\Http\Controllers\API\Admin\NewsCategoryController;
use App\Http\Controllers\API\Admin\SupportGroupController;
use App\Http\Controllers\API\Member\SaleSupportController;
use App\Http\Controllers\API\Member\ProductAdvertiseController;
use App\Http\Controllers\API\Admin\ContactConfigController;
// use App\Http\Controllers\API\Admin\CompareProductController;
use App\Http\Controllers\API\Admin\RelatedProductController;
use App\Http\Controllers\API\Admin\ShippingMethodController;
// use App\Http\Controllers\API\Admin\FeaturedProductController;
use App\Http\Controllers\API\Admin\OrderManagementController;
// use App\Http\Controllers\API\Admin\ConfirmationMailController;
use App\Http\Controllers\API\Admin\IntroducePartnerController;
use App\Http\Controllers\API\Admin\StatisticsController;

//member
use App\Http\Controllers\API\Admin\MemberManagementController;
use App\Http\Controllers\API\Admin\AdministrationMemberController;
use App\Http\Controllers\API\Admin\CouponDescriptionUsingController;
use App\Http\Controllers\API\Admin\DatabaseBackup\DatabaseBackupController;
use App\Http\Controllers\API\Admin\AdminMannagementController;
use App\Http\Controllers\API\Member\StarController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::post('/register', [App\Http\Controllers\API\Member\AuthController::class, 'register']);
Route::any('/forget-password', [App\Http\Controllers\API\Member\AuthController::class,'forgetPassword'])->name('forget-password');
Route::any('/forget-password-change', [App\Http\Controllers\API\Member\AuthController::class,'forgetPasswordChange'])->name('forget-password-change');
Route::match(['get','post'],'/login', [App\Http\Controllers\API\Member\AuthController::class, 'login'])->name('login');
Route::post('/logout', [App\Http\Controllers\API\Member\AuthController::class, 'logout'])->name('logout');


Route::post('/fill-form', [App\Http\Controllers\API\Member\AuthController::class, 'form']);

Route::match(['get','post'],'/login-admin', [LoginAdminController::class, 'login'])->name('login-admin');
Route::match(['get','post'],'/register-admin', [LoginAdminController::class,'register'])->name('register-admin');


Route::get('export/brand',[ImportExportController::class,'exportBrand']);

// Route::get('products/export/technology',[ImportExportController::class,'exportTechnologyExcel']);
Route::group(['middleware' => 'admin', 'prefix' => 'admin'], function () { 
    //**Department CRUD && Search */
    Route::resource('HireCategory',App\Http\Controllers\API\Admin\HireCategoryController::class);
    Route::resource('HirePost',App\Http\Controllers\API\Admin\HirePostController::class);
   
    
    Route::get('/show-hire-post/{name}/{hire_cate_id}',[App\Http\Controllers\API\Admin\HirePostController::class,'showHirePost']);

    Route::get('/get-member-sap',[OrderManagementController::class,'getMemberSAP']);
    Route::get('/create-member-sap',[OrderManagementController::class,'createMemberSAP']);
    Route::get('/get-product-sap',[OrderManagementController::class,'getProductSAP']);
   
    Route::get('/leader-department/{id}',[AdminController::class,'leaderDepartment']);

    Route::post('/login-voucher',[App\Http\Controllers\API\Admin\CodeVoucherController::class,'loginVoucher']);
    Route::resource('CodeVoucher',App\Http\Controllers\API\Admin\CodeVoucherController::class);

    Route::resource('partner',App\Http\Controllers\API\Admin\PartnerController::class);
    Route::resource('partnerNews',App\Http\Controllers\API\Admin\PartnerNewsController::class);
    Route::resource('partnerIntroduce',App\Http\Controllers\API\Admin\PartnerIntroduceController::class);
    Route::get('/get-partner',[App\Http\Controllers\API\Admin\PartnerNewsController::class,'getPartner']);
   

    Route::resource('admin-menu',AdminMenuController::class);
    
    Route::resource('/permission',App\Http\Controllers\API\Admin\PermissionController::class);
    Route::resource('department',App\Http\Controllers\API\Admin\DepartmentController::class);
    Route::get('/search-department',[DepartmentController::class,'search']);
    Route::get('/search-product-desc',[App\Http\Controllers\API\Admin\ProductController::class,'searchProductDesc']);

     Route::resource('mannage',AdminMannagementController::class);
     //*Roles CRUD* */
     Route::resource('roles',RoleController::class);
     Route::get('search-role',[RoleController::class,'searchRole'])->name('searchRole');
     //** Information Account Amdin */
     Route::resource('/information',AdminController::class);
     Route::get('/admin-log',[AdminController::class,'log']);
     Route::get('search-account',[AdminController::class,'searchAccount'])->name('searchAccount');
     Route::get('search-account-filter',[AdminController::class,'searchAccountFilter'])->name('searchAccountFilter');
     Route::get('change-password/{user}',[AdminController::class,'changePassword']);
     Route::post('change-password/{user}',[AdminController::class,'password']);
     //**List member,CRUD */
     Route::resource('member',App\Http\Controllers\API\Admin\MemberController::class);
     Route::get('/export-member',[App\Http\Controllers\API\Admin\MemberController::class,'export']);
     Route::get('/list-members', [AdministrationMemberController::class,'index'])->name('list_members');
     Route::get('/update-members', [AdministrationMemberController::class,'edit'])->name('update_members');
     Route::post('/update-members/{id}', [AdministrationMemberController::class,'update']);
     Route::resource('support-member',App\Http\Controllers\API\Admin\MemberSupportController::class); 
     //*Coupon*/
     Route::resource('coupon',CouponController::class);
     Route::get('/search-coupon',[CouponController::class,'searchCoupon']); 
     Route::get('/export-coupon',[CouponController::class,'export']);
    
     //**Coupon status && search */
     Route::resource('coupon-status',CouponStatusController::class);
     Route::get('/search-coupon-status',[DepartmentController::class,'search']);
     //**Coupon status */
     Route::resource('coupon-description',CouponDescriptionUsingController::class);
     //**Export, Import Excel */

     Route::post('import/technology',[ImportExportController::class,'importTechnologyExcel']);
     Route::post('products/import/',[ImportExportController::class,'import'])->name('products-import');
     Route::get('products/export/',[ImportExportController::class,'exportExcel'])->name('products-export');
     Route::get('products/export/technology',[ImportExportController::class,'exportTechnologyExcel']);
     Route::get('orders/export/',[OrderManagementController::class,'export']);
    //  Route::get('orderSum/export/',[OrderManagementController::class,'exportOrderSum']);
     Route::get('/search-members-order',[OrderManagementController::class,'searchMembersOrder'])->name('search-members-order');
     //*Order CU && Filter*/

    Route::resource('order-management',OrderManagementController::class);
    Route::get('order-management-filter',[OrderManagementController::class,'filter']);
    Route::get('report-check-member',[OrderManagementController::class,'reportCheckMember']);
    Route::get('report-order',[OrderManagementController::class,'reportOrderStatiscs']);
    Route::get('report-statiscs',[OrderManagementController::class,'reportStatiscsPage']);
    Route::get('report-category',[OrderManagementController::class,'reportCategory']);
    Route::get('report-best-product',[OrderManagementController::class,'reportBestProduct']);
    Route::get('report-best-sale',[OrderManagementController::class,'reportBestSale']);
    Route::get('order-management-filter-product',[OrderManagementController::class,'filterProduct']);
    Route::get('total-order-month',[OrderManagementController::class,'totalOrderMonth']);
    Route::get('order-management-coupon',[OrderManagementController::class,'getCoupon']);
     
     //**Oder Status CRUD */
     Route::resource('order-status',OrderStatusController::class);
     Route::get('order-status-search',[OrderStatusController::class,'search'])->name('order-status-search');
     //**SHIPPING method CRUD */
     Route::resource('shipping-method',ShippingMethodController::class);
     Route::get('shipping-method-search',[ShippingMethodController::class,'search'])->name('shipping-method-search');
     Route::resource('introduce-partners',IntroducePartnerController::class);
     Route::get('introduce-partners-search',[IntroducePartnerController::class,'search'])->name('introduce-partners-search');
     /**product*/
     Route::resource('product', App\Http\Controllers\API\Admin\ProductController::class);
     Route::get('search-news/{slug}', [App\Http\Controllers\API\Admin\ProductController::class,'searchNews']);
     Route::resource('products', App\Http\Controllers\API\Admin\ProductsController::class);

     Route::match(['post','get'],'/store-product', [App\Http\Controllers\API\Admin\ProductController::class,'storeProduct'])->name('storeProduct');;
     
     //Route::post('product/advertise', [App\Http\Controllers\API\Admin\ProductController::class,'productAdvertise']);
     //**Combo product */
     Route::resource('combo-product',ComboProductController::class);
     /** Member management */
     Route::resource('member-management',MemberManagementController::class);
     Route::resource('member-group',MemberGroupController::class);
     /** List cart member */
     Route::resource('member-list-cart',ListCartController::class);
     /**Comment  */
     Route::resource('comment',App\Http\Controllers\API\Admin\CommentController::class);
     /*Setting*/
     Route::resource('setting',SettingController::class);
     /*Introduction*/
    Route::resource('introduction',IntroductionController::class);
    //  Route::post('introduction', [IntroductionController::class,'store'])->name('introduction');
    /*guide*/
     Route::resource('guide',GuideController::class);
     Route::get('/search-guide',[GuideController::class,'search']);
     /*news*/
    Route::resource('news',App\Http\Controllers\API\Admin\NewsController::class);
     /*news-Category*/
     Route::resource('news-category',App\Http\Controllers\API\Admin\NewsCategoryController::class);
     /*promotion*/
     Route::resource('promotion',PromotionController::class);
     Route::get('/search-promotion',[PromotionController::class,'search']);
    /*faqs*/
    Route::resource('faqs',FaqsController::class);
    /*chart */
    Route::resource('chart',ChartController::class);
    /*faqs-Category*/
    Route::resource('faqs-category',FaqsCategoryController::class);
    /*service*/
    Route::resource('service',ServiceController::class);
    Route::get('/search-service',[ServiceController::class,'search']);
    
    /*contact*/
    Route::resource('contact',ContactController::class);
    Route::get('/contact-export',[ContactController::class,'export']);
    /*contact-staff*/
    Route::resource('contact-staff',ContactStaffController::class);
    /*contact-config(setting)*/
    Route::resource('contact-config',ContactConfigController::class);
    /*contact-qoute*/
    Route::resource('contact-qoute',ContactQouteController::class);
    /*mailtemp*/
    Route::resource('mailtemp',MailTempController::class);
     /**Support member */
    Route::resource('member-support',SupportController::class);
    
     Route::get('/member-support-search',[SupportController::class,'search']);
     Route::resource('member-support-group',App\Http\Controllers\API\Admin\SupportGroupController::class);
     Route::get('/member-support-group-search',[SupportGroupController::class,'search']);
     //*Export excel*/
     Route::post('/product-price-export',[ProductPriceController::class,'store']);
     //* Price Product Create*/
     Route::get('/product-price',[ProductPriceController::class,'create'])->name('productPrice');
     //category-product
     Route::resource('category', CategoryController::class);
     //option
     Route::resource('option', OptionController::class);
     //Brand
     Route::resource('brand', BrandController::class);
     //properties
    Route::resource('properties', PropertiesController::class);

    Route::get('select-category-child/{catId}', [PropertiesController::class,'selectClildCategory']);

    Route::get('check-op-category/{catId}/{title}', [PropertiesController::class,'checkOpCategory']);

    Route::get('select-one-cate/{catId}', [PropertiesController::class,'selectOneClildCategory']);

    //selectOneClildCategory
     
     //advertise
     Route::resource('advertise',AdvertiseController::class);
     Route::resource('ad-pos',App\Http\Controllers\API\Admin\AdposController::class);
     //StatisticsPages
     Route::resource('statistics',StatisticsController::class); 
     Route::post('statistics/export',[StatisticsController::class,'export']);

     //product-flash-sale
     
     Route::resource('product-flash-sale', App\Http\Controllers\API\Admin\ProductFlashSaleController::class);
     Route::put('/date-flash-sale', [App\Http\Controllers\API\Admin\ProductFlashSaleController::class,'updatedate'])->name('flash-sale');

     // search product_group
    Route::get('select-product-group', [App\Http\Controllers\API\Admin\ProductController::class,'searchProductGroup']);

    
});
 
Route::group(['middleware' => 'api', 'prefix' => 'member'], function () {
    Route::post('import-code',[ImportExportController::class,'exportCodevoucher']);
   
    Route::get('get-partnerIntro',[App\Http\Controllers\API\Admin\PartnerIntroduceController::class,'getPartnerIntroduce']);
    Route::resource('partner',App\Http\Controllers\API\Member\PartnerController::class);
    Route::get('partner-detail/{slug}',[App\Http\Controllers\API\Member\PartnerController::class,'detailPartner']);
    Route::get('partnerNews-detail/{slug}',[App\Http\Controllers\API\Member\PartnerController::class,'detailPartnerNews']);
    Route::get('get-delivery',[App\Http\Controllers\API\Admin\OrderManagementController::class,'getDelivery']);

    Route::get('/services',[App\Http\Controllers\API\Member\ServiceController::class,'index']);
    Route::get('/service-detail/{slug}',[App\Http\Controllers\API\Member\ServiceController::class,'detail']);

    Route::resource('advertise',App\Http\Controllers\API\Member\AdvertiseController::class);

    Route::get('promotion',[App\Http\Controllers\API\Member\PromotionController::class,'index']);
    Route::get('promotion-show',[App\Http\Controllers\API\Member\PromotionController::class,'show']);
    Route::get('/promotion/{slug}',[App\Http\Controllers\API\Member\PromotionController::class,'detail'])->name('promotion-detail');
        /**Product related */
    Route::get('/category-member-search',[App\Http\Controllers\API\Member\CategoryController::class,'search'])->name('category-member-search');
    /**Compare two products */
    Route::get('compare-products',[App\Http\Controllers\API\Member\BrandController::class,'compareProducts'])->name('compare-products');
    /**Compare search product brand */
    Route::get('/compare-product-search',[App\Http\Controllers\API\Member\BrandController::class,'searchCategoryProduct'])->name('category-compare-search');
    //** list category search brand  */
    Route::get('/category-list/{idCategory}',[App\Http\Controllers\API\Member\BrandController::class,'listCategory'])->name('list-category-member');
    
    //**category home */
    Route::get('/category-list-home',[App\Http\Controllers\API\Member\CategoryController::class,'listCategoryHome'])->name('list-category-member-home');
    Route::get('/category-list-option',[App\Http\Controllers\API\Member\CategoryController::class,'listCategoryOption'])->name('list-category-option');

    /**Relate product in brand */
    Route::get('/product-relate-brand',[RelatedProductController::class,'productRelatedBrand'])->name('productRelatedBrand');
    /**Relate Product */
    Route::get('/relate-product',[RelatedProductController::class,'index'])->name('relate-product');
    /**Product other category */
    Route::get('/product-proposal',[RelatedProductController::class,'productProposal'])->name('productProposal');
    /**Post relate product */
    Route::get('/post-relate',[RelatedProductController::class,'postRelate'])->name('post-relate');
    //**Recommended products for purchase */
    Route::get('/recommend-products',[App\Http\Controllers\API\Member\ProductController::class,'bundledProduct'])->name('recommend-product');
    /**Search product */
    Route::get('/search-product',[App\Http\Controllers\API\Member\ProductController::class,'searchProduct'])->name('search-product');
    /**Get list product */
    Route::get('/product',[App\Http\Controllers\API\Member\ProductController::class,'index'])->name('product-member');
    

    /**Show product detail */

    Route::get('/check-product/{slug}',[App\Http\Controllers\API\Member\ProductController::class,'checkProductDetail']);

    Route::get('/product-detail/{slug}',[App\Http\Controllers\API\Member\ProductController::class,'detail'])->name('product-detail');
    /**Show product hot */
    Route::get('/product-hot',[App\Http\Controllers\API\Member\ProductController::class,'productHot'])->name('product-hot');
    
    /**Get list category product */
    Route::get('/category',[App\Http\Controllers\API\Member\CategoryController::class,'index'])->name('category-member');
    Route::get('/category-menu',[App\Http\Controllers\API\Member\CategoryController::class,'menu'])->name('category-menu');
    Route::get('/select-category/{slug}',[App\Http\Controllers\API\Member\CategoryController::class,'selectCategoryChild'])->name('select-category');
    
    /**Get category detail and show product other category */
    Route::get('/category-detail/{id}/{sub?}',[App\Http\Controllers\API\Member\CategoryController::class,'detail'])->name('category-detail');
    // /**Get list brand product */
    Route::get('/brand',[App\Http\Controllers\API\Member\BrandController::class,'index'])->name('brand-member');
    /**Get list comment and create comment  */
    Route::get('/comment',[App\Http\Controllers\API\Member\CommentController::class,'index'])->name('comment-index');
    Route::post('/comment',[App\Http\Controllers\API\Member\CommentController::class,'store'])->name('comment-store');
    /**Show HOTLINE customer care */
    Route::get('/support',[App\Http\Controllers\API\Member\SupportController::class,'index'])->name('support-index');
    /**Filter category show product */
    Route::get('/filter-category',[App\Http\Controllers\API\Member\FilterCategoryController::class,'filter']);
    Route::get('/category-option',[App\Http\Controllers\API\Member\FilterCategoryController::class,'index']);
    Route::get('/cate-child-option',[App\Http\Controllers\API\Member\FilterCategoryController::class,'cateChildOption']);
    Route::get('/render-html',[App\Http\Controllers\API\Member\RenderHtmlController::class,'index'])->name('render');
    Route::get('/react-files',[App\Http\Controllers\API\Member\RenderHtmlController::class,'show']);
     //*New member/
     Route::get('news/{slug}',[App\Http\Controllers\API\Member\NewsController::class,'index'])->name('news-category');  
     Route::get('news-search',[App\Http\Controllers\API\Member\NewsController::class,'search'])->name('news-search');   
     //newCategory
     Route::get('news-category',[App\Http\Controllers\API\Member\NewsCategoryController::class,'index'])->name('news-category'); 
     //*New member detail/
     Route::get('news-detail/{slug}',[App\Http\Controllers\API\Member\NewsController::class,'detail'])->name('news-category-detail');      
    /**Cart Member */
    Route::resource('list-cart',App\Http\Controllers\API\Member\CartController::class);
    /**Order Member */
    Route::resource('order',App\Http\Controllers\API\Member\OrderController::class);
    Route::get('total-order-month',[App\Http\Controllers\API\Member\OrderController::class,'totalOrderMonth']);
    Route::resource('guide',App\Http\Controllers\API\Member\GuideController::class);
    Route::resource('about',App\Http\Controllers\API\Member\AboutController::class);
    /**Sale support Member */
    Route::get('sale-support',[App\Http\Controllers\API\Member\SaleSupportController::class,'index']);
    /**Build PC */
    Route::get('build-pc',[App\Http\Controllers\API\Member\BuildPCController::class,'index'])->name('build-pc');
    Route::get('filter-build-pc',[App\Http\Controllers\API\Member\BuildPCController::class,'filterBuildPc'])->name('filter-build-pc');
    //**Export excel build pc */
    // Route::get('export-excel-pc',[App\Http\Controllers\API\Member\BuildPCController::class,'exportExcelPC'])->name('export-excel-build-pc');
    Route::get('export-excel-pc',[App\Http\Controllers\API\Member\BuildPCController::class,'exportExcelPC'])->name('export-excel-build-pc');
    //**download PDF */
    Route::post('download-pdf',[App\Http\Controllers\API\Member\BuildPCController::class,'downloadPDF'])->name('download-pdf-build-pc');
    /**Flase Sale  */
    Route::resource('flash-sale',App\Http\Controllers\API\Member\FlashSaleController::class);
    Route::get('flash-sale-show',[App\Http\Controllers\API\Member\FlashSaleController::class,'show']);
    Route::get('update-flash-sale',[App\Http\Controllers\API\Member\FlashSaleController::class,'update']);
    // Route::get('/filter-flash-sale',[App\Http\Controllers\API\Member\FlashSaleController::class,'filter']);
    /**Coupon */
    Route::get('coupon',[App\Http\Controllers\API\Member\CouponController::class,'index'])->name('coupon-member');
    /**infomation, account */
    // Route::match(['get','post'],'/update-profile', [App\Http\Controllers\API\Member\AuthController::class, 'update'])->name('update-profile');
    Route::get('member-information',[App\Http\Controllers\API\Member\AuthController::class,'information'])->name('member-information');
    Route::post('member-information',[App\Http\Controllers\API\Member\AuthController::class,'update'])->name('member-update');
    Route::post('forget-password',[App\Http\Controllers\API\Member\AuthController::class,'forgetPassword'])->name('forget-password');
    Route::post('forget-password-change',[App\Http\Controllers\API\Member\AuthController::class,'forgetPasswordChange'])->name('forget-password-change');
    Route::get('coupon-list',[App\Http\Controllers\API\Member\CouponController::class,'index'])->name('coupon-list');
    /**Checkout and card promotion */
    Route::post('checkout',[CheckoutController::class,'checkout'])->name('checkout');
    //**repurchase*/
    Route::resource('repurchase',RepurchaseController::class);

});
Route::resource('member-support',SupportController::class);
Route::resource('admin-chat', ChatAdminController::class);
Route::resource('user-chat', ChatUserController::class);
Route::resource('chat-message', MessageController::class);
Route::resource('list-cart', ListCartController::class);
Route::resource('product-advertise', ProductAdvertiseController::class);
Route::get('product-show/{id}', [ProductController::class,'list']);
Route::resource('star',StarController::class);

//backupDatabase

Route::get('/backup-database', [DatabaseBackupController::class,'backupDatabse'])->name('backup-database');
Route::get('/category-redis-delete', [App\Http\Controllers\Api\Member\CategoryController::class, 'deleteKeyRedis'])->name('deleteKeyRedis');
// Route::get('/craw/{key}', [App\Http\Controllers\Api\Admin\CrawController::class, 'craw'])->name('craw');
Route::get('/product-redis-delete', [App\Http\Controllers\Api\Member\ProductController::class,
 'deleteKeyRedis'])->name('productDeleteKey');

