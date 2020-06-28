<?php
/**
 * Created by PhpStorm.
 * User: vash
 * Date: 9/05/20
 * Time: 7:18 AM
 */
namespace Vsynch\StripeIntegration\Events;

use App\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Stripe\Event;

class CustomerDeleted
{
    use Dispatchable, SerializesModels;

    public $user;

    public $stripe_event;

    public function __construct(User $user,Event $stripe_event)
    {
        $this->user = $user;
        $this->stripe_event = $stripe_event;
    }
}