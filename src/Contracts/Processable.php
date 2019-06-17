<?php

declare(strict_types=1);

namespace CustomerGauge\Laravel\Queue\Sns\Contracts;

interface Processable
{
    public function __construct(array $data, array $attributes);
}
