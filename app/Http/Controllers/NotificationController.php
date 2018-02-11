<?php

namespace App\Http\Controllers;

use App\Jobs\SendMailDigest;
use App\MailDigest;
use App\Record;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class NotificationController extends Controller
{
    protected function index()
    {

        // For test only
        $digestsByUser = MailDigest::build();
        foreach ($digestsByUser as $email => $digest) {
            dispatch(new SendMailDigest($email, $digest));
        }
    }

    /**
     * HTTPS Endpoint for SNS sub / pub.
     *
     * @param Request $request
     *
     * @return null
     */
    protected function store(Request $request)
    {
        $header = $request->headers->get('X-Amz-Sns-Message-Type');
        $json = $request->getContent();
        $data = json_decode($json, true);

        if ($header == 'SubscriptionConfirmation') {
            $subscribeUrl = $data['SubscribeURL'];

            return Redirect::away($subscribeUrl);
        } elseif ($header == 'Notification') {
            Log::info('Notification');
            $arrRecord = json_decode($data['Message']); //
            $record = new Record();
            foreach ($arrRecord as $key => $value) {
                $record->$key = $value;
            }
            $record->save();
        }
//        $dynamoDB = App::make('aws')->createClient('DynamoDb');
    }
}
