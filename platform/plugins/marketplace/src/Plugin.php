<?php

namespace Botble\Marketplace;

use Botble\PluginManagement\Abstracts\PluginOperationAbstract;
use Illuminate\Database\Schema\Blueprint;
use Schema;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        Schema::dropIfExists('mp_customer_revenues');
        Schema::dropIfExists('mp_customer_withdrawals');

        Schema::table('ec_orders', function (Blueprint $table) {
            $table->dropColumn('store_id');
        });

        Schema::table('ec_products', function (Blueprint $table) {
            $table->dropColumn('store_id');
        });

        Schema::table('ec_customers', function (Blueprint $table) {
            $table->dropColumn(['is_vendor', 'balance']);
        });

        Schema::dropIfExists('mp_stores');
    }
}
