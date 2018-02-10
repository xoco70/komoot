Hourly Digest Mails
===================
 
- I used PHP 7.1 / Laravel 5.5, as it is the latest tech I have used.
- I used SQS to send mail asynchronously
- I used Gmail to send mail
- I use RDS (MariaDB) to store Data ( DynamoDB should have been better for scaling and speed, but make no difference for this exercise)


Here is the general flow:

1. Create HTTPS SNS Subscription -> Confirm Sub -> Insert each record in DB
2. Each Hour, We create a Job that format the mails to send, and add a Queue Item


Let's review the code:
For the HTTPS subscription, I had to follow this doc: https://docs.aws.amazon.com/sns/latest/dg/SendMessageToHttp.html
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

Installation
=============

```bash
git clone https://github.com/xoco70/komoot.git

``` 


> Note: LIMITS of this work
> - Right now, we send multiple mails in a single Job. It is not the best thing to do in case of failed ones.
> Ideally, we would like to have 1 mail x job, so if a mail sending is failing, we can retry it independenlty from other mails 
> - Also, a bad thing in my design ( I will fix it ) is that I send mails async, which is great, because it scales 
> but I have no garantees that it will be sent each hour exactly.
> Solution: I should I include a hard reference of my DB Registries  
 
 
 
 
 
