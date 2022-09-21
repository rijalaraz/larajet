<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { onMounted } from 'vue';
import useProduct from '../composables/products';
import useHelpers from '../helpers';

defineProps({
    orders: Object,
});

const { cartCount, getCount } = useProduct();

const {
    formatMoney,
    formatDate
} = useHelpers();

onMounted(async () => {
  await getCount();
});
</script>

<template>
    <AppLayout title="Dashboard" :count-cart="cartCount">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Vos commandes
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div>
                    <div class="-mx-4 sm:-mx-8 px-4 sm:px-8 overflow-x-auto">
                        <div v-for="order in orders" class="inline-block min-w-full shadow rounded-lg overflow-hidden bg-gray-200 mb-2">
                            <h2 class="font-semibold text-lg flex justify-center">Commande n° {{ order?.order_number }} passée le {{ formatDate(order?.created_at) }}</h2>
                            <table class="min-w-full leading-normal">
                                <thead>
                                    <tr>
                                        <th
                                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-200 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            Nom
                                        </th>
                                        <th
                                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-200 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            Prix
                                        </th>
                                        <th
                                            class="px-5 py-3 border-b-2 border-gray-200 bg-gray-200 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                                            Quantité
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="product in order?.products">
                                        <td class="px-5 py-3 border-b border-gray-200 bg-white text-sm">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 w-10 h-10">
                                                    <img class="w-full h-full rounded-full"
                                                        :src="product.image"
                                                        alt="" />
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-gray-900 whitespace-no-wrap">
                                                        {{ product.name }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-3 border-b border-gray-200 bg-white text-sm">
                                            <p class="text-gray-900 whitespace-no-wrap">{{ formatMoney(product.pivot.total_price / 100) }}</p>
                                        </td>
                                        <td class="px-5 py-3 border-b border-gray-200 bg-white text-sm">
                                            <p class="text-gray-900 whitespace-no-wrap">
                                                {{ product.pivot.total_quantity }}
                                            </p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
