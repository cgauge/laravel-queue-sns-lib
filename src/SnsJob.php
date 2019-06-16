<?php

namespace CustomerGauge\Laravel\Queue\Sns;

use Aws\Sqs\SqsClient;
use CustomerGauge\Laravel\Queue\Sns\Exceptions\TopicException;
use Illuminate\Container\Container;
use Illuminate\Queue\Jobs\SqsJob;

class SnsJob extends SqsJob
{
    /** @var JobMap */
    private $map;

    public function __construct(Container $container, SqsClient $sqs, array $job, string $connectionName, string $queue, JobMap $map)
    {
        parent::__construct($container, $sqs, $job, $connectionName, $queue);
        $this->map = $map;
    }

    public function payload()
    {
        $payload = parent::payload();

        if (! isset($payload['TopicArn'])) {
            throw TopicException::missingArn($this->getJobId());
        }

        $payload['job'] = 'Illuminate\Queue\CallQueuedHandler@call';

        $attributes = $payload['MessageAttributes'] ?? [];

        $command = $this->map->fromTopic(
            $payload['TopicArn'],
            $this->convertMessageToArray($payload['Message']),
            $attributes
        );

        $payload['data']['commandName'] = get_class($command);

        $payload['data']['command'] = serialize($command);

        return $payload;
    }

    protected function convertMessageToArray($message): array
    {
        return (array) json_decode($message, true);
    }
}
