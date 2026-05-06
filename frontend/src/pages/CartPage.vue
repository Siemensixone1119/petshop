<script setup>
import { computed, onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';
import CartList from '../components/cart/CartList.vue';
import BaseButton from '../components/ui/BaseButton.vue';
import { orderApi } from '../api/orderApi';
import { useAuth } from '../composables/useAuth';
import { useCart } from '../composables/useCart';

const router = useRouter();
const { isAuthenticated } = useAuth();
const { items, total, isLoading, loadCart, updateItem, removeItem, clearCart } = useCart();
const isOrdering = ref(false);
const message = ref('');
const errorMessage = ref('');

const formattedTotal = computed(() => `${Number(total.value).toLocaleString('ru-RU')} Р`);

const requireAuth = () => {
  if (!isAuthenticated.value) {
    router.push({ name: 'login', query: { redirect: '/cart' } });
    return false;
  }

  return true;
}

const refreshCart = async () => {
  if (!requireAuth()) {
    return;
  }

  try {
    await loadCart();
  } catch {
    clearCart();
  }
}

const incrementItem = (item) => updateItem(item.id, item.quantity + 1);
const decrementItem = (item) => {
  if (item.quantity > 1) {
    updateItem(item.id, item.quantity - 1);
  }
}

const createOrder = async () => {
  if (!requireAuth() || !items.value.length) {
    return;
  }

  isOrdering.value = true;
  errorMessage.value = '';
  message.value = '';

  try {
    const response = await orderApi.create();
    clearCart();
    message.value = `Заказ №${response.data.data.id} оформлен`;
  } catch {
    errorMessage.value = 'Не получилось оформить заказ. Проверьте корзину и попробуйте еще раз.';
  } finally {
    isOrdering.value = false;
  }
}

onMounted(refreshCart);
</script>

<template>
  <div class="container">
    <h1 class="title">Корзина покупателя</h1>
    <p v-if="message" class="cart-message">{{ message }}</p>
    <p v-if="errorMessage" class="cart-error">{{ errorMessage }}</p>
    <p v-if="isLoading" class="cart-state">Загружаем корзину...</p>
    <p v-else-if="!items.length" class="cart-state">Корзина пока пустая</p>
    <CartList
      v-else
      :items="items"
      @increment="incrementItem"
      @decrement="decrementItem"
      @remove="removeItem($event.id)"
    />
    <div v-if="items.length" class="cart-bottom">
      <div class="cart-bottom__total">
        <span>Итого:</span>
        <span>{{ formattedTotal }}</span>
      </div>
      <BaseButton :label="isOrdering ? 'Оформляем...' : 'Оформить заказ'" :disabled="isOrdering" @click="createOrder" />
    </div>
  </div>
</template>
<style lang="scss" scoped>
@use "../assets/styles/variables" as *;

.title {
  font-size: 32px;
  font-family: $font-title;
  margin: 15px 0;
}

.cart-bottom {
  background-color: $color-light-bg;
  border: 2px solid $color-line;
  border-radius: 8px;
  margin-top: 30px;
  padding: 15px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 15px;

  &__total {
    display: flex;
    gap: 10px;
    font-size: 18px;
    font-weight: 700;
  }
}

.cart-state,
.cart-message,
.cart-error {
  background-color: $color-light-bg;
  border: 2px solid $color-line;
  border-radius: 8px;
  padding: 15px;
}

.cart-message {
  color: $color-brand;
  font-weight: 700;
}

.cart-error {
  color: $color-primary;
  font-weight: 700;
}
</style>
