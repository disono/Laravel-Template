<?php

namespace App\Notifications;

use App\Models\EmailVerification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegisterNotification extends Notification
{
    use Queueable;
    private $user;

    /**
     * Create a new notification instance.
     * @param $data
     */
    public function __construct($data)
    {
        // get user
        $this->user = $data;

        // url
        $token = rand_token();
        $this->user->link = url('email/verify?token=' . $token . '&email=' . $this->user->email);

        // clean all verification before saving new
        EmailVerification::where('email', $this->user->email)->delete();

        // create token
        EmailVerification::create([
            'token' => $token,
            'email' => $this->user->email,
            'expired_at' => expired_at(1440)
        ]);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->greeting('Hello ' . $this->user->first_name . '!')
            ->line('This is your registration link to verify your email.')
            ->action('Click to verify', $this->user->link)
            ->line('Thank you for using our application!')
            ->subject('Verify Your New Account');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
