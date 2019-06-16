# laravel-queue-sns-lib 📡

Laravel Queue Sns Library extends Laravel Queue component with one extra driver: `sns`.
This driver provides the capability of processing AWS SQS Messages populated by AWS SNS when using [SQS as a subscriber of SNS](https://docs.aws.amazon.com/sns/latest/dg/sns-sqs-as-subscriber.html).

## Installation

```bash
composer require customergauge/laravel-queue-sns-lib
```

## Configuration

With the library installed, it will be possible to add a new queue configuration to Laravel's `queue.php` config file as follow:

```php
        'sns' => [
            'driver' => 'sns',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'prefix' => env('SUBSCRIBER_PREFIX', 'https://sqs.us-east-1.amazonaws.com/your-account-id'),
            'queue' => env('SUBSCRIBER_QUEUE', 'your-queue-name'),
            'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
            'map' => [
                \App\Modules\Accounts\Jobs\AccountSaved::class => env('ACCOUNT_SAVED_TOPIC_ARN'),
            ]
        ],
```
