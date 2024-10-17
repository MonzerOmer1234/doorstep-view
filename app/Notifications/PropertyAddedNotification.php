<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PropertyAddedNotification extends Notification
{
    use Queueable;

    public $property;

    /**
     * Create a new notification instance.
     */
    public function __construct($property)
    {
        $this->property = $property;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('A new property has been added that matches your preferences.')
                    ->action('View Property', url('/properties/' . $this->property->id))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification for database.
     */
    public function toArray($notifiable)
    {
        return [
            'property_id' => $this->property->id,
            'property_title' => $this->property->title,
            'property_price' => $this->property->price,
        ];
    }
}
