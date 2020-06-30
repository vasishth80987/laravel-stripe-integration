@extends('layouts.admin')
@section('content')

    <div class="card">
        <div class="card-header">
            {{ trans('global.edit') }} {{ trans('global.product.title_singular') }}
        </div>

        <div class="card-body">
            <form action="{{ route("admin.subscription-packages.update", [$subscription_package->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name', isset($subscription_package) ? $subscription_package->name : '') }}" readonly="readonly">
                    @if($errors->has('name'))
                        <p class="help-block">
                            {{ $errors->first('name') }}
                        </p>
                    @endif
                </div>
                <div class="form-group {{ $errors->has('display_name') ? 'has-error' : '' }}">
                    <label for="name">Name</label>
                    <input type="text" id="display_name" name="display_name" class="form-control" value="{{ old('display_name', isset($subscription_package) ? $subscription_package->display_name : '') }}">
                    @if($errors->has('display_name'))
                        <p class="help-block">
                            {{ $errors->first('display_name') }}
                        </p>
                    @endif
                </div>
                <div class="form-group {{ $errors->has('plan_name') ? 'has-error' : '' }}">
                    <label for="plan_name">Name</label>
                    <input type="text" id="plan_name" name="plan_name" class="form-control" value="{{ old('plan_name', isset($subscription_package) ? $subscription_package->plan_name : '') }}" readonly="readonly">
                    @if($errors->has('plan_name'))
                        <p class="help-block">
                            {{ $errors->first('plan_name') }}
                        </p>
                    @endif
                </div>
                <div class="form-group {{ $errors->has('stripe_product') ? 'has-error' : '' }}">
                    <label for="stripe_product">Name</label>
                    <input type="text" id="stripe_product" name="stripe_product" class="form-control" value="{{ old('stripe_product', isset($subscription_package) ? $subscription_package->stripe_product : '') }}" readonly="readonly">
                    @if($errors->has('stripe_product'))
                        <p class="help-block">
                            {{ $errors->first('stripe_product') }}
                        </p>
                    @endif
                </div>
                <div class="form-group {{ $errors->has('stripe_pricing_plan') ? 'has-error' : '' }}">
                    <label for="stripe_pricing_plan">Name</label>
                    <input type="text" id="stripe_pricing_plan" name="stripe_pricing_plan" class="form-control" value="{{ old('stripe_pricing_plan', isset($subscription_package) ? $subscription_package->stripe_pricing_plan : '') }}" readonly="readonly">
                    @if($errors->has('stripe_pricing_plan'))
                        <p class="help-block">
                            {{ $errors->first('stripe_pricing_plan') }}
                        </p>
                    @endif
                </div>
                <div>
                    <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
                </div>
            </form>
        </div>
    </div>

@endsection