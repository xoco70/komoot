<?php

namespace App\Http\Controllers;

use App\Jobs\PublishMessage;

class MailController extends Controller
{
    protected function index()
    {
        PublishMessage::dispatch('arn:aws:sns:us-east-1:199539587591:komoot');

    }
}
