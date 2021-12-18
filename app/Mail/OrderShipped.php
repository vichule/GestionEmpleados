<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderShipped extends Mailable
{
    use Queueable, SerializesModels;

    public $asunto;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($asunto,$titulo,$datos)
    {
        //
        $this->asunto = $asunto;
        $this->titulo = $titulo;
        $this->datos = $datos;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //return $this->subject($this->asunto)->view('password');
    }
}
