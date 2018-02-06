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
    protected $arn;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($arn)
    {
        $this->arn = $arn;
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
                'TopicArn' => $this->arn,
                'Subject' => 'PHP Test',
            ]);
        dump($result);
        } catch (\Exception $e) {
            dd($e);
        }
    }
}
