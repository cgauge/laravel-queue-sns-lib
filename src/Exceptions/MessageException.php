<?php

namespace CustomerGauge\Laravel\Queue\Sns\Exceptions;

use Exception;

class MessageException extends Exception
{
    public static function emptyTopic(string $id): self
    {
        return new self("Message with id [$id] does not have a Topic Arn");
    }
}
