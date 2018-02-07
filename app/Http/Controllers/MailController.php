<?php

namespace App\Http\Controllers;

use App\Jobs\PublishMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class MailController extends Controller
{
    protected function index()
    {
        PublishMessage::dispatch();
    }

    /**
     * HTTPS Endpoint for SNS sub / pub
     * @param Request $request
     * @return null
     */
    protected function store(Request $request)
    {
        $header = $request->headers->get('X-Amz-Sns-Message-Type');
        $json = $request->getContent();
        $data = json_decode($json, TRUE);
        if ($header == 'SubscriptionConfirmation') {
            $subscribeUrl = $data['SubscribeURL'];
            Log::info($subscribeUrl);
            return Redirect::away($subscribeUrl);
        }elseif ($header == 'Notification'){
            Log::info('Notification');
//            $message = $data['Message'];
//            $message = json_decode($message);
//            $timestamp = $data['Timestamp'];
        }
        return null;
    }
}
