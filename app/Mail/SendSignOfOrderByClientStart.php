<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendSignOfOrderByClientStart extends Mailable
{
    use Queueable, SerializesModels;

    public $id;
    public $title;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($id, $title)
    {
        $this->id = $id;
        $this->title = $title;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('info@gravescare.com')
            ->subject($this->title)
            ->view('emails.SendSignOfOrderByClientStart');
    }
}
