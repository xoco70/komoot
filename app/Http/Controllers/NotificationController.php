<?php

namespace App\Http\Controllers;

use App\Jobs\MakeHouryDigest;
use App\Jobs\PublishMessage;
use App\Record;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class NotificationController extends Controller
{
    protected function index()
    {
        MakeHouryDigest::dispatch();
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
        return null;
    }
}
