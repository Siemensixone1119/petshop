<script setup>
import { computed } from "vue";
import CategoryCard from './CategoryCard.vue';

const props = defineProps({
  categories: Array,
  required: true
})
</script>
<template>
  <ul class="category-list container">
    <li v-for="item in categories" :key="item.id">
      <CategoryCard :category="item" />
    </li>
  </ul>
</template>

<style lang="scss" scoped>
.category-list {
  display: grid;
  grid-template-columns: repeat(6, 1fr);
  gap: 15px;
  margin-top: 10px;

  @media screen and (max-width: 768px) {
    grid-template-columns: repeat(3, 1fr);
  }
}
</style>