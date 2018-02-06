<?php

namespace App\Http\Controllers;

use App\Jobs\PublishMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MailController extends Controller
{
    protected function index()
    {
        PublishMessage::dispatch();
    }

    protected function store(Request $request)
    {
        $hdr = $request->headers->get('X-Amz-Sns-Message-Type');
        $json = $request->getContent();
        $data = json_decode($json, TRUE);
        $token = $data['Token'];

        if ($hdr == 'SubscriptionConfirmation' && array_key_exists('subscribeURL', $data)) {
            $subscribeURL = $data['SubscribeURL'];
        }
        Log::info($token);
        Log::info($subscribeURL);



    }
}
