Hourly Digest Mails
===================
 
I used PHP / Laravel, as it is the latest tech I have used.

First, I had to send a mail every minute, so I created a SNS Topic, and created a mail subscription.

I created a Laravel Job to publish a message.

Then, I used a Scheduler to run it every minute.


https://github.com/xoco70/komoot/blob/master/app/Jobs/PublishMessage.php

```
 $sns->publish([
    'Message' => $record,
    'TopicArn' => $this->arn,
    'Subject' => null,
]);
```

https://github.com/xoco70/komoot/blob/master/app/Console/Kernel.php

```
$schedule
    ->job(new PublishMessage())
    ->everyMinute();
```

Now I created 2 subscriptions
 - HTTPS ( OPTIONAL, will be used if needed custom action, like saving messages into DynamoDB )
 - SQS ( will be used to push message directly in Queue)

 For the HTTPS subscription, I had to follow this doc: https://docs.aws.amazon.com/sns/latest/dg/SendMessageToHttp.html
 
 
 
 
