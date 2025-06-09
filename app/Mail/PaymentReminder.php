<?php

namespace App\Mail;

use App\Models\Customer;
use App\Models\Shop;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentReminder extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The shop instance.
     *
     * @var \App\Models\Shop
     */
    public $shop;

    /**
     * The customer instance.
     *
     * @var \App\Models\Customer
     */
    public $customer;

    /**
     * The pending sales.
     *
     * @var \Illuminate\Database\Eloquent\Collection
     */
    public $pendingSales;

    /**
     * The total due amount.
     *
     * @var float
     */
    public $totalDue;

    /**
     * Create a new message instance.
     */
    public function __construct(Shop $shop, Customer $customer, $pendingSales, $totalDue)
    {
        $this->shop = $shop;
        $this->customer = $customer;
        $this->pendingSales = $pendingSales;
        $this->totalDue = $totalDue;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Payment Reminder - ' . $this->shop->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-reminder',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}