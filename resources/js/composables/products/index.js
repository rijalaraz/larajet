import axios from "axios";
import { ref } from 'vue';

export default function useProduct() {

    const products = ref([]);

    const getProducts = async() => {
        let response = await axios.get(route("products.index"));
        products.value = response.data.cartContent;
    }

    const add = async(productId) => {
        let response = await axios.post(route("products.store"), {
            productId: productId,
        });
        return response.data.count;
    }

    const getCount = async() => {
        let response = await axios.get(route('products.count'));
        return response.data.count;
    }

    return {
        add,
        getCount,
        products,
        getProducts,
    }
}

    