<script setup>
defineProps({
  modelValue: {
    type: [String, Number],
    default: ''
  },
  type: {
    type: String,
    default: 'text'
  },
  placeholder: {
    type: String,
    default: ''
  }
})

defineEmits(['update:modelValue'])
</script>

<template>
  <input
    class="input"
    :type="type"
    :placeholder="placeholder"
    :value="modelValue"
    @input="$emit('update:modelValue', $event.target.value)"
  >
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
