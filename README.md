# Turatel SMS Notifications Channel for Laravel

This package makes it easy to send sms notifications using [Turatel](https://www.turatel.com/) with Laravel 10.0+

## Contents

- [Installation](#installation)
    - [Setting up the Ileti Merkezi service](#setting-up-the-Ileti-Merkezi-service)
- [Usage](#usage)
    - [ On-Demand Notifications](#on-demand-notifications)
- [Testing](#testing)
- [Changelog](#changelog)
- [Credits](#credits)

## Installation

You can install this package via composer:

``` bash
composer require macellan/turatel
```


### Setting up the Turatel service

Add your Turatel sms gate configs to your config/services.php:

```php
// config/services.php
...
    'sms' => [
        'turatel' => [
            'sms_service' => [
                'enable' => env('TURATEL_SMS_SERVICE_ENABLE', false),
                'debug' => env('TURATEL_SMS_SERVICE_DEBUG', false),
                'sandbox_mode' => env('TURATEL_SMS_SERVICE_SANDBOX_MODE', false),
                'base_url' => env('TURATEL_SMS_SERVICE_BASE_URL', 'https://processor.smsorigin.com/xml/process.aspx'),
                'platform_id' => env('TURATEL_SMS_SERVICE_PLATFORM_ID', 0),
                'channel_code' => env('TURATEL_SMS_SERVICE_CHANNEL_CODE', 0),
                'user_name' => env('TURATEL_SMS_SERVICE_USER_NAME', ''),
                'password' => env('TURATEL_SMS_SERVICE_PASSWORD', ''),
                'originator' => env('TURATEL_SMS_SERVICE_ORIGINATOR', ''),
            ],
        ],
    ],
...
```


## Usage

You can use the channel in your via() method inside the notification:

```php
use Illuminate\Notifications\Notification;
use Macellan\Turatel\Notifications\Messages\TuratelSmsMessage;

class TestNotification extends Notification
{
    public function via($notifiable)
    {
        return ['turatel_sms'];
    }

    public function toTuratelSms($notifiable): TuratelSmsMessage
    {
        return new TuratelSmsMessage('Test Message');
    }
}
```

In your notifiable model, make sure to include a routeNotificationForSms() method, which returns a phone number or an array of phone numbers.

```php
public function routeNotificationForSms()
{
    return str_replace(['+', ' '], '', $this->phone);
}
```


### On-Demand Notifications

Sometimes you may need to send a notification to someone who is not stored as a "user" of your application. Using the Notification::route method, you may specify ad-hoc notification routing information before sending the notification:

```php
Notification::route('turatel_sms', '905554443322')  
            ->notify(new TestNotification());
```
## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Credits

- [Arif Demir](https://github.com/epicentre)
