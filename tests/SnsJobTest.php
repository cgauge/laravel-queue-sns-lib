<?php

namespace Tests\CustomerGauge\Laravel\Queue\Sns;

use Aws\Sqs\SqsClient;
use CustomerGauge\Laravel\Queue\Sns\Contracts\Processable;
use CustomerGauge\Laravel\Queue\Sns\Exceptions\TopicException;
use CustomerGauge\Laravel\Queue\Sns\JobMap;
use CustomerGauge\Laravel\Queue\Sns\SnsJob;
use Illuminate\Container\Container;
use PHPUnit\Framework\TestCase;

class SnsJobTest extends TestCase
{
    public function test_job_payload_structure()
    {
        $container = $this->createMock(Container::class);

        $sqs = $this->createMock(SqsClient::class);

        $map = new JobMap([MyFakeJob::class => $topic = 'arn:aws:sns:eu-west-1:000000000000:topic-name']);

        $message = [
            'MessageId' => __FUNCTION__,
            'Body' => json_encode([
                'TopicArn' => $topic,
                'Message' => '',
            ]),
        ];

        $job = new SnsJob($container, $sqs, $message, 'sns', 'queue', $map);

        $payload = $job->payload();

        self::assertArrayHasKey('job', $payload);

        self::assertArrayHasKey('command', $payload['data']);

        self::assertSame(MyFakeJob::class, $payload['data']['commandName']);
    }

    public function test_message_must_have_topic_arn(): void
    {
        $container = $this->createMock(Container::class);

        $sqs = $this->createMock(SqsClient::class);

        $map = $this->createMock(JobMap::class);

        $message = [
            'MessageId' => __FUNCTION__,
            'Body' => '',
        ];

        $job = new SnsJob($container, $sqs, $message, 'sns', 'queue', $map);

        $this->expectException(TopicException::class);

        $job->payload();
    }
}

class MyFakeJob implements Processable
{
    public function __construct(array $data, array $attributes)
    {
    }
}