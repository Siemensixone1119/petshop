<script setup>
import CartList from '../components/cart/CartList.vue';
import BaseButton from '../components/ui/BaseButton.vue';
</script>

<template>
  <div class="container">
    <h1 class="title">Корзина покупателя</h1>
    <CartList />
    <div class="cart-bottom">
      <div>
        <span>Итого:</span>
        <span>3560 Р</span>
      </div>
      <BaseButton label="Оформить заказ" />
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
}
</style>