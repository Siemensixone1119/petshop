<script setup>
import BaseButton from '../ui/BaseButton.vue';

defineProps({
  product: {
    type: Object
  }
})

const apiUrl = import.meta.env.VITE_API_URL
</script>
<template>
  <div class="product-card">
    <div class="product-card__media">
      <img :src="apiUrl + product.image" alt="">
    </div>
    <div class="product-card__body">
      <h3 class="product-card__title">{{ product.name }}</h3>
      <div class="product-card__body-bottom">
        <span class="product-card__price">{{ product.price }}</span>
        <BaseButton label="В корзину" />
      </div>
    </div>
  </div>
</template>

<style lang="scss" scoped>
@use "../../assets/styles/variables" as *;

.product-card {
  border: 2px solid $color-line;
  border-radius: 8px;
  height: 100%;
  display: flex;
  flex-direction: column;

  &__media {
    background:
      linear-gradient(#fefaf550, #fefaf550),
      url('../../assets/images/bcg/bcg.png');
    background-size: 40%;
    background-position: center;
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
  }

  &__media img {
    height: 170px;
    margin: 0 auto;
    margin: 10px auto;
  }

  &__body {
    display: flex;
    flex-direction: column;
    margin-top: auto;
    justify-content: space-between;
    gap: 10px;
    padding: 10px;
    background-color: $color-light-bg;
    border-bottom-left-radius: 8px;
    border-bottom-right-radius: 8px;
    height: 100%;
  }

  &__title {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }

  &__price {
    font-size: 16px;
    font-weight: 600;
  }

  &__body-bottom {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
  }
}
</style>