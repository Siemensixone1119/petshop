<script setup>
import { ref, onMounted } from 'vue'
import { categoryApi } from '../api/categoryApi'
// import { productApi } from './api/productApi'

const categories = ref([]);

onMounted(async () => {
    const categoryResponse = await categoryApi.getAll();

    categories.value = categoryResponse.data.data

    console.log(categories.value);
})

</script>

<template>
<div v-for="category in categories" :key="categories.id">
      {{ category.name }}
    </div>
</template>