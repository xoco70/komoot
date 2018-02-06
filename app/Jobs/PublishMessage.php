<?php

namespace App\Jobs;

use Faker\Factory;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\App;

class PublishMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $sns = App::make('aws')->createClient('sns');
        $faker = Factory::create();
        $rdmSentence = $faker->sentence();
        try {
            $result = $sns->publish([
                'Message' => $rdmSentence,
                'TopicArn' => 'arn:aws:sns:us-east-1:199539587591:komoot',
                'Subject' => 'PHP Test',
            ]);

        } catch (\Exception $e) {
            dd($e);
        }
    }
}
