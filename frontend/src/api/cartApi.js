import { api } from './http'

export const cartApi = {
    getCart() {
        return api.get('/cart')
    },

    addItem(productId, data = {}) {
        return api.post(`/cart/items/${productId}`, data)
    },

    updateItem(id, data) {
        return api.put(`/cart/items/${id}`, data)
    },

    deleteItem(id) {
        return api.delete(`/cart/items/${id}`)
    },
}