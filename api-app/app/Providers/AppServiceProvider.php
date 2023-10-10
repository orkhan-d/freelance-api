<?php

namespace App\Providers;



use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Response::macro('error', function ($msg, $code){
            return \response()->json([
                'error'=>[
                    'code'=>$code,
                    'message'=>$msg
                ]
            ], $code);
        }
        );
    }
}
