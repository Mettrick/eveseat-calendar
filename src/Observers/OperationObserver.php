<?php

namespace Seat\Mettrick\Calendar\Observers;

use Illuminate\Support\Facades\Notification;
use Seat\Mettrick\Calendar\Models\Operation;
use Seat\Mettrick\Calendar\Notifications\OperationPosted;
use Seat\Mettrick\Calendar\Notifications\OperationUpdated;
use Seat\Mettrick\Calendar\Notifications\OperationCancelled;
use Seat\Mettrick\Calendar\Notifications\OperationActivated;

/**
 * Class OperationObserver.
 *
 * @package Seat\Mettrick\Calendar\Observers
 */
class OperationObserver
{
    /**
     * @param \Seat\Mettrick\Calendar\Models\Operation $operation
     */
    public function created(Operation $operation)
    {
        if (setting('mettrick.calendar.slack_integration', true) == 1 && !is_null($operation->integration))
            Notification::send($operation, new OperationPosted());
    }

    /**
     * @param \Seat\Mettrick\Calendar\Models\Operation $new_operation
     */
    public function updating(Operation $new_operation)
    {
        if (setting('mettrick.calendar.slack_integration', true) == 1 && !is_null($new_operation->integration)) {
            $old_operation = Operation::find($new_operation->id);
            if ($old_operation->is_cancelled != $new_operation->is_cancelled) {
                if ($new_operation->is_cancelled == true)
                    Notification::send($new_operation, new OperationCancelled());
                else
                    Notification::send($new_operation, new OperationActivated());
            }
            else {
                if ($old_operation->end_at == null && $new_operation->end_at != null) {
                }
                else {
                    Notification::send($new_operation, new OperationUpdated());
                }
            }
        }
    }
}
