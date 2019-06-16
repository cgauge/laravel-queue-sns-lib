<?php

namespace Tests\CustomerGauge\Laravel\Queue\Sns;

use Aws\Sqs\SqsClient;
use CustomerGauge\Laravel\Queue\Sns\Contracts\Processable;
use CustomerGauge\Laravel\Queue\Sns\SnsQueueServiceProvider;
use Orchestra\Testbench\TestCase;

/**
 * @group integration
 */
class IntegrationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        MyJob::$processed = false;
    }

    protected function getPackageProviders($app)
    {
        return [SnsQueueServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('queue.connections.sns', [
            'endpoint' => 'http://localstack:4576',
            'driver' => 'sns',
            'key' => 'foo',
            'secret' => 'bar',
            'queue' => 'http://localstack:4576/queue/test-queue',
            'region' => 'local',
            'map' => [
                MyJob::class => 'arn:aws:sns:eu-west-1:000000000000:localstack',
            ]
        ]);
    }

    private function sendMessageToSqs(string $message)
    {
        $client = $this->makeClient();

        $client->createQueue(['QueueName' => 'test-queue']);

        $client->purgeQueue(['QueueUrl' => 'http://localstack:4576/queue/test-queue']);

        $client->sendMessage([
            'QueueUrl' => 'http://localstack:4576/queue/test-queue',
            'MessageBody' => $message,
        ]);
    }

    private function makeClient(): SqsClient
    {
        return new SqsClient([
            'endpoint' => 'http://localstack:4576',
            'version' => 'latest',
            'region' => 'local',
            'credentials' => [
                'key' => 'foo',
                'secret' => 'bar',
            ]
        ]);
    }

    public function test_it_can_process_messages_from_sqs_when_populated_by_sns()
    {
        $this->sendMessageToSqs(file_get_contents(__DIR__ . '/fixtures/sample.json'));

        $this->artisan('queue:work', [
            '--once' => true,
            'connection' => 'sns',
        ]);

        self::assertTrue(MyJob::$processed);
    }
}

class MyJob implements Processable
{
    public static $processed;

    private $data;

    private $attributes;

    public function __construct(array $data, array $attributes)
    {

        $this->data = $data;
        $this->attributes = $attributes;
    }

    public function handle()
    {
        self::$processed = true;
    }
}
