<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendQuestion extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $question;
    public $title;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($question, $user, $title)
    {
        $this->question = $question;
        $this->user = $user;
        $this->title = $title;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('refollower@yandex.ru')
            ->subject($this->title)
            ->view('emails.sendquestions');
        // ->with([
        //     'question' => $this->question,
        //     'user' => $this->user,
        //     'title' => $this->title
        // ]);
    }
}
