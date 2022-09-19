<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import StripeCheckout from '../../Components/StripeCheckout.vue';
import useProduct from '../../composables/products';

const { cartCount, getCount } = useProduct();

const updateCartCount = async () => {
  await getCount();
};
</script>

<template>
    <AppLayout title="Checkout" :count-cart="cartCount">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Paiement
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl flex justify-center mx-auto sm:px-6 lg:px-8">
                <StripeCheckout @cartCountUpdated="updateCartCount()"></StripeCheckout>
            </div>
        </div>
    </AppLayout>
</template>
    