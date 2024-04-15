<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;

use Illuminate\Support\Facades\Auth;
use App\Models\AdminGroup;
use App\Models\Admin;
use App\Models\User;





class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // if (! $this->app->routesAreCached()) {
        //     Passport::routes();
        // }
        
        // Gate::define('2',function (User $user){
           
        //     $level = Auth::guard('admin')->user()->level;
        //     return $level;
        //     $adminGroup=AdminGroup::where('id',$level)->first();
        //     if($adminGroup){
        //         $per= explode(',',$adminGroup->permission);
        //         if(in_array('2',$per)){
        //             return true;
        //         }; 
        //     }
        //     return false;
           
        // });
       
        // Gate::define('department.add', function ($user = null, $admin);
        // Gate::define('department.add', function (User $user) {
        //     return $user->hasPermission('department.add');
        // });
       

      



        Passport::routes();
        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        //Passport::personalAccessTokensExpireIn(now()->addMonths(6));
    }

}
