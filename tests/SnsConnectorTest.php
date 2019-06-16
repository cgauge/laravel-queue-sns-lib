<?php

namespace Tests\CustomerGauge\Laravel\Queue\Sns;

use CustomerGauge\Laravel\Queue\Sns\Exceptions\TopicException;
use CustomerGauge\Laravel\Queue\Sns\SnsConnector;
use CustomerGauge\Laravel\Queue\Sns\SnsQueue;
use PHPUnit\Framework\TestCase;

class SnsConnectorTest extends TestCase
{
    public function test_connector_will_establish_sns_queue(): void
    {
        $connector = new SnsConnector();

        $queue = $connector->connect([
            'key' => null,
            'secret' => null,
            'region' => 'eu-west-1',
            'queue' => 'queue-name',
            'map' => [
                \My\Fake\Job::class => 'arn:aws:sns:eu-west-1:000000000000:my-fake-topic'
            ]
        ]);

        self::assertInstanceOf(SnsQueue::class, $queue);
    }

    public function test_connector_should_have_at_least_one_mapped_topic(): void
    {
        $connector = new SnsConnector();

        $this->expectException(TopicException::class);

        $connector->connect([
            'key' => null,
            'secret' => null,
        ]);
    }
}
