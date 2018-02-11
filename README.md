Hourly Digest Mails
===================

Installation
------------

```bash
git clone https://github.com/xoco70/komoot.git
composer install
cp .env.example .env
php artisan key:generate
```
Fill the empty values of .env file


To be able to run scheduled tasks with laravel, you must add a cron task:

```bash
 * * * * * php path/to/artisan schedule:run >> /dev/null 2>&1
 ```
To be able to consume SQS Queue there is 2 options:
1. In development, you can do 
```bash
 php artisan queue:listen
 ```

2. In production, you can use Supervisor 
https://laravel.com/docs/5.5/queues#supervisor-configuration

Now you can change

 
- I used PHP 7.1 / Laravel 5.5, as it is the latest tech I have used.
- I used SQS to send mail asynchronously
- I used Gmail to send mail
- I use RDS (MariaDB) to store Data ( DynamoDB should have been better for scaling and speed, but make no difference for this exercise)


General flow:
------------

1. Create HTTPS SNS Subscription -> Confirm Sub -> Insert each record in DB
2. Each Hour, We create a Job that format the mails to send, and add a Queue Item


Let's review the code:
For the HTTPS subscription, I followed this doc: https://docs.aws.amazon.com/sns/latest/dg/SendMessageToHttp.html

Confirm SNS and insert into DB: https://github.com/xoco70/komoot/blob/master/app/Http/Controllers/NotificationController.php

```php
$header = $request->headers->get('X-Amz-Sns-Message-Type');
$json = $request->getContent();
$data = json_decode($json, TRUE);
if ($header == 'SubscriptionConfirmation') {
    $subscribeUrl = $data['SubscribeURL'];
    return Redirect::away($subscribeUrl);
} elseif ($header == 'Notification') {
    Log::info('Notification');
    $arrRecord = json_decode($data['Message']); //
    $record = new Record;
    foreach ($arrRecord as $key => $value) {
        $record->$key = $value;
    }
    $record->save();
}
```

We trigger a hourly job:

https://github.com/xoco70/komoot/blob/master/app/Console/Kernel.php

```php
$schedule
    ->job(new SendMailDigest())
    ->hourlyAt(0);
```
**What is good in my method**
- Flexible, you could easily change 1 hour time range to 3 hours
- Minimalist, 20L in controller, 30L in model, 10L in Job, and 3 lines in scheduler, it is difficult to have a more syntetic and readable code 
- Scalable and decoupled, if you have a lot of traffic, you can add more workers to process SQS
 

**What could be better in my method / Posible improvements**
- One thing that could be improved is that I send mails async, which is great, because it scales, and it is loosely coupled,  
but I have no garantees that mails will be sent each hour exactly, I can eventually lose some records 
**Solution**: I should include a hard time reference of the last processed DB Registry  
- Using a non gmail email. When having  a lot of jobs queued, we may encounter a Google Issue: Too many mails per seconds 
- Add some tests.
 
 
 
