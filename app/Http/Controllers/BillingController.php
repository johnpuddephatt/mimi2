<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

class BillingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
      $this->middleware('auth');
    }

    public function start() {
      \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
      $prices = \Stripe\Price::all();
      foreach($prices as $price) {
        $price->product_data =  \Stripe\Product::retrieve($price->product);
      }
      return view('billing.start', compact('prices'));
    }

    public function addSinglePaymentMethod($stripe_price_code) {
      \Auth::user()->createOrGetStripeCustomer();
      return view('billing.add-single-payment-method', [
        'stripe_price_code' => $stripe_price_code
      ]);
    }

    public function addSubscriptionPaymentMethod($stripe_price_code) {
      return view('billing.add-subscription-payment-method', [
        'intent' => \Auth::user()->createSetupIntent(),
        'stripe_price_code' => $stripe_price_code
      ]);
    }

    public function createUserSubscription(Request $request, $stripe_price_code) {
      \Auth::user()->newSubscription(
        'default', $stripe_price_code
      )->create($request->payment_method);
    }

    public function createUserPayment(Request $request, $stripe_price_code) {
      \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

      $stripeCharge = $request->user()->charge(
        \Stripe\Price::retrieve($stripe_price_code)->unit_amount, $request->paymentMethodId
      );

      if($stripeCharge->status == 'succeeded') {
        \Auth::user()->trial_ends_at = Carbon::now()->addMonths(4);
        \Auth::user()->save();
      }
      return response()->json([
        'status' => $stripeCharge->status,
      ]);
    }

    public function billingPortal (Request $request) {
      return $request->user()->redirectToBillingPortal();
    }
}
