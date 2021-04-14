<template>
<app-layout>
  <div class="column is-7-tablet is-6-desktop is-5-widescreen">

    <form ref="form" class="box register-form">

      <div v-if="payment.is_cancelled">
        <h3 class="title has-text-centered">Payment Cancelled <span class="emoji">🚦</span></h3>
        <p class="subtitle has-text-centered">This payment was cancelled.</p>

        <p>The transaction has been cancelled, no payment has been taken.</p>
        <p>If you did not intend to cancel the payment, please try again from the beginning.</p>

      </div>

      <div v-else>
        <h3 class="title has-text-centered">Verifica necessaria <span class="emoji">🚦</span></h3>
        <p class="subtitle has-text-centered">Please verify your payment</p>

        <b-notification
          v-if="errorMessage"
          type="is-danger"
          has-icon
          role="alert"
          :closable="false"
          :message="errorMessage">
        </b-notification>

        <div v-if="!paymentProcessed" id="payment-elements">

            <div v-show="requiresPaymentMethod">

              <p>We were unable to process your payment of {{ payment.amount}}. Please try again.</p>

              <hr>

              <b-field label="Cardholder name">
                <b-input id="cardholder-name" required v-model="name" type="text">
                </b-input>
              </b-field>

              <b-field label="Card details">
                <div id="card-element"></div>
              </b-field>

              <button
                class="button is-primary"
                id="card-button"
                @click="addPaymentMethod"
                :disabled="paymentProcessing">
                Pay now
              </button>
            </div>

            <div v-show="requiresAction || requiresConfirmation">

              <p>You are about to make a payment of {{ payment.amount}}, click the button below to proceed. You may be prompted for additional verification for security purposes.</p>

              <button
                class="button is-primary is-fullwidth mt-4"
                id="card-button"
                @click="confirmPaymentMethod"
                :disabled="paymentProcessing">
                Confirm payment
              </button>
            </div>
        </div>

      </div>

    </form>


  </div>
</app-layout>
</template>

<script>
import { Inertia } from '@inertiajs/inertia'
import AppLayout from '@/Layouts/AppLayout'

export default {
  props: ['stripe_public_key', 'payment', 'stripe_authentication_failure_code', '$parameters'],
  components: {
    AppLayout,
  },
  data() {
    return {
      name: '',
      cardElement: null,
      paymentProcessing: false,
      paymentProcessed: false,
      requiresPaymentMethod: this.payment.requires_payment_method,
      requiresAction: this.payment.requires_action,
      requiresConfirmation: this.payment.requires_confirmation,
      errorMessage: ''
    }
  },
  mounted() {
    const script = document.createElement('script');
    script.src = 'https://js.stripe.com/v3';
    document.querySelector('head').appendChild(script);
    script.onload = () => {
      window.Stripe = Stripe(this.stripe_public_key);
      if(!this.payment.is_succeeded && !this.payment.is_cancelled && !this.payment.requires_action) {
        this.configureStripe();
      }
    };
  },
  methods: {
    addPaymentMethod: function () {
       var self = this;
       this.paymentProcessing = true;
       this.paymentProcessed = false;
       this.errorMessage = '';
       Stripe.confirmCardPayment(
           self.payment.client_secret, {
               payment_method: {
                   card: self.cardElement,
                   billing_details: {
                     name: self.name
                   }
               }
           }
       ).then(function (result) {
           self.paymentProcessing = false;
           if (result.error) {
               if (result.error.code === self.stripe_authentication_failure_code &&
                   result.error.param === 'payment_method_data[billing_details][name]') {
                   self.errorMessage = 'Please provide your name.';
               } else {
                   self.errorMessage = result.error.message;
               }
           } else {
               Inertia.visit(route('billing.show-profile'));
           }
       });
    },
    confirmPaymentMethod: function () {
       var self = this;
       this.paymentProcessing = true;
       this.paymentProcessed = false;
       this.errorMessage = '';
       Stripe.confirmCardPayment(
         self.payment.client_secret, {
           payment_method: self.payment.payment_method
         }
       ).then(function (result) {
         self.paymentProcessing = false;
         if (result.error) {
             self.errorMessage = result.error.message;
             if (result.error.code === self.stripe_authentication_failure_code) {
                 self.requestPaymentMethod();
             }
         } else {
            Inertia.visit(route('billing.show-profile'));
         }
       });
    },
    requestPaymentMethod: function () {
       this.configureStripe();
       this.requiresPaymentMethod = true;
       this.requiresAction = false;
    },
    configureStripe: function () {
      const elements = Stripe.elements();
      this.cardElement = elements.create('card', {
        hidePostalCode: false
      });
      this.cardElement.mount('#card-element');
    },
  },

}
</script>

<style lang="scss">

</style>
