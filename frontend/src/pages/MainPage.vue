<script setup>
import { ref, onMounted } from 'vue';
import { categoryApi } from '../api/categoryApi';
import { productApi } from '../api/productApi';
import { ArrowRight } from "@lucide/vue";
import Hero from '../components/main_page/Hero.vue';
import CategoryList from '../components/categories/CategoryList.vue';
import ProductList from '../components/products/ProductList.vue';
import DeliveryInfo from '../components/main_page/DeliveryInfo.vue';

defineProps({
    categories: {
        type: Array
    }
})

const products = ref([]);

onMounted(async () => {
    const productResponse = await productApi.getAll();
    products.value = productResponse.data.data.items
})

</script>

<template>
    <Hero />
    <CategoryList :categories="categories" />
    <div>
        <div class="product-list__head container">
            <h2 class="product-list__title">Популярные товары</h2>
            <RouterLink to="catalog" class="product-list__link">
                Смотреть все
                <ArrowRight />
            </RouterLink>
        </div>
        <ProductList :products="products.slice(0, 4)" />
    </div>
    <DeliveryInfo />
</template>

<style lang="scss" scoped>
.product-list__link {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 10px;
    font-weight: 600;
}

.product-list__head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 25px;
    margin-bottom: 10px;
}

.product-list__title {
    font-size: 20px;
    font-weight: 600;
}
</style>
