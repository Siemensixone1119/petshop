<script setup>
import { computed, ref, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { productApi } from '../api/productApi';
import ProductList from '../components/products/ProductList.vue';
import SortField from '../components/ui/SortField.vue';
import FilterField from '../components/ui/FilterField.vue';

const props = defineProps({
  categories: {
    type: Array,
    default: () => []
  },
  allCategories: {
    type: Array,
    default: () => []
  }
})

const route = useRoute();
const router = useRouter();

const products = ref([]);
const sort = ref(route.query.sort ?? 'default');

const activeCategoryId = computed(() => {
  const categoryId = route.params.categoryId;
  return categoryId ? Number(categoryId) : null;
})

const activeCategory = computed(() =>
  props.allCategories.find(item => Number(item.id) === activeCategoryId.value) ?? null
)

const normalizeParentId = (value) => {
  if (value === null || value === undefined || value === '' || value === 0 || value === '0') {
    return null
  }

  return Number(value)
}

const rootCategories = computed(() =>
  props.allCategories.filter(item => normalizeParentId(item.parent_id) === null)
)

const subcategories = computed(() => {
  if (!activeCategoryId.value) {
    return props.allCategories.filter(item => normalizeParentId(item.parent_id) !== null)
  }

  if (!activeCategory.value) {
    return []
  }

  const activeParentId = normalizeParentId(activeCategory.value.parent_id)
  const parentId = activeParentId ?? Number(activeCategory.value.id)

  return props.allCategories.filter(item => normalizeParentId(item.parent_id) === Number(parentId))
})

const requestParams = computed(() => {
  const params = {}

  if (route.query.search) {
    params.search = route.query.search
  }

  if (route.query.sort) {
    params.sort = route.query.sort
  }

  if (route.query.page) {
    params.page = route.query.page
  }

  return params
})

const updateQuery = (name, value) => {
  const query = { ...route.query }

  if (!value || value === 'default') {
    delete query[name]
  } else {
    query[name] = value
  }

  delete query.page
  router.push({ name: route.name, params: route.params, query })
}

watch(
  () => route.query.sort,
  value => {
    sort.value = value ?? 'default'
  }
)

watch(sort, value => updateQuery('sort', value))

watch(
  [activeCategoryId, requestParams],
  async () => {
    const productResponse = activeCategoryId.value
      ? await productApi.getByCategory(activeCategoryId.value, requestParams.value)
      : await productApi.getAll(requestParams.value)

    products.value = productResponse.data.data.items
  },
  { immediate: true }
)
</script>

<template>
  <div class="catalog-top container">
    <h1 class="title">Каталог</h1>
    <SortField v-model="sort" />
  </div>
  <div class="catalog-content container">
    <div class="catalog-content__filters">
      <FilterField
        title="Категории"
        :categories="rootCategories"
        :active-category-id="activeCategoryId"
        show-all-link
      />
      <FilterField
        v-if="subcategories.length"
        title="Подкатегории"
        :categories="subcategories"
        :active-category-id="activeCategoryId"
      />
    </div>
    <div class="catalog-content__main">
      <ProductList :products="products" />
    </div>
  </div>
</template>

<style lang="scss" scoped>
@use "../assets/styles/variables" as  *;
.title{
  font-size: 32px;
  font-family: $font-title;
}
.catalog-top {
  display: flex;
  align-items: center;
  gap: 15px;
  margin: 15px 0;

  .sort-select {
    margin-left: auto;
  }
}

.catalog-content {
  display: grid;
  grid-template-columns: 1fr 3fr;
  gap: 15px;

  &__main {
    min-width: 0;
  }
}

@media screen and (max-width: 768px) {
  .catalog-content {
    grid-template-columns: 1fr;
  }
}
</style>
