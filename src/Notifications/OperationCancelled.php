<?php

namespace Seat\Mettrick\Calendar\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\SlackMessage;
use Seat\Mettrick\Calendar\Helpers\Helper;

/**
 * Class OperationCancelled.
 *
 * @package Seat\Mettrick\Calendar\Notifications
 */
class OperationCancelled extends Notification
{
    use Queueable;

    /**
     * @param $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    /**
     * @param $notifiable
     * @return mixed
     */
    public function toSlack($notifiable)
    {
        $attachment = Helper::BuildSlackNotificationAttachment($notifiable);

        return (new SlackMessage)
            ->error()
            ->from('SeAT Calendar', ':calendar:')
            ->content(trans('calendar::seat.notification_cancel_operation'))
            ->attachment($attachment);
    }
}
