@extends('layouts.static')

@section('content')
  <section class="section is-medium">
    <div class="container">
      <div class="columns is-centered">
        <div class="column is-7-tablet is-6-desktop is-5-widescreen is-paddingless">
          <div class="box">
            <h3 class="title has-text-centered">Make a payment</h3>
            <p class="subtitle has-text-centered">Got the money?</p>

            @if(1 == 2)
              <div class="confirmation-form">
                <h3>Great success!</h3>
                <p> You are subscribed</p>
              </div>
            @else
              <div class="payment-form">
                <input class="input" id="card-holder-name" type="text">
                <div id="card-element"></div>
                <button class="button" id="card-button">
                    Submit
                </button>
              </div>

              <script src="https://js.stripe.com/v3/"></script>
              <script>

                  const stripe = Stripe('{{ config('services.stripe.public') }}');

                  const elements = stripe.elements();
                  const cardElement = elements.create('card');

                  cardElement.mount('#card-element');

                  const cardHolderName = document.getElementById('card-holder-name');
                  const cardButton = document.getElementById('card-button');
                  const clientSecret = cardButton.dataset.secret;

                  cardButton.addEventListener('click', async (e) => {
                    const { paymentMethod, error } = await stripe.createPaymentMethod(
                      'card', cardElement, {
                          billing_details: { name: cardHolderName.value }
                      }
                  );

                  if (error) {
                      alert('something went wrong', error);
                  } else {
                      const response = await fetch(route('billing.create-user-payment', { 'stripe_price_code': '{{ $stripe_price_code }}'}), {
                        method: 'POST',
                        headers: {
                          'Content-Type': 'application/json',
                          'X-CSRF-Token': document.head.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ 'paymentMethodId': paymentMethod.id }) // body data type must match "Content-Type" header
                      }).then(response => response.json())
                      .then(data => alert(data.status));;

                      // location.reload();
                    }
                  });
              </script>
            @endif


          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
