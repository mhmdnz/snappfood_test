<?php


namespace App\Facades;


use App\Models\AnswerLog;
use App\Models\Employee;
use App\Models\IncomingCall;
use Illuminate\Support\Facades\DB;

class AnswerLogHelper
{
    public function save(IncomingCall $incomingCall, Employee $employee)
    {
        $result = DB::transaction(function () use ($employee, $incomingCall) {
            resolve(AnswerLog::class)->make($employee, $incomingCall);
            $employee->updateState($employee::ONCALL);
            return $incomingCall->updateIsTaken(true);
        });

        return $result;
    }
}
