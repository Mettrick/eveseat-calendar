<?php

namespace Seat\Mettrick\Calendar\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\SlackMessage;
use Seat\Mettrick\Calendar\Helpers\Helper;

/**
 * Class OperationUpdated.
 *
 * @package Seat\Mettrick\Calendar\Notifications
 */
class OperationUpdated extends Notification
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
            ->warning()
            ->from('SeAT Calendar', ':calendar:')
            ->content(trans('calendar::seat.notification_edit_operation'))
            ->attachment($attachment);
    }
}
