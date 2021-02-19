<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\User;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;
    public $user;

    /**
    * Create a new message instance.
    
    * @return void
    */
    public function __construct(User $user) {
        $this->user = $user;
    }

        /**
    * Build the message.
    
    * @return $this
    */
    public function build()
    {
        $first_name = explode(' ', $this->user->name)[0];

        $token = app('auth.password.broker')->createToken($this->user);

        $url = route('password.reset', [
            'token' => $token,
            'email' => $this->user->email,
        ]);

        return $this
            ->subject('Bem vindo ao Trelássio!')
			->markdown('vendor.emails.welcome', ['url' => $url,'name' => $first_name]);
    }
}
