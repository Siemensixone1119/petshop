<script setup>
defineProps({
  label: {
    type: String,
    required: true
  },
  second: {
    type: String,
    required: false
  },
  type: {
    type: String,
    default: 'button'
  },
  disabled: {
    type: Boolean,
    default: false
  }
})
</script>

<template>
  <button class="base-button" :class="`${second}`" :type="type" :disabled="disabled">
    {{ label }}
  </button>
</template>

<style lang="scss" scoped>
@use "../../assets/styles/variables" as *;

.base-button {
  background-color: $color-primary;
  color: $color-light-bg;
  padding: 10px 15px;
  border-radius: 8px;

  &:disabled {
    cursor: not-allowed;
    opacity: 0.65;
  }
}

.second-btn {
  background-color: $color-page-bg;
  border: 2px solid $color-line;
  color: $color-text;
}
</style>
