export default function useProduct() {
    const add = async(productId) => {
        await axios.post(route("products.store"), {
            productId: productId,
        });
    }

    return {
        add,
    }
}

    