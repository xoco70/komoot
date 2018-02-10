<?php

namespace App\Jobs;

use App\MailDigest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendMailDigest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Send several email with email digest
     *
     * @return void
     */
    public function handle()
    {
        $digestsByUser = MailDigest::build();
        foreach ($digestsByUser as $email => $digest) {
            Mail::send('emails.digest', ['mailBody' => $digest], function ($m) use ($email, $digest) {
                $m->from('julien@cappiello.fr', 'Julien Cappiello');
                $m->to($email)->subject('Your Friends have been active!');
            });
        }
    }
}
