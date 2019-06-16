<?php

namespace Tests\CustomerGauge\Laravel\Queue\Sns;

use Aws\Sqs\SqsClient;
use CustomerGauge\Laravel\Queue\Sns\JobMap;
use CustomerGauge\Laravel\Queue\Sns\SnsJob;
use CustomerGauge\Laravel\Queue\Sns\SnsQueue;
use Illuminate\Container\Container;
use PHPUnit\Framework\TestCase;
use Mockery;

class SnsQueueTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function test_fetch_jobs_from_sqs_queue(): void
    {
        $client = Mockery::mock(SqsClient::class);

        $client
            ->shouldReceive('receiveMessage')
            ->with([
                'QueueUrl' => '/queue-name',
                'AttributeNames' => ['ApproximateReceiveCount'],
            ])
            ->andReturn(['Messages' => [
                ['message' => 'content']
            ]])
            ->once();

        $queue = new SnsQueue($client, 'queue-name', new JobMap([]));

        $queue->setContainer($this->createMock(Container::class));

        $queue->setConnectionName('sns');

        self::assertInstanceOf(SnsJob::class, $queue->pop());
    }
}
