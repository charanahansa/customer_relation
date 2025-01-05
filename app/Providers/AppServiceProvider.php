<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(){

        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(){

        $_SERVER["SERVER_NAME"] = "epiclanka.net";

        Blade::directive('money', function ($amount) {
			return "<?php echo number_format($amount, 2); ?>";
		});

		Schema::defaultStringLength(191);

        // if (env('APP_ENV') !== 'local') {
        //     URL::forceScheme('https');
        // }
        date_default_timezone_set('Asia/Colombo');

    }
}
