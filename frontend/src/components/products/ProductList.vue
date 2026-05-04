<script setup>
import ProductCard from "../products/ProductCard.vue"
defineProps({
  products: {
    type: Array
  }
})
</script>
<template>
  <ul class="product-list container">
    <li v-for="item in products" :key="item.id">
      <ProductCard :product="item" />
    </li>
  </ul>
</template>

<style lang="scss" scoped>
.product-list {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 15px;

  @media screen and (max-width: 768px) {
    grid-template-columns: repeat(2, 1fr);
  }

  @media screen and (max-width: 576px) {
    grid-template-columns: repeat(1, 1fr);
  }
}

.catalog-content {
  .product-list {
    padding: 0;
    display: grid;
    grid-template-columns: repeat(3, 1fr);

    @media screen and (max-width: 768px) {
      grid-template-columns: repeat(2, 1fr);
    }

    @media screen and (max-width: 576px) {
      grid-template-columns: repeat(1, 1fr);
    }
  }
}
</style>