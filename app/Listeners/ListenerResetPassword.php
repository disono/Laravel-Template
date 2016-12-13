<?php

namespace App\Listeners;

use App\Events\EventResetPassword;
use Illuminate\Support\Facades\Mail;

class ListenerResetPassword
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  EventResetPassword $event
     * @return void
     */
    public function handle(EventResetPassword $event)
    {
        $user = $event->data['user'];

        try {
            // send email for password reset
            Mail::send('auth.emails.password_update', ['user' => $user], function ($m) use ($user) {
                $m->from(env('MAIL_FROM_ADDRESS'), 'Password reset from ' . app_header('title'));
                $m->to($user->sent_to, $user->first_name . ' ' . $user->last_name)->subject('Password reset confirmation!');
            });
        } catch (\Swift_SwiftException $e) {
            // error sending email
            error_logger($e->getMessage());
        }
    }
}
