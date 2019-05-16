<?php
/**
 * @author          Archie Disono (webmonsph@gmail.com)
 * @link            https://github.com/disono/Laravel-Template
 * @copyright       Webmons Development Studio. (https://webmons.com), 2016-2019
 * @license         Apache, 2.0 https://github.com/disono/Laravel-Template/blob/master/LICENSE
 */

namespace App\Notifications;

use App\Models\Verification;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegisterNotification extends Notification
{
    use Queueable;
    private $user;

    /**
     * Create a new notification instance.
     *
     * @param $user
     */
    public function __construct($user)
    {
        // get user
        $this->user = $user;

        // url
        $token = isset($user->verification_code) ? $user->verification_code : str_random(64);
        $this->user->link = url('verify/email?token=' . $token . '&email=' . $this->user->email);

        // do we need to renew the verification code
        $renew = true;
        if (isset($user->renew_code)) {
            $renew = $user->renew_code;
        }

        if ($renew) {
            // clean all verification before saving new
            Verification::where('value', $this->user->email)->where('type', 'email')->delete();

            // create token
            Verification::create([
                'user_id' => $this->user->id,
                'token' => $token,
                'value' => $this->user->email,
                'type' => 'email',
                'expired_at' => expiredAt(1440)
            ]);
        }
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->greeting('Hello ' . $this->user->first_name . '!')
            ->line('This is your registration link to verify your email.')
            ->action('Click to verify', $this->user->link)
            ->line('Thank you for using our application!')
            ->subject('Verify Your New Account')
            ->view('vendor.mail.layout');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
