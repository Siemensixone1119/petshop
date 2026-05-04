<script setup>
import { Search } from '@lucide/vue';

defineProps({
  modelValue: {
    type: String,
    default: ''
  }
})

defineEmits(['update:modelValue'])
</script>

<template>
  <div class="search">
    <form class="search__form" role="search" @submit.prevent>
      <input
        class="search__input"
        type="search"
        placeholder="Поиск товаров"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
      >

      <Search class="search__icon" :size="24" color="#270f0A" aria-hidden="true" />
    </form>
  </div>
</template>

<style lang="scss" scoped>
@use "../../assets/styles/variables" as *;

.search {
  flex: 1;
  max-width: 600px;

  &__form {
    position: relative;
    width: 100%;
    height: fit-content;
    max-height: 53px;
    flex: 1;

  }

  &__input {
    width: 100%;
    height: 100%;
    padding: 10px 20px;
    border-radius: 8px;
    border: 1px solid $color-line;
    appearance: none;
  }

  &__input:focus {
    border: 2px solid $color-line;
  }

  &__icon {
    position: absolute;
    bottom: 50%;
    transform: translateY(50%);
    right: 10px;
  }


  @media screen and (max-width: 1200px) {
    max-width: 450px;
  }

  @media screen and (max-width: 576px) {
    max-width: 100%;
  }
}
</style>
