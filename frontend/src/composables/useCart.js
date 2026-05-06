import { computed, ref } from 'vue'
import { cartApi } from '../api/cartApi'

const cart = ref(null)
const isLoading = ref(false)

function setCart(data) {
  cart.value = data || {
    items: [],
    total_count: 0,
    total_amount: 0,
  }
}

export function useCart() {
  const items = computed(() => cart.value?.items ?? [])
  const count = computed(() => cart.value?.total_count ?? 0)
  const total = computed(() => cart.value?.total_amount ?? 0)

  const loadCart = async () => {
    isLoading.value = true

    try {
      const response = await cartApi.getCart()
      setCart(response.data.data)
      return response
    } catch (error) {
      if ([401, 404].includes(error.response?.status)) {
        setCart(null)
      }

      throw error
    } finally {
      isLoading.value = false
    }
  }

  const addItem = async (productId) => {
    const response = await cartApi.addItem(productId)
    setCart(response.data.data)
    return response
  }

  const updateItem = async (itemId, quantity) => {
    const response = await cartApi.updateItem(itemId, { quantity })
    setCart(response.data.data)
    return response
  }

  const removeItem = async (itemId) => {
    const response = await cartApi.deleteItem(itemId)
    setCart(response.data.data)
    return response
  }

  const clearCart = () => {
    setCart(null)
  }

  return {
    cart,
    items,
    count,
    total,
    isLoading,
    loadCart,
    addItem,
    updateItem,
    removeItem,
    clearCart,
  }
}
