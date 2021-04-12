@extends('layouts.static')

@section('content')


  <section class="section is-medium">
    <div class="container">
      <div class="columns is-centered">
        <div class="column is-7-tablet is-6-desktop is-5-widescreen is-paddingless">
          <div class="box">
            <h3 class="title has-text-centered">Opzioni di pagamento 💳</h3>
            <p class="subtitle has-text-centered">Decide how you want to pay</p>

              @foreach($prices as $price)
                <a class="box" href="{{ route('billing.payment-form', ['payment_type' => ($price->recurring ? 'subscription' : 'single'), 'stripe_price_code' => $price->id ]) }}">
                  <h3 class="is-size-4">{{$price->product_data->name}}</h3>
                  <p>{{$price->product_data->description}}</p>
                  <p>
                    @if($price->currency == 'eur')€@endif
                    @if($price->currency == 'gbp')£@endif
                    @if($price->currency == 'usd')$@endif
                    {{ $price->unit_amount/100}}
                    @if($price->recurring)
                      per {{ $price->recurring->interval }}
                    @endif
                  </p>
                </a>
              @endforeach

          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
