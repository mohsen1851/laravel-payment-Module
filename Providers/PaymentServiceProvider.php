<?php


namespace Mohsen\Payment\Providers;


use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Mohsen\Payment\Gateways\Gateway;
use Mohsen\Payment\Gateways\Zarinpal\ZarinpalAdaptor;
use Mohsen\Payment\Models\Settlement;
use Mohsen\RolePermissions\Models\Permission;
use Mohsen\Payment\Policies\SettlementPolicy;

class PaymentServiceProvider extends ServiceProvider
{
    public $namespace = "Mohsen\Payment\Http\Controllers";

    public function boot()
    {
        $this->app->singleton(Gateway::class, function () {
            return new ZarinpalAdaptor();
        });
        config()->set('sidebar.items.payments',
            [
                'icon' => 'i-transactions',
                'title' => 'ترانش  ها',
                'url' => route('payments.index'),
                'permission' => [Permission::PERMISSION_MANAGE_PAYMENTS]
            ]);

        config()->set('sidebar.items.mu-purchases',
            [
                'icon' => 'i-my__purchases',
                'title' => 'خرید های من',
                'url' => route('purchases.index'),
            ]);

        config()->set('sidebar.items.settlements',
            [
                'icon' => 'i-checkouts',
                'title' => 'تسویه حساب ها',
                'url' => route('settlements.index'),
                'permission' => [
                    Permission::PERMISSION_TEACH,
                    Permission::PERMISSION_MANAGE_SETTLEMENTS
                    ]
            ]);

        config()->set('sidebar.items.settlementsRequest',
            [
                'icon' => 'i-checkout__request',
                'title' => 'درخواست  تسویه',
                'url' => route('settlements.create'),
                'permission' => [
                    Permission::PERMISSION_TEACH,
                ]
            ]);
    }

    public function register()
    {
        $this->app->register(EventServiceProvider::class);
        Route::middleware('web')->namespace($this->namespace)->group(__DIR__ . "/../routes/payments_routes.php");
        Route::middleware('web')->namespace($this->namespace)->group(__DIR__ . "/../routes/settlement_routes.php");
        $this->loadMigrationsFrom(__DIR__ . "/../Database/Migrations");
        $this->loadViewsFrom(__DIR__.'/../Resources/Views','Payment');
        $this->loadJsonTranslationsFrom(__DIR__."/../Resources/Lang/");
        Gate::policy(Settlement::class,SettlementPolicy::class);
    }
}
