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

        $url = route('password.reset', [
            'token' => $this->user->token,
            'email' => $this->user->email,
        ]);

        return $this
            ->subject('Bem vindo!')
			->markdown('vendor.emails.welcome', ['url' => $url,'name' => $first_name]);
    }
}