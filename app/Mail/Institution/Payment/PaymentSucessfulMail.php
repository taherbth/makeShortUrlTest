<?php

namespace App\Mail\Institution\Payment;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentSucessfulMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $invoice;
    public function __construct($invoice)
    {
        $this->invoice= $invoice;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Successful transaction')
            ->markdown('emails.institution.payment.payment_sucessful_invoice_mail')
            ->with([
            'invoice'=>$this->invoice,
        ]);
    }
}
