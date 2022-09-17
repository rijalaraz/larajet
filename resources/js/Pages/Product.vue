<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { onMounted } from "vue";
import AddToCart from "../Components/AddToCart.vue";
import useProduct from "../composables/products";
import useUtils from "../helpers";

defineProps({
  products: Object,
});

const { cartCount, getCount } = useProduct();

const updateCartCount = async () => {
  await getCount();
};

const { formatMoney } = useUtils();

onMounted(async () => {
  await getCount();
});
</script>

<template>
  <AppLayout title="Products" :count-cart="cartCount">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Vos produits</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- component -->
        <div tabindex="0" class="focus:outline-none">
          <!-- Remove py-8 -->
          <div class="mx-auto container">
            <div class="grid grid-cols-4">
              <!-- Card 1 -->
              <div
                v-for="product in products"
                tabindex="0"
                class="focus:outline-none mx-2 w-72 xl:mb-0 mb-8"
              >
                <div>
                  <img
                    :alt="product.name"
                    :src="product.image"
                    tabindex="0"
                    class="focus:outline-none w-full h-44"
                  />
                </div>
                <div class="bg-white">
                  <div class="flex items-center justify-between px-4 pt-4">
                    <div>
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        tabindex="0"
                        class="focus:outline-none"
                        width="20"
                        height="20"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="#2c3e50"
                        fill="none"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                      >
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path
                          d="M9 4h6a2 2 0 0 1 2 2v14l-5-3l-5 3v-14a2 2 0 0 1 2 -2"
                        ></path>
                      </svg>
                    </div>
                    <div class="bg-yellow-200 py-1.5 px-6 rounded-full">
                      <p tabindex="0" class="focus:outline-none text-xs text-yellow-700">
                        {{ formatMoney(product.price / 100, "fr-FR", "EUR") }}
                      </p>
                    </div>
                  </div>
                  <div class="p-4">
                    <div class="flex items-center">
                      <h2 tabindex="0" class="focus:outline-none text-lg font-semibold">
                        {{ product.name }}
                      </h2>
                    </div>
                    <p tabindex="0" class="focus:outline-none text-xs text-gray-600 mt-2">
                      {{ product.description }}
                    </p>

                    <AddToCart
                      :product-id="product.id"
                      @cartCountUpdated="updateCartCount"
                    />
                  </div>
                </div>
              </div>
              <!-- Card 1 Ends -->
            </div>
          </div>
          <!--- more free and premium Tailwind CSS components at https://tailwinduikit.com/ --->
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<style lang="scss" scoped></style>
