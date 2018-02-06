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

    protected function store(Request $request)
    {
        $hdr = $request->headers->get('X-Amz-Sns-Message-Type');
        $json = $request->getContent();
        $data = json_decode($json, TRUE);
        if ($hdr == 'SubscriptionConfirmation') {
            return Redirect::to($data['SubscribeURL']);
        }elseif ($hdr == 'Notification'){
            Log::info('Notification');
        }
        return null;
    }
}
