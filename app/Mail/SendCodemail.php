<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendCodemail extends Mailable
{
    use Queueable, SerializesModels;
    public $userinfo;


    /**
     * Create a new message instance.
     *
     * @return void
     */

    public function __construct($userinfo)
    {
        $this->userinfo = $userinfo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->view('view.name');
        return $this->subject('Verification de conexion')
        ->view('mail.codeEmail');
    }
}