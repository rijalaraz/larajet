import axios from "axios"
import { ref } from "vue";

export default function useStripe() {

    const elements = ref(null);

    const initialize = async() => {
        // This is your test publishable API key.
        const stripe = Stripe(import.meta.env.VITE_STRIPE_TEST_PUBLIC_KEY);

        const { clientSecret } = await axios.post(route('payment.intent'))
            .then( r => r.data )
            .catch( err => console.error(err) )

            elements.value = stripe.elements({ clientSecret });

        const paymentElement = elements.value.create("payment");
        paymentElement.mount("#payment-element");
    }

    return {
        initialize
    }

}