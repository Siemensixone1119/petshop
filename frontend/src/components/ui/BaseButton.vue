<script setup>
defineProps({
  label: {
    type: String,
    required: true
  },
  second: {
    type: String,
    required: false
  }
})
</script>

<template>
  <button class="base-button" :class="`${second}`">
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
}

.second-btn {
  background-color: $color-page-bg;
  border: 2px solid $color-line;
  color: $color-text;
}
</style>