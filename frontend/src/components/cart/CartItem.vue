<script setup>
import { Trash } from "@lucide/vue";
defineProps({
  item: {
    type: Object,
    required: true
  }
})

defineEmits(['increment', 'decrement', 'remove'])

const apiUrl = import.meta.env.VITE_API_URL
</script>

<template>
  <div class="cart-item">
    <div class="cart-item__media">
      <img v-if="item.product?.image" :src="apiUrl + item.product.image" :alt="item.product.name">
    </div>
    <div class="cart-item__body">
      <span class="cart-item__name">{{ item.product?.name || 'Товар' }}</span>
      <div class="cart-item__counter">
        <button class="cart-item__minus" :disabled="item.quantity <= 1" @click="$emit('decrement')">-</button>
        <div class="cart-item__stock">{{ item.quantity }}</div>
        <button class="cart-item__plus" @click="$emit('increment')">+</button>
      </div>
      <span class="cart-item__price">
        {{ (Number(item.product?.price || 0) * item.quantity).toLocaleString('ru-RU') }} Р
      </span>
      <button class="cart-item__remove" type="button" @click="$emit('remove')" aria-label="Удалить товар">
        <Trash />
      </button>
    </div>
  </div>
</template>

<style lang="scss" scoped>
@use "../../assets/styles/variables" as *;

.cart-item {
  background-color: $color-light-bg;
  border-radius: 8px;
  border: 2px solid $color-line;
  display: flex;
  font-weight: 600;
  font-size: 16px;

  &__media {
    width: 100px;
    height: 100px;
    background:
      linear-gradient(#fefaf550, #fefaf550),
      url('../../assets/images/bcg/bcg.png');
    background-size: 45%;
    background-position: center;
    border-top-left-radius: 8px;
    border-bottom-left-radius: 8px;
    flex: 0 0 100px;
    display: grid;
    place-items: center;
    overflow: hidden;

    img {
      max-height: 90px;
      object-fit: contain;
    }
  }

  &__body {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    padding: 20px;
    gap: 15px;
  }

  &__name {
    flex: 1;
  }

  &__counter {
    display: flex;
    border: 1px solid $color-line;
    border-radius: 8px;

    .cart-item__plus,
    .cart-item__stock,
    .cart-item__minus {
      padding: 10px 15px;
      line-height: 1;
      border: 1px solid $color-line;
      min-width: 42px;
      text-align: center;
    }

    button:disabled {
      cursor: not-allowed;
      opacity: 0.5;
    }
  }

  &__minus {
    border-top-left-radius: 8px;
    border-bottom-left-radius: 8px;
  }

  &__plus {
    border-top-right-radius: 8px;
    border-bottom-right-radius: 8px;
  }

  &__price {
    min-width: 90px;
    text-align: right;
  }

  &__remove {
    color: $color-primary;
  }

  @media screen and (max-width: 768px) {
    &__body {
      align-items: flex-start;
      flex-direction: column;
    }

    &__price {
      text-align: left;
    }
  }
}
</style>
