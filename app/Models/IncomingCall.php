<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IncomingCall extends Model
{
    use SoftDeletes;
    public function updateIsTaken(bool $state)
    {
        $this->is_taken = $state;
        return $this->update();
    }

    public static function getByCallId($call_id)
    {
        return IncomingCall::where('call_id', $call_id)->first();
    }

    public function answerLog()
    {
        return $this->hasOne(AnswerLog::class);
    }

    public static function getFirstNormal()
    {
        return self::where('type', 'normal')->where('is_taken', 0)->first();
    }
}
