<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendMailDigest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email, $digest;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $digest)
    {
        $this->email = $email;
        $this->digest = $digest;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::send('emails.digest', ['mailBody' => $this->digest], function ($m) {
            $m->from('julien@cappiello.fr', 'Julien Cappiello');
            $m->to($this->email)->subject('Your Friends have been active!');
        });

    }
}
