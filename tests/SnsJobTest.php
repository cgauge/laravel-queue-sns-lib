<?php

namespace Tests\CustomerGauge\Laravel\Queue\Sns;

use Aws\Sqs\SqsClient;
use CustomerGauge\Laravel\Queue\Sns\Exceptions\MessageException;
use CustomerGauge\Laravel\Queue\Sns\SnsJob;
use Illuminate\Container\Container;
use PHPUnit\Framework\TestCase;

class SnsJobTest extends TestCase
{
    public function test_message_must_have_topic_arn(): void
    {
        $container = $this->createMock(Container::class);

        $sqs = $this->createMock(SqsClient::class);

//        $map = $this->createMock(JobMap::class);

        $message = [
            'MessageId' => 'uuid',
            'Body' => '',
        ];

        $job = new SnsJob($container, $sqs, $message, 'sns', 'queue'/*, $map*/);

        $this->expectException(MessageException::class);

        $job->payload();
    }
}
