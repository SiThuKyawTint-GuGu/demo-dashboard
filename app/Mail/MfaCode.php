<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MfaCode extends Mailable
{
    use Queueable, SerializesModels;

    public $mfaCode;

    /**
     * Create a new message instance.
     *
     * @param string $mfaCode
     * @return void
     */
    public function __construct($mfaCode)
    {
        $this->mfaCode = $mfaCode;
    }

    /**
     * Build the message.
     *
     * @return \Illuminate\Mail\Mailable
     */
    public function build()
    {
        return $this->subject('Your MFA Code')
            ->view('auth.emails.mfa-code');
    }
}
