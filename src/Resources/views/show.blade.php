@extends('layouts.admin')
@section('content')
    <article class="content forms-page">
        <div class="title-block">
            <h3 class="title"> SubscriptionPackages </h3>
            <p class="title-description"></p>
        </div>
        <div class="subtitle-block">
            <h3 class="subtitle">  </h3>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-block sameheight-item">
                        <div class="card-header">SubscriptionPackage {{ $subscription_package->id }}</div>
                        <div class="card-body">
                            <a href="{{ url()->previous() }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                            <br/>
                            <br/>

                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                    <tr>
                                        <th>ID</th><td>{{ $subscription_package->id }}</td>
                                    </tr>
                                    <tr><th> {{ trans('StripeIntegration::subscriptionPackages.name') }} </th><td> {{ $subscription_package->name }} </td></tr>
                                    <tr><th> {{ trans('StripeIntegration::subscriptionPackages.display_name') }} </th><td> {{ $subscription_package->display_name }} </td></tr>
                                    <tr><th> {{ trans('StripeIntegration::subscriptionPackages.stripe_product') }} </th><td> {{ $subscription_package->stripe_product }} </td></tr>
                                    <tr><th> {{ trans('StripeIntegration::subscriptionPackages.stripe_pricing_plan') }} </th><td> {{ $subscription_package->stripe_pricing_plan }} </td></tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="card-footer flex-row-reverse">
                                <a href="{{ url(config('stripe_integration.web_route_url_prefix').'/subscription-packages') }}" title="Close"><button class="btn btn-outline-secondary btn-sm"><i class="fa fa-door-closed" aria-hidden="true"></i> Close</button></a>
                                <a href="{{ route('admin.subscription-packages.edit',$subscription_package->id) }}" title="Back"><button class="btn btn-secondary btn-sm"><i class="fa fa-edit" aria-hidden="true"></i>Edit</button></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </article>
@endsection
