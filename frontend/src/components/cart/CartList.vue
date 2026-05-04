<script setup>
import CartItem from './CartItem.vue';
</script>

<template>
  <ul class="cart-list">
    <li class="cart-list__item">
      <CartItem />
    </li>
    <li class="cart-list__item">
      <CartItem />
    </li>
    <li class="cart-list__item">
      <CartItem />
    </li>
    <li class="cart-list__item">
      <CartItem />
    </li>
    <li class="cart-list__item">
      <CartItem />
    </li>
  </ul>
</template>

<style lang="scss" scoped>

.cart-list {
  display: flex;
  flex-direction: column;
  gap: 10px;
}
</style>