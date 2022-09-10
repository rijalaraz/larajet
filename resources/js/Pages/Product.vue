<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import AddToCart from "../Components/AddToCart.vue";

defineProps({
  products: Object,
});

const formatPrice = (value) => {
  let val = (value / 1).toFixed(2).replace(".", ",");
  return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
};

/////////////////////////////////////////////////////////////////////
// Format money based on integer or floating input
// ===============================================
// Possible inputs are:
// value:                 Numerical input (required)
// locale:                Language and country information, such as 'en' or 'en-US'
// currencyCode:          3-character cdde from ISO 4217
// subunitsValue:         Value is denominated in subunits, such as cents
// subunitsToUnits:       Overrides the minor unit value from ISO 4217. The "subunitsValue"
//                        option is redundant if you enter a value for this
// hideSubunits:          Set this to true if you don't want to display the subunits
// supplementalPrecision: Allows you to display partial subunits . This is ignored if
//                        you specify "hideSubunits=true"
/////////////////////////////////////////////////////////////////////
const formatMoney = (
  value,
  locale,
  currencyCode,
  subunitsValue,
  subunitsToUnit,
  hideSubunits,
  supplementalPrecision
) => {
  let ret = 0;
  if (Number.isFinite(value)) {
    try {
      let numFormat = new Intl.NumberFormat(locale, {
        style: "currency",
        currency: currencyCode,
      });
      let options = numFormat.resolvedOptions();
      let fraction_digits = options.minimumFractionDigits;
      if (subunitsToUnit > 1) {
        value = value / subunitsToUnit;
      } else if (subunitsValue == true) {
        value = value / 10 ** options.minimumFractionDigits;
      }
      if (hideSubunits == true) {
        numFormat = new Intl.NumberFormat(locale, {
          style: "currency",
          currency: currencyCode,
          minimumFractionDigits: 0,
          maximumFractionDigits: 0,
        });
      } else if (supplementalPrecision > 0) {
        numFormat = new Intl.NumberFormat(locale, {
          style: "currency",
          currency: currencyCode,
          minimumFractionDigits: options.minimumFractionDigits + supplementalPrecision,
          maximumFractionDigits: options.maximumFractionDigits + supplementalPrecision,
        });
      }
      ret = numFormat.format(value);
    } catch (err) {
      ret = err.message;
    }
  } else {
    ret = "#NaN!";
  }
  return ret;
};
</script>

<template>
  <AppLayout title="Products">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Products</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- component -->
        <div tabindex="0" class="focus:outline-none">
          <!-- Remove py-8 -->
          <div class="mx-auto container py-8">
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

                    <AddToCart :product-id="product.id" />
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
