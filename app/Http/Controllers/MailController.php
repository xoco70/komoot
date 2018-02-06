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
        Log::info($request);
    }
}
