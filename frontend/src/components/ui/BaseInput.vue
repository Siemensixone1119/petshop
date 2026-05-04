<script setup>
defineProps({
  type: {
    type: String
  },
  placeholder: {
    type: String
  }
})
</script>

<template>
  <input class="input" :type="`${type}`" :placeholder="`${placeholder}`">
</template>

<style lang="scss" scoped>
@use "../../assets/styles/variables" as *;

.input {
  width: 100%;
  height: 100%;
  padding: 10px 20px;
  border-radius: 8px;
  border: 1px solid $color-line;
  appearance: none;
}

.input:focus {
  border: 2px solid $color-line;
}
</style>
