<?php

namespace App\Jobs;

use App\Record;
use Faker\Factory;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class PublishMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $arn;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->arn = env('AWS_SNS_ARN');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $emails = [
            "email1@email.com",
            "email2@email.com",
            "email3@email.com",
        ];

        $sns = App::make('aws')->createClient('sns');
        $faker = Factory::create();
        $record = new Record;
        $record->message = $faker->sentence();
        $record->timestamp = Carbon::now()->toDateTimeString();
        $record->email = $faker->randomElement($emails);
        Log::info($record);
        try {
            $result = $sns->publish([
                'Message' => $record,
                'TopicArn' => $this->arn,
                'Subject' => null,
            ]);
        dump($result);
        } catch (\Exception $e) {
            dd($e);
        }
    }
}
