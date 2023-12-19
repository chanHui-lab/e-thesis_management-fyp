<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\SubmissionPost; // Adjust the namespace

class DeadlineNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $submission;

    public function __construct(SubmissionPost $submission)
    {
        $this->submission = $submission;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }


    /**
     * Get the mail representation of the notification.
     */
    // public function toMail(object $notifiable): MailMessage
    // {
    //     // return (new MailMessage)
    //     //             ->line('The introduction to the notification.')
    //     //             ->action('Notification Action', url('/'))
    //     //             ->line('Thank you for using our application!');
    // }

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

    public function toMail($notifiable)
    {
        // You can leave it empty or return a MailMessage instance
        return (new MailMessage)
            ->line('The introduction to the notification.');
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'deadline_reminder',
            'message' => 'The submission deadline for "'.$this->submission->title.'" is approaching!',
            // 'link' => url('/student/form/submission/create/' . $this->submission->id), // Assuming submission post ID is in the variable $this->submission->id
            'link' => url('/student/form/submission/'), // Assuming submission post ID is in the variable $this->submission->id
        ];
    }
}
