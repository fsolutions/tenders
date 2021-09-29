<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendReaction extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $order;
    public $title;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order, $user, $title)
    {
        $this->order = $order;
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
        return $this->from('info@gravescare.com')
            ->subject($this->title)
            ->view('emails.SendReaction');
        // ->with([
        //     'order' => $this->order,
        //     'user' => $this->user,
        //     'title' => $this->title
        // ]);
    }
}
