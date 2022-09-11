<script setup>
import JetButton from "@/Components/Button.vue";
import axios from "axios";
import useProduct from "../composables/products";

defineProps({
  productId: Number,
});

const emit = defineEmits(["cartCountUpdated"]);

const { add } = useProduct();

const addToCart = async (productId) => {
  await axios.get("/sanctum/csrf-cookie");
  await axios
    .get("/api/user")
    .then(async (result) => {
      let cartCount = await add(productId);
      emit("cartCountUpdated", cartCount);
    })
    .catch((err) => {});
};
</script>

<template>
  <div class="flex items-center justify-between py-4">
    <JetButton @click="addToCart(productId)"> Ajouter au panier </JetButton>
  </div>
</template>
