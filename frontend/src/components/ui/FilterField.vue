<script setup>
import { useRoute } from 'vue-router'

defineProps({
  title: {
    type: String,
    default: ''
  },
  categories: {
    type: Array,
    default: () => []
  },
  activeCategoryId: {
    type: Number,
    default: null
  },
  showAllLink: {
    type: Boolean,
    default: false
  }
})

const route = useRoute()
</script>

<template>
  <div class="filter-field">
    <h2 v-if="title" class="filter-field__title">{{ title }}</h2>
    <ul class="filter-field__list">
      <li v-if="showAllLink" class="filter-field__item">
        <RouterLink
          class="filter-field__link"
          :class="{ 'filter-field__link--active': activeCategoryId === null }"
          :to="{ name: 'catalog', query: route.query }"
        >
          Все товары
        </RouterLink>
      </li>
      <li v-for="item in categories" :key="item.id" class="filter-field__item">
        <RouterLink
          class="filter-field__link"
          :class="{ 'filter-field__link--active': activeCategoryId === Number(item.id) }"
          :to="{ name: 'catalog-category', params: { categoryId: item.id }, query: route.query }"
        >
          {{ item.name }}
        </RouterLink>
      </li>
    </ul>
  </div>
</template>

<style lang="scss" scoped>
@use "../../assets/styles/variables" as *;
.filter-field{
  background-color: $color-light-bg;
  padding: 15px;
  border-radius: 8px;
  border: 2px solid $color-line;
  margin-bottom: 15px;

  &__title{
    font-size: 16px;
    font-weight: 800;
    color: $color-primary;
    margin-bottom: 16px;
  }

  &__list{
    display: flex;
    flex-direction: column;
    gap: 16px;
  }

  &__link {
    display: block;
    font-weight: 700;
    padding: 2px 0;
  }

  &__link--active {
    color: $color-primary;
  }
}
</style>
