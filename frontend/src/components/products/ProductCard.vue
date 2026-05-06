<script setup>
import { ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import BaseButton from '../ui/BaseButton.vue';
import { useAuth } from '../../composables/useAuth';
import { useCart } from '../../composables/useCart';

const props = defineProps({
  product: {
    type: Object
  }
})

const apiUrl = import.meta.env.VITE_API_URL
const route = useRoute();
const router = useRouter();
const { isAuthenticated } = useAuth();
const { addItem } = useCart();
const isAdding = ref(false);
const buttonLabel = ref('В корзину');

const openProduct = () => {
  router.push({ name: 'product', params: { id: props.product.id } });
}

const addToCart = async () => {
  if (!isAuthenticated.value) {
    router.push({ name: 'login', query: { redirect: route.fullPath } });
    return;
  }

  isAdding.value = true;

  try {
    await addItem(props.product.id);
    buttonLabel.value = 'Добавлено';
    window.setTimeout(() => {
      buttonLabel.value = 'В корзину';
    }, 1200);
  } catch (error) {
    if (error.response?.status === 401) {
      router.push({ name: 'login', query: { redirect: route.fullPath } });
    }
  } finally {
    isAdding.value = false;
  }
}
</script>
<template>
  <div class="product-card" role="link" tabindex="0" @click="openProduct" @keydown.enter.self="openProduct">
    <div class="product-card__media">
      <img :src="apiUrl + product.image" alt="">
    </div>
    <div class="product-card__body">
      <h3 class="product-card__title">{{ product.name }}</h3>
      <div class="product-card__body-bottom">
        <span class="product-card__price">{{ Number(product.price).toLocaleString('ru-RU') }} Р</span>
        <BaseButton :label="isAdding ? 'Добавляем...' : buttonLabel" :disabled="isAdding" @click.stop="addToCart" />
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
  cursor: pointer;

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
