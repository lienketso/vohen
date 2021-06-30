<?php

namespace Botble\Marketplace\Providers;

use Botble\Base\Supports\Helper;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Ecommerce\Models\Customer;
use Botble\Marketplace\Http\Middleware\RedirectIfNotVendor;
use Botble\Marketplace\Models\Store;
use Botble\Marketplace\Models\Warehouse;
use Botble\Marketplace\Repositories\Caches\StoreCacheDecorator;
use Botble\Marketplace\Repositories\Caches\WarehouseCacheDecorator;
use Botble\Marketplace\Repositories\Eloquent\StoreRepository;
use Botble\Marketplace\Repositories\Eloquent\WarehouseRepository;
use Botble\Marketplace\Repositories\Interfaces\StoreInterface;
use Botble\Marketplace\Repositories\Interfaces\WarehouseInterface;
use Event;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use SeoHelper;
use SlugHelper;

class MarketplaceServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        if (is_plugin_active('ecommerce')) {
            $this->app->bind(StoreInterface::class, function () {
                return new StoreCacheDecorator(
                    new StoreRepository(new Store)
                );
            });
            $this->app->bind(WarehouseInterface::class, function () {
                return new WarehouseCacheDecorator(
                    new WarehouseRepository(new Warehouse())
                );
            });

            Helper::autoload(__DIR__ . '/../../helpers');

            /**
             * @var Router $router
             */
            $router = $this->app['router'];

            $router->aliasMiddleware('vendor', RedirectIfNotVendor::class);
        }
    }

    public function boot()
    {
        if (is_plugin_active('ecommerce')) {
            $this->setNamespace('plugins/marketplace')
                ->loadAndPublishConfigurations(['permissions', 'assets'])
                ->loadMigrations()
                ->loadAndPublishTranslations()
                ->loadAndPublishViews()
                ->publishAssets()
                ->loadRoutes(['base', 'fronts', 'product']);

            Event::listen(RouteMatched::class, function () {
                dashboard_menu()
                    ->registerItem([
                        'id'          => 'cms-plugins-marketplace',
                        'priority'    => 9,
                        'parent_id'   => null,
                        'name'        => 'plugins/marketplace::marketplace.name',
                        'icon'        => 'fas fa-project-diagram',
                        'url'         => '#',
                        'permissions' => ['marketplace.index'],
                    ])
                    ->registerItem([
                        'id'          => 'cms-plugins-store',
                        'priority'    => 0,
                        'parent_id'   => 'cms-plugins-marketplace',
                        'name'        => 'plugins/marketplace::store.name',
                        'icon'        => null,
                        'url'         => route('marketplace.store.index'),
                        'permissions' => ['marketplace.store.index'],
                    ]);

            });

            SlugHelper::registerModule(Store::class, 'Stores');
            SlugHelper::setPrefix(Store::class, 'stores');

            SeoHelper::registerModule([Store::class]);

            $this->app->register(HookServiceProvider::class);

            $this->app->booted(function () {
                Customer::resolveRelationUsing('store', function ($model) {
                    return $model->hasOne(Store::class)->withDefault();
                });
            });
        }
    }
}
