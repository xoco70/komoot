<?php

namespace App\Http\Controllers;

use App\Jobs\PublishMessage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class MailController extends Controller
{
    protected function index()
    {
        PublishMessage::dispatch();
    }

    protected function store(Request $request)
    {
        $hdr = $request->headers->get('X-Amz-Sns-Message-Type');
        Log::info($request->data);
        Log::info($hdr);
    }
}
