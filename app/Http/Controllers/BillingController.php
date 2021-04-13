<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Payment;
use Inertia\Inertia;
use App\Http\Requests\StoreUser;
use Redirect;
use Vinkla\Hashids\Facades\Hashids;
use Laravel\Cashier\Exceptions\IncompletePayment;

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


    public function billingPortal (Request $request) {
      return $request->user()->redirectToBillingPortal();
    }


    public function listProducts() {
      \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
      $prices = \Stripe\Price::all();
      foreach($prices as $key => $price) {
        $price->product_data =  \Stripe\Product::retrieve($price->product);
        if(!$price->product_data->active) {
          unset($prices->data[$key]);
        }
      }
      return view('billing.start', compact('prices'));
    }


    public function paymentForm($payment_type, $stripe_price_code) {
      \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
      $price = \Stripe\Price::retrieve($stripe_price_code);
      $price->product_data =  \Stripe\Product::retrieve($price->product);

      return Inertia::render('Billing/Subscribe', [
        'stripe_public_key' => config('services.stripe.public'),
        'price' => $price

      ]);
    }


    public function createUser(StoreUser $request, $payment_type, $stripe_price_code) {

        $user = User::where(['email' => $request->email])->first();

        if($user) {
          if($user->subscribed()) {
            throw \Illuminate\Validation\ValidationException::withMessages([
               'email' => ['An account with this email already has an active subscription.'],
            ]);
          }
          if(!Hash::check($request->password, $user->password)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
               'email' => ['An account with this email exists but the provided password did not match.'],
            ]);
          }
        }

        if(!$user) {
          $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'first_name' => $request->first_name,
            'last_name' => $request->last_name
          ]);
        }

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
        try {
          \Auth::user()->newSubscription('default', $stripe_price_code)->create($request->intent['payment_method']);
        } catch (IncompletePayment $exception) {
          return redirect()->route('billing.verify-payment',[$exception->payment->id]);
        } catch(Exception $exception) {
          return redirect()->route('billing.error');
        }
      }

      else {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        try {
          $stripeCharge = \Auth::user()->charge(
            \Stripe\Price::retrieve($stripe_price_code)->unit_amount, $request->paymentMethodId
          );
        } catch (IncompletePayment $exception) {
          return redirect()->route(
            'billing.verify-payment',
            [$exception->payment->id]
          );
        } catch(Exception $exception) {
          return redirect()->route('billing.error');
        }

        if($stripeCharge->status == 'succeeded') {
          \Auth::user()->credits = 3;
          \Auth::user()->save();
        }
      }

      return redirect()->route('billing.success');
    }


    public function verifyPayment($id)
    {
      $payment = new Payment(
          \Stripe\PaymentIntent::retrieve($id, Cashier::stripeOptions())
      );

      return Inertia::render('Billing/VerifyPayment', [
        'stripe_public_key' => config('services.stripe.public'),
        'payment' => [
          'amount' => $payment->amount(),
          'is_succeeded' => $payment->isSucceeded(),
          'is_cancelled' => $payment->isCancelled(),
          'requires_payment_method' => $payment->requiresPaymentMethod(),
          'requires_action' => $payment->requiresAction(),
          'requires_confirmation' => $payment->requiresConfirmation(),
          'client_secret' => $payment->clientSecret(),
          'payment_method' => $payment->payment_method,
        ],
        'stripe_authentication_failure_code' => \Stripe\ErrorObject::CODE_PAYMENT_INTENT_AUTHENTICATION_FAILURE
      ]);
    }

}
