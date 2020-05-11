<?php

namespace App\Jobs;

use Facades\App\Facades\AnswerLogFacade;
use App\Models\Employee;
use App\Models\IncomingCall;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FindFirstEmployeeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $incomingCall;

    /**
     * FindFirstEmployeeJob constructor.
     * @param IncomingCall $incomingCall
     */
    public function __construct(IncomingCall $incomingCall)
    {
        $this->incomingCall = $incomingCall;
    }

    /**
     * @throws \Exception
     */
    public function handle()
    {
        $waited_employee = Employee::getFirstWaited();
        if ($waited_employee)
            AnswerLogFacade::save($this->incomingCall, $waited_employee);

        throw new \Exception('waited for new User');
    }
}
