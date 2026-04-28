import { api } from './http'

export const categoryApi = {
    getAll() {
        return api.get('/categories')
    },

    getById(id) {
        return api.get(`/categories/${id}`)
    },

    create(data) {
        return api.post('/categories', data)
    },

    update(id, data) {
        return api.put(`/categories/${id}`, data)
    },

    delete(id) {
        return api.delete(`/categories/${id}`)
    },
}