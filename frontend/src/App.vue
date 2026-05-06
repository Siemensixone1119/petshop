<script setup>
import { ref, computed, onMounted } from 'vue';
import { categoryApi } from './api/categoryApi';
import { useAuth } from './composables/useAuth';
import { useCart } from './composables/useCart';
import Header from './components/layouts/Header.vue';
import Footer from './components/layouts/Footer.vue';

const categories = ref([]);
const { initAuth, isAuthenticated } = useAuth();
const { loadCart, clearCart } = useCart();

const rootCategories = computed(() =>
  categories.value.filter(item => item.parent_id === null || item.parent_id === undefined)
);

onMounted(async () => {
  const categoryResponse = await categoryApi.getAll();
  categories.value = categoryResponse.data.data

  await initAuth();

  if (isAuthenticated.value) {
    try {
      await loadCart();
    } catch {
      clearCart();
    }
  }
})
</script>

<template>
  <Header />
  <RouterView v-slot="{ Component }">
    <component :is="Component" :categories="categories" :all-categories="categories" />
  </RouterView>
  <Footer :categories="rootCategories" />
</template>
