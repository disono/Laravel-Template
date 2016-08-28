<?php

namespace App\Listeners;

use App\Events\EventSignUp;
use App\Models\EmailVerification;
use Illuminate\Support\Facades\Mail;

class ListenerSignUp
{
    /**
     * Create the event listener.
     *
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  EventSignUp $event
     * @return void
     */
    public function handle(EventSignUp $event)
    {
        $data = $event->data;

        // get user
        $user = $data['user'];

        // url
        $token = rand_token();
        $user->link = url('email/verify?token=' . $token . '&email=' . $user->email);

        // clean all verification before saving new
        EmailVerification::where('email', $user->email)->delete();

        // create token
        EmailVerification::create([
            'token' => $token,
            'email' => $user->email,
            'expired_at' => expired_at(1440)
        ]);

        try {
            // send email for verification of registration
            Mail::send('auth.emails.register', ['content' => $user], function ($m) use ($user) {
                $m->from(env('MAIL_NAME'), 'Confirm your registration at ' . app_header('title'));
                $m->to($user->email, $user->first_name . ' ' . $user->last_name)->subject('Verify your Registration!');
            });
        } catch (\Swift_SwiftException $e) {
            // error sending email
            error_logger($e->getMessage());
        }
    }
}
