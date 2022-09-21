<script setup>
import JetButton from "@/Components/Button.vue";
import axios from "axios";
import useProduct from "../composables/products";
import { createToaster } from "@meforma/vue-toaster";

defineProps({
  productId: Number,
});

const emit = defineEmits(["cartCountUpdated"]);

const { add } = useProduct();

const toaster = createToaster();

const addToCart = async (productId) => {
  await axios.get("/sanctum/csrf-cookie");
  await axios
    .get("/api/user")
    .then(async (result) => {
      await add(productId);
      emit("cartCountUpdated");
      toaster.success("Produit ajouté au panier");
    })
    .catch((err) => {});
};
</script>

<template>
  <div class="flex items-center justify-between py-2 mx-auto">
    <JetButton @click="addToCart(productId)"> Ajouter au panier </JetButton>
  </div>
</template>
