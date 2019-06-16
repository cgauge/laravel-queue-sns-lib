<?php

namespace Tests\CustomerGauge\Laravel\Queue\Sns;

use CustomerGauge\Laravel\Queue\Sns\Exceptions\TopicException;
use CustomerGauge\Laravel\Queue\Sns\JobMap;
use PHPUnit\Framework\TestCase;

class JobMapTest extends TestCase
{
    public function test_topic_mapped_to_a_processable_job()
    {
        $map = new JobMap([
            $topic = 'arn:aws:sns:eu-west-1:000000000000:mapped' => SampleJob::class
        ]);

        $this->expectException(TopicException::class);

        $job = $map->fromTopic($topic, ['id' => 5], ['attr' => 'val']);

        self::assertSame(5, $job->data['id']);

        self::assertSame('val', $job->attributes['attr']);
    }

    public function test_topic_must_be_mapped()
    {
        $map = new JobMap([]);

        $this->expectException(TopicException::class);

        $map->fromTopic('arn:aws:sns:eu-west-1:000000000000:not-mapped', [], []);
    }
}

class SampleJob
{
    public $data;

    public $attributes;

    public function __construct(array $data, array $attributes)
    {
        $this->data = $data;
        $this->attributes = $attributes;
    }
}
