<?php

declare(strict_types=1);

namespace CustomerGauge\Laravel\Queue\Sns;

use Aws\Sqs\SqsClient;
use CustomerGauge\Laravel\Queue\Sns\Exceptions\TopicException;
use Illuminate\Queue\Connectors\ConnectorInterface;
use Illuminate\Queue\Connectors\SqsConnector;
use Illuminate\Support\Arr;

class SnsConnector extends SqsConnector implements ConnectorInterface
{
    public function connect(array $config)
    {
        $config = $this->getDefaultConfiguration($config);

        if ($config['key'] && $config['secret']) {
            $config['credentials'] = Arr::only($config, ['key', 'secret', 'token']);
        }

        if (! isset($config['map'])) {
            throw TopicException::empty();
        }

        return new SnsQueue(
            new SqsClient($config),
            $config['queue'],
            new JobMap($config['map'])
        );
    }
}
