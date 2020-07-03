@extends('layouts.admin')
@section('content')
    <article class="content responsive-tables-page">
        <div class="title-block">
            <h1 class="title">
                SubscriptionPackage List
            </h1>
            <p class="title-description">  </p>
        </div>
        <section class="section">

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div id="stripe-error-message"></div>


                            @if ($errors->any())
                                <ul class="alert alert-danger">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            @endif

                            <br/>
                            <br/>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover datatable">
                                    <thead>
                                    <tr>
                                        <th></th><th>#</th><th>{{ trans('StripeIntegration::subscriptionPackages.name') }}</th><th>Plan</th><th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($subscription_packages as $item)
                                        <tr data-entry-id="{{ $item->package_id }}">
                                            <td></td>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->display_name }}</td><td>{{ $item->plan_nickname }}</td>
                                            <td>
                                                <a href="{{ url('/admin/subscription-packages/' . $item->package_id ) }}" title="Specifications"><button class="btn btn-outline-primary">View Specifications</button></a>
                                                @if(!auth()->user()->subscribed($item->name))
                                                    <a href="{{ url('/admin/subscription-packages/' . $item->package_id . '/subscribe') }}" title="Subscribe"><button class="btn btn-primary btn-sm"><i class="fa fa-money" aria-hidden="true"></i> @if(!is_null($item->pricing_interval))Subscribe @else Pay @endif</button></a>
                                                @elseif(!auth()->user()->subscribedToPlan($item->stripe_plan, $item->name))
                                                    <a href="{{ url('/admin/subscription-packages/' . $item->package_id . '/change-plan') }}" title="Change Plan"><button class="btn btn-primary btn-sm"><i class="fa fa-spin" aria-hidden="true"></i> Change Plan</button></a>
                                                @elseif(auth()->user()->subscription($item->name)->onGracePeriod())
                                                    <a href="{{ url('/admin/subscription-packages/' . $item->package_id . '/resume-subscription') }}" title="Resume Subscription"><button class="btn btn-primary btn-sm"><i class="fa fa-money" aria-hidden="true"></i> Resume Subscription</button></a>
                                                    <a href="{{ url('/admin/subscription-packages/' . $item->package_id . '/unsubscribe-now') }}" title="Cancel Immediately"><button class="btn btn-danger btn-sm"><i class="fa fa-money" aria-hidden="true"></i> Cancel Immediately</button></a>
                                                @else
                                                    <a href="{{ url('/admin/subscription-packages/subscriptions' ) }}" title="Subsciptions"><button class="btn btn-outline-primary">View {{auth()->user()->subscription($item->name)->quantity}} active subscription(s)</button></a>
                                                    @if(auth()->user()->subscription($item->name)->quantity>1) <a href="{{ url('/admin/subscription-packages/' . $item->package_id . '/decrement-quanity') }}" title="Decrement Quanity"><button class="btn btn-warning btn-sm"><i class="fa fa-money" aria-hidden="true"></i> Reduce Subscription Quantity</button></a> @endif
                                                    <a href="{{ url('/admin/subscription-packages/' . $item->package_id . '/unsubscribe') }}" title="Unsubscribe"><button class="btn btn-warning btn-sm"><i class="fa fa-money" aria-hidden="true"></i> Unsubscribe</button></a>
                                                    <a href="{{ url('/admin/subscription-packages/' . $item->package_id . '/subscribe') }}" title="Subscribe"><button class="btn btn-primary btn-sm"><i class="fa fa-money" aria-hidden="true"></i> Make another Subscription</button></a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="pagination-wrapper"> {!! $subscription_packages->appends(['search' => Request::get('search')])->render() !!} </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </article>
@endsection

@section('scripts')
    @parent
    <script>
        $(function () {
            if($.fn.dataTable != undefined) {

                $('.datatable:not(.ajaxTable)').DataTable({
                    searching: false,
                    paging: false,
                    info: false
                })
            }
        })

    </script>
@endsection
