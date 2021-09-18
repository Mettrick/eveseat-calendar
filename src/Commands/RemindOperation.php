<?php

namespace Seat\Mettrick\Calendar\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon;
use Seat\Mettrick\Calendar\Models\Operation;
use Seat\Mettrick\Calendar\Notifications\OperationPinged;

/**
 * Class RemindOperation.
 *
 * @package Seat\Mettrick\Calendar\Commands
 */
class RemindOperation extends Command
{
    /**
     * @var string
     */
    protected $signature = 'calendar:remind';

    /**
     * @var string
     */
    protected $description = 'Check for operation to be reminded on Slack';

    /**
     * @var array
     */
    private $marks = [60];

    /**
     * Process the command.
     */
    public function handle()
    {
        if (setting('mettrick.calendar.slack_integration', true) == 1) {
            $ops = Operation::all()->take(-50);
            $now = Carbon::now('UTC');

            foreach($ops as $op)
            {
                if ($op->status == 'incoming' && in_array($now->diffInMinutes($op->start_at, false), $this->marks))
                    Notification::send($op, new OperationPinged());
            }
        }
    }
}
