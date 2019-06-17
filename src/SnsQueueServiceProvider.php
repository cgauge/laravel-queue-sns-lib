<?php

declare(strict_types=1);

namespace CustomerGauge\Laravel\Queue\Sns;

use Illuminate\Queue\QueueManager;
use Illuminate\Support\ServiceProvider;

class SnsQueueServiceProvider extends ServiceProvider
{
    public function register() : void
    {
        $this->app->afterResolving(QueueManager::class, static function (QueueManager $manager) : void {
            $manager->addConnector('sns', static function () {
                return new SnsConnector();
            });
        });
    }
}
