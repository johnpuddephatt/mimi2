<template>
<app-layout>
  <div class="column is-7-tablet is-6-desktop is-5-widescreen">

    <form ref="form" class="box register-form is-relative">

      <b-loading v-model="isProcessing" :is-full-page="false"></b-loading>

      <h3 class="title has-text-centered">Ti diamo il benvenuto <span class="emoji">👋</span></h3>
      <p class="subtitle has-text-centered">Enter your details below to begin</p>
      <b-notification
            v-if="errorMessage"
            type="is-danger"
            has-icon
            role="alert"
            :closable="false"
            :message="errorMessage">
      </b-notification>

      {{ price }}


      <b-field label="First name" :message="errors.first_name" :type="errors.first_name ? 'is-danger' : null">
        <b-input required name="first_name" v-model="userForm.first_name" ></b-input>
      </b-field>

      <b-field label="Last name" :message="errors.last_name" :type="errors.last_name ? 'is-danger' : null">
        <b-input required name="last_name" v-model="userForm.last_name"></b-input>
      </b-field>

      <b-field label="Email" :message="errors.email" :type="errors.email ? 'is-danger' : null">
        <b-input required type="email" name="email" v-model="userForm.email"></b-input>
      </b-field>

      <b-field label="Password" :message="errors.password || 'Minimum 8 characters'" :type="errors.password ? 'is-danger' : null">
        <b-input v-model="userForm.password" name="password" type="password" password-reveal minlength="8" required>
        </b-input>
      </b-field>

      <hr>

      <b-field>
        <b-checkbox v-model="user_is_cardholder">The person named above is the cardholder</b-checkbox>
      </b-field>

      <div v-if="!user_is_cardholder" class="mb-5">
        <b-field label="Cardholder name">
          <b-input v-model="billing.name" type="text">
          </b-input>
        </b-field>
      </div>

      <b-field label="Card details">
        <stripe-element-card
          ref="stripeElementCard"
          :pk="stripe_public_key" />
      </b-field>

      <b-button @click.prevent="register" :loading="isProcessing" type="is-primary" expanded>Pay now</b-button>
    </form>


  </div>
</app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout'
import { StripeElementCard } from '@vue-stripe/vue-stripe';


export default {
  props: ['errors', 'price', 'stripe_public_key', 'client_secret', 'user_hash', '$parameters'],
  components: {
    AppLayout,
    StripeElementCard
  },
  data() {
    return {
      isProcessing: false,
      user_is_cardholder: true,
      errorMessage: null,
      show_billing_fields: false,
      userForm: this.$inertia.form({
        first_name: null,
        last_name: null,
        email: null,
        password: null
      }),
      paymentForm: this.$inertia.form({
        intent: null,
        paymentMethodId: null
      }),
      billing: {
        name: null
      }
    }
  },
  mounted() {
  },
  computed: {

  },
  methods: {

    register() {
      this.isProcessing = true;
      if(!this.user_hash) {
        this.userForm.post(route('billing.create-user', { payment_type: this.$parameters.payment_type, stripe_price_code: this.$parameters.stripe_price_code }), {
          preserveScroll: true,
          errorBag: 'user',
          onSuccess: () => {
            this.setupIntent();
          },
          onError: errors => {
            this.isProcessing = false;
            this.errorToast('Check the form for errors');
          },
        })
      }
      else {
        this.setupIntent();
      }
    },

    async setupIntent() {
      const stripe = this.$refs.stripeElementCard.stripe;

      let payment_method = {
        card: this.$refs.stripeElementCard.element,
        billing_details: {
          name: this.user_is_cardholder ? `${this.userForm.first_name} ${this.userForm.last_name}` : this.billing.name,
          email: this.userForm.email
        }
      };

      if(this.$parameters.payment_type == 'subscription') {
        const { setupIntent, error } = await stripe.confirmCardSetup(this.client_secret, {
          payment_method: payment_method
        });
        this.paymentForm.intent = setupIntent;

        if(error) {
          this.errorMessage = error.message;
          this.isProcessing = false;
        }
        else {
          this.processPayment();
        }
      }
      else if (this.$parameters.payment_type == 'single') {
        const { paymentMethod, error } = await stripe.createPaymentMethod(
          'card',
          payment_method.card,
          { billing_details: payment_method.billing_details }
        );

        if(error) {
          this.errorMessage = error.message;
          this.isProcessing = false;
        }
        else {
          this.paymentForm.paymentMethodId = paymentMethod.id;
          this.processPayment();
        }
      }
    },

    processPayment() {
      this.paymentForm.post(route('billing.process-payment', { payment_type: this.$parameters.payment_type, stripe_price_code: this.$parameters.stripe_price_code, user_hash: this.user_hash }), {
        preserveScroll: true,
        onError: errors => {
          this.isProcessing = false;
          this.errorToast('Error processing payment');
        },
      })
    }
  }
}
</script>

<style lang="scss">

</style>
