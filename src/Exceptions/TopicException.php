<?php

namespace CustomerGauge\Laravel\Queue\Sns\Exceptions;

use Exception;

class TopicException extends Exception
{
    public static function missingArn(string $id): self
    {
        return new self("Message with id [$id] does not have a Topic Arn");
    }

    public static function missingMap(string $topic): self
    {
        return new self("Topic [$topic] is not mapped to any Job.");
    }
}
