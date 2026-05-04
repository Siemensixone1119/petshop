import { api } from './http'

export const productApi = {
    getAll(params = {}) {
        return api.get('/products', { params })
    },

    getByCategory(categoryId, params = {}) {
        return api.get(`/categories/${categoryId}/products`, { params })
    },

    getById(id) {
        return api.get(`/products/${id}`)
    },

    create(categoryId, data) {
        return api.post(`/categories/${categoryId}/products`, data)
    },

    update(categoryId, id, data) {
        return api.put(`/categories/${categoryId}/products/${id}`, data)
    },

    delete(categoryId, id) {
        return api.delete(`/categories/${categoryId}/products/${id}`)
    },
}
