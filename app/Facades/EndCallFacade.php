<?php


namespace App\Facades;


use App\Models\AnswerLog;
use App\Models\Employee;
use App\Models\IncomingCall;
use Illuminate\Support\Facades\DB;

class EndCallFacade
{
    public static function endCall(IncomingCall $incomingCall)
    {
        $answerLog = $incomingCall->answerLog()->with('employee')->first();
        if ($answerLog) {
            $employee = $answerLog->employee;
            $result = DB::transaction(function () use ($employee, $incomingCall, $answerLog) {
                /**
                 * @var AnswerLog $answerLog
                 */
                $answerLog->updateEndCallTime();
                /**
                 * @var Employee $employee
                 */
                return $employee->updateState(Employee::WAITED);
            });

        } else {
            $result = $incomingCall->delete();
        }

        return $result;
    }
}
