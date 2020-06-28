<?php

namespace Vsynch\StripeIntegration\Controllers\Api\V1;

use App\Http\Controllers\Controller;

use App\User;
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
        $success = true;

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
            response()->json(['success'=>false,'error'=>$e->getMessage()],500);
        }

        return response()->json(compact('subscription_packages','success'));
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
            response()->json(['success'=>false,'error'=>$e->getMessage()],500);
        }

        return response()->json(['success'=>true]);
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
        $success = true;
        try{

            $subscription_package = SubscriptionPackage::findOrFail($id);
        }
        catch(\Exception $e){
            response()->json(['success'=>false,'error'=>$e->getMessage()],500);
        }

        return response()->json(compact('subscription_package','success'));
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
        $success = true;
        try{
            $subscription_package = SubscriptionPackage::findOrFail($id);

        }
        catch(\Exception $e){
            response()->json(['success'=>false,'error'=>$e->getMessage()],500);
        }

        return response()->json(compact('subscription_package','success'));
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
            response()->json(['success'=>false,'error'=>$e->getMessage()],500);
        }

        return response()->json(['success'=>true]);
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
            response()->json(['success'=>false,'error'=>$e->getMessage()],500);
        }
        return response()->json(['success'=>true]);
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
            response()->json(['success'=>false,'error'=>$e->getMessage()],500);
        }

        return response()->json(['success'=>true]);
    }

    public function showSubscriptions(Request $request){
        $success = true;
        try {

            $user = $request->get('selected_user');

            if($user) $user = User::findOrFail($user);
            else $user = Auth::user();

            $subscriptions = $user->getActiveSubscriptions()->join('subscription_packages', 'subscriptions.stripe_plan', '=', 'subscription_packages.stripe_pricing_plan')->paginate(5);

        }catch(\Exception $e){
            return response()->json(['success'=>false,'error'=>$e->getMessage()],500);
        }

        return response()->json(compact('subscriptions','success'));

    }
}
