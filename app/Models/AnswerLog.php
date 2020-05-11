<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class AnswerLog extends Model
{
    public function make(Employee $employee, IncomingCall $incomingCall)
    {
        $this->employee()->associate($employee);
        $this->incomingCall()->associate($incomingCall);
        return $this->save();
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function incomingCall()
    {
        return $this->belongsTo(IncomingCall::class);
    }

    public function updateEndCallTime()
    {
        $this->end_call_time = Carbon::now();
        return $this->update();
    }
}
