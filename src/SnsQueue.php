<?php

namespace CustomerGauge\Laravel\Queue\Sns;

use Aws\Sqs\SqsClient;
use Illuminate\Queue\SqsQueue;

class SnsQueue extends SqsQueue
{
    private $map;

    public function __construct(SqsClient $sqs, string $default, JobMap $map)
    {
        parent::__construct($sqs, $default);

        $this->map = $map;
    }

    public function pop($queue = null)
    {
        $response = $this->sqs->receiveMessage([
            'QueueUrl' => $queue = $this->getQueue($queue),
            'AttributeNames' => ['ApproximateReceiveCount'],
        ]);

        if (! is_null($response['Messages']) && count($response['Messages']) > 0) {
            return new SnsJob(
                $this->container, $this->sqs, $response['Messages'][0],
                $this->connectionName, $queue, $this->map
            );
        }
    }
}