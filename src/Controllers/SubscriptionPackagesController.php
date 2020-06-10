<?php

namespace Vsynch\StripeIntegration\Controllers;

use App\Http\Controllers\Controller;

use Vsynch\StripeIntegration\Requests\SubscriptionPackageMassDestroyRequest;
use Vsynch\StripeIntegration\Requests\SubscriptionPackageStoreRequest;
use Vsynch\StripeIntegration\Requests\SubscriptionPackageUpdateRequest;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Vsynch\StripeIntegration\SubscriptionPackage;

class SubscriptionPackagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {

        $keyword = $request->get('search');
        $perPage = 25;

        try{
            if (!empty($keyword)) {
                $subscription_packages = SubscriptionPackage::where('name', 'LIKE', "%$keyword%")
                    ->orWhere('stripe_product', 'LIKE', "%$keyword%")
                    ->orWhere('stripe_pricing_plan', 'LIKE', "%$keyword%")
                    ->latest()->paginate($perPage);
            } else {
                $subscription_packages = SubscriptionPackage::latest()->paginate($perPage);
            }
        }
        catch(\Exception $e){
            abort(403,$e->getMessage());
        }

        return view('vendor.vsynch.stripe-integration.index', compact('subscription_packages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('vendor.vsynch.stripe-integration.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\SubscriptionPackageStoreRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(SubscriptionPackageStoreRequest $request)
    {
        $requestData = $request->all();

        try{
            $subscription_package = SubscriptionPackage::create($requestData);
        }
        catch(\Exception $e){
            abort(403,$e->getMessage());
        }

        return redirect('admin/subscription-packages')->with('flash_message', 'SubscriptionPackage added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        try{

            $subscription_package = SubscriptionPackage::findOrFail($id);
        }
        catch(\Exception $e){
            abort(403,$e->getMessage());
        }

        return view('vendor.vsynch.stripe-integration.show', compact('subscription_package'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        try{
            $subscription_package = SubscriptionPackage::findOrFail($id);

        }
        catch(\Exception $e){
            abort(403,$e->getMessage());
        }

        return view('vendor.vsynch.stripe-integration.edit')->with(compact('subscription_package'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\SubscriptionPackageUpdateRequest $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(SubscriptionPackageUpdateRequest $request, $id)
    {
        $requestData = $request->all();

        try{

            $subscription_package = SubscriptionPackage::findOrFail($id);

            $subscription_package->update($requestData);

        }
        catch(\Exception $e){
            abort(403,$e->getMessage());
        }

        return redirect('admin/subscription-packages')->with('flash_message', 'SubscriptionPackage updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        try{
            SubscriptionPackage::destroy($id);
        }
        catch(\Exception $e){
            abort(403,$e->getMessage());
        }
        return redirect('admin/subscription-packages')->with('flash_message', 'SubscriptionPackage deleted!');
    }

    /**
     * Remove the multiple resources from storage.
     *
     * @param  \App\Http\Requests\SubscriptionPackageMassDestroyRequest  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function massDestroy(SubscriptionPackageMassDestroyRequest $request)
    {
        //SubscriptionPackage::whereIn('id', request('ids'))->delete();
        try{
            foreach(request('ids') as $id){
                SubscriptionPackage::destroy($id);
            }
        }
        catch(\Exception $e){
            abort(403,$e->getMessage());
        }

        return response(null, 204);
    }

    public function updateUserPaymentMethod(Request $request){
        try{

            $paymentMethod = $request->get('payment_method');

            //TODO -- add guard
            $user = Auth::user();

            $user->deletePaymentMethods();

            $update = $user->updateDefaultPaymentMethod($paymentMethod);

            $user->updateDefaultPaymentMethodFromStripe();

            return json_encode($update);

        }catch(\Exception $e){
            abort(403,$e->getMessage());
        }
    }

    public function editUserPaymentMethod(){
        try{
            //TODO -- add guard
            $user = Auth::user();

            if(!$user->hasPaymentMethod()) {
                return view('vsynch.stripe-integration.update_payment_method', [
                    'intent' => $user->createSetupIntent(),
                    'current_card_digits' => null
                ]);
            }
            else{
                $paymentMethod = $user->defaultPaymentMethod();

                return view('vendor.vsynch.stripe-integration.update_payment_method', [
                    'intent' => $user->createSetupIntent(),
                    'current_card_digits' => $paymentMethod->card->last4
                ]);
            }

        }catch(\Exception $e){
            abort(403,$e->getMessage());
        }
    }

    public function subscribe($id){
        try{
            $subscription_package = SubscriptionPackage::findOrFail($id);

            //TODO -- add guard
            $user = Auth::user();

            $stripeCustomer = $user->createOrGetStripeCustomer();

        }catch(\Exception $e){
            abort(403,$e->getMessage());
        }

        if(!$user->hasPaymentMethod()) return view('vendor.vsynch.stripe-integration.update_payment_method', [
            'intent' => $user->createSetupIntent(),
            'current_card_digits' => null
        ]);
        else{
            $paymentMethod = $user->defaultPaymentMethod();

            
            if (!$user->subscribed($subscription_package->stripe_product)) {
                try {
                    $user->newSubscription($subscription_package->stripe_product, $subscription_package->stripe_pricing_plan)->create($paymentMethod->id);
                } catch (IncompletePayment $exception) {
                    return redirect()->route(
                        'cashier.payment',
                        [$exception->payment->id, 'redirect' => redirect()->route(config('stripe_integration.web_route_name_prefix').'subscription-packages.index')]
                    );
                }
                    toastr()->success('You are now subscribed to ' . $subscription_package->name, 'Success', ['timeOut' => 5000]);
                    return redirect()->route(config('stripe_integration.web_route_name_prefix') . 'subscription-packages.index');
                }
            else if($user->subscription($subscription_package->stripe_product)->onGracePeriod()){
                $user->subscription($subscription_package->stripe_product)->resume();
                toastr()->success('Your subscription to '.$subscription_package->name.' has resumed', 'Success', ['timeOut' => 5000]);
                return redirect()->route(config('stripe_integration.web_route_name_prefix').'subscription-packages.index');
            }
            else {
                $user->subscription($subscription_package->stripe_product)->incrementQuantity();
                toastr()->success('We have incremented your subscription quantity for ' . $subscription_package->name, 'Success', ['timeOut' => 5000]);
                return redirect()->route(config('stripe_integration.web_route_name_prefix') . 'subscription-packages.index');
                //return redirect()->route(config('stripe_integration.web_route_name_prefix').'subscription-packages.index')->withErrors('You are already subscribed to this package!');
            }
        }
    }

    public function changePlan($id){
        try{
            $subscription_package = SubscriptionPackage::findOrFail($id);

            //TODO -- add guard
            $user = Auth::user();

            $stripeCustomer = $user->createOrGetStripeCustomer();

        }catch(\Exception $e){
            abort(403,$e->getMessage());
        }

        if(!$user->hasPaymentMethod()) return view('vendor.vsynch.stripe-integration.update_payment_method', [
            'intent' => $user->createSetupIntent(),
            'current_card_digits' => null
        ]);
        else{
            $paymentMethod = $user->defaultPaymentMethod();

            if ($user->subscribed($subscription_package->stripe_product)) {
                try {
                    $user->subscription($subscription_package->stripe_product)->swapAndInvoice($subscription_package->stripe_pricing_plan);
                } catch (IncompletePayment $exception) {
                    return redirect()->route(
                        'cashier.payment',
                        [$exception->payment->id, 'redirect' => redirect()->route(config('stripe_integration.web_route_name_prefix').'subscription-packages.index')]
                    );
                }
                toastr()->success('You are now subscribed to '.$subscription_package->name, 'Success', ['timeOut' => 5000]);
                return redirect()->route(config('stripe_integration.web_route_name_prefix').'subscription-packages.index');
            }
            else return redirect()->route(config('stripe_integration.web_route_name_prefix').'subscription-packages.index')->withErrors('You are not subscribed to this package!');
        }
    }

    public function unsubscribe($id){
        try{
            $subscription_package = SubscriptionPackage::findOrFail($id);

            //TODO -- add guard
            $user = Auth::user();

            $stripeCustomer = $user->createOrGetStripeCustomer();

        }catch(\Exception $e){
            abort(403,$e->getMessage());
        }

        if(!$user->hasPaymentMethod()) return view('vendor.vsynch.stripe-integration.update_payment_method', [
            'intent' => $user->createSetupIntent(),
            'current_card_digits' => null
        ]);
        else{
            $paymentMethod = $user->defaultPaymentMethod();

            if ($user->subscribed($subscription_package->stripe_product)) {
                $user->subscription($subscription_package->stripe_product)->cancel();
                toastr()->success('You subscription to '.$subscription_package->name.' has been cancelled', 'Success', ['timeOut' => 5000]);
                return redirect()->route(config('stripe_integration.web_route_name_prefix').'subscription-packages.index');
            }
            else return redirect()->route(config('stripe_integration.web_route_name_prefix').'subscription-packages.index')->withErrors('You are not subscribed to this package!');
        }
    }

    public function unsubscribeNow($id){
        try{
            $subscription_package = SubscriptionPackage::findOrFail($id);

            //TODO -- add guard
            $user = Auth::user();

            $stripeCustomer = $user->createOrGetStripeCustomer();

        }catch(\Exception $e){
            abort(403,$e->getMessage());
        }

        if(!$user->hasPaymentMethod()) return view('vendor.vsynch.stripe-integration.update_payment_method', [
            'intent' => $user->createSetupIntent(),
            'current_card_digits' => null
        ]);
        else{
            $paymentMethod = $user->defaultPaymentMethod();

            if ($user->subscription($subscription_package->stripe_product)->onGracePeriod() || $user->subscribed($subscription_package->stripe_product)) {
                $user->subscription($subscription_package->stripe_product)->cancelNow();
                toastr()->success('You subscription to '.$subscription_package->name.' has been cancelled effective immediately', 'Success', ['timeOut' => 5000]);
                return redirect()->route(config('stripe_integration.web_route_name_prefix').'subscription-packages.index');
            }
            else return redirect()->route(config('stripe_integration.web_route_name_prefix').'subscription-packages.index')->withErrors('You are not subscribed to this package!');
        }
    }

    public function resumeSubscription($id){
        try{
            $subscription_package = SubscriptionPackage::findOrFail($id);

            //TODO -- add guard
            $user = Auth::user();

            $stripeCustomer = $user->createOrGetStripeCustomer();

        }catch(\Exception $e){
            abort(403,$e->getMessage());
        }

        if(!$user->hasPaymentMethod()) return view('vendor.vsynch.stripe-integration.update_payment_method', [
            'intent' => $user->createSetupIntent(),
            'current_card_digits' => null
        ]);
        else{
            $paymentMethod = $user->defaultPaymentMethod();

            if($user->subscription($subscription_package->stripe_product)->onGracePeriod()){
                $user->subscription($subscription_package->stripe_product)->resume();
                toastr()->success('Your subscription to '.$subscription_package->name.' has resumed', 'Success', ['timeOut' => 5000]);
                return redirect()->route(config('stripe_integration.web_route_name_prefix').'subscription-packages.index');
            }
            else return redirect()->route(config('stripe_integration.web_route_name_prefix').'subscription-packages.index')->withErrors('Cannot Resume subscription. You are not on Grace Period!');
        }
    }
}
