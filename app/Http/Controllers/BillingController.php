<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Inertia\Inertia;
use App\Http\Requests\StoreUser;
use Redirect;
use Vinkla\Hashids\Facades\Hashids;

class BillingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function addSinglePaymentMethod($stripe_price_code) {
      \Auth::user()->createOrGetStripeCustomer();
      return view('billing.add-single-payment-method', [
        'stripe_price_code' => $stripe_price_code
      ]);
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


    // Page that lists current ‘products’ in Stripe
    public function listProducts() {
      \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
      $prices = \Stripe\Price::all();
      foreach($prices as $price) {
        $price->product_data =  \Stripe\Product::retrieve($price->product);
      }
      return view('billing.start', compact('prices'));
    }

    public function paymentForm($payment_type, $stripe_price_code) {
      return Inertia::render('Billing/Subscribe', [
        'stripe_public_key' => config('services.stripe.public')
      ]);
    }

    public function createUser(StoreUser $request, $payment_type, $stripe_price_code) {

        $user =  User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if($payment_type == "single") {
          $user->createOrGetStripeCustomer();
        }

        return Inertia::render('Billing/Subscribe', [
          'user_hash' => Hashids::encode($user->id),
          'stripe_price_code' => $stripe_price_code,
          'stripe_public_key' => config('services.stripe.public'),
          'client_secret' => $payment_type == 'subscription' ? $user->createSetupIntent()->client_secret : null,
        ]);
    }

    public function processPayment(Request $request, $payment_type, $stripe_price_code, $user_hash) {
      \Auth::loginUsingId(Hashids::decode($user_hash)[0], true);

      if($payment_type == 'subscription') {
        \Auth::user()->newSubscription('default', $stripe_price_code)->create($request->intent['payment_method']);
      }

      else {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $stripeCharge = \Auth::user()->charge(
          \Stripe\Price::retrieve($stripe_price_code)->unit_amount, $request->paymentMethodId
        );

        if($stripeCharge->status == 'succeeded') {
          \Auth::user()->credits = 99999;
          \Auth::user()->save();
        }
      }

      return 'I think this worked';
    }
}
