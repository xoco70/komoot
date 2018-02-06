<?php

namespace App\Http\Controllers;


use Faker\Factory;
use Illuminate\Support\Facades\App;

class MailController extends Controller
{
    protected function index()
    {
        $sns = App::make('aws')->createClient('sns');
        $faker = Factory::create();
        $rdmSentence = $faker->sentence();
        try {
            $result = $sns->publish([
                'Message' => $rdmSentence,
                'TopicArn' => 'arn:aws:sns:us-east-1:199539587591:komoot',
                'Subject' => 'PHP Test',
            ]);
            dump($result);

        } catch (\Exception $e) {
            dd($e);
        }
    }
}


//$result = $client->publish([
//    'Message' => '<string>', // REQUIRED
//    'MessageAttributes' => [
//        '<String>' => [
//                'BinaryValue' => <string || resource || Psr\Http\Message\StreamInterface>,
//            'DataType' => '<string>', // REQUIRED
//            'StringValue' => '<string>',
//        ],
//    // ...
//],
//    'MessageStructure' => '<string>',
//    'PhoneNumber' => '<string>',
//    'Subject' => '<string>',
//    'TargetArn' => '<string>',
//    'TopicArn' => '<string>',
//]);
