<script setup>
defineProps({
  category: {
    type: Object
  }
})
const apiUrl = import.meta.env.VITE_API_URL
</script>
<template>
  <RouterLink :to="`/catalog/${category.id}`" class="category-card">
    <div class="category-card__image">
      <img :src="apiUrl + category.image" alt="">
    </div>
    <span class="category-card__name">{{ category.name }}</span>
  </RouterLink>
</template>

<style lang="scss" scoped>
@use "../../assets/styles/variables" as *;

.category-card {
  display: flex;
  flex-direction: column;
  align-items: center;
  border: 2px solid $color-line;
  border-radius: 8px;

  &__image {
    display: flex;
    width: 100%;
    height: 70px;
    background:
      linear-gradient(#f9ede180, #f9ede180),
      url('../../assets/images/bcg/bcg.png');
    background-size: 120%;
    background-position: center;
    background-color: $color-light-bg;
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;

    img {
      width: 70px;
      height: 70px;
      margin: 0 auto;
    }
  }

  &__name {
    padding: 3px 0;
    font-weight: 600;
    background-color: $color-light-bg;
    width: 100%;
    text-align: center;
    border-bottom-left-radius: 8px;
    border-bottom-right-radius: 8px;
  }
}
</style>