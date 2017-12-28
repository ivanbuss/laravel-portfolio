<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Elastic;
use Elasticsearch\ClientBuilder;

class ElasticServiceProvider extends ServiceProvider {
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {
        $this->app->bind(Elastic::class, function ($app) {
            return new Elastic(
                ClientBuilder::create()
                    ->setHosts(config('search.elastic.hosts'))
                    ->setLogger(ClientBuilder::defaultLogger(storage_path('logs/elastic.log')))
                    ->build()
            );
        });
    }
}
