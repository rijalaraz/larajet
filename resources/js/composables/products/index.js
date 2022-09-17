import axios from "axios";
import { ref } from 'vue';

export default function useProduct() {

    const products = ref([]);
    const cartCount = ref(0);

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
        cartCount.value = response.data.count;
        return response.data.count;
    }

    const increaseQuantity = async(id) => {
        await axios.post(route('product.increase', id));
        await getProducts();
    }

    const decreaseQuantity = async(id) => {
        await axios.post(route('product.decrease', id));
        await getProducts();
    }

    const destroyProduct = async(id) => {
        await axios.delete(route('products.destroy', id));
        await getProducts();
    }

    return {
        add,
        getCount,
        products,
        getProducts,
        increaseQuantity,
        decreaseQuantity,
        destroyProduct,
        cartCount,
    }
}

    