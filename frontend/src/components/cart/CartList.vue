<script setup>
import CartItem from './CartItem.vue';

defineProps({
  items: {
    type: Array,
    default: () => []
  }
})

defineEmits(['increment', 'decrement', 'remove'])
</script>

<template>
  <ul class="cart-list">
    <li v-for="item in items" :key="item.id" class="cart-list__item">
      <CartItem
        :item="item"
        @increment="$emit('increment', item)"
        @decrement="$emit('decrement', item)"
        @remove="$emit('remove', item)"
      />
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
