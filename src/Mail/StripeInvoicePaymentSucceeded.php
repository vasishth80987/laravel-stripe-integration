<?php
/**
 * Created by PhpStorm.
 * User: vash
 * Date: 9/05/20
 * Time: 7:10 AM
 */

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Laravel\Cashier\Subscription;

class StripeInvoicePaymentSucceeded extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user,Subscription $subscription)
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('vendor.vsynch.stripe-integration.mail.invoice_payment_succeeded');
    }
}
