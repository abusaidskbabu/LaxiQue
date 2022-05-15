<?php
   
namespace App\Providers;
  
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
  
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(){
        Inertia::share('app.name', config('app.name')); 
       
        Inertia::share('errors', function(){
            return session()->get('errors') ? session()->get('errors')->getBag('default')->getMessages(): (object)[];
        });
 
        Inertia::share('successMessage', function(){
             return session()->get('successMessage') ? session()->get('successMessage') : null;
         });
         Inertia::share('errorMessage', function(){
            return session()->get('errorMessage') ? session()->get('errorMessage') : null;
        });
    }
  
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Inertia::share([
            'errors' => function () {
                return Session::get('errors')
                    ? Session::get('errors')->getBag('default')->getMessages()
                    : (object) [];
            },
        ]);
  
        Inertia::share('flash', function () {
            return [
                'message' => Session::get('message'),
            ];
        });
    }
}