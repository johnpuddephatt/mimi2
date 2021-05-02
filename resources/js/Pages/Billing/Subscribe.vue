<template>
<app-layout>
  <div class="columns is-centered">
    <div class="column is-12-tablet is-12-desktop is-9-widescreen">

      <form ref="form" class="box p-0 register-form is-relative columns">

        <b-loading v-model="isProcessing" :is-full-page="false"></b-loading>

        <div class="column has-background-white-bis p-6 portrait-p-3">
          <img class="sidebar-image" src="/images/Katie-e-Matteo.png" />
          <p v-if="price.recurring" class="tag is-success mb-4">Recurring {{ price.recurring.interval }}ly payment</p>
          <p v-else class="tag is-success mb-4">Single payment</p>

          <h3 class="title is-size-4">{{ price.product_data.name }}</h3>
          <p>{{ price.product_data.description }}</p>

          <p class="title is-size-5 mt-3"><span v-if="price.currency == 'usd'">$</span><span v-else-if="price.currency == 'eur'">€</span><span v-else-if="price.currency == 'gbp'">£</span>{{ price.unit_amount/100 }}         <span v-if="price.recurring">per {{ price.recurring.interval }}</span></p>

          <ul class="mt-6">
            <li class="is-flex is-size-6 mb-2"><b-icon class="mr-1" type="is-success" icon="checkbox-marked-circle-outline"/><p>Enjoy a new Italian lesson every day</p></li>
            <li class="is-flex is-size-6 mb-2"><b-icon class="mr-1" type="is-success" icon="checkbox-marked-circle-outline"/><p>Take part in live weekly conversation practice</p></li>
            <li class="is-flex is-size-6"><b-icon class="mr-1" type="is-success" icon="checkbox-marked-circle-outline"/><p>Receive personalised feedback from Italian teachers</p></li>
          </ul>

          <p class="is-size-7 is-flex has-text-grey-dark is-flex-align-items-center mt-6">Secure checkout powered by <svg class="ml-1" style="margin-top: 1px" focusable="false" width="33" height="15"><g fill-rule="evenodd"><path fill="currentColor" d="M32.956 7.925c0-2.313-1.12-4.138-3.261-4.138-2.15 0-3.451 1.825-3.451 4.12 0 2.719 1.535 4.092 3.74 4.092 1.075 0 1.888-.244 2.502-.587V9.605c-.614.307-1.319.497-2.213.497-.876 0-1.653-.307-1.753-1.373h4.418c0-.118.018-.588.018-.804zm-4.463-.859c0-1.02.624-1.445 1.193-1.445.55 0 1.138.424 1.138 1.445h-2.33zM22.756 3.787c-.885 0-1.454.415-1.77.704l-.118-.56H18.88v10.535l2.259-.48.009-2.556c.325.235.804.57 1.6.57 1.616 0 3.089-1.302 3.089-4.166-.01-2.62-1.5-4.047-3.08-4.047zm-.542 6.225c-.533 0-.85-.19-1.066-.425l-.009-3.352c.235-.262.56-.443 1.075-.443.822 0 1.391.922 1.391 2.105 0 1.211-.56 2.115-1.39 2.115zM18.04 2.766V.932l-2.268.479v1.843zM15.772 3.94h2.268v7.905h-2.268zM13.342 4.609l-.144-.669h-1.952v7.906h2.259V6.488c.533-.696 1.436-.57 1.716-.47V3.94c-.289-.108-1.346-.307-1.879.669zM8.825 1.98l-2.205.47-.009 7.236c0 1.337 1.003 2.322 2.34 2.322.741 0 1.283-.135 1.581-.298V9.876c-.289.117-1.716.533-1.716-.804V5.865h1.716V3.94H8.816l.009-1.96zM2.718 6.235c0-.352.289-.488.767-.488.687 0 1.554.208 2.241.578V4.202a5.958 5.958 0 0 0-2.24-.415c-1.835 0-3.054.957-3.054 2.557 0 2.493 3.433 2.096 3.433 3.17 0 .416-.361.552-.867.552-.75 0-1.708-.307-2.467-.723v2.15c.84.362 1.69.515 2.467.515 1.879 0 3.17-.93 3.17-2.548-.008-2.692-3.45-2.213-3.45-3.225z"></path></g></svg></p>
        </div>

        <div class="column is-three-fifths p-6 portrait-p-3">
          <h3 class="title has-text-centered">Impariamo l’italiano insieme <span class="emoji">🇮🇹</span></h3>
          <p class="subtitle has-text-centered mb-6">Let’s learn Italian together</p>
          <b-notification
                v-if="errorMessage"
                type="is-danger"
                has-icon
                role="alert"
                :closable="false"
                :message="errorMessage">
          </b-notification>

          <div class="columns mb-0">
            <b-field class="column" label="First name" :message="userErrors.first_name" :type="userErrors.first_name ? 'is-danger' : null">
              <b-input required name="first_name" v-model="userForm.first_name" ></b-input>
            </b-field>

            <b-field class="column" label="Last name" :message="userErrors.last_name" :type="userErrors.last_name ? 'is-danger' : null">
              <b-input required name="last_name" v-model="userForm.last_name"></b-input>
            </b-field>
          </div>

          <b-field label="Email" :message="userErrors.email" :type="userErrors.email ? 'is-danger' : null">
            <b-input required type="email" name="email" v-model="userForm.email"></b-input>
          </b-field>

          <b-field label="Password" :message="userErrors.password || 'Minimum 8 characters'" :type="userErrors.password ? 'is-danger' : null">
            <b-input v-model="userForm.password" name="password" type="password" password-reveal minlength="8" required>
            </b-input>
          </b-field>

          <hr>

          <b-field>
            <b-checkbox class="is-size-6 mb-2" v-model="user_is_cardholder">The name above is the cardholder</b-checkbox>
          </b-field>

          <div v-if="!user_is_cardholder" class="mb-5">
            <b-field label="Cardholder name">
              <b-input v-model="billing.name" type="text">
              </b-input>
            </b-field>
          </div>

          <b-field label="Card details">
            <stripe-element-card
              @element-change="stripeElementCardChanged($event)"
              ref="stripeElementCard"
              :pk="stripe_public_key" />
          </b-field>

          <b-button @click.prevent="register" :disabled="!stripeElementCardComplete" :loading="isProcessing" class="has-text-weight-medium" type="is-primary" size="is-large" expanded>Pay now</b-button>
          <p class="mt-2 is-size-6">By proceeding you confirm acceptance of our <a href="/terms" target="_blank">terms and conditions</a></p>
        </div>

      </form>
    </div>
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
      stripeElementCardComplete: false,
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
    userErrors() {
      return this.errors?.user || {};
    }

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
        // preserveScroll: true,
        onError: errors => {
          this.isProcessing = false;
          this.errorToast('Error processing payment');
        },
      })
    },

    stripeElementCardChanged(event) {
      if (event.complete) {
        this.stripeElementCardComplete = true;
      } else {
        this.stripeElementCardComplete = false;
      }
    }
  }
}
</script>

<style lang="scss">
.sidebar-image {
  margin: -3rem -3rem 1.5rem;
  max-width: none;
  width: calc(100% + 6rem);

  @media (orientation: portrait) {
    margin: -1.5rem -1.5rem 1.5rem;
    width: calc(100% + 3rem);
  }
}

.portrait-p-3 {
  @media (orientation: portrait) {
    padding: 1.5rem !important;
  }
}
</style>
