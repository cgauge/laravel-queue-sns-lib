<?php

namespace CustomerGauge\Laravel\Queue\Sns;

use CustomerGauge\Laravel\Queue\Sns\Exceptions\TopicException;
use Exception;

class JobMap
{
    private $map;

    public function __construct(array $map)
    {
        $this->map = $map;
    }

    public function fromTopic(string $topic, array $message, array $attributes)/*: Processable*/
    {
        $job = array_search($topic, $this->map);

        if (! $job) {
            throw TopicException::missingMap($topic);
        }

        return new $job($message, $attributes);
    }
}
