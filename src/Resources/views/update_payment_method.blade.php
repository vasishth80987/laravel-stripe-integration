@extends('layouts.admin')
@section('content')

    <article class="content forms-page">
        <div class="title-block">
            <p class="title-description">Payment Methods</p>
        </div>
        <section class="section">
            <div class="row sameheight-container">
                <div class="col-md-6">
                    <div class="card card-block sameheight-item">
                        <div class="card-header">Update Your Card Payment Details</div>
                        <div class="card-body">
                            <div class="title-block">
                                <h3 class="title"> {{is_null($current_card_digits)?'You have no cards saved. Please add your card details to subscribe':'The last 4 digits of your current default card is '.$current_card_digits}} </h3>
                            </div>
                            <div class="form">
                                <div class="form-group">
                                    <label for="name" class="control-label">Card Holder Name</label>
                                    <input id="card-holder-name" type="text" class="form-control underlined" placeholder="Full Name as Displayed on Card">
                                </div>

                                <!-- Stripe Elements Placeholder -->
                                <div class="form-group">
                                    <label for="name" class="control-label">Card Details</label>
                                </div>

                                <div id="card-element" class="form-group"></div><br>

                                <div class="form-group">
                                    <button id="card-button" data-secret="{{ $intent->client_secret }}">
                                        Update Payment Method
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </article>
@endsection
@push('styles')
    <style>
        /**
 * The CSS shown here will not be introduced in the Quickstart guide, but shows
 * how you can use CSS to style your Element's container.
 */
        #card-holder-name::placeholder{
            color: #aab7c4;
        }
        .StripeElement,#card-holder-name {
            box-sizing: border-box;

            height: 40px;

            padding: 10px 12px;

            border: 1px solid #ced4da;
            border-radius: 4px;
            background-color: white;

            box-shadow: 0 1px 3px 0 #e6ebf1;
            -webkit-transition: box-shadow 150ms ease;
            transition: box-shadow 150ms ease;
        }

        .StripeElement--focus {
            box-shadow: 0 1px 3px 0 #cfd7df;
        }

        .StripeElement--invalid {
            border-color: #fa755a;
        }

        .StripeElement--webkit-autofill {
            background-color: #fefde5 !important;
        }
    </style>
@endpush
@push('scripts')
    <!-- Load Stripe.js on your website. -->
    <script src="https://js.stripe.com/v3"></script>
@endpush
@section('scripts')
    @parent
    <script>

        const stripe = Stripe("{{env('STRIPE_KEY')}}");

        var style = {
            base: {
                color: '#32325d',
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: 'antialiased',
                fontSize: '16px',
                '::placeholder': {
                    color: '#aab7c4'
                },
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
            }
        };
        const elements = stripe.elements();
        const cardElement = elements.create('card', {style: style});


        cardElement.mount('#card-element');

        const cardHolderName = document.getElementById('card-holder-name');
        const cardButton = document.getElementById('card-button');
        const clientSecret = cardButton.dataset.secret;

        cardButton.addEventListener('click', async (e) => {
            const { setupIntent, error } = await stripe.confirmCardSetup(
                clientSecret, {
                    payment_method: {
                        card: cardElement,
                        billing_details: { name: cardHolderName.value }
                    }
                }
            );

            if (error) {
                console.log(error.message);
                // Display "error.message" to the user...
            } else {
                // The card has been verified successfully...
                console.log('card is verified: '+setupIntent.payment_method);
                $.ajax({
                    headers: {'x-csrf-token': _token},
                    method: 'POST',
                    url: "{{route(config('stripe_integration.web_route_name_prefix').'stripe.update-payment-method')}}",
                    data: { payment_method: setupIntent.payment_method, _method: 'POST' }})
                    .done(function () { location.replace('{{ url()->previous() }}') })
            }
        });

    </script>
@endsection