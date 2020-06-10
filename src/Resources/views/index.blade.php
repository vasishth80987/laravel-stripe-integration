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

                            <form method="GET" action="{{ url(config('stripe_integration.web_route_url_prefix').'/subscription-packages') }}" accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-right" role="search">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search" placeholder="Search..." value="{{ request('search') }}">
                                    <span class="input-group-append">
                                        <button class="btn btn-secondary" type="submit">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </span>
                                </div>
                            </form>

                            <br/>
                            <br/>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover datatable">
                                    <thead>
                                    <tr>
                                        <th></th><th>#</th><th>{{ trans('vendor.vsynch.stripe-integration.subscriptionPackages.name') }}</th><th>{{ trans('vendor.vsynch.stripe-integration.subscriptionPackages.stripe_product') }}</th><th>{{ trans('vendor.vsynch.stripe-integration.subscriptionPackages.stripe_pricing_plan') }}</th><th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($subscription_packages as $item)
                                        <tr data-entry-id="{{ $item->id }}">
                                            <td></td>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->name }}</td><td>{{ $item->stripe_product }}</td><td>{{ $item->stripe_pricing_plan }}</td>
                                            <td>
                                                    <a href="{{ url('/admin/subscription-packages/' . $item->id ) }}" title="Specifications"><button class="btn btn-outline-primary">View Specifications</button></a>
                                                @if(!auth()->user()->subscribed($item->stripe_product))
                                                    <a href="{{ url('/admin/subscription-packages/' . $item->id . '/subscribe') }}" title="Subscribe"><button class="btn btn-primary btn-sm"><i class="fa fa-money" aria-hidden="true"></i> Subscribe</button></a>
                                                @elseif(!auth()->user()->subscribedToPlan($item->stripe_pricing_plan, $item->stripe_product))
                                                    <a href="{{ url('/admin/subscription-packages/' . $item->id . '/change-plan') }}" title="Change Plan"><button class="btn btn-primary btn-sm"><i class="fa fa-spin" aria-hidden="true"></i> Change Plan</button></a>
                                                @elseif(auth()->user()->subscription($item->stripe_product)->onGracePeriod())
                                                    <a href="{{ url('/admin/subscription-packages/' . $item->id . '/resume-subscription') }}" title="Resume Subscription"><button class="btn btn-primary btn-sm"><i class="fa fa-money" aria-hidden="true"></i> Resume Subscription</button></a>
                                                    <a href="{{ url('/admin/subscription-packages/' . $item->id . '/unsubscribe-now') }}" title="Cancel Immediately"><button class="btn btn-danger btn-sm"><i class="fa fa-money" aria-hidden="true"></i> Cancel Immediately</button></a>
                                                @else
                                                    <a href="{{ url('/admin/subscription-packages/' . $item->id . '/subscriptions' ) }}" title="Subsciptions"><button class="btn btn-outline-primary">View {{auth()->user()->subscription($item->stripe_product)->quantity}} active subscription(s)</button></a>
                                                <!--<a href="{{ url('/admin/subscription-packages/' . $item->id . '/unsubscribe') }}" title="Unsubscribe"><button class="btn btn-warning btn-sm"><i class="fa fa-money" aria-hidden="true"></i> Unsubscribe</button></a>-->
                                                    <a href="{{ url('/admin/subscription-packages/' . $item->id . '/subscribe') }}" title="Subscribe"><button class="btn btn-primary btn-sm"><i class="fa fa-money" aria-hidden="true"></i> Make another Subscription</button></a>
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

                let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
                let deleteButton = {
                    text: deleteButtonTrans,
                    url: "{{ route(config('stripe_integration.web_route_name_prefix').'subscription-packages.massDestroy') }}",
                    className: 'btn-danger',
                    action: function (e, dt, node, config) {
                        var ids = $.map(dt.rows({selected: true}).nodes(), function (entry) {
                            return $(entry).data('entry-id')
                        });

                        if (ids.length === 0) {
                            alert('{{ trans('global.datatables.zero_selected') }}')

                            return
                        }

                        if (confirm('{{ trans('global.areYouSure') }}')) {
                            $.ajax({
                                headers: {'x-csrf-token': _token},
                                method: 'POST',
                                url: config.url,
                                data: {ids: ids, _method: 'DELETE'}
                            })
                                .done(function () {
                                    location.reload()
                                })
                        }
                    }
                }
                let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
                dtButtons.push(deleteButton)

                $('.datatable:not(.ajaxTable)').DataTable({
                    buttons: dtButtons,
                    searching: false,
                    paging: false,
                    info: false
                })
            }
        })

    </script>
@endsection
