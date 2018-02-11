<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class MailDigest extends Model
{
    /**
     * Build Mail messages ready to be sent.
     *
     * @return array
     */
    public static function build()
    {
        $hourlyTimestamp = Cache::get('schedule_timestamp', Carbon::now());
        $recordsByUsers = Record::where('timestamp', '>', $hourlyTimestamp->subHours(1))
            ->orderby('timestamp', 'desc')
            ->get()
            ->groupBy('email');
        $digest = [];
        foreach ($recordsByUsers as $recordsByUser) {
            $email = $recordsByUser->get(0)->email;
            $digest[$email] = self::buildMessage($recordsByUser);
        }

        return $digest;
    }

    /**
     * Build Mail message with title, and a list of events.
     *
     * @param $recordsByUser
     *
     * @return string
     */
    protected static function buildMessage($recordsByUser)
    {
        $name = $recordsByUser->get(0)->name;
        $body = 'Hi'.' '.$name.', your friends are active<br/><br/>';
        $body .= $recordsByUser->map(function ($record) use ($body) {
            return Carbon::parse($record->timestamp)->format('l H:i').' '.$record->message.'<br/>';
        })->implode('', '<br/>');

        return $body;
    }
}
