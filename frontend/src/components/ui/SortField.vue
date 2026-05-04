<script setup>
defineProps({
  modelValue: {
    type: String,
    default: 'default'
  }
})

defineEmits(['update:modelValue'])
</script>

<template>
  <div class="sort-select">
    <select
      class="sort-select__field"
      name="sort"
      id="sort"
      :value="modelValue"
      @change="$emit('update:modelValue', $event.target.value)"
    >
      <option class="sort-select__option" value="default">По умолчанию</option>
      <option class="sort-select__option" value="date-desc">Сначала новые</option>
      <option class="sort-select__option" value="date-asc">Сначала старые</option>
      <option class="sort-select__option" value="price-desc">Сначала дороже</option>
      <option class="sort-select__option" value="price-asc">Сначала дешевле</option>
      <option class="sort-select__option" value="name-asc">С начала алфавита</option>
      <option class="sort-select__option" value="name-desc">С конца алфавита</option>
    </select>
  </div>
</template>

<style lang="scss" scoped>
@use "../../assets/styles/variables" as *;

.sort-select {

  &__field {
    padding: 10px;
    border: 1px solid $color-line;
    border-radius: 8px;
  }
}
</style>
