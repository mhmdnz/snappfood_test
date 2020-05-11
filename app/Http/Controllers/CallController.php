<?php

namespace App\Http\Controllers;

use App\Helper\AnswerLogHelper;
use App\Helper\EndCallHelper;
use App\Jobs\FindFirstEmployeeJob;
use App\Models\AnswerLog;
use App\Models\Employee;
use App\Models\IncomingCall;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CallController extends Controller
{
    public function start(Request $request)
    {
        $request->validate([
            'type' => 'required|in:immediate,normal'
        ]);

        $call_id = uniqid();
        $incomingCall = new IncomingCall();
        $incomingCall->type = $request->type;
        $incomingCall->call_id = $call_id;
        if ($incomingCall->save()) {
            $answered_employee = $incomingCall->type == 'immediate' ? $this->handleImmediateCalls($incomingCall):'-';
            return response(['call_id' => $call_id, 'answer_employee' => $answered_employee]);
        }

        return response(['message' => 'error'], 500);
    }

    public function end(Request $request)
    {
        $request->validate([
            'call_id' => 'required|exists:incoming_calls,call_id'
        ]);
        $incomingCall = IncomingCall::getByCallId($request->call_id);
        if ($incomingCall->answerLog->end_call_time) {
            return response(['message' => 'This Call Is Ended Already']);
        }
        if (EndCallHelper::endCall($incomingCall))
            return response(['Call Ended']);

        return response(['message' => 'Something Is Wrong'], 500);
    }

    public function answer(Employee $employee)
    {
        if ($employee->state != Employee::WAITED) {
            return response(['message' => 'You Cannot Answer New Phone Right Now'], 403);
        } elseif (!$incoming_call = IncomingCall::getFirstNormal()) {
            return response(['message' => "There Isn't Any Normal Call"]);
        }

        if ($this->handleNormalCalls($employee, $incoming_call))
            return response(['incoming_call' => $incoming_call]);
    }

    private function handleImmediateCalls(IncomingCall $incoming_call) : string
    {
        $waited_employee = Employee::getFirstWaited();
        $result = '-';
        if ($waited_employee) {
            $result = AnswerLogHelper::save($incoming_call, $waited_employee) == true ? $waited_employee->name: '-';
        } else {
            FindFirstEmployeeJob::dispatch($incoming_call);
        }

        return $result;
    }

    private function handleNormalCalls(Employee $employee, $normal_call)
    {
        return AnswerLogHelper::save($normal_call, $employee) == true ? $employee->name: '-';
    }
}
