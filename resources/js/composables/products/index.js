import axios from "axios";

export default function useProduct() {
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
        getCount
    }
}

    