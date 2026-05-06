import { computed, ref } from 'vue'
import { authApi } from '../api/authApi'

const storageKey = 'petshop_user'
const user = ref(readStoredUser())
const isLoading = ref(false)

function readStoredUser() {
  try {
    return JSON.parse(localStorage.getItem(storageKey)) || null
  } catch {
    return null
  }
}

function setUser(value) {
  user.value = value

  if (value) {
    localStorage.setItem(storageKey, JSON.stringify(value))
  } else {
    localStorage.removeItem(storageKey)
  }
}

export function useAuth() {
  const isAuthenticated = computed(() => Boolean(user.value))

  const initAuth = async () => {
    isLoading.value = true

    try {
      const response = await authApi.me()
      setUser(response.data.data)
    } catch {
      setUser(null)
    } finally {
      isLoading.value = false
    }
  }

  const login = async (payload) => {
    const response = await authApi.login(payload)
    setUser(response.data.data)
    return response
  }

  const register = async (payload) => {
    const response = await authApi.register(payload)
    setUser(response.data.data)
    return response
  }

  const logout = async () => {
    try {
      await authApi.logout()
    } finally {
      setUser(null)
    }
  }

  return {
    user,
    isLoading,
    isAuthenticated,
    initAuth,
    login,
    register,
    logout,
  }
}
