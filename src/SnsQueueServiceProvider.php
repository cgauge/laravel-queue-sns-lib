<?php

namespace CustomerGauge\Laravel\Queue\Sns;

use Illuminate\Queue\QueueManager;
use Illuminate\Support\ServiceProvider;

class SnsQueueServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->afterResolving(QueueManager::class, function (QueueManager $manager) {
            $manager->addConnector('sns', function () {
                return new SnsConnector();
            });
        });
    }
}