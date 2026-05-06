<script setup>
import { computed, onMounted, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import BaseButton from '../components/ui/BaseButton.vue';
import { productApi } from '../api/productApi';
import { useAuth } from '../composables/useAuth';
import { useCart } from '../composables/useCart';

const route = useRoute();
const router = useRouter();
const { isAuthenticated } = useAuth();
const { addItem } = useCart();
const apiUrl = import.meta.env.VITE_API_URL;

const product = ref(null);
const isLoading = ref(false);
const isAdding = ref(false);
const message = ref('');

const formattedPrice = computed(() =>
  product.value ? `${Number(product.value.price).toLocaleString('ru-RU')} Р` : ''
);

const loadProduct = async () => {
  isLoading.value = true;

  try {
    const response = await productApi.getById(route.params.id);
    product.value = response.data.data;
  } finally {
    isLoading.value = false;
  }
}

const addToCart = async () => {
  if (!isAuthenticated.value) {
    router.push({ name: 'login', query: { redirect: route.fullPath } });
    return;
  }

  isAdding.value = true;
  message.value = '';

  try {
    await addItem(product.value.id);
    message.value = 'Товар добавлен в корзину';
  } finally {
    isAdding.value = false;
  }
}

onMounted(loadProduct);
</script>

<template>
  <main class="product-page container">
    <p v-if="isLoading" class="product-state">Загружаем товар...</p>
    <section v-else-if="product" class="product-detail">
      <div class="product-detail__media">
        <img v-if="product.image" :src="apiUrl + product.image" :alt="product.name">
      </div>
      <div class="product-detail__content">
        <RouterLink class="product-detail__back" to="/catalog">Назад в каталог</RouterLink>
        <h1 class="product-detail__title">{{ product.name }}</h1>
        <p v-if="product.description" class="product-detail__description">{{ product.description }}</p>
        <span class="product-detail__price">{{ formattedPrice }}</span>
        <p v-if="message" class="product-detail__message">{{ message }}</p>
        <BaseButton :label="isAdding ? 'Добавляем...' : 'В корзину'" :disabled="isAdding" @click="addToCart" />
      </div>
    </section>
    <p v-else class="product-state">Товар не найден</p>
  </main>
</template>

<style lang="scss" scoped>
@use "../assets/styles/variables" as *;

.product-page {
  padding-top: 30px;
}

.product-detail {
  display: grid;
  grid-template-columns: minmax(260px, 420px) 1fr;
  gap: 30px;
  align-items: stretch;

  &__media {
    background:
      linear-gradient(#fefaf550, #fefaf550),
      url('../assets/images/bcg/bcg.png');
    background-size: 45%;
    background-position: center;
    background-color: $color-light-bg;
    border: 2px solid $color-line;
    border-radius: 8px;
    min-height: 360px;
    display: grid;
    place-items: center;
    padding: 24px;

    img {
      max-height: 310px;
      object-fit: contain;
    }
  }

  &__content {
    background-color: $color-light-bg;
    border: 2px solid $color-line;
    border-radius: 8px;
    padding: 24px;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 16px;
    min-height: 360px;
  }

  &__back {
    color: $color-primary;
    font-weight: 700;
  }

  &__title {
    font-family: $font-title;
    font-size: 32px;
  }

  &__description {
    font-size: 16px;
    flex: 1;
  }

  &__price {
    font-size: 24px;
    font-weight: 800;
  }

  &__message {
    color: $color-brand;
    font-weight: 700;
  }
}

.product-state {
  background-color: $color-light-bg;
  border: 2px solid $color-line;
  border-radius: 8px;
  padding: 15px;
}

@media screen and (max-width: 768px) {
  .product-detail {
    grid-template-columns: 1fr;
  }
}
</style>
