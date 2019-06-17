<?php

declare(strict_types=1);

namespace CustomerGauge\Laravel\Queue\Sns;

use CustomerGauge\Laravel\Queue\Sns\Contracts\Processable;
use CustomerGauge\Laravel\Queue\Sns\Exceptions\TopicException;
use function array_search;

class JobMap
{
    private $map;

    public function __construct(array $map)
    {
        $this->map = $map;
    }

    public function fromTopic(string $topic, array $message, array $attributes) : Processable
    {
        $job = array_search($topic, $this->map);

        if (! $job) {
            throw TopicException::missingMap($topic);
        }

        return new $job($message, $attributes);
    }
}
