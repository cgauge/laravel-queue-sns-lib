<?php

namespace CustomerGauge\Laravel\Queue\Sns;

use App\Components\Queue\JobMap;
use Aws\Sqs\SqsClient;
use CustomerGauge\Laravel\Queue\Sns\Exceptions\MessageException;
use Illuminate\Container\Container;
use Illuminate\Queue\Jobs\SqsJob;

class SnsJob extends SqsJob
{
//    /** @var JobMap */
//    private $map;

    public function __construct(Container $container, SqsClient $sqs, array $job, string $connectionName, string $queue/*, JobMap $map*/)
    {
        parent::__construct($container, $sqs, $job, $connectionName, $queue);
//        $this->map = $map;
    }

    public function payload()
    {
        $payload = parent::payload();

        if (! isset($payload['TopicArn'])) {
            throw MessageException::emptyTopic($this->getJobId());
        }
    }
}
