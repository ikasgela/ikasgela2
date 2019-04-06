<?php

namespace App\Mail;

use App\Tarea;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FeedbackRecibido extends Mailable
{
    use Queueable, SerializesModels;

    public $tarea;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Tarea $tarea)
    {
        $this->tarea = $tarea;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Feedback recibido')
            ->markdown('emails.feedback_recibido');
    }
}
