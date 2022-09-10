<script setup>
import JetButton from "@/Components/Button.vue";
import axios from "axios";
import useProduct from "../composables/products";

defineProps({
  productId: Number,
});

const { add } = useProduct();

const addtoCart = async (productId) => {
  await axios.get("/sanctum/csrf-cookie");
  await axios
    .get("/api/user")
    .then(async (result) => {
      await add(productId);
    })
    .catch((err) => {});
};
</script>

<template>
  <div class="flex items-center justify-between py-4">
    <JetButton @click="addtoCart(productId)"> Ajouter au panier </JetButton>
  </div>
</template>
