<script setup>
import { ref, computed, onMounted } from 'vue';
import { categoryApi } from './api/categoryApi';
import Header from './components/layouts/Header.vue';
import Footer from './components/layouts/Footer.vue';

const categories = ref([]);

onMounted(async () => {
  const categoryResponse = await categoryApi.getAll();
  categories.value = categoryResponse.data.data
})

const rootCategories = computed(() =>
  categories.value.filter(item => item.parent_id === null)
)
</script>

<template>
  <Header />
  <RouterView v-slot="{ Component }">
    <component :is="Component" :categories="rootCategories" :all-categories="categories" />
  </RouterView>
  <Footer :categories="rootCategories" />
</template>
