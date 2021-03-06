<?php

namespace Vsynch\StripeIntegration\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionPackageStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
             'name' => 'required|max:50',
             'stripe_product' => 'required',
             'stripe_pricing_plan' => 'required',
             'price_per_unit' => 'required',
             'billing_interval' => 'required',
         ];
    }
}
