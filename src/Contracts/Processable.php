<?php

namespace CustomerGauge\Laravel\Queue\Sns\Contracts;

interface Processable
{
    public function __construct(array $data, array $attributes);
}
