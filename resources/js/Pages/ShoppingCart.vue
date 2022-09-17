<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import JetButton from "@/Components/Button.vue";
import useProduct from "../composables/products";
import { onMounted, computed } from "vue";
import useUtils from "../helpers";

const {
  products,
  getProducts,
  increaseQuantity,
  decreaseQuantity,
  destroyProduct,
  cartCount,
  getCount,
} = useProduct();

const cartTotal = computed(() => {
  let price = Object.values(products.value).reduce(
    (acc, product) => (acc += product.price * product.quantity),
    0
  );
  return formatMoney(price / 100, "fr-FR", "EUR");
});

const { formatMoney } = useUtils();

const increase = async (id) => {
  await increaseQuantity(id);
  await getCount();
};

const decrease = async (id) => {
  await decreaseQuantity(id);
  await getCount();
};

const destroy = async (id) => {
  await destroyProduct(id);
  await getCount();
};

onMounted(async () => {
  await getProducts();
  await getCount();
});
</script>

<template>
  <AppLayout title="Recaps" :count-cart="cartCount">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Récapitulatif de votre panier
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <section class="antialiased bg-gray-100 text-gray-600 h-screen px-4" x-data="app">
          <div class="flex flex-col justify-center">
            <!-- Table -->
            <div
              class="w-full max-w-5xl mx-auto bg-white shadow-lg rounded-sm border border-gray-200"
            >
              <div class="overflow-x-auto p-3">
                <table class="table-auto w-full">
                  <thead class="text-xs font-semibold uppercase text-gray-400 bg-gray-50">
                    <tr>
                      <th></th>
                      <th class="p-2">
                        <div class="font-semibold text-left">Product Name</div>
                      </th>
                      <th class="p-2">
                        <div class="font-semibold text-left">Quantity</div>
                      </th>
                      <th class="p-2">
                        <div class="font-semibold text-left">Unit price</div>
                      </th>
                      <th class="p-2">
                        <div class="font-semibold text-left">Total</div>
                      </th>
                      <th class="p-2">
                        <div class="font-semibold text-center">Action</div>
                      </th>
                    </tr>
                  </thead>

                  <tbody class="text-sm divide-y divide-gray-100">
                    <template v-for="product in products" :key="product.id">
                      <tr>
                        <td class="hidden pb-4 md:table-cell">
                          <a href="#"
                            ><img
                              :src="product.associatedModel.image"
                              class="w-20 rounded"
                              alt=""
                          /></a>
                        </td>
                        <td class="p-2">
                          <div class="font-medium text-gray-800">
                            {{ product.name }}
                          </div>
                        </td>
                        <td class="p-2">
                          <div class="flex flex-row">
                            <button @click="decrease(product.id)">-</button>
                            <div class="mx-3 bg-gray-200 w-5 text-center">
                              {{ product.quantity }}
                            </div>
                            <button @click="increase(product.id)">+</button>
                          </div>
                        </td>
                        <td class="p-2">
                          <div class="text-left">
                            {{ formatMoney(product.price / 100, "fr-FR", "EUR") }}
                          </div>
                        </td>
                        <td class="p-2">
                          <div class="text-left font-medium text-green-500">
                            {{
                              formatMoney(
                                (product.price * product.quantity) / 100,
                                "fr-FR",
                                "EUR"
                              )
                            }}
                          </div>
                        </td>
                        <td class="p-2">
                          <div class="flex justify-center">
                            <button
                              title="Supprimer le produit"
                              @click="destroy(product.id)"
                            >
                              <svg
                                class="w-8 h-8 hover:text-blue-600 rounded-full hover:bg-gray-100 p-1"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                              >
                                <path
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                ></path>
                              </svg>
                            </button>
                          </div>
                        </td>
                      </tr>
                    </template>
                  </tbody>
                </table>
              </div>

              <!-- total amount -->
              <div
                class="flex justify-end font-bold space-x-4 text-2xl border-t border-gray-100 px-5 py-4"
              >
                <div>Total</div>
                <div class="text-blue-600">
                  {{ cartTotal }}
                </div>
              </div>

              <div class="flex items-center justify-end py-4 px-4">
                <JetButton @click=""> Proceed to checkout </JetButton>
              </div>

              <div class="flex justify-end">
                <!-- send this data to backend (note: use class 'hidden' to hide this input) -->
                <input
                  type="hidden"
                  class="border border-black bg-gray-50"
                  x-model="selected"
                />
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>
  </AppLayout>
</template>

<style lang="scss" scoped></style>
