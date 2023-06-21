<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOrder extends Notification implements ShouldQueue
{
    use Queueable;

    protected $result;

    /**
     * Create a new notification instance.
     */
    public function __construct($result)
    {
        $this->result = $result;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $newMail = (new MailMessage)->subject('New Order');

        $newMail->greeting('Order data Client: '.$this->result["name"]);

        foreach ($this->result["order"] as $item) {
            $newMail->line('Item: '.$item["product"]["name"].' value '
            .number_format($item["product"]["price"], 2)
        );

        }

        $newMail->line('Total: '.$this->result["total"]);

        $newMail->line('Thank you for using our service!');

        return $newMail;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
