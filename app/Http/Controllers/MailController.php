<?php

namespace App\Http\Controllers;


use App\Jobs\PublishMessage;
use Faker\Factory;
use Illuminate\Support\Facades\App;

class MailController extends Controller
{
    protected function index()
    {
        PublishMessage::dispatch();

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
