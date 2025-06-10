<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\User;

class SubscriptionStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    protected $status;
    protected $requestingUser;

    /**
     * Create a new notification instance.
     *
     * @param string $status
     * @param User|null $requestingUser For admin notifications, the user who requested/cancelled
     * @return void
     */
    public function __construct($status, $requestingUser = null)
    {
        $this->status = $status;
        $this->requestingUser = $requestingUser;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $mailMessage = new MailMessage;
        $mailMessage->subject('Subscription Status Update');

        switch ($this->status) {
            case 'approved':
                $mailMessage->line('Good news! Your subscription has been approved.')
                    ->line('You now have full access to all features of the application.')
                    ->action('Access Your Account', url('/dashboard'));
                break;
            case 'rejected':
                $mailMessage->line('Your subscription request has been reviewed.')
                    ->line('Unfortunately, your subscription request was not approved at this time.')
                    ->line('You can contact support for more information or submit a new request.')
                    ->action('Contact Support', url('/contact'));
                break;
            case 'cancelled':
                $mailMessage->line('Your subscription has been cancelled as requested.')
                    ->line('You can resubscribe at any time from your account settings.')
                    ->action('Resubscribe', url('/subscription/request'));
                break;
            case 'requested':
                // This is for admin notifications
                if ($this->requestingUser) {
                    $mailMessage->subject('New Subscription Request')
                        ->line('A new subscription request has been submitted.')
                        ->line('User: ' . $this->requestingUser->name)
                        ->line('Email: ' . $this->requestingUser->email)
                        ->action('Review Pending Requests', url('/admin/subscriptions/pending'));
                }
                break;
            case 'user_cancelled':
                // This is for admin notifications when a user cancels
                if ($this->requestingUser) {
                    $mailMessage->subject('Subscription Cancellation')
                        ->line('A user has cancelled their subscription.')
                        ->line('User: ' . $this->requestingUser->name)
                        ->line('Email: ' . $this->requestingUser->email)
                        ->action('View Subscription Management', url('/admin/subscriptions'));
                }
                break;
            default:
                $mailMessage->line('There has been an update to your subscription status.')
                    ->line('Please log in to your account to view your current subscription status.')
                    ->action('View Status', url('/subscription/status'));
        }

        return $mailMessage->line('Thank you for using our application!');
    }
}
