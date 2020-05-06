<?php
/**
 * Created by PhpStorm.
 * User: vash
 * Date: 2/05/20
 * Time: 10:58 PM
 */
namespace Vsynch\StripeIntegration\Facades;

use Illuminate\Support\Facades\Facade;

class StripeIntegration extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'stripeIntegration';
    }
}