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
        Log::info($request->toArray());
    }
}
