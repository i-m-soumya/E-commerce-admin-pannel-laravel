<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GeneralLinkEmail extends Mailable
{
    use Queueable, SerializesModels;
    protected $USER_EMAIL;
    protected $USER_NAME;
    protected $MAIL_SUBJECT;
    protected $MAIL_BODY;
    protected $MAIL_LINK;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($USER_EMAIL,$USER_NAME,$MAIL_SUBJECT,$MAIL_BODY,$MAIL_LINK)
    {
        $this->USER_EMAIL = $USER_EMAIL;
        $this->USER_NAME = $USER_NAME;
        $this->MAIL_SUBJECT = $MAIL_SUBJECT;
        $this->MAIL_BODY = $MAIL_BODY;
        $this->MAIL_LINK = $MAIL_LINK;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.email_link_template')
                    ->with([
                        'USER_NAME' => $this->USER_NAME,
                        'MAIL_BODY' => $this->MAIL_BODY,
                        'MAIL_LINK' => $this->MAIL_LINK,
                        'MAIL_SUBJECT' => $this->MAIL_SUBJECT,
                    ])
                    ->from('support@grocerbee.co.in')
                    ->to($this->USER_EMAIL)
                    ->subject($this->MAIL_SUBJECT);
    }
}
