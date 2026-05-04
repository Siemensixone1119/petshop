<script setup>
import { Heart } from '@lucide/vue';
defineProps({
  label: {
    type: String,
    required: true
  },
  count: {
    type: Number,
    default: 0
  },
  icon: {
    type: String
  }
})
</script>

<template>
  <button class="header-action">
    <span class="header-action__icon">
      <slot />
    </span>
    <span class="header-action__label">
      {{ label }}
    </span>
    <span v-if="count > 0" class="header-action__badge">
      {{ count }}
    </span>
  </button>
</template>

<style lang="scss" scoped>
@use "../../assets/styles/variables" as *;

.header-action {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: space-between;
  gap: 4px;
  position: relative;

  __icon svg {
    width: 28px;
    height: 28px;
  }

  &__badge {
    position: absolute;
    top: 0;
    right: 0;
    background-color: $color-primary;
    color: $color-light-bg;
    line-height: 1;
    padding: 3px 5px;
    border-radius: 12px;
    font-size: 10px;


  }

  @media screen and (max-width: 1024px) {
    &__label {
      font-size: 12px;
    }
  }

  @media screen and (max-width: 768px) {
    &__label {
      display: none;
    }
  }
}
</style>