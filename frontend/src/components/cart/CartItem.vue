<script setup>
import { Trash } from "@lucide/vue";
defineProps({
  item: {
    type: String
  }
})
</script>

<template>
  <div class="cart-item">
    <div class="cart-item__media"></div>
    <div class="cart-item__body">
      <span class="cart-item__name">Кошачий корм</span>
      <div class="cart-item__counter">
        <button class="cart-item__plus">+</button>
        <div class="cart-item__stock">1</div>
        <button class="cart-item__minus">-</button>
      </div>
      <span class="cart-item__price">1200.00 Р</span>
      <div class="cart-item__remove">
        <Trash />
      </div>
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
    background-color: aliceblue;
    border-top-left-radius: 8px;
    border-bottom-left-radius: 8px;
  }

  &__body {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    padding: 20px;
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
    }
  }

  &__plus {
    border-top-left-radius: 8px;
    border-bottom-left-radius: 8px;
  }

  &__minus {
    border-top-right-radius: 8px;
    border-bottom-right-radius: 8px;
  }
}
</style>